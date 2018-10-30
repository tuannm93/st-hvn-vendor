<?php

namespace App\Console\Commands;

use App\Services\Demand\DemandInfoService;
use App\Services\Job\AuctionAutoCallService;
use App\Services\Log\ShellLogService;
use Illuminate\Console\Command;

class DemandGuideSendMail extends Command
{
    /**
     * @var ShellLogService
     */
    protected $shellLog;

    /**
     * @var DemandInfoService
     */
    protected $demandInfoService;

    /**
     * @var AuctionAutoCallService
     */
    protected $auctionAutoCallService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:demand_guide_send_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '案件案内メール処理';

    /**
     * DemandGuideSendMail constructor.
     *
     * @param DemandInfoService $demandInfoService
     * @param ShellLogService $shellLog
     * @param AuctionAutoCallService $auctionAutoCallService
     */
    public function __construct(
        DemandInfoService $demandInfoService,
        ShellLogService $shellLog,
        AuctionAutoCallService $auctionAutoCallService
    ) {
        parent::__construct();
        $this->shellLog = $shellLog;
        $this->demandInfoService = $demandInfoService;
        $this->auctionAutoCallService = $auctionAutoCallService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $this->shellLog->log('案件案内メール処理start');
            $this->demandInfoService->executeDemandGuideSendMail();
            $this->auctionAutoCallService->execute();
            $this->shellLog->log('案件案内メール処理end');
        } catch (\Exception $exception) {
            $this->shellLog->log($exception);
        }
    }
}
