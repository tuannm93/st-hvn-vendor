<?php

namespace App\Http\Controllers\ProgressManagement;

use App\Http\Controllers\Controller;
use App\Models\MItem;
use App\Models\ProgDemandInfo;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\ProgCorpService;
use App\Services\ProgressManagementAffService;
use App\Services\ProgressManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressManagementAffUserController extends Controller
{
    /**
     * @var ProgressManagementService
     */
    public $pMService;
    /**
     * @var ProgCorpService
     */
    public $pCorpService;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var ProgressManagementAffService
     */
    protected $pMAffService;

    /**
     * ProgressManagementAffUserController constructor.
     * @param ProgressManagementService $pMService
     * @param ProgCorpService $pCorpService
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param ProgressManagementAffService $pMAffService
     */
    public function __construct(
        ProgressManagementService $pMService,
        ProgCorpService $pCorpService,
        MCorpRepositoryInterface $mCorpRepository,
        ProgressManagementAffService $pMAffService
    ) {
        parent::__construct();
        $this->pMService = $pMService;
        $this->pCorpService = $pCorpService;
        $this->mCorpRepository = $mCorpRepository;
        $this->pMAffService = $pMAffService;
    }

    /**
     * @param Request $request
     * @param null $fileId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function affDemandDetail(Request $request, $fileId = null)
    {
        if (!is_numeric($fileId)) {
            return abort(404);
        }

        if(!$this->pMAffService->getProgImportFile($fileId)){
            return view('errors.401');
        }

        if (!isset($fileId)) {
            return view('progress_management.demand_detail.aff_demand_detail', [
                'screen' => __('demand_detail.status_screen.no_file_id'),
                'id' => $fileId
            ]);
        }
        $tax = ProgDemandInfo::PM_TAX;
        $corpId = Auth::user()->affiliation_id;
        $diffFlags = ProgDemandInfo::PM_DIFF_LIST_DEMAND_DETAIL;
        $commissionStatusList = ProgDemandInfo::PM_COMMISSION_STATUS;
        array_unshift($commissionStatusList, '');
        $pc = $this->pCorpService->getProgCorpByFlag($corpId, $fileId, 2);
        $corpInfo = $this->mCorpRepository->getFirstById($corpId);
        if (empty($pc)) {
            return view('progress_management.demand_detail.aff_demand_detail', [
                'screen' => __('demand_detail.status_screen.no_access_role'),
                'corpInfo' => $corpInfo,
                'id' => $fileId
            ]);
        }

        $progTmp = $this->pCorpService->getTmp($pc->id);
        $progData = $this->pCorpService->getProgDemandInfos($pc->id);
        $oldProdData = $progData;
        $pageInfo = $this->pMService->getPageInfo($progData['ProgDemandInfoPaginate']);
        $dataPaginate = $progData['ProgDemandInfoPaginate'];
        $progData = $this->pMAffService->parseProgData($progData, $progTmp);
        $data['ProgDemandInfo'] = $progData['ProgDemandInfo'];

        if ($progTmp) {
            $data['ProgAddDemandInfo'] = $this->pCorpService->getTmp($pc->id, 'add_demand_info');
            if (empty($data['ProgAddDemandInfo']) && !empty($progData['ProgAddDemandInfo'])) {
                $data['ProgAddDemandInfo'] = $progData['ProgAddDemandInfo'];
            }
            $data['ProgImportFile'] = $this->pCorpService->getTmp($pc->id, 'file');
            $data['ProgDemandInfoOther'] = $this->pCorpService->getTmp($pc->id, 'other');
        } else {
            $data = $oldProdData;
        }

        $pageTitle = $corpInfo->official_corp_name . '様 案件一覧';
        $pi = $this->pCorpService->getProgItem();
        $commissionOrderFailReasonList = getDropList(MItem::COMMISSION_ORDER_FAIL_REASON);
        array_unshift($commissionOrderFailReasonList, "");

        $dataReturn = [
            'tax' => $tax,
            'id' => $fileId,
            'corpInfo' => $corpInfo,
            'pageTitle' => $pageTitle,
            'prog_corp_id' => $pc->id,
            'pi' => $pi,
            'dataPaginate' => $dataPaginate,
            'pageInfo' => $pageInfo,
            'data' => $data,
            'diffFlags' => $diffFlags,
            'commissionStatus' => $commissionStatusList,
            'commissionOrderFailReasonList' => $commissionOrderFailReasonList,
            'screen' => __('demand_detail.status_screen.ok'),
        ];

        if ($request->ajax()) {
            $dataPost = $request->all();
            if (!isset($dataPost['submitBack'])) {
                $this->pMAffService->setTmp($dataPost, $dataPost['prog_corp_id']);
            }
            return view('progress_management.component.ajax_form_demand_detail', $dataReturn);
        }

        return view('progress_management.demand_detail.aff_demand_detail', $dataReturn);
    }

    /**
     * get save info and redirect to update confirm page
     * @param Request $request
     * @return $dataPost['ProgImportFile']['file_id']
     */
    public function redirectToUpdateConfirm(Request $request)
    {
        $dataPost = $request->all();
        $this->pMAffService->setTmp($dataPost, $dataPost['prog_corp_id']);

        return $dataPost['ProgImportFile']['file_id'];
    }

    /**
     * get save session
     * @param Request $request
     * @return null
     */
    public function saveSession(Request $request)
    {
        $dataPost = $request->all();
        $this->pMAffService->setTmp($dataPost, $dataPost['prog_corp_id']);
        return '';
    }
}
