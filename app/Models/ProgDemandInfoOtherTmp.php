<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgDemandInfoOtherTmp
 *
 * @property int $id ID
 * @property int $prog_corp_id 進捗管理企業ID
 * @property int $add_flg 追加フラグ
 * @property int $agree_flag 同意チェック
 * @property int|null $prog_import_file_id 進捗管理ファイルID
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成ユーザID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新ユーザID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereAddFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfoOtherTmp whereProgImportFileId($value)
 * @mixin \Eloquent
 */
class ProgDemandInfoOtherTmp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prog_demand_info_other_tmps';

    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $guarded = ['id'];
}
