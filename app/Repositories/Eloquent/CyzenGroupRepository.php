<?php
namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenGroup;
use App\Repositories\CyzenGroupRepositoryInterface;

class CyzenGroupRepository extends SingleKeyModelRepository implements CyzenGroupRepositoryInterface
{
    /**
     * @var \App\Models\Cyzen\CyzenGroup $model
     */
    public $model;

    /**
     * CyzenGroupRepository constructor.
     *
     * @param \App\Models\Cyzen\CyzenGroup $model
     */
    public function __construct(CyzenGroup $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function saveData($data)
    {
        $group = $this->model->find($data['id']);

        if (!$group) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $group->$key = $value;
        }
        return $group->save();
    }

    /**
     * @return mixed
     */
    public function getAllId()
    {
        $group = $this->model->select('id')->get()->toArray();
        $groupId= [];
        foreach ($group as $data) {
            array_push($groupId, $data['id']);
        }
        return $groupId;
    }

    /**
     * @return \App\Models\Cyzen\CyzenGroup|mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
