<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CommissionOrderSupport
 *
 * @property int $id ID
 * @property int $commission_id 取次ID
 * @property int|null $correspond_status 状況
 * @property string|null $correspond_datetime 対応日時
 * @property int|null $order_fail_reason 失注理由
 * @property string|null $responders 対応者
 * @property string|null $corresponding_contens 対応内容
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $rits_responders rits側対応者
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereCorrespondDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereCorrespondStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereCorrespondingContens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereResponders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionOrderSupport whereRitsResponders($value)
 * @mixin \Eloquent
 */
class CommissionOrderSupport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commission_order_supports';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
