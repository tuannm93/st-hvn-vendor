<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\Models\AntisocialCheck
 *
 * @property int $id 反社チェックID
 * @property int|null $corp_id 企業ID
 * @property string|null $date 反社チェック日時
 * @property string|null $created 作成日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $modified 更新日時
 * @property string|null $modified_user_id 更新者ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AntisocialCheck whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class AntisocialCheck extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'antisocial_checks';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * get result list
     *
     * @return array
     */
    public function getResultList()
    {
        return [
            'None' => trans('antisocial_follow.none'),
            'OK' => trans('antisocial_follow.ok'),
            'NG' => trans('antisocial_follow.ng'),
            'Inadequate' => trans('antisocial_follow.inadequate')
        ];
    }

    /**
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'mcorp_id' => trans('antisocial_follow.mcorp_id'),
            'official_corp_name' => trans('antisocial_follow.official_corp_name'),
            'corp_name_kana' => trans('antisocial_follow.corp_name_kana'),
            'max' => trans('antisocial_follow.last_antisocial_check_date'),
            'commission_dial' => trans('antisocial_follow.commission_dial'),
        ];
    }
}
