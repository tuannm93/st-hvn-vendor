<?php

namespace App\Repositories;

interface DemandExtendInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($data);
    /**
     * @param $demandId
     * @return mixed
     */
    public function getAllByDemandId($demandId);
}
