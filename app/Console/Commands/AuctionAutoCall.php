<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Job\AuctionAutoCallService;
use App\Services\Log\ShellLogService;

/**
 *
 */
const TYPE = ['first', 'called'];

/**
 * Class AuctionAutoCall
 *
 * @package App\Console\Commands
 */
class AuctionAutoCall extends Command
{
    /**
     * @var AuctionAutoCallService
     */
    protected $service;
    /**
     * @var ShellLogService
     */
    protected $shellLog;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auction_auto_call {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auction auto call command with type argument value called';


    /**
     * AuctionAutoCall constructor.
     * @param AuctionAutoCallService $service
     * @param ShellLogService $shellLogService
     */
    public function __construct(AuctionAutoCallService $service, ShellLogService $shellLogService)
    {
        parent::__construct();
        $this->service = $service;
        $this->shellLog = $shellLogService;
    }


    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $this->shellLog->log('入札案件オートコール処理 start', 200);
            $autoCallFlg = ($this->arguments()['type'] == 'called') ? true : false;
            $this->service->execute($autoCallFlg);
            $this->shellLog->log('入札案件オートコール処理 end', 200);
        } catch (\Exception $exception) {
            $this->shellLog->log($exception->getMessage(), 400);
        }
    }
}
