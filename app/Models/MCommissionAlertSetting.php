<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCommissionAlertSetting
 *
 * @property int $id ID
 * @property int $phase_id フェーズ
 * @property string $phase_name フェーズ名
 * @property int $correspond_status 状況
 * @property int|null $condition_value 条件値
 * @property string|null $condition_unit 条件単位
 * @property int|null $condition_value_min 条件値_分
 * @property int|null $rits_follow_datetime リッツ後追い時間
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereConditionUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereConditionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereConditionValueMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereCorrespondStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting wherePhaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting wherePhaseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionAlertSetting whereRitsFollowDatetime($value)
 * @mixin \Eloquent
 */
class MCommissionAlertSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_commission_alert_settings';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
