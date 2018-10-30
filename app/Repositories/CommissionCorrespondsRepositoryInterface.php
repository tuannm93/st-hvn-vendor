<?php

namespace App\Repositories;

interface CommissionCorrespondsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function save($data);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findByIdWithUserName($id);
}
