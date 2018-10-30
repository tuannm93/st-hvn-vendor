<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DemandInfo
 *
 * @property int $id 案件ID
 * @property string|null $follow_date 後追い日
 * @property int|null $demand_status 案件状況
 * @property int|null $order_fail_reason 失注理由
 * @property int|null $mail_demand メール案件
 * @property int|null $nighttime_takeover 夜間引継案件
 * @property int|null $low_accuracy 低確度案件
 * @property int|null $remand 差し戻し案件
 * @property int|null $immediately 至急案件
 * @property int|null $corp_change 加盟店変更希望
 * @property int|null $order_loss 加盟店なく失注
 * @property string|null $receive_datetime 受信日時
 * @property int|null $site_id サイト
 * @property int|null $genre_id ジャンル
 * @property int|null $category_id カテゴリ
 * @property int|null $cross_sell_source_site 【クロスセル】元サイト
 * @property int|null $cross_sell_source_category 【クロスセル】元カテゴリ
 * @property int|null $receptionist 受付者
 * @property string|null $customer_name お客様名
 * @property string|null $customer_tel お客様電話番号
 * @property string|null $customer_mailaddress お客様メールアドレス
 * @property string|null $postcode 郵便番号
 * @property string|null $address1 都道府県
 * @property string|null $address2 市区町村
 * @property string|null $address3 町域
 * @property string|null $address4 丁目番地
 * @property string|null $building 建物名
 * @property string|null $room 部屋号数
 * @property string|null $tel1 連絡先①
 * @property string|null $tel2 連絡先②
 * @property string|null $contents ご相談内容
 * @property string|null $contact_desired_time 連絡希望日時
 * @property string|null $jbr_order_no [JBR様]受付No
 * @property string|null $jbr_work_contents [JBR様]作業内容
 * @property string|null $jbr_category [JBR様]カテゴリ
 * @property string|null $mail メール本文
 * @property string|null $order_date 受注日
 * @property string|null $complete_date 施工完了日
 * @property string|null $order_fail_date 失注日
 * @property int|null $jbr_estimate_status [JBR様]見積書状況
 * @property int|null $jbr_receipt_status [JBR様]領収書状況
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property int|null $development_request 開拓依頼
 * @property string|null $share_notice 社内共有注意事項
 * @property int|null $sms_send_agreement SMS送信承諾
 * @property int $del_flg 削除フラグ
 * @property int|null $riro_kureka リロ・クレカ案件
 * @property int|null $cross_sell_source_genre 【クロスセル】元ジャンル
 * @property string|null $order_no_marriage 注文番号※婚活のみ
 * @property string|null $source_demand_id 元案件番号
 * @property int|null $reservation_demand リザベ案件
 * @property string|null $same_customer_demand_url 同一顧客案件URL
 * @property string|null $upload_estimate_file_name 見積書アップロードファイル名
 * @property string|null $upload_receipt_file_name 領収書アップロードファイル名
 * @property int|null $pet_tombstone_demand ペット墓石案内
 * @property int|null $sms_demand SMS案内
 * @property int|null $cost_customer_question お客様より金額質問あり
 * @property int|null $cost_from 価格提示_from
 * @property int|null $cost_to 価格提示_to
 * @property int|null $special_measures 特別施策
 * @property int|null $cost_customer_answer 上記料金質問の返答
 * @property int|null $call_back_time 折り返し日時
 * @property int|null $auction 入札流れ案件
 * @property int|null $follow フォロー済
 * @property int|null $business_trip_amount 出張費
 * @property string|null $auction_deadline_time オークション締切日時
 * @property int|null $selection_system 選定方式
 * @property int|null $priority 優先度
 * @property int|null $push_stop_flg オークションメールSTOPフラグ
 * @property string|null $auction_start_time オークション開始日時
 * @property int|null $acceptance_status 受付ステータス
 * @property int|null $construction_class 建物種別
 * @property int|null $cross_sell_implement クロスセル獲得
 * @property int|null $commission_limitover_time 取次完了リミット超過時間
 * @property bool|null $lock_flag ロックフラグ
 * @property string|null $lock_date ロック日時
 * @property int|null $lock_user_id ロックユーザーID
 * @property string|null $contact_desired_time_from 連絡期限日時_from
 * @property string|null $contact_desired_time_to 連絡期限日時_to
 * @property int|null $is_contact_time_range_flg
 * @property string|null $customer_corp_name 法人名
 * @property int|null $jbr_receipt_price [JBR様]領収書金額
 * @property int|null $nitoryu_flg 二刀流フラグ
 * @property int|null $cross_sell_call クロスセル声掛け
 * @property int|null $sms_reorder SMS再取次
 * @property int|null $st_claim STクレーム
 * @property int|null $calendar_flg
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AuctionInfo[] $auctionInfo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BillInfo[] $billInfos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionInfo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionInfoMail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $commissionWord
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DemandAttachedFile[] $demandAttachedFiles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DemandCorrespond[] $demandCorrespondHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DemandCorrespond[] $demandCorresponds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DemandInquiryAns[] $demandInquiryAnswers
 * @property-read false|string $auction_deadline_time_format
 * @property-read mixed $commission_limitover_time_format
 * @property-read false|string $follow_tel_date_format
 * @property-read mixed|string $m_site_name
 * @property-read int $over_limit_time
 * @property-read mixed $over_limit_time_format
 * @property-read false|string $receive_date_time_format
 * @property-read mixed|string $status_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DemandInquiryAns[] $inquiries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommissionInfo[] $introduceInfo
 * @property-read \App\Models\MCategory|null $mCategory
 * @property-read \App\Models\MGenre|null $mGenres
 * @property-read \App\Models\MSite|null $mSite
 * @property-read \App\Models\MUser|null $mUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VisitTime[] $visitTimes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAcceptanceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAddress3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAddress4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAuction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAuctionDeadlineTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereAuctionStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereBusinessTripAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCalendarFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCallBackTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCommissionLimitoverTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCompleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereConstructionClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereContactDesiredTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereContactDesiredTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereContactDesiredTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCorpChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCostCustomerAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCostCustomerQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCostFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCostTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCrossSellCall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCrossSellImplement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCrossSellSourceCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCrossSellSourceGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCrossSellSourceSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCustomerCorpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCustomerMailaddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereCustomerTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereDemandStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereDevelopmentRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereFollow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereFollowDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereImmediately($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereIsContactTimeRangeFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereJbrCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereJbrEstimateStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereJbrOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereJbrReceiptPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereJbrReceiptStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereJbrWorkContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereLockDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereLockFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereLockUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereLowAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereMailDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereNighttimeTakeover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereNitoryuFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereOrderFailDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereOrderLoss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereOrderNoMarriage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo wherePetTombstoneDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo wherePushStopFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereReceiveDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereReceptionist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereRemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereReservationDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereRiroKureka($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSameCustomerDemandUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSelectionSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereShareNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSmsDemand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSmsReorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSmsSendAgreement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSourceDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereSpecialMeasures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereStClaim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereTel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereTel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereUploadEstimateFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandInfo whereUploadReceiptFileName($value)
 * @mixin \Eloquent
 */
class DemandInfo extends Model
{
    const AU_DROP_DOWN_START_YEAR = 2015; // auction setting base year
    const DEMAND_INFO_AUCTIONED = 1; // auction field in DB when auctioned.
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    //define format csv
    const CSV_AUCTION_SETTING_HEADER = [
        'prefecture_name' => '都道府県',
        'year_count' => '累計件数',
        'year_flowing_ratio' => '累計率',
        'month_count' => '件数',
        'month_flowing_ratio' => '率'
    ];
    const SELECTION_TYPE = ['manual' => 0, 'auto' => 4, 'auction' => 2, 'auto_auction' => 3];
    /**
     * @var array
     */
    public $conditionForOrder = [
        'auction' => 'demand_infos.auction',
        'modified2' => 'commission_infos.modified',
        'user_name' => 'm_user_name',
        'holiday' => '((ARRAY_TO_STRING(ARRAY(SELECT "mi"."item_name"
                                        FROM m_corp_subs mcs INNER JOIN m_items mi ON "mi"."item_category" = "mcs"."item_category"
                                        AND "mi"."item_id" = "mcs"."item_id" WHERE "mcs"."item_category" = \'休業日\'
                                        AND "mcs"."corp_id" = "m_corps"."id"), \',\')))',
        'visit_time' => 'visit_time_view.visit_time',
        'demand_follow_date' => '(case when "demand_infos"."follow_date" is null then \'Z\'
                                        when "demand_infos"."follow_date" = \'\' then \'Y\'
                                        else "demand_infos"."follow_date" end)',
        'commission_rank' => 'm_genres.commission_rank',
        'detect_contact_desired_time' => '(CASE WHEN "visit_time_view"."is_visit_time_range_flg" = 1
                THEN "visit_time_view"."visit_adjust_time" WHEN "demand_infos"."is_contact_time_range_flg" = 1
                THEN "demand_infos"."contact_desired_time_from" ELSE "demand_infos"."contact_desired_time" END)',
        'corp_name' => 'm_corps.corp_name',
        'site_id' => 'demand_infos.site_id',
        'customer_name' => 'demand_infos.customer_name',
        'address' => 'demand_infos.address1',
        'contactable' => '(CASE "m_corps"."contactable_support24hour"
                                        WHEN 1 THEN \'24H対応\'
                                        ELSE "m_corps"."contactable_time_from" || \'～\' || "m_corps"."contactable_time_to" END)',
        'first_commission' => 'commission_infos.first_commission',
        'demand_id' => 'demand_infos.id',
        'commission_dial' => 'm_corps.commission_dial',
        'follow_date' => 'demand_infos.follow_date'
    ];
    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * @var array
     */
    public $demandFields = [
        'id' => null,
        'follow_date' => null,
        'demand_status' => null,
        'order_fail_reason' => null,
        'mail_demand' => null,
        'nighttime_takeover' => null,
        'low_accuracy' => null,
        'remand' => null,
        'immediately' => null,
        'corp_change' => null,
        'order_loss' => null,
        'receive_datetime' => null,
        'site_id' => null,
        'genre_id' => null,
        'category_id' => null,
        'cross_sell_source_site' => null,
        'cross_sell_source_category' => null,
        'receptionist' => null,
        'customer_name' => null,
        'customer_tel' => null,
        'customer_mailaddress' => null,
        'postcode' => null,
        'address1' => null,
        'address2' => null,
        'address3' => null,
        'address4' => null,
        'building' => null,
        'room' => null,
        'tel1' => null,
        'tel2' => null,
        'contents' => null,
        'contact_desired_time' => null,
        'jbr_order_no' => null,
        'jbr_work_contents' => null,
        'jbr_category' => null,
        'mail' => null,
        'order_date' => null,
        'complete_date' => null,
        'order_fail_date' => null,
        'jbr_estimate_status' => null,
        'jbr_receipt_status' => null,
        'modified_user_id' => null,
        'created_user_id' => null,
        'development_request' => null,
        'share_notice' => null,
        'sms_send_agreement' => null,
        'del_flg' => 0,
        'riro_kureka' => 0,
        'cross_sell_source_genre' => null,
        'order_no_marriage' => null,
        'source_demand_id' => null,
        'reservation_demand' => 0,
        'same_customer_demand_url' => null,
        'upload_estimate_file_name' => null,
        'upload_receipt_file_name' => null,
        'pet_tombstone_demand' => null,
        'sms_demand' => 0,
        'cost_customer_question' => 0,
        'cost_from' => null,
        'cost_to' => null,
        'special_measures' => 0,
        'cost_customer_answer' => null,
        'call_back_time' => null,
        'auction' => 0,
        'follow' => 0,
        'business_trip_amount' => null,
        'auction_deadline_time' => null,
        'selection_system' => null,
        'priority' => null,
        'push_stop_flg' => 0,
        'auction_start_time' => null,
        'acceptance_status' => null,
        'construction_class' => null,
        'cross_sell_implement' => null,
        'commission_limitover_time' => null,
        'lock_flag' => null,
        'lock_date' => null,
        'lock_user_id' => null,
        'contact_desired_time_from' => null,
        'contact_desired_time_to' => null,
        'is_contact_time_range_flg' => null,
        'customer_corp_name' => null,
        'jbr_receipt_price' => null,
        'nitoryu_flg' => 0,
        'cross_sell_call' => null,
        'sms_reorder' => 0,
        'st_claim' => 0,
        'calendar_flg' => 0
    ];

    // declare constant selection type for selection_system field
    /**
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demand_infos';
    /**
     * @var array
     */
    private $visitTimeList;

    /**
     * define default format csv
     *
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'default' => [
                'id' => trans('demandinfo_table.demand_id'),
                'follow_date' => trans('demandinfo_table.follow_date'),
                'demand_status' => trans('demandinfo_table.demand_status'),
                'order_fail_reason' => trans('demandinfo_table.order_fail_reason'),
                'mail_demand' => trans('demandinfo_table.mail_demand'),
                'nighttime_takeover' => trans('demandinfo_table.nighttime_takeover'),
                'low_accuracy' => trans('demandinfo_table.low_accuracy'),
                'remand' => trans('demandinfo_table.remand'),
                'immediately' => trans('demandinfo_table.immediately'),
                'corp_change' => trans('demandinfo_table.corp_change'),
                'receive_datetime' => trans('demandinfo_table.receive_datetime'),
                'site_name' => trans('demandinfo_table.site_name'),
                'genre_name' => trans('demandinfo_table.genre_name'),
                'category_name' => trans('demandinfo_table.category_name'),
                'cross_sell_source_site' => trans('demandinfo_table.cross_sell_source_site'),
                'cross_sell_source_genre' => trans('demandinfo_table.cross_sell_source_genre'),
                'cross_sell_source_category' => trans('demandinfo_table.cross_sell_source_category'),
                'source_demand_id' => trans('demandinfo_table.source_demand_id'),
                'same_customer_demand_url' => trans('demandinfo_table.same_customer_demand_url'),
                'receptionist' => trans('demandinfo_table.receptionist'),
                'customer_name' => trans('demandinfo_table.customer_name'),
                'customer_corp_name' => trans('demandinfo_table.customer_corp_name'),
                'customer_tel' => trans('demandinfo_table.customer_tel'),
                'customer_mailaddress' => trans('demandinfo_table.customer_mailaddress'),
                'postcode' => trans('demandinfo_table.postcode'),
                'address1' => trans('demandinfo_table.address1'),
                'address2' => trans('demandinfo_table.address2'),
                'address3' => trans('demandinfo_table.address3'),
                'tel1' => trans('demandinfo_table.tel1'),
                'tel2' => trans('demandinfo_table.tel2'),
                'contents' => trans('demandinfo_table.contents'),
                'contact_desired_time' => trans('demandinfo_table.contact_desired_time'),
                'selection_system' => trans('demandinfo_table.selection_system'),
                'pet_tombstone_demand' => trans('demandinfo_table.pet_tombstone_demand'),
                'sms_demand' => trans('demandinfo_table.sms_demand'),
                'order_no_marriage' => trans('demandinfo_table.order_no_marriage'),
                'jbr_order_no' => trans('demandinfo_table.jbr_order_no'),
                'jbr_work_contents' => trans('demandinfo_table.jbr_work_contents'),
                'jbr_category' => trans('demandinfo_table.jbr_category'),
                'mail' => trans('demandinfo_table.mail'),
                'order_date' => trans('demandinfo_table.order_date'),
                'complete_date' => trans('demandinfo_table.complete_date'),
                'order_fail_date' => trans('demandinfo_table.order_fail_date'),
                'jbr_estimate_status' => trans('demandinfo_table.jbr_estimate_status'),
                'jbr_receipt_status' => trans('demandinfo_table.jbr_receipt_status'),
                'acceptance_status' => trans('demandinfo_table.acceptance_status'),
                'nitoryu_flg' => trans('demandinfo_table.nitoryu_flg'),
                'commission_id' => trans('demandinfo_table.commission_id'),
                'corp_id' => trans('demandinfo_table.corp_id'),
                'corp_name' => trans('demandinfo_table.corp_name'),
                'official_corp_name' => trans('demandinfo_table.official_corp_name'),
                'commit_flg' => trans('demandinfo_table.commit_flg'),
                'commission_type' => trans('demandinfo_table.commission_type'),
                'appointers' => trans('demandinfo_table.appointers'),
                'first_commission' => trans('demandinfo_table.first_commission'),
                'corp_fee' => trans('demandinfo_table.corp_fee'),
                'attention' => trans('demandinfo_table.attention'),
                'commission_dial' => trans('demandinfo_table.commission_dial'),
                'tel_commission_datetime' => trans('demandinfo_table.tel_commission_datetime'),
                'tel_commission_person' => trans('demandinfo_table.tel_commission_person'),
                'commission_fee_rate' => trans('demandinfo_table.commission_fee_rate'),
                'commission_note_send_datetime' => trans('demandinfo_table.commission_note_send_datetime'),
                'commission_note_sender' => trans('demandinfo_table.commission_note_sender'),
                'send_mail_fax' => trans('demandinfo_table.send_mail_fax'),
                'send_mail_fax_datetime' => trans('demandinfo_table.send_mail_fax_datetime'),
                'commission_status' => trans('demandinfo_table.commission_status'),
                'commission_order_fail_reason' => trans('demandinfo_table.commission_order_fail_reason'),
                'commission_infos_complete_date' => trans('demandinfo_table.complete_date'),
                'commission_infos_order_fail_date' => trans('demandinfo_table.order_fail_date'),
                'estimate_price_tax_exclude' => trans('demandinfo_table.estimate_price_tax_exclude'),
                'construction_price_tax_exclude' => trans('demandinfo_table.construction_price_tax_exclude'),
                'construction_price_tax_include' => trans('demandinfo_table.construction_price_tax_include'),
                'deduction_tax_include' => trans('demandinfo_table.deduction_tax_include'),
                'deduction_tax_exclude' => trans('demandinfo_table.deduction_tax_exclude'),
                'confirmd_fee_rate' => trans('demandinfo_table.confirmd_fee_rate'),
                'unit_price_calc_exclude' => trans('demandinfo_table.unit_price_calc_exclude'),
                'report_note' => trans('demandinfo_table.report_note'),
            ],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mCategory()
    {
        return $this->belongsTo('App\Models\MCategory', 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billInfos()
    {
        return $this->hasMany('App\Models\BillInfo', 'demand_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mGenres()
    {
        return $this->belongsTo('App\Models\MGenre', 'genre_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctionInfo()
    {
        return $this->hasMany("App\Models\AuctionInfo", "demand_id", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mSite()
    {
        return $this->belongsTo('App\Models\MSite', 'site_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mUser()
    {
        return $this->belongsTo('App\Models\MUser', 'receptionist', 'id');
    }

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visitTimes()
    {
        return $this->hasMany(VisitTime::class, 'demand_id', 'id')->orderBy('id', 'asc');
    }

    /**
     * @return $this
     */
    public function demandCorresponds()
    {
        return $this->hasMany(DemandCorrespond::class, 'demand_id', 'id')->orderBy('id', 'desc');
    }

    /**
     * Get over limit time attribute
     *
     * @return integer
     */
    public function getOverLimitTimeAttribute()
    {
        if (!isset($this->mGenres) || !in_array($this->getAttribute('demand_status'), [1, 2, 3])
            || ($this->mGenres && $this->mGenres->commission_limit_time < 1)) {
            return 0;
        }

        $limitTimeStamp = strtotime($this->getAttribute('receive_datetime') . '+ ' . (int)$this->mGenres->commission_limit_time . ' minute');
        $overLimitTimeStamp = strtotime("now") - $limitTimeStamp;

        return $overLimitTimeStamp;
    }

    /**
     * Get over limit time format attribute
     *
     * @return mixed
     */
    public function getOverLimitTimeFormatAttribute()
    {
        $overLimitTime = $this->getAttribute('over_limit_time');

        return ($overLimitTime > 0) ? round($overLimitTime / 3600) . '時間' . round($overLimitTime % 3600 / 60) . '分' : '';
    }

    /**
     * @return DemandInfo
     */
    public function commissions()
    {
        return $this->commissionInfos();
    }

    /**
     * @return $this
     */
    public function commissionInfos()
    {
        return $this->commissionInfo()->where('del_flg', 0)->orderBy('id', 'asc')
            ->with('mCorpAndMCorpNewYear');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commissionInfo()
    {
        return $this->hasMany(CommissionInfo::class, 'demand_id', 'id');
    }

    /**
     * @return $this
     */
    public function introduceInfo()
    {
        return $this->hasMany(CommissionInfo::class, 'demand_id', 'id')
            ->where('commission_infos.commission_type', '1')
            ->orderBy('commission_infos.id', 'asc');
    }

    /**
     * @return $this
     */
    public function demandCorrespondHistory()
    {
        return $this->hasMany(DemandCorrespond::class, 'demand_id', 'id')
            ->orderBy('demand_corresponds.id', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demandInquiryAnswers()
    {
        return $this->hasMany(DemandInquiryAns::class, 'demand_id', 'id');
    }

    /**
     * @return $this
     */
    public function demandAttachedFiles()
    {

        return $this->hasMany(DemandAttachedFile::class, 'demand_id', 'id')->orderBy('id', 'asc');
    }

    /**
     * @return $this
     */
    public function demandNotification()
    {
        return $this->hasMany(DemandNotification::class, 'demand_id', 'id')->orderBy('id', 'asc');
    }

    /**
     * @return mixed|string
     */
    public function getStatusNameAttribute()
    {
        $demandStatus = $this->getAttribute('demand_status');
        $item = MItem::getByCategoryAndDemandStatus('案件状況', $demandStatus);

        return $item ? $item->item_name : '';
    }

    /**
     * @return mixed|string
     */
    public function getStatusIdAttribute(){
        $demandStatus = $this->getAttribute('demand_status');
        $item = MItem::getByCategoryAndDemandStatus('案件状況', $demandStatus);

        return $item ? $item->id : '';
    }

    /**
     * @return mixed|string
     */
    public function getMSiteNameAttribute()
    {
        $siteId = $this->getAttribute('site_id');
        $mSite = MSite::find($siteId);
        return $mSite ? $mSite->site_name : '';
    }

    /**
     * @return false|string
     */
    public function getReceiveDateTimeFormatAttribute()
    {
        return dateTimeFormat($this->getAttribute('receive_datetime'));
    }

    /**
     * @return false|string
     */
    public function getAuctionDeadlineTimeFormatAttribute()
    {
        return dateTimeFormat($this->getAttribute('auction_deadline_time'));
    }

    /**
     * @return false|string
     */
    public function getFollowTelDateFormatAttribute()
    {
        return dateTimeFormat($this->getAttribute('follow_tel_date'));
    }

    /**
     * @return false|string
     */
    public function getContactDesiredTimeFromFormatAttribute()
    {
        return dateTimeFormat($this->getAttribute('contact_desired_time_from'));
    }

    /**
     * @return false|string
     */
    public function getContactDesiredTimeToFormatAttribute()
    {
        return dateTimeFormat($this->getAttribute('contact_desired_time_to'));
    }

    /**
     *
     */
    public function getCommissionLimitoverTimeFormatAttribute()
    {
        return formatSec($this->getAttribute('commission_limitover_time') * 60);
    }

    /**
     * @return $this
     */
    public function commissionInfoMail()
    {
        return $this->hasMany(CommissionInfo::class, 'demand_id', 'id')
            ->where('commit_flg', 1)
            ->where('del_flg', '!=', 1)
            ->where('introduction_not', '!=', 1)
            ->where('lost_flg', '!=', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commissionWord()
    {
        return $this->hasMany(CommissionInfo::class, 'demand_id', 'id');
    }

    // original field in DB

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inquiries()
    {
        return $this->hasMany('App\Models\DemandInquiryAns', 'demand_id', 'id');
    }

    /**
     * build visit time
     * @author thaihv
     * @param  array $old user input
     * @param  int $idx position
     * @param  string $field (visit_time_from, visit_time_to, ajuidment)
     * @return string        time value
     */
    public function buildVisittime($old, $idx, $field)
    {
        $txtTime = '';
        if (!empty($old)) {
            $idx++;
        }
        if (is_null($this->visitTimeList)) {
            $this->visitTimeList = $this->visitTimes->toArray();
        }
        if (isset($old[$idx][$field])) {
            $txtTime = $old[$idx][$field];
        } elseif (isset($this->visitTimeList[$idx])) {
            $txtTime = $this->visitTimeList[$idx][$field];
        }
        if (!empty($txtTime)) {
            $txtTime = date('Y/m/d H:i', strtotime($txtTime));
        }
        return $txtTime;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany("App\Models\DemandNotification", "demand_id", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function extendInfo()
    {
        return $this->hasOne("App\Models\DemandExtendInfo", "demand_id", "demand_id");
    }
}
