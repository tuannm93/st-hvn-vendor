<?php

namespace App\Repositories;

interface CommissionInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $corpId
     * @return mixed
     */
    public function checkCreditSumPrice($corpId = null);

    /**
     * get commisson info by demand id
     *
     * @param  integer $demandId
     * @param boolean $isCorpFields
     * @param integer $commitFlg
     * @return array
     */
    public function getListByDemandId($demandId, $isCorpFields = false, $commitFlg = 1);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getWithRelationById($id);

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $demandId
     * @param array $commInfoCols
     * @param array $corpCols
     * @return mixed
     */
    public function getCommInfoWithCorpByDemandId($demandId, $commInfoCols = ["*"], $corpCols = ["*"]);

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @return mixed
     */
    public function getCommInfoForExportWordById($id);

    /**
     * @param \App\Models\Base|object|array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * get list by list commission_infos.id
     *
     * @param array $ids
     * @param array $corpIds
     * @return mixed
     */
    public function getListByIds($ids, $corpIds = null);

    /**
     * @param string $followDateFrom
     * @param string $followDateTo
     * @param bool $isGetAll
     * @return mixed
     */
    public function getListJbrReceiptFollow($followDateFrom = null, $followDateTo = null, $isGetAll = true);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getCommissionInfoById($id);

    /**
     * @return mixed
     */
    public function getAllFields();

    /**
     * @return mixed
     */
    public function subQueryForDemandStatus();

    /**
     * @return mixed
     */
    public function subQueryForHearNum();

    /**
     * @param integer $commissionId
     * @return mixed
     */
    public function findById($commissionId);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findCommissionInfo($id);

    /**
     * @param integer $id
     * @param array $data
     * @return \App\Models\Base
     */
    public function update($id, $data);

    /**
     * @param integer $demandId
     * @param integer $id
     * @return mixed
     */
    public function getByDemandId($demandId, $id);


    /**
     * Search commission info by condition
     *
     * @author TungDo
     * @param array $conditions
     * @param array $orderBy
     * @param integer $limit
     * @return mixed
     */
    public function searchCommissionInfo($conditions, $orderBy, $limit);


    /**
     * Get list data csv export by condition
     *
     * @author TungDo
     * @param array $conditions
     * @return mixed
     */
    public function getListCommissionExportCSV($conditions);

    /**
     * @param array $params
     * @param array $sortPrarams
     * @return mixed
     */
    public function getSalesSupport($params = [], $sortPrarams = []);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getCommissionInfoByIdForApproval($id);

    /**
     * get first by demand_id and corp_id
     *
     * @param integer $demandId
     * @param integer $corpId
     * @return mixed
     */
    public function getFirstByDemandIdAndCorpId($demandId, $corpId);

    /**
     * @param integer $demandId
     * @param integer $corpId
     * @return mixed
     */
    public function getWordData($demandId, $corpId);

    /**
     * Get list commission_infos, m_corps, demand_infos, affiliation_stats
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithRelForGroupCategory();

    /**
     * Get list commission_infos, m_corps, demand_infos, affiliation_stats by commission_status
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return mixed
     */
    public function getWithRelForGroupCategoryByComStatus($status);

    /**
     * Get list commission_infos, m_corps and count total row
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  boolean $inWeek
     * @return mixed
     */
    public function getWithMCorpAndCountRow($inWeek = false);

    /**
     * Get list corp_id, total row, avg construction_price_tax_exclude by commission_status
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return mixed
     */
    public function getWithAVGPriceTaxByStatus($status);

    /**
     * Get list corp_id, avg corp_fee
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithAvgCorpFee();

    /**
     * Get list commission_infos, m_corps, demand_infos, affiliation_area_stats
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithRelForGroupCategoryByPrefecture();

    /**
     * Get sub query commission info
     *
     * @return mixed
     */
    public function subCommissionInfo();

    /**
     * Find data by demand id
     *
     * @param integer $demandId
     * @return mixed
     */
    public function findByDemandId($demandId);

    /**
     * Get list column using in function commission search
     *
     * @author TungDo
     * @return mixed
     */
    public function getColumnInCommissionSearch();

    /**
     * Get List column using in function csv export
     *
     * @author TungDo
     * @return mixed
     */
    public function getColumnInCsvExport();

    /**
     * @param array $ids
     * @return mixed
     */
    public function updateAppPushFlg($ids);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertCommission($data);

    /**
     * @param array $data
     * @return mixed
     */
    public function multipleUpdate($data);

    /**
     * @param integer $demandId
     * @param integer $corpId
     * @param integer $cType
     * @return mixed
     */
    public function findByDemandIdCorpAndType($demandId, $corpId, $cType);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getAllCommissionByDemandId($demandId);

    /**
     * Find commission registed by demand id, use for Batch 002
     * @param $demandId
     * @return mixed
     */
    public function findCommissionRegistedByDemandId($demandId);

    /**
     * @param $commissionId
     * @param \Monolog\Logger $logger
     * @return mixed
     */
    public function updateWorkStatusAfterDeleteSchedule($commissionId, $logger);
}
