<?php

namespace App\Repositories;

interface AuctionInfoRepositoryInterface extends SingleKeyModelRepositoryInterface
{

    /**
     * get list
     *
     * @param array $orderBy
     * @param integer $affiliationId
     * @return mixed
     */
    public function getAuctionAlreadyList($orderBy, $affiliationId);

    /**
     * get by id
     * @param integer $id
     * @return mixed
     */
    public function getById($id);

    /**
     * count by id and commit flag
     * @param integer $id
     * @param integer $commitFlg
     * @return mixed
     */
    public function countByIdAndCommissionCommitFlag($id, $commitFlg = 1);

    /**
     * get by id and commit flag
     * @param integer $id
     * @return mixed
     */
    public function getByIdAndCommissionCommitFlag($id, $commitFlg = 1);

    /**
     * @param integer $auctionId
     * @return mixed
     */
    public function getAuctionInfoDemandInfo($auctionId);

    /**
     * @param integer $auctionId
     * @return mixed
     */
    public function getAuctionFee($auctionId);

    /**
     * @param array $dataAuction
     * @return mixed
     */
    public function updateAuctionInfo($dataAuction);

    /**
     * @param integer $auctionId
     * @return mixed
     */
    public function getFirstById($auctionId);

    /**
     * @param array $data
     * @param array $flags
     * @return mixed
     */
    public function updateFlag($data, $flags = ['refusal_flg']);

    /**
     * @param array $dataRequest
     * @return mixed
     */
    public function deleteItemByListId($dataRequest);

    /**
     * find by demand id and corp id
     *
     * @param  integer $demandId
     * @param  integer $corpId
     * @return object|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstByDemandIdAndCorpId($demandId, $corpId);

    /**
     * @param null $demandId
     * @return mixed
     */
    public function getListByDemandId($demandId = null);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function saveAuction($id, $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveAuctions($data);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getAuctionInfoByDemandIdForCheckDeadline($demandId);

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer|null $id
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($id, $data);

    /**
     * @return mixed
     */
    public function countRefusal($demandInfoId);
}
