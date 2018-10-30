<?php

namespace App\Repositories;

interface CommissionAppRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $commissionId
     * @return mixed
     */
    public function findByCommissionId($commissionId);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveApp($data);
}
