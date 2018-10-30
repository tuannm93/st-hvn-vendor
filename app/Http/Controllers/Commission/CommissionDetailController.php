<?php

namespace App\Http\Controllers\Commission;

use App\Http\Controllers\Controller;
use App\Services\Commission\CalculatorService;
use App\Services\Commission\CommissionDataService;
use App\Services\Commission\CommissionDetailService;
use App\Services\Commission\CommissionFileService;
use App\Services\Commission\CorrespondService;
use App\Services\Commission\SupportService;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Services\Demand\DemandExtendInfoService;

class CommissionDetailController extends Controller
{
    /**
     * @var CorrespondService
     */
    protected $commissionCorrespondService;
    /**
     * @var CommissionDetailService
     */
    protected $commissionDetailService;
    /**
     * @var SupportService
     */
    protected $commissionSupportService;
    /**
     * @var CommissionDataService
     */
    protected $commissionDataService;
    /**
     * @var CommissionFileService
     */
    protected $commissionFileService;
    /**
     * @var CalculatorService
     */
    protected $commissionCalculatorService;
    /**
     * @var DemandExtendInfoService $demandExtendInfoService
     */
    protected $demandExtendInfoService;

    /**
     * CommissionController constructor.
     * @param CorrespondService $correspondService
     * @param CommissionDetailService $commissionDetailService
     * @param SupportService $commissionSupportService
     * @param CommissionDataService $commissionDataService
     * @param CommissionFileService $commissionFileService
     * @param CalculatorService $commissionCalculatorService
     * @param DemandExtendInfoService $demandExtendInfoService
     */
    public function __construct(
        CorrespondService $correspondService,
        CommissionDetailService $commissionDetailService,
        SupportService $commissionSupportService,
        CommissionDataService $commissionDataService,
        CommissionFileService $commissionFileService,
        CalculatorService $commissionCalculatorService,
        DemandExtendInfoService $demandExtendInfoService
    ) {
        parent::__construct();
        $this->commissionCorrespondService = $correspondService;
        $this->commissionDetailService = $commissionDetailService;
        $this->commissionSupportService = $commissionSupportService;
        $this->commissionDataService = $commissionDataService;
        $this->commissionFileService = $commissionFileService;
        $this->commissionCalculatorService = $commissionCalculatorService;
        $this->demandExtendInfoService = $demandExtendInfoService;
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    public function index($id = null)
    {
        if (empty($id)) {
            return redirect()->route('commission.index');
        }

        $user = Auth::user();
        $auth = $user->auth;
        $affiliationId = $user->affiliation_id;
        $userId = $user->user_id;

        $this->commissionDetailService->updateCommissionData($id);
        $commissionData = $this->commissionDetailService->getCommissionData($id);

        $this->abortPage404WhenAccessIndex($commissionData, $auth);

        $commissionData = $this->commissionCalculatorService->calculateBillPrice($commissionData);
        $taxRate = $this->commissionDetailService->getTaxRates($commissionData['CommissionInfo__complete_date']);
        $fileUrl = $this->commissionFileService->getFileUrl($commissionData['DemandInfo__id']);
        $historyData = $this->commissionCorrespondService->findByIdWithUserName($id);
        $demandAttachedFiles = $this->commissionDetailService->getDemandAttachedFiles($commissionData['DemandInfo__id']);

        /* JBR */
        $commissionData = $this->commissionDetailService->getDefaultJbrInfo($commissionData);
        /* support */
        $commissionData = $this->commissionSupportService->getSupport($id, $commissionData);
        /* address */
        $addressDisclosure = $this->commissionDetailService->getDisclosureData('address_disclosure');
        /* tel */
        $telDisclosure = $this->commissionDetailService->getDisclosureData('tel_disclosure');

        $commissionAlert = $this->getCommissionAlert($commissionData);
        $mCommissionAlertSettingsTel = $commissionAlert['commissionAlertSettingsTel'];
        $mCommissionAlertSettingsVisit = $commissionAlert['commissionAlertSettingsVisit'];
        $mCommissionAlertSettingsOrder = $commissionAlert['commissionAlertSettingsOrder'];


        $userList = $this->commissionDataService->getMUsers();
        $mCommissionAlertSettingsTel['display_time'] = $this->getCommissionAlertSettingsTelDate($commissionData, $mCommissionAlertSettingsTel)->format('Y-m-d H:i:s');
        $mCommissionAlertSettingsVisit['display_time'] = $this->getCommissionAlertSettingsVisitDate($commissionData, $mCommissionAlertSettingsVisit)->format('Y-m-d H:i:s');
        $mCommissionAlertSettingsOrder['display_time'] = $this->getCommissionAlertSettingsOrderDate($commissionData, $mCommissionAlertSettingsOrder)->format('Y-m-d H:i:s');
        $mSites = $this->commissionDetailService->getMsiteById($commissionData['DemandInfo__site_id']);
        $visitTime = $this->commissionDetailService->getVisitTime($commissionData['CommissionInfo__commission_visit_time_id']);
        $resultTime = $this->getVisitTime($commissionData, $visitTime);

        if ($commissionData['CommissionInfo__lock_status'] == 1 && $auth == 'affiliation') {
            session()->flash('lock_status_invalid', trans('commission_corresponds.msg_error_inner'));
        }

        $categoryDefaultFee = '';

        if (empty($commissionData['DemandInfo__category_id']) == false) {
            $categoryDefaultFee = $this->commissionDataService->getDefaultFee($commissionData['DemandInfo__category_id']);
        }

        $notEnough = '';

        if ($auth == 'affiliation') {
            $notEnough = $this->commissionDataService->checkHaveEnoughData($affiliationId);
        }

        $applications = $this->commissionDataService->getApplicationData($id);
        $divValue = $this->commissionDataService->getDivValueList();
        $dropList = $this->commissionDataService->getDropList();

        return view(
            'commission.detail',
            [
                'id' => $id,
                'history_list' => $historyData,
                'results' => $commissionData,
                'demand_attached_files' => $demandAttachedFiles,
                'm_commission_alert_settings_tel' => $mCommissionAlertSettingsTel,
                'm_commission_alert_settings_visit' => $mCommissionAlertSettingsVisit,
                'm_commission_alert_settings_order' => $mCommissionAlertSettingsOrder,
                'user_list' => $userList,
                'user_id' => $userId,
                'user' => $user,
                'tax_rate' => $taxRate,
                'site_list' => $mSites,
                'address_disclosure' => $addressDisclosure,
                'tel_disclosure' => $telDisclosure,
                'visit_time' => $visitTime,
                'contact_desired_time_hope' => $resultTime['contact_desired_time_hope'],
                'contact_desired_time' => $resultTime['contact_desired_time'],
                'visit_time_display' => $resultTime['visit_time_display'],
                'visit_time_of_hope' => $resultTime['visit_time_of_hope'],
                'category_default_fee' => $categoryDefaultFee,
                'not_enough' => $notEnough,
                'applications' => $applications,
                'auth' => $auth,
                'affiliation_id' => $affiliationId,
                'file_url' => $fileUrl,
                'div_value' => $divValue,
                'drop_list' => $dropList,
            ]
        );
    }

    /**
     * @param $commissionData
     * @param $mCommissionAlertSettingsTel
     * @return \DateTime
     * @throws Exception
     */
    private function getCommissionAlertSettingsTelDate($commissionData, $mCommissionAlertSettingsTel)
    {
        /* tel - date */
        $dateTel = new \DateTime($commissionData['CommissionInfo__modified']);

        if (isset($mCommissionAlertSettingsTel['condition_value_min']) && isset($mCommissionAlertSettingsTel['rits_follow_datetime'])) {
            $dataTelList = $mCommissionAlertSettingsTel['condition_value_min'] + $mCommissionAlertSettingsTel['rits_follow_datetime'];
            $dateTel->add(new \DateInterval('PT' . $dataTelList . 'M'));
        }
        return $dateTel;
    }

    /**
     * @param $commissionData
     * @param $mCommissionAlertSettingsVisit
     * @return \DateTime
     * @throws Exception
     */
    private function getCommissionAlertSettingsVisitDate($commissionData, $mCommissionAlertSettingsVisit)
    {
        /* visit - date */
        $dateVisit = new \DateTime($commissionData['CommissionInfo__modified']);

        if (isset($mCommissionAlertSettingsVisit['condition_value_min']) && isset($mCommissionAlertSettingsVisit['rits_follow_datetime'])) {
            $dataVisitList = $mCommissionAlertSettingsVisit['condition_value_min'] + $mCommissionAlertSettingsVisit['rits_follow_datetime'];
            $dateVisit->add(new \DateInterval('PT' . $dataVisitList . 'M'));
        }
        return $dateVisit;
    }

    /**
     * @param $commissionData
     * @param $mCommissionAlertSettingsOrder
     * @return \DateTime
     * @throws Exception
     */
    private function getCommissionAlertSettingsOrderDate($commissionData, $mCommissionAlertSettingsOrder)
    {
        /* order - date */
        $dateOrder = new \DateTime($commissionData['CommissionInfo__modified']);

        if (isset($mCommissionAlertSettingsOrder['condition_value_min']) && isset($mCommissionAlertSettingsOrder['rits_follow_datetime'])) {
            $dataOrderList = $mCommissionAlertSettingsOrder['condition_value_min'] + $mCommissionAlertSettingsOrder['rits_follow_datetime'];
            $dateOrder->add(new \DateInterval('PT' . $dataOrderList . 'M'));
        }
        return $dateOrder;
    }

    /**
     * @param $commissionData
     * @return array
     */
    private function getCommissionAlert($commissionData)
    {
        $mCommissionAlertSettingsTel = [];
        $mCommissionAlertSettingsVisit = [];
        $mCommissionAlertSettingsOrder = [];

        /* m_commission_alert_settings-tel */
        if (isset($commissionData['CommissionTelSupport']['correspond_status'])) {
            $mCommissionAlertSettingsTel = $this->commissionDataService->mCommissionAlertSettings($commissionData['CommissionTelSupport']['correspond_status'], 0);
        }

        /* m_commission_alert_settings-visit */
        if (isset($commissionData['CommissionVisitSupport']['correspond_status'])) {
            $mCommissionAlertSettingsVisit = $this->commissionDataService->mCommissionAlertSettings($commissionData['CommissionVisitSupport']['correspond_status'], 1);
        }

        /* m_commission_alert_settings-order */
        if (isset($commissionData['CommissionOrderSupport']['correspond_status'])) {
            $mCommissionAlertSettingsOrder = $this->commissionDataService->mCommissionAlertSettings($commissionData['CommissionOrderSupport']['correspond_status'], 2);
        }

        return ['commissionAlertSettingsTel' => $mCommissionAlertSettingsTel, 'commissionAlertSettingsVisit' => $mCommissionAlertSettingsVisit,
            'commissionAlertSettingsOrder' => $mCommissionAlertSettingsOrder];
    }

    /**
     * @param $commissionData
     * @param $auth
     */
    private function abortPage404WhenAccessIndex($commissionData, $auth)
    {
        if (empty($commissionData)) {
            abort(404);
        }

        if ($auth == 'affiliation') {
            if ($commissionData['CommissionInfo__lost_flg'] != 0
                || $commissionData['CommissionInfo__del_flg'] != 0
                || $commissionData['CommissionInfo__introduction_not'] != 0
                || $commissionData['CommissionInfo__commit_flg'] != 1) {
                abort(404);
            }
        }
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function commissionFileDownload($id = null)
    {
        if (!ctype_digit($id)) {
            abort(404);
        }

        $demandAttachFile = $this->commissionDetailService->getDetailDemandAttachFile($id);

        if (file_exists($demandAttachFile['path'])) {
            return response()->download($demandAttachFile['path'], $demandAttachFile['name']);
        }

        echo __('commission_detail.no_file');
    }

    /**
     * Get visit time detail
     * @param array $commissionData
     * @param object $visitTime
     * @return array
     */
    private function getVisitTime($commissionData, $visitTime)
    {
        /* contact datetime */
        $contactDesiredTimeHope = $this->getContactDesiredTimeHopeFormat($commissionData, $visitTime);

        $contactDesiredTime = $this->getContactDesiredTimeFormat($visitTime, $contactDesiredTimeHope, $commissionData['DemandInfo__id']);

        /* visit datetime */
        $visitTimeFormat = $this->getVisitTimeFormat($commissionData, $visitTime);

        $result = [
            'contact_desired_time_hope' => $contactDesiredTimeHope,
            'contact_desired_time' => $contactDesiredTime,
            'visit_time_display' => $visitTimeFormat['visitTimeDisplay'],
            'visit_time_of_hope' => $visitTimeFormat['visitTimeOfHope'],
        ];

        return $result;
    }

    /**
     * @param $commissionData
     * @param $visitTime
     * @return false|string
     */
    private function getContactDesiredTimeHopeFormat($commissionData, $visitTime)
    {
        /* contact datetime */
        $contactDesiredTimeHope = '-';

        if (isset($commissionData['DemandInfo__contact_desired_time'])) {
            $contactDesiredTimeHope = dateTimeFormat($commissionData['DemandInfo__contact_desired_time']);
        } elseif (isset($commissionData['DemandInfo__contact_desired_time_from'])) {
            $contactDesiredTimeHope = dateTimeFormat($commissionData['DemandInfo__contact_desired_time_from']) . ' ~ ' . dateTimeFormat($commissionData['DemandInfo__contact_desired_time_to']);
        } elseif (isset($visitTime['visit_adjust_time'])) {
            $contactDesiredTimeHope = dateTimeFormat($visitTime['visit_adjust_time']);
        }
        return $contactDesiredTimeHope;
    }

    /**
     * @param $visitTime
     * @param $contactDesiredTimeHope
     * @param $demandId
     * @return false|string
     */
    private function getContactDesiredTimeFormat($visitTime, $contactDesiredTimeHope, $demandId)
    {
        $demandInforExtend = $this->demandExtendInfoService->getAllByDemandId($demandId);
        if (isset($demandInforExtend['est_start_work'])) {
            $contactDesiredTime = dateTimeFormat($demandInforExtend['est_start_work']) . ' ~ ' . dateTimeFormat($demandInforExtend['est_end_work']);
        } elseif (isset($visitTime['visit_adjust_time'])) {
            $contactDesiredTime = dateTimeFormat($visitTime['visit_adjust_time']);
        } else {
            $contactDesiredTime = $contactDesiredTimeHope;
        }
        return $contactDesiredTime;
    }

    /**
     * @param $commissionData
     * @param $visitTime
     * @return array
     */
    private function getVisitTimeFormat($commissionData, $visitTime)
    {
        /* visit datetime */
        $visitTimeDisplay = '-';
        $visitTimeOfHope = '-';

        if (isset($commissionData['CommissionInfo__visit_desired_time'])) {
            $visitTimeDisplay = dateTimeFormat($commissionData['CommissionInfo__visit_desired_time']);
        } elseif (isset($visitTime['visit_time'])) {
            $visitTimeDisplay = dateTimeFormat($visitTime['visit_time']);
        } elseif (isset($visitTime['visit_time_from'])) {
            $visitTimeOfHope = dateTimeFormat($visitTime['visit_time_from']) . ' ~ ' . dateTimeFormat($visitTime['visit_time_to']);
        }

        if (isset($visitTime['visit_time'])) {
            $visitTimeOfHope = dateTimeFormat($visitTime['visit_time']);
        } elseif (isset($visitTime['visit_time_from'])) {
            $visitTimeOfHope = dateTimeFormat($visitTime['visit_time_from']) . ' ~ ' . dateTimeFormat($visitTime['visit_time_to']);
        }

        return ['visitTimeDisplay' => $visitTimeDisplay, 'visitTimeOfHope' => $visitTimeOfHope];
    }
}
