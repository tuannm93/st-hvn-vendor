<?php

namespace App\Repositories;

interface CyzenDemandInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function apiDemandInfo($id, $userId);

    /**
     * @param $demandId
     * @param $staffId
     * @param bool $isName
     * @return mixed
     */
    public function getDemandStatus($demandId, $staffId, $isName = true);

    /**
     * @param $statusId
     * @return mixed
     */
    public function getStatusName($statusId);

    /**
     * @param $demandId
     * @param $status
     * @param $staffId
     * @return mixed
     */
    public function updateStatusCommissionInfo($demandId, $status, $staffId);

    /**
     * @param $demandId
     * @param $userId
     * @return mixed
     */
    public function getNotificationStatus($demandId, $userId);

    /**
     * @param $listCorpId
     * @param $start
     * @param $end
     * @param $genreId
     * @param $categoryId
     * @param bool $isSpecificTime
     * @return mixed
     */
    public function getStaffFreeSchedule($listCorpId, $start, $end, $genreId, $categoryId, $isSpecificTime = false);
}
