<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgDemandInfoTmp
 *
 * @property int $id ID
 * @property int|null $prog_demand_info_id 進捗管理案件情報ID
 * @property int|null $prog_corp_id 進捗管理企業ID
 * @property int|null $prog_import_file_id 進捗管理ファイルID
 * @property int|null $corp_id 企業ID
 * @property int|null $commission_id 取次ID
 * @property int|null $demand_id 案件ID
 * @property string|null $fee_billing_date 手数料請求日
 * @property string|null $genre_name ジャンル名
 * @property string|null $receive_datetime 受信日時
 * @property string|null $category_name カテゴリ名
 * @property string|null $customer_name お客様名
 * @property int|null $fee 手数料金額
 * @property float|null $fee_rate 手数料率
 * @property int|null $fee_target_price 手数料対象金額
 * @property int|null $agree_flag 同意チェック
 * @property int|null $diff_flg 差分フラグ
 * @property int|null $affiliation_add_flg 加盟店追加フラグ
 * @property string|null $comment_update 備考
 * @property string|null $commission_status 取次状態
 * @property string|null $commission_status_update 取次状態更新
 * @property int|null $commission_order_fail_reason 失注理由
 * @property int|null $commission_order_fail_reason_update 失注理由更新
 * @property string|null $complete_date 施工完了・失注日
 * @property string|null $complete_date_update 施工完了・失注日更新
 * @property string|null $construction_price_tax_exclude 施工金額（税抜）
 * @property string|null $construction_price_tax_exclude_update 施工金額（税抜）更新
 * @property string|null $construction_price_tax_include 施工金額（税込）
 * @property string|null $construction_price_tax_include_update 施工金額（税込）更新
 * @property string|null $ip_address_update IPアドレス
 * @property string|null $user_agent_update ユーザーエージェント
 * @property string|null $host_name_update ホスト名
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成ユーザID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $modified_user_id 更新ユーザID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereAffiliationAddFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCommentUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCommissionOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCommissionOrderFailReasonUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCommissionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCommissionStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCompleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCompleteDateUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereConstructionPriceTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereConstructionPriceTaxExcludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereConstructionPriceTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereConstructionPriceTaxIncludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereDiffFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereFeeBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereFeeTargetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereGenreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereHostNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereIpAddressUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereProgDemandInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereReceiveDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoTmp whereUserAgentUpdate($value)
 * @mixin \Eloquent
 */
class ProgDemandInfoTmp extends Model
{
    /**
     * @var string
     */
    protected $table = 'prog_demand_info_tmps';
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * map updated_at of eloquent with modified
     */
    const UPDATED_AT = 'modified';
}
