<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface AgreementAdminLicenseRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllLicense();

    /**
     * @param integer $id
     * @return mixed
     */
    public function getLicenseById($id);

    /**
     * @param Request $request
     * @return mixed
     */
    public function addLicense(Request $request);

    /**
     * @param integer $id
     * @return mixed
     */
    public function deleteLicenseById($id);

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateLicense(Request $request);
}
