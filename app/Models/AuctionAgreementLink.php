<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuctionAgreementLink
 *
 * @property int $id ID
 * @property int $auction_id オークション情報ID
 * @property int $corp_id 企業ID
 * @property int $auction_agreement_id 入札手数料同意書マスタID
 * @property int $demand_id 案件ID
 * @property int $commission_id 取次ID
 * @property int|null $auction_fee 入札手数料
 * @property bool|null $agreement_check 同意チェック
 * @property string|null $responders 対応者
 * @property string|null $modified_user_id 更新者
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereAgreementCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereAuctionAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereAuctionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AuctionAgreementLink whereResponders($value)
 * @mixin \Eloquent
 */
class AuctionAgreementLink extends Model
{
    /**
     * @var string
     */
    protected $table = 'auction_agreement_links';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
