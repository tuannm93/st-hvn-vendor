<?php

namespace App\Repositories\Eloquent;

use App\Models\MStaff;
use App\Repositories\MStaffRepositoryInterface;

class MStaffRepository extends SingleKeyModelRepository implements MStaffRepositoryInterface
{
    /**
     * @var \App\Models\MStaff $model
     */
    protected $model;

    /**
     * MStaffRepository constructor.
     *
     * @param \App\Models\MStaff $MStaff
     */
    public function __construct(MStaff $MStaff)
    {
        $this->model = $MStaff;
    }

    /**
     * @param $listIdCorp
     * @return mixed
     */
    public function getCorpHaveStaff($listIdCorp)
    {
        $list = $this->model->select(['corp_id'])->whereIn('corp_id', $listIdCorp)->get()->toArray();
        return $list;
    }
}
