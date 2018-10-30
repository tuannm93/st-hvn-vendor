<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\License
 *
 * @property int $id id
 * @property string|null $name ライセンス名
 * @property bool $certificate_required_flag 証明書必要フラグ
 * @property string|null $note 備考
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCertificateRequiredFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\License whereVersionNo($value)
 * @mixin \Eloquent
 */
class License extends Model
{

    /**
     * @var string
     */
    protected $table = 'license';

    const HAVE_TO = '必須';

    /**
     * @var boolean
     */
    public $timestamps = false;
}
