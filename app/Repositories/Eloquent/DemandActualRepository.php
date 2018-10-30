<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandActual;
use App\Repositories\DemandActualRepositoryInterface;

/**
 * Class DemandActualRepository
 *
 * @package App\Repositories\Eloquent
 */
class DemandActualRepository extends SingleKeyModelRepository implements DemandActualRepositoryInterface
{
    /**
     * @var DemandActual
     */
    protected $model;

    /**
     * DemandActualRepository constructor.
     *
     * @param DemandActual $model
     */
    public function __construct(DemandActual $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|DemandActual|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandActual();
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
     * Get total data by date
     *
     * @param string $date
     * @return mixed
     */
    public function countByDate($date)
    {
        return $this->model
            ->whereDate('actual_datetime', $date)
            ->count('id');
    }
}
