<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AutoSelectCorp
 *
 * @mixin \Eloquent
 */
class AutoSelectCorp extends Model
{
    /**
     * @var string
     */
    public $table = 'auto_select_corps';
    /**
     * @var array
     */
    public $guarded = ['id'];
}
