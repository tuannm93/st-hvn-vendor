<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MoneyCorrespond
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property string|null $payment_date 入金日
 * @property string|null $nominee 名義人
 * @property int|null $payment_amount 入金金額
 * @property string|null $modified_user_id 更新者ID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property \Carbon\Carbon|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond whereNominee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond wherePaymentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MoneyCorrespond wherePaymentDate($value)
 * @mixin \Eloquent
 */
class MoneyCorrespond extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    /**
     * @var string
     */
    protected $table = 'money_corresponds';

    /**
     * @var array
     */
    protected $guarded = [];
}
