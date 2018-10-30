<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenScheduleService;
use Illuminate\Console\Command;

class CyzenSchedule extends Command
{
    /**
     * @var \App\Services\Cyzen\CyzenScheduleService $service
     */
    protected $service;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data of cyzen schedules';

    /**
     * CyzenSchedule constructor.
     * @param \App\Services\Cyzen\CyzenScheduleService $service
     */
    public function __construct(CyzenScheduleService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->service->handle();
    }
}
