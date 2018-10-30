<?php

namespace App\Repositories;

interface MAnswerRepositoryInterface
{
    /**
     * @param integer $id
     * @param bool $toArray
     * @return mixed
     */
    public function dropDownAnswer($id, $toArray = false);
}
