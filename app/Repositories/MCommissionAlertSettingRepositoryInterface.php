<?php

namespace App\Repositories;

interface MCommissionAlertSettingRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $phaseId
     * @param string    $correspondStatus
     * @return mixed
     */
    public function findByPhaseId($phaseId, $correspondStatus = null);
}
