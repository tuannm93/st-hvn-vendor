<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AffiliationSub
 *
 * @property int $id ID
 * @property int $affiliation_id 加盟店情報ID
 * @property string $item_category 項目カテゴリ
 * @property int $item_id 項目ID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereAffiliationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereItemCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AffiliationSub whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class AffiliationSub extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliation_subs';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
