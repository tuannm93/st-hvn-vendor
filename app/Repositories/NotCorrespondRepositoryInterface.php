<?php

namespace App\Repositories;

interface NotCorrespondRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function updateById($data);

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteMultiRecord($ids);

    /**
     * @return mixed
     */
    public function getFirstItem();

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findNotCorrespond($corpId);
}
