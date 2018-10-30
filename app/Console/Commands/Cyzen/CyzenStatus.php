<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenStatusService;
use Illuminate\Console\Command;

class CyzenStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list status cyzen';

    /**
     * @var \App\Services\Cyzen\CyzenStatusService $cyzenStatusService
     */
    protected $cyzenStatusService;

    /**
     * Create a new command instance.
     *
     * @param CyzenStatusService $cyzenStatusService
     */
    public function __construct(CyzenStatusService $cyzenStatusService)
    {
        parent::__construct();
        $this->cyzenStatusService = $cyzenStatusService;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->cyzenStatusService->handle();
    }
}
