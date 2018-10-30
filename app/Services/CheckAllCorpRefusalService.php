<?php

namespace App\Services;

use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandCorrespondsRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Services\Command\CheckDeadlinePastAuctionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckAllCorpRefusalService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var DemandCorrespondsRepositoryInterface
     */
    protected $demandCorrespondsRepository;
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepo;
    /**
     * @var CheckDeadlinePastAuctionService
     */
    protected $checkPastAuctionService;
    /**
     * @var string
     */
    protected $user = 'system';

    /**
     * CheckAllCorpRefusalService constructor.
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param DemandCorrespondsRepositoryInterface $demandCorrespondsRepository
     * @param AuctionInfoRepositoryInterface $auctionInfoRepo
     * @param CheckDeadlinePastAuctionService $checkPastAuctionService
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        DemandCorrespondsRepositoryInterface $demandCorrespondsRepository,
        AuctionInfoRepositoryInterface $auctionInfoRepo,
        CheckDeadlinePastAuctionService $checkPastAuctionService
    ) {
        $this->demandInfoRepository = $demandInfoRepository;
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->demandCorrespondsRepository = $demandCorrespondsRepository;
        $this->auctionInfoRepo = $auctionInfoRepo;
        $this->checkPastAuctionService = $checkPastAuctionService;
    }

    /**
     * @param $command
     */
    public function execute($command)
    {
        try {
            $command->line("Set data begin.");

            $subCommissionInfo = $this->commissionInfoRepository->subCommissionInfo();
            $data = $this->demandInfoRepository->commandCheckCorpRefusal($subCommissionInfo);
            $tmp = [];
            DB::beginTransaction();
            foreach ($data as $key => $row) {
                $countRefusal = $this->auctionInfoRepo->countRefusal($row->id)->r_count;
                if ($countRefusal == 0) {
                    $commissionInfos = $this->checkPastAuctionService->getAuctionCommissionList($row);
                    if ($row->selection_system == getDivValue('selection_type', 'automatic_auction_selection')) {
                        $userId = 'AutomaticAuction';
                    } else {
                        $userId = $this->user;
                    }
                    $row->modified_user_id = $userId;
                    $row->modified = date('Y-m-d H:i:s');
                    $row->auction = 1;
                    $row->selection_system = getDivValue('selection_type', 'manual_selection');
                    $row->priority = 0;
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
                    $row = $row->toArray();
                    $tmp = $this->setData($tmp, $row, $userId, $commissionInfos);
                }
            }
            $this->saveData($tmp);
            DB::commit();
            $command->line("Save done!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            $command->line("Something bad happened!");
        }
    }

    /**
     * @param $tmp
     * @param $row
     * @param $userId
     * @param $commissionInfos
     * @return mixed
     */
    private function setData($tmp, $row, $userId, $commissionInfos)
    {
        $tmp['demandInfo'][] = $row;

        if (isset($commissionInfos['commissionInfo'])) {
            foreach ($commissionInfos['commissionInfo'] as $commissionInfo) {
                unset($commissionInfo['sort_push_time']);
                unset($commissionInfo['sort_commission_unit_price_category']);
                unset($commissionInfo['sort_commission_count_category']);
                $tmp['commissionInfo'][] = $commissionInfo;
            }
        }

        if (!empty($commissionInfos['corresponding_contens'][0])) {
            $tmp['demandCorrespond'][] = [
                'demand_id' => $row['id'],
                'corresponding_contens' => $commissionInfos['corresponding_contens'][0],
                'responders' => '入札流れ',
                'correspond_datetime' => date('Y-m-d H:i:s'),
                'created_user_id' => $userId,
                'created' => date('Y-m-d H:i:s'),
                'modified_user_id' => $userId,
                'modified' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($commissionInfos['corresponding_contens'][1])) {
            $tmp['demandCorrespond'][] = [
                'demand_id' => $row['id'],
                'corresponding_contens' => $commissionInfos['corresponding_contens'][1],
                'responders' => '自動選定',
                'correspond_datetime' => date('Y-m-d H:i:s'),
                'created_user_id' => $userId,
                'created' => date('Y-m-d H:i:s'),
                'modified_user_id' => $userId,
                'modified' => date('Y-m-d H:i:s'),
            ];
        }

        return $tmp;
    }

    /**
     * @param $tmp
     */
    private function saveData($tmp)
    {
        if (!empty($tmp['demandInfo'])) {
            $this->demandInfoRepository->insertOrUpdateMultiData($tmp['demandInfo']);
        }

        if (!empty($tmp['commissionInfo'])) {
            $this->commissionInfoRepository->insertOrUpdateMultiData($tmp['commissionInfo']);
        }

        if (!empty($tmp['demandCorrespond'])) {
            $this->demandCorrespondsRepository->insertOrUpdateMultiData($tmp['demandCorrespond']);
        }
    }
}
