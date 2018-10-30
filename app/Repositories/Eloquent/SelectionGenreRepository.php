<?php

namespace App\Repositories\Eloquent;

use App\Models\SelectGenres;
use App\Repositories\SelectionGenreRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SelectionGenreRepository extends SingleKeyModelRepository implements SelectionGenreRepositoryInterface
{
    /**
     * @var SelectGenres
     */
    protected $model;

    /**
     * SelectionGenreRepository constructor.
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
     * Update or save selection genre
     *
     * @param array $data
     * @param  integer $id
     * @return \App\Models\Base|SelectGenres|boolean|\Illuminate\Database\Eloquent\Model|mixed|static
     */
    public function updateOrSave($data, $id = null)
    {
        if (empty($id)) {
            $result = $this->getBlankModel();
            $result->created_user_id = Auth::user()->user_id;
            $result->created = date('Y-m-d H:i:s');
        } else {
            $result = $this->model->find($id);
            if (empty($result)) {
                return false;
            } elseif ($result->select_type == $data['select_type']) {
                return true;
            }
        }

        $result->genre_id = $data['genre_id'];
        $result->select_type = $data['select_type'];
        $result->modified_user_id = Auth::user()->user_id;
        $result->modified = date('Y-m-d H:i:s');
        $result->save();

        return $result;
    }

    /**
     * @param integer $id
     * @param string $field
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findBaseOnGenreId($id, $field = null)
    {
        $result = $this->model->where('genre_id', $id)->first();
        if ($result) {
            $data = isset($field) ? $result->$field : $result;
            return $data;
        } else {
            return false;
        }
    }
}
