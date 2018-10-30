<?php

namespace App\Repositories;

interface MUserRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function dropDownUser();

    /**
     * @return mixed
     */
    public function getListUserNotAffiliation();

    /**
     * @return mixed
     */
    public function getUser();

    /**
     * @param array $data
     * @return mixed
     */
    public function saveUser($data);

    /**
     * @param int  $pageNumber
     * @param string $auth
     * @param string $username
     * @param string $corpName
     * @return mixed
     */
    public function getUserForSearch($pageNumber = 100, $auth = null, $username = null, $corpName = null);

    /**
     * @return mixed
     */
    public function dropDownUserList();

    /**
     * @param integer $id
     * @return mixed
     */
    public function getUserById($id);

    /**
     * @param integer $userId
     * @param array $data
     * @return mixed
     */
    public function updateUser($userId, $data);

    /**
     * @return mixed
     */
    public function getListUserForDropDown();

    /**
     * update last_login_date
     *
     * @param  string $userId
     * @param  array  $data
     * @return mixed
     */
    public function updateLastLogin($userId, $data);

    /**
     * @param integer $affId
     * @return mixed
     */
    public function getUserByAffiliationId($affId);

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserByUserId($userId);

    /**
     * @param $data
     * @return mixed
     */
    public function getUserByUserIdAndPassword($data);
}
