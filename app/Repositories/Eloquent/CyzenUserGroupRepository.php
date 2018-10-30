<?php

namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenUserGroup;
use App\Repositories\CyzenUserGroupRepositoryInterface;

class CyzenUserGroupRepository extends SingleKeyModelRepository implements CyzenUserGroupRepositoryInterface
{
    /**
     * @var CyzenUserGroups
     */
    protected $model;

    /**
     * CyzenUserGroupsRepository constructor.
     *
     * @param CyzenUserGroup $model
     */
    public function __construct(CyzenUserGroup $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|CyzenUserGroup|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CyzenUserGroup(); // Change the autogenerated stub
    }

    /**
     * @param $listUserGroup
     * @return mixed|void
     */
    public function restructureUserGroup($listUserGroup)
    {
        $UserGroup = [];
        $now = date('Y-m-d h:i:s');
        foreach ($listUserGroup as $data) {
            foreach ($data['groups'] as $item) {
                $dataUserGroup = [
                    'user_id' => $data['user_id'],
                    'group_id' => $item['group_id'],
                    'is_group_owner' => $item['is_group_owner'],
                    "created_at" => $data["created_at"],
                    "updated_at" => $data["updated_at"],
                    "crawler_time" => $now
                ];
                array_push($UserGroup, $dataUserGroup);
            }
        }
        return $UserGroup;
    }

    /**
     * @return mixed
     */
    public function deleteAll()
    {
        return $this->model->truncate();
    }
}
