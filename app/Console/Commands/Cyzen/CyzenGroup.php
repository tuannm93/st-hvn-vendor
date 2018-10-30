<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenGroupService;
use Illuminate\Console\Command;

class CyzenGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler all Cyzen group';

    /**
     * @var \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;

    /**
     * CyzenGroup constructor.
     *
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    public function __construct(CyzenGroupService $cyzenGroupService)
    {
        parent::__construct();
        $this->cyzenGroupService = $cyzenGroupService;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->cyzenGroupService->handle();
    }
}
