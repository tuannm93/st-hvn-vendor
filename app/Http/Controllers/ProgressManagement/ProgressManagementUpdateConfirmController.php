<?php

namespace App\Http\Controllers\ProgressManagement;

use App\Repositories\ProgAddDemandInfoTmpRepositoryInterface;
use App\Repositories\ProgCorpRepositoryInterface;
use App\Repositories\ProgDemandInfoOtherTmpRepositoryInterface;
use App\Repositories\ProgDemandInfoTmpRepositoryInterface;
use App\Services\PMUpdateConfirmService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\ProgDemandInfo;
use App\Repositories\MCorpRepositoryInterface;
use Exception;

class ProgressManagementUpdateConfirmController extends Controller
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var ProgCorpRepositoryInterface
     */
    protected $progCorpRepository;
    /**
     * @var ProgDemandInfoTmpRepositoryInterface
     */
    protected $progDemandInfoTmpRepo;
    /**
     * @var ProgAddDemandInfoTmpRepositoryInterface
     */
    protected $progAddDemandInfoTmpRepo;

    /**
     * @var ProgDemandInfoOtherTmpRepositoryInterface
     */
    protected $progDemandInfoOtherTmpRepo;
    /**
     * @var PMUpdateConfirmService
     */
    protected $pmUpdateConfirmService;

    /**
     * ProgressManagementUpdateConfirmController constructor.
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param ProgCorpRepositoryInterface $progCorpRepository
     * @param ProgDemandInfoTmpRepositoryInterface $progDemandInfoTmpRepo
     * @param ProgDemandInfoOtherTmpRepositoryInterface $progDemandInfoOtherTmpRepo
     * @param ProgAddDemandInfoTmpRepositoryInterface $progAddDemandInfoTmpRepo
     * @param PMUpdateConfirmService $pmUpdateConfirmService
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        ProgCorpRepositoryInterface $progCorpRepository,
        ProgDemandInfoTmpRepositoryInterface $progDemandInfoTmpRepo,
        ProgDemandInfoOtherTmpRepositoryInterface $progDemandInfoOtherTmpRepo,
        ProgAddDemandInfoTmpRepositoryInterface $progAddDemandInfoTmpRepo,
        PMUpdateConfirmService $pmUpdateConfirmService
    ) {
        parent::__construct();
        $this->mCorpRepository = $mCorpRepository;
        $this->progDemandInfoTmpRepo = $progDemandInfoTmpRepo;
        $this->progCorpRepository = $progCorpRepository;
        $this->progAddDemandInfoTmpRepo = $progAddDemandInfoTmpRepo;
        $this->progDemandInfoOtherTmpRepo = $progDemandInfoOtherTmpRepo;
        $this->pmUpdateConfirmService = $pmUpdateConfirmService;
    }

    /**
     * show page update  confirm
     * @param  integer $progImportFileId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    public function showUpdateConfirm($progImportFileId = null)
    {
        try {
            $corpId         = Auth::user()->affiliation_id;
            $corpInfo       = $this->mCorpRepository->getFirstById($corpId);
            $officialCorpName = !empty($corpInfo->official_corp_name) ? $corpInfo->official_corp_name : '';
            $progCorp       = $this->progCorpRepository->findFirstByCorpIdAndFileId($corpId, $progImportFileId);
            $progDemandInfo = $this->progDemandInfoTmpRepo->getByProgCorpId($progCorp);
            if (!count($progDemandInfo)) {
                throw new Exception();
            }
            $progAddDemandInfo  = $this->progAddDemandInfoTmpRepo->getByProgCorpId($progCorp);
            $progImportFile     = $this->progDemandInfoOtherTmpRepo->findByProgCorpId($progCorp);
            $pmCommissionStatus = ProgDemandInfo::PM_COMMISSION_STATUS;
            $diffFllags         = ProgDemandInfo::PM_DIFF_LIST;
            $reasonList         = getDropList(config('rits.commission_order_fail_reason'));
            $demandTypeList     = config('rits.demand_type_list');
            return view('progress_management.update_confirm', compact('progDemandInfo', 'progAddDemandInfo', 'progImportFile', 'pmCommissionStatus', 'diffFllags', 'reasonList', 'demandTypeList', 'progImportFileId', 'officialCorpName'));
        } catch (Exception $exception) {
            $errorMessage = trans('pm_update_confirm.exception');
            return view('partials.errors', compact('errorMessage'));
        }
    }

    /**
     * update confirm
     * @param  Request $request
     * @param  integer  $progImportFileId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateConfirm(Request $request, $progImportFileId = null)
    {
        try {
            $data             = $request->all();
            $corpId           = Auth::user()->affiliation_id;
            $mCorp            = $this->mCorpRepository->findOrFail($corpId);
            $officialCorpName = !empty($mCorp->official_corp_name) ? $mCorp->official_corp_name : '';
            $result           = $this->pmUpdateConfirmService->updateConfirm($data, $progImportFileId, $officialCorpName);
            if ($result) {
                return redirect()->route('progress_management.show.update_end');
            }
            $errorMessage = trans('pm_update_confirm.exception');
            return view('partials.errors', compact('errorMessage'));
        } catch (Exception $exception) {
            $errorMessage = trans('pm_update_confirm.exception');
            return view('partials.errors', compact('errorMessage'));
        }
    }
    /**
     * show page update end
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateEnd()
    {
        $corpId    = Auth::user()->affiliation_id;
        $corpInfo  = $this->mCorpRepository->getFirstById($corpId);
        $pageTitle = !empty($corpInfo->official_corp_name) ? $corpInfo->official_corp_name : '';
        return view('progress_management.update_end', compact('pageTitle'));
    }
}
