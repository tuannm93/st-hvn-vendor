<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementStatusHistory
 *
 * @property int $id ID
 * @property int $corps_id corps_id
 * @property int $corp_agreement_id corp_agreement_id
 * @property string $status ステータス
 * @property string|null $before_status 前回ステータス
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereBeforeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereCorpAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereCorpsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementStatusHistory whereVersionNo($value)
 * @mixin \Eloquent
 */
class AgreementStatusHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_status_hisoty';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
