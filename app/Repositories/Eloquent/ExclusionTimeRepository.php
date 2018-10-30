<?php

namespace App\Repositories\Eloquent;

use App\Models\ExclusionTime;
use App\Repositories\ExclusionTimeRepositoryInterface;

class ExclusionTimeRepository extends SingleKeyModelRepository implements ExclusionTimeRepositoryInterface
{
    /**
     * @var ExclusionTime
     */
    protected $model;

    const PATTERN = 'パターン';

    /**
     * ExclusionTimeRepository constructor.
     *
     * @param ExclusionTime $model
     */
    public function __construct(ExclusionTime $model)
    {
        $this->model = $model;
    }

    /**
     * get list data ExclusionTime
     *
     * @return array
     */
    public function getList()
    {
        $lists = $this->model->select('exclusion_times.pattern')->orderBy('exclusion_times.pattern', 'asc')
            ->groupBy('pattern')->get()->toarray();
        $results[''] = trans('common.none');
        foreach ($lists as $list) {
            $results[$list['pattern']] =  self::PATTERN.$list['pattern'];
        }
        return $results;
    }

    /**
     * get by pattern
     *
     * @param  integer $pattern
     * @return array
     */
    public function findByPattern($pattern = null)
    {
        return $this->model->select('id', 'pattern', 'exclusion_time_from', 'exclusion_time_to', 'exclusion_day')->where('pattern', '=', $pattern)
            ->where(
                function ($query) {
                    $query->whereNotNull('exclusion_time_from')
                        ->orWhereNotNull('exclusion_time_to');
                }
            )->orderBy('modified', 'desc')
            ->first();
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getExclusionTime()
    {
        $lists = $this->model->select('id', 'exclusion_time_from', 'exclusion_time_to', 'exclusion_time_to', 'exclusion_day', 'pattern')
            ->orderBy('pattern', 'asc')
            ->get();
        return $lists;
    }

    /**
     * @param integer $id
     * @param array $data
     */
    public function updateExclusion($id, $data)
    {
        $this->model->where('id', $id)->update($data);
    }

    /**
     * @param integer $genreId
     * @param integer $prefectureCd
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getData($genreId, $prefectureCd)
    {
        $result = $this->model->join('auction_genre_areas', function($join) use ($genreId, $prefectureCd){
            $join->on('auction_genre_areas.exclusion_pattern', '=', 'exclusion_times.pattern')
                    ->where('auction_genre_areas.genre_id', '=', $genreId)
                    ->where('auction_genre_areas.prefecture_cd', '=', $prefectureCd);
        })->select('exclusion_times.*')->first();
        if (!empty($result)) {
            return $result->toArray();
        }

        $result = $this->model->join('auction_genres', function($join) use ($genreId){
            $join->on('auction_genres.exclusion_pattern', '=', 'exclusion_times.pattern')
                    ->where('auction_genres.genre_id', '=', $genreId);
        })->select('exclusion_times.*')->first();
        if (!empty($result)) {
            return $result->toArray();
        }
        return [];
    }
}
