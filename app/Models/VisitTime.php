<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VisitTime
 *
 * @property int $id ID
 * @property int $demand_id 案件ID
 * @property string|null $visit_time 訪問日時
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $is_visit_time_range_flg
 * @property string|null $visit_time_from
 * @property string|null $visit_time_to
 * @property string|null $visit_adjust_time 訪問日時要調整時間
 * @property-read \App\Models\DemandInfo $demandInfo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereIsVisitTimeRangeFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereVisitAdjustTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereVisitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereVisitTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VisitTime whereVisitTimeTo($value)
 * @mixin \Eloquent
 */
class VisitTime extends Model
{
    /**
     * @var string
     */
    protected $table = 'visit_times';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get field
     *
     * @return array
     */
    public static function getField()
    {
        return [
            'demand_id' => null,
            'visit_time' => null,
            'modified_user_id' => null,
            'modified' => null,
            'created_user_id' => null,
            'created' => null,
            'is_visit_time_range_flg' => 0,
            'visit_time_from' => null,
            'visit_time_to' => null,
            'visit_adjust_time' => null
        ];
    }

    /**
     * Demand info relations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function demandInfo()
    {
        return $this->belongsTo(DemandInfo::class, 'demand_id', 'id');
    }

    /**
     * @return false|string
     */
    public function getVisitTimeFormatAttribute()
    {
        return date_time_format($this->getAttribute('visit_time'), 'Y/m/d H:s');
    }
}
