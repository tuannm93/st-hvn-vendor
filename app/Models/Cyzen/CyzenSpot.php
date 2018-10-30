<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenSpot
 *
 * @property string $id
 * @property string|null $spot_code
 * @property string|null $spot_name_kana
 * @property string|null $spot_name
 * @property int|null $zip_code
 * @property int|null $tel
 * @property int|null $fax
 * @property string $address
 * @property string|null $valid_from
 * @property string|null $valid_to
 * @property string|null $url
 * @property string|null $comment
 * @property string $create_user_id
 * @property point $spot_location
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereCreateUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereSpotCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereSpotName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereSpotNameKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpot whereZipCode($value)
 * @mixin \Eloquent
 */
class CyzenSpot extends Model
{
    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $keyType
     */
    public $keyType = 'string';
}
