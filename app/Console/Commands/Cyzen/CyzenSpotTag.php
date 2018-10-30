<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenSpotTagService;
use Illuminate\Console\Command;

class CyzenSpotTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_spot_tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler all Cyzen spot tag';

    /**
     * @var \App\Services\Cyzen\CyzenSpotTagService $cyzenSpotTagService
     */
    protected $cyzenSpotTagService;

    /**
     * CyzenGroup constructor.
     *
     * @param \App\Services\Cyzen\CyzenSpotTagService $cyzenSpotTagService
     */
    public function __construct(CyzenSpotTagService $cyzenSpotTagService)
    {
        parent::__construct();
        $this->cyzenSpotTagService = $cyzenSpotTagService;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->cyzenSpotTagService->handle();
    }
}
