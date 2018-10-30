<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MSiteGenres
 *
 * @property int $id ID
 * @property int $site_id サイトID
 * @property int $genre_id ジャンルID
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property-read \App\Models\MGenre $mGenre
 * @property-read \App\Models\MSite $mSite
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MSiteGenres whereSiteId($value)
 * @mixin \Eloquent
 */
class MSiteGenres extends Model
{
    /**
     * @var string
     */
    public $table = 'm_site_genres';

    /**
     * @return $this
     */
    public function mGenre()
    {
        return $this->belongsTo(MGenre::class, 'genre_id', 'id')->select(['genre_name', 'id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mSite()
    {
        return $this->belongsTo(MSite::class, 'site_id', 'id');
    }
}
