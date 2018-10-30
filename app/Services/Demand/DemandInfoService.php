<?php

namespace App\Services\Demand;

use App\Models\DemandInfo;
use App\Models\MSite;
use App\Repositories\AccumulatedInformationsRepositoryInterface;
use App\Repositories\AutoCommissionCorpRepositoryInterface;
use App\Repositories\DemandInquiryAnsRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\Credit\CreditService;
use Cache;

class DemandInfoService extends BaseDemandInfoService
{
    /**
     * @var MGenresRepositoryInterface
     */
    public $mGenreRepo;

    /**
     * @var MCategoryRepositoryInterface
     */
    public $mCategoryRepo;

    /**
     * @var MUserRepositoryInterface
     */
    public $mUserRepo;



    /**
     * number of record each page
     *
     * @var integer
     */
    public $paginate = 500;

    /**
     * @var mixed
     */
    public $mUser;

    /**
     * @var DemandInquiryAnsRepositoryInterface
     */
    protected $demandInquiryAnswerRepo;


    /**
     * @var AccumulatedInformationsRepositoryInterface
     */
    protected $accumulatedInfoRepo;

    /**
     * @var ValidateDemandInfoService
     */
    protected $validateDemandInfoService;
    /**
     * @var DemandInfoMailService
     */
    protected $demandInfoMailService;
    /**
     * @var BusinessService
     */
    protected $businessService;
    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    protected $autoCommissionCorpRepo;

    /**
     * @var creditService
     */
    protected $creditService;

    /**
     * DemandInfoService constructor.
     * @param MGenresRepositoryInterface $mGenreRepo
     * @param MCategoryRepositoryInterface $mCategoryRepo
     * @param MUserRepositoryInterface $mUserRepo
     * @param DemandInquiryAnsRepositoryInterface $demandInquiryAnswerRepo
     * @param AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepo
     * @param AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo
     * @param BusinessService $businessService
     * @param ValidateDemandInfoService $validateDemandInfoService
     * @param DemandInfoMailService $demandInfoMailService
     * @param CreditService $creditService
     */
    public function __construct(
        MGenresRepositoryInterface $mGenreRepo,
        MCategoryRepositoryInterface $mCategoryRepo,
        MUserRepositoryInterface $mUserRepo,
        DemandInquiryAnsRepositoryInterface $demandInquiryAnswerRepo,
        AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepo,
        AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo,
        BusinessService $businessService,
        ValidateDemandInfoService $validateDemandInfoService,
        DemandInfoMailService $demandInfoMailService,
        CreditService $creditService
    ) {
        $this->mUserRepo = $mUserRepo;
        $this->mGenreRepo = $mGenreRepo;
        $this->mCategoryRepo = $mCategoryRepo;
        $this->demandInquiryAnswerRepo = $demandInquiryAnswerRepo;
        $this->autoCommissionCorpRepo = $autoCommissionCorpRepo;
        $this->accumulatedInfoRepo = $accumulatedInfoRepo;
        $this->validateDemandInfoService = $validateDemandInfoService;
        $this->demandInfoMailService = $demandInfoMailService;
        $this->businessService = $businessService;
        $this->creditService = $creditService;
    }

    /**
     * @param array $dataList
     * @return mixed
     */
    public function convertDataCsv($dataList)
    {
        $demandStatusList = getDropList(trans('demandlist.demand_status'));
        $orderFailReasonList = getDropList(trans('demandlist.order_fail_reason'));
        $siteList = $this->validateDemandInfoService->mSiteRepo->getList();
        $genreList = $this->mGenreRepo->getList();
        $categoryList = $this->mCategoryRepo->getList();
        $petTombstoneDemandList = getDropList(trans('demandlist.pet_tombstone_demand'));
        $smsDemandList = getDropList(trans('demandlist.sms_demand'));
        $jbrWorkContentsList = getDropList(trans('demandlist.jbr_work_contents'));
        $jbrCategoryList = getDropList(trans('demandlist.jbr_category'));
        $userList = $this->mUserRepo->dropDownUser();
        $jbrEstimateStatusList = getDropList(trans('demandlist.jbr_estimate_status'));
        $jbrReceiptStatusList = getDropList(trans('demandlist.jbr_receipt_status'));
        $sendMailFaxList = ['' => '', '0' => '', '1' => '送信済み'];
        $acceptanceStatusList = getDropList(trans('demandlist.acceptance_status'));
        $commissionStatusList = getDropList(trans('demandlist.commission_status'));
        $commissionOrderFailReasonList = getDropList(trans('demandlist.commission_order_fail_reason'));
        $selectionSystemList = getDivList('datacustom.selection_type', 'demandlist');
        $data = $dataList;

        $changeArray = [0 => 'mail_demand', 1 => 'nighttime_takeover', 2 => 'low_accuracy', 3 => 'remand',
            4 => 'immediately', 5 => 'corp_change', 6 => 'sms_reorder'];
        foreach ($dataList as $key => $val) {
            $data = $this->setArrayKey($key, $val, $changeArray, $data);

            $data[$key]->demand_status = !empty($val->demand_status) && isset($demandStatusList[$val->demand_status])
                ? $demandStatusList[$val->demand_status] : '';
            $data[$key]->order_fail_reason = !empty($val->order_fail_reason) && isset($orderFailReasonList[$val->order_fail_reason])
                ? $orderFailReasonList[$val->order_fail_reason] : '';
            $data[$key]->site_name = !empty($val->site_name) ? $val->site_name : '';
            $data[$key]->genre_name = !empty($val->genre_name) ? $val->genre_name : '';
            $data[$key]->category_name = !empty($val->category_name) ? $val->category_name : '';
            $data[$key]->cross_sell_source_site =
                (!empty($val->cross_sell_source_site) && isset($siteList[$val->cross_sell_source_site])) ? $siteList[$val->cross_sell_source_site] : '';
            $data[$key]->cross_sell_source_genre =
                (!empty($val->cross_sell_source_genre) && isset($genreList[$val->cross_sell_source_genre])) ? $genreList[$val->cross_sell_source_genre] : '';
            $data[$key]->cross_sell_source_category =
                (!empty($val->cross_sell_source_category) && isset($categoryList[$val->cross_sell_source_category])) ? $categoryList[$val->cross_sell_source_category] : '';
            $data[$key]->pet_tombstone_demand =
                (!empty($val->pet_tombstone_demand) && isset($petTombstoneDemandList[$val->pet_tombstone_demand])) ? $petTombstoneDemandList[$val->pet_tombstone_demand] : '';
            $data[$key]->sms_demand = (!empty($val->sms_demand) && isset($smsDemandList[$val->sms_demand])) ? $smsDemandList[$val->sms_demand] : '';
            $data[$key]->receptionist = (!empty($val->receptionist) && isset($userList[$val->receptionist])) ? $userList[$val->receptionist] : '';
            $data[$key]->address1 = !empty($val->address1) ? getDivTextJP('prefecture_div', $val->address1) : '';
            $data[$key]->jbr_work_contents =
                (!empty($val->jbr_work_contents) && isset($jbrWorkContentsList[$val->jbr_work_contents])) ? $jbrWorkContentsList[$val->jbr_work_contents] : '';
            $data[$key]->jbr_category = (!empty($val->jbr_category) && isset($jbrCategoryList[$val->jbr_category])) ? $jbrCategoryList[$val->jbr_category] : '';
            $data[$key]->jbr_estimate_status =
                (!empty($val->jbr_estimate_status) && isset($jbrEstimateStatusList[$val->jbr_estimate_status])) ? $jbrEstimateStatusList[$val->jbr_estimate_status] : '';
            $data[$key]->jbr_receipt_status =
                (!empty($val->jbr_receipt_status) && isset($jbrReceiptStatusList[$val->jbr_receipt_status])) ? $jbrReceiptStatusList[$val->jbr_receipt_status] : '';
            $data[$key]->contact_desired_time = !empty($val->contact_desired_time) ? $val->contact_desired_time : '';
            $data[$key]->acceptance_status =
                (!empty($val->acceptance_status) && isset($acceptanceStatusList[$val->acceptance_status])) ? $acceptanceStatusList[$val->acceptance_status] : '';
            $data[$key]->nitoryu_flg = !empty($val->nitoryu_flg) ? trans('demandlist.maru') : trans('demandlist.batu');
            $data[$key]->send_mail_fax = (!empty($val->send_mail_fax) && isset($sendMailFaxList[$val->send_mail_fax])) ? $sendMailFaxList[$val->send_mail_fax] : '';
            $data[$key]->commit_flg = !empty($val->commit_flg) ? trans('demandlist.maru') : trans('demandlist.batu');
            $data[$key]->commission_type =
                !empty($val->commission_type) ? trans('demandlist.bulk_quote') : trans('demandlist.normal_commission');
            $data[$key]->appointers = (!empty($val->appointers) && isset($userList[$val->appointers])) ? $userList[$val->appointers] : '';
            $data[$key]->first_commission =
                !empty($val->first_commission) ? trans('demandlist.maru') : trans('demandlist.batu');
            $data[$key]->tel_commission_person =
                (!empty($val->tel_commission_person) && isset($userList[$val->tel_commission_person])) ? $userList[$val->tel_commission_person] : '';
            $data[$key]->commission_note_sender =
                (!empty($val->commission_note_sender) && isset($userList[$val->commission_note_sender])) ? $userList[$val->commission_note_sender] : '';
            $data[$key]->commission_status =
                (!empty($val->commission_status) && isset($commissionStatusList[$val->commission_status])) ? $commissionStatusList[$val->commission_status] : '';
            $data[$key]->commission_order_fail_reason = (!empty($val->commission_order_fail_reason) && isset($commissionOrderFailReasonList[$val->commission_order_fail_reason]))
                ? $commissionOrderFailReasonList[$val->commission_order_fail_reason] : '';
            $data[$key]->selection_system =
                (!is_null($val->selection_system) && isset($selectionSystemList[$val->selection_system])) ? $selectionSystemList[$val->selection_system] : '';
        }

        return json_decode(json_encode($data), true);
    }

    /**
     * @param integer $key
     * @param array $val
     * @param array $changeArray
     * @param array $data
     * @return mixed
     */
    private function setArrayKey($key, $val, $changeArray, $data)
    {
        foreach ($changeArray as $v) {
            if ($val->$v == 0) {
                $data[$key]->$v = trans('demandlist.batu');
            } else {
                $data[$key]->$v = trans('demandlist.maru');
            }
        }
        return $data;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $data
     * @param string $sessionKeyForAffiliationSearch
     * @param string $sessionKeyForDemandSearch
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkAffiliationResults(
        $request,
        $data,
        $sessionKeyForAffiliationSearch,
        $sessionKeyForDemandSearch
    ) {
        if (empty($data["customer_tel"])) {
            return redirect()->route('demand.cti', [0, $data['site_tel']]);
        }
        if ($data["customer_tel"] == self::NON_NOTIFICATION) {
            return redirect()->route('demand.cti', [self::NON_NOTIFICATION, $data['site_tel']]);
        }
        $affiliationResults = $this->demandInfoMailService->mCorpRepo->searchAffiliationInfoAll($data['customer_tel']);
        if ($affiliationResults) {
            if (count($affiliationResults) == 1) {
                return redirect()->route('affiliation.detail.edit', [$affiliationResults[0]['id']]);
            } else {
                $affiliationSearch = [
                    "tel1" => $data['customer_tel'],
                    "support24hour" => "",
                    "affiliation_status" => "",
                ];

                $request->session()->put($sessionKeyForAffiliationSearch, $affiliationSearch);

                return redirect()->route('affiliation.index');
            }
        }

        $siteTel = $data["site_tel"];
        unset($data["site_tel"]);
        $results = $this->demandInfoMailService->demandRepo->getDemandInfo($data);
        $data["site_tel"] = $siteTel;
        if ($results->isEmpty()) {
            return redirect()->route('demand.cti', [$data['customer_tel'], $data['site_tel']]);
        }
        $results = $this->demandInfoMailService->demandRepo->getDemandInfo($data);
        if (empty($data['site_tel']) || $results->isEmpty()) {
            return $this->checkRedirectBySiteTel($sessionKeyForDemandSearch, $data, $request);
        } else {
            if ($results->count() == 1) {
                return redirect()->route('demand.detail', $results->first()->id);
            } else {
                $request->session()->put($sessionKeyForDemandSearch, $data);

                return redirect()->route('demandlist.search');
            }
        }
    }

    /**
     * check redirect by site tel
     *
     * @param  string $sessionKeyForDemandSearch
     * @param  array  $data
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function checkRedirectBySiteTel($sessionKeyForDemandSearch, &$data, $request)
    {
        $results = $this->validateDemandInfoService->mSiteRepo->searchMsite($data['site_tel']);
        if (0 == count($results)) {
            $searchCustmerTelResults = $this->businessService->searchCustmerTel($data['customer_tel']);
            if (0 < count($searchCustmerTelResults)) {
                $data['site_tel'] = "";
                $request->session()->put($sessionKeyForDemandSearch, $data);
                return redirect()->route('demandlist.search');
            }
        }
        return redirect()->route('demand.cti', [$data['customer_tel'], $data['site_tel']]);
    }

    /**
     * @param $corpData
     * @return array
     */
    public function getMailAndFaxByCorpData($corpData)
    {
        return $this->demandInfoMailService->getMailAndFaxByCorpData($corpData);
    }

    /**
     * @param DemandInfo|null $demand
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getSelectionSystemList(DemandInfo $demand = null)
    {
        if (!$demand) {
            return $this->translateSelectionSystem();
        }
        $selection = $this->getSelectionSystem($demand);

        return !empty($selection) ? $selection : $this->translateSelectionSystem();
    }

    /**
     * @param DemandInfo|null $demand
     * @return array
     */
    private function getSelectionSystem(DemandInfo $demand = null)
    {
        $selectionSystem = $this->getSelectType($demand);
        $translateSystem = $this->translateSelectionSystem();

        return $demand != null ? $selectionSystem : $translateSystem;
    }

    /**
     * @param DemandInfo|null $demand
     * @return false|int|string
     */
    private function getSelectType(DemandInfo $demand = null)
    {
        $genreId = $demand->genre_id;
        $address1 = $demand->address1;

        $selectionSystem = '';

        if (!empty($address1)) {
            $genrePrefecture = $this->validateDemandInfoService->selectGenrePrefectureRepo->getByGenreIdAndPrefectureCd([
                'genre_id' => $genreId,
                'address1' => $address1
            ]);

            if ($genrePrefecture) {
                $selectionSystem = $genrePrefecture->selection_type;
            }
        }

        if ($selectionSystem === '') {
            $selectGenre = $this->validateDemandInfoService->selectGenreRepo->findByGenreId($genreId);

            if ($selectGenre) {
                $selectionSystem = $selectGenre->select_type;
            }
        }

        $defaultSelection = getDivValue('selection_type', 'manual_selection');
        $option = [$selectionSystem => __('auto_commission_corp.' . config('rits.selection_type')[(int)$selectionSystem])];
        return array_unique(array_replace(
            [
                $defaultSelection => __('auto_commission_corp.' . config('rits.selection_type')['0'])
            ],
            $option
        ));
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateDemand($data)
    {
        return $this->demandInfoMailService->updateDemand($data);
    }

    /**
     * [buildAutoCommissionCorpData description]
     *
     * @author thaihv
     * @param  array $data request data
     * @param  \Illuminate\Support\Collection $commissionCorps commissioncorp data
     * @param  \Illuminate\Support\Collection $newCommissionCorps new commissioncorp data
     * @param  array $autoCommissionCorp auto commission corp data
     * @param $defaultFee
     * @param $autoCommissionSelectionLimit
     * @return array  auto commissioncorp data, allreadycommission, auto commisionselectlimit
     */
    private function buildAutoCommissionCorpData(
        $data,
        $commissionCorps,
        $newCommissionCorps,
        $autoCommissionCorp,
        $defaultFee,
        $autoCommissionSelectionLimit
    ) {
        $autoCommissionCorpData = [];
        $alreadyCommissions = false;
        $commissionSelectionLimitCount = 0;
        foreach ($autoCommissionCorp as $autoCorp) {
            if (isset($data['commissionInfo'])) {
                foreach ($data['commissionInfo'] as $commission) {
                    if ($autoCorp->corp_id == $commission['corp_id']) {
                        $alreadyCommissions = true;
                        break;
                    }
                }
            }
            if ($alreadyCommissions) {
                continue;
            }
            $targetCommissionCorp = null;
            // Search for selection information matching category ID registered in category x prefecture
            foreach ($newCommissionCorps as $newCommissionCorp) {
                if ($newCommissionCorp->MCorp_id == $autoCorp->corp_id) {
                    $targetCommissionCorp = $newCommissionCorp;
                    break;
                }
            }

            if ($targetCommissionCorp === null) {
                foreach ($commissionCorps as $commissionCorp) {
                    if ($commissionCorp->MCorp_id == $autoCorp->corp_id) {
                        $targetCommissionCorp = $commissionCorp;
                        break;
                    }
                }
            }
            // wtf use for?
            if ($targetCommissionCorp === null) {
                continue;
            }
            //Credit check
            $resultCredit = $this->creditService->checkCredit(
                $targetCommissionCorp->MCorp_id,
                $data['demandInfo']['genre_id'],
                false,
                true
            );
            if ($resultCredit == config('constant.CREDIT_DANGER')) {
                continue;
            }

            $commitFlg = 0;

            // Add processing type to finalization condition
            if ($targetCommissionCorp->MCorpCategory_corp_commission_type != 2) { //  what the 2
                // Conclusion base
                $orderFee = $targetCommissionCorp->MCorpCategory_order_fee;
                $orderFeeUnit = $targetCommissionCorp->MCorpCategory_order_fee_unit;
                $commissionStatus = getDivValue('construction_status', 'progression');
                $lostFlg = 0;
                $introductionNot = null;
                $commissionType = getDivValue('commission_type', 'normal_commission');
            } else {
                // Introduction base
                $orderFee = $targetCommissionCorp->MCorpCategory_introduce_fee;
                $orderFeeUnit = 0;
                $commissionStatus = getDivValue('construction_status', 'introduction');
                /* In the case of Null since it does not match the condition of mail information acquisition,
                it is modified
                */
                $lostFlg = 0;
                $introductionNot = 0;
                $commissionType = getDivValue('commission_type', 'package_estimate');
            }
            if ($autoCorp->process_type == "2"  // 1: Automatic selection 2: Automatic transfer
                || $commissionSelectionLimitCount > 0
            ) {
                if ($autoCommissionSelectionLimit > $commissionSelectionLimitCount) {
                    $commitFlg = 1;
                } else {
                    $lostFlg = 1;
                }
            }
            //fee
            $orderFee = !empty($orderFee) ? $orderFee : $defaultFee->category_default_fee;
            $orderFeeUnit = !empty($orderFee) ? $orderFeeUnit : $defaultFee->category_default_fee_unit;
            $autoCommissionCorpData[] = [
                'mCorp' => $this->setMCorp($targetCommissionCorp),
                'corp_fee' => $orderFeeUnit == 0 ? $orderFee : null, // Brokerage fee
                'commission_fee_rate' => $orderFeeUnit == 0 ? null : $orderFee, // Commission rate at commission
                'order_fee_unit' => $orderFeeUnit,
                'commission_status' => $commissionStatus,
                'lost_flg' => $lostFlg, // Prior to ordering
                'introduction_not' => $introductionNot, //Can not introduce
                'commission_type' => $commissionType,
                'corp_id' => $targetCommissionCorp->MCorp_id,
                'del_flg' => 0, //Delete
                'appointers' => auth()->id(), // Selector
                'first_commission' => 0,
                'unit_price_calc_exclude' => 0,
                'corp_claim_flg' => 0, //Agency complaint
                'commit_flg' => $commitFlg, // Confirm
                'commission_note_sender' => $commitFlg === 1 ? auth()->id() : null, // Mail order sender
                //Date and time of agency sent
                'commission_note_send_datetime' => $commitFlg === 1 ? date('Y/m/d H:i:s') : null,
                'select_commission_unit_price_rank' => $this->setCommissionUnitPriceRank($targetCommissionCorp),
                'select_commission_unit_price' => $this->setCommissionUnitPrice($targetCommissionCorp),
                'created_user_id' => 'AutoCommissionCorp',
                'modified_user_id' => 'AutoCommissionCorp',
                'send_mail_fax' => 1,
                'send_mail_fax_othersend' => 0,
            ];

            //It is not necessary to limit the count, but just in case
            //The count for the fixed upper limit number is incremented by +1
            $commissionSelectionLimitCount = $this->countCommissionSelectionLimit($commitFlg, $commissionSelectionLimitCount);
        }

        return [
            'autoCommissionCorpData' => $autoCommissionCorpData,
            'alreadyCommissions' => $alreadyCommissions,
            'autoCommissionSelectionLimitCount' => $commissionSelectionLimitCount,
        ];
    }

    /**
     * @param object $targetCommissionCorp
     * @return array
     */
    private function setMCorp($targetCommissionCorp)
    {
        return [
            'fax' => $targetCommissionCorp->MCorp_fax,
            'mailaddress_pc' => $targetCommissionCorp->MCorp_mailaddress_pc,
            'coordination_method'  => $targetCommissionCorp->MCorp_coordination_method,
            'contactable_time' => $targetCommissionCorp->MCorp_contactable_time_from
                . ' - ' . $targetCommissionCorp->MCorp_contactable_time_to,
            'holiday' => $targetCommissionCorp->MCorp__holiday,
            'corp_name' => $targetCommissionCorp->MCorp_corp_name,
            'commission_dial' => $targetCommissionCorp->MCorp_commission_dial,
        ];
    }

    /**
     * @param object $targetCommissionCorp
     * @return null
     */
    private function setCommissionUnitPrice($targetCommissionCorp)
    {
        if (isset($targetCommissionCorp->AffiliationAreaStat_commission_unit_price_category)
            && !empty($targetCommissionCorp->AffiliationAreaStat_commission_unit_price_category)
        ) {
            return $targetCommissionCorp->AffiliationAreaStat_commission_unit_price_category;
        } else {
            return null;
        }
    }

    /**
     * @param object $targetCommissionCorp
     * @return null
     */
    private function setCommissionUnitPriceRank($targetCommissionCorp)
    {
        if (isset($targetCommissionCorp->AffiliationAreaStat_commission_unit_price_rank)
            && !empty($targetCommissionCorp->AffiliationAreaStat_commission_unit_price_rank)
        ) {
            return $targetCommissionCorp->AffiliationAreaStat_commission_unit_price_rank;
        } else {
            return null;
        }
    }

    /**
     * @param boolean $commigFlag
     * @param integer $count
     * @return mixed
     */
    private function countCommissionSelectionLimit($commigFlag, $count)
    {
        if ($commigFlag === 1) {
            $count++;
        }
        return $count;
    }

    /**
     * @param integer $categoryId
     * @param array $commissionInfoData
     * @return mixed
     */
    private function updateCommissionData($categoryId, $commissionInfoData)
    {
        $commissionTypeCategory = $this->mCategoryRepo->getCommissionType($categoryId);
        $maxCommission = config('rits.demand_max_commission'); // max commission for demand 30
        for ($cnt = 0; $cnt < $maxCommission; $cnt++) {
            if (isset($commissionInfoData[$cnt]) && $commissionInfoData[$cnt]["corp_id"] == 3539) {
                if ($commissionTypeCategory != 2) {
                    // Interaction type = contract-based basis
                    $commissionInfoData[$cnt]['commission_fee_rate'] = 999;
                }

                break;
            } elseif (isset($commissionInfoData[$cnt]) && (int)$commissionInfoData[$cnt]["corp_id"] == 0) {
                $commissionInfoData[$cnt]["corp_id"] = 3539;
                $commissionInfoData[$cnt]["commit_flg"] = 1;
                $commissionInfoData[$cnt]["mCorp"]["corp_name"] = "【SF用】取次前失注用(質問のみ等)";

                if ($commissionTypeCategory != 2) {
                    // Interaction type = contract-based basis
                    $commissionInfoData[$cnt]['commission_fee_rate'] = 999;
                    $commissionInfoData[$cnt]['corp_commission_type'] = 1;
                } else {
                    // Interaction type = contract-based basis
                    $commissionInfoData[$cnt]['corp_commission_type'] = 2;
                }

                break;
            }
        }

        return $commissionInfoData;
    }

    /**
     * @param array $demandInfo
     * @param array $mailList
     * @param object $mailInfo
     * @return bool
     */
    public function sendMail($demandInfo, $mailList, $mailInfo)
    {
        return $this->demandInfoMailService->sendMail($demandInfo, $mailList, $mailInfo);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function checkModifiedDemand($data)
    {
        return $this->demandInfoMailService->checkModifiedDemand($data);
    }

    /**
     * @author thaihv
     * @param  array $data demand info data
     * @return array all data processed
     */
    public function processDataWithoutQuickOrder($data)
    {
        //Ref. Upper limit reference
        $resultCrossSiteFlg = $this->validateDemandInfoService->mSiteRepo->getCrossSiteFlg(MSite::CROSS_FLAG);
        if ($data['demandInfo']['demand_status'] == getDivValue('demand_status', 'no_selection')
            && (!isset($data['quick_order_fail'])
            &&  $data["demandInfo"]["quick_order_fail_reason"] == "")
            && (!in_array($data["demandInfo"]["site_id"], $resultCrossSiteFlg))
            && (!in_array($data["demandInfo"]["site_id"], config('rits.arrSiteId')))
        ) {
            // Acquisition of selection targets
            $jisCd = $this->getTargetJisCd($data["demandInfo"]["address1"], null);
            $commissionConditions = [
                'category_id' => $data['demandInfo']['category_id'],
                'jis_cd' => $jisCd,
                'target_check' => ''
            ];
            // if empty corp => flash error
            $commissionCorps = $this->businessService->getCommissionCorpsBy($commissionConditions);
            $newCommissionCorps = $this->businessService->getCommissionCorpsBy($commissionConditions, 'new');
            /*
            Acquire prefecture and category of case
            Acquire eligible merchants
             */
            $add1 = $data["demandInfo"]["address1"];
            $category = $data["demandInfo"]['category_id'];
            $autoCommissionCorp = $this->autoCommissionCorpRepo->getAutoCommissionCorp($add1, $category, null, false);
            /**
             * if $data['selection_system'] == 2 || $data['selection_system'] == 3
             * return auction_selection_limit
             * other case => manual_selection_limit
             * on the site belongs to "Selection method" (選定方式) item when select site
             */
            try {
                $autoCommissionSelectionLimit = $this->validateDemandInfoService->mSiteRepo->getMaxLimitDemandCreate($data['demandInfo'], true);
            } catch (\Exception $e) {
                session()->flash('error_msg_input', $e->getMessage());
                return false;
            }
            //Whether the franchise chosen by the user was covered with an automatically selected franchise
            //Accept default default commission and default fee unit for specified category
            $defaultFee = $this->mCategoryRepo->getDefaultFee($data['demandInfo']['category_id']);
            /**
             * build data
             * autoCommissionCorpData
             * alreadyCommissions
             * autoCommissionSelectionLimitCount
             */
            $allData = $this->buildAutoCommissionCorpData(
                $data,
                $commissionCorps,
                $newCommissionCorps,
                $autoCommissionCorp,
                $defaultFee,
                $autoCommissionSelectionLimit
            );
            $autoCommissionCorpData = $allData['autoCommissionCorpData'];
            $alreadyCommissions = $allData['alreadyCommissions'];
            $commissionSelectionLimitCount = $allData['autoCommissionSelectionLimitCount'];
            // check error restore
            if (empty($autoCommissionCorpData)) {
                /*On the screen, if it was indicated that there was an automatic
                supplier but it did not exist in some sort of irregular */
                //However, exclude cases that can not be determined because what was already selected by the user
                if (isset($data['display_auto_commission_message'])
                    && $data['display_auto_commission_message'] == "1"
                    && !$alreadyCommissions
                ) {
                    session()->flash('again_enabled', true);
                    session()->flash('error_msg_input', __('demand.notExistsAutoCommissionCorp'));
                    return false;
                }
            } else {
                $selectionSystem = getDivValue('selection_type', 'auto_selection');
                $informationSent = getDivValue('demand_status', 'information_sent');
                $agencyBefore = getDivValue('demand_status', 'agency_before');
                $manualSelection = getDivValue('selection_type', 'manual_selection');
                $data['restore_at_error'] = $this->demandInfoMailService->buildRestoreAtError(
                    $selectionSystem,
                    $data['demandInfo'],
                    $commissionSelectionLimitCount,
                    $data
                );
                //Store items to be restored when an error occurs
                $data['demandInfo'] = $this->demandInfoMailService->updateDemandInfoDataByCorp(
                    $informationSent,
                    $agencyBefore,
                    $manualSelection,
                    $selectionSystem,
                    $data['demandInfo'],
                    $commissionSelectionLimitCount
                );
                if ($commissionSelectionLimitCount > 0) {
                    //Since there is an automatic destination, flag the mail transmission
                    $data['send_commission_info'] = 1;
                }
                $data['commissionInfo'] = array_merge($autoCommissionCorpData, $data['commissionInfo']);
            }
        }
        return $data;
    }


    /**
     * @author  thaihv
     * @param string $address1
     * @param string $address2
     * @return string
     */
    private function getTargetJisCd($address1, $address2)
    {
        return $this->businessService->getTargetJisCd($address1, $address2);
    }
    /**
     * @author thaihv
     * @param  array $data all data
     * @return array
     */
    public function processQuickOrderFail($data)
    {

        if (!empty($data['quick_order_fail'])
            && $data['demandInfo']['quick_order_fail_reason'] !="") {
            $data['demandInfo']['do_auction'] = 0;
            $data['demandInfo']['do_auto_selection'] = 0;
            // update demand info
            $data['demandInfo'] = $this->updateDemandInfoDataByQuickOrder($data['demandInfo']);
            //update demand correspond
            $data['demandCorrespond'] = $this->updateCorrespondContent($data['demandCorrespond']);
            //update commission info
            if (isset($data['commissionInfo'])) {
                $data['commissionInfo'] = $this->updateCommissionData(
                    $data['demandInfo']['category_id'],
                    $data['commissionInfo']
                );
            }
        }

        return $data;
    }

    /**
     * processing auction selection
     * @author thaihv
     * @param  array $data source data
     * @return array       data (auctionFlg, data, auctionNoneFlg,  hasStartTimeErr)
     */
    public function processAuctionSelection($data)
    {
        $auctionFlg = true;
        $auctionNoneFlg = true;
        $hasStartTimeErr = false;
        // Auction selection only
        $arraySelection = [getDivValue('selection_type', 'auction_selection') ,
                getDivValue('selection_type', 'automatic_auction_selection')]; //[2,3]
        if (isset($data['demandInfo']['selection_system'])
            && (in_array($data['demandInfo']['selection_system'], $arraySelection))
            && !empty($data['demandInfo']['do_auction'])
            && $data['demandInfo']['do_auto_selection_category'] == 0
        ) {
            // Only for auction process execution
            // ADD start In case of automatic consignment, auction is not selected
            // 【Bidding ceremony】 In case of re-bidding, recalculate priority
            if ($data['demandInfo']['do_auction'] == 2) {
                $data['demandInfo']['priority'] = '';
            }
            // Auction start date and time
            $data['demandInfo']['auction_start_time'] = date('Y-m-d H:i:s');
            // Auction closing date and time
            $data['demandInfo']['auction_deadline_time'] = '';
            // Proposal status
            $data['demandInfo']['demand_status'] = 3; //agency_before status
            // Get the minimum visit date and time, date of contact request
            list($data,$preferredDate)  = $this->setDataByVisitTime($data);

            $auctionStartTime = strtotime($data['demandInfo']['auction_start_time']);
            $preferDate = strtotime($preferredDate);
            // If the case creation date and the desired date are reversed, manual selection is made
            if ($preferDate < $auctionStartTime) {
                $auctionFlg = false;
                $auctionNoneFlg = false;
                $hasStartTimeErr = true;
            } else {
                // When the priority is not set yet
                $judgeResult = $this->buildJudeResult($data['demandInfo'], $preferredDate);

                if ($judgeResult['result_flg'] == 1) {
                    // Auction start date and time
                    if (!empty($judgeResult['result_date'])) {
                        $data['demandInfo']['auction_start_time'] = $judgeResult['result_date'];
                    }
                    // Process for recalculating priority <=> 優先度再計算用処理
                    // When the priority is changed, the determination for each priority is performed again
                    $judgeResult = judgeAuction(
                        $data['demandInfo']['auction_start_time'],
                        $preferredDate,
                        $data['demandInfo']['genre_id'],
                        $data['demandInfo']['address1'],
                        $data['demandInfo']['auction_deadline_time'],
                        $data['demandInfo']['priority']
                    );
                    if ($judgeResult['result_flg'] == 0) {
                        $auctionFlg = true;
                    } else {
                        $auctionFlg = false;
                    }
                }

                // Whether or not there is a member shop subject to auction (0 is false)
                $auctionNoneFlg = $this->businessService->checkAuctionBy($data);
            }
            // Outside auction selection time or when there are 0 target franchise stores
            $data['demandInfo'] = $this->updateDemandInfoDataByFlg($data['demandInfo'], $auctionFlg, $auctionNoneFlg);
        }
        return [
                'data' => $data, 'auctionFlg' => $auctionFlg,
                'auctionNoneFlg' => $auctionNoneFlg, 'hasStartTimeErr' => $hasStartTimeErr
            ];
    }

    /**
     * @param array $data
     * @param array $visitTimeList
     * @return array
     */
    private function setDataByVisitTime($data, $visitTimeList = [])
    {
        foreach ($data['visitTime'] as $val) {
            if ($val['is_visit_time_range_flg'] == 0 && strlen($val['visit_time']) > 0) {
                $visitTimeList[] = $val['visit_time'];
            }
            if ($val['is_visit_time_range_flg'] == 1 && strlen($val['visit_time_from']) > 0) {
                $visitTimeList[] = $val['visit_time_from'];
            }
        }
        if (!empty($visitTimeList)) {
            // When to use the visit date and time
            $preferredDate = getMinVisitTime($visitTimeList);
            $data['demandInfo']['method'] = 'visit';
        } else {
            // When using the contact date and time desired
            $preferredDate = $this->setPreferredDateBy($data);

            $data['demandInfo']['method'] = 'tel';
        }

        return [$data, $preferredDate];
    }

    /**
     * @param array $data
     * @return null
     */
    private function setPreferredDateBy($data)
    {
        if (isset($data['demandInfo']['is_contact_time_range_flg']) && $data['demandInfo']['is_contact_time_range_flg'] == 0) {
            $preferredDate = $data['demandInfo']['contact_desired_time'];
        }
        if (isset($data['demandInfo']['is_contact_time_range_flg']) && $data['demandInfo']['is_contact_time_range_flg'] == 1) {
            $preferredDate = $data['demandInfo']['contact_desired_time_from'];
        }
        return isset($preferredDate) ? $preferredDate : null;
    }

    /**
     * processing data selection system
     * @author thaihv
     * @param  array $data source data
     * @return array       data
     */
    public function processDataWithSelectionSystem($data)
    {
        if ($data['demandInfo']['selection_system'] != getDivValue('selection_type', 'auto_selection')
            || $data['demandInfo']['demand_status'] != getDivValue('demand_status', 'no_selection')
            ) {
            return $data;
        }
        // In case of automatic selection, return to manual selection after selection
        $data['demandInfo']['selection_system'] = getDivValue('selection_type', 'manual_selection');
        // Only for automatic selection process execution
        // In case of automatic consignment, auction is not selected
        if (empty($data['demandInfo']['do_auto_selection'])
            || $data['demandInfo']['do_auto_selection_category'] != 0) {
            return $data;
        }
        $commissionInfos = [];
        $demandId = (array_key_exists('id', $data['demandInfo'])) ? $data['demandInfo']['id'] : null;
        // Move existing process to Component, move to AuctionInfoUtil -> update_auction_infos
        $autoCommissions = $this->businessService->getAuctionForAutoCommissionByDemandId($demandId, $data);

        $defaultFee = $this->mCategoryRepo->getDefaultFee($data['demandInfo']['category_id']);

        // Extract selected manual destination
        $commissionInfos = $this->setDataForCommissionByCorpId($data['commissionInfo'], $commissionInfos);

        if (is_array($autoCommissions) && !empty($autoCommissions)) {
            $commissionInfosData = $this->businessService->buildCommission($autoCommissions, $defaultFee, $commissionInfos);
        }
        if (!empty($commissionInfosData['commissionInfos'])) {
            if (!empty($commissionInfosData['isSelected'])) {
                $data['demandInfo']['demand_status'] = getDivValue('demand_status', 'agency_before');
            }
            $data['commissionInfo'] = $commissionInfosData['commissionInfos'];
        }
        return $data;
    }

    /**
     * @param array $commissionInfo
     * @param array $results
     * @return array
     */
    private function setDataForCommissionByCorpId($commissionInfo, $results = [])
    {
        // Extract selected manual destination
        foreach ($commissionInfo as $val) {
            if (!empty($val['corp_id'])) {
                $results[] = $val;
            }
        }
        return $results;
    }

    /**
     * set pre demand
     * @param string $customerTel
     * @param string $siteTel
     * @return array
     */
    public function setPreDemand($customerTel, $siteTel)
    {
        $demandInfo = $this->demandInfoMailService->demandRepo->getFirstDemandByTel($customerTel);
        $mSite = $this->validateDemandInfoService->mSiteRepo->getFirstSiteByTel($siteTel);
        if ($demandInfo) {
            $dStatus = $demandInfo->demand_status;
        } else {
            $dStatus = "";
        }

        if ($mSite) {
            $siteId = $mSite->id;
        } else {
            $siteId = "";
        }
        return [
            'customer_tel' => $customerTel,
            'site_id' => $siteId,
            'receptionist' => auth()->id(),
            'demand_status' => $dStatus,
            'commission_limitover_time' => 0
        ];
    }

    /**
     * Execute demand guide send mail
     */
    public function executeDemandGuideSendMail()
    {
        $this->demandInfoMailService->executeDemandGuideSendMail();
    }

    /**
     * validate data of demand info
     * @param $attributes
     * @return mixed
    */
    public function validateDemandInfo($attributes)
    {
        return $this->validateDemandInfoService->validateDemandInfo($attributes);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function makeCommissionInfoData($data)
    {
        return $this->demandInfoMailService->makeCommissionInfoData($data);
    }

    /**
     * @param $demandId
     * @return bool
     */
    public function checkDemandLocked($demandId)
    {
        $locked = Cache::get('locked_' . $demandId);
        if ($locked) {
            return true;
        }
        Cache::put('locked_' . $demandId, auth()->id(), 5);
        return false;
    }

    /**
     * @param $demandId
     */
    public function unlockDemand($demandId)
    {
        Cache::forget('locked_' . $demandId);
    }
}
