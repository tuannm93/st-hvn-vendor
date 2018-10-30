<?php

namespace App\Services\Demand;

use App\Repositories\DemandExtendInfoRepositoryInterface;
use App\Services\CorpRegisteredScheduleService;

class DemandExtendInfoService extends BaseDemandInfoService
{
    /**
     * @var DemandExtendInfoRepositoryInterface $demandExtendInfoRepository
     */
    protected $demandExtendInfoRepository;

    /**
     * @var CorpRegisteredScheduleService $corpRegisteredScheduleService
     */
    protected $corpRegisteredScheduleService;

    /**
     * DemandExtendInfoService constructor.
     * @param DemandExtendInfoRepositoryInterface $demandExtendInfoRepository
     * @param CorpRegisteredScheduleService $corpRegisteredScheduleService
     */
    public function __construct(
        DemandExtendInfoRepositoryInterface $demandExtendInfoRepository,
        CorpRegisteredScheduleService $corpRegisteredScheduleService
    ) {
        $this->demandExtendInfoRepository = $demandExtendInfoRepository;
        $this->corpRegisteredScheduleService = $corpRegisteredScheduleService;
    }

    /**
     * @param $demandInfo
     * @return array
     */
    public function demandExtendInfoData($demandInfo)
    {
        $demandExtendData = $this->handleContactTime($demandInfo);
        $demandExtendData['demand_id'] = $demandInfo['id'];
        $demandExtendData['lat'] = $demandInfo['lat'];
        $demandExtendData['lng'] = $demandInfo['lng'];
        return $demandExtendData;
    }

    /**
     * @param $demandInfo
     * @return array
     */
    public function handleContactTime($demandInfo)
    {
        if ($demandInfo['contact_estimated_time_from'] != null && $demandInfo['contact_estimated_time_to'] != null) {
            $timeFrom = $demandInfo['contact_estimated_time_from'];
            $timeTo = $demandInfo['contact_estimated_time_to'];
            return [
                'est_start_work' => $timeFrom,
                'est_end_work' => $timeTo
            ];
        } elseif ($demandInfo['contact_desired_time']) {
            $timeFrom = $this->corpRegisteredScheduleService->timeSpecifyTo($demandInfo['contact_desired_time']);
            $timeTo = date("Y-m-d H:i:s", strtotime($demandInfo['contact_desired_time']));
            return [
                'contact_time_from' => $timeFrom,
                'contact_time_to' => $timeTo
            ];
        } else {
            $timeFrom = $demandInfo['contact_desired_time_from'];
            $timeTo = $demandInfo['contact_desired_time_to'];
            return [
                'contact_time_from' => $timeFrom,
                'contact_time_to' => $timeTo
            ];
        }
    }

    /**
     * @param $demandId
     * @return mixed
     */
    public function getAllByDemandId($demandId)
    {
        $data = $this->demandExtendInfoRepository->getAllByDemandId($demandId);
        if (!empty($data)) {
            if ($data['est_start_work'] != null && $data['est_end_work'] != null) {
                $data['est_start_work'] = date('Y/m/d H:i', strtotime($data['est_start_work']));
                $data['est_end_work'] = date('Y/m/d H:i', strtotime($data['est_end_work']));
            }
            return $data;
        }
        return false;
    }
}
