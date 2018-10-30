<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ProgAddDemandInfoTmpRepositoryInterface;
use App\Models\ProgAddDemandInfoTmp;

class ProgAddDemandInfoTmpRepository extends SingleKeyModelRepository implements ProgAddDemandInfoTmpRepositoryInterface
{
    /**
     * @var ProgAddDemandInfoTmp
     */
    protected $model;

    /**
     * ProgAddDemandInfoTmpRepository constructor.
     *
     * @param ProgAddDemandInfoTmp $model
     */
    public function __construct(ProgAddDemandInfoTmp $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|ProgAddDemandInfoTmp|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new ProgAddDemandInfoTmp();
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
                'prog_add_demand_info_id',
                'sequence',
                'agree_flag',
                'display',
                'demand_id_update',
                'customer_name_update',
                'category_name_update',
                'commission_status_update',
                'complete_date_update',
                'construction_price_tax_exclude_update',
                'comment_update',
                'demand_type_update',
                'demand_id_update'
            );
        if (!empty($progCorp->id)) {
            $conditions = $conditions->where('prog_corp_id', $progCorp->id);
        } else {
            $conditions = $conditions->whereNull('prog_corp_id');
        }
        return $conditions->orderBy('id', 'asc')
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
