<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccumulatedInformations
 *
 * @property int $id 蓄積情報ID
 * @property int $demand_id 案件ID
 * @property int $corp_id 企業ID
 * @property string $demand_regist_date 案件登録日時
 * @property int|null $mail_open_flag メール開封フラグ デフォルト0
 * @property string|null $mail_send_date メール送信日時
 * @property string|null $mail_open_date メール開封日時
 * @property string|null $bid_regist_date 入札確定日時
 * @property string|null $refusal_date 辞退登録日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereBidRegistDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereDemandRegistDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereMailOpenDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereMailOpenFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereMailSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatedInformations whereRefusalDate($value)
 * @mixin \Eloquent
 */
class AccumulatedInformations extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accumulated_informations';

    /**
     * @var boolean
     */
    public $timestamps = false;
}
