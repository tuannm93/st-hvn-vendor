<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DemandActual
 *
 * @package App\Models
 * @property int $id id
 * @property string|null $state_id 都道府県ID
 * @property int|null $region_id 地方ID
 * @property string|null $actual_datetime 地方ID
 * @property int|null $genre0 ジャンルID[ガラス]
 * @property int|null $genre1 ジャンルID[アンテナ]
 * @property int|null $genre2 ジャンルID[シャッター]
 * @property int|null $demand_count0 案件数[ガラス]
 * @property int|null $demand_count1 案件数[アンテナ]
 * @property int|null $demand_count2 案件数[シャッター]
 * @property string|null $created 作成日時
 * @property string|null $modified 更新日時
 * @property float|null $wind_speed_avg 風速平均
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereActualDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereDemandCount0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereDemandCount1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereDemandCount2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereGenre0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereGenre1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereGenre2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandActual whereWindSpeedAvg($value)
 * @mixin \Eloquent
 */
class DemandActual extends Model
{
    /**
     * @var string
     */
    protected $table = 'demand_actuals';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
