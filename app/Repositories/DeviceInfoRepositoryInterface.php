<?php

namespace App\Repositories;

interface DeviceInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param string $deviceToken
     * @return mixed
     */
    public function findByDeviceToken($deviceToken);

    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $userId
     * @return mixed
     */
    public function getDeviceInfoByUserId($userId);
}
