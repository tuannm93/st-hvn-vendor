<?php

namespace App\Repositories;

interface DemandInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @param array $orderBy
     * @return mixed
     */
    public function searchDemandInfoList($data = null, $orderBy = null);

    /**
     * Counting demand_info by genres
     *
     * @param $genreId
     * @param string $auctioned
     * @param string $year
     * @param string $month
     * @param array $systemType
     * @return mixed
     */
    public function getDemandbyGenreId($genreId, $auctioned = null, $year = null, $month = null, $systemType = []);

    /**
     * @param array $data
     * @return mixed
     */
    public function getQueryDemandInfo($data);

    /**
     * @param array $data
     * @return mixed
     */
    public function getDemandInfo($data);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getDemandById($demandId);

    /**
     * @param integer $sort
     * @param integer $direction
     * @return mixed
     */
    public function getDemandForReport($sort = null, $direction = null);

    /**
     * @param string $subQueryForHearNum
     * @return mixed
     */
    public function joinQueryGetReportCorpCommission($subQueryForHearNum);

    /**
     * @return mixed
     */
    public function joinQueryGetReportCorpSelection();

    /**
     * @return mixed
     */
    public function getAllFields();

    /**
     * @param integer $subQueryForDemandStatus
     * @return mixed
     */
    public function findReportDemandStatus($subQueryForDemandStatus);

    /**
     * @param string $subQueryForHearNum
     * @return mixed
     */
    public function getRealTimeReportHearLossNum1($subQueryForHearNum);

    /**
     * @param string $subQueryForHearNum
     * @return mixed
     */
    public function getRealTimeReportHearLossNum2($subQueryForHearNum);

    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getLimitoverTime($demandId);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateDemandData($id, $data);

    /**
     * @param array $field
     * @param array $sortData
     * @return mixed
     */
    public function getDataUnSentList($field, $sortData);

    /**
     * @param integer $id
     * @return mixed
     */
    public function deleteByDemandId($id);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param array $setting
     * @return mixed
     */
    public function getDemandInfos($setting);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateExecuteFollowDate($data);

    /**
     * Check deadline past auction base on sub query
     *
     * @param string $subQuery
     * @return mixed
     */
    public function commandCheckDeadlinePastAuction($subQuery);

    /**
     * @param string $subQuery
     * @return mixed
     */
    public function commandCheckCorpRefusal($subQuery);

    /**
     * Get list auction auto call.
     *
     * @param bool $autoCallFlg
     * @return mixed
     */
    public function getAutoCallList($autoCallFlg);

    /**
     * Get demand info
     * @param integer $customerTel
     * @return mixed
     */
    public function getFirstDemandByTel($customerTel);

    /**
     * @param array $orders
     * @return mixed
     */
    public function getJbrCommissionReport($orders = []);

    /**
     * @param array $orders
     * @return mixed
     */
    public function totalRecordCommissionReport($orders = []);

    /**
     * get jbr ongoing
     *
     * @param  array $data
     * @return object
     */
    public function getJbrOngoing($data);

    /**
     * @param integer $demandId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getMailData($demandId);

    /**
     * Get list data demand by selection_system
     * Use in DemandInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param array $selectionSystem
     * @return mixed
     */
    public function getForDemandGuideSendMail($selectionSystem);

    /**
     * Check identically customer
     * Use in DemandInfoService
     * @param String $customerTel
     * @return mixed
     */
    public function checkIdenticallyCustomer($customerTel);

    /**
     * Get demand detail
     * Use in DemandInfoService
     * @param integer $demandId
     * @return mixed
     */
    public function getDemandByIdWithRelations($demandId);

    /**
     * @param $demandId
     * @return mixed
     */
    public function getGenreCategoryNameByDemand($demandId);

    /**
     * @param $customerTel
     * @return mixed
     */
    public function getCustomerTel($customerTel);
}
