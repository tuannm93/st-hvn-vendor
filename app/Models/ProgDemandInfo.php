<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgDemandInfo
 *
 * @property int $id ID
 * @property int $prog_corp_id 進捗管理企業ID
 * @property int $prog_import_file_id 進捗管理ファイルID
 * @property int $corp_id 企業ID
 * @property int $commission_id 取次ID
 * @property int $demand_id 案件ID
 * @property int $agree_flag 同意チェック
 * @property int|null $diff_flg 差分フラグ
 * @property string|null $comment_update 備考
 * @property string $commission_status 取次状態
 * @property string|null $commission_status_update 取次状態更新
 * @property int|null $commission_order_fail_reason 失注理由
 * @property int|null $commission_order_fail_reason_update 失注理由更新
 * @property string|null $complete_date 施工完了・失注日
 * @property string|null $complete_date_update 施工完了・失注日更新
 * @property string|null $construction_price_tax_exclude 施工金額（税抜）
 * @property string|null $construction_price_tax_exclude_update 施工金額（税抜）更新
 * @property string|null $construction_price_tax_include 施工金額（税込）
 * @property string|null $construction_price_tax_include_update 施工金額（税込）更新
 * @property string|null $ip_address_update IPアドレス
 * @property string|null $user_agent_update ユーザーエージェント
 * @property string|null $host_name_update ホスト名
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成ユーザID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $modified_user_id 更新ユーザID
 * @property string|null $category_name カテゴリ名
 * @property string|null $customer_name お客様名
 * @property float|null $fee_rate 手数料率
 * @property int|null $fee_target_price 手数料対象金額
 * @property int|null $fee 手数料金額
 * @property string|null $genre_name ジャンル名
 * @property string|null $fee_billing_date 手数料請求日
 * @property string|null $receive_datetime 受信日時
 * @property-read \App\Models\CommissionInfo $commissionInfo
 * @property-read \App\Models\MCorp $mCorp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProgAddDemandInfo[] $progAddDemandInfo
 * @property-read \App\Models\ProgCorp $progCorp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProgDemandInfoTmp[] $rogDemandInfoTmps
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCommentUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCommissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCommissionOrderFailReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCommissionOrderFailReasonUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCommissionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCommissionStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCompleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCompleteDateUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereConstructionPriceTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereConstructionPriceTaxExcludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereConstructionPriceTaxInclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereConstructionPriceTaxIncludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereDiffFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereFeeBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereFeeTargetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereGenreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereHostNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereIpAddressUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereReceiveDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgDemandInfo whereUserAgentUpdate($value)
 * @mixin \Eloquent
 */
class ProgDemandInfo extends Model
{
    // map updated_at of eloquent with modified
    const UPDATED_AT = 'modified';
    const PM_TAX = 8;
    const PM_COMMISSION_STATUS = [1=>'進行中', 2 => '受注', 3 => '施工完了', 4 => '失注'];
    const PM_DIFF_LIST = [1=> '--', 2 => '変更なし', 3 => '変更がある'];
    const PM_DIFF_LIST_DEMAND_DETAIL = [1=> '--', 2 => '変更はない', 3 => '変更がある'];
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


    const CSV_FIELD = [
        'corp_name' => '企業名', //m_corps
        'official_corp_name' => '正式名',//m_corps
        'corp_id' => '施工会社番号',
        'commission_dial' => '取次要ダイヤル',//m_corps
        'mail_address' => '進捗表送付先(メール)', //prog_corps
        'fax' => '進捗表送付(FAX)',//prog_corps
        'contact_type' => '送付方法',//prog_corps
        'unit_cost' => '単価',//prog_corps
        'progress_flag' => '進捗表状況',//prog_corps
        'demand_id' =>'案件コード',
        'commission_id' =>'取次ID',
        'receive_datetime' =>'受信日時',
        'customer_name' =>'(ひらがなフルネーム)',
        'category_name' =>'カテゴリ',
        'fee' =>'手数料率(手数料金額)',
        'complete_date' => '施工完了日[失注日](インポート時)',
        'construction_price_tax_exclude' => '施工金額（税抜）(インポート時)',
        'commission_status' =>'進捗状況(インポート時)',
        'diff_flg' =>'情報相違',
        'complete_date_update' =>'施工完了日[失注日](業者返送時)',
        'construction_price_tax_exclude_update' =>'施工金額（税抜）(業者返送時)',
        'commission_status_update' =>'進捗状況(業者返送時)',
        'fee_target_price' =>'手数料対象金額',
        'commission_order_fail_reason_update' =>'失注理由',
        'comment_update' =>'備考欄',
        'koujo' => '控除金額',//prog_corps
        'collect_date' => '回収日',//prog_corps
        'sf_register_date' => '未送信（焦げ付き）',//prog_corps
        'last_send_date' => '最新送信日',//prog_corps
        'mail_count' => 'メール送付回数',//prog_corps
        'fax_count' => 'ＦＡＸ送付回数',//prog_corps
        'note' =>'後追い履歴',//prog_corps //カスタム項目
        'not_replay_flag' => '未返信理由',//prog_corps
        'fee_billing_date' => '手数料請求日',//請求データ参照
        'genre_name' => 'ジャンル',
        'tel1' => '連絡先①',//m_corps
        'agree_flag' =>'同意チェック',
        'ip_address_update' =>'IPアドレス',
        'user_agent_update' =>'ユーザーエージェント',
        'host_name_update' =>'ホスト名',
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prog_demand_infos';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function progCorp()
    {
        return $this->belongsTo('\App\Models\ProgCorp', 'prog_corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mCorp()
    {
        return $this->belongsTo('\App\Models\MCorp', 'corp_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rogDemandInfoTmps()
    {
        return $this->hasMany('App\Models\ProgDemandInfoTmp', 'prog_demand_info_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function progAddDemandInfo()
    {
        return $this->hasMany('App\Models\ProgAddDemandInfo', 'demand_id_update', 'demand_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commissionInfo()
    {
        return $this->belongsTo('App\Models\CommissionInfo', 'commission_id', 'id');
    }
}
