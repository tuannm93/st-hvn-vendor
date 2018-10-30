<?php

namespace App\Repositories\Eloquent;

use App\Models\FiltersConditions;
use App\Repositories\FiltersConditionsRepositoryInterface;

class FiltersConditionsRepository extends SingleKeyModelRepository implements FiltersConditionsRepositoryInterface
{
    /** @var FiltersConditions $model */
    protected $model;

    /**
     * FiltersConditionsRepository constructor.
     * @param FiltersConditions $model
     */
    public function __construct(FiltersConditions $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|FiltersConditions|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new FiltersConditions();
    }

    /**
     * Get list condition by filter id sort by priority (order)
     * @param $filterId
     * @return mixed
     */
    public function getConditionById($filterId)
    {
        $query = $this->model->select('*')
            ->where('filter_id', '=', $filterId)
            ->orderBy('order', 'asc')
            ->get()->toArray();
        return $query;
    }
}
