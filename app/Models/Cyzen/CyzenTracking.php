<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenTracking
 *
 * @property int $id
 * @property string $user_id
 * @property string $group_id
 * @property string $address
 * @property int $tracking_accuracy
 * @property point $tracking_location
 * @property \Carbon\Carbon $created_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereCrawlerTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereTrackingAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenTracking whereUserId($value)
 * @mixin \Eloquent
 */
class CyzenTracking extends Model
{
    /**
     * @var bool $timestamps
     */
    public $timestamps = false;
}
