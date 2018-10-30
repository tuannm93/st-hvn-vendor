<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CommissionInfo
 *
 * @property int $id 取次ID
 * @property int $demand_id 案件ID
 * @property int|null $corp_id 取次先企業
 * @property int|null $commit_flg 確定フラグ
 * @property int|null $commission_type 取次種別
 * @property int|null $lost_flg 取次前失注フラグ
 * @property string|null $appointers 選定者
 * @property int|null $first_commission 初取次チェック
 * @property int|null $corp_fee 取次先手数料
 * @property int|null $waste_collect_oath 不用品回収誓約書
 * @property string|null $attention 注意事項
 * @property string|null $commission_dial 取次用ダイヤル
 * @property string|null $tel_commission_datetime 電話取次日時
 * @property string|null $tel_commission_person 電話取次者
 * @property float|null $commission_fee_rate 取次時手数料率
 * @property string|null $commission_note_send_datetime 取次票送信日時
 * @property string|null $commission_note_sender 取次票送信者
 * @property int|null $commission_status 取次状況
 * @property int|null $commission_order_fail_reason 取次失注理由
 * @property string|null $complete_date 施工完了日
 * @property string|null $order_fail_date 失注日
 * @property int|null $estimate_price_tax_exclude 見積金額(税抜)
 * @property int|null $construction_price_tax_exclude 施工金額(税抜)
 * @property int|null $construction_price_tax_include 施工金額(税込)
 * @property int|null $deduction_tax_include 控除金額(税込)
 * @property int|null $deduction_tax_exclude 控除金額(税抜)
 * @property float|null $confirmd_fee_rate 確定手数料率
 * @property int|null $unit_price_calc_exclude 取次単価対象外
 * @property string|null $report_note 報告備考欄
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int $del_flg 削除フラグ
 * @property int $checked_flg 確認済みフラグ
 * @property int|null $reported_flg 報告済みフラグ
 * @property float|null $irregular_fee_rate イレギュラー手数料率
 * @property int|null $irregular_fee イレギュラー手数料金額
 * @property int|null $falsity 虚偽報告確認済み
 * @property string|null $follow_date 後追い日
 * @property int|null $introduction_not 紹介不可
 * @property int|null $lock_status 進捗案件ロック
 * @property string|null $commission_status_last_updated 取次管理最終更新日時
 * @property int|null $progress_reported 進捗表回収済みフラグ
 * @property string|null $progress_report_datetime 進捗表回収日時
 * @property int|null $introduction_free 紹介無料
 * @property int|null $app_notread アプリ未読フラグ
 * @property int|null $app_push_flg アプリ通知済みフラグ
 * @property int|null $send_mail_fax メール/FAX送信済みフラグ
 * @property string|null $send_mail_fax_datetime メール/FAX送信日時
 * @property string|null $select_commission_unit_price_rank 取次単価ランク
 * @property int|null $business_trip_amount 出張費
 * @property int|null $select_commission_unit_price 選定時取次単価
 * @property string|null $send_mail_fax_sender メール/FAX送信送信者
 * @property int|null $reform_upsell_ic リフォームアップセルIC
 * @property int|null $re_commission_exclusion_status 営業支援除外ステータス
 * @property string|null $re_commission_exclusion_user_id 除外者
 * @property string|null $re_commission_exclusion_datetime 除外日時
 * @property int|null $tel_support 電話対応状況
 * @property int|null $visit_support 訪問対応状況
 * @property int|null $order_support 受注対応状況
 * @property string|null $commission_visit_time
 * @property string|null $commission_order_init_time
 * @property string|null $remand_reason
 * @property string|null $remand_correspond_person
 * @property string|null $visit_desired_time 訪問希望日時
 * @property string|null $order_respond_datetime 受注対応日時
 * @property int|null $send_mail_fax_othersend 個別送信フラグ
 * @property int|null $commission_visit_time_id 訪問日時ID
 * @property int $irregular_reason イレギュラー理由
 * @property bool|null $ac_commission_exclusion_flg 入札手数料除外フラグ
 * @property int|null $order_fee_unit 取次先手数料単位
 * @property string|null $fee_billing_date 手数料請求日
 * @property int|null $corp_claim_flg 代理店クレームフラグ
 * @property-read \App\Models\AffiliationInfo $affiliationInfo
 * @property-read \App\Models\BillInfo $billInfo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BillInfo[] $billInfos
 * @property-read \App\Models\DemandInfo $demandInfo
 * @property-read false|string $commission_note_send_datetime_format
 * @property-read string $corp_commission_type_disp
 * @property-read string $order_fee_unit_label
 * @property-read \App\Models\MCorp|null $mCorp
 * @property-read \App\Models\MItem|null $mItem
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereAcCommissionExclusionFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereAppNotread($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereAppPushFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereAppointers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereAttention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereBusinessTripAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCheckedFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionDial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionNoteSendDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionNoteSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionOrderInitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionStatusLastUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionVisitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommissionVisitTimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCommitFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCompleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereConfirmdFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereConstructionPriceTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereConstructionPriceTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCorpClaimFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCorpFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereDeductionTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereDeductionTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereEstimatePriceTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereFalsity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereFeeBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereFirstCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereFollowDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereIntroductionFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereIntroductionNot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereIrregularFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereIrregularFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereIrregularReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereLockStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereLostFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereOrderFailDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereOrderFeeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereOrderRespondDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereOrderSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereProgressReportDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereProgressReported($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereReCommissionExclusionDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereReCommissionExclusionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereReCommissionExclusionUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereReformUpsellIc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereRemandCorrespondPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereRemandReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereReportNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereReportedFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereSelectCommissionUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereSelectCommissionUnitPriceRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereSendMailFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereSendMailFaxDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereSendMailFaxOthersend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereSendMailFaxSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereTelCommissionDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereTelCommissionPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereTelSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereUnitPriceCalcExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereVisitDesiredTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereVisitSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionInfo whereWasteCollectOath($value)
 * @mixin \Eloquent
 */
class CommissionInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commission_infos';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    const UPDATED_AT = 'modified';
    const CREATED_AT = 'created';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return $this
     */
    public function mCorp()
    {
        return $this->belongsTo('App\Models\MCorp', 'corp_id', 'id')->with('affiliationInfo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function demandInfo()
    {
        return $this->belongsTo('App\Models\DemandInfo', 'demand_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mItem()
    {
        return $this->belongsTo('App\Models\MItem', 'commission_status', 'item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billInfos()
    {
        return $this->hasMany('App\Models\BillInfo', 'commission_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billInfo()
    {
        return $this->hasOne('App\Models\BillInfo', 'commission_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demandNotification()
    {
        return $this->hasMany('App\Models\DemandNotification', 'commission_id', 'id');
    }

    /**
     * create format csv for list jbr receipt follow
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'demand_id' => trans('jbr_receipt_follow.demand_id'),
            'official_corp_name' => trans('jbr_receipt_follow.official_corp_name'),
            'genre_name' => trans('jbr_receipt_follow.genre_name'),
            'commission_id' => trans('jbr_receipt_follow.commission_id'),
            'jbr_order_no' => trans('jbr_receipt_follow.jbr_order_no'),
            'customer_name' => trans('jbr_receipt_follow.customer_name'),
            'complete_date' => trans('jbr_receipt_follow.complete_date'),
            'construction_price_tax_include' => trans('jbr_receipt_follow.construction_price_tax_include'),
            'MItem_item_name' => trans('jbr_receipt_follow.MItem_item_name'),
            'MItem2_item_name' => trans('jbr_receipt_follow.MItem2_item_name'),
        ];
    }


    /**
     * @author Dung.PhamVan@nashtechglobal.com
     */
    public function mCorpAndMCorpNewYear()
    {
        return $this->mCorp()->with('mCorpNewYear');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function affiliationInfo()
    {
        return $this->hasOne('App\Models\AffiliationInfo', 'corp_id', 'corp_id');
    }

    /**
     * @return false|string
     */
    public function getCommissionNoteSendDatetimeFormatAttribute()
    {
        return dateTimeFormat($this->getAttribute('commission_note_send_datetime'));
    }

    /**
     * @return string
     */
    public function getOrderFeeUnitLabelAttribute()
    {
        $categoryId = $this->demandInfo ? $this->demandInfo->category_id : null;
        $categoryDefaultFee = MCategory::getDefaultFee($categoryId);
        $feeUnit = $this->getAttribute('order_fee_unit') ?? $categoryDefaultFee;

        return $this->getAttribute('commission_fee_rate') && $feeUnit == 1 ? '取次時手数料率' : '取次先手数料';
    }

    /**
     * @return string
     */
    public function getCorpCommissionTypeDispAttribute()
    {
        $displayData = $this->getAttribute('commission_type') == 1 ? '紹介 ' : '成約 ';
        $corpFee = $this->getAttribute('corp_fee');
        $feeUnit = $this->getAttribute('order_fee_unit');

        $feeRate = $this->getAttribute('commission_fee_rate');

        if(!empty($corpFee)){
            $displayData .= $corpFee . ' 円';
        }
        if(!empty($feeRate) && $feeUnit == 1){
            $displayData .= $feeRate . ' ％';
        }

        return $displayData;
    }

    /**
     * @return mixed|string
     */
    public function getFeeUnit()
    {
        $categoryDefaultFee = MCategory::getDefaultFee();
        $orderFeeUnit = $this->getAttribute('order_fee_unit');

        return is_null($orderFeeUnit) ? $orderFeeUnit : $categoryDefaultFee;
    }

    /**
     * @return array
     */
    public static function csvFieldList()
    {
        $csvFields = [];
        foreach (\Schema::getColumnListing('demand_infos') as $column) {
            $csvFields[] = "demand_infos." . $column . " AS demand_infos" . '_' . $column;
        }
        $addColumn = [
            'commission_infos.id',
            'commission_infos.commit_flg',
            'commission_infos.commission_type',
            'commission_infos.appointers',
            'commission_infos.first_commission',
            'commission_infos.corp_fee',
            'commission_infos.attention',
            'commission_infos.commission_dial',
            'commission_infos.tel_commission_datetime',
            'commission_infos.tel_commission_person',
            'commission_infos.commission_fee_rate',
            'commission_infos.commission_note_send_datetime',
            'commission_infos.commission_note_sender',
            'commission_infos.commission_status',
            'commission_infos.commission_order_fail_reason',
            'commission_infos.complete_date',
            'commission_infos.order_fail_date',
            'commission_infos.estimate_price_tax_exclude',
            'commission_infos.construction_price_tax_exclude',
            'commission_infos.construction_price_tax_include',
            'commission_infos.deduction_tax_include',
            'commission_infos.deduction_tax_exclude',
            'commission_infos.confirmd_fee_rate',
            'commission_infos.unit_price_calc_exclude',
            'commission_infos.report_note',
            'commission_infos.corp_id',
            'commission_infos.send_mail_fax',
            'commission_infos.send_mail_fax_datetime',
            'm_corps.corp_name',
            'm_corps.official_corp_name',
            'm_sites.site_name',
            'm_genres.genre_name',
            'm_categories.category_name'
        ];

        foreach ($addColumn as $column) {
            $field = explode(".", $column);
            $table = $field[0];
            $columnName = $field[1];
            $csvFields[] = "$table." . $columnName . " AS $table" . '_' . $columnName;
        }
        return $csvFields;
    }

    /**
     * @return array
     */
    public static function csvFieldListFormat()
    {
        return [
            'demand_infos_id' => trans('commissioninfos.csv_field.demand_infos_id'),
            'demand_infos_follow_date' => trans('commissioninfos.csv_field.demand_infos_follow_date'),
            'demand_infos_demand_status' => trans('commissioninfos.csv_field.demand_infos_demand_status'),
            'demand_infos_order_fail_reason' => trans('commissioninfos.csv_field.demand_infos_order_fail_reason'),
            'demand_infos_mail_demand' => trans('commissioninfos.csv_field.demand_infos_mail_demand'),
            'demand_infos_nighttime_takeover' => trans('commissioninfos.csv_field.demand_infos_nighttime_takeover'),
            'demand_infos_low_accuracy' => trans('commissioninfos.csv_field.demand_infos_low_accuracy'),
            'demand_infos_remand' => trans('commissioninfos.csv_field.demand_infos_remand'),
            'demand_infos_immediately' => trans('commissioninfos.csv_field.demand_infos_immediately'),
            'demand_infos_corp_change' => trans('commissioninfos.csv_field.demand_infos_corp_change'),
            'demand_infos_receive_datetime' => trans('commissioninfos.csv_field.demand_infos_receive_datetime'),
            'demand_infos_site_name' => trans('commissioninfos.csv_field.demand_infos_site_name'),
            'demand_infos_genre_name' => trans('commissioninfos.csv_field.demand_infos_genre_name'),
            'demand_infos_category_name' => trans('commissioninfos.csv_field.demand_infos_category_name'),
            'demand_infos_cross_sell_source_site' => trans('commissioninfos.csv_field.demand_infos_cross_sell_source_site'),
            'demand_infos_cross_sell_source_genre' => trans('commissioninfos.csv_field.demand_infos_cross_sell_source_genre'),
            'demand_infos_cross_sell_source_category' => trans('commissioninfos.csv_field.demand_infos_cross_sell_source_category'),
            'demand_infos_source_demand_id' => trans('commissioninfos.csv_field.demand_infos_source_demand_id'),
            'demand_infos_same_customer_demand_url' => trans('commissioninfos.csv_field.demand_infos_same_customer_demand_url'),
            'demand_infos_receptionist' => trans('commissioninfos.csv_field.demand_infos_receptionist'),
            'demand_infos_customer_name' => trans('commissioninfos.csv_field.demand_infos_customer_name'),
            'demand_infos_customer_corp_name' => trans('commissioninfos.csv_field.demand_infos_customer_corp_name'),
            'demand_infos_customer_tel' => trans('commissioninfos.csv_field.demand_infos_customer_tel'),
            'demand_infos_customer_mailaddress' => trans('commissioninfos.csv_field.demand_infos_customer_mailaddress'),
            'demand_infos_postcode' => trans('commissioninfos.csv_field.demand_infos_postcode'),
            'demand_infos_address1' => trans('commissioninfos.csv_field.demand_infos_address1'),
            'demand_infos_address2' => trans('commissioninfos.csv_field.demand_infos_address2'),
            'demand_infos_address3' => trans('commissioninfos.csv_field.demand_infos_address3'),
            'demand_infos_tel1' => trans('commissioninfos.csv_field.demand_infos_tel1'),
            'demand_infos_tel2' => trans('commissioninfos.csv_field.demand_infos_tel2'),
            'demand_infos_contents' => trans('commissioninfos.csv_field.demand_infos_contents'),
            'demand_infos_contact_desired_time' => trans('commissioninfos.csv_field.demand_infos_contact_desired_time'),
            'demand_infos_selection_system' => trans('commissioninfos.csv_field.demand_infos_selection_system'),
            'demand_infos_pet_tombstone_demand' => trans('commissioninfos.csv_field.demand_infos_pet_tombstone_demand'),
            'demand_infos_sms_demand' => trans('commissioninfos.csv_field.demand_infos_sms_demand'),
            'demand_infos_order_no_marriage' => trans('commissioninfos.csv_field.demand_infos_order_no_marriage'),
            'demand_infos_jbr_order_no' => trans('commissioninfos.csv_field.demand_infos_jbr_order_no'),
            'demand_infos_jbr_work_contents' => trans('commissioninfos.csv_field.demand_infos_jbr_work_contents'),
            'demand_infos_jbr_category' => trans('commissioninfos.csv_field.demand_infos_jbr_category'),
            'demand_infos_mail' => trans('commissioninfos.csv_field.demand_infos_mail'),
            'demand_infos_order_date' => trans('commissioninfos.csv_field.demand_infos_order_date'),
            'demand_infos_complete_date' => trans('commissioninfos.csv_field.demand_infos_complete_date'),
            'demand_infos_order_fail_date' => trans('commissioninfos.csv_field.demand_infos_order_fail_date'),
            'demand_infos_jbr_estimate_status' => trans('commissioninfos.csv_field.demand_infos_jbr_estimate_status'),
            'demand_infos_jbr_receipt_status' => trans('commissioninfos.csv_field.demand_infos_jbr_receipt_status'),
            'demand_infos_acceptance_status' => trans('commissioninfos.csv_field.demand_infos_acceptance_status'),
            'demand_infos_nitoryu_flg' => trans('commissioninfos.csv_field.demand_infos_nitoryu_flg'),
            'commission_infos_id' => trans('commissioninfos.csv_field.commission_infos_id'),
            'commission_infos_corp_id' => trans('commissioninfos.csv_field.commission_infos_corp_id'),
            'm_corps_corp_name' => trans('commissioninfos.csv_field.m_corps_corp_name'),
            'm_corps_official_corp_name' => trans('commissioninfos.csv_field.m_corps_official_corp_name'),
            'commission_infos_commit_flg' => trans('commissioninfos.csv_field.commission_infos_commit_flg'),
            'commission_infos_commission_type' => trans('commissioninfos.csv_field.commission_infos_commission_type'),
            'commission_infos_appointers' => trans('commissioninfos.csv_field.commission_infos_appointers'),
            'commission_infos_first_commission' => trans('commissioninfos.csv_field.commission_infos_first_commission'),
            'commission_infos_corp_fee' => trans('commissioninfos.csv_field.commission_infos_corp_fee'),
            'commission_infos_attention' => trans('commissioninfos.csv_field.commission_infos_attention'),
            'commission_infos_commission_dial' => trans('commissioninfos.csv_field.commission_infos_commission_dial'),
            'commission_infos_tel_commission_datetime' => trans('commissioninfos.csv_field.commission_infos_tel_commission_datetime'),
            'commission_infos_tel_commission_person' => trans('commissioninfos.csv_field.commission_infos_tel_commission_person'),
            'commission_infos_commission_fee_rate' => trans('commissioninfos.csv_field.commission_infos_commission_fee_rate'),
            'commission_infos_commission_note_send_datetime' => trans('commissioninfos.csv_field.commission_infos_commission_note_send_datetime'),
            'commission_infos_commission_note_sender' => trans('commissioninfos.csv_field.commission_infos_commission_note_sender'),
            'commission_infos_send_mail_fax' => trans('commissioninfos.csv_field.commission_infos_send_mail_fax'),
            'commission_infos_send_mail_fax_datetime' => trans('commissioninfos.csv_field.commission_infos_send_mail_fax_datetime'),
            'commission_infos_commission_status' => trans('commissioninfos.csv_field.commission_infos_commission_status'),
            'commission_infos_commission_order_fail_reason' => trans('commissioninfos.csv_field.commission_infos_commission_order_fail_reason'),
            'commission_infos_complete_date' => trans('commissioninfos.csv_field.commission_infos_complete_date'),
            'commission_infos_order_fail_date' => trans('commissioninfos.csv_field.commission_infos_order_fail_date'),
            'commission_infos_estimate_price_tax_exclude' => trans('commissioninfos.csv_field.commission_infos_estimate_price_tax_exclude'),
            'commission_infos_construction_price_tax_exclude' => trans('commissioninfos.csv_field.commission_infos_construction_price_tax_exclude'),
            'commission_infos_construction_price_tax_include' => trans('commissioninfos.csv_field.commission_infos_construction_price_tax_include'),
            'commission_infos_deduction_tax_include' => trans('commissioninfos.csv_field.commission_infos_deduction_tax_include'),
            'commission_infos_deduction_tax_exclude' => trans('commissioninfos.csv_field.commission_infos_deduction_tax_exclude'),
            'commission_infos_confirmd_fee_rate' => trans('commissioninfos.csv_field.commission_infos_confirmd_fee_rate'),
            'commission_infos_unit_price_calc_exclude' => trans('commissioninfos.csv_field.commission_infos_unit_price_calc_exclude'),
            'commission_infos_report_note' => trans('commissioninfos.csv_field.commission_infos_report_note'),
        ];
    }
}
