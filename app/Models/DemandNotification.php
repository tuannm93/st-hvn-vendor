<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DemandNotification
 *
 * @property int $id
 * @property int $demand_id
 * @property string $staff_id
 * @property string $call_time
 * @property string $start_time
 * @property string $end_time
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereCallTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandNotification whereStatus($value)
 * @mixin \Eloquent
 */
class DemandNotification extends Model
{
    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demand_notification';


    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
