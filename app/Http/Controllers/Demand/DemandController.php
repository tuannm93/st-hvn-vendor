<?php

namespace App\Http\Controllers\Demand;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoDemandCorrespondsFormRequest;
use App\Http\Requests\Demand\DemandInfoDataRequest;
use App\Services\Affiliation\AffiliationInfoService;
use App\Services\Auction\AuctionService;
use App\Services\CommissionService;
use App\Services\CorpRegisteredScheduleService;
use App\Services\Cyzen\CyzenNotificationServices;
use App\Services\Cyzen\CyzenSpotServices;
use App\Services\Demand\BusinessService;
use App\Services\Demand\DemandExtendInfoService;
use App\Services\Demand\DemandInfoMailService;
use App\Services\Demand\DemandInfoService;
use App\Services\Demand\DemandNotificationService;
use App\Services\DemandCorrespondService;
use App\Services\DemandInquiryAnswerService;
use App\Services\MPostService;
use App\Services\MSiteService;
use App\Services\PdfGenerator;
use App\Services\VisitTimeService;
use App\Traits\DemandInfoTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class DemandController extends Controller
{
    use DemandInfoTrait;

    /**
     * @var DemandInfoService
     */
    protected $demandInfoService;
    /**
     * @var DemandInquiryAnswerService
     */
    protected $demandInquiryAnswerService;

    /**
     * @var VisitTimeService
     */
    protected $visitTimeService;
    /**
     * @var DemandCorrespondService
     */
    protected $demandCorrespondService;

    /**
     * @var BusinessService
     */
    protected $businessDemandService;

    /**
     * @var AffiliationInfoService $affiliationInfoService
     */
    protected $affiliationInfoService;
    /**
     * @var DemandInfoMailService
     */
    protected $demandMailService;

    /**
     * @var CorpRegisteredScheduleService $AffRegisteredScheduleService
     */
    protected $corpRegisteredScheduleService;

    /**
     * @var DemandNotificationService $demandNotificationService
     */
    protected $demandNotificationService;

    /**
     * @var DemandExtendInfoService $demandExtendInfoService
     */
    protected $demandExtendInfoService;

    /**
     * @var CyzenNotificationServices $cyzenNotificationServices
     */
    protected $cyzenNotificationServices;

    /**
     * @var CyzenSpotServices $cyzenSpotServices
     */
    protected $cyzenSpotServices;

    /**
     * DemandController constructor.
     * @param MSiteService $mSiteService
     * @param CommissionService $commissionService
     * @param MPostService $mPostService
     * @param AuctionService $auctionService
     * @param DemandInfoService $demandInfoService
     * @param DemandInquiryAnswerService $demandInquiryAnswerService
     * @param VisitTimeService $visitTimeService
     * @param BusinessService $businessService
     * @param DemandCorrespondService $correspondService
     * @param DemandInfoMailService $demandInfoMailService
     * @param CorpRegisteredScheduleService $corpRegisteredScheduleService
     * @param AffiliationInfoService $affiliationInfoService
     * @param DemandNotificationService $demandNotificationService
     * @param DemandExtendInfoService $demandExtendInfoService
     * @param \App\Services\Cyzen\CyzenSpotServices $cyzenSpotServices
     * @param \App\Services\Cyzen\CyzenNotificationServices $cyzenNotificationServices
     */
    public function __construct(
        MSiteService $mSiteService,
        CommissionService $commissionService,
        MPostService $mPostService,
        AuctionService $auctionService,
        DemandInfoService $demandInfoService,
        DemandInquiryAnswerService $demandInquiryAnswerService,
        VisitTimeService $visitTimeService,
        BusinessService $businessService,
        DemandCorrespondService $correspondService,
        DemandInfoMailService $demandInfoMailService,
        CorpRegisteredScheduleService $corpRegisteredScheduleService,
        AffiliationInfoService $affiliationInfoService,
        DemandNotificationService $demandNotificationService,
        DemandExtendInfoService $demandExtendInfoService,
        CyzenSpotServices $cyzenSpotServices,
        CyzenNotificationServices $cyzenNotificationServices
    ) {
        parent::__construct();
        $this->mSiteService = $mSiteService;
        $this->commissionService = $commissionService;
        $this->mPostService = $mPostService;
        $this->auctionService = $auctionService;
        $this->demandInfoService = $demandInfoService;
        $this->demandInquiryAnswerService = $demandInquiryAnswerService;
        $this->visitTimeService = $visitTimeService;
        $this->businessDemandService = $businessService;
        $this->demandCorrespondService = $correspondService;
        $this->demandMailService = $demandInfoMailService;
        $this->corpRegisteredScheduleService = $corpRegisteredScheduleService;
        $this->affiliationInfoService = $affiliationInfoService;
        $this->demandExtendInfoService = $demandExtendInfoService;
        $this->demandNotificationService = $demandNotificationService;
        $this->cyzenSpotServices = $cyzenSpotServices;
        $this->cyzenNotificationServices = $cyzenNotificationServices;
        $this->initRepository();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pdfGenerator = app(PdfGenerator::class);
        $pdfGenerator->commission();
        utilGetDropList("IPHONE");

        return view('home');
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function historyInput($id = null)
    {
        $demand = $this->findDemandCorrespond($id);
        $userList = $this->mUserRepository->getUser();

        return view(
            'demand.history_input',
            [
                'demand' => $demand ?? (new DemandInfo()),
                'userList' => $userList,
            ]
        );
    }

    /**
     * @param AutoDemandCorrespondsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @des update history_input
     */
    public function update(AutoDemandCorrespondsFormRequest $request)
    {
        $data = $request->except('_token');
        if ($data['id'] && $data['modified']) {
            $demand = $this->findDemandCorrespond($data['id']);
            if ($data['modified'] != $demand['modified']) {
                return redirect()->back()->with('modified', $data['modified']);
            }
        }
        $rslt = $this->demandCorrespondsRepository->save($data);

        if ($rslt) {
            return redirect()->back()->with('success', Lang::get('demand.message_successfully'));
        }

        return redirect()->back()->with('error', '');
    }

    /**
     * @param null $demandId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auctionDetail($demandId = null)
    {
        $results = $this->auctionInfoRepo->getListByDemandId($demandId);
        return view('demand.auction_detail', [
            'results' => $results
        ]);
    }

    /**
     * @param $data
     * @param $demandNotificationData
     * @throws \Exception
     */
    private function cyzenEvent($data, $demandNotificationData)
    {
        if (isset($data['commissionInserted'])) {
            $demandNotificationData = $this->matchData($demandNotificationData, $data['commissionInserted']);
            if ((int)$data['demandInfo']['demand_status'] == 5) {
                $demandSpotFlag = [];
                foreach ($demandNotificationData as $key => $notification) {
                    $spotTag = $this->cyzenSpotServices->getTagByStaff($notification['user_id']);

                    if (!$spotTag) {
                        continue;
                    }
                    if ($notification['status'] != CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER) {
                        if (!isset($demandSpotFlag[$notification['group_id']])) {
                            $demandSpotFlag[$notification['group_id']]['created_spot'] = false;
                        }
                        $demandSpotFlag[$notification['group_id']][$notification['user_id']] = true;

                        if ($demandSpotFlag[$notification['group_id']]['created_spot'] == false) {
                            //create or update spot
                            $address = trans('rits_config.' . config('rits.prefecture_div.' . $data['demandInfo']['address1']))
                                . ' ' . $data['demandInfo']['address2'] . ' ' . $data['demandInfo']['address3'];
                            $spotId = $this->cyzenSpotServices->checkSpotId(
                                $data['demandInfo']['id'],
                                $spotTag->group_id
                            );

                            $url = $this->mSiteService->getSiteUrl($data['demandInfo']['site_id']);
                            $genreName = $this->mGenresRepository->getNameById($data['demandInfo']['genre_id']);
                            $categoryName = $this->mCateRepo->getNameById($data['demandInfo']['category_id']);
                            if (strpos(trim($url), 'http') !== 0) {
                                $url = 'http://' . $url;
                            }
                            if (empty($spotId->id)) {
                                $spot = $this->cyzenSpotServices->createSpot(
                                    $address,
                                    $data['demandInfo']['id'],
                                    $notification['user_id'],
                                    $data['demandInfo']['lat'],
                                    $data['demandInfo']['lng'],
                                    '',
                                    $data['demandInfo']['postcode'],
                                    $url,
                                    '◇' . trans('demand_detail.genre') . '：' . $genreName .
                                    "\r\n◇" . trans('demand_detail.category') . ': ' . $categoryName .
                                    "\r\n◇" . trans('demand_detail.content_transfer') . "\r\n" .
                                    $data['demandInfo']['contents'],
                                    $data['demandInfo']['tel1'],
                                    '',
                                    '',
                                    '',
                                    (!empty($spotTag)) ? $spotTag->group_id : '',
                                    (!empty($spotTag)) ? $spotTag->spot_tag_id : ''
                                );
                                $demandNotificationData[$key]['spot_id'] = $spot['spot_id'];
                                $demandNotificationData[$key]['status'] = CyzenNotificationServices::STATUS_DEMAND_REGISTER;
                                $demandSpotFlag[$notification['group_id']]['created_spot'] = $spot['spot_id'];
                                \Log::info('CREATE SPOT ID: ' . $spot['spot_id'] . ' FOR DEMAND ID: ' . $data['demandInfo']['id']);
                            } else {
                                \Log::info('UPDATE SPOT ID: ' . $spotId->id . ' FOR DEMAND ID: ' . $data['demandInfo']['id']);
                                $this->cyzenSpotServices->updateSpot(
                                    $spotId->id,
                                    $address,
                                    $spotId->spot_code,
                                    $data['demandInfo']['lat'],
                                    $data['demandInfo']['lng'],
                                    $spotId->spot_name,
                                    $spotId->spot_name_kana,
                                    $data['demandInfo']['postcode'],
                                    $url,
                                    '◇' . trans('demand_detail.genre') . '：' . $genreName .
                                    "\r\n◇" . trans('demand_detail.category') . ': ' . $categoryName .
                                    "\r\n◇" . trans('demand_detail.content_transfer') . "\r\n" .
                                    $data['demandInfo']['contents'],
                                    $data['demandInfo']['tel1'],
                                    $spotId->fax,
                                    $spotId->valid_from,
                                    $spotId->valid_to,
                                    (!empty($spotTag)) ? $spotTag->group_id : '',
                                    (!empty($spotTag)) ? $spotTag->spot_tag_id : ''
                                );
                                $demandNotificationData[$key]['spot_id'] = $spotId->id;
                                $demandSpotFlag[$notification['group_id']]['created_spot'] = $spotId->id;
                            }
                        } else {
                            $demandNotificationData[$key]['spot_id'] = $demandSpotFlag[$notification['group_id']]['created_spot'];
                        }
                    } else {
                        $demandNotificationData[$key]['spot_id'] = null;
                        if ($notification['status'] == CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP) {
                            $spotId2 = $this->cyzenSpotServices->checkSpotId(
                                $data['demandInfo']['id'],
                                $spotTag->group_id
                            );
                            $demandNotificationData[$key]['spot_id'] = $spotId2->id ?? null;
                        }
                    }
                    $demandNotificationData[$key]['group_id'] = $spotTag->group_id;
                    $demandNotificationData[$key]['cyzen_user_id'] = $spotTag->cyzen_user_id;
                }
            }
            foreach ($demandNotificationData as $key => $noti) {
                unset($demandNotificationData[$key]['corp_id']);
                unset($demandNotificationData[$key]['is_choice']);
                unset($demandNotificationData[$key]['position']);
            }
        }

        $this->updateDemandExtendAndNotify(
            $data,
            $demandNotificationData
        );
    }

    /**
     * @param $data
     * @param $commissionData
     * @return mixed
     * @throws \Exception
     */
    private function validateCyzen($data, $commissionData)
    {
        $data['demandInfo']['id'] = $data['demandInfo']['id'] ?? null;
        $demandNotificationData = $this->demandNotificationService->demandNotification($commissionData, $data);
        if ((int)$data['demandInfo']['demand_status'] == 5) {
            foreach ($demandNotificationData as $key => $notification) {
                $spotTag = $this->cyzenSpotServices->getTagByStaff($notification['user_id']);
                if (!$spotTag) {
                    continue;
                }
                $bNewDemand = false;
                if ($notification['status'] != CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER) {
                    $idDemandNotifTemp = null;
                    if (!empty($data['demandInfo']['id'])) {
                        if ($notification['is_choice'] == 1) {
                            $this->demandNotificationService->updateDemandNotification(
                                $data['demandInfo']['id'],
                                $notification,
                                CyzenNotificationServices::STATUS_WAIT_FOR_CREATE_CYZEN_SPOT
                            );
                        }
                    } else {
                        $bNewDemand = true;
                        $idDemandNotifTemp = $this->demandNotificationService->insertTempDemandNotification($notification);
                    }
                    usleep(rand(5000, 2500000));
                    $isExist = $this->demandNotificationService->checkExistDraftSchedule(
                        $data['demandInfo']['id'],
                        $notification['user_id'],
                        $notification['draft_start_time'],
                        $notification['draft_end_time'],
                        $idDemandNotifTemp
                    );
                    if ($isExist) {
                        $result = $this->cyzenNotificationServices->corpRepos->getCorpNameAndStaffNameFromUserId($notification['user_id']);
                        $messageError = __(
                            'demand.error_staff_not_have_schedule',
                            ['kameiten' => $result[0]['corp_name'], 'staff' => $result[0]['user_name']]
                        );

                        $this->updateDemandNotificationWhenCheckDraftTime(
                            $data['demandInfo']['id'],
                            $notification['user_id'],
                            $idDemandNotifTemp
                        );
                        if (!$bNewDemand) {
                            \Log::error('ERROR STAFF NOT HAVE SCHEDULE WHEN SEND AGENCY [' . __LINE__ . '] >>> '
                                . $data['demandInfo']['id'] . ' >>> ' . $notification['user_id']);
                            throw new \Exception(
                                $messageError,
                                DemandNotificationService::CODE_ERROR_STAFF_SAME_SCHEDULE
                            );
                        } else {
                            \Log::error('ERROR STAFF NOT HAVE SCHEDULE WHEN CREATE AND SEND AGENCY [' . __LINE__ . '] >>> '
                                . $idDemandNotifTemp . ' >>> ' . $notification['user_id']);
                            throw new \Exception(
                                $messageError,
                                DemandNotificationService::CODE_ERROR_STAFF_NOT_SCHEDULE
                            );
                        }
                    } else {
                        $this->updateDemandNotificationWhenCheckDraftTime(
                            $data['demandInfo']['id'],
                            $notification['user_id'],
                            $idDemandNotifTemp,
                            CyzenNotificationServices::STATUS_DEMAND_REGISTER
                        );
                    }
                }
            }
        }
        return $demandNotificationData;
    }

    /**
     * @param $data
     * @param $commissionRemoveList
     * @return bool
     * @throws \Exception
     */
    private function validateInputCyzen($data, &$commissionRemoveList)
    {
        $result = true;
        $commissionData = $data['commissionInfo'];
        $demandInfo = $data['demandInfo'];
        $bHaveStaff = false;
        foreach ($commissionData as $commission) {
            if ((int)$commission['del_flg'] == 1) {
                if (!empty($commission['id'])) {
                    $commissionRemoveList[] = $commission['id'];
                }
                continue;
            }
            if (isset($commission['id_staff']) && !empty($commission['id_staff'])) {
                $bHaveStaff = true;
            }
        }
        if ($bHaveStaff) {
            if ((!isset($demandInfo['contact_estimated_time_from']) || empty($demandInfo['contact_estimated_time_from']))
                && (!isset($demandInfo['contact_estimated_time_to']) || empty($demandInfo['contact_estimated_time_to']))) {
                switch ($demandInfo['is_contact_time_range_flg']) {
                    case 0:
                        if (!isset($demandInfo['contact_desired_time']) || empty($demandInfo['contact_desired_time'])) {
                            $result = false;
                        }
                        break;
                    case 1:
                        if ((!isset($demandInfo['contact_desired_time_from']) || empty($demandInfo['contact_desired_time_from']))
                            && (!isset($demandInfo['contact_desired_time_to']) || empty($demandInfo['contact_desired_time_to']))) {
                            $result = false;
                        }
                        break;
                }

                if (!$result) {
                    \Log::error('INPUT CYZEN NOT CORRECT [' . __LINE__ . '] >>> HAVE STAFF BUT HAVE NOT SCHEDULE TIME');
                    throw new \Exception(
                        __('cyzen_notifications.message_staff_exception'),
                        DemandNotificationService::CODE_ERROR_STAFF_NOT_SCHEDULE
                    );
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $idDemandNotif
     * @param int $status
     */
    private function updateDemandNotificationWhenCheckDraftTime($demandId, $userId, $idDemandNotif, $status = CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER)
    {
        if (!empty($demandId)) {
            $this->demandNotificationService->updateDemandNotificationStatus(
                $demandId,
                $userId,
                $status
            );
        } else {
            $this->demandNotificationService->deleteDemandNotificationTemp($idDemandNotif);
        }
    }

    /**
     * @param DemandInfoDataRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function regist(DemandInfoDataRequest $request)
    {
        $data = $request->except('_token', '_method');
        if (!empty($data['demandInfo']['id'])) {
            $locked = $this->demandInfoService->checkDemandLocked($data['demandInfo']['id']);
            if ($locked) {
                session()->flash('error_msg_input', __('demand.modifiedNotCheck'));
                \Log::debug('___ Demand_editing ___');
                return redirect()->route('demand.detail', $data['demandInfo']['id'])->with('again_enabled', true);
            }
        }
        $data['not-send'] = $data['not-send'] ?? 0;
        $data['demandInfo']['contact_estimated_time_from'] = $data['demandInfo']['contact_estimated_time_from'] ?? null;
        $data['demandInfo']['contact_estimated_time_to'] = $data['demandInfo']['contact_estimated_time_to'] ?? null;
        $data['demandInfo'] = $this->demandInfoService->addDefaultValueForDemand($data['demandInfo']);
        $commissionData = isset($data['commissionInfo']) ? $data['commissionInfo'] : [];

        $data['commissionInfo'] = $this->demandInfoService->makeCommissionInfoData($commissionData);

        foreach ($data['commissionInfo'] as $key => $commission) {
            if (!isset($commission['lost_flg'])) {
                $commission['lost_flg'] = 0;
            }
            if (!isset($commission['del_flg'])) {
                $commission['del_flg'] = 0;
            }

            if (!isset($commission['first_commission'])) {
                $commission['first_commission'] = 0;
            }

            if (!isset($commission['unit_price_calc_exclude'])) {
                $commission['unit_price_calc_exclude'] = 0;
            }
            if (!isset($commission['commit_flg'])) {
                $commission['commit_flg'] = 0;
            }
            if (!isset($commission['corp_claim_flg'])) {
                $commission['corp_claim_flg'] = 0;
            }

            if (!isset($commission['introduction_not'])) {
                $commission['introduction_not'] = 0;
            }

            $data['commissionInfo'][$key] = $commission;
        }

        $processData = $this->demandInfoService->processDataWithoutQuickOrder($data);
        if (!$processData) {
            // redirect back to regist demand
            // return $this->backToDetail($request->except('_token', '_method'));
            return $this->backToDetail($data);
        } else {
            $data = $processData;
        }
        // DemandInfo Remove the leading and trailing spaces of each item
        $data['demandInfo'] = $this->demandInfoService->replaceSpace($data['demandInfo']);

        //One-touch loss
        $quickOrderFail = false;
        // check do_auction
        $data['demandInfo'] = $this->demandInfoService->checkDemandInfoDoAuction($data['demandInfo']);
        // check auto_selecttion
        $data['demandInfo'] = $this->demandInfoService->checkDoAutoSelection($data['demandInfo']);
        // update demand info if has quick order fail
        $data = $this->demandInfoService->processQuickOrderFail($data);
        $errFlg = false;
        $errFlg = $this->checkCommissionFlgCount(
            $data,
            $request->get('commissionInfo'),
            $request->get('send_commission_info'),
            $errFlg
        );
        $errFlg = $this->validateAndDebug($data, $errFlg);
        $errFlg = $this->validateAndBackToDetail($data, $errFlg);
        if ($errFlg) {
            if (!session()->has('error_msg_input')) {
                session()->flash('error_msg_input', __('demand.input_required'));
            }
            \Log::debug('errFlg');
            return $this->backToDetail($data);
        }
        $allData = $this->demandInfoService->processAuctionSelection($data);
        $data = $allData['data'];
        $auctionFlg = $allData['auctionFlg'];
        $auctionNoneFlg = $allData['auctionNoneFlg'];
        $hasStartTimeErr = $allData['hasStartTimeErr'];
        $data = $this->demandInfoService->processDataWithSelectionSystem($data);
        $oldData = $data;
        $listKameitenHaveStaffError = [];
        try {
            $idDemandNotifTemp = null;
            $listCommissionRemoved = [];
            $checkInputCyzen = $this->validateInputCyzen($data, $listCommissionRemoved);
            $demandNotificationData = [];

            if ($checkInputCyzen) {
                $demandNotificationData = $this->validateCyzen($data, $commissionData);
            }
            foreach ($data['commissionInfo'] as $key => $item) {
                $data['commissionInfo'][$key]['position'] = $key;
            }
            DB::beginTransaction();
            $errorNo = $this->updateData($data);
            if (empty($errorNo)) {
                DB::commit();
            } else {
                DB::rollback();
                $errorMessage = __('demand.not_email_and_fax');
                foreach ($errorNo as $val) {
                    $errorMessage .= ' [取次先' . $val . ']';
                }
                session()->flash('error_msg_input', $errorMessage);
                return $this->backToDetail($data);
            }

            $demandExtendInfoData = $this->demandExtendInfoService->demandExtendInfoData($data['demandInfo']);
            $data['demandExtendInfo'] = $this->mDemandExtendRepo->updateOrCreate($demandExtendInfoData);
            if ($checkInputCyzen) {
                $this->cyzenEvent($data, $demandNotificationData);
            }

            $this->removeDemandNotification($listCommissionRemoved);
            if (isset($data['commissionInserted'])) {
                $this->sendNotify($data, $data['commissionInserted']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error('__ORDER_FAIL_EXCEPTION___________________________________________');
            \Log::error($ex);
            \Log::error('__END_ORDER_FAIL_EXCEPTION___________________________________________');
            session()->flash('error_msg_input', __('demand.input_required'));
            if ($ex->getCode() == DemandNotificationService::CODE_ERROR_STAFF_SAME_SCHEDULE) {
                session()->flash('error_msg_input', $ex->getMessage());

                if (isset($data['demandInfo']['id']) && !empty($data['demandInfo']['id'])) {
                    $this->demandInfoService->unlockDemand($data['demandInfo']['id']);
                    return redirect()->route('demand.detail', $data['demandInfo']['id']);
                }
            } elseif ($ex->getCode() == DemandNotificationService::CODE_ERROR_STAFF_NOT_SCHEDULE) {
                session()->flash('error_msg_input', $ex->getMessage());
            }

            return $this->backToDetail($oldData);
        }
        if ($data['send_commission_info'] == 1 && isset($data['commissionInfo'])) {
            \Log::debug('___ start process mail and fax ___');
            $listCommissionNotStaff = collect($data['commissionInfo'])->reject(function ($item) {
                if ((isset($item['id_staff']) && strlen(trim($item['id_staff'])) > 0)) {
                    return true;
                }
                return false;
            })->merge(collect($listKameitenHaveStaffError))->unique('corp_id')->toArray();
            $mailAndFaxs = $this->demandInfoService->getMailAndFaxByCorpData($listCommissionNotStaff);
            $faxList = $mailAndFaxs['faxList'];
            $mailList = $mailAndFaxs['mailList'];
            $demandInfo = $data['demandInfo'];
            $mailInfo = $this->demandMailService->getMailData($demandInfo['id']);
            $resultSendMail = $this->demandMailService->sendMail($demandInfo, $mailList, $mailInfo);
            $resultSendFax = $this->demandMailService->sendFax($demandInfo, $faxList, $mailInfo);
            if (!$resultSendMail || !$resultSendFax) {
                session()->flash('error_msg_input', __('demand.send_error'));
                \Log::debug('___ fail send email and fax ___');
//                return $this->backToDetail($data);
            }
        }
        $this->updateCommissionSendMailFax($data);
        $messageUpdated = [__('demand.msg_success')];
        $warningInner = $this->setWarningMessage($hasStartTimeErr, $auctionNoneFlg, $auctionFlg);
        if ($warningInner) {
            $messageUpdated = [$warningInner];
        }
        $this->demandInfoService->unlockDemand($data['demandInfo']['id']);
        return redirect()->route('demand.detail', $data['demandInfo']['id'])
            ->with('message', $messageUpdated);
    }

    /**
     * @param $commissionData
     * @param $commissionInserted
     * @return mixed
     */
    private function matchData($commissionData, $commissionInserted)
    {
        usort($commissionInserted, function ($a, $b) {
            if (isset($a['position']) &&  isset($b['position']) && ($a['position'] > $b['position'])) {
                return 1;
            }
        });
        foreach ($commissionData as $key => $item) {
            foreach ($commissionInserted as $obj) {
                if (isset($commissionData[$key]['position']) && isset($obj['position']) && $commissionData[$key]['position'] == $obj['position']) {
                    $commissionData[$key]['commission_id'] = $obj['id'];
                    $commissionData[$key]['demand_id'] = $obj['demand_id'] ?? $commissionData[$key]['demand_id'];
                }
            }
        }
        return $commissionData;
    }

    /**
     * @param $listIdCommission
     */
    private function removeDemandNotification($listIdCommission)
    {
        foreach ($listIdCommission as $commissionId) {
            $this->demandNotificationService->updateDemandNotificationStatusByCommissionId($commissionId);
        }
    }
    /**
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    private function backToDetail($data)
    {
        $errs = $this->getErrors();

        $data = (isset($data['restore_at_error']) && !empty($data['restore_at_error']))
            ? array_replace_recursive($data, $data['restore_at_error'])
            : $data;

        $data['demandCorrespond'] = $data['demandCorrespond'] ?? [];

        if (!isset($data['demandInfo']['id']) || empty($data['demandInfo']['id'])) {
            return redirect()->back()->withErrors($errs)->withInput($data);
        }
        $this->demandInfoService->unlockDemand($data['demandInfo']['id']);
        return redirect()->back()->withErrors($errs)->withInput($data)->with('disableLimit', 'disabled');
    }

    /**
     * @param Request $request
     * @param null $customerTel
     * @param null $siteTel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cti(Request $request, $customerTel = null, $siteTel = null)
    {
        if (empty($siteTel)) {
            return redirect()->route('demand.get.create');
        }
        $customerTelSave = '';
        $ctiCustomerTel = '';
        if ($customerTel == '0') {
            $customerTelSave = '0';
            $customerTel = '';
        }

        if ($customerTel == NON_NOTIFICATION) {
            $customerTelSave = NON_NOTIFICATION;
            $customerTel = '';
        }
        $demandInfo = $this->demandInfoService->setPreDemand($customerTel, $siteTel);
        if (isset($demandInfo['demand_status']) && $demandInfo['demand_status'] != 0) {
            $ctiCustomerTel = $demandInfo['customer_tel'];
        }
        $demandInfo['demand_status'] = 0;
        if ($customerTelSave == NON_NOTIFICATION) {
            $demandInfo['customer_tel'] = NON_NOTIFICATION;
        }
        return $this->create($request, $demandInfo, $ctiCustomerTel);
        // return redirect()->route('demand.get.create')->with('ctiDemandInfo', $demandInfo);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, $ctiDemandInfo = null, $ctiCustomerTel = '')
    {
        $data = $this->businessDemandService->getCommonData();

        $vacationLabels = $this->mItemRepository->getMItemList(LONG_HOLIDAYS, date('Y/m/d'));

        $selectionSystemList = $this->demandInfoService->getSelectionSystemList();

        $genresDropDownList = config('constant.defaultOption');
        if (isset($request->old('demandInfo')['site_id'])) {
            $genresDropDownList = array_replace(
                $genresDropDownList,
                $this->mSiteGenresRepository->getGenreBySiteStHide($request->old('demandInfo')['site_id'])->toArray()
            );
        }

        $categoriesDropDownList = config('constant.defaultOption');
        if (isset($request->old('demandInfo')['genre_id'])) {
            $categoriesDropDownList =
                $this->mCateRepo->getListCategoriesForDropDown($request->old('demandInfo')['genre_id']);
        }
        return view('demand.create', array_replace($data, [
            'vacationLabels' => $vacationLabels,
            'selectionSystemList' => $selectionSystemList,
            'categoriesDropDownList' => $categoriesDropDownList,
            'genresDropDownList' => $genresDropDownList,
            'ctiDemand' => $ctiDemandInfo,
            'ctiCustomerTel' => $ctiCustomerTel,
        ]));
    }

    /**
     * clone a demand
     *
     * @param  null $demandId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function copy($demandId = null)
    {
        if ($demandId == null) {
            return abort(404);
        }

        $demand = $this->demandInfoRepo->getDemandByIdWithRelations($demandId);

        if (!$demand) {
            return abort(404);
        }

        $demand = $this->businessDemandService->unsetData($demand, [
            'id',
            'selection_system_before',
            'commissionInfos',
            'auction',
            'riro_kureka',
            'demandAttachedFiles'
        ]);

        $demand->demand_status = 1;
        $demand->demand_status_before = $demand->demand_status;

        $selectionSystemList = $this->demandInfoService->getSelectionSystemList($demand);
        $data = $this->businessDemandService->getDataForDetail($demand, $demand->cross_sell_source_site);
        $demandExtenInfoData = null;
        if ($demandId) {
            $demandExtenInfoData = $this->demandExtendInfoService->getAllByDemandId($demandId);
        }
        $enableSiteId = in_array($demand->site_id, [861, 863, 889, 890, 1312, 1313, 1314]);
        return view('demand.detail', array_replace([
            'selectionSystemList' => $selectionSystemList,
            'copy' => true,
            'demand' => $demand,
            'enableSiteId' => $enableSiteId,
            'demandExtenInfoData' => $demandExtenInfoData
        ], $data));
    }

    /**
     * Remove demand info
     *
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $this->demandInfoRepo->deleteByDemandId($id);

        return redirect()->route('demandlist.search');
    }

    /**
     * クロスセル専用
     *
     * @param  integer $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cross($id = null)
    {
        if (empty($id)) {
            return redirect()->route('demand.detail');
        }

        $demand = $this->demandInfoRepo->getDemandByIdWithRelations($id);

        $demand->cross_sell_source_site =
            isset($demand->site_id) ? $demand->site_id : '';

        $site = $this->mSite->getSiteByName(CROSS_SELLING_DEAL);
        $demand->site_id = $site['id'] ?? '';

        $this->businessDemandService->setData($demand);

        $demand = $this->businessDemandService->unsetData($demand, [
            'id',
            'selection_system_before',
            'commissionInfos',
            'IntroduceInfo',
            'demandAttachedFiles',
            'auction'
        ]);

        $selectionSystemList = $this->demandInfoService->getSelectionSystemList($demand);

        $siteId = null;
        if (!empty($demand->cross_sell_source_site)) {
            $site = $this->mSite->find($demand->cross_sell_source_site);
            $siteId = ($site && $site->cross_site_flg == 1) ? $site->id : null;
        }

        $data = $this->businessDemandService->getDataForDetail($demand, $siteId);
        $demandExtenInfoData = null;
        if ($id) {
            $demandExtenInfoData = $this->demandExtendInfoService->getAllByDemandId($id);
        }
        return view(
            'demand.detail',
            array_replace([
                'selectionSystemList' => $selectionSystemList,
                'demand' => $demand,
                'cross' => true,
                'id' => $id,
                'demandExtenInfoData' => $demandExtenInfoData
            ], $data)
        );
    }

    /**
     * Download file attach in demand
     *
     * @param  integer $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function demandFileDownload($id = null)
    {
        if ($id == null) {
            return abort(404);
        }

        $fileAttach = $this->demandAttachedFileRepo->findId($id);
        if (!file_exists($fileAttach->path)) {
            return abort(404);
        }

        return response()->download($fileAttach->path, $fileAttach->name);
    }
}
