<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DemandCorrespond
 *
 * @property int $id ID
 * @property int $demand_id 案件ID
 * @property string|null $correspond_datetime 対応日時
 * @property string|null $responders 対応者
 * @property string|null $corresponding_contens 対応内容
 * @property string|null $modified_user_id 更新者ID
 * @property string|null $modified 更新日時
 * @property string|null $created_user_id 作成者ID
 * @property string|null $created 作成日時
 * @property-read \App\Models\MUser|null $MUser
 * @property-read false|string $correspond_date_time_format
 * @property-read false|string $user_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereCorrespondDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereCorrespondingContens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereDemandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereModifiedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DemandCorrespond whereResponders($value)
 * @mixin \Eloquent
 */
class DemandCorrespond extends Model
{
    //
    /**
     * @var string
     */
    protected $table = 'demand_corresponds';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function MUser()
    {
        return $this->belongsTo(MUser::class, 'responders', 'id');
    }

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return false|string
     * @des get user_name attribute
     */
    public function getUserNameAttribute()
    {
        $responder = $this->getAttribute('responders');

        return (!ctype_digit($responder) || !$this->MUser) ? $responder : $this->MUser->user_name;
    }

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return false|string
     * @des get correspond_date_time_format attribute
     */
    public function getCorrespondDateTimeFormatAttribute()
    {

        return dateTimeFormat($this->getAttribute('correspond_datetime'));
    }
}
