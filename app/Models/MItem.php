<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MItem
 *
 * @property int $id ID
 * @property int $item_id 項目ID
 * @property string $item_category 項目カテゴリ
 * @property string|null $item_name 項目名
 * @property int|null $sort_order 表示順
 * @property string|null $enabled_start 有効期限開始
 * @property string|null $enabled_end 有効期限終了
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereEnabledEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereEnabledStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereItemCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MItem whereSortOrder($value)
 * @mixin \Eloquent
 */
class MItem extends Model
{
    const CONMISSION_ORDER_FAIL_REASON = '取次失注理由';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_items';

    /**
     * @var array
     */
    protected $guarded = [];

    const PROGRESS_STATUS_CATEGORY = '進捗表状況';
    const PROGRESS_STATUS_REPLY_RESULT_CATEGORY = '進捗表_未返信理由';
    const PROGRESS_DELIVERY_CATEGORY = '進捗表送付方法';
    const PROGRESS_BACK_PHONE_CATEGORY = '進捗表_架電後フラグ';
    const COMMISSION_ORDER_FAIL_REASON = '取次失注理由';
    const BUILDING_TYPE = '建物種別';
    /**
     * get list by query function
     *
     * @param  string $query
     * @return object
     */
    public function scopeGetList($query)
    {
        return $query->where('id', '>', 100);
    }

    /**
     * get list genres function
     * @param string $category
     * @return array
     */
    public function getList($category)
    {
        $results = [];
        $list = self::where('item_category', $category)
                    ->where('enabled_start', '<=', date('Y/m/d'))
                    ->where(function ($query) {
                        $query->where('enabled_end', '>=', date('Y/m/d'))
                            ->orWhere('enabled_end', null);
                    })->orderBy('sort_order', 'asc')->get();

        foreach ($list as $val) {
            $results[$val['item_id']] = $val['item_name'];
        }

        return $results;
    }

    /**
     * get list genres item name function
     *
     * @param  string  $category
     * @param  integer $value
     * @return string
     */
    public function getListText($category, $value)
    {
        $results = MItem::where('item_category', '=', $category)
            ->where('item_id', '=', $value)
            ->orderBy('item_id', 'asc')->get()->toarray();
        if (isset($results[0]['item_name'])) {
            return $results[0]['item_name'];
        } else {
            return "";
        }
    }

    /**
     * get item by category name and demand status
     *
     * @param  string  $itemCategory
     * @param  integer $demandStatus
     * @return string
     */
    public static function getByCategoryAndDemandStatus($itemCategory, $demandStatus){
        $item = MItem::where('item_category', $itemCategory)
            ->where('item_id', $demandStatus)
            ->first();
        return $item ?? false;
    }
}
