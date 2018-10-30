<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GeneralSearchItem
 *
 * @property int $id ID
 * @property int $general_search_id 総合検索ID
 * @property int $function_id 機能ID
 * @property string $table_name 取得テーブル名
 * @property string $column_name 項目名
 * @property int $output_order 出力順
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereColumnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereFunctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereGeneralSearchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereOutputOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchItem whereTableName($value)
 * @mixin \Eloquent
 */
class GeneralSearchItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'general_search_items';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
