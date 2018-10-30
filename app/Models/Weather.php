<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Weather
 *
 * @package App\Models
 * @property int $id id
 * @property string|null $state_id 都道府県ID
 * @property int|null $region_id 地方ID
 * @property string|null $referer 参照元
 * @property string|null $weather_datetime 対象日時
 * @property string|null $weather_time 対象時間
 * @property float|null $wind_speed_avg 風速平均
 * @property string|null $created 作成日時
 * @property string|null $modified 更新日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereWeatherDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereWeatherTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereWindSpeedAvg($value)
 * @mixin \Eloquent
 */
class Weather extends Model
{
    /**
     * @var string
     */
    protected $table = 'weathers';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
