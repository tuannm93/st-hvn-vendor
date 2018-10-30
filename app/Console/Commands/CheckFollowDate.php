<?php

namespace App\Console\Commands;

use App\Services\Demand\DemandInfoMailService;
use Illuminate\Console\Command;
use App\Services\Log\ShellLogService;

class CheckFollowDate extends Command
{
    /**
     * @var DemandInfoMailService
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
    protected $signature = 'command:check_follow_date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '後追い日時を過ぎた場合、後追い日時を削除する';

    /**
     * Create a new command instance.
     *
     * @param DemandInfoMailService $service
     * @param ShellLogService   $shellLog
     */
    public function __construct(
        DemandInfoMailService $service,
        ShellLogService $shellLog
    ) {
        parent::__construct();
        $this->service = $service;
        $this->shellLog = $shellLog;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->shellLog->log('後追い日時削除処理 start');
            $this->service->executeCheckFollowDate();
            $this->shellLog->log('後追い日時削除処理 end');
        } catch (\Exception $exception) {
            $this->shellLog->log($exception);
        }
    }
}
