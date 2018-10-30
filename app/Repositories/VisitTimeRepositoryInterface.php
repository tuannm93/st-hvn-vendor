<?php

namespace App\Repositories;

interface VisitTimeRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $demandId
     * @param bool $first
     * @return mixed
     */
    public function findAllByDemandId($demandId, $first = false);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param array  $data
     * @return mixed
     */
    public function saveMany($data);

    /**
     * @param array $data
     * @return mixed
     */
    public function multipleUpdate($data);

    /**
     * @param array $ids
     * @return mixed
     */
    public function multipleDelete($ids);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function findListByDemandId($demandId);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function findAllWithAuctionInfo($demandId);
}
