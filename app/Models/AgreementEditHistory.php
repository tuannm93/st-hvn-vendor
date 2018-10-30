<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementEditHistory
 *
 * @property int $id ID
 * @property int $agreement_id 契約約款マスタID
 * @property string $name 名称
 * @property string $title タイトル
 * @property int $history_id 履歴ID
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property int|null $ticket_no
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereTicketNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementEditHistory whereVersionNo($value)
 * @mixin \Eloquent
 */
class AgreementEditHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_edit_history';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
