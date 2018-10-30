<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandExtendInfo extends Model
{

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var null $primaryKey
     */
    protected $primaryKey = null;

    /**
     * @var bool $incrementing
     */
    public $incrementing = false;

    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];
}
