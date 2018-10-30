<?php
namespace App\Models\Cyzen;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cyzen\CyzenUserGroup
 *
 * @property string $user_id
 * @property string $group_id
 * @property bool $is_group_owner
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $crawler_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUserGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUserGroup whereIsGroupOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUserGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cyzen\CyzenUserGroup whereUserId($value)
 * @mixin \Eloquent
 */
class CyzenUserGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cyzen_user_groups';

    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('group_id', '=', $this->getAttribute('group_id'));
        return $query;
    }
}
