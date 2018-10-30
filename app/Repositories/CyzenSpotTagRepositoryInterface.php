<?php

namespace App\Repositories;

interface CyzenSpotTagRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @param $spotId
     * @return mixed
     */
    public function deleteBySpotId($spotId);

    /**
     * @param $key
     * @param $model
     * @return mixed
     */
    public function checkForeignKey($key, $model);
}
