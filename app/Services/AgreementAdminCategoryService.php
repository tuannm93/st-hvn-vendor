<?php

namespace App\Services;

use App\Models\CategoryLicenseLink;
use App\Repositories\AgreementAdminCategoryRepositoryInterface;
use App\Repositories\CategoryLicenseLinkRepositoryInterface;
use Excel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MCategory;
use Illuminate\Support\Facades\Lang;

class AgreementAdminCategoryService
{
    /**
     * @var AgreementAdminCategoryRepositoryInterface
     */
    protected $agreementCategoryRepository;

    /**
     * @var $categoryLicenseLinkRepository
     */
    private $categoryLicenseLinkRepository;

    /**
     * AgreementAdminCategoryService constructor.
     *
     * @param AgreementAdminCategoryRepositoryInterface $agreementCategoryRepository
     * @param CategoryLicenseLinkRepositoryInterface    $categoryLicenseLinkRepository
     */
    public function __construct(
        AgreementAdminCategoryRepositoryInterface $agreementCategoryRepository,
        CategoryLicenseLinkRepositoryInterface $categoryLicenseLinkRepository
    ) {
        $this->agreementCategoryRepository = $agreementCategoryRepository;
        $this->categoryLicenseLinkRepository = $categoryLicenseLinkRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAgreementCategoriesData(Request $request)
    {
        // using having for search data instead of where condition
        $query = $this->agreementCategoryRepository->getAllJoinedMCategory();
        if (validateStringIsNullOrEmpty($request->input('order.0.column'))) {
            $query = $query->orderBy('id', 'asc');
        }

        // column search
        if (!validateStringIsNullOrEmpty($request->input('columns.0.search.value'))) {
            $sql = "CAST(m_categories.id as TEXT) like ?";
            $query = $query->havingRaw($sql, ["%{$request->input('columns.0.search.value')}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.1.search.value'))) {
            $searchValue = $request->input('columns.1.search.value');
            $sql = "LOWER(m_genres.genre_name) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$searchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.2.search.value'))) {
            $searchValue = $request->input('columns.2.search.value');
            $sql = "LOWER(m_categories.category_name) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$searchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.3.search.value'))) {
            $searchValue = $request->input('columns.3.search.value');
            $sql = "LOWER(CASE m_categories.license_condition_type
                        WHEN '" . MCategory::AND_LICENSE_CONDITION . "' THEN '" . MCategory::LICENSE_CONDITION_TYPE[MCategory::AND_LICENSE_CONDITION] . "' 
                        ELSE '" . MCategory::LICENSE_CONDITION_TYPE[MCategory::OR_LICENSE_CONDITION] . "' END) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$searchValue}%"]);
        }
        if (!validateStringIsNullOrEmpty($request->input('columns.4.search.value'))) {
            $searchValue = $request->input('columns.4.search.value');
            $sql = "LOWER(string_agg(DISTINCT license.name, ',')) like LOWER(?)";
            $query = $query->havingRaw($sql, ["%{$searchValue}%"]);
        }

        //search all
        if (!validateStringIsNullOrEmpty($request->input('search.value'))) {
            $searchValue = "%{$request->input('search.value')}%";
            $sql = "(CAST(m_categories.id as TEXT) like ? OR
                    LOWER(m_genres.genre_name) like LOWER(?) OR
                    LOWER(m_categories.category_name) like LOWER(?) OR
                    LOWER(CASE m_categories.license_condition_type
                        WHEN '" . MCategory::AND_LICENSE_CONDITION . "' THEN '" . MCategory::LICENSE_CONDITION_TYPE[MCategory::AND_LICENSE_CONDITION] . "' 
                        ELSE '" . MCategory::LICENSE_CONDITION_TYPE[MCategory::OR_LICENSE_CONDITION] . "' END) like LOWER(?) OR
                    LOWER(string_agg(DISTINCT license.name, ',')) like LOWER(?))";
            $query = $query->havingRaw($sql, [$searchValue, $searchValue, $searchValue, $searchValue, $searchValue]);
        }

        $datatableQuery = DataTables::of($query)
            ->addColumn('update_info_url', function ($query) {
                return route('agreement.admin.categories.get-category-added-license', ['id' => $query->id]);
            })
            ->addColumn('update_action_url', function ($query) {
                return route('agreement.admin.categories.update-category-license', ['id' => $query->id]);
            })
            ->filterColumn('m_categories.id', function ($query, $keyword) {
            })
            ->filterColumn('id', function ($query, $keyword) {
            })
            ->filterColumn('m_genres.genre_name', function ($query, $keyword) {
            })
            ->filterColumn('m_categories.category_name', function ($query, $keyword) {
            })
            ->filterColumn('license_condition_type_converted', function ($query, $keyword) {
            })
            ->filterColumn('license_name', function ($query, $keyword) {
            });

        return $datatableQuery->make(true);
    }

    /**
     * get list license by id
     *
     * @param $id
     * @return mixed
     */
    public function getAgreementCategoryLicenseInfo($id)
    {
        return $this->categoryLicenseLinkRepository->getLicenseIdsByCategoryId($id);
    }

    /**
     * @param $id
     * @param Request $request
     */
    public function updateAgreementCategoryLicense($id, Request $request)
    {
        $category = $this->agreementCategoryRepository->getById($id);
        $genreId = $category->genre_id;

        // update m_categories.license_condition_type
        if ($request->input("licenseConditionType") != $category->license_condition_type) {
            $category->license_condition_type = $request->input("licenseConditionType");
            $this->agreementCategoryRepository->save($category);
        }

        // delete category_license_link
        $deletedIds = $request->input("deletedIds");
        if (! checkIsNullOrEmpty($deletedIds)) {
            $this->categoryLicenseLinkRepository->deleteByCategoryIdAndLicenseId($id, $deletedIds);
        }

        // add category_license_link
        $addedIds = $request->input("addedIds");
        if (! checkIsNullOrEmpty($addedIds)) {
            foreach ($addedIds as $addedId) {
                $categoryLicenseLink = new CategoryLicenseLink();
                $categoryLicenseLink->delete_flag = false;
                $categoryLicenseLink->genre_id = $genreId;
                $categoryLicenseLink->category_id = $id;
                $categoryLicenseLink->license_id = $addedId;
                $categoryLicenseLink->version_no = 1;
                $categoryLicenseLink->create_date = date('Y-m-d H:i:s');
                $categoryLicenseLink->update_date = date('Y-m-d H:i:s');

                $this->categoryLicenseLinkRepository->save($categoryLicenseLink);
            }
        }
    }

    /**
     * @param Request  $request
     * @param $fileType
     * @throws \Exception
     */
    public function exportFile(Request $request, $fileType)
    {
        set_time_limit(0);
        $fileName = Lang::get('agreement_admin.category_management');
        $data = [];

        $items = $this->getAgreementCategoriesData($request);
        $dataObject = $items->getData();
        $dataArray = $dataObject->{'data'};

        foreach ($dataArray as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['genre_name'] = $value->genre_name;
            $data[$key]['category_name'] = $value->category_name;
            $data[$key]['license_condition_type_converted'] = $value->license_condition_type_converted;
            $data[$key]['license_name'] = $value->license_name;
        }

        $dataHeader = [
            Lang::get('agreement_admin.category_id'),
            Lang::get('agreement_admin.genre_name'),
            Lang::get('agreement_admin.category_name'),
            Lang::get('agreement_admin.license_check_condition'),
            Lang::get('agreement_admin.license')
        ];

        Excel::create(
            $fileName,
            function ($excel) use ($data, $dataHeader) {
                $excel->sheet(
                    'Sheet 1',
                    function ($sheet) use ($data, $dataHeader) {

                        $sheet->row(1, $dataHeader);
                        $sheet->fromArray($data, null, 'A2', false, false);
                    }
                );
            }
        )->export($fileType);
    }
}
