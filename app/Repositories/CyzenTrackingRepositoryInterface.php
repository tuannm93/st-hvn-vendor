<?php

namespace App\Repositories;

interface CyzenTrackingRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @param $listIdCorp
     * @param $lat
     * @param $lng
     * @param $limit
     * @return mixed
     */
    public function getListDistanceOfUser(&$listIdCorp, $lat, $lng, $limit);
}
