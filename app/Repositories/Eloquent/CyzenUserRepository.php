<?php

namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenUser;
use App\Repositories\CyzenUserRepositoryInterface;

class CyzenUserRepository extends SingleKeyModelRepository implements CyzenUserRepositoryInterface
{
    /**
     * @var CyzenUser $model
     */
    public $model;

    /**
     * CyzenUsersRepository constructor.
     *
     * @param CyzenUser $model
     */
    public function __construct(CyzenUser $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Cyzen\CyzenUser|mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \App\Models\Base|CyzenUser|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CyzenUser();
    }

    /**
     * @param $dataAPI
     * @param string $get
     * @return array|mixed
     */
    public function synchronizedUser($dataAPI, $get = 'UserGroups')
    {
        $users = [];
        $groups = [];
        for ($i = 0; $i < count($dataAPI['users']); $i++) {
            $groupArray = [
                "user_id" => $dataAPI['users'][$i]['user_id'],
                "created_at" => gmt_to_jst_time($dataAPI['users'][$i]["created_at"]),
                "updated_at" => gmt_to_jst_time($dataAPI['users'][$i]["updated_at"]),
                "groups" => $dataAPI['users'][$i]['groups']
            ];
            array_push($groups, $groupArray);
            array_pop($dataAPI['users'][$i]);
            array_push($users, $dataAPI['users'][$i]);
        }
        if ($get === 'userGroups') {
            return $groups;
        }
        return $users;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUsersUpdate($id)
    {
        $UserUpdate = $this->model->select('id')->whereIn('id', $id)->get()->toArray();
        return $UserUpdate;
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function updateById($data)
    {
        $now = date('Y-m-d h:i:s');
        foreach ($data as $item) {
            $updateUser = [
                "user_login_id" => $item["user_login_id"],
                "user_code" => $item["user_code"],
                "user_name" => $item["user_name"],
                "app_version" => $item["app_version"],
                "device" => $item["device"],
                "os_version" => $item["os_version"],
                "created_at" => $item["created_at"],
                "updated_at" => $item["updated_at"],
                'crawler_time' => $now
            ];
            $user = $this->model->find($item['id']);
            foreach ($updateUser as $key => $value) {
                $user->$key = $value;
            }
            $user->save();
        }
    }

    /**
     * @param array $users
     * @return array|mixed
     */
    public function allUser(array $users)
    {

        $users = array_map(function ($user) {
            $now = date('Y-m-d H:i:s');
            return [
                'id' => $user['user_id'],
                'user_login_id' => $user['user_login_id'],
                'user_code' => $user['user_code'],
                'user_name' => $user['user_name'],
                'app_version' => $user['app_version'],
                'device' => $user['device'],
                'os_version' => $user['os_version'],
                'created_at' => gmt_to_jst_time($user['created_at']),
                'updated_at' => gmt_to_jst_time($user['updated_at']),
                'crawler_time' => $now
            ];
        }, $users);
        return $users;
    }

    /**
     * @return array
     */
    public function getAllId()
    {
        $user = $this->model->select('id')->get()->toArray();
        $userId= [];
        foreach ($user as $data) {
            array_push($userId, $data['id']);
        }
        return $userId;
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function saveData($data)
    {
        $user = $this->model->find($data['id']);

        if (!$user) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        return $user->save();
    }
}
