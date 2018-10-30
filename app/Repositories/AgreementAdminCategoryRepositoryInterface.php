<?php

namespace App\Repositories;

interface AgreementAdminCategoryRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllJoinedMCategory();

    /**
     * @param integer $id
     * @return mixed
     */
    public function getById($id);
}
