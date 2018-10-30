<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bill\RegisterPaymentHistoryRequest;
use App\Http\Requests\BillDetailRequest;
use App\Http\Requests\BillSaveRequest;
use App\Http\Requests\MCorpSearchRequest;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\BillListService;
use App\Services\BillService;
use App\Services\ExportExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BillController extends Controller
{

    /**
     * @var BillService
     */
    protected $billService;
    /**
     * @var BillListService
     */
    protected $billListService;

    /**
     * @var ExportExcelService
     */
    protected $exportExcelService;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;

    /**
     * BillController constructor.
     * @param BillService $billService
     * @param BillListService $billListService
     * @param ExportExcelService $excelService
     * @param MCorpRepositoryInterface $mCorpRepository
     */
    public function __construct(
        BillService $billService,
        BillListService $billListService,
        ExportExcelService $excelService,
        MCorpRepositoryInterface $mCorpRepository
    ) {
        parent::__construct();
        $this->billService = $billService;
        $this->billListService = $billListService;
        $this->exportExcelService = $excelService;
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        return view('bill.index');
    }

    /**
     * show view mCorpList
     *
     * @return View
     */
    public function mCorpList()
    {
        $isBackPage = session()->get(self::$sessionKeyForCheckBillSearch);
        if ($isBackPage) {
            session()->forget(self::$sessionKeyForCheckBillSearch);
            $afterEditSession = session()->get(self::$sessionKeyForSearchAfterEdit);
            if ($afterEditSession) {
                session()->forget(self::$sessionKeyForSearchAfterEdit);
            }
        } else {
            $afterEditSession = null;
        }

        $defaultDisplay = true;
        $billingStatus = $this->billListService->getBillStatus();
        return view('bill.mcorp_list', compact('billingStatus', 'defaultDisplay', 'afterEditSession'));
    }

    /**
     * search data function
     *
     * @param  MCorpSearchRequest $request
     * @return string
     * @throws \Throwable|json
     */
    public function mCorpSearch(MCorpSearchRequest $request)
    {
        try {
            $page = getDivValue('list_limit', 'perPage');
            $dataForBillSearch['bill_status'] = $request->bill_status;
            if ($request->session()->has(self::$sessionKeyForBillMcorpSearch)) {
                $request->session()->forget(self::$sessionKeyForBillMcorpSearch);
            }
            $request->session()->push(self::$sessionKeyForBillMcorpSearch, $dataForBillSearch);
            $results = $this->billListService->searchMCorpAndPaging($request->all(), $page);
            return response()->json(\view('bill.component.search_mcorp', compact('results'))->render());
        } catch (\Exception $exception) {
            return response()->json(\view('bill.component.search_mcorp')->render());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function getBillList(Request $request)
    {
        if (empty($request->id)) {
            return redirect()->route('bill.mCorpList');
        }
        $id = $request->id;
        $dataSession = $request->session()->get(self::$sessionKeyForBillMcorpSearch);
        $billSession[0]['bill_status'] = ( $dataSession != null ) ? $dataSession[0]['bill_status'] : BillListService::BILL_STATUS_DEFAULT ;
        $billList = $this->billListService->getBillList($billSession, $id, $request->all());
        $bill = $this->mCorpRepository->findByAffiliationId($id);
        $lists = $this->billListService->getListByCategoryItem();
        $oldList = $this->billListService->getFirstOldList();
        $billingStatus = getDropdownList($lists, $oldList);
        $categoryList = $this->billListService->getMCategoryList();
        return view('bill.bill_list', compact('id', 'billingStatus', 'billSession', 'billList', 'categoryList', 'bill'));
    }

    /**
     * search bill info data
     *
     * @param  Request $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postBillSearch(Request $request, $id)
    {
        try {
            $dataSession = $request->session()->get(self::$sessionKeyForBillMcorpSearch);
            $billSession[0]['bill_status'] = ( $dataSession != null ) ? $dataSession[0]['bill_status'] : BillListService::BILL_STATUS_DEFAULT ;
            if (!empty($request->bill_status)) {
                $billSession[0]['bill_status'] = $request->bill_status;
                $dataForBillSearch['bill_status'] = $request->bill_status;
                if ($request->session()->has(self::$sessionKeyForBillMcorpSearch . $id)) {
                    $request->session()->forget(self::$sessionKeyForBillMcorpSearch . $id);
                }
                $request->session()->push(self::$sessionKeyForBillMcorpSearch . $id, $dataForBillSearch);
            } else {
                if ($request->session()->has(self::$sessionKeyForBillMcorpSearch . $id)) {
                    $billSession = $request->session()->get(self::$sessionKeyForBillMcorpSearch . $id);
                }
            }
            $billList = $this->billListService->getBillList($billSession, $id, $request->all());
            $lists = $this->billListService->getListByCategoryItem();
            $oldList = $this->billListService->getFirstOldList();
            $billingStatus = getDropdownList($lists, $oldList);
            $categoryList = $this->billListService->getMCategoryList();
            return response()->json(
                \view(
                    'bill.component.bill_search',
                    compact('id', 'billingStatus', 'billSession', 'billList', 'categoryList')
                )->render()
            );
        } catch (\Exception $exception) {
            $errorMessage = trans('mcorp_list.exception');
            return \response()->json($errorMessage);
        }
    }

    /**
     * save bill_info data
     * @param BillSaveRequest $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function postBillSave(BillSaveRequest $request)
    {
        try {
            $resultFlg = $this->billListService->checkBillModified($request);
            if (!$resultFlg) {
                $request->session()->flash('alert-error', __(trans('bill_list.not_match')));
                return redirect()->back();
            }
            $resultFlg = $this->billListService->updateBillInfo(
                $request->all('id', 'fee_payment_price', 'fee_payment_balance', 'target')
            );
            if ($resultFlg) {
                $request->session()->flash('alert-success', __(trans('bill_list.update_success')));
                return redirect()->route('bill.mCorpList');
            } else {
                $request->session()->flash('alert-error', __(trans('bill_list.update_error')));
                return redirect()->back();
            }
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * export excel function
     *
     * @param  BillSaveRequest $request
     * @param  integer $id
     * @return void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function postBillDownload(BillSaveRequest $request, $id)
    {
        $mCorpData['mcorp_id'] = $id;
        $mCorpData['official_corp_name'] = $request['official_corp_name'];
        $ids = [];
        foreach ($request['target'] as $target) {
            if ($request['bill_status'] == getDivValue('bill_status', 'not_issue')) {
                $this->billListService->billStatusChange(
                    $request['id'][$target],
                    getDivValue('bill_status', 'issue'),
                    date('Y/m/d')
                );
            }
            $ids[$target] = $request['id'][$target];
        }
        $billList = $this->billListService->getBillListDownload($ids);
        $mCorpData['past_bill_price'] = $this->billListService->getBillPastIssueList(
            $ids,
            $mCorpData['mcorp_id'],
            $request['bill_status']
        );
        $this->exportExcelService->exportBillExcel($billList, $mCorpData);
    }

    /**
     * order money correspond function
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderMoneyCorrespond(Request $request)
    {
        $corpId = $request->get('corp_id');
        $searchValue = $request->get('searchValue');
        $order = $request->get('order');

        $listMoneyData = $this->billListService->getMoneyCorrespondDataInitial(
            $corpId,
            $searchValue,
            $order
        );
        $listMoneyData = $listMoneyData->toarray()['data'];

        foreach ($listMoneyData as &$money) {
            $money['payment_amount'] = yenFormat2($money['payment_amount']);
        }

        return response()->json(
            [
                'status' => 200,
                'listMoneyData' => $listMoneyData
            ]
        );
    }

    /**
     * get list money data, payment date, payment amount
     *
     * @param  int $corpId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function moneyCorrespond($corpId = 1)
    {
        $listMoneyData = $this->billListService->getMoneyCorrespondDataInitial(
            $corpId,
            request('search_nominee'),
            request('sort')
        );

        $paymentDate = request('paymentDate');
        $nominee = request('nominee');
        $paymentAmount = request('paymentAmount');

        return view(
            'bill.money_correspond',
            compact(
                'listMoneyData',
                'corpId',
                'paymentDate',
                'nominee',
                'paymentAmount'
            )
        );
    }

    /**
     * create money correspond
     *
     * @param  RegisterPaymentHistoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moneyAddDeposit(RegisterPaymentHistoryRequest $request)
    {
        $this->billListService->create(
            array_merge(
                $request->only('payment_date', 'nominee', 'payment_amount', 'corp_id'),
                [
                    'created_user_id' => Auth::user()['user_id'],
                    'modified_user_id' => Auth::user()['user_id'],
                    'created' => $this->billListService->getDateTimeNow(),
                    'modified' => $this->billListService->getDateTimeNow()
                ]
            )
        );
        $paymentDate = $request->get('payment_date');
        $nominee = $request->get('nominee');
        $paymentAmount = $request->get('payment_amount');

        $resultReturn = [
            "corp_id" => request('corp_id'),
            'paymentDate' => $paymentDate,
            'nominee' => $nominee,
            'paymentAmount' => $paymentAmount
        ];

        $request->session()->flash('msg_create_deposit', __('money_correspond.success_create_deposit'));
        return redirect()->route('bill.moneyCorrespond', $resultReturn);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDeposit()
    {
        if ($this->billListService->deleteRecord(request('id'))) {
            return response()->json([], 200);
        }
        return response()->json([], 500);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function output()
    {
        $list = \Session::get(__('bill.KEY_SESSION_TIME_CHOOSEN'));
        $fromDate = !empty($list[0]) ? $list[0] : '';
        $toDate = !empty($list[1]) ? $list[1] : '';
        $bHasError = \Session::has(__('bill.KEY_SESSION_ERROR_DOWNLOAD_BILL_CSV'));
        return view('bill.output', [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'bHasError' => $bHasError
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadBillCsv(Request $request)
    {
        $fromDate = $request['from_complete_date'];
        $toDate = $request['to_complete_date'];
        $check = $request['fee_billing_date'];
        $checkFee = false;
        if ($check == 'on') {
            $checkFee = true;
        }
        if ((!is_null($fromDate) && strlen(trim($fromDate)) > 0) || (!is_null($toDate) && strlen(trim($toDate)) > 0)) {
            $file = $this->billService->getDataForDownloadBill($fromDate, $toDate, $checkFee);
            if (!is_null($file)) {
                return $file->download('csv');
            } else {
                \Session::flash(__('bill.KEY_SESSION_TIME_CHOOSEN'), [$fromDate, $toDate]);
                \Session::flash(__('bill.KEY_SESSION_ERROR_DOWNLOAD_BILL_CSV'), __('bill.output_message_no_data'));
            }
        } else {
            \Session::flash(__('bill.KEY_SESSION_ERROR_DOWNLOAD_BILL_CSV'), __('bill.message_no_input'));
        }
        return redirect()->route('bill.bill_output');
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function billDetail($id, Request $request)
    {
        $result = $this->billService->getDataBillDetail((int)$id);
        $mItem = $this->billService->getMItemBillDetail();
        $data = $result;
        $request->session()->forget('message');
        $request->session()->forget('class');
        $request->session()->forget('_old_input');
        return view('bill.bill_detail', compact('result', 'mItem', 'data'));
    }

    /**
     * @param BillDetailRequest $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function billDetailUpdate(BillDetailRequest $request, $id)
    {
        $dataRequest = $request->all();
        Session::flashInput($request->input());
        $result = $this->billService->getDataBillDetail((int)$id);
        $mItem = $this->billService->getMItemBillDetail();
        $dataRequest['bill_infos_indivisual_billing'] = isset($dataRequest['bill_infos_indivisual_billing'])
            ? $dataRequest['bill_infos_indivisual_billing'] : 0;
        if ($dataRequest['regist']) {
            if ($this->billService->checkBillModified($id, $dataRequest['bill_infos_modified'])) {
                $dataUpdateBill = $this->billService->updateBillDetail($id, $dataRequest);
                Session::flash('message', $dataUpdateBill['message']);
                Session::flash('class', $dataUpdateBill['class']);
            } else {
                Session::flash('message', trans('bill.modified_not_check'));
                Session::flash('class', 'error');
            }
            $data = $dataRequest;
        } else {
            $data = $result;
        }
        return view('bill.bill_detail', compact('result', 'mItem', 'data'));
    }

    /**
     * Save session mcorp search for bill
     * @param Request $request
     */
    public function saveSessionBill(Request $request)
    {
        $request->session()->put(self::$sessionKeyForSearchAfterEdit, $request->except('_token'));
    }

    /**
     * Save session mcorp search for bill
     * @param Request $request
     */
    public function checkSessionSearch(Request $request)
    {
        $request->session()->put(self::$sessionKeyForCheckBillSearch, true);
    }
}
