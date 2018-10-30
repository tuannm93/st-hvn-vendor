<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GeneralSearchCondition
 *
 * @property int $id ID
 * @property int $general_search_id 総合検索ID
 * @property string $table_name 抽出条件テーブル名
 * @property string $column_name 項目名
 * @property int $condition_expression 抽出条件式
 * @property string $condition_value 抽出条件値
 * @property string $condition_type 抽出条件型
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereColumnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereConditionExpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereConditionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereConditionValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereGeneralSearchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GeneralSearchCondition whereTableName($value)
 * @mixin \Eloquent
 */
class GeneralSearchCondition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'general_search_conditions';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
