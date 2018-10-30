<?php

namespace App\Repositories\Eloquent;

use App\Models\Filters;
use App\Repositories\FiltersRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class FiltersRepository extends SingleKeyModelRepository implements FiltersRepositoryInterface
{
    /** @var Filters $model */
    protected $model;

    /**
     * FiltersRepository constructor.
     * @param Filters $filters
     */
    public function __construct(Filters $filters)
    {
        $this->model = $filters;
    }

    /**
     * @return \App\Models\Base|Filters|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new Filters();
    }

    /**
     * @param $jisCd
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getFiltersId($jisCd, $genreId, $categoryId)
    {
        $query = $this->model->select('id')
            ->where('jis_cd', '=', $jisCd)
            ->where('genre_id', '=', $genreId)
            ->where('category_id', '=', $categoryId)
            ->get()->toArray();
        if (empty($query)) {
            $query = $this->model->select('*')
                ->where(function ($whereJis) use ($jisCd) {
                    /** @var Builder $whereJis */
                    $whereJis->where('jis_cd', '=', $jisCd)
                        ->orWhere('is_all_jis_cd', '=', true);
                })
                ->where(function ($whereGenre) use ($genreId) {
                    /** @var Builder $whereGenre */
                    $whereGenre->where('genre_id', '=', $genreId)
                        ->orWhere('is_all_genre', '=', true);
                })
                ->where(function ($whereCategory) use ($categoryId) {
                    /** @var Builder $whereCategory */
                    $whereCategory->where('category_id', '=', $categoryId)
                        ->orWhere('is_all_category', '=', true);
                })
                ->orderBy('jis_cd', 'asc')
                ->orderBy('category_id', 'asc')
                ->orderBy('genre_id', 'asc')
                ->get()->toArray();
            return $query;
        }
        return $query;
    }
}
