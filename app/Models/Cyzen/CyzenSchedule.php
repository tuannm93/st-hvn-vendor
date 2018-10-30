<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenSchedule
 *
 * @property int $id
 * @property string $group_id
 * @property string $title
 * @property string $detail
 * @property string $start_date
 * @property string $end_date
 * @property bool $is_all_day
 * @property string $spot_id
 * @property string $address
 * @property point $location
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereIsAllDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereSpotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CyzenSchedule extends Model
{
    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $keyType
     */
    public $keyType = 'string';
}
