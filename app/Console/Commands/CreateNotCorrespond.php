<?php

namespace App\Console\Commands;

use App\Services\CreateNotCorrespondService;
use App\Services\Log\ShellLogService;
use Illuminate\Console\Command;

class CreateNotCorrespond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_not_correspond';

    /**
     * @var array
     */
    protected $tasks = [];

    /**
     * @var string
     */
    protected static $user = 'system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '案件データ作成処理';

    /**
     * @var CreateNotCorrespondService
     */
    protected $createNotCorrespondService;

    /**
     * @var ShellLogService
     */
    protected $shellLogService;

    /**
     * Create a new command instance.
     *
     * @param CreateNotCorrespondService $createNotCorrespondService
     * @param ShellLogService            $shellLogService
     */
    public function __construct(CreateNotCorrespondService $createNotCorrespondService, ShellLogService $shellLogService)
    {
        parent::__construct();
        $this->createNotCorrespondService = $createNotCorrespondService;
        $this->shellLogService = $shellLogService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $this->shellLogService->log('エリア対応加盟店なし案件データの作成');
            $this->createNotCorrespondService->execute();
            $this->shellLogService->log('エリア対応加盟店なし案件データの作成 終了');
        } catch (\Exception $e) {
            $this->shellLogService->log($e);
        }
    }
}
