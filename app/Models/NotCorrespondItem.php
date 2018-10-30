<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NotCorrespondItem
 *
 * @property int $id ID
 * @property int $immediate_lower_limit 急 案件数下限値
 * @property int $large_lower_limit 多 案件数下限値
 * @property int $midium_lower_limit 中 案件数下限値
 * @property int $small_lower_limit 小 案件数下限値
 * @property int $immediate_date 直近期間(日)
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereImmediateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereImmediateLowerLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereLargeLowerLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereMidiumLowerLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondItem whereSmallLowerLimit($value)
 * @mixin \Eloquent
 */
class NotCorrespondItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'not_correspond_items';

    /**
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return array
     */
    public function getEditableColumns()
    {
        return [
            'immediate_lower_limit',
            'large_lower_limit',
            'midium_lower_limit',
            'small_lower_limit',
            'immediate_date',
            'modified_user_id',
            'modified'
        ];
    }
}
