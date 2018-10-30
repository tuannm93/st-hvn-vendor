<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CorpRegisteredSchedule extends Model
{
    //
    /**
     * @var $table
     */
    protected $table = 'corp_registered_schedule';

    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('corp_id', '=', $this->getAttribute('corp_id'))
            ->where('category_id', '=', $this->getAttribute('category_id'))
            ->where('genre_id', '=', $this->getAttribute('genre_id'));
        return $query;
    }
}
