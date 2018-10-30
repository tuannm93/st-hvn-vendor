<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenScheduleService;
use Illuminate\Console\Command;

class CyzenScheduleDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_schedule_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use delete schedule by manual';
    /**
     * @var $cyzenScheduleRepository
     */
    protected $service;
    /**
     * @var string $path
     */
    private $path = '/webapi/v0/schedules';

    /**
     * Create a new command instance.
     *
     * @param CyzenScheduleService $service
     * @throws \Exception
     */
    public function __construct(CyzenScheduleService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->service->deleteScheduleManual();
    }
}
