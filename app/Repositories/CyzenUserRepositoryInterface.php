<?php

namespace App\Repositories;

interface CyzenUserRepositoryInterface extends CyzenModelRepositoryInterface
{
    /**
     * @param $users
     * @param $get
     * @return mixed
     */
    public function synchronizedUser($users, $get);

    /**
     * @param $data
     * @return mixed
     */
    public function updateById($data);

    /**
     * @param array $users
     * @return mixed
     */
    public function allUser(array $users);

    /**
     * @param array $allUserId
     * @return mixed
     */
    public function getUsersUpdate($allUserId);

    /**
     * @return mixed
     */
    public function getAllId();
}
