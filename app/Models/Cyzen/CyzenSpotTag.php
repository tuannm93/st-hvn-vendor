<?php

namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenSpotTag
 *
 * @property string $spot_tag_id
 * @property string $group_id
 * @property string $spot_tag_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpotTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpotTag whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpotTag whereSpotTagName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenSpotTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CyzenSpotTag extends Model
{
    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string $keyType
     */
    public $keyType = 'string';

    /** @var string */
    public $table = 'cyzen_spot_tags';

    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('spot_tag_id', '=', $this->getAttribute('spot_tag_id'))
            ->where('group_id', '=', $this->getAttribute('group_id'));
        return $query;
    }
}
