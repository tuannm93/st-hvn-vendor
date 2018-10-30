<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface MCorpRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $customerTel
     * @return mixed
     */
    public function searchAffiliationInfoAll($customerTel);

    /**
     * @param integer $id
     * @param bool $toArray
     * @return mixed
     */
    public function getFirstById($id, $toArray = false);

    /**
     * find m_corp by id
     *
     * @param integer $corpId
     * @return \App\Models\Base|MCorp|\Illuminate\Database\Eloquent\Model|mixed|static
     */
    public function findMcorp($corpId);

    /**
     * get m_corps data
     *
     * @param array $data
     * @param integer $page
     * @return mixed
     */
    public function searchCorpAndPaging($data, $page);

    /**
     * @param integer $id
     * @return Collection
     */
    public function findByIdForAffiliation($id);

    /**
     * @param string $searchKey
     * @param array $conditions
     * @param integer $limitSearch
     * @param integer $count
     * @return mixed
     */
    public function searchByCorpIdOrCorpName($searchKey, $conditions, $limitSearch, $count);

    /**
     * @param array $data
     * @param string $limitSearch
     * @param integer $count
     * @return mixed
     */
    public function searchCorpAddList($data, $limitSearch, $count);

    /**
     * @param array $data
     * @param bool $isNew
     * @param integer $count
     * @return mixed
     */
    public function searchCorpForPopup($data, $isNew, $count);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getDataAffiliationById($id);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateCorp($id, $data);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteSoftById($id);

    /**
     * @param string $searchKey
     * @param string $searchValue
     * @param integer $excludeCorpId
     * @return mixed
     */
    public function buildAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId);

    /**
     * @param string $searchKey
     * @param string $searchValue
     * @param integer $excludeCorpId
     * @param integer $limit
     * @return mixed
     */
    public function getAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId, $limit = 50);

    /**
     * @param string $searchKey
     * @param string $searchValue
     * @param integer $excludeCorpId
     * @return mixed
     */
    public function getCountAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId);

    /**
     * @param array $categoryIds
     * @param array $listPref
     * @param  array $corpIds
     * @param integer $type
     * @param string $text
     * @return mixed
     */
    public function searchByCategoryPref($categoryIds, $listPref, $corpIds, $type, $text);

    /**
     * @param array $categoryIds
     * @param array $address1
     * @return mixed
     */
    public function getListByCategoryIdsAndAddress1($categoryIds, $address1);

    /**
     * @param $allCondition
     * @param string $orderBy
     * @param string $direction
     * @param $page
     * @param integer $limit
     * @return mixed
     */
    public function getListCorpByConditionFromAffiliation(
        $allCondition,
        $orderBy = 'id',
        $direction = 'asc',
        $page = 1,
        $limit = 100
    );

    /**
     * @param object $allCondition
     * @return mixed
     */
    public function createDataDownloadCsvAffiliation($allCondition);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function updateGuidelineCheckDate($corpId);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateById($data);

    /**
     * get official_corp_name column
     *
     * @param integer $id
     * @return mixed
     */
    public function getOfficialName($id);

    /**
     * get all by id
     * @param integer $id
     * @return mixed
     */
    public function getAllInformationById($id);

    /**
     * Get crop unattended for report development search
     *
     * @param integer $genreId
     * @return mixed
     */
    public function getUnattendedForReportDevByGenreId($genreId);

    /**
     * Get crop advance for report development search
     *
     * @param integer $genreId
     * @return mixed
     */
    public function getAdvanceForReportDevByGenreId($genreId);

    /**
     * @param integer $genreId
     * @param string $address
     * @param null $status
     * @return mixed
     */
    public function getListForDataTableByGenreIdAndAddressAndStatus($genreId, $address, $status = null);

    /**
     * @param null $data search
     * @return array m corp
     */
    public function getListForCommissionSelect($data = null);

    /**
     * @param integer $affiliationId
     * @return mixed
     */
    public function findByAffiliationId($affiliationId);

    /**
     * @param array $corpIds
     * @return mixed
     */
    public function getHolidayByCorpId($corpIds = []);

    /**
     * @param array $data
     * @param bool $builder
     * @return mixed
     */
    public function demandCorpData($data, $builder = false);

    /**
     * @param integer $id
     * @return mixed
     */
    public function isCommissionStop($id);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getCorpData($corpId);

    /**
     * @param integer $id
     * @param array $columns
     * @param array $order
     * @return mixed
     */
    public function getDataById($id, $columns = ['*'], $order = ['column' => 'id', 'dir' => 'desc']);

    /**
     * @param string $corpName
     * @return mixed
     */
    public function findByName($corpName);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getHolidays($corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getHolidayListByCorpId($corpId);

    /**
     * @param integer $corpId
     * @param integer $categoryId
     * @return mixed
     */
    public function getByCorpIdAndCategoryId($corpId, $categoryId = null);

    /**
     * @param integer $num
     * @param integer $categoryId
     * @param integer $corpId
     * @return mixed
     */
    public function getCommissionChangeByCategoryIdAndCorpId($num, $categoryId = null, $corpId = null);

    /**
     * Get data to check deadline in command
     * @param integer $jisCd
     * @param array $data
     * @return mixed
     */
    public function getDataCheckDeadlineCommand($jisCd, $data);

    /**
     * Get m_corp contactable time
     *
     * @param integer $corpId
     * @return mixed
     */
    public function getContactableTime($corpId);

    /**
     * @param $staffId
     * @return mixed
     */
    public function getMailByUserId($staffId);

    /**
     * @param array $data
     * @param bool $isAntiSocial
     * @return mixed
     */
    public function updateAll($data, $isAntiSocial = false);

    /**
     * @param $userId
     * @return mixed
     */
    public function getCorpNameAndStaffNameFromUserId($userId);
}
