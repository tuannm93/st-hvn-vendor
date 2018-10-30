<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FiltersConditions extends Model
{
    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('filter_id', '=', $this->getAttribute('filter_id'))
            ->where('condition_cd', '=', $this->getAttribute('condition_cd'));
        return $query;
    }
}
