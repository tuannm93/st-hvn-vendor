<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CorpAgreement
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property string|null $corp_kind 企業種別     Corp：法人
 * Person：個人
 * @property bool|null $agreement_flag 規約同意フラグ
 * @property int|null $agreement_id 契約約款マスタID    規約同意時点のID
 * @property bool|null $customize_flag 契約約款カスタマイズフラグ     特約
 * @property int $agreement_history_id 契約約款履歴ID  規約同意時点の履歴番号
 * @property int|null $agreement_customize_id agreement_customize_id
 * @property bool $new_flag 新規契約フラグ
 * @property int $ticket_no 契約書番号
 * @property string $status ステータス
 * @property string|null $hansha_check 反社チェック    None：未実施
 * OK：実施済みOK
 * NG：実施済みNG
 * @property string|null $hansha_check_date 反社チェック日
 * @property string|null $hansha_check_user_id 反社チェック　確認者
 * @property string|null $transactions_law_date 特商法違反チェック日
 * @property string|null $transactions_law_user_id 特商法違反　確認者
 * @property string|null $acceptation_date 承認日
 * @property string|null $acceptation_user_id 承認者
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property string|null $kind 契約種別
 * @property string|null $original_agreement 同意申請時の契約約款内容
 * @property string|null $customize_agreement 同意申請時の特約内容
 * @property bool $accept_check
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAcceptCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAcceptationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAcceptationUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAgreementCustomizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAgreementFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAgreementHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereCorpKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereCustomizeAgreement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereCustomizeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereHanshaCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereHanshaCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereHanshaCheckUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereNewFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereOriginalAgreement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereTicketNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereTransactionsLawDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereTransactionsLawUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpAgreement whereVersionNo($value)
 * @mixin \Eloquent
 */
class CorpAgreement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'corp_agreement';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    const STEP0 = 'Step0';
    const STEP1 = 'Step1';
    const STEP2 = 'Step2';
    const STEP3 = 'Step3';
    const STEP4 = 'Step4';
    const STEP5 = 'Step5';
    const STEP6 = 'Step6';
    const CONFIRM = 'Confirm';
    const REVIEW = 'Review';
    const PASS_BACK = 'PassBack';
    const COMPLETE = 'Complete';
    const NOT_SIGNED = 'NotSigned';
    const RECONFIRMATION = 'Reconfirmation';
    const RESIGNING = 'Resigning';
    const APPLICATION = 'Application';

    const AGREEMENT_STATUS = [
        null => '',
        self::STEP0 => '未確認',
        self::STEP1 => '契約内容確認中',
        self::STEP2 => '契約内容確認中',
        self::STEP3 => '契約内容確認中',
        self::STEP4 => '契約内容確認中',
        self::STEP5 => '契約内容確認中',
        self::STEP6 => '契約内容確認中',
        self::CONFIRM => '契約内容最終確認',
        self::APPLICATION => '同意申請完了',
        self::REVIEW => '申請審査中',
        self::PASS_BACK => '差戻し中',
        self::COMPLETE => '契約完了',
        self::NOT_SIGNED => '未締結',
        self::RECONFIRMATION => '再確認',
        self::RESIGNING => '再契約申請'
    ];

    const NONE = 'None';
    const OK = 'OK';
    const NG = 'NG';
    const INADEQUATE = 'Inadequate';

    const HANSHA_CHECK_STATUS = [
        self::NONE => '未実施',
        self::OK => '実施済みOK',
        self::NG => '実施済みNG',
        self::INADEQUATE => '書類不備'
    ];

    /**
     * get status message
     *
     * @return array
     */
    public function getStatusMessage()
    {
        return [
            'Step0' => '未確認',
            'Step1' => '契約内容確認中',
            'Step2' => '契約内容確認中',
            'Step3' => '契約内容確認中',
            'Step4' => '契約内容確認中',
            'Step5' => '契約内容確認中',
            'Step6' => '契約内容確認中',
            'Confirm' => '契約内容最終確認',
            'Application' => '同意申請完了',
            'Review' => '申請審査中',
            'PassBack' => '差戻し中',
            'Complete' => '契約完了',
            'NotSigned' => '未締結',
            'Reconfirmation' => '契約再確認申請',
            'Resigning' => '再契約申請'
        ];
    }
}
