<?php

namespace App\Repositories;

interface ProgAddDemandInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $pCorpId
     * @return mixed
     */
    public function getDataByProgCorpId($pCorpId);

    /**
     * @param integer $progAddId
     * @param array $data
     * @return mixed
     */
    public function updateById($progAddId, $data);

    /**
     * @param integer $idOrFileId
     * @param string $field
     * @return mixed
     */
    public function getCSVData($idOrFileId, $field);

    /**
     * @param array $ids
     * @return mixed
     */
    public function findByIds($ids);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertGetId($data);
}
