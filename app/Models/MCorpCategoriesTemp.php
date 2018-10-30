<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCorpCategoriesTemp
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property int $genre_id ジャンルID
 * @property int $category_id カテゴリID
 * @property int|null $order_fee 受注手数料
 * @property int|null $order_fee_unit 受注手数料単位
 * @property int|null $introduce_fee 紹介手数料
 * @property string|null $note 備考
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $select_list select_list
 * @property int|null $select_genre_category 対応可能ジャンルフラグ
 * @property int|null $target_area_type 対応可能エリアタイプ
 * @property int $version_no バージョンNo
 * @property string|null $create_date 登録日
 * @property string|null $create_user_id 登録ユーザーID
 * @property string|null $update_date 更新日
 * @property string|null $update_user_id 更新ユーザーID
 * @property string|null $delete_date 削除日
 * @property bool $delete_flag 削除フラグ
 * @property int|null $temp_id
 * @property string|null $action
 * @property int|null $corp_commission_type 取次形態
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCorpCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereDeleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereDeleteFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereIntroduceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereOrderFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereOrderFeeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereSelectGenreCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereSelectList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereTargetAreaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereTempId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereUpdateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereUpdateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpCategoriesTemp whereVersionNo($value)
 * @mixin \Eloquent
 */
class MCorpCategoriesTemp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_corp_categories_temp';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    const DELETE_FLAG_TRUE = true;
    const DELETE_FLAG_FALSE = false;

    /**
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'id' => trans('report_corp_agreement_category.history_id'),
            'corp_agreement_id' => trans('report_corp_agreement_category.contract_id'),
            'm_corps_id' => trans('report_corp_agreement_category.company_id'),
            'official_corp_name' => trans('report_corp_agreement_category.formal_member_store_name'),
            'm_genres_id' => trans('report_corp_agreement_category.genre_id'),
            'genre_name' => trans('report_corp_agreement_category.genre_name'),
            'm_categories_id' => trans('report_corp_agreement_category.category_id'),
            'category_name' => trans('report_corp_agreement_category.category_name'),
            'order_fee' => trans('report_corp_agreement_category.order_receiving_fee'),
            'custom_order_fee_unit' => trans('report_corp_agreement_category.order_commission_unit_price'),
            'introduce_fee' => trans('report_corp_agreement_category.referral_fee'),
            'note' => trans('report_corp_agreement_category.remarks'),
            'select_list' => trans('report_corp_agreement_category.expertise'),
            'custom_corp_commission_type' => trans('report_corp_agreement_category.order_form'),
            'custom_action_type' => trans('report_corp_agreement_category.update_type'),
            'custom_action' => trans('report_corp_agreement_category.update_contents'),
            'modified' => trans('report_corp_agreement_category.update_date_and_time'),
        ];
    }
    const SELECT_LIST = [
        '' => 'なし',
        'A' => 'A',
        'B' => 'B',
        'C' => 'C'
    ];
}
