<?php

namespace App\Repositories;

interface AutoCommissionCorpRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * count auto commission corp list by corpId
     * @param  integer $corpId
     * @return integer
     */
    public function countByCorpId($corpId);

    /**
     * @param integer $jisCd
     * @param integer $categoryId
     * @param integer $corpId
     * @return mixed
     */
    public function deleteBy($jisCd, $categoryId, $corpId);

    /**
     * @param array $data
     * @param integer $type
     * @param bool $checkRequest
     * @return mixed
     */
    public function getByCategoryGenreAndPrefCd($data, $type = null, $checkRequest = true, $datacustom = 'state_list');

    /**
     * @param integer $arrGenreId
     * @return mixed
     */
    public function findByGenreId($arrGenreId);

    /**
     * @param array $listCateId
     * @param array $listPrefId
     * @return mixed
     */
    public function deleteByCateAndPref($listCateId, $listPrefId);

    /**
     * @param array $listCorpCommission
     * @param array $listCorpSelect
     * @param array $listCate
     * @param array $listJiscd
     * @return mixed
     */
    public function addCorpInfor($listCorpCommission, $listCorpSelect, $listCate, $listJiscd);
}
