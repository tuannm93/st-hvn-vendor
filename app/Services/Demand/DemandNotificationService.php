<?php

namespace App\Services\Demand;

use App\Repositories\DemandNotificationRepositoryInterface;
use App\Services\CorpRegisteredScheduleService;
use App\Services\Cyzen\CyzenDemandServices;
use App\Services\Cyzen\CyzenNotificationServices;

class DemandNotificationService extends BaseDemandInfoService
{
    const CODE_ERROR_STAFF_SAME_SCHEDULE = 220489;
    const CODE_ERROR_STAFF_NOT_SCHEDULE = 220490;
    /**
     * @var DemandNotificationRepositoryInterface $demandNotificationRepository
     */
    protected $demandNotificationRepository;
    /**
     * @var CorpRegisteredScheduleService $corpRegisteredScheduleService
     */
    protected $corpRegisteredScheduleService;
    /**
     * @var CyzenDemandServices $cyzenDemandServices
     */
    protected $cyzenDemandServices;
    /**
     * @var \App\Services\Cyzen\CyzenNotificationServices $cyzenNotificationService
     */
    protected $cyzenNotificationService;

    /**
     * DemandNotificationService constructor.
     *
     * @param DemandNotificationRepositoryInterface $demandNotificationRepository
     * @param CorpRegisteredScheduleService $corpRegisteredScheduleService
     * @param CyzenDemandServices $cyzenDemandServices
     * @param \App\Services\Cyzen\CyzenNotificationServices $cyzenNotificationServices
     */
    public function __construct(
        DemandNotificationRepositoryInterface $demandNotificationRepository,
        CorpRegisteredScheduleService $corpRegisteredScheduleService,
        CyzenDemandServices $cyzenDemandServices,
        CyzenNotificationServices $cyzenNotificationServices
    ) {
        $this->demandNotificationRepository = $demandNotificationRepository;
        $this->corpRegisteredScheduleService = $corpRegisteredScheduleService;
        $this->cyzenDemandServices = $cyzenDemandServices;
        $this->cyzenNotificationService = $cyzenNotificationServices;
    }

    /**
     * @param $commissionData
     * @param $dataDemand
     * @return mixed
     * @throws \Exception
     */
    public function demandNotification($commissionData, $dataDemand)
    {
        $demandInfo = $dataDemand['demandInfo'];
        $demandInfo['id'] = $demandInfo['id'] ?? null;
        $demandInfo['modified'] = $demandInfo['modified'] ?? now();
        if (!empty($demandInfo['id'])) {
            $currentStatus = $this->currentStatus($demandInfo['id']);
            $commissionData = $this->updateStatus($commissionData, $currentStatus);
        }
        $corpId = [];
        $notificationData = [];
        foreach ($commissionData as $data) {
            if (isset($data['commit_flg']) && (int)$data['commit_flg'] == 1
                && isset($data['id_staff']) && !empty($data['id_staff'])) {
                array_push($corpId, $data['corp_id']);
            }
        }
        $callTimeDraft = $this->draftSchedule($demandInfo);
        $draftSchedule = $this->cyzenDemandServices->generateStaffDraftSchedule(
            $demandInfo['id'],
            $callTimeDraft['draft_start'],
            $callTimeDraft['draft_end'],
            $corpId,
            $demandInfo['genre_id'],
            $demandInfo['category_id'],
            $callTimeDraft['is_estimated_time'],
            $callTimeDraft['is_specific_time']
        );

        $draftSchedule = $this->cyzenDemandServices->refactorDraftSchedule(
            $draftSchedule,
            $demandInfo['modified'],
            $callTimeDraft['is_specific_time']
        );
        foreach ($commissionData as $i => $value) {
            if (empty($value['id_staff']) || !isset($value['id_staff'])) {
                continue;
            }
            if (isset($value['del_flg']) && (int)$value['del_flg'] == 1) {
                $notificationData[$i]['status'] = CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP;
            } else {
                if ((int)$demandInfo['demand_status'] == 5 && isset($value['commit_flg']) && (int)$value['commit_flg'] == 1) {
                    if (!isset($value['notification_status']) ||
                        (int)$value['notification_status'] == CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER
                    ) {
                        $notificationData[$i]['status'] = CyzenNotificationServices::STATUS_DEMAND_REGISTER;
                    } else {
                        $notificationData[$i]['status'] = $value['notification_status'];
                    }
                } else {
                    $notificationData[$i]['status'] = CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER;
                }
            }
            $notificationData[$i]['position'] = $i;
            $notificationData[$i]['corp_id'] = $value['corp_id'] ?? null;
            $notificationData[$i]['is_choice'] = 0;
            $notificationData[$i]['commission_id'] = $value['id'] ?? null;
            $notificationData[$i]['group_id'] = $this->cyzenNotificationService->getGroupIdByUser($value['id_staff']);
            $notificationData[$i]['demand_id'] = $demandInfo['id'];
            $notificationData[$i]['user_id'] = $value['id_staff'];
            if (isset($value['call_time_from'])) {
                $notificationData[$i]['call_time_from'] = $value['call_time_from'];
                $notificationData[$i]['call_time_to'] = $value['call_time_to'];
                $notificationData[$i]['draft_start_time'] = $value['draft_start_time'];
                $notificationData[$i]['draft_end_time'] = $value['draft_end_time'];
            } else {
                $notificationData[$i]['call_time_from'] = '1970-01-01 00:00';
                $notificationData[$i]['call_time_to'] = '1970-01-01 00:00';
                $notificationData[$i]['draft_start_time'] = '1970-01-01 00:00';
                $notificationData[$i]['draft_end_time'] = '1970-01-01 00:00';
            }
            if (isset($value['commit_flg']) && (int)$value['commit_flg'] == 1
                &&  (!isset($value['del_flg']) || (isset($value['del_flg']) && (int)$value['del_flg'] == 0))) {
                if ((isset($value['notification_status']) && $value['notification_status'] == CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER)
                    || (!isset($value['notification_status']) && (int)$dataDemand['not-send'] == 1)
                    || (!isset($value['notification_status']) && !empty($demandInfo['id']))) {
                    if (!isset($draftSchedule[$value['id_staff']]) || empty($draftSchedule[$value['id_staff']])) {
                        \Log::error('ERROR STAFF NOT HAVE SCHEDULE AT NOW [' . __LINE__ . '] >>> '
                            . $demandInfo['id'] . ' >>> ' . $value['id_staff'] . ' >>> ' . $value['corp_id']);
                        $messageError = __(
                            'demand.error_staff_not_have_schedule',
                            ['kameiten' => $value['mCorp']['corp_name'], 'staff' => $value['staff_name']]
                        );
                        throw new \Exception($messageError, self::CODE_ERROR_STAFF_SAME_SCHEDULE);
                    } else {
                        $startTime = $draftSchedule[$value['id_staff']]['start_date'];
                        $endTime = $draftSchedule[$value['id_staff']]['end_date'];
                        $callTime = $this->callTime($demandInfo, $callTimeDraft['call_start']);
                        $notificationData[$i]['call_time_from'] = $callTime['call_time_from'];
                        $notificationData[$i]['call_time_to'] = $callTime['call_time_to'];
                        $notificationData[$i]['draft_start_time'] = $startTime;
                        $notificationData[$i]['draft_end_time'] = $endTime;
                        if ((int)$dataDemand['not-send'] == 1) {
                            $notificationData[$i]['status'] = CyzenNotificationServices::STATUS_DEMAND_REGISTER;
                        }
                    }
                }
                $notificationData[$i]['is_choice'] = 1;
            }
        }
        return $notificationData;
    }

    /**
     * @param $demandId
     * @return mixed
     */
    public function currentStatus($demandId)
    {
        return $this->demandNotificationRepository->getCurrentStatus($demandId);
    }

    /**
     * @param $commissionData
     * @param $currentStatus
     * @return mixed
     */
    public function updateStatus($commissionData, $currentStatus)
    {
        if (isset($commissionData) && !empty($commissionData) && isset($currentStatus) && !empty($currentStatus)) {
            for ($i = 0; $i < count($commissionData); $i++) {
                for ($j = 0; $j < count($currentStatus); $j++) {
                    if ((int)$commissionData[$i]['id'] == (int)$currentStatus[$j]['commission_id']) {
                        $commissionData[$i]['notification_status'] = $currentStatus[$j]['status'];
                        $commissionData[$i]['call_time_from'] = $currentStatus[$j]['call_time_from'];
                        $commissionData[$i]['call_time_to'] = $currentStatus[$j]['call_time_to'];
                        $commissionData[$i]['draft_start_time'] = $currentStatus[$j]['draft_start_time'];
                        $commissionData[$i]['draft_end_time'] = $currentStatus[$j]['draft_end_time'];
                        if ($currentStatus[$j]['status'] == CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP
                            && isset($commissionData[$i]['commit_flg']) && (int)$commissionData[$i]['commit_flg'] = 1) {
                            $commissionData[$i]['notification_status'] = CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER;
                        }
                    }
                }
            }
        }
        return $commissionData;
    }

    /**
     * @param $demandInfo
     * @return array
     */
    public function draftSchedule($demandInfo)
    {
        $isEstimatedTime = false;
        $isSpecificTime = false;
        if ($demandInfo['contact_estimated_time_from'] != null && $demandInfo['contact_estimated_time_to'] != null) {
            $draftStart = $callStart = $demandInfo['contact_estimated_time_from'];
            $draftEnd = $callEnd = $demandInfo['contact_estimated_time_to'];
            $isEstimatedTime = true;
        } elseif (isset($demandInfo['contact_desired_time']) && !empty($demandInfo['contact_desired_time'])) {
            $callStart = date("Y-m-d H:i:s", strtotime("now"));
            $callEnd = date("Y-m-d H:i:s", strtotime("now + 5 minutes"));
            $draftStart = date('Y-m-d H:i:s', strtotime($demandInfo['contact_desired_time'] . " - 5 minutes"));
            $draftEnd = date('Y-m-d H:i:s', strtotime($demandInfo['contact_desired_time']));
            $isSpecificTime = true;
        } else {
            $draftStart = $callStart = $demandInfo['contact_desired_time_from'];
            $draftEnd = $callEnd = $demandInfo['contact_desired_time_to'];
        }
        return [
            'draft_start' => date("Y-m-d H:i:s", strtotime($draftStart)),
            'draft_end' => date("Y-m-d H:i:s", strtotime($draftEnd)),
            'call_start' => $callStart,
            'call_end' => $callEnd,
            'is_estimated_time' => $isEstimatedTime,
            'is_specific_time' => $isSpecificTime
        ];
    }

    /**
     * @param $demandInfo
     * @param $draftStartTime
     * @return array
     */
    private function callTime($demandInfo, $draftStartTime)
    {
        if (empty($demandInfo['contact_estimated_time_from']) || empty($demandInfo['contact_estimated_time_to'])) {
            if (isset($demandInfo['contact_desired_time_to']) && !empty($demandInfo['contact_desired_time_to'])
                && isset($demandInfo['contact_desired_time_from']) && !empty($demandInfo['contact_desired_time_from'])) {
                $callTimeFrom = date("Y-m-d H:i:s", strtotime($demandInfo['contact_desired_time_from']));
                $callTimeTo = date("Y-m-d H:i:s", strtotime($demandInfo['contact_desired_time_to']));
            } else {
                $callTimeFrom = $this->corpRegisteredScheduleService->timeSpecifyTo($demandInfo['contact_desired_time']);
                $callTimeTo = date("Y-m-d H:i:s", strtotime($demandInfo['contact_desired_time']));
            }
        } else {
            $spaceTime = (strtotime($demandInfo['contact_estimated_time_from']) - strtotime("now")) / 3600;
            if ($spaceTime <= 12) {
                $callTimeFrom = date("Y-m-d H:i:s", strtotime("+1 hours"));
                $callTimeTo = date("Y-m-d H:i:s", strtotime("+1 hours +5 minutes"));
            } else {
                $callTimeFrom = date("Y-m-d H:i:s", strtotime($draftStartTime . "-12 hours"));
                $callTimeTo = date("Y-m-d H:i:s", strtotime($draftStartTime . "-12 hours +5 minutes"));
            }
        }
        return [
            'call_time_from' => $callTimeFrom,
            'call_time_to' => $callTimeTo
        ];
    }

    /**
     * @param $NotifyData
     * @return mixed
     */
    public function statusAccordingCommision($NotifyData)
    {
        $notifyStatus = [];
        foreach ($NotifyData as $data) {
            $notifyStatus[$data->commission_id] = $data->status;
        }
        return $notifyStatus;
    }

    /**
     * @param $demandId
     * @param $notification
     * @param $status
     * @return void
     */
    public function updateDemandNotification($demandId, $notification, $status)
    {
        $this->demandNotificationRepository->updateInfoByDemandAndStaff(
            $demandId,
            $notification['user_id'],
            $notification['call_time_from'],
            $notification['call_time_to'],
            $notification['draft_start_time'],
            $notification['draft_end_time'],
            $status,
            $notification['commission_id']
        );
    }

    /**
     * @param $notification
     * @return mixed
     */
    public function insertTempDemandNotification($notification)
    {
        $idDemandNotification = $this->demandNotificationRepository->insertDemandNotifTemp(
            $notification['user_id'],
            $notification['call_time_from'],
            $notification['call_time_to'],
            $notification['draft_start_time'],
            $notification['draft_end_time'],
            CyzenNotificationServices::STATUS_WAIT_FOR_CREATE_CYZEN_SPOT
        );
        return $idDemandNotification;
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $status
     */
    public function updateDemandNotificationStatus($demandId, $userId, $status)
    {
        $this->demandNotificationRepository->updateStatusByStaffAndDemand($demandId, $userId, $status);
    }

    /**
     * @param $commissionId
     */
    public function updateDemandNotificationStatusByCommissionId($commissionId)
    {
        $this->demandNotificationRepository->updateByCommisionId($commissionId);
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $startTime
     * @param $endTime
     * @param $idDemandNotification
     * @return bool
     */
    public function checkExistDraftSchedule($demandId, $userId, $startTime, $endTime, $idDemandNotification)
    {
        $result = $this->demandNotificationRepository->checkImpactDraftSchedule(
            $demandId,
            $userId,
            $startTime,
            $endTime,
            $idDemandNotification
        );
        if (empty($result)) {
            return false;
        }
        return true;
    }

    /**
     * @param $id
     */
    public function deleteDemandNotificationTemp($id)
    {
        try {
            $this->demandNotificationRepository->deleteDemandNotification($id);
        } catch (\Exception $ex) {
            \Log::error(__CLASS__ . ' >>> ' . __LINE__ . ': ' . $ex->getMessage());
        }
    }
}
