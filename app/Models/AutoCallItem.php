<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AutoCallItem
 *
 * @property int $id ID
 * @property int|null $asap 大至急案件オートコール時間
 * @property int|null $immediately 大至急案件オートコール時間
 * @property int|null $normal 通常案件オートコール時間
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereAsap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereImmediately($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AutoCallItem whereNormal($value)
 * @mixin \Eloquent
 */
class AutoCallItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'auto_call_items';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var boolean
     */
    public $timestamps = false;
}
