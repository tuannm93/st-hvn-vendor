<?php

namespace App\Http\Controllers\Agreement;

use App\Http\Controllers\Controller;

use App\Services\AgreementAdminCategoryService;
use Illuminate\Http\Request;
use App\Repositories\AgreementAdminCategoryRepositoryInterface;

class AgreementAdminCategoryController extends Controller
{
    /**
     * @var AgreementAdminCategoryService
     */
    protected $agreementAdminCategoryService;
    /**
     * @var AgreementAdminCategoryRepositoryInterface
     */
    protected $agreementCategoryRepository;

    /**
     * AgreementAdminCategoryController constructor.
     *
     * @param AgreementAdminCategoryService             $agreementAdminCategoryService
     * @param AgreementAdminCategoryRepositoryInterface $agreementCategoryRepository
     */
    public function __construct(
        AgreementAdminCategoryService $agreementAdminCategoryService,
        AgreementAdminCategoryRepositoryInterface $agreementCategoryRepository
    ) {
        parent::__construct();
        $this->agreementAdminCategoryService = $agreementAdminCategoryService;
        $this->agreementCategoryRepository = $agreementCategoryRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAgreementCategoriesPage()
    {
        return view('agreement.admin.categories.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAgreementCategoriesData(Request $request)
    {
        return $this->agreementAdminCategoryService->getAgreementCategoriesData($request);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAgreementCategoryAddedLicense($id)
    {
        return $this->agreementAdminCategoryService->getAgreementCategoryLicenseInfo($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function updateAgreementCategoryLicense($id, Request $request)
    {
        $this->agreementAdminCategoryService->updateAgreementCategoryLicense($id, $request);
        $content = __('agreement_admin.the_setting_process_is_complete');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function exportExcel(Request $request)
    {
        $this->agreementAdminCategoryService->exportFile($request, config("datacustom.file_type.excel"));
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function exportCsv(Request $request)
    {
        $this->agreementAdminCategoryService->exportFile($request, config("datacustom.file_type.csv"));
    }
}
