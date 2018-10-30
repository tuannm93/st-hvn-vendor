<?php

namespace App\Repositories;

interface MStaffRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $listIdCorp
     * @return mixed
     */
    public function getCorpHaveStaff($listIdCorp);
}
