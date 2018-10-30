<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenGroup
 *
 * @property int $id
 * @property string $group_join_id
 * @property string $group_code
 * @property string $group_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereGroupCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereGroupJoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenGroup whereCrawlerTime($value)
 */
class CyzenGroup extends Model
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
