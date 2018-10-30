<?php

namespace App\Repositories\Eloquent;

use App\Models\AuctionGenre;
use App\Repositories\AuctionGenreRepositoryInterface;
use DB;
use Auth;

class AuctionGenreRepository extends SingleKeyModelRepository implements AuctionGenreRepositoryInterface
{
    /**
     * @var AuctionGenre
     */
    protected $model;

    /**
     * AuctionGenreRepository constructor.
     *
     * @param AuctionGenre $model
     */
    public function __construct(AuctionGenre $model)
    {
        $this->model = $model;
    }

    /**
     * get data by id
     *
     * @param  integer $genreId
     * @return array
     */
    public function getFirstByGenreId($genreId = null)
    {
        return $this->model->where('genre_id', '=', $genreId)->first();
    }

    /**
     * save auction genre
     *
     * @param  array $data
     * @return boolean
     */
    public function saveAuctionGenre($data)
    {
        try {
            if (empty($data['id'])) {
                unset($data['id']);
                $data['modified'] = date('Y-m-d H:i:s');
                $data['modified_user_id'] = Auth::user()->user_id;
                $data['created'] = date('Y-m-d H:i:s');
                $data['created_user_id'] = Auth::user()->user_id;
                $this->model->insert($data);
            } else {
                $data['modified'] = date('Y-m-d H:i:s');
                $data['modified_user_id'] = Auth::user()->user_id;
                $this->model->where('id', '=', $data['id'])->update($data);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
