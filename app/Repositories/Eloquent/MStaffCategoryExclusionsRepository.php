<?php

namespace App\Repositories\Eloquent;

use App\Models\MStaffCategoryExclusions;
use App\Repositories\MStaffCategoryExclusionsRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class MStaffCategoryExclusionsRepository extends SingleKeyModelRepository implements MStaffCategoryExclusionsRepositoryInterface
{
    /** @var MStaffCategoryExclusions */
    protected $model;

    /**
     * MStaffCategoryExclusionsRepository constructor.
     * @param MStaffCategoryExclusions $staffCategoryExclusions
     */
    public function __construct(MStaffCategoryExclusions $staffCategoryExclusions)
    {
        $this->model = $staffCategoryExclusions;
    }

    /**
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getListStaffExclude($jisCd, $genreId, $categoryId)
    {
        $query = $this->model->select('user_id')
            ->where('genre_id', '=', $genreId)
            ->where('category_id', '=', $categoryId)
            ->where(function ($where) use ($jisCd) {
                /** @var Builder $where */
                $where->where('jis_cd', '=', $jisCd)
                    ->orWhere('jis_cd', '=', 0);
            })->pluck('user_id')->toArray();
        return $query;
    }
}
