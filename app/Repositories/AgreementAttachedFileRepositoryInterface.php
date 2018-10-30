<?php

namespace App\Repositories;

interface AgreementAttachedFileRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get all agreement attached file by corp id and kind
     *
     * @param  integer $corpId
     * @param  string  $kind
     * @return object agreement
     */
    public function getAllAgreementAttachedFileByCorpIdAndKind($corpId, $kind);

    /**
     * find by corp id and id
     *
     * @param  integer $corpId
     * @param  integer $fileId
     * @return object
     */
    public function findByCorpIdAndId($corpId, $fileId);

    /**
     * @param integer $fileId
     * @return mixed
     */
    public function findById($fileId);

    /**
     * @param integer $licenseId
     * @return mixed
     */
    public function findByLicenseId($licenseId);
}
