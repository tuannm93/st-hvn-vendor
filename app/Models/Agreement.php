<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\Models\Agreement
 *
 * @property int $id ID
 * @property string $name 名称
 * @property string $title タイトル
 * @property int $last_history_id 最新履歴ID
 * @property int|null $ticket_no 契約書番号
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgreementProvision[] $agreementProvision
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereLastHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereTicketNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Agreement whereVersionNo($value)
 * @mixin \Eloquent
 */
class Agreement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return $this
     */
    public function agreementProvision()
    {
        return $this->hasMany('App\Models\AgreementProvision', 'agreement_id', 'id')->orderBy('sort_no');
    }
}
