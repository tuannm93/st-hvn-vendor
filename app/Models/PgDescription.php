<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PgDescription
 *
 * @mixin \Eloquent
 */
class PgDescription extends Model
{
    /**
     * @var string
     */
    protected $table = 'pg_description';
    /**
     * @var array
     */
    protected $guarded = [];
}
