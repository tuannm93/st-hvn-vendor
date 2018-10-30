<?php

namespace App\Repositories;

interface CorpLicenseLinkRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $licenseId
     * @return mixed
     */
    public function findByLicenseId($licenseId);
}
