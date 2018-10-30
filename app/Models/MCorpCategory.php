<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCorpCategory
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int $genre_id ジャンル
 * @property int $category_id カテゴリ
 * @property int|null $order_fee 受注手数料
 * @property int|null $order_fee_unit 受注手数料単位
 * @property int|null $introduce_fee 紹介手数料
 * @property string|null $note 備考
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $select_list
 * @property int|null $select_genre_category 対応可能ジャンルフラグ
 * @property int|null $target_area_type 対応可能エリアタイプ
 * @property int|null $auction_status 取次方法(カテゴリー個別) 未設定:0
 * @property int|null $corp_commission_type 取次形態
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MTargetArea[] $mTargetAreas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereAuctionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereCorpCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereIntroduceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereOrderFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereOrderFeeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereSelectGenreCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereSelectList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategory whereTargetAreaType($value)
 * @mixin \Eloquent
 */
class MCorpCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_corp_categories';

    /**
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * get array field category
     *
     * @return array
     */
    public function getArrayFieldCategory()
    {
        return [
            'corp_id',
            'genre_id',
            'category_id',
            'order_fee',
            'order_fee_unit',
            'introduce_fee',
            'note',
            'select_list',
            'select_genre_category',
            'target_area_type',
            'modified_user_id',
            'modified',
            'created_user_id',
            'created',
            'corp_commission_type'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mTargetAreas()
    {
        return $this->hasMany('App\Models\MTargetArea', 'corp_category_id', 'id');
    }
}
