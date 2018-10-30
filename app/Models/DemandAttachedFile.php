<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DemandAttachedFile
 *
 * @property int $id ID
 * @property int $demand_id 案件ID
 * @property string|null $path ファイルパス
 * @property string|null $name ファイル名
 * @property string|null $content_type ファイル形式
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandAttachedFile whereUpdateUserId($value)
 * @mixin \Eloquent
 */
class DemandAttachedFile extends Model
{

    /**
     * @var string
     */
    public $table = 'demand_attached_files';
    /**
     * @var string
     */
    public $primaryKey = 'id';
    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'id', 'demand_id', 'path', 'name', 'content_type', 'create_date','create_user_id',
        'update_date', 'update_user_id', 'delete_date', 'delete_flag'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
}
