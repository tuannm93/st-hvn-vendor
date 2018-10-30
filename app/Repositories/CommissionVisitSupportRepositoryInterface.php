<?php

namespace App\Repositories;

interface CommissionVisitSupportRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);


    /**
     * @param integer $commissionId
     * @param bool $all
     * @return mixed
     */
    public function findByCommissionId($commissionId, $all = false);
}
