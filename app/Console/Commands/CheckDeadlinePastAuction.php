<?php

namespace App\Console\Commands;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Services\Command\CheckDeadlinePastAuctionService;
use App\Services\Log\ShellLogService;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;

class CheckDeadlinePastAuction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_deadline_past_auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check deadline past auction';

    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;

    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;

    /**
     * @var CheckDeadlinePastAuctionService
     */
    protected $checkPastAuctionService;

    /**
     * @var ShellLogService
     */
    protected $shellLog;

    /**
     * Default user
     * @var string
     */
    protected $user = 'system';

    /**
     * CheckDeadlinePastAuction constructor.
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param CheckDeadlinePastAuctionService $checkPastAuctionService
     * @param ShellLogService $shellLogService
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        CheckDeadlinePastAuctionService $checkPastAuctionService,
        ShellLogService $shellLogService
    ) {
        parent::__construct();
        $this->demandInfoRepository = $demandInfoRepository;
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->checkPastAuctionService = $checkPastAuctionService;
        $this->shellLog = $shellLogService;
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');

        try {
            DB::beginTransaction();
            $this->shellLog->log(__METHOD__ . ": オークション流れ案件処理start \n");
            $this->info(__METHOD__ . ": オークション流れ案件処理start");

            $subCommissionInfo = $this->commissionInfoRepository->subCommissionInfo();
            $data = $this->demandInfoRepository->commandCheckDeadlinePastAuction($subCommissionInfo);
            $total = $data->count();
            $tmp = [];
            $bar = $this->output->createProgressBar($total);
            $this->line("Set data begin.");
            foreach ($data as $key => $row) {
                // Manual selection is made in the order in which the bid was delivered
                $commissionInfos = $this->checkPastAuctionService->getAuctionCommissionList($row);

                // Log error when corresponding_contens > 1000
                if (!empty($commissionInfos['corresponding_contens'][0]) &&
                    mb_strlen($commissionInfos['corresponding_contens'][0]) > 1000) {
                    $this->shellLog->log("案件ID#".$row->id."：エラーが発生しました。コンテンツの文字数は". mb_strlen($commissionInfos['corresponding_contens'][0]) . "です。");
                    continue;
                }

                if ($row->selection_system == getDivValue('selection_type', 'automatic_auction_selection')) {
                    $userId = 'AutomaticAuction';
                } else {
                    $userId = $this->user;
                }
                $row->modified_user_id = $userId;
                $row->modified = date('Y-m-d H:i:s');

                // In case of automatic selection (automatic bidding ceremony)
                $row->auction = 1;
                $row->selection_system = getDivValue('selection_type', 'manual_selection');
                $demandStatus = getDivValue('demand_status', 'no_selection');

                if (!empty($commissionInfos['commissionInfo'])) {
                    foreach ($commissionInfos['commissionInfo'] as $value) {
                        if ($value['lost_flg'] == 0) {
                            $demandStatus = getDivValue('demand_status', 'agency_before');
                            break;
                        }
                    }
                }

                $row->demand_status = $demandStatus;
                $row->priority = 0;
                $row = $row->toArray();
                $tmp = $this->checkPastAuctionService->setData($tmp, $key, $row, $userId, $commissionInfos);
                $bar->advance();
            }
            $bar->finish();
            $this->line("\n");
            $this->line("Save data begin!");
            $this->checkPastAuctionService->saveData($tmp);
            DB::commit();
            $this->line("Save done!");
            $this->info(__METHOD__ . ": オークション流れ案件処理end");
            $this->shellLog->log(__METHOD__ . ": オークション流れ案件処理end \n");
        } catch (Exception $e) {
            DB::rollBack();
            $this->error(__METHOD__ . ': Error - ' . $e->getMessage());
            $this->shellLog->log(__METHOD__ . ': Error - ' . $e->getMessage());
        }
    }
}
