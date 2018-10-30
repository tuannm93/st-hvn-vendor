<?php

namespace App\Repositories;

interface CyzenUserGroupRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param $groups
     * @return mixed
     */
    public function restructureUserGroup($groups);

    /**
     * @return mixed
     */
    public function deleteAll();
}
