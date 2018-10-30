<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportCorpCateAppAdminRequest;
use App\Repositories\ApprovalRepositoryInterface;
use App\Services\Report\ReportAdminService;

class ReportAdminController extends Controller
{
    /**
     * @var ApprovalRepositoryInterface
     */
    protected $approvalRepo;

    /**
     * @var ReportAdminService
     */
    protected $reportAdminService;

    /**
     * @var string
     */
    private $mItemCategory = "申請";

    /**
     * ReportAdminController constructor.
     *
     * @param ApprovalRepositoryInterface $approvalRepository
     * @param ReportAdminService          $reportAdminService
     */
    public function __construct(ApprovalRepositoryInterface $approvalRepository, ReportAdminService $reportAdminService)
    {
        parent::__construct();
        $this->approvalRepo = $approvalRepository;
        $this->reportAdminService = $reportAdminService;
        $this->pageNumber = 20;
    }

    /**
     * @param $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCorpCategoryAppAdmin($groupId)
    {
        $results = $this->approvalRepo->getApprovalForCropCategoryAppAdmin($groupId);

        return view(
            "report.corp_category_application_admin",
            [
            "user" => $this->getUser(),
            "results" => $results,
            "groupId" => $groupId,
            ]
        );
    }

    /**
     * Check action and update approvals, m_corp_categories_temp
     *
     * @param  $groupId
     * @param  \App\Http\Requests\ReportCorpCateAppAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCorpCategoryAppAdmin($groupId, ReportCorpCateAppAdminRequest $request)
    {
        $approvalIds = [];
        foreach ($request->input("check") as $approvalId) {
            $approvalIds[] = $approvalId;
        }
        $result = $this->reportAdminService->postCorpCategoryAppAdmin($groupId, $approvalIds, $request->input("submit"), $this->getUser()->user_id);
        if ($result) {
            $request->session()->flash('success', trans('report_corp_cate_app_admin.updated'));
        } else {
            $request->session()->flash('error', trans('report_corp_cate_app_admin.error'));
        }

        return redirect()->back();
    }

    /**
     * @param $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCorpCategoryAppAnswer($groupId)
    {
        $results = $this->approvalRepo->getApprovalForCropCategoryAppAnswer($groupId, $this->pageNumber);

        return view(
            "report.corp_category_application_answer",
            [
            "mItems" => getDropList($this->mItemCategory),
            "results" => $results,
            "groupId" => $groupId,
            ]
        );
    }
}
