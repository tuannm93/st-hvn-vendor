<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCategoryCopyRule
 *
 * @property int $id ID
 * @property int $org_category_id コピー元カテゴリID
 * @property int $copy_category_id コピー先カテゴリID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereCopyCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategoryCopyRule whereOrgCategoryId($value)
 * @mixin \Eloquent
 */
class MCategoryCopyRule extends Model
{
    /**
     * @var string
     */
    protected $table = 'm_category_copyrules';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
