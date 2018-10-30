<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Log\ShellLogService;
use App\Services\AuctionInAdvanceAnnounceService;

class AuctionInAdvanceAnnounce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auction_in_advance_announce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '入札通知メール送信処理';

    /**
     * @var ShellLogService
     */
    protected $shellLog;
    /**
     * @var AuctionInAdvanceAnnounceService
     */
    protected $service;

    /**
     * Create a new command instance.
     *
     * @param AuctionInAdvanceAnnounceService $service
     * @param ShellLogService                 $shellLog
     */
    public function __construct(AuctionInAdvanceAnnounceService $service, ShellLogService $shellLog)
    {
        parent::__construct();
        $this->shellLog   = $shellLog;
        $this->service    = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->shellLog->log('事前周知メール処理start');

            $this->shellLog->log('至急案件処理start');
            $this->service->executeImmediately();
            $this->shellLog->log('至急案件処理end');

            $this->shellLog->log('通常案件処理start');
            $this->service->executeNormal();
            $this->shellLog->log('通常案件処理end');

            $this->shellLog->log('事前周知メール処理end');
        } catch (Exception $e) {
            $this->shellLog->log($e);
        }
    }
}
