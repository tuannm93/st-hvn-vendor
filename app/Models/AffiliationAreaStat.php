<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AffiliationAreaStat
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int $genre_id ジャンルID
 * @property string $prefecture 県コード
 * @property int|null $commission_count_category 取次数
 * @property int|null $orders_count_category 受注数
 * @property int|null $commission_unit_price_category 取次単価
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $commission_unit_price_rank 取次単価ランク
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereCommissionCountCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereCommissionUnitPriceCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereCommissionUnitPriceRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat whereOrdersCountCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationAreaStat wherePrefecture($value)
 * @mixin \Eloquent
 */
class AffiliationAreaStat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliation_area_stats';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    const AFF_TRANSACTION = 5; // Increase the upper limit because it overflows memory when outputting all items
}
