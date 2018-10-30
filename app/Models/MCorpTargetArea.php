<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MCorpTargetArea
 *
 * @property int $id ID
 * @property int|null $corp_id 企業ID
 * @property string|null $jis_cd 全国地方公共団体コード
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereJisCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MCorpTargetArea whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class MCorpTargetArea extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_corp_target_areas';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
