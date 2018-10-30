<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ProgDemandInfoTmpRepositoryInterface;
use App\Models\ProgDemandInfoTmp;

class ProgDemandInfoTmpRepository extends SingleKeyModelRepository implements ProgDemandInfoTmpRepositoryInterface
{
    /**
     * @var ProgDemandInfoTmp
     */
    protected $model;

    /**
     * ProgDemandInfoTmpRepository constructor.
     *
     * @param ProgDemandInfoTmp $model
     */
    public function __construct(ProgDemandInfoTmp $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|ProgDemandInfoTmp|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new ProgDemandInfoTmp();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * get by corp id
     *
     * @param  object $progCorp
     * @return array object
     */
    public function getByProgCorpId($progCorp)
    {
        $conditions = $this->model
            ->select(
                'id',
                'prog_demand_info_id',
                'agree_flag',
                'demand_id',
                'commission_id',
                'receive_datetime',
                'customer_name',
                'category_name',
                'complete_date',
                'construction_price_tax_exclude',
                'construction_price_tax_include',
                'fee_target_price',
                'diff_flg',
                'commission_status',
                'commission_status_update',
                'complete_date_update',
                'fee',
                'fee_rate',
                'construction_price_tax_exclude_update',
                'construction_price_tax_include_update',
                'commission_order_fail_reason_update',
                'comment_update',
                'receive_datetime'
            );
        if (!empty($progCorp->id)) {
            $conditions = $conditions->where('prog_corp_id', $progCorp->id);
        } else {
            $conditions = $conditions->whereNull('prog_corp_id');
        }
        return $conditions->orderBy('receive_datetime', 'asc')
            ->get();
    }

    /**
     * delete by prog corp id
     *
     * @param  integer $progCorpId
     * @return boolean
     * @throws \Exception
     */
    public function deleteByProgCorpId($progCorpId)
    {
        return $this->model
            ->where('prog_corp_id', $progCorpId)
            ->delete();
    }
}
