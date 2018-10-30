<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CommissionCorrespond
 *
 * @property int $id ID
 * @property int $commission_id 取次ID
 * @property string|null $correspond_datetime 対応日時
 * @property string|null $responders 対応者
 * @property string|null $corresponding_contens 対応内容
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $rits_responders rits側対応者
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereCorrespondDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereCorrespondingContens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereResponders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionCorrespond whereRitsResponders($value)
 * @mixin \Eloquent
 */
class CommissionCorrespond extends Model
{
    /**
     * @var string
     */
    protected $table = 'commission_corresponds';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
