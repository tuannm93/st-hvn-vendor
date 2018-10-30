<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AutoCommissionCorp
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int $category_id カテゴリーID
 * @property string $jis_cd 全国地方公共団体コード
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property int|null $sort 施設登録順
 * @property int $process_type 自動処理種別 1:自動選定 2:自動取次
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MCorp[] $mCorps
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereJisCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereProcessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCommissionCorp whereSort($value)
 * @mixin \Eloquent
 */
class AutoCommissionCorp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auto_commission_corp';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mCorps()
    {
        return $this->hasMany(MCorp::class, 'corp_id', 'id');
    }
}
