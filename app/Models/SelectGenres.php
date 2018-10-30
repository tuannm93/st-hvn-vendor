<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SelectGenres
 *
 * @property int $id ID
 * @property int $genre_id ジャンルID
 * @property int $select_type 選定タイプ
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日次
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成者
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SelectGenres whereSelectType($value)
 * @mixin \Eloquent
 */
class SelectGenres extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'select_genres';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
