<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PublicHoliday
 *
 * @property int $id ID
 * @property string|null $holiday_date 祝日
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicHoliday whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicHoliday whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicHoliday whereHolidayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicHoliday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicHoliday whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PublicHoliday whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class PublicHoliday extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'public_holidays';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
