<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReputationChecks
 *
 * @property int $id 風評チェックID
 * @property int|null $corp_id 企業ID
 * @property string|null $date 風評チェック日時
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReputationChecks whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class ReputationChecks extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reputation_checks';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;
}
