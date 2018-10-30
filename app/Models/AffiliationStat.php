<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AffiliationStat
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int $genre_id ジャンルID
 * @property int|null $commission_count_category 取次数
 * @property int|null $orders_count_category 受注数
 * @property int|null $commission_unit_price_category 取次単価
 * @property int|null $sf_commission_unit_price_category SF取次単価
 * @property int|null $sf_commission_count_category SF取次件数
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereCommissionCountCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereCommissionUnitPriceCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereOrdersCountCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereSfCommissionCountCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationStat whereSfCommissionUnitPriceCategory($value)
 * @mixin \Eloquent
 */
class AffiliationStat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliation_stats';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
