<?php

namespace App\Repositories\Eloquent;

use App\Models\AuctionGenreArea;
use App\Repositories\AuctionGenreAreaRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionGenreAreaRepository extends SingleKeyModelRepository implements AuctionGenreAreaRepositoryInterface
{
    /**
     * @var AuctionGenreArea
     */
    protected $model;

    /**
     * AuctionGenreAreaRepository constructor.
     *
     * @param AuctionGenreArea $model
     */
    public function __construct(AuctionGenreArea $model)
    {
        $this->model = $model;
    }

    /**
     * get data by id
     *
     * @param  integer $genreId
     * @param integer $prefCd
     * @return Collection
     */
    public function getFirstByGenreIdAndPrefCd($genreId, $prefCd)
    {
        return $this->model->where(
            [
            ['genre_id', $genreId],
            ['prefecture_cd', $prefCd]
            ]
        )->first();
    }

    /**
     * insert or update function
     *
     * @param  array $data
     * @return boolean
     */
    public function saveData($data = null)
    {
        $date = Carbon::now();
        $userId = Auth::user()['user_id'];
        try {
            DB::beginTransaction();
            if (empty($data['id'])) {
                unset($data['id']);
                unset($data['_token']);
                $data['created'] = $date;
                $data['created_user_id'] = $userId;
                $data['modified'] = $date;
                $data['modified_user_id'] = $userId;
                $this->model->insert($data);
            } else {
                unset($data['_token']);
                $data['modified'] = $date;
                $data['modified_user_id'] = $userId;
                $this->model->where('id', $data['id'])->update($data);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
