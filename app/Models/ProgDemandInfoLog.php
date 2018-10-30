<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgDemandInfoLog
 *
 * @property int $id ID
 * @property int $prog_demand_info_id 進捗管理案件情報ID
 * @property int $prog_corp_id 進捗管理企業ID
 * @property int $prog_import_file_id 進捗管理ファイルID
 * @property int $corp_id 企業ID
 * @property int|null $commission_id 取次ID
 * @property int $demand_id 案件ID
 * @property string|null $category_name カテゴリ名
 * @property string|null $customer_name お客様名
 * @property int|null $fee 手数料金額
 * @property float|null $fee_rate 手数料率
 * @property int|null $fee_target_price 手数料対象金額
 * @property int|null $agree_flag 同意チェック
 * @property int|null $diff_flg 差分フラグ
 * @property int|null $affiliation_add_flg 加盟店追加フラグ
 * @property string|null $comment_update 備考
 * @property string $commission_status 取次状態
 * @property string $commission_status_update 取次状態更新
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
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新ユーザID
 * @property string|null $fee_billing_date 手数料請求日
 * @property string|null $genre_name ジャンル名
 * @property string|null $receive_datetime 受信日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereAffiliationAddFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCommentUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCommissionOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCommissionOrderFailReasonUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCommissionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCommissionStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCompleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCompleteDateUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereConstructionPriceTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereConstructionPriceTaxExcludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereConstructionPriceTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereConstructionPriceTaxIncludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereDiffFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereFeeBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereFeeTargetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereGenreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereHostNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereIpAddressUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereProgDemandInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereReceiveDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoLog whereUserAgentUpdate($value)
 * @mixin \Eloquent
 */
class ProgDemandInfoLog extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];
}
