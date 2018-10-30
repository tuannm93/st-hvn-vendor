<?php

namespace App\Repositories;

interface MGenresRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param bool $validFlg
     * @param bool $useExclusionFlg
     * @return mixed
     */
    public function getList($validFlg = false, $useExclusionFlg = false);

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param array $data
     * @return mixed
     */
    public function editGenre($data);

    /**
     * @return mixed
     */
    public function getListBySelectType();

    /**
     * @param integer $genreId
     * @return mixed
     */
    public function getNameById($genreId = null);
    /**
     * @param array $arrGenreId
     * @param array $arrCategoryId
     * @return mixed
     */
    public function queryListGenreRelated($arrGenreId = [], $arrCategoryId = []);

    /**
     * @param integer $registrationMediation
     * @return mixed
     */
    public function getListByMediation($registrationMediation = 1);

    /**
     * @return mixed
     */
    public function findAllForAffiliation();

    /**
     * @param array $ids
     * @return mixed
     */
    public function getCommissionUnitPrice($ids);

    /**
     * @return mixed
     */
    public function getSelectionGenre();

    /**
     * @param array $condition
     * @param array $orderBy
     * @return mixed
     */
    public function getGenreWithConditions($condition = [], $orderBy = []);

    /**
     * @param integer $id
     * @param  integer $flag
     * @return mixed
     */
    public function updateExclusionFlg($id, $flag);

    /**
     * @param integer $corpId
     * @param integer $dkey
     * @return mixed
     */
    public function getMGenreByCorpIdAnDevelopmentGroup($corpId, $dkey);

    /**
     * Get corp category by corp_id and genre_id
     * @param integer $corpId
     * @param integer $genreId
     * @return mixed
     */
    public function getListByCorpIdAndGenreId($corpId, $genreId);

    /**
     * Get list data for addition form
     * @param array $whereCondition
     * @return mixed
     */
    public function getListForAdditionForm($whereCondition);

    /**
     * Get list select box function
     * @param array $condition
     * @return \Illuminate\Support\Collection
     */
    public function getListSelectBox($condition);

    /**
     * Get list genres
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  array $ids
     * @return \Illuminate\Support\Collection
     */
    public function getGenresByIds($ids);

    /**
     * @param integer $siteId
     * @return array genres
    */
    public function getListForDropDown($siteId);

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @return string
     */
    public function getListText($id);
}
