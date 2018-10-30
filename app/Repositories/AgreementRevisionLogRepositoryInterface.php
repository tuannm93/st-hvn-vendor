<?php

namespace App\Repositories;

interface AgreementRevisionLogRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllContractTermsRevisionHistoryJoinMUserWithoutContent();

    /**
     * @param integer $id
     * @return mixed
     */
    public function findByIdJoinWithMUser($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function insert($data);

    /**
     * @return mixed
     */
    public function getFirstAgreementRevisionLog();

    /**
     * @return mixed
     */
    public function getMaxAgreementRevisionLogId();
}
