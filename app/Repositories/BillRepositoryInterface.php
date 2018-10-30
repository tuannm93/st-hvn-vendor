<?php

namespace App\Repositories;

interface BillRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel();

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return mixed
     */
    public function getDataByCondition($fromDate, $toDate);

    /**
     * create bill_infos
     *
     * @param array $data
     * @return mixed
     */
    public function insertData($data);

    /**
     * search bill_info data by conditions
     * @param array $data
     * @return mixed
     */
    public function searchByConditions($data);

    /**
     * find modified column
     *
     * @param integer $id
     * @return mixed
     */
    public function findModified($id);

    /**
     * update record
     *
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateRecord($id, $data);

    /**
     * get bill_info lists
     *
     * @param array $ids
     * @return mixed
     */
    public function getDownloadList($ids);

    /**
     * get bill info data
     *
     * @param array $ids
     * @param integer $mCorpId
     * @param integer $billStatus
     * @return mixed
     */
    public function getPastIssueList($ids, $mCorpId, $billStatus);

    /**
     * @param integer $auctionId
     * @return mixed
     */
    public function findByAuctionId($auctionId);

    /**
     * @param integer $billId
     * @return mixed
     */
    public function getData($billId);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateData($id, $data);

    /**
     * delete multi records
     * @param integer $demandId
     * @param array $ids
     * @return mixed
     */
    public function deleteByDemandIdAndIds($demandId, $ids);

    /**
     * get first data by demand id and auction id
     * @param integer $demandId
     * @param null|integer $auctionId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getByDemandIdAuctionId($demandId, $auctionId = null);

    /**
     * count data by demand id and commission id
     * @param integer $demandId
     * @param integer $commissionId
     * @return int
     */
    public function countByDemandIdAndCommissionId($demandId, $commissionId);
}
