<?php

namespace App\Repositories;

interface CyzenSpotRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getLastSpotByCrawlerTime();

    /**
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public function updateOrInsertData($id, $data);

    /**
     * @param $spotId
     * @param $groupId
     * @return mixed
     */
    public function checkSpotId($spotId, $groupId);

    /**
     * @param $staffId
     * @return mixed
     */
    public function getTagByStaff($staffId);
}
