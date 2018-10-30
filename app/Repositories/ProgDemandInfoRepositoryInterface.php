<?php

namespace App\Repositories;

interface ProgDemandInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * update data
     *
     * @param  integer $id   record id
     * @param  array   $data data
     * @return boolean
     */
    public function update($id, $data);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertTMP($data);

    /**
     * @param array $arrayCommissionId
     * @param integer $fileId
     * @return mixed
     */
    public function delProgDemand($arrayCommissionId, $fileId);

    /**
     * @param array $commissionInfo
     * @param integer $fileId
     * @return mixed
     */
    public function findByMulticondition($commissionInfo, $fileId);

    /**
     * @param integer $idOrFileId
     * @param integer $field
     * @return mixed
     */
    public function getCSVData($idOrFileId, $field);

    /**
     * @param integer $progDemandId
     * @return mixed
     */
    public function findWithCommissionById($progDemandId);

    /**
     * @param array $ids
     * @return mixed
     */
    public function findByIds($ids);

    /**
     * @param integer $progCorpId
     * @return mixed
     */
    public function findByProgCorpId($progCorpId);

    /**
     * @param array $saveData
     * @return mixed
     */
    public function updateProgDemandInfo($saveData);
}
