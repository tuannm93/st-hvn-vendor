<?php

namespace App\Services\Report;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;

class ReportRealTimeService
{
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var MCorpSubRepositoryInterface
     */
    protected $mCorpSubsRepository;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;

    /**
     * ReportRealTimeService constructor.
     *
     * @param DemandInfoRepositoryInterface     $demandInfoRepository
     * @param MCorpSubRepositoryInterface       $mCorpSubsRepository
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        MCorpSubRepositoryInterface $mCorpSubsRepository,
        CommissionInfoRepositoryInterface $commissionInfoRepository
    ) {
        $this->demandInfoRepository = $demandInfoRepository;
        $this->mCorpSubsRepository = $mCorpSubsRepository;
        $this->commissionInfoRepository = $commissionInfoRepository;
    }


    /**
     * Get data real time
     *
     * @return mixed
     */
    public function getDataRealTime()
    {
        // Acquire conditional expression
        $subQueryForDemandStatus = $this->commissionInfoRepository->subQueryForDemandStatus();
        $results = $this->demandInfoRepository->findReportDemandStatus($subQueryForDemandStatus);

        $subQueryForHearNum = $this->commissionInfoRepository->subQueryForHearNum();

        // Number of inbound callable / required number of hears / number of acquisitions
        $results1 = $this->demandInfoRepository->getRealTimeReportHearLossNum1($subQueryForHearNum);

        // (JBR) Callable inside / required number of hares / number of acquisitions
        $results2 = $this->demandInfoRepository->getRealTimeReportHearLossNum2($subQueryForHearNum);

        // Add item to result set
        $results['call_hear_num'] = $results1['CallHearNum1'] + $results2['CallHearNum2'];
        $results['call_loss_num'] = $results1['CallLossNum1'] + $results2['CallLossNum2'];

        return $results;
    }
}
