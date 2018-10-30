<?php

namespace App\Http\Controllers\ProgressManagement;

use App\Http\Controllers\Controller;
use App\Models\MItem;
use App\Models\ProgCorp;
use App\Models\ProgDemandInfo;
use App\Repositories\ProgImportFilesRepositoryInterface;
use App\Services\ProgCorpService;
use App\Services\ProgressManagementItemService;
use App\Services\ProgressManagementService;
use Illuminate\Http\Request;

class ProgressManagementController extends Controller
{
    //business service
    /**
     * @var ProgressManagementService
     */
    public $pMService;
    /**
     * @var ProgCorpService
     */
    public $pCorpService;
    /**
     * @var ProgImportFilesRepositoryInterface
     */
    protected $progImportFile;

    /**
     * @var ProgressManagementItemService
     */
    protected $pMItemService;

    /**
     * ProgressManagementController constructor.
     *
     * @param ProgressManagementService $pMService
     * @param ProgCorpService $pCorpService
     * @param ProgImportFilesRepositoryInterface $progImportFile
     * @param \App\Services\ProgressManagementItemService $pMItemService
     */
    public function __construct(
        ProgressManagementService $pMService,
        ProgCorpService $pCorpService,
        ProgImportFilesRepositoryInterface $progImportFile,
        ProgressManagementItemService $pMItemService
    ) {
        parent::__construct();
        $this->pMService = $pMService;
        $this->pCorpService = $pCorpService;
        $this->progImportFile = $progImportFile;
        $this->pMItemService = $pMItemService;
    }

    /**
     * index progress demand info
     * @author thaihv
     * @param  Request $request http request
     * @return  collection
     */
    public function index(Request $request)
    {
        $progFiles = $this->pMItemService->getImportFileNotDelete();
        if (session('message_del')) {
            $request->session()->flash('message', session('message_del'));
            $request->session()->forget('message_del');
        }
        $confirmDelete = __('progress_management.index.confirm_delete');
        return view('progress_management.index')->with('progFiles', $progFiles)->with('confirmDelete', $confirmDelete);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  $pCorpId
     * @return \Illuminate\Http\Response
     */
    public function adminDemandDetail(Request $request, $pCorpId)
    {
        $pageTitle = '進捗管理システム';
        $pDemandInfos = $this->pMService->adminDemandDetail($pCorpId);
        $addDemandData = $this->pMService->getProgAddDemandInfos($pCorpId);
        $limitAddDemand = config('datacustom.limit_prog_add_demand_info');
        $pageInfo = $this->pMService->getPageInfo($pDemandInfos);
        $progCorp = $this->pCorpService->getProCorById($pCorpId);
        $demandTypeUpdate = $progCorp->getDemandTypeUpdate();
        $hidClass = $progCorp->progress_flag == ProgCorp::MAIL_COLLECTED ? 'd-none' : '';
        $guideTitle = $this->pMService->getGuideTitle($progCorp->progress_flag);
        $pmCommissionStatus = ProgDemandInfo::PM_COMMISSION_STATUS;
        $diffFllags = ProgDemandInfo::PM_DIFF_LIST;
        $tax = ProgDemandInfo::PM_TAX;
        $cmOrderFailReasonList = $this->pMService->getMItemList(MItem::CONMISSION_ORDER_FAIL_REASON);
        $cmOrderFailReasonUpdate = ['' => ''];
        foreach ($cmOrderFailReasonList as $value) {
            $cmOrderFailReasonUpdate[$value['id']] = $value['category_name'];
        }
        return view('progress_management.admin_demand_detail')->with(
            compact(
                'pageTitle',
                'pDemandInfos',
                'addDemandData',
                'limitAddDemand',
                'guideTitle',
                'pCorpId',
                'progCorp',
                'pageInfo',
                'pmCommissionStatus',
                'diffFllags',
                'cmOrderFailReasonUpdate',
                'hidClass',
                'demandTypeUpdate',
                'tax'
            )
        );
    }


    /**
     * update progress demand info
     * @author thaihv
     * @param Request $request
     * @param $pDMandInfoId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateProgDemandInfo(Request $request, $pDMandInfoId)
    {
        $pDemandInfo = $request->all();
        $userUpdateInfo = $this->pMService->getUserAgent($request);
        $pDemandInfo = array_merge($userUpdateInfo, $pDemandInfo);
        $pDemandInfo = $this->pMService->handelDataUpdate($pDemandInfo);
        $updated = $this->pMService->updateProgDemandInfo($pDMandInfoId, $pDemandInfo);
        if ($updated) {
            $progDemandInfo = $this->pMService->findWithCommissionById($pDMandInfoId);
            $this->pMService->updateCommissionInfos([$progDemandInfo], 'admin');
            $this->pMService->insertLog([$progDemandInfo]);
            session()->flash('message', __('progress_management.update_message'));
            return response()->json(['code' => 200, 'message' => __('progress_management.update_message')]);
        }
        session()->flash('message', __('progress_management.update_all_fail'));
        return response()->json(['code' => 500, 'message' => __('progress_management.update_all_fail')]);
    }

    /**
     * update progress demand info
     * @author thaihv
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function multipleUpdate(Request $request)
    {
        $pDemandInfos = $request->get('pData');

        $userUpdateInfo = $this->pMService->getUserAgent($request);
        $numOfRowUpdated = 0;
        $progIds = [];
        foreach ($pDemandInfos as $value) {
            $pDemandInfo = array_merge($userUpdateInfo, $value);
            $pDMandInfoId = $pDemandInfo['pId'];
            $pDemandInfo = $this->pMService->handelDataUpdate($pDemandInfo);
            unset($pDemandInfo['pId']);
            $updated = $this->pMService->updateProgDemandInfo($pDMandInfoId, $pDemandInfo);
            if ($updated) {
                $progIds[] = $pDMandInfoId;
                $numOfRowUpdated++;
            }
        }
        $rowMessage = ' (' . $numOfRowUpdated . '/' . count($pDemandInfos);
        if ($numOfRowUpdated > 0) {
            // update log
            $progDemandInfos = $this->pMService->findByIds($progIds);
            if (!empty($progDemandInfos)) {
                $this->pMService->updateCommissionInfos($progDemandInfos, 'admin');
                $this->pMService->insertLog($progDemandInfos);
            }
            session()->flash('message', __('progress_management.update_all_success'));
            return response()->json([
                'code' => 200,
                'message' => __('progress_management.update_all_success') . $rowMessage
            ]);
        }
        session()->flash('message', __('progress_management.update_prog_fail'));
        return response()->json([
            'code' => 500,
            'message' => __('progress_management.update_prog_fail') . $rowMessage
        ]);
    }

    /**
     * reacquisition
     * @author thaihv
     * @param  Request $request http request
     * @param  integer $pDMandInfoId progress_demand_info_id
     * @return json status and message
     */
    public function reacquisition(Request $request, $pDMandInfoId)
    {
        $userUpdateInfo = $this->pMService->getUserAgent($request);
        $updated = $this->pMService->reacquisition($pDMandInfoId, $userUpdateInfo);
        if ($updated) {
            session()->flash('message', __('progress_management.reacqure_success'));
            return response()->json(['code' => 200, 'message' => __('progress_management.reacqure_success')]);
        }
        session()->flash('message', __('progress_management.reacqure_fail'));
        return response()->json(['code' => 500, 'message' => __('progress_management.reacqure_fail')]);
    }

    /**
     * add progress ad demand info
     * @author thaihv
     *
     * @param Request $request
     * @param $pCorpId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function progAddDemandInfoDetail(Request $request, $pCorpId)
    {
        $proCorp = $this->pCorpService->getProgCorpById($pCorpId);
        if (!$proCorp) {
            return response()->json(['code' => 500, 'message' => __('progress_management.corp_not_found')]);
        }
        $data = $request->get('pData');
        $userAgent = $this->pMService->getUserAgent($request);
        foreach ($data as $key => $value) {
            $pData = array_merge($value, $userAgent);
            $pData['prog_corp_id'] = $proCorp->id;
            $pData['prog_import_file_id'] = $proCorp->prog_import_file_id;
            $pData['corp_id'] = $proCorp->corp_id;
            $data[$key] = $pData;
        }
        $insertedIds = $this->pMService->insertUpdateProgAddDemandInfo($data);
        if (!empty($insertedIds)) {
            $progAdds = $this->pMService->getProgAddDemandByIds($insertedIds);
            $this->pMService->insertLog($progAdds, 'add_demand_info');
            session()->flash('message', __('progress_management.add_demand_success'));
            return response()->json(['code' => 200, 'message' => __('progress_management.add_demand_success')]);
        }
        session()->flash('message', __('progress_management.add_demand_fail'));
        return response()->json(['code' => 500, 'message' => __('progress_management.add_demand_fail')]);
    }

    /**
     * get progress corp
     * @author  thaihv
     * @param Request $request
     * @param $fileId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function corpIndex(Request $request, $fileId)
    {
        $importFile = $this->progImportFile->findNotDeleteById($fileId);
        if (!$importFile) {
            return redirect()->route('progress.management.index')->with('message', __('progress_management.err_msg'));
        }
        $searchQuery = $request->all();
        foreach ($searchQuery as $key => $value) {
            if ($key != 'corp_name') {
                $searchQuery[$key] = trim($value);
            }
        }
        if (!empty($searchQuery)) {
            session(['searchQuery' => json_encode($searchQuery)]);
        } else {
            session()->forget('searchQuery');
        }
        if (isset($searchQuery['corp_id']) && strlen($searchQuery['corp_id']) > 7) {
            $progCorps = collect([]);
        } else {
            $progCorps = $this->pCorpService->getProgCorpWithHoliday($fileId, $searchQuery);
        }
        $progressFlagList = getDropList(MItem::PROGRESS_STATUS_CATEGORY);
        $notReplyFlagList = getDropList(MItem::PROGRESS_STATUS_REPLY_RESULT_CATEGORY);
        $contactTypeList = getDropList(MItem::PROGRESS_DELIVERY_CATEGORY);
        $callBackPhoneFlag = getDropList(MItem::PROGRESS_BACK_PHONE_CATEGORY);
        // js data
        $baseUrl = route('progress.corpIndex', $fileId);
        $confirmUpdateMsg = __('progress_management.confirm_update');
        $mailEmptyMsg = __('progress_management.mail_empty_message');
        $willSendEmailMsg = __('progress_management.will_send_email');
        $willSendFaxMsg = __('progress_management.will_send_fax');
        $faxEmptyMsg = __('progress_management.fax_empty_message');
        $faxNumberMsg = __('progress_management.fax_number_msg');
        $mailNumberMsg = __('progress_management.mail_number_msg');
        $willSendFaxMail = __('progress_management.will_send_fax_mail');
        $sendBulkMail = __('progress_management.send_bulk_mail');
        $sendBulkFax = __('progress_management.send_bulk_fax');
        $noChecked = __('progress_management.no_checked');
        $emailNotEnter = __('progress_management.email_not_enter');
        $faxNotEnter = __('progress_management.fax_not_enter');
        $bulkMailFax = __('progress_management.send_bulk_mail_fax');
        $corpIdNull = __('progress_management.corp_id_null');

        return view(
            'progress_management.corp_index',
            compact(
                'importFile',
                'progCorps',
                'progressFlagList',
                'notReplyFlagList',
                'contactTypeList',
                'callBackPhoneFlag',
                'searchQuery',
                'confirmUpdateMsg',
                'mailEmptyMsg',
                'willSendEmailMsg',
                'willSendFaxMsg',
                'faxEmptyMsg',
                'faxNumberMsg',
                'willSendFaxMail',
                'baseUrl',
                'sendBulkMail',
                'noChecked',
                'emailNotEnter',
                'sendBulkFax',
                'faxNotEnter',
                'bulkMailFax',
                'corpIdNull',
                'fileId',
                'mailNumberMsg'
            )
        );
    }

    /**
     * update progress corp
     * @author  thaihv
     * @param Request $request
     * @param $fileId
     * @param $progCorpId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProgressCorp(Request $request, $fileId, $progCorpId)
    {
        $searchQuery = json_decode(session()->pull('searchQuery'));
        if (empty($searchQuery)) {
            $searchQuery = [];
        }
        $searchQuery = (array)$searchQuery;
        $searchQuery['fileId'] = $fileId;
        $progCorpInput = $request->except('_token', '_method');
        $corpName = $progCorpInput['official_corp_name'];
        unset($progCorpInput['official_corp_name']);
        $updated = $this->pCorpService->updateProgressCorp($progCorpId, $progCorpInput);
        if ($updated) {
            $message = __('progress_management.update_prog_success', ['coprName' => $corpName]);
        } else {
            $message = __('progress_management.update_prog_fail', ['coprName' => $corpName]);
        }
        session()->flash('message',$message);
        return response()->json(['status' => 200, 'message' => $message]);
    }

    /**
     * @param ProgressManagementItemService $itemService
     * @return $this
     */
    public function itemEdit(ProgressManagementItemService $itemService)
    {
        $item = $itemService->getProgressItem();
        if ($item !== null) {
            return view('progress_management.item_edit')->with(compact('item'));
        } else {
            abort(404);
        }
    }

    /**
     * @param $progressItemId
     * @param Request $request
     * @param ProgressManagementItemService $itemService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItemEdit($progressItemId, Request $request, ProgressManagementItemService $itemService)
    {
        $data = $request->all();
        $result = $itemService->updateItem($progressItemId, $data);
        if ($result == true) {
            $message['type'] = 'success';
            $message['text'] = trans('progress_management.item_update.success_message');
        } else {
            $message['type'] = 'error';
            $message['text'] = trans('progress_management.item_update.error_message');
        }
        return redirect()->back()->with('flash_message', $message);
    }

    /**
     * @param $fileId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fileDelete($fileId)
    {
        $message = $this->pMItemService->deleteFile($fileId);
        return redirect()->route('progress.management.index')->with('message_del', $message);
    }
}
