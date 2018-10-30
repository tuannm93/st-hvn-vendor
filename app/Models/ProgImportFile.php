<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgImportFile
 *
 * @property int $id ID
 * @property string|null $file_name ファイル名
 * @property string|null $original_file_name オリジナルファイル名
 * @property string|null $import_date インポート日時
 * @property int|null $delete_flag 削除フラグ
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @property int|null $lock_flag 取次ロックフラグ
 * @property int|null $release_flag 公開フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereImportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereLockFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgImportFile whereReleaseFlag($value)
 * @mixin \Eloquent
 */
class ProgImportFile extends Model
{
    const UPDATED_AT = 'modified';
       /**
        * The table associated with the model.
        *
        * @var string
        */
    protected $table = 'prog_import_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
        'original_file_name',
        'import_date',
        'delete_flag',
        'modified_user_id',
        'lock_flag',
        'release_flag',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
