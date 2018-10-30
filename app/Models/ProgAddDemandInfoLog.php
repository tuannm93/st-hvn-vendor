<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProgAddDemandInfoLog
 *
 * @property int $id ID
 * @property int $prog_add_demand_info_id 進捗管理追加案件ID
 * @property int $prog_corp_id 進捗管理企業ID
 * @property int $prog_import_file_id 進捗管理ファイルID
 * @property int $corp_id 企業ID
 * @property int $sequence
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
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新ユーザID
 * @property int $agree_flag 同意チェック
 * @property int|null $demand_type_update 案件属性
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereAgreeFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCategoryNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCommentUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCommissionStatusUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCompleteDateUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereConstructionPriceTaxExcludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereConstructionPriceTaxIncludeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereCustomerNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereDemandIdUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereDemandTypeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereHostNameUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereIpAddressUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereProgAddDemandInfoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereProgCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereProgImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProgAddDemandInfoLog whereUserAgentUpdate($value)
 * @mixin \Eloquent
 */
class ProgAddDemandInfoLog extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];
}
