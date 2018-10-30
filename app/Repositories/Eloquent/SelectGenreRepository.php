<?php

namespace App\Repositories\Eloquent;

use App\Models\SelectGenres;

use App\Repositories\SelectGenreRepositoryInterface;

class SelectGenreRepository extends SingleKeyModelRepository implements SelectGenreRepositoryInterface
{
    /**
     * @var SelectGenres
     */
    protected $model;

    /**
     * SelectGenreRepository constructor.
     *
     * @param SelectGenres $model
     */
    public function __construct(SelectGenres $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|SelectGenres|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new SelectGenres();
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
     * @param array $data
     * @return \App\Models\Base|bool|void
     */
    public function save($data)
    {
    }

    /**
     * @param integer $id
     * @return bool|null|void
     */
    public function delete($id)
    {
    }

    /**
     * @param integer $genreId
     * @return mixed
     */
    public function findByGenreId($genreId)
    {
        return $this->model->whereGenreId($genreId)->first();
    }
}
