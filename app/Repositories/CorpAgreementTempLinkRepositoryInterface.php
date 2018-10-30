<?php

namespace App\Repositories;

interface CorpAgreementTempLinkRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function getItemByCorpIdAndCorpAgreementId($corpId, $corpAgreementId);

    /**
     * @param object $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getTempLink($corpId);

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function insertAgreementTempLink($corpId, $corpAgreementId);

    /**
     * @param integer $corpId
     * @param integer $tempId
     * @return mixed
     */
    public function getFirstByCorpId($corpId, $tempId);

    /**
     * @param array $data
     * @return mixed
     */
    public function updateByTempLink($data);

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function getByCorpIdAndCorpAgreementId($corpId, $corpAgreementId);

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function getFirstByCorpIdAndCorpAgreementId($corpId, $corpAgreementId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getByCorpIdWith2Record($corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getFirstIdByCorpId($corpId);

    /**
     * @param integer $corpId
     * @param string $limit
     * @return mixed
     */
    public function getItemByCorpIdAndLimit($corpId, $limit);


    /**
     * @param $corpId
     * @param string $orderBy
     * @return mixed
     */
    public function findLatestByCorpId($corpId, $orderBy = 'desc');

    /**
     * @param integer $corpId
     * @param integer $corpAgreementId
     * @return mixed
     */
    public function insertAndGetIdBack($corpId, $corpAgreementId);

    /**
     * @param integer $cropId
     * @return mixed
     */
    public function getByCropIdWithRelation($cropId);
}
