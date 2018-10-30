<?php

namespace App\Http\Controllers\Commission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Services\Commission\CommissionAppService;
use App\Services\Commission\SearchService;
use App\Services\Commission\ExportCsvService;
use App\Services\Commission\ApprovalService;
use App\Services\Commission\CorrespondService;

use App\Http\Requests\AutoCommissionCorrespondsRequest;
use App\Http\Requests\CommissionApprovalRequest;
use App\Http\Requests\CommissionSearchRequest;

class CommissionController extends Controller
{
    /**
     * @var CorrespondService
     */
    protected $commissionCorrespondService;
    /**
     * @var SearchService
     */
    protected $commissionSearchService;
    /**
     * @var ExportCsvService
     */
    protected $commissionExportService;

    /**
     * @var CommissionAppService
     */
    protected $commissionAppService;
    /**
     * @var ApprovalService
     */
    protected $commissionApprovalService;
    /**
     * CommissionController constructor.
     * @param CorrespondService $correspondService
     * @param CommissionAppService $commissionAppService
     * @param SearchService $commissionSearchService
     * @param ExportCsvService $commissionExportService
     * @param ApprovalService $commissionApprovalService
     */
    public function __construct(
        CorrespondService $correspondService,
        CommissionAppService $commissionAppService,
        SearchService $commissionSearchService,
        ExportCsvService $commissionExportService,
        ApprovalService $commissionApprovalService
    ) {
        parent::__construct();
        $this->commissionCorrespondService = $correspondService;
        $this->commissionAppService = $commissionAppService;
        $this->commissionSearchService = $commissionSearchService;
        $this->commissionExportService = $commissionExportService;
        $this->commissionApprovalService = $commissionApprovalService;
    }

    /**
     * Get list commission information without search filter
     *
     * @param  Request $request
     * @param  null    $affiliationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $affiliationId = null)
    {
        $dataRequest = $request->all();
        $dataSession['isMobile'] = utilIsMobile($request->header('user-agent'));
        $request->session()->forget(self::$sessionKeyForCommissionSearch);
        return $this->indexOrSearchCommission($dataRequest, $dataSession, $affiliationId);
    }

    /**
     * Get list commission information with search filter
     *
     * @param  Request $request
     * @param  null    $affiliationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request, $affiliationId = null)
    {
        $dataSession = $request->session()->get(self::$sessionKeyForCommissionSearch)[0];
        $dataRequest = $request->all();
        $method = $request->isMethod('post') ? 'post' : 'get';
        $dataSession['isMobile'] = utilIsMobile($request->header('user-agent'));
        return $this->indexOrSearchCommission($dataRequest, $dataSession, $affiliationId, $method);
    }

    /**
     * Call action search or export csv.
     *
     * @param  CommissionSearchRequest $request
     * @param  null                    $affiliationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws Exception
     */
    public function postSearch(CommissionSearchRequest $request, $affiliationId = null)
    {
        if (!empty($request->csv_out)) {
            return $this->export($request, $affiliationId);
        }

        if (empty($affiliationId) && $request->input('affiliationId') != 'none') {
            $affiliationId = $request->input('affiliationId');
        }
        $dataRequest = $request->all();
        $dataRequest['isMobile'] = utilIsMobile($request->header('user-agent'));
        $dataRequest['display'] = true;

        $request->session()->forget(self::$sessionKeyForCommissionSearch);
        $request->session()->push(self::$sessionKeyForCommissionSearch, $dataRequest);
        $method = $request->isMethod('post') ? 'post' : 'get';
        if ($affiliationId == 'none') {
            return $this->indexOrSearchCommission(null, $dataRequest, null, $method);
        } else {
            return $this->indexOrSearchCommission(null, $dataRequest, $affiliationId, $method);
        }
    }

    /**
     * Get list data and export to csv file
     *
     * @param  Request $request
     * @param  null    $affiliationId
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    private function export(Request $request, $affiliationId = null)
    {
        $dataRequest = $request->all();
        return $this->commissionExportService->exportCsv($dataRequest, $affiliationId);
    }

    /**
     * Correspond Edit
     *
     * @param  integer $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function correspondEdit($id)
    {
        $item = $this->commissionCorrespondService->findById($id);
        $userList = [];
        if ($item == null) {
            return abort(404);
        }
        if ($item->rits_responders) {
            $userList = $this->commissionCorrespondService->getListUser();
        }

        return view('commission.history_input', ['commission' => $item,'userList' => $userList]);
    }

    /**
     * Correspond update
     *
     * @param  AutoCommissionCorrespondsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function correspondUpdate(AutoCommissionCorrespondsRequest $request)
    {
        $data = $request->only('id', 'rits_responders', 'responders', 'corresponding_contens', 'correspond_datetime', 'modified');
        $result = $this->commissionCorrespondService->update($data);

        return response()->json($result);
    }

    /**
     * Update approvals.status when user approval or rejected
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  \App\Http\Requests\CommissionApprovalRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approval(CommissionApprovalRequest $request)
    {
        try {
            /* Find approval */
            $approval = $this->commissionApprovalService->getApprovalById($request->input('approval_id'));
            /* If approval not find, redirect 404 */
            if ($approval == null) {
                abort(404);
            }
            $approval = $approval->toArray();
            $commissionApp = $this->commissionApprovalService->getItemById($approval['relation_application_id']);
            /* If application_user_id == this->user then redirect back with message */
            if ($approval['application_user_id'] == $this->getUser()['user_id']) {
                $request->session()->flash('error_message', trans('commission.message_approval_error_user'));

                return redirect(route('report.applicationAdmin'));
            }

            $result = $this->commissionApprovalService->approval($approval['id'], $commissionApp, $request->input('action_name'), $this->getUser());

            if ($result) {
                if ($request->input('action_name') == 'rejected') {
                    $request->session()->flash('success_message', trans('commission.message_rejected_success'));
                } else {
                    $request->session()->flash('success_message', trans('commission.message_approval_success'));
                }
            } else {
                $request->session()->flash('error_message', trans('commission.message_approval_error_update'));

                return redirect(route('report.applicationAdmin'));
            }

            return redirect()->route('commission.detail', ['id' => $commissionApp->commission_id, 'app' => '#app']);
        } catch (\Exception $exception) {
            abort('500');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function application(Request $request)
    {
        $data = $request->input('data');

        if ($request->isMethod('post')) {
            $this->commissionAppService->registerApplication($data);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Call service get list commission info.
     * @param null $dataRequest
     * @param null $dataSession
     * @param null $affiliationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function indexOrSearchCommission($dataRequest = null, $dataSession = null, $affiliationId = null, $method = null)
    {
        $dataResponse = $this->commissionSearchService->search($dataRequest, $dataSession, $affiliationId);

        if (count($dataResponse) > 0) {
            if ($method == 'post') {
                return view('commission.component.index.ajax_content', $dataResponse);
            }
            return view('commission.index', $dataResponse);
        } else {
            return abort(500);
        }
    }
}
