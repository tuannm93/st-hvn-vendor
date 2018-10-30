<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgAddDemandInfoTmp
 *
 * @property int $id ID
 * @property int|null $prog_add_demand_info_id 進捗管理追加案件ID
 * @property int $prog_corp_id 進捗管理企業ID
 * @property int|null $prog_import_file_id 進捗管理ファイルID
 * @property int|null $corp_id 企業ID
 * @property int|null $sequence シーケンス
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
 * @property int|null $agree_flag 同意チェック
 * @property int|null $display 表示フラグ
 * @property int|null $demand_type_update 案件属性
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCategoryNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCommentUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCommissionStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCompleteDateUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereConstructionPriceTaxExcludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereConstructionPriceTaxIncludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereCustomerNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereDemandIdUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereDemandTypeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereHostNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereIpAddressUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereProgAddDemandInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoTmp whereUserAgentUpdate($value)
 * @mixin \Eloquent
 */
class ProgAddDemandInfoTmp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prog_add_demand_info_tmps';

    /**
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * map updated_at of eloquent with modified
     */
    const UPDATED_AT = 'modified';
}
