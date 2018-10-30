<?php

namespace App\Repositories;

interface NotCorrespondItemRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findBy($corpId);

    /**
     * @return mixed
     */
    public function findFirst();
}
