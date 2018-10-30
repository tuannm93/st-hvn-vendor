<?php

namespace App\Repositories;

interface MCorpTargetAreaRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get list by corp id
     *
     * @param  integer $corpId
     * @param boolean $toArray
     * @return array object
     */
    public function getListByCorpId($corpId, $toArray = false);

    /**
     * @param \App\Models\Base|object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function countByCorpId($corpId = null);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getLastModifiedByCorpId($corpId = null);

    /**
     * @param integer $id
     * @return mixed
     */
    public function editTargetAreaToGenre($id);

    /**
     * @param null $id
     * @param null $address1
     * @return mixed
     */
    public function removeByCorpId($id = null, $address1 = null);

    /**
     * @param integer $id
     * @param integer $genreId
     * @return mixed
     */
    public function editTargetAreaToCategory($id = null, $genreId = null);

    /**
     * get list by corp_id and address_code
     *
     * @param integer $corpId
     * @param string $addressCode
     * @return mixed
     */
    public function getListByCorpIdAndAddressCode($corpId, $addressCode);

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteByListId($ids);

    /**
     * @param integer $corpId
     * @param array $columns
     * @param array $order
     * @return mixed
     */
    public function getLastByMCorp($corpId, $columns = ['*'], $order = ['column' => 'id', 'dir' => 'desc']);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getJscByCorpId($corpId);
}
