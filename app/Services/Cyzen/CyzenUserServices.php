<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenGroupRepositoryInterface;
use App\Repositories\CyzenUserGroupRepositoryInterface;
use App\Repositories\CyzenUserRepositoryInterface;
use Exception;
use Monolog\Logger;

class CyzenUserServices extends BaseCyzenServices
{
    /**
     * @var CyzenUserRepositoryInterface $cyzenUser
     */
    protected $cyzenUser;

    /**
     * @var CyzenUserGroupRepositoryInterface $cyzenUserGroup
     */
    protected $cyzenUserGroup;

    /**
     * @var CyzenGroupRepositoryInterface $cyzenGroup
     */
    protected $cyzenGroup;

    /**
     * @var \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;

    /**
     * @var CyzenUserGroupServices $cyzenUserGroupServices
     */
    protected $cyzenUserGroupServices;
    /**
     * @var string $path
     */
    private $path = '/webapi/v0/users';

    /**
     * CyzenUserServices constructor.
     *
     * @param \App\Repositories\CyzenUserRepositoryInterface $cyzenUser
     * @param \App\Repositories\CyzenUserGroupRepositoryInterface $cyzenUserGroup
     * @param \App\Repositories\CyzenGroupRepositoryInterface $cyzenGroup
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     * @param \App\Services\Cyzen\CyzenUserGroupServices $cyzenUserGroupServices
     * @throws \Exception
     */
    public function __construct(
        CyzenUserRepositoryInterface $cyzenUser,
        CyzenUserGroupRepositoryInterface $cyzenUserGroup,
        CyzenGroupRepositoryInterface $cyzenGroup,
        CyzenGroupService $cyzenGroupService,
        CyzenUserGroupServices $cyzenUserGroupServices
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_USER);
        $this->cyzenUser = $cyzenUser;
        $this->cyzenUserGroup = $cyzenUserGroup;
        $this->cyzenGroup = $cyzenGroup;
        $this->cyzenGroupService = $cyzenGroupService;
        $this->cyzenUserGroupServices = $cyzenUserGroupServices;
    }

    /**
     * @param string $nextId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle($nextId = '')
    {
        // call Api
        $this->logger->log(Logger::INFO, '==========START CRON JOB GET USERS==========');
        $query = ($nextId !== '') ? ['next_user_id' => $nextId] : [];
        $cyzenUser = $this->get($this->path, $query);
        $users = $this->cyzenUser->synchronizedUser($cyzenUser, 'users');
        $users = $this->cyzenUser->allUser($users);
        $userGroup = $this->cyzenUser->synchronizedUser($cyzenUser, 'userGroups');
        $userGroup = $this->cyzenUserGroup->restructureUserGroup($userGroup);
        $allUserId = [];
        foreach ($users as $user) {
            array_push($allUserId, $user['id']);
        }
        //get all id exist database
        $exist = $this->cyzenUser->getUsersUpdate($allUserId);
        $existId = [];
        foreach ($exist as $data) {
            array_push($existId, $data['id']);
        }
        $insertUsers = [];
        $updateUsers = [];
        // set data update and insert
        foreach ($users as $data) {
            if (in_array($data['id'], $existId)) {
                array_push($updateUsers, $data);
            } else {
                array_push($insertUsers, $data);
            }
        }
        // update user
        if ($updateUsers != null) {
            try {
                $this->cyzenUser->updateById($updateUsers);
            } catch (Exception $ex) {
                $this->logger->log(Logger::ERROR, $ex->getMessage() . json_encode($query));
            }
        }
        // insert user
        if ($insertUsers != null) {
            try {
                $this->cyzenUser->insert($insertUsers);
            } catch (Exception $ex) {
                $this->logger->log(Logger::ERROR, $ex->getMessage() . json_encode($query));
            }
        }
        // insert userGroup
        try {
            $this->cyzenUserGroupServices->userGroupHandle($userGroup);
        } catch (Exception $ex) {
            $this->logger->log(Logger::ERROR, $ex->getMessage() . json_encode($query));
        }
        sleep(1);
        if (isset($cyzenUser['next_user_id'])) {
            return $this->handle($cyzenUser['next_user_id']);
        }
        $this->logger->log(Logger::INFO, '==========END CRON JOB GET USERS==========');
        return null;
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($id)
    {
        //call api
        $now = date('Y-m-d H:i:s');
        $query = ['user_id' => $id];
        $rawData = $this->get($this->path, $query);
        if ($rawData) {
            $rawData['users'][0]['crawler_time'] = $now;
            $importData = $this->initParams($rawData['users'][0]);
            return $this->processingData($importData, $this->cyzenUser);
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function initParams($data)
    {
        return [
            'id' => $data['user_id'],
            'user_login_id' => $data['user_login_id'],
            'user_code' => $data['user_code'],
            'user_name' => $data['user_name'],
            'app_version' => $data['app_version'],
            'device' => $data['device'],
            'os_version' => $data['os_version'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'crawler_time' => $data['crawler_time']
        ];
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->cyzenUser->getModel();
    }
}
