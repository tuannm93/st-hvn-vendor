<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenTrackingService;
use Illuminate\Console\Command;

class CyzenTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cyzen tracking location';

    /**
     * @var \App\Services\Cyzen\CyzenTrackingService $service
     */
    protected $service;

    /**
     * CyzenTracking constructor.
     *
     * @param \App\Services\Cyzen\CyzenTrackingService $cyzenTrackingService
     */
    public function __construct(CyzenTrackingService $cyzenTrackingService)
    {
        parent::__construct();
        $this->service = $cyzenTrackingService;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->service->handle();
    }
}
