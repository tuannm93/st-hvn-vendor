<?php

namespace App\Repositories\Eloquent;

use App\Models\SelectGenrePrefecture;

use App\Repositories\SelectGenrePrefectureRepositoryInterface;

class SelectGenrePrefectureRepository extends SingleKeyModelRepository implements SelectGenrePrefectureRepositoryInterface
{
    /**
     * @var SelectGenrePrefecture
     */
    protected $model;

    /**
     * SelectGenrePrefectureRepository constructor.
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
     * @param \App\Models\Base $data
     * @return \App\Models\Base|bool|void
     */
    public function save($data)
    {
    }

    /**
     * @param \App\Models\Base $id
     * @return bool|null|void
     */
    public function delete($id)
    {
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getByGenreIdAndPrefectureCd($data = [])
    {
        return $this->model->where('genre_id', $data['genre_id'])->where('prefecture_cd', $data['address1'])->first();
    }
}
