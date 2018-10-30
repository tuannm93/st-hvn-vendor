<?php

namespace App\Repositories;

interface CategoryLicenseLinkRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getLicenseIdsByCategoryId($id);

    /**
     * @param integer $categoryId
     * @param integer $licenseId
     * @return mixed
     */
    public function deleteByCategoryIdAndLicenseId($categoryId, $licenseId);

    /**
     * @param integer $licenseId
     * @return mixed
     */
    public function deleteByLicenseId($licenseId);
}
