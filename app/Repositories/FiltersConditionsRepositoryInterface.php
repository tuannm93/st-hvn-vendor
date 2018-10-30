<?php

namespace App\Repositories;

interface FiltersConditionsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $filterId
     * @return mixed
     */
    public function getConditionById($filterId);
}
