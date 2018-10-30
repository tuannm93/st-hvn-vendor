<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgAddDemandInfo
 * @property int $id ID
 * @property int $prog_corp_id 進捗管理企業ID
 * @property int $prog_import_file_id 進捗管理ファイルID
 * @property int $corp_id 企業ID
 * @property int|null $demand_id_update 案件ID
 * @property string|null $category_name_update カテゴリ名
 * @property string|null $customer_name_update お客様名
 * @property string|null $comment_update 備考
 * @property string|null $commission_status_update 取次状態更新
 * @property string|null $complete_date_update 施工完了・失注日更新
 * @property string|null $construction_price_tax_exclude_update 施工金額（税抜）更新
 * @property string|null $construction_price_tax_include_update 施工金額（税込）更新
 * @property string|null $ip_address_update IPアドレス
 * @property string|null $user_agent_update ユーザーエージェント
 * @property string|null $host_name_update ホスト名
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成ユーザID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $modified_user_id 更新ユーザID
 * @property int $sequence
 * @property int $agree_flag 同意チェック
 * @property int|null $demand_type_update 案件属性
 * @property-read \App\Models\MCorp $mCorp
 * @property-read \App\Models\ProgCorp $progCorp
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCategoryNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCommentUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCommissionStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCompleteDateUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereConstructionPriceTaxExcludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereConstructionPriceTaxIncludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereCustomerNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereDemandIdUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereDemandTypeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereHostNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereIpAddressUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfo whereUserAgentUpdate($value)
 * @mixin \Eloquent
 */
class ProgAddDemandInfo extends Model
{
    const UPDATED_AT = 'modified';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
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
}
