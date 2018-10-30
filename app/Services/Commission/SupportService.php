<?php
namespace App\Services\Commission;

use App\Services\BaseService;
use App\Repositories\CommissionTelSupportRepositoryInterface;
use App\Repositories\CommissionVisitSupportRepositoryInterface;
use App\Repositories\CommissionOrderSupportRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;
use DateTime;
use Illuminate\Support\Facades\Lang;

class SupportService extends BaseService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepository;
    /**
     * @var CommissionTelSupportRepositoryInterface
     */
    private $commissionTelSupportRepository;
    /**
     * @var CommissionVisitSupportRepositoryInterface
     */
    private $commissionVisitSupportRepo;
    /**
     * @var CommissionOrderSupportRepositoryInterface
     */
    private $commissionOrderSupportRepo;
    /**
     * @var CommissionDetailService
     */
    private $commissionDetailService;

    const TEL = 'tel';
    const VISIT = 'visit';
    const ORDER = 'order';

    /**
     * SupportService constructor.
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param CommissionTelSupportRepositoryInterface $commissionTelSupportRepository
     * @param CommissionVisitSupportRepositoryInterface $commissionVisitSupportRepo
     * @param CommissionOrderSupportRepositoryInterface $commissionOrderSupportRepo
     * @param CommissionDetailService $commissionDetailService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        CommissionTelSupportRepositoryInterface $commissionTelSupportRepository,
        CommissionVisitSupportRepositoryInterface $commissionVisitSupportRepo,
        CommissionOrderSupportRepositoryInterface $commissionOrderSupportRepo,
        CommissionDetailService $commissionDetailService
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->commissionTelSupportRepository = $commissionTelSupportRepository;
        $this->commissionVisitSupportRepo = $commissionVisitSupportRepo;
        $this->commissionOrderSupportRepo = $commissionOrderSupportRepo;
        $this->commissionDetailService = $commissionDetailService;
    }

    /**
     * @param integer $id
     * @param string $datetime
     * @param integer $status
     * @param string $responder
     * @param integer $failReason
     * @param string $contents
     * @param string $hopeDatetime
     * @return mixed
     */
    public function registTelSupports($id, $datetime, $status, $responder, $failReason, $contents, $hopeDatetime)
    {
        try {
            DB::beginTransaction();
            $resultFlg = true;

            $data = [
                'CommissionTelSupport' => [
                    'commission_id' => $id,
                    'correspond_status' => $status,
                    'correspond_datetime' => DateTime::createFromFormat('Y_-_-m_-_-d H-_-_i', $datetime)->format('Y-m-d H:i'),
                    'order_fail_reason' => intval($failReason),
                    'responders' => $responder,
                    'corresponding_contens' => str_replace(config('constant.ajax.SEARCH'), config('constant.ajax.REPLACE'), $contents)
                ]
            ];

            if (!$this->commissionTelSupportRepository->save($data['CommissionTelSupport'])) {
                $resultFlg = false;
            }

            $commissionStatus = $this->getCommissionStatus($status, self::TEL);
            $commissionFailReason = $this->getCommissionFailReason($failReason, self::TEL);

            $data = ['CommissionInfo' => ['id' => $id, 'tel_support' => 0]];

            $data['CommissionInfo']['commission_status'] = $commissionStatus;

            if ($hopeDatetime != config('constant.ajax.PARAM_NO_VALUE')) {
                $data['CommissionInfo']['visit_desired_time'] = DateTime::createFromFormat('Y_-_-m_-_-d H-_-_i', $hopeDatetime)->format('Y-m-d H:i');
            }

            if ($data['CommissionInfo']['commission_status'] == config('constant.ajax.COMMISSION_STATUS_LOST_ORDER')) {
                $data['CommissionInfo']['commission_order_fail_reason'] = $commissionFailReason;
                $data['CommissionInfo']['order_fail_date'] = DateTime::createFromFormat('Y_-_-m_-_-d H-_-_i', $datetime)->format('Y/m/d');
            }

            if ($resultFlg) {
                $this->commissionDetailService->registCorrespond($id, $data);
                $data['CommissionInfo']['support'] = true;

                if (!$this->commissionInfoRepository->save($data['CommissionInfo'])) {
                    $resultFlg = false;
                }
            }

            if ($resultFlg) {
                session()->flash('success', Lang::get('commission_corresponds.message_successfully'));
                DB::commit();
            } else {
                session()->flash('error', Lang::get('commission_corresponds.message_failure'));
                DB::rollBack();
                logger(__METHOD__ . ': ' . 'ERORR 3ステップ 電話対応登録失敗 取次ID: '.$id);
            }
        } catch (Exception $e) {
            session()->flash('error', Lang::get('commission_corresponds.message_failure'));
            logger(__METHOD__ . ': ' . $e->getMessage());
            DB::rollBack();
        }

        $result = $this->getTelSupport($id, true);

        return $result;
    }

    /**
     * @param integer $id
     * @param string $datetime
     * @param integer $status
     * @param string $responder
     * @param integer $failReason
     * @param string $contents
     * @param string $supportDatetime
     * @return mixed
     */
    public function registVisitSupports($id, $datetime, $status, $responder, $failReason, $contents, $supportDatetime)
    {
        try {
            DB::beginTransaction();
            $resultFlg = true;

            $data = [
                'CommissionVisitSupport' => [
                    'commission_id' => $id,
                    'correspond_status' => $status,
                    'correspond_datetime' => DateTime::createFromFormat('Y_-_-m_-_-d H-_-_i', $datetime)->format('Y-m-d H:i'),
                    'order_fail_reason' => intval($failReason),
                    'responders' => $responder,
                    'corresponding_contens' => str_replace(config('constant.ajax.SEARCH'), config('constant.ajax.REPLACE'), $contents)
                ]
            ];

            if (!$this->commissionVisitSupportRepo->save($data['CommissionVisitSupport'])) {
                $resultFlg = false;
            }

            $commissionStatus = $this->getCommissionStatus($status, self::VISIT);
            $commissionFailReason = $this->getCommissionFailReason($failReason, self::VISIT);

            $data = ['CommissionInfo' => ['id' => $id, 'visit_support' => 0]];
            $data['CommissionInfo']['commission_status'] = $commissionStatus;

            if ($supportDatetime != config('constant.ajax.PARAM_NO_VALUE')) {
                $data['CommissionInfo']['order_respond_datetime'] = DateTime::createFromFormat('Y_-_-m_-_-d H-_-_i', $supportDatetime)->format('Y-m-d H:i');
            }

            if ($data['CommissionInfo']['commission_status'] == config('constant.ajax.COMMISSION_STATUS_LOST_ORDER')) {
                $data['CommissionInfo']['commission_order_fail_reason'] = $commissionFailReason;
                $data['CommissionInfo']['order_fail_date'] = DateTime::createFromFormat('Y_-_-m_-_-d H-_-_i', $datetime)->format('Y/m/d');
            }

            if ($resultFlg) {
                $this->commissionDetailService->registCorrespond($id, $data);
                $data['CommissionInfo']['support'] = true;

                if (!$this->commissionInfoRepository->save($data['CommissionInfo'])) {
                    $resultFlg = false;
                }
            }

            if ($resultFlg) {
                session()->flash('success', Lang::get('commission_corresponds.message_successfully'));
                DB::commit();
            } else {
                session()->flash('error', Lang::get('commission_corresponds.message_failure'));
                DB::rollBack();
                logger(__METHOD__ . ': ' . 'ERORR 3ステップ 電話対応登録失敗 取次ID: '.$id);
            }
        } catch (Exception $e) {
            session()->flash('error', Lang::get('commission_corresponds.message_failure'));
            logger(__METHOD__ . ': ' . $e->getMessage());
            DB::rollBack();
        }

        $result = $this->getVisitSupport($id, true);

        return $result;
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function registOrderSupports($request)
    {
        $id = '';

        try {
            DB::beginTransaction();
            $resultFlg = true;

            $id = $request['commission_id'];
            $status = $request['correspond_status'];
            $failReason = intval($request['order_fail_reason']);

            $data = [
                'CommissionOrderSupport' => [
                    'commission_id' => $id,
                    'correspond_status' => $status,
                    'correspond_datetime' => $request['correspond_datetime'],
                    'order_fail_reason' => $failReason,
                    'responders' => $request['responders'],
                    'corresponding_contens' => $request['corresponding_contens'],
                ]

            ];

            if (!$this->commissionOrderSupportRepo->save($data['CommissionOrderSupport'])) {
                $resultFlg = false;
            }

            $commissionStatus = $this->getCommissionStatus($status, self::ORDER);
            $commissionFailReason = $this->getCommissionFailReason($failReason, self::ORDER);

            $data = ['CommissionInfo' => ['id' => $id, 'order_support' => 0]];
            $data['CommissionInfo']['commission_status'] = $commissionStatus;

            $data = $this->checkCommissionStatusCompletion($data, $request);

            if ($data['CommissionInfo']['commission_status'] == config('constant.ajax.COMMISSION_STATUS_LOST_ORDER')) {
                $data['CommissionInfo']['commission_order_fail_reason'] = $commissionFailReason;
                $orderFailDate = explode(' ', $request['correspond_datetime']);
                $data['CommissionInfo']['order_fail_date'] = str_replace("-", "/", $orderFailDate[0]);
            }

            if ($resultFlg) {
                $saveData['CommissionInfo'] = $request;

                foreach ($data['CommissionInfo'] as $key => $val) {
                    $saveData['CommissionInfo'][$key] = $val;
                }

                $this->commissionDetailService->registCorrespond($id, $saveData);
                $data['CommissionInfo']['support'] = true;

                if (!$this->commissionInfoRepository->save($data['CommissionInfo'])) {
                    $resultFlg = false;
                }
            }

            if ($resultFlg) {
                session()->flash('success', Lang::get('commission_corresponds.message_successfully'));
                DB::commit();
            } else {
                session()->flash('error', Lang::get('commission_corresponds.message_failure'));
                DB::rollBack();
                logger(__METHOD__ . ': ' . 'ERORR 3ステップ 電話対応登録失敗 取次ID: '.$id);
            }
        } catch (Exception $e) {
            session()->flash('error', Lang::get('commission_corresponds.message_failure'));
            logger(__METHOD__ . ': ' . $e->getMessage());
            DB::rollBack();
        }

        $result = $this->getOrderSupport($id, true);

        return $result;
    }

    /**
     * @param array $data
     * @param array $request
     * @return mixed
     */
    public function checkCommissionStatusCompletion($data, $request)
    {
        if ($data['CommissionInfo']['commission_status'] == config('constant.ajax.COMMISSION_STATUS_COMPLETION')) {
            if (!empty($request['completion_datetime'])) {
                $data['CommissionInfo']['complete_date'] = $request['completion_datetime'];
            }
            if (!empty($request['construction_price_tax_exclude'])) {
                $data['CommissionInfo']['construction_price_tax_exclude'] = $request['construction_price_tax_exclude'];
            }
            if (!empty($request['construction_price_tax_include'])) {
                $data['CommissionInfo']['construction_price_tax_include'] = $request['construction_price_tax_include'];
            }
        }
        return $data;
    }
    /**
     * @param integer $commissionId
     * @param array $data
     * @return mixed
     */
    public function getSupport($commissionId, $data)
    {
        $data += $this->getTelSupport($commissionId, false);
        $data += $this->getVisitSupport($commissionId, false);
        $data += $this->getOrderSupport($commissionId, false);

        return $data;
    }

    /**
     * @param integer $commissionId
     * @param boolean $all
     * @return mixed
     */
    public function getTelSupport($commissionId, $all)
    {
        $telSupport = $this->commissionTelSupportRepository->findByCommissionId($commissionId, $all);
        $result = ['CommissionTelSupport' => $telSupport];

        return $result;
    }

    /**
     * @param integer $commissionId
     * @param boolean $all
     * @return mixed
     */
    public function getVisitSupport($commissionId, $all)
    {
        $visitSupport = $this->commissionVisitSupportRepo->findByCommissionId($commissionId, $all);
        $result = ['CommissionVisitSupport' => $visitSupport];

        return $result;
    }

    /**
     * @param integer $commissionId
     * @param boolean $all
     * @return mixed
     */
    public function getOrderSupport($commissionId, $all)
    {
        $orderSupport = $this->commissionOrderSupportRepo->findByCommissionId($commissionId, $all);
        $result = ['CommissionOrderSupport' => $orderSupport];

        return $result;
    }

    /**
     * Update item in $data
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $commissionId
     * @param array $commissionInfo
     * @return mixed
     */
    public function setSupport($commissionId, $commissionInfo)
    {
        $commissionOrder = $this->commissionOrderSupportRepo->findBy("commission_id", $commissionId);
        if ($commissionOrder !== null) {
            $commissionInfo['commission_order_support'] = $commissionOrder->toArray();
        }

        $commissionTel = $this->commissionTelSupportRepository->findBy("commission_id", $commissionId);
        if ($commissionTel !== null) {
            $commissionInfo['commission_tel_support'] = $commissionTel->toArray();
        }

        $commissionVisit = $this->commissionVisitSupportRepo->findBy("commission_id", $commissionId);
        if ($commissionVisit !== null) {
            $commissionInfo['commission_visit_support'] = $commissionVisit->toArray();
        }

        return $commissionInfo;
    }

    /**
     * @param string $status
     * @param string $type
     * @return \Illuminate\Config\Repository|mixed
     */
    private function getCommissionStatus($status, $type)
    {
        switch ($type) {
            case self::TEL:
                $commissionStatusProgress = [
                    config('constant.ajax.TEL_STATUS_NOT_CORRESPOND'),
                    config('constant.ajax.TEL_STATUS_ABSENCE'),
                    config('constant.ajax.TEL_STATUS_CONSIDERATION_WITH_SERVICE'),
                    config('constant.ajax.TEL_STATUS_CONSIDERATION_ADJUSTMENT'),
                    config('constant.ajax.TEL_STATUS_EXPECTED_FIX'),
                    config('constant.ajax.TEL_STATUS_FIX'),
                    config('constant.ajax.TEL_STATUS_OTHER'),
                    config('constant.ajax.TEL_STATUS_CONSIDERATION_AFFILIATION'),
                    config('constant.ajax.TEL_STATUS_CONSIDERATION_SUPPORT')
                ];
                if (in_array($status, $commissionStatusProgress)) {
                    return config('constant.ajax.COMMISSION_STATUS_IN_PROGRESS');
                }
                if ($status == config('constant.ajax.TEL_STATUS_LOST')) {
                    return config('constant.ajax.COMMISSION_STATUS_LOST_ORDER');
                }
                break;
            case self::VISIT:
                $visit = $this->getCommissionStatusVisit($status);
                if (!empty($visit)) {
                    return $visit;
                }
                break;
            case self::ORDER:
                $order = $this->getCommissionStatusOrder($status);
                if (!empty($order)) {
                    return $order;
                }
                break;
        }
    }

    /**
     * @param string $status
     * @return \Illuminate\Config\Repository|mixed|string
     */
    private function getCommissionStatusVisit($status)
    {
        $commissionStatusProgress = [
            config('constant.ajax.VISIT_STATUS_NOT_CORRESPOND'),
            config('constant.ajax.VISIT_STATUS_ABSENCE'),
            config('constant.ajax.VISIT_STATUS_CONSIDERATION_WITH_SERVICE'),
            config('constant.ajax.VISIT_STATUS_CONSIDERATION_ADJUSTMENT'),
            config('constant.ajax.VISIT_STATUS_EXPECTED_FIX'),
            config('constant.ajax.VISIT_STATUS_OTHER'),
            config('constant.ajax.VISIT_STATUS_CONSIDERATION_AFFILIATION'),
            config('constant.ajax.VISIT_STATUS_CONSIDERATION_SUPPORT')
        ];
        if (in_array($status, $commissionStatusProgress)) {
            return config('constant.ajax.COMMISSION_STATUS_IN_PROGRESS');
        }
        if ($status == config('constant.ajax.VISIT_STATUS_FIX')) {
            return config('constant.ajax.COMMISSION_STATUS_RECEIPT_ORDER');
        }
        if ($status == config('constant.ajax.VISIT_STATUS_LOST')) {
            return config('constant.ajax.COMMISSION_STATUS_LOST_ORDER');
        }
        return '';
    }
    /**
     * @param string $status
     * @return \Illuminate\Config\Repository|mixed|string
     */
    private function getCommissionStatusOrder($status)
    {
        $commissionStatusProgress = [
            config('constant.ajax.ORDER_STATUS_NOT_CORRESPOND'),
            config('constant.ajax.ORDER_STATUS_OTHER')
        ];
        $commissionStatusCompletion = [
            config('constant.ajax.ORDER_STATUS_FIX'),
            config('constant.ajax.ORDER_STATUS_FIX_AND_MORE')
        ];
        if (in_array($status, $commissionStatusProgress)) {
            return config('constant.ajax.COMMISSION_STATUS_IN_PROGRESS');
        }
        if (in_array($status, $commissionStatusCompletion)) {
            return config('constant.ajax.COMMISSION_STATUS_COMPLETION');
        }
        if ($status == config('constant.ajax.ORDER_STATUS_CANCEL')) {
            return config('constant.ajax.COMMISSION_STATUS_LOST_ORDER');
        }
        return '';
    }
    /**
     * @param integer $failReason
     * @param string $type
     * @return \Illuminate\Config\Repository|mixed
     */
    private function getCommissionFailReason($failReason, $type)
    {
        switch ($type) {
            case self::TEL:
                $tel = $this->getCommissionFailReasonTel($failReason);
                if (!empty($tel)) {
                    return $tel;
                }
                break;
            case self::VISIT:
                $visit = $this->getCommissionFailReasonVisit($failReason);
                if (!empty($visit)) {
                    return $visit;
                }
                break;
            case self::ORDER:
                $order = $this->getCommissionFailReasonOrder($failReason);
                if (!empty($order)) {
                    return $order;
                }
                break;
        }
    }

    /**
     * @param integer $failReason
     * @return \Illuminate\Config\Repository|mixed|string
     */
    private function getCommissionFailReasonTel($failReason)
    {
        $commissionOwnSolution = [
            config('constant.ajax.TEL_LOST_REASON_OWN_SOLUTION'),
            config('constant.ajax.TEL_LOST_REASON_ONLY_QUESTION_WITH_FAIR'),
            config('constant.ajax.TEL_LOST_REASON_ONLY_QUESTION_WITHOUT_FAIR')
        ];
        if ($failReason == config('constant.ajax.TEL_LOST_REASON_NOT_CONTACT')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_NO_CONTACT');
        }
        if (in_array($failReason, $commissionOwnSolution)) {
            return config('constant.ajax.COMMISSION_LOST_REASON_OWN_SOLUTION');
        }
        if ($failReason == config('constant.ajax.TEL_LOST_REASON_DELAY')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_DELAY');
        }
        if ($failReason == config('constant.ajax.TEL_LOST_REASON_NOT_ADJUSTMENT')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        if ($failReason == config('constant.ajax.TEL_LOST_REASON_NEGATIVE')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        if ($failReason == config('constant.ajax.TEL_LOST_REASON_OTHER')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        if ($failReason == config('constant.ajax.TEL_LOST_REASON_MEETING_ESTIMATE')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_MEETING_ESTIMATE');
        }
        return '';
    }

    /**
     * @param integer $failReason
     * @return \Illuminate\Config\Repository|mixed|string
     */
    private function getCommissionFailReasonVisit($failReason)
    {
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_OWN_SOLUTION')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_OWN_SOLUTION');
        }
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_LACK_BUDGET')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_LACK_BUDGET');
        }
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_MEETING_ESTIMATE')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_MEETING_ESTIMATE');
        }
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_DELAY')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_DELAY');
        }
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_NOT_ADJUSTMENT')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_NEGATIVE')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        if ($failReason == config('constant.ajax.VISIT_LOST_REASON_OTHER')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        return '';
    }

    /**
     * @param integer $failReason
     * @return \Illuminate\Config\Repository|mixed|string
     */
    private function getCommissionFailReasonOrder($failReason)
    {
        if ($failReason == config('constant.ajax.ORDER_LOST_REASON_OWN_SOLUTION')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_OWN_SOLUTION');
        }
        if ($failReason == config('constant.ajax.ORDER_LOST_REASON_MEETING_ESTIMATE')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_MEETING_ESTIMATE');
        }
        if ($failReason == config('constant.ajax.ORDER_LOST_REASON_DELAY')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_DELAY');
        }
        if ($failReason == config('constant.ajax.ORDER_LOST_REASON_OTHER')) {
            return config('constant.ajax.COMMISSION_LOST_REASON_WITHOUT_SCHEDULE');
        }
        return '';
    }
}
