<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MTime
 *
 * @property int $id ID
 * @property int $item_id 項目ID
 * @property string $item_detail 項目詳細
 * @property string $item_category 項目カテゴリ
 * @property int|null $item_hour_date 時間
 * @property int|null $item_minute_date 分
 * @property int|null $item_type 仕様タイプ
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereItemCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereItemDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereItemHourDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereItemMinuteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTime whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MTime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_times';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
