<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MAddress1
 *
 * @property string|null $address1_cd
 * @property string|null $address1
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAddress1 whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAddress1 whereAddress1Cd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MAddress1 whereId($value)
 * @mixin \Eloquent
 */
class MAddress1 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_address1';
}
