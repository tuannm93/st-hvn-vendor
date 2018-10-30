<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuctionInfo
 *
 * @property int $id オークション情報ID
 * @property int $demand_id 案件ID
 * @property int $corp_id 企業ID
 * @property string|null $responders 対応者
 * @property int|null $visit_time_id 訪問日時ID
 * @property string|null $push_time 送信予定時間
 * @property int|null $push_flg 送信フラグ
 * @property int|null $before_push_flg 事前通知メールフラグ
 * @property string|null $first_display_time 案件表示時間
 * @property int|null $display_flg 非表示フラグ
 * @property int|null $refusal_flg 辞退フラグ
 * @property string|null $modified_user_id 更新者
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者
 * @property string|null $created 作成日時
 * @property string|null $auto_call_time オートコール発信時間
 * @property int|null $auto_call_flg オートコールフラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereAutoCallFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereAutoCallTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereBeforePushFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereDisplayFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereFirstDisplayTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo wherePushFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo wherePushTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereRefusalFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereResponders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionInfo whereVisitTimeId($value)
 * @mixin \Eloquent
 */
class AuctionInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_infos';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
