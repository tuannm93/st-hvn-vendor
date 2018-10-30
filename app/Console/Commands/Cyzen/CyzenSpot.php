<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenSpotServices;
use Illuminate\Console\Command;

class CyzenSpot extends Command
{
    /**
     * @var CyzenSpotServices $service
     */
    protected $service;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_spot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get cyzen spots and spot tags';


    /**
     * CyzenSpot constructor.
     *
     * @param CyzenSpotServices $service
     */
    public function __construct(CyzenSpotServices $service)
    {
        parent::__construct();
        $this->service = $service;
    }


    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->service->executeApi();
    }
}
