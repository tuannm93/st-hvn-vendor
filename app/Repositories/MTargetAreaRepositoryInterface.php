<?php

namespace App\Repositories;

interface MTargetAreaRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param \App\Models\Base|object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param int $corpCategoryId
     * @return bool
     */
    public function deleteById($corpCategoryId);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertByCorpCategoryId($data);

    /**
     * @param integer|null $id
     * @param integer|null $jisCD
     * @return integer
     */
    public function getCorpCategoryTargetAreaCount($id = null, $jisCD = null);

    /**
     * delete list item by array corp category id
     *
     * @param  array $arrayId
     * @return boolean
     * @throws \Exception
     */
    public function deleteListItemByArrayCorpCategoryId($arrayId);

    /**
     * count corp category target area
     *
     * @param  integer $corpCategoryId
     * @return integer
     */
    public function countCorpCategoryTargetArea($corpCategoryId);

    /**
     * @param integer $id
     * @param integer $jisCd
     * @return mixed
     */
    public function getCorpCategoryTargetAreaCount2($id = null, $jisCd = null);

    /**
     * @param integer $corpCategoryId
     * @return mixed
     */
    public function findAllByCorpCategoryId($corpCategoryId);

    /**
     * @param integer $corpCategoryId
     * @param integer $jisCd
     * @return mixed
     */
    public function findAllByCorpCategoryIdAndJisCd($corpCategoryId, $jisCd);

    /**
     * @param integer $corpId
     * @param array $defaultJisCds
     * @return mixed
     */
    public function countHasJisCdsOfCorpCategory($corpId = null, $defaultJisCds = null);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getTargetAreaLastModified($corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getCorpCategoryTargetAreaCount3($corpId);

    /**
     * @param integer $id
     * @param integer $jisCd
     * @return mixed
     */
    public function getCorpCategoryTargetAreaByJisCd($id = null, $jisCd = null);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveAll($data);

    /**
     * @param integer $corpCategoryId
     * @return mixed
     */
    public function deleteByCorpCategoryId($corpCategoryId);
}
