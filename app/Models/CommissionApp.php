<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\Models\CommissionApp
 *
 * @property int $id ID
 * @property int $commission_id 取次ID
 * @property int $demand_id 案件ID
 * @property int $corp_id 加盟店ID
 * @property int|null $deduction_tax_include 控除金額税込み
 * @property float|null $irregular_fee_rate イレギュラー手数料
 * @property int|null $irregular_fee イレギュラー手数料金額
 * @property int|null $introduction_free 紹介無料
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @property bool $chg_deduction_tax_include 控除金額(税込)　変更フラグ
 * @property bool $chg_irregular_fee_rate イレギュラー手数料率　変更フラグ
 * @property bool $chg_irregular_fee イレギュラー手数料　変更フラグ
 * @property bool $chg_introduction_free 紹介無料　変更フラグ
 * @property int|null $irregular_reason イレギュラー理由
 * @property bool|null $ac_commission_exclusion_flg オークション手数料除外フラグ
 * @property bool $chg_ac_commission_exclusion_flg オークション手数料除外フラグ 変更フラグ
 * @property bool|null $chg_introduction_not 紹介不可　変更フラグ
 * @property int|null $introduction_not 紹介不可
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereAcCommissionExclusionFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereChgAcCommissionExclusionFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereChgDeductionTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereChgIntroductionFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereChgIntroductionNot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereChgIrregularFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereChgIrregularFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereDeductionTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereIntroductionFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereIntroductionNot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereIrregularFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereIrregularFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereIrregularReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionApp whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class CommissionApp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commission_applications';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
