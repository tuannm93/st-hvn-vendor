<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementProvision
 *
 * @property int $id ID
 * @property int $agreement_id 契約約款マスタID
 * @property string|null $provisions 条文
 * @property int $sort_no 表示順
 * @property int $last_history_id 最新履歴ID
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property-read \App\Models\Agreement $agreement
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AgreementProvisionItem[] $agreementProvisionItem
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereLastHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereProvisions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereSortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementProvision whereVersionNo($value)
 * @mixin \Eloquent
 */
class AgreementProvision extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_provisions';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agreement()
    {
        return $this->belongsTo('App\Models\Agreement', 'id', 'agreement_id');
    }

    /**
     * @return $this
     */
    public function agreementProvisionItem()
    {
        return $this->hasMany('App\Models\AgreementProvisionItem', 'agreement_provisions_id', 'id')->orderBy('sort_no');
    }

    /**
     * @return string
     */
    public function getContentAndAllItems()
    {
        $content = (checkIsNullOrEmptyStr($this->provisions) ? "null" : $this->provisions) . " \n";
        $items = $this->agreementProvisionItem;
        foreach ($items as $item) {
            $content .= " " . (checkIsNullOrEmptyStr($item->item) ? "null" : $item->item) . " \n";
        }
        return $content;
    }
}
