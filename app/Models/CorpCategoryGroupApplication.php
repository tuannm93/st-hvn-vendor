<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CorpCategoryGroupApplication
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryGroupApplication whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryGroupApplication whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryGroupApplication whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryGroupApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryGroupApplication whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CorpCategoryGroupApplication whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class CorpCategoryGroupApplication extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'corp_category_group_applications';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'id' => '申請グループID',
            'custom_application_section' => '申請区分',
            'corp_id' => '企業ID',
            'official_corp_name' => '対象加盟店',
            'application_user_id' => '申請者',
            'application_datetime' => '申請日時',
            'approvals_id' => '申請番号',
            'genre_id' => 'ジャンルID',
            'genre_name' => 'ジャンル名',
            'category_id' => 'カテゴリID',
            'category_name' => 'カテゴリ名',
            'order_fee' => '受注手数料',
            'custom_order_fee_unit' => '受注手数料単位',
            'custom_introduce_fee' => '紹介手数料',
            'custom_corp_commission_type' => '取次形態',
            'note'=>'備考',
            'application_reason' => '申請理由',
            'custom_status' => '可否',
            'approval_user_id' => '承認者',
            'approval_datetime' => '承認日時'
        ];
    }
}
