<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SelectGenrePrefecture
 *
 * @property int $id ID
 * @property int $genre_id ジャンルID
 * @property string $prefecture_cd 都道府県コード
 * @property int|null $selection_type 選定方式
 * @property int|null $business_trip_amount 出張費
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $auction_fee 入札手数料
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereAuctionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereBusinessTripAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture wherePrefectureCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenrePrefecture whereSelectionType($value)
 * @mixin \Eloquent
 */
class SelectGenrePrefecture extends Model
{
    /**
     * @var string
     */
    public $table = 'select_genre_prefectures';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
