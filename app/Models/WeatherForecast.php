<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WeatherForecast
 *
 * @package App\Models
 * @property int $id id
 * @property string|null $state_id 都道府県ID
 * @property int|null $region_id 地方ID
 * @property string|null $referer 参照元
 * @property int|null $forecast_day_range 予報幅
 * @property string|null $forecast_datetime 予報日時
 * @property string|null $forecast_time 予報時間
 * @property float|null $wind_speed_level 風速レベル
 * @property string|null $created 作成日時
 * @property string|null $modified 更新日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereForecastDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereForecastDayRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereForecastTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WeatherForecast whereWindSpeedLevel($value)
 * @mixin \Eloquent
 */
class WeatherForecast extends Model
{
    /**
     * @var string
     */
    protected $table = 'weather_forecasts';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
