<?php

namespace App\Repositories;

interface MCorpCategoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getFirstByCorpId($corpId);

    /**
     * get array field category
     *
     * @return array
     */
    public function getArrayFieldCategory();

    /**
     * @param integer $corpId
     * @param boolean $toArray
     * @return mixed
     */
    public function getListByCorpId($corpId = null, $toArray = true);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getByCorpId($corpId);

    /**
     * @param array $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateById($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getListForIdByCorpId($corpId = null);

    /**
     * @param integer|null $corpId
     * @param integer|null $genreId
     * @return array
     */
    public function getListForIdByCorpIdAndGenreId($corpId = null, $genreId = null);

    /**
     * @param integer $id
     * @param integer $affiliationStatus
     * @return mixed
     */
    public function getListByIdAndAffiliationStatus($id, $affiliationStatus = 1);

    /**
     * count area corp list by corp id
     *
     * @param  integer $corpId
     * @return integer
     */
    public function getCountAreaCorpListByCorpId($corpId);

    /**
     * get list id by corp id
     *
     * @param  integer $corpId
     * @return array
     */
    public function getListIdByCorpId($corpId);

    /**
     * get mcorp category id list by corp id
     *
     * @param  integer $corpId
     * @return array object
     */
    public function getCorpCategoryIdListByCorpId($corpId);

    /**
     * get all mcorp category by corp id and gener id
     *
     * @param  integer $corpId
     * @param  integer $genreId
     * @return array object
     */
    public function getAllByCorpIdAndGenreId($corpId, $genreId);

    /**
     * update corp category target area type
     *
     * @param  integer $corpId
     * @param  integer $type
     * @return none
     */
    public function updateCorpCategoryTargetAreaType($corpId, $type);

    /**
     * get list by corp_id and affiliation_status
     * @param  integer $corpId
     * @param  integer $affiliationStatus
     * @return array
     */
    public function getListByCorpIdAndAffiliationStatus($corpId, $affiliationStatus = 1);

    /**
     * update many item with array
     *
     * @param  array $arrayData
     * @return boolean
     */
    public function updateManyItemWithArray($arrayData);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getCorpSelectGenreList($id = null);

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @return mixed
     */
    public function getIdByCorpIdAndGenreId($corpId = null, $genreId = null);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveCorpCategory($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getGenresByCorpId($corpId);

    /**
     * @param integer $genreId
     * @param integer $corpId
     * @return mixed
     */
    public function getCategoriesByGenreIdCorpId($genreId, $corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findAllByCorpId($corpId);

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @param integer $categoryId
     * @return mixed
     */
    public function findByCorpIdAndGenreIdAndCategoryId($corpId, $genreId, $categoryId = null);

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @return mixed
     */
    public function findAllByCorpIdAndGenreId($corpId, $genreId);

    /**
     * @param integer $corpId
     * @param array $columns
     * @param array $order
     * @return mixed
     */
    public function getLastByCorpId($corpId, $columns = ['*'], $order = ['column' => 'id', 'dir' => 'desc']);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getCountByCorpId($corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getListForGenreAndCategoryByCorpId($corpId);

    /**
     * @param integer $corpId
     * @param integer $type
     * @return mixed
     */
    public function editCorpCategoryTargetAreaType($corpId, $type);

    /**
     * @param integer $affiliationId
     * @return mixed
     */
    public function findByAffiliationId($affiliationId);

    /**
     * Get list category_name an address for page target_area/{$corpId}
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @return mixed
     */
    public function getByCorpIdForTargetArea($corpId);

    /**
     * @param integer $corpId
     * @param integer $idCategory
     * @return mixed
     */
    public function findLastIdByCorpIdAndCategoryId($corpId, $idCategory);

    /**
     * Get list m_corp_categories join m_corps
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array $columns
     * @return mixed
     */
    public function getListByCorpDelFlagAndAffiliationStatus($columns = ["*"]);

    /**
     * Get list m_corp_categories join m_corps group by m_corps.id and genre_id
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array $columns
     * @return mixed
     */
    public function getListAndGroupByCorpDelFlagAndAffiliationStatus($columns = ["*"]);

    /**
     * @param integer $corpId
     * @param integer $categoryId
     * @param integer $defaultFee
     * @return mixed
     */
    public function getIntroduceFee($corpId, $categoryId, $defaultFee);


    /**
     * @param $corpId
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getOrderFeeCorp($corpId, $genreId, $categoryId);
}
