<?php

namespace App\Console\Commands;

use App\Services\Job\PeriodicallyService;
use Illuminate\Console\Command;

/**
 * Class AuctionAutoCall
 *
 * @package App\Console\Commands
 */
class Periodically extends Command
{
    /**
     * @var PeriodicallyService
     */
    protected $periodicallyService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:periodically';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'フラグ初期化処理';

    /**
     * AuctionAutoCall constructor.
     *
     * @param \App\Services\Job\PeriodicallyService $service
     */
    public function __construct(PeriodicallyService $service)
    {
        parent::__construct();
        $this->periodicallyService = $service;
    }


    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->periodicallyService->execute();
    }
}
