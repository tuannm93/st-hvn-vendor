<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MTargetArea
 *
 * @property int $id ID
 * @property int $corp_category_id 企業別対応カテゴリカテゴリID
 * @property string $jis_cd 全国地方公共団体コード
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property string|null $address1_cd 都道府県コード
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereAddress1Cd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereCorpCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereJisCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MTargetArea whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MTargetArea extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_target_areas';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
