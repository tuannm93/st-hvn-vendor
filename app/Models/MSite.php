<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MSite
 *
 * @property int $id サイトID
 * @property string|null $site_name サイト名
 * @property string|null $site_tel サイト掲載電話番号
 * @property string|null $site_url サイトURL
 * @property int|null $commission_type 取次形態
 * @property int|null $cross_site_flg クロスセルサイト判定
 * @property int|null $jbr_flg 生活救急車判定
 * @property string|null $note 備考
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int $manual_selection_limit 手動選定確定上限
 * @property int $auction_selection_limit 入札選定確定上限
 * @property-read \App\Models\MCommissionType|null $mCommissionType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereAuctionSelectionLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereCrossSiteFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereJbrFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereManualSelectionLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereSiteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereSiteTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSite whereSiteUrl($value)
 * @mixin \Eloquent
 */
class MSite extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_sites';

    const CROSS_FLAG = 1;

    /**
     * get list site function
     *
     * @return array
     */
    public function getList()
    {
        $list = MSite::select('site_name', 'id')->orderBy('site_name', 'asc')->get()->toarray();
        $results = [];
        foreach ($list as $val) {
            $results[$val['id']] = $val['site_name'];
        }
        return $results;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mCommissionType()
    {
        return $this->belongsTo(MCommissionType::class, 'commission_type', 'id');
    }
}
