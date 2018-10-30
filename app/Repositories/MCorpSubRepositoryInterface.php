<?php

namespace App\Repositories;

/**
 * Interface MCorpSubRepositoryInterface
 *
 * @package App\Repositories
 */
interface MCorpSubRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findByCorpIdForAffiliation($corpId);

    /**
     * Acquisition of company master incidental information
     *
     * @param integer $id
     * @return array
     */
    public function getMCorpSubList($id);

    /**
     * @return mixed
     */
    public function holidayQuery();

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getMCorpSubData($corpId);

    /**
     * @param integer $corpId
     * @param array $fields
     * @param array $orders
     * @return mixed
     */
    public function getItemByMCorpId($corpId, $fields, $orders);

    /**
     * get m_corp_subs by corp_id and item_category
     * @param $corpId
     * @param $category
     * @return mixed
     */
    public function getItemByCorpIdAndCate($corpId, $category);

    /**
     * delete row where corp_id, item_category, item_id
     * @param $conditions
     * @return void
     */
    public function deleteItemsNotExist($conditions);
}
