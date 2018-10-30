<?php

namespace App\Repositories\Eloquent;

use App\Models\SelectGenrePrefecture;
use App\Repositories\SelectionGenrePrefectureRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SelectionGenrePrefectureRepository extends SingleKeyModelRepository implements SelectionGenrePrefectureRepositoryInterface
{
    /**
     * @var SelectGenrePrefecture
     */
    protected $model;

    /**
     * SelectionGenrePrefectureRepository constructor.
     *
     * @param SelectGenrePrefecture $model
     */
    public function __construct(SelectGenrePrefecture $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|SelectGenrePrefecture|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new SelectGenrePrefecture();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * @param integer $id
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getSelectGenrePrefecture($id)
    {
        $results = $this->model->where('genre_id', $id)->get();
        return $results;
    }

    /**
     * @param integer $id
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteBaseOnGenreId($id)
    {
        $result = $this->model->where('genre_id', $id)->delete();
        return $result;
    }

    /**
     * @param array $data
     * @return \App\Models\Base|SelectGenrePrefecture|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function saveNewSelectionGenrePrefecture($data)
    {
        $result = $this->getBlankModel();
        $result->genre_id = $data['genre_id'];
        $result->prefecture_cd = $data['prefecture_cd'];
        $result->selection_type = $data['selection_type'];
        $result->business_trip_amount = $data['business_trip_amount'];
        $result->auction_fee = $data['auction_fee'];
        $result->created_user_id = Auth::user()->user_id;
        $result->created = date('Y-m-d H:i:s');
        $result->modified_user_id = Auth::user()->user_id;
        $result->modified = date('Y-m-d H:i:s');
        $result->save();

        return $result;
    }
}
