<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MTaxRate
 *
 * @property int $id ID
 * @property string $start_date 開始日
 * @property string $end_date 終了日
 * @property float $tax_rate 消費税率
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTaxRate whereTaxRate($value)
 * @mixin \Eloquent
 */
class MTaxRate extends Model
{
    /**
     * @var string
     */
    protected $table = 'm_tax_rates';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
