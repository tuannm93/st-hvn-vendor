<?php

namespace App\Repositories;

interface CorpAgreementRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get status message
     *
     * @return array
     */
    public function getStatusMessage();

    /**
     * get count  by corp_id and status
     * @param  integer $corpId
     * @return integer
     */
    public function getCountByCorpIdAndStatus($corpId);

    /**
     * get first item by corp_id and agreement_id
     * @param  integer $corpId
     * @param  integer $agreementId
     * @param  boolean $isLastCorpId
     * @return object agreement
     */
    public function getFirstByCorpIdAndAgreementId($corpId, $agreementId = null, $isLastCorpId = false);

    /**
     * get all corp agreement by crop id
     *
     * @param  integer $corpId
     * @param  string $orderBy
     * @return object agreement
     */
    public function getAllByCorpId($corpId, $orderBy = 'desc');

    /**
     * get first item by corp_id and status
     * @param integer corpId
     * @param status
     * @return object
    */
    public function findByCorpIdAndStatus($corpId, $status);

    /**
     * @param array $data
     * @return mixed
     */
    public function createNewCorpAgreement($data);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findByCorpId($corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findByCorpIdAndStatusCompleteAndNotNullAcceptationDate($corpId);
}
