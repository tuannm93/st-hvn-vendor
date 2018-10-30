<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MSiteCateogry
 *
 * @author cuongnguyenx
 * @property int $id ID
 * @property int $site_id サイトID
 * @property int $category_id カテゴリID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property-read \App\Models\MCategory $mCategory
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteCateogry whereSiteId($value)
 * @mixin \Eloquent
 */
class MSiteCateogry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_site_categories';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mCategory()
    {
        return $this->belongsTo(MCategory::class, 'category_id', 'id');
    }
}
