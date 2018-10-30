<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AutoCallRepositoryInterface;
use App\Models\AutoCallItem;

use Illuminate\Support\Facades\Auth;

class AutoCallRepository extends SingleKeyModelRepository implements AutoCallRepositoryInterface
{
    /**
     * @var AutoCallItem
     */
    protected $model;

    /**
     * AutoCallRepository constructor.
     *
     * @param AutoCallItem $model
     */
    public function __construct(AutoCallItem $model)
    {
        $this->model = $model;
    }

    /**
     * get first record
     *
     * @return object
     */
    public function getItem()
    {
        return $this->model->first();
    }

    /**
     * save data
     * create new record if model is null
     * update record if model exists
     *
     * @param  array $data
     * @return \App\Models\Base|boolean
     */
    public function save($data)
    {
        $userId = Auth::user()->user_id;

        // insert
        if (!isset($data['id'])) {
            $this->model->created_user_id = $userId;
            $this->model->created = date(config('constant.FullDateTimeFormat'), time());
        } else { // update
            $this->model = $this->model->where('id', $data['id'])->first();
            $this->model->modified_user_id = $userId;
            $this->model->modified = date(config('constant.FullDateTimeFormat'), time());
        }

        $this->model->asap = (trim($data['asap']) === '') ? null : intval($data['asap']);
        $this->model->immediately = (trim($data['immediately']) === '') ? null : intval($data['immediately']);
        $this->model->normal = (trim($data['normal']) === '') ? null : intval($data['normal']);

        return $this->model->save();
    }
}
