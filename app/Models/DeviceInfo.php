<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeviceInfo
 *
 * @property int $id デバイスID
 * @property string $user_id ユーザーID
 * @property string $device_token デバイストークンまたはRegistrationID
 * @property string $endpoint エンドポイント
 * @property string|null $os_type デバイスのOS
 * @property int $del_flg 削除フラグ
 * @property string|null $created 作成日時
 * @property string|null $modified 更新日時
 * @property int $push_cnt 未読通知数
 * @property string|null $last_push_sender_time 最新通知送信日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereLastPushSenderTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereOsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo wherePushCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceInfo whereUserId($value)
 * @mixin \Eloquent
 */
class DeviceInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_infos';
    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $guarded = ['id'];
}
