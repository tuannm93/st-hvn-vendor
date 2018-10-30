<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCorpNewYear
 *
 * @property int $id ID
 * @property int|null $corp_id 企業ID
 * @property string|null $label_01 日付1
 * @property string|null $status_01 日付1ステータス
 * @property string|null $label_02 日付2
 * @property string|null $status_02 日付2ステータス
 * @property string|null $label_03 日付3
 * @property string|null $status_03 日付3ステータス
 * @property string|null $label_04 日付4
 * @property string|null $status_04 日付4ステータス
 * @property string|null $label_05 日付5
 * @property string|null $status_05 日付5ステータス
 * @property string|null $label_06 日付6
 * @property string|null $status_06 日付6ステータス
 * @property string|null $label_07 日付7
 * @property string|null $status_07 日付7ステータス
 * @property string|null $label_08 日付8
 * @property string|null $status_08 日付8ステータス
 * @property string|null $label_09 日付9
 * @property string|null $status_09 日付9ステータス
 * @property string|null $label_10 日付10
 * @property string|null $status_10 日付10ステータス
 * @property string|null $note 備考
 * @property string $created 作成日
 * @property string|null $modified 更新日
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel06($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel07($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel08($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel09($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereLabel10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus06($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus07($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus08($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus09($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpNewYear whereStatus10($value)
 * @mixin \Eloquent
 */
class MCorpNewYear extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_corp_new_years';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}
