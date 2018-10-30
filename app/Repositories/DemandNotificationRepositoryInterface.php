<?php

namespace App\Repositories;

interface DemandNotificationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $status
     * @return mixed
     */
    public function getListStaffByStatus($status);

    /**
     * @param $demandId
     * @param $staffId
     * @param $status
     * @return mixed
     */
    public function updateStatusByStaffAndDemand($demandId, $staffId, $status);
    /**
     * @param $demandId
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($demandId, $data);
    /**
     * @param $demandId
     * @param $userId
     * @return mixed
     */
    public function getDetailForMailContent($demandId, $userId);

    /**
     * @param $demandId
     * @return mixed
     */
    public function getAllByDemandId($demandId);

    /**
     * @param $staffId
     * @return mixed
     */
    public function getGroupByStaff($staffId);

    /**
     * @param $demandId
     * @return mixed
     */
    public function getCurrentStatus($demandId);

    /**
     * @param $demandId
     * @param $userId
     * @param \Monolog\Logger $logger
     * @return mixed
     */
    public function updateStatusWhenDeleteSchedule($demandId, $userId, $logger);

    /**
     * @param $scheduleId
     * @return mixed
     */
    public function getDemandIdAndUserIdFromScheduleId($scheduleId);

    /**
     * @param $demandId
     * @param $userId
     * @param $draftStart
     * @param $draftEnd
     * @param $idDemandNotification
     * @return mixed
     */
    public function checkImpactDraftSchedule($demandId, $userId, $draftStart, $draftEnd, $idDemandNotification);

    /**
     * @param $demandId
     * @param $userId
     * @param $callStart
     * @param $callEnd
     * @param $draftStart
     * @param $draftEnd
     * @param $status
     * @param $commissionId
     * @return mixed
     */
    public function updateInfoByDemandAndStaff(
        $demandId,
        $userId,
        $callStart,
        $callEnd,
        $draftStart,
        $draftEnd,
        $status,
        $commissionId
    );

    /**
     * @param $userId
     * @param $callStart
     * @param $callEnd
     * @param $draftStart
     * @param $draftEnd
     * @param $status
     * @return mixed
     */
    public function insertDemandNotifTemp($userId, $callStart, $callEnd, $draftStart, $draftEnd, $status);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteDemandNotification($id);

    /**
     * @param $commissionId
     * @return mixed
     */
    public function updateByCommisionId($commissionId);
}
