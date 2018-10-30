<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DemandForecast
 *
 * @package App\Models
 * @property int $id ID
 * @property string|null $state_id 都道府県ID
 * @property int|null $region_id 地方ID
 * @property string|null $forecast_date 予報日
 * @property int|null $genre0 ジャンルID【ガラス】
 * @property int|null $genre1 ジャンルID【アンテナ】
 * @property int|null $genre2 ジャンルID【シャッター】
 * @property string|null $display_date 表示日
 * @property int|null $demand_count0_min ジャンル0案件数 最小値
 * @property int|null $demand_count0_max ジャンル0案件数 最大値
 * @property int|null $demand_count1_min ジャンル1案件数 最小値
 * @property int|null $demand_count1_max ジャンル1案件数 最大値
 * @property int|null $demand_count2_min ジャンル2案件数 最小値
 * @property int|null $demand_count2_max ジャンル2案件数 最大値
 * @property float|null $wind_speed_level 風速レベル
 * @property string|null $created 作成日時
 * @property string|null $modified 更新日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDemandCount0Max($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDemandCount0Min($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDemandCount1Max($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDemandCount1Min($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDemandCount2Max($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDemandCount2Min($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereDisplayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereForecastDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereGenre0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereGenre1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereGenre2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandForecast whereWindSpeedLevel($value)
 * @mixin \Eloquent
 */
class DemandForecast extends Model
{
    /**
     * @var string
     */
    protected $table = 'demand_forecasts';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
