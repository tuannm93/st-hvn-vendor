<?php

namespace App\Console\Commands;

use App\Services\CheckAllCorpRefualService;
use App\Services\CheckAllCorpRefusalService;
use App\Services\Log\ShellLogService;
use Illuminate\Console\Command;

class CheckAllCorpRefusalAuctionShell extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_refusal_auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var CheckAllCorpRefusalService
     */
    protected $checkAllCorpRefusalService;
    /**
     * @var ShellLogService
     */
    protected $shellLogService;

    /**
     * CheckAllCorpRefusalAuctionShell constructor.
     * @param ShellLogService $shellLogService
     * @param CheckAllCorpRefusalService $checkAllCorpRefusalService
     */
    public function __construct(ShellLogService $shellLogService, CheckAllCorpRefusalService $checkAllCorpRefusalService)
    {
        parent::__construct();
        $this->checkAllCorpRefusalService = $checkAllCorpRefusalService;
        $this->shellLogService = $shellLogService;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $this->shellLogService->log('オークション流れ案件処理(オークション期限前チェック)start');
            $this->checkAllCorpRefusalService->execute($this);
            $this->shellLogService->log('オークション流れ案件処理(オークション期限前チェック)end');
        } catch (\Exception $e) {
            $this->shellLogService->log($e);
        }
    }
}
