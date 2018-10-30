<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgreementCustomize
 *
 * @property int $id ID
 * @property int|null $corp_agreement_id corp_agreement_id
 * @property int $agreement_id 契約約款マスタID
 * @property string|null $edit_kind 処理区分     追加：Add
 * 更新：Update
 * 削除：Delete
 * @property string $table_kind 対象テーブル
 * @property int $original_id 変更元ID
 * @property string|null $content 内容
 * @property int $sort_no 表示順
 * @property int $last_history_id 最新履歴ID
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property int|null $corp_id
 * @property int|null $original_provisions_id 変更元契約条文マスタID
 * @property int|null $original_item_id 変更元契約条文項目マスタID
 * @property int|null $customize_provisions_id
 * @property int|null $customize_item_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereCorpAgreementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereCustomizeItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereCustomizeProvisionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereEditKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereLastHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereOriginalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereOriginalItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereOriginalProvisionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereSortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereTableKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AgreementCustomize whereVersionNo($value)
 * @mixin \Eloquent
 */
class AgreementCustomize extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement_customize';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
    /**
     * * value of table_kind field in table
     */
    const AGREEMENT_PROVISIONS = 'AgreementProvisions';
    /**
     *
     */
    const AGREEMENT_PROVISIONS_ITEM = 'AgreementProvisionsItem';
    /**
     *
     */
    const TABLE_KIND_LABEL = [
        self::AGREEMENT_PROVISIONS => '条文',
        self::AGREEMENT_PROVISIONS_ITEM => '項目'
    ];
    /**
     * value of edit_kind field in table
     */
    const DELETE = 'Delete';
    /**
     *
     */
    const UPDATE = 'Update';
    /**
     *
     */
    const ADD = 'Add';
    /**
     *
     */
    const EDIT_KIND_LABEL = [
        self::ADD => '追加',
        self::UPDATE => '更新',
        self::DELETE => '削除'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agreementProvision()
    {
        return $this->belongsTo('\App\Models\AgreementProvision', 'original_provisions_id', 'id');
    }

    /**
     * @return mixed|string
     */
    public function getCustomizeProvisionKey()
    {
        if ($this->original_provisions_id != 0) {
            return $this->original_provisions_id;
        } else {
            return "c" . $this->customize_provisions_id;
        }
    }

    /**
     * @return mixed|string
     */
    public function getCustomizeItemKey()
    {
        if ($this->original_item_id != 0) {
            return $this->original_item_id;
        } else {
            return "c" . $this->customize_item_id;
        }
    }
}
