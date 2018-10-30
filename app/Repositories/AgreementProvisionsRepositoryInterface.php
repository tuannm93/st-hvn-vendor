<?php

namespace App\Repositories;

interface AgreementProvisionsRepositoryInterface
{

    /**
     * @param integer $id
     * @param integer $deleteFlag
     * @return mixed
     */
    public function findAgreementById($id, $deleteFlag);

    /**
     * @param integer $agreementId
     * @param integer $deleteFlag
     * @return mixed
     */
    public function findAgreementProvisionsByAgreementId($agreementId, $deleteFlag);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);
}
