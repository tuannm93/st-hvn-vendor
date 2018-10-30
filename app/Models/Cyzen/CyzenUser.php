<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenUser
 *
 * @property int $id
 * @property string $user_login_id
 * @property string $user_code
 * @property string $user_name
 * @property string|null $app_version
 * @property string|null $device
 * @property string|null $os_version
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereUserCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereUserLoginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereUserName($value)
 * @mixin \Eloquent
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUser whereCrawlerTime($value)
 */
class CyzenUser extends Model
{
    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $keyType
     */
    public $keyType = 'string';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cyzen_users';
}
