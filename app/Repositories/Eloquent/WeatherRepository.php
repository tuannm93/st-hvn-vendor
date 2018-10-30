<?php

namespace App\Repositories\Eloquent;

use App\Models\Weather;
use App\Repositories\WeatherRepositoryInterface;

/**
 * Class WeatherRepository
 *
 * @package App\Repositories\Eloquent
 */
class WeatherRepository extends SingleKeyModelRepository implements WeatherRepositoryInterface
{
    /**
     * @var Weather
     */
    protected $model;

    /**
     * WeatherRepository constructor.
     *
     * @param Weather $model
     */
    public function __construct(Weather $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|Weather|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new Weather();
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
     * Get total weather data in date.
     *
     * @param string $date
     * @return mixed
     */
    public function countByDate($date)
    {
        return $this->model
            ->whereDate('weather_datetime', $date)
            ->count('id');
    }
}
