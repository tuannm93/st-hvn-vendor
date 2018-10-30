<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Refusals
 *
 * @property int $id ID
 * @property int $auction_id オークションID
 * @property string|null $corresponds_time1 対応可能時間1
 * @property string|null $corresponds_time2 対応可能時間2
 * @property string|null $corresponds_time3 対応可能時間3
 * @property int|null $cost_from 価格from
 * @property int|null $cost_to 価格to
 * @property string|null $other_contens その他詳細
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $not_available_flg 対応不可案件フラグ
 * @property string|null $estimable_time_from 見積り可能日時from
 * @property string|null $contactable_time_from 連絡可能日時from
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereContactableTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCorrespondsTime1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCorrespondsTime2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCorrespondsTime3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCostFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCostTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereEstimableTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereNotAvailableFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Refusals whereOtherContens($value)
 * @mixin \Eloquent
 */
class Refusals extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'refusals';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
