<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CorpCategoryApplication
 *
 * @property int $id ID
 * @property int $group_id グループID
 * @property int $corp_id 企業ID
 * @property int $genre_id ジャンル
 * @property int $category_id カテゴリ
 * @property int|null $order_fee 受注手数料
 * @property int|null $order_fee_unit 受注手数料単位
 * @property int|null $introduce_fee 紹介手数料
 * @property string|null $note 備考
 * @property int|null $corp_commission_type 取次形態
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereCorpCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereIntroduceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereOrderFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryApplication whereOrderFeeUnit($value)
 * @mixin \Eloquent
 */
class CorpCategoryApplication extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'corp_category_applications';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
