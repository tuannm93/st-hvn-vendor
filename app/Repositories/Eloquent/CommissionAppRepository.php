<?php

namespace App\Repositories\Eloquent;

use App\Models\CommissionApp;
use App\Repositories\CommissionAppRepositoryInterface;

class CommissionAppRepository extends SingleKeyModelRepository implements CommissionAppRepositoryInterface
{
    /**
     * @var CommissionApp
     */
    protected $model;

    /**
     * CommissionAppRepository constructor.
     *
     * @param CommissionApp $model
     */
    public function __construct(CommissionApp $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CommissionApp|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CommissionApp();
    }

    /**
     * @param integer $commissionId
     * @return array
     */
    public function findByCommissionId($commissionId)
    {
        $fields = $this->getAllTableFieldsByAlias('commission_applications', 'CommissionApplication');
        $query = $this->model
            ->from('commission_applications AS CommissionApplication')
            ->join(
                'approvals AS Approval',
                function ($join) {
                                $join->on('Approval.relation_application_id', '=', 'CommissionApplication.id');
                                $join->where('Approval.application_section', '=', 'CommissionApplication');
                }
            )
                        ->where('CommissionApplication.commission_id', $commissionId)
                        ->select($fields)
                        ->addSelect(
                            'Approval.application_user_id AS Approval__application_user_id',
                            'Approval.application_datetime AS Approval__application_datetime',
                            'Approval.status AS Approval__status',
                            'Approval.application_reason AS Approval__application_reason',
                            'Approval.id AS Approval__id'
                        );

        $results = $query->get()->toArray();

        return $results;
    }

    /**
     * @param array $data
     * @return \App\Models\Base|CommissionApp|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function saveApp($data)
    {
        if (isset($data['id'])) {
            $commissionApp = $this->model->where('id', $data['id'])->first();
        } else {
            $commissionApp = $this->getBlankModel();
            $commissionApp->created = date('Y-m-d H:i:s');
            $commissionApp->created_user_id = auth()->user()->user_id;
        }

        $commissionApp->modified = date('Y-m-d H:i:s');
        $commissionApp->modified_user_id = auth()->user()->user_id;

        $fillAble = [
            'chg_deduction_tax_include', 'deduction_tax_include', 'chg_irregular_fee_rate', 'irregular_fee_rate', 'chg_irregular_fee',
            'irregular_fee', 'irregular_reason', 'chg_introduction_free', 'introduction_free', 'chg_ac_commission_exclusion_flg',
            'ac_commission_exclusion_flg', 'chg_introduction_not', 'introduction_not', 'commission_id', 'demand_id', 'corp_id'
        ];
        foreach ($fillAble as $field) {
            if (isset($data[$field])) {
                $commissionApp->$field = $data[$field];
            }
        }

        $commissionApp->save();

        return $commissionApp;
    }
}
