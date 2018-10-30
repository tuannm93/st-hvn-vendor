<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuctionAgreementItem
 *
 * @property int $id ID
 * @property int $auction_agreement_provisions_id 入札契約条文マスタID
 * @property string|null $item 項目
 * @property int $sort_no 表示順
 * @property int $last_history_id 最新履歴ID
 * @property int $version_no バージョンNo
 * @property string|null $modified_user_id 更新ユーザーID
 * @property string|null $modified 更新日
 * @property string|null $created_user_id 登録ユーザーID
 * @property string|null $created 登録日
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereAuctionAgreementProvisionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereLastHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereSortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementItem whereVersionNo($value)
 * @mixin \Eloquent
 */
class AuctionAgreementItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'auction_agreement_provisions_items';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
