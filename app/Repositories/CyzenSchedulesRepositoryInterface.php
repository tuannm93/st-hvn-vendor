<?php

namespace App\Repositories;

interface CyzenSchedulesRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @param $field
     * @return mixed
     */
    public function getAllGroupBy($field);

    /**
     * @param $data
     * @return mixed
     */
    public function saveSchedule($data);

    /**
     * @param $key
     * @param $model
     * @return mixed
     */
    public function checkForeignKey($key, $model);

    /**
     * @return mixed
     */
    public function getCountData();

    /**
     * @return mixed
     */
    public function getLastUpdatedDate();

    /**
     * @param $data
     * @return mixed
     */
    public function saveScheduleUser($data);

    /**
     * @param $listIdCorp
     * @param $startTime
     * @param $endTime
     * @param $genreId
     * @param $categoryId
     * @param $isEstimateTime
     * @return mixed
     */
    public function getListScheduleOfUse(&$listIdCorp, $startTime, $endTime, $genreId, $categoryId, $isEstimateTime);

    /**
     * @param $data
     * @return mixed
     */
    public function updateDemandNotification($data);

    /**
     * @param $id
     * @return mixed
     */
    public function getStaffFromUserId($id);

    /**
     * @param $spotId
     * @return mixed
     */
    public function getDemandIdFromSpot($spotId);

    /**
     * @param $corpId
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getCorpRegisteredSchedule($corpId, $genreId, $categoryId);

    /**
     * @param $listStaff
     * @param $start
     * @param $end
     * @param $listIdCorp
     * @param $genreId
     * @param $categoryId
     * @param $isEstimatedTime
     * @return mixed
     */
    public function getListScheduleInRange(
        $listStaff,
        $start,
        $end,
        $listIdCorp,
        $genreId,
        $categoryId,
        $isEstimatedTime
    );

    /**
     * @param $listStaff
     * @param $start
     * @param $end
     * @param $isSpecificTime
     * @param $demandId
     * @return mixed
     */
    public function getListScheduleInDemandNotification($listStaff, $start, $end, $isSpecificTime, $demandId);

    /**
     * @param $scheduleId
     * @return mixed
     */
    public function deleteScheduleById($scheduleId);
}
