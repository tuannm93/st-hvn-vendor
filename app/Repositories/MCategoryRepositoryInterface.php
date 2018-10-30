<?php

namespace App\Repositories;

interface MCategoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * count by category id and genre id
     *
     * @param  integer $categoryId
     * @param  integer $genreId
     * @return integer
     */
    public function countByCategoryIdAndGenreId($categoryId, $genreId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getFirstByCorpId($corpId);

    /**
     * @param integer $id
     * @param integer $genreId
     * @return mixed
     */
    public function getCount($id, $genreId);

    /**
     * @param integer $genreId
     * @param bool $isAllCategory
     * @return mixed
     */
    public function getList($genreId = null, $isAllCategory = false);

    /**
     * @param integer $genreId
     * @param bool $isAllCategory
     * @return mixed
     */
    public function getDropListCategory($genreId = null, $isAllCategory = false);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getListText($id);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getDefaultFee($id);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getCommissionType($id);

    /**
     * Acquisition of Contract STOP category
     *
     * @param integer $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getStopCategoryList($corpId);

    /**
     * @param integer $id
     * @return mixed
     */
    public function getFeeData($id);

    /**
     * @param integer $corpId
     * @param  integer $tempId
     * @return mixed
     */
    public function getAllTempCategory($corpId, $tempId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getAllCategoryByCorpId($corpId);

    /**
     * @return mixed
     */
    public function findAllForAffiliation();

    /**
     * @param array $listCopyCategoriesId
     * @return mixed
     */
    public function findAllByCopyCategoryIds($listCopyCategoriesId);

    /**
     * @param integer $categoryId
     * @return mixed
     */
    public function getFeeDataCategories($categoryId);

    /**
     * @param integer $genreId
     * @param bool $isAllCategory
     * @return mixed
     */
    public function getListStHide($genreId = null, $isAllCategory = false);

    /**
     * @param null $genreId
     * @return mixed
     */
    public function getListCategoriesForDropDown($genreId);

    /**
     * @param $CategoryId
     * @return mixed
     */
    public function getNameById($CategoryId);
}
