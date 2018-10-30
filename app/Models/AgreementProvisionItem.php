<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementProvisionItem
 *
 * @property int $id ID
 * @property int $agreement_provisions_id 契約条文マスタID
 * @property string|null $item 項目
 * @property int $sort_no 表示順
 * @property int $last_history_id 最新履歴ID
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereAgreementProvisionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereLastHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereSortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvisionItem whereVersionNo($value)
 * @mixin \Eloquent
 */
class AgreementProvisionItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_provisions_item';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @var mixed
     */
    protected $customizeKey;

    /**
     * @return mixed
     */
    public function getCustomizeItemKey()
    {
        if (is_null($this['customizeKey']) || empty($this['customizeKey'])) {
            return $this->id;
        } else {
            return $this['customizeKey'];
        }
    }
}
