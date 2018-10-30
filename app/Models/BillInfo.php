<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BillInfo
 *
 * @property int $id 請求ID
 * @property int $demand_id 案件ID
 * @property int|null $bill_status 請求状況
 * @property float|null $irregular_fee_rate イレギュラー手数料率
 * @property int|null $irregular_fee イレギュラー手数料金額
 * @property int|null $deduction_tax_include 控除金額(税込)
 * @property int|null $deduction_tax_exclude 控除金額(税抜)
 * @property int|null $indivisual_billing 個別請求処理案件
 * @property float|null $comfirmed_fee_rate 確定手数料率
 * @property int|null $fee_target_price 手数料対象金額
 * @property int|null $fee_tax_exclude 手数料(税抜)
 * @property int|null $tax 消費税
 * @property int|null $insurance_price 保険料金額
 * @property int|null $total_bill_price 合計請求金額
 * @property string|null $fee_billing_date 手数料請求日
 * @property string|null $fee_payment_date 手数料入金日
 * @property int|null $fee_payment_price 手数料入金金額
 * @property int|null $fee_payment_balance 手数料入金残高
 * @property string|null $report_note 報告備考欄
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $commission_id 取次ID
 * @property int|null $business_trip_amount 出張費
 * @property int|null $auction_id オークションID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereBillStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereBusinessTripAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereComfirmedFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereDeductionTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereDeductionTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereFeeBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereFeePaymentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereFeePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereFeePaymentPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereFeeTargetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereFeeTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereIndivisualBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereInsurancePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereIrregularFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereIrregularFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereReportNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BillInfo whereTotalBillPrice($value)
 * @mixin \Eloquent
 */
class BillInfo extends Model
{
    /**
     * @var array
     */
    public static $csvFormat = [
        'MCorp__id' => '企業コード',
        'MCorp__official_corp_name' => '企業名',
        'BillInfo__demand_id' => '案件ID',
        'BillInfo__commission_id' => '取次ID',
        'MItem__item_name' => '取次形態',
        'CommissionInfo__tel_commission_datetime' => '電話取次日時',
        'CommissionInfo__complete_date' => '施工完了日',
        'DemandInfo__customer_name' => 'お客様名',
        'DemandInfo__riro_kureka' => 'リロ・クレカ案件',
        'BillInfo__fee_target_price' => '手数料対象金額',
        'BillInfo__comfirmed_fee_rate' => '確定手数料率',
        'BillInfo__fee_tax_exclude' => '手数料(税抜)',
        'BillInfo__tax' => '消費税',
        'BillInfo__insurance_price' => '保険料金額',
        'BillInfo__fee_billing_date' => '手数料請求日',
        'BillInfo__fee_payment_balance' => '手数料入金残高',
    ];
    
    /**
     * @var string
     */
    protected $table = 'bill_infos';
    /**
     * @var boolean
     */
    public $timestamps = false;
}
