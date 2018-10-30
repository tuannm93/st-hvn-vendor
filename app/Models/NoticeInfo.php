<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NoticeInfo
 *
 * @property int $id
 * @property string $info_title
 * @property string|null $info_contents
 * @property int|null $corp_commission_type
 * @property int $del_flg
 * @property string|null $modified_user_id
 * @property \Carbon\Carbon|null $modified
 * @property string|null $created_user_id
 * @property \Carbon\Carbon|null $created
 * @property bool $is_target_selected 加盟店の指定あり
 * @property string|null $choices 選択肢
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereChoices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereCorpCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereInfoContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereInfoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereIsTargetSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfo whereModifiedUserId($value)
 * @mixin \Eloquent
 */
class NoticeInfo extends Model
{
    const CREATED_AT = "created";
    const UPDATED_AT = "modified";

    /**
     * @var string
     */
    protected $table = 'notice_infos';
    /**
     * @var array
     */
    protected $guarded = [];
}
