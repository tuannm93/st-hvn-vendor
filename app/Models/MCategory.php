<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCategory
 *
 * @property int $id カテゴリID
 * @property int|null $genre_id ジャンルID
 * @property string|null $category_name カテゴリ名
 * @property string|null $note 備考
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $valid_flg
 * @property int|null $hide_flg 非表示フラグ
 * @property int|null $display_order 表示順序
 * @property int|null $category_default_fee デフォルト手数料
 * @property int|null $category_default_fee_unit デフォルト手数料単位
 * @property int|null $license_condition_type ライセンス確認条件
 *  1: AND条件
 *  2: OR条件
 * @property bool|null $disable_flg 無効フラグ
 * @property int|null $st_hide_flg シェアリングテクノロジー側非表示フラグ
 * @property-read \App\Models\MGenre $mGenres
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MSiteCateogry[] $mSiteCategory
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereCategoryDefaultFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereCategoryDefaultFeeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereDisableFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereHideFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereLicenseConditionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereStHideFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCategory whereValidFlg($value)
 * @mixin \Eloquent
 */
class MCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_categories';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    const DISABLE_FLG_TRUE = true;
    const DISABLE_FLG_FALSE = false;

    const AND_LICENSE_CONDITION = 1;
    const OR_LICENSE_CONDITION = 2;
    const LICENSE_CONDITION_TYPE = [
        self::AND_LICENSE_CONDITION => 'AND条件',
        self::OR_LICENSE_CONDITION => 'OR条件'
    ];

    const STOP_CATEGORY = '取次STOPカテゴリ';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mGenres()
    {
        return $this->hasOne('App\Models\MGenre', 'id', 'genre_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mSiteCategory()
    {
        return $this->hasMany(MSiteCateogry::class, 'category_id', 'id');
    }

    /**
     * @return mixed|string
     */
    public static function getDefaultFee($categoryId = null)
    {
        $query = self::orderBy('id', 'asc');
        if(!empty($categoryId)){
            $query->where('id', $categoryId);
        }
        $mCategory = $query->first();
        return $mCategory ? $mCategory->category_default_fee_unit : '';
    }
}
