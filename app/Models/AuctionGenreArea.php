<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuctionGenreArea
 *
 * @property int $id ID
 * @property int $genre_id ジャンルID
 * @property string $prefecture_cd 都道府県コード
 * @property int|null $exclusion_pattern 除外時間パターン
 * @property int|null $limit_asap 大至急案件判定時間
 * @property int|null $limit_immediately 至急案件判定時間
 * @property int|null $asap 大至急案件締め切り時間
 * @property int|null $immediately 至急案件締め切り時間
 * @property int|null $normal1 通常案件締め切り時間
 * @property int|null $normal2 通常案件締め切り時間2
 * @property int|null $open_rank_a 開放ランクa
 * @property int|null $open_rank_b 開放ランクb
 * @property int|null $open_rank_c 開放ランクc
 * @property int|null $open_rank_d 開放ランクd
 * @property int|null $tel_hope_a 電話希望案件a
 * @property int|null $tel_hope_b 電話希望案件b
 * @property int|null $tel_hope_c 電話希望案件c
 * @property int|null $tel_hope_d 電話希望案件d
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $open_rank_z 開放ランクz
 * @property int|null $tel_hope_z 電話希望案件z
 * @property int|null $immediately_small 至急案件締め切り時間(猶予なし時設定)
 * @property int|null $normal3 通常案件入札までの時間
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereAsap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereExclusionPattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereImmediately($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereImmediatelySmall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereLimitAsap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereLimitImmediately($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereNormal1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereNormal2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereNormal3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereOpenRankA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereOpenRankB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereOpenRankC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereOpenRankD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereOpenRankZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea wherePrefectureCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereTelHopeA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereTelHopeB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereTelHopeC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereTelHopeD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenreArea whereTelHopeZ($value)
 * @mixin \Eloquent
 */
class AuctionGenreArea extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_genre_areas';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
