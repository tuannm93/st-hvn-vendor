<?php
namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenHistoryService;
use Illuminate\Console\Command;

class CyzenHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from history api';

    /**
     * @var \App\Services\Cyzen\CyzenHistoryService $cyzenHistoryService
     */
    protected $cyzenHistoryService;

    /**
     * CyzenHistory constructor.
     *
     * @param \App\Services\Cyzen\CyzenHistoryService $cyzenHistoryService
     */
    public function __construct(CyzenHistoryService $cyzenHistoryService)
    {
        parent::__construct();
        $this->cyzenHistoryService = $cyzenHistoryService;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->cyzenHistoryService->handle();
    }
}
