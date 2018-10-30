<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCorpsNoticeInfo
 *
 * @property int $id
 * @property int $m_corp_id
 * @property int $notice_info_id
 * @property string|null $modified_user_id
 * @property \Carbon\Carbon|null $modified
 * @property string|null $created_user_id
 * @property \Carbon\Carbon|null $created
 * @property string|null $answer_value 回答結果
 * @property string|null $answer_user_id 回答者ID
 * @property string|null $answer_date 回答日
 * @property string|null $settlement_user_id 決済者ID
 * @property string|null $settlement_date 決済日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereAnswerDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereAnswerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereAnswerValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereMCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereNoticeInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereSettlementDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpsNoticeInfo whereSettlementUserId($value)
 * @mixin \Eloquent
 */
class MCorpsNoticeInfo extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    /**
     * @var string
     */
    protected $table = 'm_corps_notice_infos';

    /**
     * @var array
     */
    protected $guarded = [];
}
