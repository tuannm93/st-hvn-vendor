<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\Models\MGenre
 *
 * @property int $id ジャンルID
 * @property string $genre_name ジャンル名
 * @property int|null $insurant_flg 保険料対象
 * @property string|null $note 備考
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $valid_flg 有効フラグ
 * @property string|null $genre_kana
 * @property int|null $commission_rank 取次ランク
 * @property int|null $new_genre_flg 新規ジャンルフラグ
 * @property int|null $order_fail_interval_day1 失注期限（進行中）日数
 * @property int|null $order_fail_interval_day2 失注期限（受注中）日数
 * @property int|null $default_fee デフォルト手数料
 * @property int|null $default_fee_unit デフォルト手数料単位
 * @property int|null $targer_commission_unit_price 目標取次単価
 * @property string|null $inquiry_item ヒアリング項目
 * @property string|null $attention リッツ側注意事項
 * @property int|null $registration_mediation 登録斡旋フラグ
 * @property int|null $genre_group グループ
 * @property int|null $commission_type 取次形態
 * @property bool|null $exclusion_flg 営業支援レポート除外フラグ
 * @property int|null $commission_limit_time 取次完了リミット時間
 * @property int|null $credit_unit_price 与信計算用単価
 * @property int|null $auction_fee 入札手数料
 * @property int|null $development_group 開拓区分
 * @property int|null $st_hide_flg シェアリングテクノロジー側非表示フラグ
 * @property int|null $auto_call_flag オートコールフラグ
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdditionInfo[] $additionInfos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MCategory[] $mCategories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MSiteGenres[] $mSiteGenres
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SelectGenres[] $selectGenres
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereAttention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereAuctionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereAutoCallFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereCommissionLimitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereCommissionRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereCreditUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereDefaultFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereDefaultFeeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereDevelopmentGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereExclusionFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereGenreGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereGenreKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereGenreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereInquiryItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereInsurantFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereNewGenreFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereOrderFailIntervalDay1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereOrderFailIntervalDay2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereRegistrationMediation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereStHideFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereTargerCommissionUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGenre whereValidFlg($value)
 * @mixin \Eloquent
 */
class MGenre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_genres';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionInfos()
    {
        return $this->hasMany('App\Models\AdditionInfo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mCategories()
    {
        return $this->hasMany('App\Models\MCategory', 'genre_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selectGenres()
    {
        return $this->hasMany('App\Models\SelectGenres', 'genre_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mSiteGenres()
    {
        return $this->hasMany(MSiteGenres::class, 'genre_id', 'id');
    }
}
