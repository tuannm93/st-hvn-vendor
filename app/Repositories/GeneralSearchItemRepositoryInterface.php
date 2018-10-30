<?php

namespace App\Repositories;

interface GeneralSearchItemRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $mGeneralId
     * @return bool
     */
    public function deleteById($mGeneralId);

    /**
     * @param integer $mGeneralId
     * @return mixed
     */
    public function findGeneralSearchCondition($mGeneralId);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertGeneralSearch($data);
}
