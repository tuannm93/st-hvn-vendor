<?php

namespace App\Console\Commands;

use App\Services\Log\ShellLogService;
use App\Services\ProgImportService;
use Illuminate\Console\Command;
use DB;

class ProgImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:prog_import {args?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage the progress of the companies closing date and final output';
    /**
     * @var ShellLogService
     */
    protected $logService;

    /**
     * @var ProgImportService
     */
    protected $progImportService;

    /**
     * ProgImport constructor.
     * @param ShellLogService $logService
     * @param ProgImportService $progImportService
     */
    public function __construct(
        ShellLogService $logService,
        ProgImportService $progImportService
    ) {
        parent::__construct();
        $this->logService = $logService;
        $this->progImportService = $progImportService;
    }

    /**
     * Manage the progress of the companies closing date and final output.
     * @throws \Exception
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->logService->log('進捗表管理インポートシェル（末日締め）start');
            $this->logService->log('進捗表管理インポートファイル作成start');
            $argument = $this->argument();
            $importFile = $this->progImportService->insertProgImportFiles($argument);
            $this->logService->log('進捗表管理インポートファイル作成end');
            $this->logService->log('案件情報の取得start');
            $commissionInfos = $this->progImportService->findCommissionInfos();
            $this->logService->log('案件情報の取得end');
            $this->logService->log('進捗管理案件情報の作成start');
            foreach ($commissionInfos as $commissionInfo) {
                $progressCorp = $this->progImportService->insertProgCorp($commissionInfo, $importFile->id);
                $this->progImportService->insertProgDemandInfo($commissionInfo, $importFile->id, $progressCorp->id, $importFile->lock_flag);
            }
            $this->logService->log('進捗管理案件情報の作成end');
            $this->logService->log('進捗表管理インポートシェル（末日締め）end');
            DB::commit();
        } catch (\Exception $exception) {
            $this->logService->log($exception);
            DB::rollBack();
        }
    }
}
