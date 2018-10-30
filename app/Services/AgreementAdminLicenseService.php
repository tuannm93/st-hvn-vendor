<?php


namespace App\Services;

use App\Models\License;
use App\Repositories\AgreementAdminLicenseRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AgreementAdminLicenseService
{
    /**
     * @var AgreementAdminLicenseRepositoryInterface
     */
    protected $agreementLicenseRepository;


    /**
     * AgreementAdminLicenseService constructor.
     *
     * @param AgreementAdminLicenseRepositoryInterface $agreementLicenseRepository
     */
    public function __construct(
        AgreementAdminLicenseRepositoryInterface $agreementLicenseRepository
    ) {
        $this->agreementLicenseRepository = $agreementLicenseRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAgreementLicenseData(Request $request)
    {
        $query = $this->agreementLicenseRepository->getAllLicense();

        if (validateStringIsNullOrEmpty($request->input('order.0.column'))) {
            $query = $query->orderBy('id', 'asc');
        }

        // search all text box
        if (!validateStringIsNullOrEmpty($request->input('search.value'))) {
            $searchValue = "%{$request->input('search.value')}%";
            $sql = "(CAST(id as TEXT) like ?) OR
                    LOWER(name) like LOWER(?) OR
                    LOWER(CASE certificate_required_flag WHEN true THEN '" . License::HAVE_TO . "' ELSE '' END) like LOWER(?)";
            $query = $query->havingRaw($sql, [$searchValue, $searchValue, $searchValue]);
        }

        // search textboxs in table's headers
        if (!validateStringIsNullOrEmpty($request->input('columns.0.search.value'))) {
            $idValue = $request->input('columns.0.search.value');
            $sql = "CAST(id as TEXT) like ?";
            $query = $query->havingRaw($sql, ["%{$idValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.1.search.value'))) {
            $nameValue = $request->input('columns.1.search.value');
            $sql = "LOWER(name) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$nameValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.2.search.value'))) {
            $requiredValue = $request->input('columns.2.search.value');
            $sql = "LOWER(CASE certificate_required_flag WHEN true THEN '" . License::HAVE_TO . "' ELSE '' END) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$requiredValue}%"]);
        }

        $datatableQuery = DataTables::of($query)
            ->filterColumn(
                'id',
                function ($query) {
                }
            )
            ->filterColumn(
                'name',
                function ($query) {
                }
            )
            ->filterColumn(
                'certificate_required_flag_converted',
                function ($query) {
                }
            )
            ->addColumn(
                'detail_url',
                function ($query) {
                    return action('Agreement\AgreementAdminLicenseController@getLicenseDetail', ['id' => $query->id]);
                }
            )
            ->addColumn(
                'delete_url',
                function ($query) {
                    return action('Agreement\AgreementAdminLicenseController@deleteLicense', ['id' => $query->id]);
                }
            );

        return $datatableQuery->make(true);
    }
}
