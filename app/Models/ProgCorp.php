<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgCorp
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int $prog_import_file_id 進捗管理インポートファイルID
 * @property int|null $progress_flag 進捗状況
 * @property string|null $collect_date 回収日時
 * @property string|null $sf_register_date セールスフォース登録日時
 * @property string|null $note 備考
 * @property int|null $unit_cost 単価
 * @property string|null $call_back_phone_date 後追い架電日時
 * @property int $mail_count メール送信回数
 * @property string|null $mail_last_send_date 最新メール送信日時
 * @property int $fax_count FAX送信回数
 * @property string|null $fax_last_send_date 最新FAX送信日時
 * @property int|null $call_back_phone_flag 架電後フラグ
 * @property int|null $contact_type 送付方法
 * @property string|null $irregular_method イレギュラー送付方法
 * @property int|null $not_replay_flag 未返信理由
 * @property int|null $rev_mail_count 16日以降返送数
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $fax FAX番号
 * @property string|null $mail_address メールアドレス
 * @property mixed|string $holidays
 * @property-read \App\Models\MCorp $mCorp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProgAddDemandInfo[] $progAddDemandInfos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProgDemandInfo[] $progDemandInfo
 * @property-read \App\Models\ProgImportFile $progImportFile
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereCallBackPhoneDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereCallBackPhoneFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereCollectDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereContactType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereFaxCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereFaxLastSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereIrregularMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereMailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereMailCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereMailLastSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereNotReplayFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereProgressFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereRevMailCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereSfRegisterDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgCorp whereUnitCost($value)
 * @mixin \Eloquent
 */
class ProgCorp extends Model
{
    /**
     * @var string
     */
    protected $table = 'prog_corps';
    /**
     * @var array
     */
    protected $guarded = ['id'];
    const UPDATED_AT = 'modified';
    const MAIL_COLLECTED = 7; // progress flag status
    const LIMIT_ADD_DEMAND_INFO = 30;
    const DEMAND_TYPE_UPDATE_EDITABLE = [
        1 => '復活案件（過去に失注になったお客様より再度問い合わせがあった場合）',
        2 => '追加施工（問い合わせのあったジャンルと同時に、別ジャンルの受注をした場合）',
        3 => 'その他'
    ];
    const DEMAND_TYPE_UPDATE_READ_ONLY = [
        1=>'復活案件',
        2=>'追加施工',
        3=>'その他'
    ];
    const STORAGE_PATH = 'prog_corps/';
    // custom attribulte @thaihv
    /**
     * @var array
     */
    protected $attributes = ['holidays' => ''];

    /**
     * @return mixed|string
     */
    public function getHolidaysAttribute()
    {
        return isset($this->attributes['holidays']) ? $this->attributes['holidays'] : '';
    }

    /**
     * @param $string
     */
    public function setHolidaysAttribute($string)
    {
        $this->attributes['holidays'] = $string;
    }
    // end custom attribute
    /**
    /**
     * Get the prog_demand_infos for the prog_corp.
     */
    public function progAddDemandInfos()
    {
        return $this->hasMany('App\Models\ProgAddDemandInfo', 'prog_corp_id', 'id');
    }

    /**
     * Get the prog_import_file for the prog_corp.
     */
    public function progImportFile()
    {
        return $this->belongsTo('App\Models\ProgImportFile', 'prog_import_file_id', 'id');
    }
    /**
     * Get the prog_import_file for the prog_corp.
     */
    public function progDemandInfo()
    {
        return $this->hasMany('App\Models\ProgDemandInfo', 'prog_corp_id', 'id')->orderBy('receive_datetime');
    }

    /**
     * Get the mCorp for the prog_corp.
     */
    public function mCorp()
    {
        return $this->belongsTo('App\Models\MCorp', 'corp_id', 'id');
    }
    /**
     * get demand type update by progress flag
     *
     * @return array list type update
     */
    public function getDemandTypeUpdate()
    {
        return $this->progress_flag == self::MAIL_COLLECTED ? self::DEMAND_TYPE_UPDATE_READ_ONLY : self::DEMAND_TYPE_UPDATE_EDITABLE;
    }
}
