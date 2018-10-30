<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdditionInfo
 *
 * @property int $id ID
 * @property int $corp_id 企業ID
 * @property string|null $demand_id 案件ID
 * @property string|null $customer_name お客様名
 * @property int|null $genre_id ジャンルID
 * @property int|null $construction_price_tax_exclude 施工金額(税抜)
 * @property string|null $complete_date 施工完了日
 * @property string|null $note 備考欄
 * @property int|null $falsity_flg 虚偽報告確認フラグ
 * @property int $demand_flg 案件発行済フラグ
 * @property int $del_flg 削除フラグ
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $memo メモ
 * @property int|null $demand_type_update 案件属性
 * @property-read \App\Models\MGenre|null $genres
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereCompleteDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereConstructionPriceTaxExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereDemandFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereDemandTypeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereFalsityFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdditionInfo whereNote($value)
 * @mixin \Eloquent
 */
class AdditionInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addition_infos';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $editable = ['corp_id', 'demand_id', 'customer_name', 'genre_id', 'construction_price_tax_exclude', 'complete_date', 'falsity_flg', 'demand_flg', 'demand_type_update', 'note', 'del_flg', 'memo', 'modified_user_id', 'updated_at'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function genres()
    {
        return $this->belongsTo('App\Models\MGenre', 'genre_id');
    }

    /**
     * @return array
     */
    public function getEditableColumns()
    {
        return $this->editable;
    }

    /**
     * @return array
     */
    public static function csvFieldList()
    {
        $csvFields = [];
        foreach (\Schema::getColumnListing('addition_infos') as $column) {
            $csvFields[] = "addition_infos." . $column . " AS addition_infos" . '_' . $column;
        }
        $addColumn = [
            'm_genres.genre_name',
        ];

        foreach ($addColumn as $column) {
            $field = explode(".", $column);
            $table = $field[0];
            $columnName = $field[1];
            $csvFields[] = "$table." . $columnName . " AS $table" . '_' . $columnName;
        }
        return $csvFields;
    }
}
