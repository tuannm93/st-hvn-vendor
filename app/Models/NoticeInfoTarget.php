<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NoticeInfoTarget
 *
 * @property int $id ID
 * @property int|null $notice_info_id 記事ID
 * @property int|null $corp_id 加盟店ID
 * @property string|null $modified_user_id 更新者ID
 * @property \Carbon\Carbon|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property \Carbon\Carbon|null $created 作成日時
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereCorpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeInfoTarget whereNoticeInfoId($value)
 * @mixin \Eloquent
 */
class NoticeInfoTarget extends Model
{
    const CREATED_AT = "created";
    const UPDATED_AT = "modified";

    /**
     * @var string
     */
    protected $table = 'notice_info_targets';
    /**
     * @var array
     */
    protected $guarded = [];
}
