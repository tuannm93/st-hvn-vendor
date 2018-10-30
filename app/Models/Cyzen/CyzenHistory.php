<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenHistory
 *
 * @property int $id
 * @property string $user_id
 * @property string $group_id
 * @property string|null $history_comment
 * @property string $status_id
 * @property string $address
 * @property int $history_accuracy
 * @property point $history_location
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereHistoryAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereHistoryComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenHistory whereUserId($value)
 * @mixin \Eloquent
 */
class CyzenHistory extends Model
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
