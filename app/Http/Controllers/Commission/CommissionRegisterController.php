<?php

namespace App\Http\Controllers\Commission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Services\CommissionService;
use App\Services\Commission\SupportService;
use App\Services\Commission\CommissionDataService;
use App\Services\Commission\ValidateService;

class CommissionRegisterController extends Controller
{
    /**
     * @var CommissionService
     */
    protected $commissionService;
    /**
     * @var SupportService
     */
    protected $commissionSupportService;
    /**
     * @var CommissionDataService
     */
    protected $commissionDataService;
    /**
     * @var ValidateService
     */
    protected $validateService;
    /**
     * CommissionController constructor.
     * @param CommissionService $commissionService
     * @param SupportService $commissionSupportService
     * @param CommissionDataService $commissionDataService
     * @param ValidateService $validateService
     */
    public function __construct(
        CommissionService $commissionService,
        SupportService $commissionSupportService,
        CommissionDataService $commissionDataService,
        ValidateService $validateService
    ) {
        parent::__construct();
        $this->commissionService = $commissionService;
        $this->commissionSupportService = $commissionSupportService;
        $this->commissionDataService = $commissionDataService;
        $this->validateService = $validateService;
    }

    /**
     * @param Request $request
     * @param null    $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regist(Request $request, $id = null)
    {
        $errorFlg = false;

        if ($request->isMethod('post')) {
            $data = $request->input('data');
            $files = $request->file('data');
            $commissionValidate = $this->commissionService->validateRegist($data['CommissionInfo']);
            $commissionCorrValidate = $this->validateService->checkCorrespond($data['CommissionCorrespond']);
            $demandInfoValidate = $this->validateService->validateDemandInfo($data['DemandInfo']);

            if ($commissionValidate['check'] == false
                || $commissionCorrValidate['check'] == false
                || $demandInfoValidate['check'] == false) {
                $errorFlg = true;
            }

            if ($errorFlg) {
                session()->flash('error', trans('commission.check_input_item'));
            } elseif ($this->commissionDataService->regist($id, $data, $files)) {
                return redirect()->route('commission.detail', ['id' => $id]);
            }
        }

        $validationMessages = array_merge_recursive(
            $commissionValidate['validate']->messages()->toArray(),
            $commissionCorrValidate['validate']->messages()->toArray(),
            $demandInfoValidate['validate']->messages()->toArray()
        );

        $input = $this->commissionDataService->compareBeforeResponse($request->except('_token', '_method'));

        return redirect()->back()
                         ->withErrors($validationMessages)
                         ->withInput($input);
    }

    /**
     * @param $id
     * @param $datetime
     * @param $status
     * @param $responder
     * @param $failReason
     * @param $contents
     * @param $hopeDatetime
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registTelSupports($id, $datetime, $status, $responder, $failReason, $contents, $hopeDatetime)
    {
        $result = $this->commissionSupportService->registTelSupports($id, $datetime, $status, $responder, $failReason, $contents, $hopeDatetime);

        return view('commission.support', [
            'supports' => $result,
            'situation' => getDropList(config('constant.M_ITEM.TELEPHONE_SUPPORT_STATUS')),
            'prefix' => 'CommissionTelSupport'
        ]);
    }

    /**
     * @param $id
     * @param $datetime
     * @param $status
     * @param $responder
     * @param $failReason
     * @param $contents
     * @param $supportDatetime
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registVisitSupports($id, $datetime, $status, $responder, $failReason, $contents, $supportDatetime)
    {
        $result = $this->commissionSupportService->registVisitSupports($id, $datetime, $status, $responder, $failReason, $contents, $supportDatetime);

        return view(
            'commission.support',
            [
                'supports' => $result,
                'situation' => getDropList(config('constant.M_ITEM.VISIT_SUPPORT_STATUS')),
                'prefix' => 'CommissionVisitSupport'
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registOrderSupports(Request $request)
    {
        $result = $this->commissionSupportService->registOrderSupports($request->all());

        return view(
            'commission.support',
            [
                'supports' => $result,
                'situation' => getDropList(config('constant.M_ITEM.ORDER_SUPPORT_STATUS')),
                'prefix' => 'CommissionOrderSupport'
            ]
        );
    }

    /**
     * @param $id
     * @param $sup
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws Exception
     */
    public function listSupports($id, $sup)
    {
        $result = [];
        $situation = '';
        $prefix = '';

        try {
            if (!ctype_digit($id)) {
                throw new Exception(__('commission_detail.invalid_id'));
            }

            switch ($sup) {
                case 'tel':
                    $result = $this->commissionSupportService->getTelSupport($id, true);
                    $prefix = 'CommissionTelSupport';
                    $situation = getDropList(config('constant.M_ITEM.TELEPHONE_SUPPORT_STATUS'));
                    break;
                case 'visit':
                    $result = $this->commissionSupportService->getVisitSupport($id, true);
                    $prefix = 'CommissionVisitSupport';
                    $situation = getDropList(config('constant.M_ITEM.VISIT_SUPPORT_STATUS'));
                    break;
                case 'order':
                    $result = $this->commissionSupportService->getOrderSupport($id, true);
                    $prefix = 'CommissionOrderSupport';
                    $situation = getDropList(config('constant.M_ITEM.ORDER_SUPPORT_STATUS'));
                    break;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return view(
            'commission.support',
            [
                'supports' => $result,
                'situation' => $situation,
                'prefix' => $prefix,
            ]
        );
    }
}
