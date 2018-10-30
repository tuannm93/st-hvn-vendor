<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCorp
 *
 * @property int $id 企業ID
 * @property string|null $corp_name 企業名
 * @property string|null $corp_name_kana 企業名ふりがな
 * @property string|null $official_corp_name 正式企業名
 * @property int|null $affiliation_status 加盟状態
 * @property string|null $responsibility 責任者
 * @property string|null $postcode 郵便番号
 * @property string|null $address1 都道府県
 * @property string|null $address2 市区町村
 * @property string|null $address3 町域
 * @property string|null $address4 丁目番地
 * @property string|null $building 建物名
 * @property string|null $room 部屋号数
 * @property string|null $trade_name1 屋号①
 * @property string|null $trade_name2 屋号②
 * @property string|null $commission_dial 取次用ダイヤル
 * @property string|null $tel1 電話番号①
 * @property string|null $tel2 電話番号②
 * @property string|null $mobile_tel 携帯電話番号
 * @property string|null $fax FAX番号
 * @property string|null $mailaddress_pc PCメール
 * @property string|null $mailaddress_mobile 携帯メール
 * @property string|null $url URL
 * @property string|null $target_range 対応範囲(半径km)
 * @property string|null $available_time 現場対応可能時間
 * @property int|null $support24hour 24時間対応
 * @property string|null $contactable_time 連絡可能時間
 * @property int|null $free_estimate 無料見積対応
 * @property int|null $portalsite ポータルサイト掲載
 * @property string|null $reg_send_date 登録書発送日
 * @property int|null $reg_send_method 登録書発送方法
 * @property string|null $reg_collect_date 登録書回収日
 * @property string|null $ps_app_send_date PS申込書発送日
 * @property string|null $ps_app_collect_date PS申込書回収日
 * @property int|null $coordination_method 顧客情報連絡手段
 * @property int|null $prog_send_method 進捗表送付方法
 * @property string|null $prog_send_address 進捗表送付先
 * @property string|null $prog_irregular 進捗表イレギュラー
 * @property int|null $bill_send_method 請求書送付方法
 * @property string|null $bill_send_address 請求書送付先
 * @property string|null $bill_irregular 請求書イレギュラー
 * @property string|null $special_agreement 特約事項
 * @property string|null $contract_date 獲得日
 * @property string|null $order_fail_date 失注日
 * @property string|null $geocode_lat 緯度
 * @property string|null $geocode_long 経度
 * @property string|null $note 備考欄
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $construction_id
 * @property string|null $follow_date 後追い日
 * @property int|null $corp_status 開拓状況
 * @property int|null $order_fail_reason 開拓失注理由
 * @property string|null $document_send_request_date 資料発送依頼日
 * @property int|null $follow_person 後追い担当者
 * @property int|null $advertising_status 出稿型サイト状況
 * @property string|null $advertising_send_date 出稿型サイト告知送信日
 * @property string|null $progress_check_tel 進捗確認先電話番号
 * @property string|null $progress_check_person 進捗確認担当者
 * @property int|null $payment_site 支払サイト
 * @property int $del_flg 削除フラグ
 * @property int|null $rits_person リッツ担当者
 * @property int|null $corp_commission_status 加盟店取次状況
 * @property string|null $commission_ng_date 取次NG化日
 * @property string|null $listed_media リスト元媒体
 * @property string|null $corp_person 企業担当者
 * @property string|null $available_time_from 現場対応可能時間From
 * @property string|null $available_time_to 現場対応可能時間To
 * @property string|null $contactable_time_from 連絡可能時間From
 * @property string|null $contactable_time_to 連絡可能時間To
 * @property int|null $contactable_support24hour 連絡24時間対応
 * @property int|null $contactable_time_other 連絡その他
 * @property int|null $available_time_other 営業時間その他
 * @property string|null $seikatsu110_id 生活110番ID
 * @property int|null $mobile_mail_none 携帯メール無し
 * @property int|null $mobile_tel_type 携帯電話タイプ
 * @property int|null $corp_commission_type 企業取次形態
 * @property int|null $popup_stop_flg ポップアップ非表示フラグ
 * @property string|null $mailaddress_auction 入札式配信先アドレス
 * @property int|null $auction_status 取次方法
 * @property int|null $jbr_available_status JBR対応状況
 * @property int|null $auction_masking 入札式マスキング除外
 * @property string|null $corp_kind
 * @property bool|null $agreement_target_flag
 * @property string|null $cookie_id
 * @property int|null $commission_accept_flg 契約更新フラグ
 * @property string|null $commission_accept_date 契約更新フラグ更新日
 * @property string|null $commission_accept_user_id 契約更新フラグ更新者
 * @property string|null $representative_postcode 本社所在地/代表者住所 郵便番号
 * @property string|null $representative_address1 本社所在地/代表者住所 都道府県
 * @property string|null $representative_address2 本社所在地/代表者住所 市区町村
 * @property string|null $representative_address3 本社所在地/代表者住所 町域
 * @property string|null $refund_bank_name 返金先口座 銀行名
 * @property string|null $refund_branch_name 返金先口座 支店名
 * @property string|null $refund_account_type 返金先口座 預金種別
 * @property string|null $refund_account 返金先口座 口座番号
 * @property int|null $support_language_en 英語対応有無
 * @property int|null $support_language_zh 中国語対応有無
 * @property string|null $support_language_employees 言語対応可能従業員数
 * @property string|null $prog_send_mail_address 進捗表メール送付先
 * @property string|null $prog_send_fax 進捗表FAX送付先
 * @property string|null $last_antisocial_check_date 最終反社チェック日
 * @property string|null $last_antisocial_check 最終反社チェック
 * @property string|null $last_antisocial_check_user_id 最終反社チェックユーザID
 * @property int|null $antisocial_display_flag 反社チェックポップアップ表示フラグ
 * @property string|null $last_reputation_check 風評チェック結果
 * @property int|null $antisocial_check_month 反社チェック実施月（1～12）
 * @property int|null $license_display_flag ライセンスチェックポップアップ表示フラグ
 * @property int|null $auto_call_flag オートコールフラグ
 * @property string|null $guideline_check_date 利用規約確認日
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AffiliationAreaStat[] $affiliationAreaStats
 * @property-read \App\Models\AffiliationInfo $affiliationInfo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AffiliationInfo[] $affiliationInfos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AffiliationStat[] $affiliationStats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionInfos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionStatusInComplete
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionStatusInFail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionStatusInOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionStatusInProgress
 * @property-read array $address1_jp
 * @property-read mixed|string $category_note
 * @property mixed|string $complete
 * @property-read array $email_by_array
 * @property-read array $email_mobile_by_array
 * @property mixed|string $failed
 * @property-read string $holiday1
 * @property mixed|string $holidays
 * @property mixed|string $in_order
 * @property mixed|string $in_progress
 * @property-read mixed $note_or_category_note
 * @property-read string $order_fee_commission
 * @property-read mixed $text_coordination
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MCorpCategory[] $mCorpCategory
 * @property-read \App\Models\MCorpNewYear $mCorpNewYear
 * @property-read \App\Models\MItem|null $mItem
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MTargetArea[] $mTargetArea
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAddress3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAddress4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAdvertisingSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAdvertisingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAffiliationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAgreementTargetFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAntisocialCheckMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAntisocialDisplayFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAuctionMasking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAuctionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAutoCallFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAvailableTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAvailableTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAvailableTimeOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereAvailableTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereBillIrregular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereBillSendAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereBillSendMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCommissionAcceptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCommissionAcceptFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCommissionAcceptUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCommissionDial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCommissionNgDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereConstructionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereContactableSupport24hour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereContactableTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereContactableTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereContactableTimeOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereContactableTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCookieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCoordinationMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpCommissionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpNameKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCorpStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereDocumentSendRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereFollowDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereFollowPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereFreeEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereGeocodeLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereGeocodeLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereGuidelineCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereJbrAvailableStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereLastAntisocialCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereLastAntisocialCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereLastAntisocialCheckUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereLastReputationCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereLicenseDisplayFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereListedMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereMailaddressAuction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereMailaddressMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereMailaddressPc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereMobileMailNone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereMobileTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereMobileTelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereOfficialCorpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereOrderFailDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp wherePaymentSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp wherePopupStopFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp wherePortalsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgIrregular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgSendAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgSendFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgSendMailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgSendMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgressCheckPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereProgressCheckTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp wherePsAppCollectDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp wherePsAppSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRefundAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRefundAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRefundBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRefundBranchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRegCollectDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRegSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRegSendMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRepresentativeAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRepresentativeAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRepresentativeAddress3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRepresentativePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereResponsibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRitsPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereSeikatsu110Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereSpecialAgreement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereSupport24hour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereSupportLanguageEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereSupportLanguageEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereSupportLanguageZh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereTargetRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereTel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereTel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereTradeName1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereTradeName2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorp whereUrl($value)
 * @mixin \Eloquent
 */
class MCorp extends Model
{
    const CORP = 'Corp';
    const PERSON = 'Person';
    const CORP_KIND = [
        null => '',
        self::CORP => '法人',
        self::PERSON => '個人'
    ];
    const MEMBER_STATE_ACCESSION = 1;
    const AUCTION_STATUS_ONE = 1;
    const AUCTION_STATUS_TWO = 2;
    const AUCTION_STATUS_THREE = 3;
    const COMMISSION_STATUS_IMPROGRESS = 1;
    const COMMISSION_STATUS_ORDER = 2;
    const COMMISSION_STATUS_COMPLETE = 3;
    const COMMISSION_STATUS_FAIL = 4;

    const LISTED = 'listed';
    const UNLISTED = 'unlisted';
    const LISTED_KIND = [
        null => '',
        self::LISTED => '上場企業',
        self::UNLISTED => '非上場企業'
    ];

    const MOBILE_TEL_TYPE = '携帯電話タイプ';
    const COORDINATION_METHOD = '顧客情報連絡手段';

    const METHOD_6 = 'メール＋アプリ(推奨)';
    const METHOD_1 = 'メール＋FAX';
    const METHOD_7 = 'メール＋FAX＋アプリ';
    const METHOD_2 = 'メール';
    const METHOD_3 = 'FAX';
    const METHOD_4 = '専用フォーム';
    const METHOD_5 = 'その他';

    const METHOD_NUM_1 = '1';
    const METHOD_NUM_2 = '2';
    const METHOD_NUM_3 = '3';
    const METHOD_NUM_4 = '4';
    const METHOD_NUM_5 = '5';
    const METHOD_NUM_6 = '6';
    const METHOD_NUM_7 = '7';

    const COORDINATION_METHOD_LIST = [
        '' => '--なし--',
        self::METHOD_NUM_6 => self::METHOD_6,
        self::METHOD_NUM_1 => self::METHOD_1,
        self::METHOD_NUM_7 => self::METHOD_7,
        self::METHOD_NUM_2 => self::METHOD_2,
        self::METHOD_NUM_3 => self::METHOD_3,
        self::METHOD_NUM_4 => self::METHOD_4,
        self::METHOD_NUM_5 => self::METHOD_5,
    ];

    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_corps';
    /**
     * @var array
     */
    protected $guarded = [];
    // custom attribulte @thaihv
    /**
     * @var array
     */
    protected $attributes = ['holidays' => '', 'in_progress' => 0, 'in_order' => 0, 'complete' => 0, 'failed' => 0];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliationInfos()
    {
        return $this->hasMany('App\Models\AffiliationInfo', 'corp_id', 'id');
    }

    /**
     * @return mixed|string
     */
    public function getInProgressAttribute()
    {
        return isset($this->attributes['in_progress']) ? $this->attributes['in_progress'] : '';
    }

    /**
     * @param $count
     */
    public function setInProgressAttribute($count)
    {
        $this->attributes['in_progress'] = $count;
    }

    /**
     * @return mixed|string
     */
    public function getInOrderAttribute()
    {
        return isset($this->attributes['in_order']) ? $this->attributes['in_order'] : '';
    }

    /**
     * @param $count
     */
    public function setInOrderAttribute($count)
    {
        $this->attributes['in_order'] = $count;
    }

    /**
     * @return mixed|string
     */
    public function getCompleteAttribute()
    {
        return isset($this->attributes['complete']) ? $this->attributes['complete'] : '';
    }

    /**
     * @param $count
     */
    public function setCompleteAttribute($count)
    {
        $this->attributes['complete'] = $count;
    }

    /**
     * @return mixed|string
     */
    public function getFailedAttribute()
    {
        return isset($this->attributes['failed']) ? $this->attributes['failed'] : '';
    }

    /**
     * @param $count
     */
    public function setFailedAttribute($count)
    {
        $this->attributes['failed'] = $count;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commissionInfos()
    {
        return $this->hasMany('App\Models\CommissionInfo', 'corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mItem()
    {
        return $this->belongsTo('App\Models\MItem', 'coordination_method', 'item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mCorpCategory()
    {
        return $this->hasMany('App\Models\MCorpCategory', 'corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mCorpNewYear()
    {
        return $this->hasOne(MCorpNewYear::class, 'corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affiliationInfo()
    {
        return $this->hasOne(AffiliationInfo::class, 'corp_id', 'id');
    }

    /**
     * Get first holiday attribute
     *
     * @return string
     */
    public function getHoliday1Attribute()
    {
        $holiday = \DB::select(
            'SELECT ARRAY_TO_STRING(ARRAY( SELECT item_name FROM m_items m_items INNER JOIN m_corp_subs
              ON "m_corp_subs"."item_category" = "m_items"."item_category" JOIN m_corps ON "m_corp_subs"."corp_id" = "m_corps"."id"
              AND "m_corp_subs"."item_id" = "m_items"."item_id" WHERE "m_corp_subs"."item_category" = \'休業日\'
              AND m_corps.id='. $this->getAttribute('id') .'
              ORDER BY "m_items"."sort_order" ASC ),\'｜\')'
        );

        return $holiday ? $holiday[0]->array_to_string : '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliationStats()
    {
        return $this->hasMany('App\Models\AffiliationStat', 'corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliationAreaStats()
    {
        return $this->hasMany('App\Models\AffiliationAreaStat', 'corp_id', 'id');
    }

    /**
     * @return $this
     */
    public function commissionStatusInProgress()
    {
        return $this->hasMany('App\Models\CommissionInfo', 'corp_id', 'id')->where('commission_status', self::COMMISSION_STATUS_IMPROGRESS);
    }

    /**
     * @return $this
     */
    public function commissionStatusInOrder()
    {
        return $this->hasMany('App\Models\CommissionInfo', 'corp_id', 'id')->where('commission_status', self::COMMISSION_STATUS_ORDER);
    }

    /**
     * @return $this
     */
    public function commissionStatusInComplete()
    {
        return $this->hasMany('App\Models\CommissionInfo', 'corp_id', 'id')->where('commission_status', self::COMMISSION_STATUS_COMPLETE);
    }

    /**
     * @return $this
     */
    public function commissionStatusInFail()
    {
        return $this->hasMany('App\Models\CommissionInfo', 'corp_id', 'id')->where('commission_status', self::COMMISSION_STATUS_FAIL);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mTargetArea()
    {
        return $this->hasMany('App\Models\MTargetArea');
    }

    /**
     * @return array
     */
    public function getEmailByArrayAttribute()
    {
        $mail = $this->getAttribute('mailaddress_pc');
        return explode(';', $mail);
    }

    /**
     * @return array
     */
    public function getEmailMobileByArrayAttribute()
    {
        $mail = $this->getAttribute('mailaddress_mobile');
        return explode(';', $mail);
    }

    /**
     * @return mixed
     */
    public function getTextCoordinationAttribute()
    {
        $coordinationMethod = $this->getAttribute('coordination_method');
        $result = MItem::where('item_category', '=', '顧客情報連絡手段')
            ->where('item_id', '=', $coordinationMethod)->first();
        return $result->item_name;
    }

    /**
     * @return $this
     */
    public function mCorpCategoryWithCondition()
    {
        return $this->MCorpCategory()->where('category_id', $this->getAttribute('coordination_method'));
    }

    /**
     * @return string
     */
    public function getOrderFeeCommissionAttribute()
    {
        $corpMission = $this->mCorpCategoryWithCondition()->first();

        $corpMissionType = $corpMission ? $corpMission->corp_commission_type : 0;

        if ($corpMissionType != 2) {
            $orderFee = $corpMission->order_fee ?? 0;
            $orderFeeUnit =$corpMission->order_fee_unit ?? 0;
            $corpCommissionType = '成約';
        } else {
            $orderFee = $corpMission->introduce_fee;
            $orderFeeUnit = 0;
            $corpCommissionType = '紹介';
        }

        return $orderFeeUnit == 0
            ? $corpCommissionType . ($orderFee ? yenFormat2($orderFee) : "")
            : $corpCommissionType . ($orderFee ? $orderFee . '%' : '');
    }

    /**
     * @return mixed|string
     */
    public function getCategoryNoteAttribute()
    {
        $mCorp = $this->mCorpCategoryWithCondition()->first();
        return $mCorp ? $mCorp->note : '';
    }

    /**
     * Get address1 JP attribute
     *
     * @return array
     */
    public function getAddress1JpAttribute()
    {
        return getDivTextJP('prefecture_div', $this->attributes['address1']);
    }

    /**
     * @return mixed
     */
    public function getNoteOrCategoryNoteAttribute()
    {
        return $this->attributes['note'] != '' ? $this->attributes['note'] : $this->mCorpCategory->note;
    }
}
