<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AffiliationInfo
 *
 * @property int $id 加盟店情報ID
 * @property int $corp_id 企業ID
 * @property string|null $employees 従業員数
 * @property string|null $max_commission 月間最大取次数
 * @property int|null $collection_method 代金徴収方法
 * @property string|null $collection_method_others その他代金徴収方法
 * @property int|null $liability_insurance 賠償責任保険
 * @property string|null $reg_follow_date1 登録書後追い日1
 * @property string|null $reg_follow_date2 登録書後追い日2
 * @property string|null $reg_follow_date3 登録書後追い日3
 * @property int|null $waste_collect_oath 不用品回収誓約書
 * @property string|null $transfer_name 振込名義
 * @property int|null $claim_count 顧客クレーム回数
 * @property string|null $claim_history 顧客クレーム履歴
 * @property int|null $commission_count 取次件数
 * @property int|null $weekly_commission_count 取次件数(一週間)
 * @property int|null $orders_count 受注数
 * @property float|null $orders_rate 受注率
 * @property int|null $construction_cost 施工金額
 * @property int|null $fee 手数料金額
 * @property int|null $bill_price 請求金額
 * @property int|null $payment_price 入金金額
 * @property int|null $balance 残高
 * @property int|null $construction_unit_price 施工単価
 * @property int|null $commission_unit_price 取次単価
 * @property int|null $sf_construction_unit_price SF取次単価
 * @property int|null $sf_construction_count SF取次件数
 * @property string|null $reg_info 登録書情報
 * @property string|null $reg_pdf_path 登録書PDF
 * @property string|null $attention 注意事項
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $credit_limit 与信限度額
 * @property string|null $listed_kind 上場
 * @property bool|null $default_tax 税金
 * @property string|null $capital_stock 資本金
 * @property int|null $add_month_credit 当月振込前払金
 * @property string|null $virtual_account 与信振込口座番号
 * @property int|null $credit_mail_send_flg 与信メール送信フラグ
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AffiliationSub[] $affiliationSubs
 * @property-read \App\Models\MCorp $mCorp
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereAddMonthCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereAttention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereBillPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCapitalStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereClaimCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereClaimHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCollectionMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCollectionMethodOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCommissionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCommissionUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereConstructionCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereConstructionUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereCreditMailSendFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereDefaultTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereLiabilityInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereListedKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereMaxCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereOrdersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereOrdersRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo wherePaymentPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereRegFollowDate1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereRegFollowDate2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereRegFollowDate3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereRegInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereRegPdfPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereSfConstructionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereSfConstructionUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereTransferName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereVirtualAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereWasteCollectOath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationInfo whereWeeklyCommissionCount($value)
 * @mixin \Eloquent
 */
class AffiliationInfo extends Model
{
    const LISTED = 'listed';
    const UNLISTED = 'unlisted';
    const LISTED_KIND = [
        null => '',
        self::LISTED => '上場',
        self::UNLISTED => '非上場',
    ];
    const CUSTOMIZE_LABEL = '特約';
    const CSV_FORMAT = [
        'm_corps_id' => '企業ID',
        'm_corps_corp_name' => '企業名',
        'm_corps_corp_name_kana' => '企業名ふりがな',
        'm_corps_official_corp_name' => '正式企業名',
        'm_corps_corp_commission_status' => '取次状況',
        'm_corps_affiliation_status' => '加盟状態',
        'm_corps_responsibility' => '責任者　※必須',
        'm_corps_corp_person' => '担当者　※必須',
        'm_corps_postcode' => '郵便番号',
        'm_corps_address1' => '都道府県　※必須',
        'm_corps_address2' => '市区町村　※必須',
        'm_corps_address3' => '町域　※必須',
        'm_corps_address4' => '丁目番地',
        'm_corps_building' => '建物名',
        'm_corps_room' => '部屋号数',
        'm_corps_trade_name1' => '屋号①',
        'm_corps_trade_name2' => '屋号②',
        'm_corps_commission_dial' => '取次用ダイヤル　※必須',
        'm_corps_tel1' => '電話番号①　※必須',
        'm_corps_tel2' => '電話番号②',
        'm_corps_mobile_tel' => '携帯電話番号',
        'm_corps_fax' => 'FAX番号　※必須',
        'm_corps_mailaddress_pc' => 'PCメール　※必須',
        'm_corps_mobile_mail_none' => '携帯メールなし　※必須',
        'm_corps_mobile_tel_type' => '携帯種別　※必須',
        'm_corps_mailaddress_mobile' => '携帯メール　※必須',
        'm_corps_url' => 'URL',
        'm_corps_target_range' => '対応範囲(半径km)',
        'm_corps_available_time' => '現場対応可能時間_旧',
        'm_corps_contactable_time' => '連絡可能時間_旧',
        'm_corps_contactable_support24hour' => '連絡可能時間_24H　※必須',
        'm_corps_contactable_time_other' => '連絡可能時間_その他　※必須',
        'm_corps_contactable_time_from' => '連絡可能時間_From　※必須',
        'm_corps_contactable_time_to' => '連絡可能時間_To　※必須',
        'm_corps_support24hour' => '営業時間_24H　※必須',
        'm_corps_available_time_other' => '営業時間_その他　※必須',
        'm_corps_available_time_from' => '営業時間_From　※必須',
        'm_corps_available_time_to' => '営業時間_To　※必須',
        'm_corps_holiday' => '休業日　※必須',
        'm_corps_free_estimate' => '無料見積対応',
        'm_corps_portalsite' => 'ポータルサイト掲載',
        'm_corps_reg_send_date' => '登録書発送日',
        'm_corps_reg_send_method' => '登録書発送方法',
        'm_corps_reg_collect_date' => '登録書回収日',
        'm_corps_ps_app_send_date' => 'PS申込書発送日',
        'm_corps_ps_app_collect_date' => 'PS申込書回収日',
        'm_corps_coordination_method' => '取次方法　※必須',
        'm_corps_prog_send_method' => '進捗表送付方法',
        'm_corps_prog_send_mail_address' => '進捗表メール送付先',
        'm_corps_prog_send_fax' => '進捗表FAX送付先',
        'm_corps_prog_irregular' => '進捗表イレギュラー',
        'm_corps_special_agreement_check' => '請求時特約確認要',
        'm_corps_bill_send_method' => '請求書送付方法',
        'm_corps_bill_send_address' => '請求書送付先',
        'm_corps_bill_irregular' => '請求書イレギュラー',
        'm_corps_special_agreement' => '特約事項',
        'm_corps_development_response' => '開拓時の反応',
        'm_corps_contract_date' => '獲得日',
        'm_corps_order_fail_date' => '失注日',
        'm_corps_geocode_lat' => '緯度',
        'm_corps_geocode_long' => '経度',
        'm_corps_note' => '備考欄',
        'm_corps_seikatsu110_id' => '生活110番ID',
        'm_corps_modified_user_id' => '企業情報更新ID',
        'm_corps_modified' => '企業情報更新日',
        'm_corps_corp_commission_type' => '企業取次形態',
        'm_corps_jbr_available_status' => 'JBR対応状況',
        'affiliation_infos_id' => '加盟店情報ID',
        'affiliation_infos_liability_insurance' => '賠償責任保険',
        'affiliation_infos_reg_follow_date1' => '登録書後追い日1',
        'affiliation_infos_reg_follow_date2' => '登録書後追い日2',
        'affiliation_infos_reg_follow_date3' => '登録書後追い日3',
        'affiliation_infos_waste_collect_oath' => '振込名義',
        'affiliation_infos_stop_category_name' => '取次STOPカテゴリ',
        'affiliation_infos_claim_count' => '顧客クレーム回数',
        'affiliation_infos_claim_history' => '顧客クレーム履歴',
        'affiliation_infos_commission_count' => '取次件数',
        'affiliation_infos_weekly_commission_count' => '取次件数(一週間)',
        'affiliation_infos_orders_count' => '受注数',
        'affiliation_infos_orders_rate' => '受注率',
        'affiliation_infos_construction_cost' => '施工金額',
        'affiliation_infos_fee' => '手数料金額',
        'affiliation_infos_bill_price' => '請求金額',
        'affiliation_infos_payment_price' => '入金金額',
        'affiliation_infos_balance' => '残高',
        'affiliation_infos_construction_unit_price' => '施工単価',
        'affiliation_infos_commission_unit_price' => '取次単価',
        'affiliation_infos_reg_info' => '登録書情報',
        'affiliation_infos_reg_pdf_path' => '登録書PDF',
        'affiliation_infos_attention' => '注意事項',
        'corp_mcc_modified' => 'ジャンル最終更新日',
        'corp_mct_modified' => '基本エリア最終更新日',
        'm_corps_corp_kind' => '法人・個人',
        'affiliation_infos_capital_stock' => '資本金',
        'affiliation_infos_employees' => '従業員数',
        'affiliation_infos_listed_kind' => '上場',
        'affiliation_infos_default_tax' => '税金',
        'affiliation_infos_max_commission' => '月間最大取次数',
        'affiliation_infos_collection_method' => '代金徴収方法',
        'affiliation_infos_collection_method_others' => 'その他代金徴収方法',
        'affiliation_infos_credit_limit' => '与信限度額',
        'affiliation_infos_add_month_credit' => '当月振込前払金',
        'affiliation_infos_virtual_account' => '与信振込口座番号',
        'm_corps_commission_accept_flg' => '契約更新フラグ',
        'm_corps_auction_status' => '取次方法',
        'CorpAgreement.acceptation_date' => '初回契約承認日時',
        'm_corps_payment_site' => '支払サイト'
    ];
    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliation_infos';
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mCorp()
    {
        return $this->belongsTo(MCorp::class, 'corp_id', 'id');
    }

    /**
     * @param $corpId
     * @return $this
     */
    public function getOneMCorp($corpId)
    {
        return $this->mCorp()->where('corp_id', $corpId);
    }
    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @end
     */
    public function affiliationSubs()
    {
        return $this->hasMany('App\Models\AffiliationSub', 'affiliation_id', 'id');
    }
}
