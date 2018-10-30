<?php

namespace App\Repositories\Eloquent;

use App\Repositories\DeviceInfoRepositoryInterface;
use App\Models\DeviceInfo;

class DeviceInfoRepository extends SingleKeyModelRepository implements DeviceInfoRepositoryInterface
{
    /**
     * @var DeviceInfo
     */
    protected $model;

    /**
     * DeviceInfoRepository constructor.
     *
     * @param DeviceInfo $model
     */
    public function __construct(
        DeviceInfo $model
    ) {
        $this->model = $model;
    }

    /**
     * @param string $deviceToken
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findByDeviceToken($deviceToken)
    {
        $fields = $this->getAllTableFieldsByAlias('device_infos', 'DeviceInfo');
        $result = $this->model->from('device_infos AS DeviceInfo')
            ->where('DeviceInfo.device_token', $deviceToken)
            ->where('DeviceInfo.del_flg', '!=', 1)
            ->select($fields)
            ->first();

        return $result;
    }

    /**
     * @param \App\Models\Base $data
     * @return \App\Models\Base|DeviceInfo|bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function save($data)
    {
        $deviceInfo = $this->model;

        if (isset($data['id'])) {
            $deviceInfo = $this->model->where('id', $data['id'])->first();
        }

        if (isset($data['push_cnt'])) {
            $deviceInfo->push_cnt = $data['push_cnt'];
        }

        if (isset($data['user_id'])) {
            $deviceInfo->user_id = $data['user_id'];
        }

        if (isset($data['device_token'])) {
            $deviceInfo->device_token = $data['device_token'];
        }

        if (isset($data['endpoint'])) {
            $deviceInfo->endpoint = $data['endpoint'];
        }

        if (isset($data['os_type'])) {
            $deviceInfo->os_type = $data['os_type'];
        }

        if (!isset($data['id'])) {
            $deviceInfo->created = date('Y-m-d H:i:s');
        }

        $deviceInfo->modified = date('Y-m-d H:i:s');

        $deviceInfo->save();

        return $deviceInfo;
    }

    /**
     * @param integer $userId
     * @return \Illuminate\Support\Collection
     */
    public function getDeviceInfoByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->where('del_flg', '!=', 1)->get();
    }

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateById($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function deleteDeviceByUserId($userId, $osType) {
        return $this->model->where('user_id', $userId)->where('os_type', $osType)->update(['del_flg' => 1, 'modified' => date('Y-m-d H:i:s')]);
    }
}
