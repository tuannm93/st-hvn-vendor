<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCommissionType
 *
 * @property int $id ID
 * @property string|null $commission_type_name 取次形態
 * @property int|null $commission_type_div 取次形態区分
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereCommissionTypeDiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereCommissionTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCommissionType whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MCommissionType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_commission_types';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
