<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenStatus
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $group_id
 * @property string $status_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenStatus whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenStatus whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenStatus whereUpdatedAt($value)
 */
class CyzenStatus extends Model
{
    /**
     * @var string $table
     */
    public $table = 'cyzen_statuses';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;
}
