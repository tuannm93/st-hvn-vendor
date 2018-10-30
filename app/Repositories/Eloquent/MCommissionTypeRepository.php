<?php

namespace App\Repositories\Eloquent;

use App\Models\MCommissionType;
use App\Repositories\MCommissionTypeRepositoryInterface;

class MCommissionTypeRepository extends SingleKeyModelRepository implements MCommissionTypeRepositoryInterface
{
    /**
     * @var MCommissionType
     */
    protected $model;

    /**
     * MCommissionTypeRepository constructor.
     *
     * @param MCommissionType $model
     */
    public function __construct(MCommissionType $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MCommissionType|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCommissionType();
    }

    /**
     * @return array|mixed
     */
    public function getList()
    {
        $list =  $this->model->orderBy('id', 'asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['commission_type_name']];
        });

        return $list;
    }

    /**
     * @return array|mixed
     */
    public function getListCommissionTypeName()
    {
        return $this->model->orderBy('id', 'ASC')
            ->pluck('commission_type_name', 'id')->toarray();
    }
}
