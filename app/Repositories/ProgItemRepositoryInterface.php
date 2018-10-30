<?php

namespace App\Repositories;

interface ProgItemRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);
}
