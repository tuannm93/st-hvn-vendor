<?php

namespace App\Services\Job;

use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\Log\ShellLogService;

class PeriodicallyService
{
    /**
     * @var \App\Services\Log\ShellLogService
     */
    protected $shellLogService;

    /**
     * @var AffiliationInfoRepositoryInterface
     */
    protected $affiliationInfoRepo;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;

    /**
     * PeriodicallyService constructor.
     *
     * @param \App\Services\Log\ShellLogService $shellLogService
     * @param \App\Repositories\AffiliationInfoRepositoryInterface $affiliationInfoRepo
     * @param \App\Repositories\MCorpRepositoryInterface $mCorpRepo
     */
    public function __construct(
        ShellLogService $shellLogService,
        AffiliationInfoRepositoryInterface $affiliationInfoRepo,
        MCorpRepositoryInterface $mCorpRepo
    ) {
        $this->shellLogService = $shellLogService;
        $this->mCorpRepo = $mCorpRepo;
        $this->affiliationInfoRepo = $affiliationInfoRepo;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $this->shellLogService->log('PeriodicallyShell Start : main');
            if (date("d") == '01') {
                $this->initAddMonthCredit();
            }
            $this->initAntisocialDisplayFlag();
            $this->initLicenseDisplayFlag();
            $this->shellLogService->log('PeriodicallyShell End : main');
        } catch (\Exception $e) {
            $this->shellLogService->log('PeriodicallyShell Error : main');
        }
    }

    /**
     * init add month credit
     * @throws \Exception
     */
    protected function initAddMonthCredit()
    {
        $this->shellLogService->log('PeriodicallyShell Start : initAddMonthCredit');
        $dataUpdate = [
            'add_month_credit' => 0,
            'credit_mail_send_flg' => 0
        ];
        $result = $this->affiliationInfoRepo->updateRecord($dataUpdate);
        if (count($result)) {
            $this->shellLogService->log('PeriodicallyShell Success : initAddMonthCredit');
        } else {
            $this->shellLogService->log('PeriodicallyShell Failure : initAddMonthCredit');
        }
        $this->shellLogService->log('PeriodicallyShell End : initAddMonthCredit');
    }

    /**
     * init antisocial display flag
     *
     * @throws \Exception
     */
    protected function initAntisocialDisplayFlag()
    {
        $this->shellLogService->log('PeriodicallyShell Start : initAntisocialDisplayFlag');
        $dataUpdate = [
            'antisocial_display_flag' => 1
        ];
        if (count($this->mCorpRepo->updateAll($dataUpdate, true))) {
            $this->shellLogService->log('PeriodicallyShell Success : initAntisocialDisplayFlag');
        } else {
            $this->shellLogService->log('PeriodicallyShell Failure : initAntisocialDisplayFlag');
        }
        $this->shellLogService->log('PeriodicallyShell End : initAntisocialDisplayFlag');
    }

    /**
     * init license display flag
     *
     * @throws \Exception
     */
    protected function initLicenseDisplayFlag()
    {
        $this->shellLogService->log('PeriodicallyShell Start : initLicenseDisplayFlag');
        $updateField = ['license_display_flag' => 1];
        if (count($this->mCorpRepo->updateAll($updateField, false))) {
            $this->shellLogService->log('PeriodicallyShell Success : initLicenseDisplayFlag');
        } else {
            $this->shellLogService->log('PeriodicallyShell Failure : initLicenseDisplayFlag');
        }
        $this->shellLogService->log('PeriodicallyShell End : initLicenseDisplayFlag');
    }
}
