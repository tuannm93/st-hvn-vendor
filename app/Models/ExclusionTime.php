<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExclusionTime
 *
 * @property int $id ID
 * @property int $pattern パターン
 * @property string|null $exclusion_time_from 除外時間from
 * @property string|null $exclusion_time_to 除外時間to
 * @property int|null $exclusion_day 除外日程
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AuctionGenreArea[] $auctionGenreAreas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AuctionGenre[] $auctionGenres
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereExclusionDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereExclusionTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereExclusionTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ExclusionTime wherePattern($value)
 * @mixin \Eloquent
 */
class ExclusionTime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exclusion_times';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @var fillable
     */
    public $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctionGenreAreas()
    {
        return $this->hasMany('App\Models\AuctionGenreArea', 'exclusion_pattern', 'pattern');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctionGenres()
    {
        return $this->hasMany('App\Models\AuctionGenre', 'exclusion_pattern', 'pattern');
    }
}
