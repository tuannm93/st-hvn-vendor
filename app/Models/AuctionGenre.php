<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuctionGenre
 *
 * @property int $id ID
 * @property int $genre_id ジャンルID
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereAsap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereExclusionPattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereImmediately($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereImmediatelySmall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereLimitAsap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereLimitImmediately($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereNormal1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereNormal2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereNormal3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereOpenRankA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereOpenRankB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereOpenRankC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereOpenRankD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereOpenRankZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereTelHopeA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereTelHopeB($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereTelHopeC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereTelHopeD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionGenre whereTelHopeZ($value)
 * @mixin \Eloquent
 */
class AuctionGenre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_genres';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
