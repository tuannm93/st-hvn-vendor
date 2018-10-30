<?php

namespace App\Repositories;

interface MCommissionTypeRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getList();

    /**
     * @return mixed
     */
    public function getListCommissionTypeName();
}
