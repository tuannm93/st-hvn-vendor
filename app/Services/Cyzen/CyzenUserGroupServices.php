<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenGroupRepositoryInterface;
use App\Repositories\CyzenUserGroupRepositoryInterface;

class CyzenUserGroupServices extends BaseCyzenServices
{

    /**
     * @var CyzenGroupService $cyzenGroupService
     */
    protected $cyzenGroupService;

    /**
     * @var CyzenGroupRepositoryInterface $cyzenGroup
     */
    protected $cyzenGroup;

    /**
     * @var CyzenUserGroupRepositoryInterface $cyzenUserGroup
     */
    protected $cyzenUserGroup;

    /**
     * CyzenUserGroupServices constructor.
     *
     * @param \App\Services\Cyzen\CyzenGroupService $cyzenGroupService
     * @param CyzenGroupRepositoryInterface $cyzenGroup
     * @param \App\Repositories\CyzenUserGroupRepositoryInterface $cyzenUserGroup
     * @throws \Exception
     */
    public function __construct(
        CyzenGroupService $cyzenGroupService,
        CyzenGroupRepositoryInterface $cyzenGroup,
        CyzenUserGroupRepositoryInterface $cyzenUserGroup
    ) {
        parent::__construct(BaseCyzenServices::LOG_PATH_SCHEDULE);
        $this->cyzenGroupService = $cyzenGroupService;
        $this->cyzenGroup = $cyzenGroup;
        $this->cyzenUserGroup = $cyzenUserGroup;
    }

    /**
     * @param $userGroup
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userGroupHandle($userGroup)
    {
        $groupId = [];
        foreach ($userGroup as $data) {
            array_push($groupId, $data['group_id']);
        }
        $groupId = array_unique($groupId);
        $allGroupId = $this->cyzenGroup->getAllId();
        $groupIdInsert = array_diff($groupId, $allGroupId);
        if ($groupIdInsert != null) {
            foreach ($groupIdInsert as $id) {
                $this->cyzenGroupService->getById($id);
            }
        }
        return $this->cyzenUserGroup->insert($userGroup);
    }

    /**
     * @return mixed
     */
    public function clearUserGroup()
    {
        return $this->cyzenUserGroup->deleteAll();
    }
}
