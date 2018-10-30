<?php
namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenStatus;
use App\Repositories\CyzenStatusRepositoryInterface;

class CyzenStatusRepository extends SingleKeyModelRepository implements CyzenStatusRepositoryInterface
{
    /**
     * @var \App\Models\Cyzen\CyzenStatus $model
     */
    public $model;

    /**
     * CyzenHistoryRepository constructor.
     *
     * @param \App\Models\Cyzen\CyzenStatus $model
     */
    public function __construct(CyzenStatus $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed|void
     */
    public function getModel()
    {
        //  Implement getModel() method.
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function saveData($data)
    {
        $status = $this->model->where(['group_id' => $data['group_id'], 'status_id' => $data['status_id']])->first();

        if (!$status) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $status->$key = $value;
        }

        return $status->save();
    }

    /**
     * @param $key
     * @param $model
     * @return mixed
     */
    public function checkForeignKey($key, $model)
    {
        $hasDataRelation = $model->find($key);

        return ($hasDataRelation) ? true : false;
    }
}
