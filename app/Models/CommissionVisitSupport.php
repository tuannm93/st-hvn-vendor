<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CommissionVisitSupport
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereCorrespondDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereCorrespondStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereCorrespondingContens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereResponders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionVisitSupport whereRitsResponders($value)
 * @mixin \Eloquent
 */
class CommissionVisitSupport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commission_visit_supports';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
