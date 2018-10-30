<?php

namespace App\Repositories;

interface MGeneralSearchRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $mGeneralId
     * @return mixed
     */
    public function findGeneralSearch($mGeneralId);

    /**
     * @param array $whereConditions
     * @param array $orwhereConditions
     * @return mixed
     */
    public function findGeneralSearchAuth($whereConditions, $orwhereConditions);

    /**
     * @return mixed
     */
    public function getLastInsertID();

    /**
     * @param integer $generalId
     * @return mixed
     */
    public function deleteGeneralSearch($generalId);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertGeneralSearch($data);

    /**
     * @param string $sql
     * @return mixed
     */
    public function runQueryText($sql);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateGeneralSearch($data);
}
