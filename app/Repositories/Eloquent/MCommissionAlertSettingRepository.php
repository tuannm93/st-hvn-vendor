<?php

namespace App\Repositories\Eloquent;

use App\Models\MCommissionAlertSetting;
use App\Repositories\MCommissionAlertSettingRepositoryInterface;

class MCommissionAlertSettingRepository extends SingleKeyModelRepository implements MCommissionAlertSettingRepositoryInterface
{
    /**
     * @var MCommissionAlertSetting
     */
    protected $model;

    /**
     * MCommissionAlertSettingRepository constructor.
     *
     * @param MCommissionAlertSetting $model
     */
    public function __construct(MCommissionAlertSetting $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $phaseId
     * @param null $correspondStatus
     * @return array|mixed
     */
    public function findByPhaseId($phaseId, $correspondStatus = null)
    {
        $result = $this->model->from('m_commission_alert_settings AS MCommissionAlertSetting')->where('MCommissionAlertSetting.correspond_status', $correspondStatus)->where('MCommissionAlertSetting.phase_id', $phaseId)->select('*')->first();

        return $result;
    }
}
