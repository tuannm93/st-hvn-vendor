<?php

namespace App\Repositories\Eloquent;

use App\Models\WeatherForecast;
use App\Repositories\WeatherForecastRepositoryInterface;

/**
 * Class WeatherForecastRepository
 *
 * @package App\Repositories\Eloquent
 */
class WeatherForecastRepository extends SingleKeyModelRepository implements WeatherForecastRepositoryInterface
{
    /**
     * @var WeatherForecast
     */
    protected $model;

    /**
     * WeatherForecastRepository constructor.
     *
     * @param WeatherForecast $model
     */
    public function __construct(WeatherForecast $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|WeatherForecast|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new WeatherForecast();
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
     * Get total data in date range
     *
     * @param string $fromDate
     * @param string $toDate
     * @return mixed
     */
    public function countByDateRange($fromDate, $toDate)
    {
        return $this->model
            ->whereBetween('forecast_datetime', [$fromDate, $toDate])
            ->count('id');
    }
}
