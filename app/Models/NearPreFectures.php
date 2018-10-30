<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NearPreFectures
 *
 * @property int $id ID
 * @property string $prefecture_cd 都道府県
 * @property string $near_prefecture_cd 近隣都道府県
 * @property string|null $modified_user_id 更新ユーザーID
 * @property string|null $modified 更新日
 * @property string|null $created_user_id 登録ユーザーID
 * @property string|null $created 登録日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures whereNearPrefectureCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NearPreFectures wherePrefectureCd($value)
 * @mixin \Eloquent
 */
class NearPreFectures extends Model
{
    /**
     * @var string
     */
    protected $table = "near_prefectures";

    /**
     * @var array
     */
    protected $fillable = ['*'];
}
