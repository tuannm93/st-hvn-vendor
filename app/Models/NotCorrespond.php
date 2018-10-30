<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NotCorrespond
 *
 * @property int $id ID
 * @property string $jis_cd 市町村コード
 * @property string $prefecture_cd 都道府県
 * @property int $genre_id ジャンルID
 * @property int $not_correspond_count_year エリア対応加盟店なし案件数(年間)
 * @property int $not_correspond_count_latest エリア対応加盟店なし案件数(直近)
 * @property string|null $import_date インポート日
 * @property string|null $modified_user_id 更新ユーザーID
 * @property string|null $modified 更新日
 * @property string|null $created_user_id 登録ユーザーID
 * @property string|null $created 登録日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereImportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereJisCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereNotCorrespondCountLatest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond whereNotCorrespondCountYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespond wherePrefectureCd($value)
 * @mixin \Eloquent
 */
class NotCorrespond extends Model
{
    /**
     * @var string
     */
    protected $table = 'not_corresponds';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     *
     */
    public function findNotCorrespond()
    {
    }
}
