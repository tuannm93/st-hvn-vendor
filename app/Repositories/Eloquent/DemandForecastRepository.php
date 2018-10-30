<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandForecast;
use App\Repositories\DemandForecastRepositoryInterface;

/**
 * Class DemandForecastRepository
 *
 * @package App\Repositories\Eloquent
 */
class DemandForecastRepository extends SingleKeyModelRepository implements DemandForecastRepositoryInterface
{
    /**
     * @var DemandForecast
     */
    protected $model;

    /**
     * DemandForecastRepository constructor.
     *
     * @param DemandForecast $model
     */
    public function __construct(DemandForecast $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|Weather|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandForecast();
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
     * @param array $date
     * @return mixed
     */
    public function countByDate($date)
    {
        return $this->model
            ->whereDate('display_date', $date)
            ->count('id');
    }
}
