<?php

namespace App\Repositories;

interface MCorpCategoriesTempRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param \App\Models\Base|object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * save many data
     *
     * @param  array object $items
     * @return boolean
     */
    public function saveManyData($items);

    /**
     * @param integer $corpId
     * @param integer $tempId
     * @return mixed
     */
    public function countByCorpIdAndTempId($corpId, $tempId);

    /**
     * @param integer $tempId
     * @return mixed
     */
    public function countByTempId($tempId);

    /**
     * @param integer $id
     * @param integer $tempId
     * @return mixed
     */
    public function getMCorpCategoryGenreList($id = null, $tempId = null);

    /**
     * @param integer $corpId
     * @param integer   $tempId
     * @param string   $latestTempLink
     * @param string   $mCorpCategoryRepo
     * @param bool   $getAll
     * @return mixed
     */
    public function findCategoryTempCopy(
        $corpId,
        $tempId = null,
        $latestTempLink = null,
        $mCorpCategoryRepo = null,
        $getAll = false
    );

    /**
     * @param $id
     * @param $tempId
     * @return mixed
     */
    public function getTempData($id, $tempId);

    /**
     * @param array $saveData
     * @return mixed
     */
    public function saveAll($saveData);

    /**
     * get by corp id and temp id
     *
     * @param  integer $corpId
     * @param  integer $tempId
     * @return array object
     */
    public function getByCorpIdAndTempId($corpId, $tempId);

    /**
     * @param integer $corpId
     * @param integer $tempId
     * @param bool $deleteFlag
     * @param bool $disableFlgOfCategory
     * @return mixed
     */
    public function findAllByCorpIdAndTempIdWithFlag(
        $corpId,
        $tempId,
        $deleteFlag,
        $disableFlgOfCategory
    );

    /**
     * @return mixed
     */
    public function getCorpAgreementCategory();

    /**
     * @return mixed
     */
    public function getCsvCorpAgreementCategory();

    /**
     * @param $corpId
     * @param $categoryId
     * @param $tempId
     * @param $deleteFlag
     * @return mixed
     */
    public function findAllByCorpIdAndCateIdAndTempIdAndDelFlag($corpId, $categoryId, $tempId, $deleteFlag);

    /**
     * @param integer $idTemp
     * @return mixed
     */
    public function getCountByTempId($idTemp);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveWithData($data);

    /**
     * @param integer $categoryId
     * @param integer $tempId
     * @return mixed
     */
    public function findAllByCategoryIdAndTempId($categoryId, $tempId);

    /**
     * @param $idCate
     * @return mixed
     */
    public function getListCategoryIdById($idCate);

    /**
     * @param array $listCopyCate
     * @param integer $idCorp
     * @param integer $idTemp
     * @return mixed
     */
    public function getListIdBy($listCopyCate, $idCorp, $idTemp);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param integer $idCorp
     * @param integer $idGenre
     * @param integer $idCategory
     * @param integer $idTemp
     * @return mixed
     */
    public function getIdFirstBy($idCorp, $idGenre, $idCategory, $idTemp);

    /**
     * @param integer $corpId
     * @param integer $categoryId
     * @param integer $tempId
     * @param integer $deleteFlag
     * @return mixed
     */
    public function getFirstByCorpIdAndCateIdAndTempIdAndDelFlag($corpId, $categoryId, $tempId, $deleteFlag);

    /**
     * @param integer $id
     * @param integer $type
     * @return mixed
     */
    public function updateTargetAreaType($id, $type);
}
