<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MGeneralSearch
 *
 * @property int $id ID
 * @property string $definition_name 設定名称
 * @property int $auth_popular 公開範囲（一般）
 * @property int $auth_admin 公開範囲（管理者）
 * @property int $auth_accounting 公開範囲（経理）
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int $auth_accounting_admin 公開範囲（経理管理者）
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GeneralSearchCondition[] $gsCondition
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GeneralSearchItem[] $gsItem
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereAuthAccounting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereAuthAccountingAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereAuthAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereAuthPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereDefinitionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MGeneralSearch whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MGeneralSearch extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_general_searches';

    /**
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gsCondition()
    {
        return $this->hasMany('App\Models\GeneralSearchCondition', 'general_search_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gsItem()
    {
        return $this->hasMany('App\Models\GeneralSearchItem', 'general_search_id', 'id');
    }
}
