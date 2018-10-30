<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NotCorrespondLog
 *
 * @property int $id ID
 * @property int $not_correspond_id エリア対応加盟店なし案件ID
 * @property string $jis_cd 市町村コード
 * @property string $prefecture_cd 都道府県
 * @property int $genre_id ジャンルID
 * @property int $not_correspond_count_year エリア未登録案件数(年間)
 * @property int $not_correspond_count_latest エリア未登録案件数(直近)
 * @property string|null $import_date インポート日
 * @property string|null $modified_user_id 更新ユーザーID
 * @property string|null $modified 更新日
 * @property string|null $created_user_id 登録ユーザーID
 * @property string|null $created 登録日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereImportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereJisCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereNotCorrespondCountLatest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereNotCorrespondCountYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog whereNotCorrespondId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NotCorrespondLog wherePrefectureCd($value)
 * @mixin \Eloquent
 */
class NotCorrespondLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'not_correspond_logs';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var boolean
     */
    public $timestamps = false;
}
