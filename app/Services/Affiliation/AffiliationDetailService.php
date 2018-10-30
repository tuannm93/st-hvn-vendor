<?php

namespace App\Services\Affiliation;

use App\Helpers\MailHelper;
use App\Mail\CorpResponsibility;
use App\Mail\StResponsibility;
use App\Repositories\AffiliationCorrespondsRepositoryInterface;
use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\AffiliationSubsRepositoryInterface;
use App\Repositories\AntisocialCheckRepositoryInterface;
use App\Repositories\MCorpNewYearRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\CommissionInfoService;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class AffiliationDetailService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var MCorpSubRepositoryInterface
     */
    protected $mCorpSubRepository;
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    protected $affiliationInfoRepository;
    /**
     * @var AffiliationSubsRepositoryInterface
     */
    protected $affiliationSubRepository;
    /**
     * @var AffiliationCorrespondsRepositoryInterface
     */
    protected $affCorrespondsRepository;
    /**
     * @var AntisocialCheckRepositoryInterface
     */
    protected $antisocialCheckRepository;
    /**
     * @var MUserRepositoryInterface
     */
    protected $mUserRepository;
    /**
     * @var MCorpNewYearRepositoryInterface
     */
    protected $mCorpNewYearRepository;
    /**
     * @var CommissionInfoService
     */
    protected $commissionInfoService;

    /**
     * AffiliationDetailService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpSubRepositoryInterface $mCorpSubRepository
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     * @param AffiliationSubsRepositoryInterface $affiliationSubRepository
     * @param AffiliationCorrespondsRepositoryInterface $affCorrespondsRepository
     * @param AntisocialCheckRepositoryInterface $antisocialCheckRepository
     * @param MUserRepositoryInterface $mUserRepository
     * @param MCorpNewYearRepositoryInterface $mCorpNewYearRepository
     * @param CommissionInfoService $commissionInfoService
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        MCorpSubRepositoryInterface $mCorpSubRepository,
        AffiliationInfoRepositoryInterface $affiliationInfoRepository,
        AffiliationSubsRepositoryInterface $affiliationSubRepository,
        AffiliationCorrespondsRepositoryInterface $affCorrespondsRepository,
        AntisocialCheckRepositoryInterface $antisocialCheckRepository,
        MUserRepositoryInterface $mUserRepository,
        MCorpNewYearRepositoryInterface $mCorpNewYearRepository,
        CommissionInfoService $commissionInfoService
    ) {
        $this->mCorpRepository = $mCorpRepository;
        $this->mCorpSubRepository = $mCorpSubRepository;
        $this->affiliationInfoRepository = $affiliationInfoRepository;
        $this->affiliationSubRepository = $affiliationSubRepository;
        $this->affCorrespondsRepository = $affCorrespondsRepository;
        $this->antisocialCheckRepository = $antisocialCheckRepository;
        $this->mUserRepository = $mUserRepository;
        $this->mCorpNewYearRepository = $mCorpNewYearRepository;
        $this->commissionInfoService = $commissionInfoService;
    }

    /**
     * Delete old corp subs base on corp Id and item category
     *
     * @param integer $corpId
     * @param string $itemCategory
     * @return mixed
     */
    private function deleteOldCorpSubs($corpId, $itemCategory)
    {
        if (!empty($corpId)) {
            $conditions = [
                'corp_id' => $corpId,
                'item_category' => $itemCategory
            ];
            $delete = $this->mCorpSubRepository->deleteByCondition($conditions);
            return $delete;
        }
        return false;
    }

    /**
     * Delete old affiliation subs base on affiliation Id and item category
     *
     * @param integer $affiliationId
     * @param string $itemCategory
     * @return boolean
     */
    private function deleteOldAffiliationSubs($affiliationId, $itemCategory)
    {
        if (!empty($affiliationId)) {
            $conditions = [
                'affiliation_id' => $affiliationId,
                'item_category' => $itemCategory
            ];
            $delete = $this->affiliationSubRepository->deleteByCondition($conditions);
            return $delete;
        }
        return false;
    }

    /**
     * Save new corp subs
     *
     * @param integer $corpId
     * @param string $itemCategory
     * @param array $rawData
     * @return boolean
     */
    private function saveNewCorpSubs($corpId, $itemCategory, $rawData)
    {
        if (count($rawData) > 0) {
            $saveData = [];
            foreach ($rawData as $v) {
                $data['corp_id'] = $corpId;
                $data['item_category'] = $itemCategory;
                $data['item_id'] = $v;
                $data['created'] = date('Y-m-d H:i:s');
                $data['modified'] = date('Y-m-d H:i:s');
                $data['created_user_id'] = Auth::getUser()->user_id;
                $data['modified_user_id'] = Auth::getUser()->user_id;
                $saveData[] = $data;
            }
            $new = $this->mCorpSubRepository->insert($saveData);
            return $new;
        }

        return true;
    }

    /**
     * Save new affiliation subs
     *
     * @param integer $affiliationId
     * @param string $itemCategory
     * @param array $rawData
     * @return boolean
     */
    private function saveNewAffiliationSubs($affiliationId, $itemCategory, $rawData)
    {
        if (!empty($rawData)) {
            $saveData = [];
            foreach ($rawData as $v) {
                $data['affiliation_id'] = $affiliationId;
                $data['item_category'] = $itemCategory;
                $data['item_id'] = $v;
                $data['created'] = date('Y-m-d H:i:s');
                $data['modified'] = date('Y-m-d H:i:s');
                $data['created_user_id'] = Auth::getUser()->user_id;
                $data['modified_user_id'] = Auth::getUser()->user_id;
                $saveData[] = $data;
            }
            $new = $this->affiliationSubRepository->insert($saveData);
            return $new;
        }
        return true;
    }

    /**
     * Creation of company master incidental information
     *
     * @param integer $corpId
     * @param array $data
     * @return boolean
     */
    public function editMCorpSub($corpId, $data)
    {
        // Delete old corp subs (holiday + development_reaction)
        $this->deleteOldCorpSubs($corpId, config('constant.holiday'));
        $this->deleteOldCorpSubs($corpId, config('constant.development_reaction'));

        // Get new data - holiday and development response
        $s1 = $this->saveNewCorpSubs($corpId, config('constant.holiday'), $data['holiday']);
        $s2 = $this->saveNewCorpSubs($corpId, config('constant.development_reaction'), $data['development_reaction']);

        $result = ($s1 && $s2) ? true : false;
        return $result;
    }

    /**
     * Register merchant information
     *
     * @param integer $corpId
     * @param array $data
     * @return boolean|integer
     */
    public function editAffiliation($corpId, $data)
    {
        $fileName = "";

        $data['id'] = isset($data['affiliation_id']) ? $data['affiliation_id'] : '';
        $data['corp_id'] = $corpId;

        if (empty($data['id'])) {
            $data['commission_count'] = 0;
        }
        $data['default_tax'] = (empty($data['default_tax'])) ? false : true;

        // Registration Form PDF
        if (!empty($data['reg_pdf_path'])) {
            // Get upload file extension
            $tempFile = $data['reg_pdf_path'];
            $extension = pathinfo($tempFile->getClientOriginalName(), PATHINFO_EXTENSION);

            // Edit upload file name
            $fileName = "registration_" . $corpId . "." . $extension;
        }

        $data['reg_pdf_path'] = $fileName;
        $saveData = formatDataBaseOnTable('affiliation_infos', $data);

        $saveData['modified'] = date('Y-m-d H:i:s');
        $saveData['modified_user_id'] = Auth::getUser()->user_id;

        if (empty($data['id'])) {
            // Register
            $saveData['created'] = date('Y-m-d H:i:s');
            $saveData['created_user_id'] = Auth::getUser()->user_id;
            $lastAffiliationId = $this->affiliationInfoRepository->insertGetId($saveData);
        } else {
            $this->affiliationInfoRepository->updateById($data['id'], $saveData);
            $lastAffiliationId = $data['id'];
        }

        if ($lastAffiliationId) {
            if (!empty($tempFile)) {
                // file upload
                $pdfDir = storage_path('upload/registration');
                if(!is_dir($pdfDir)){
                    mkdir($pdfDir, 0777, true);
                }
                $moveFile = $tempFile->move($pdfDir, $fileName);
                if (!$moveFile) {
                    return false;
                }
            }
            return $lastAffiliationId;
        } else {
            return false;
        }
    }

    /**
     * Registration of affiliated store incidental information
     *
     * @param integer $affiliationId
     * @param array $data
     * @return boolean
     */
    public function editAffiliationSubs($affiliationId, $data)
    {
        if ($affiliationId) {
            // Delete old condition
            $this->deleteOldAffiliationSubs($affiliationId, config('constant.stop_category'));

            // Save new condition
            $save = $this->saveNewAffiliationSubs($affiliationId, config('constant.stop_category'), $data);

            $result = $save ? true : false;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Registration of merchant correspondence history
     *
     * @param  integer  $id
     * @param  array $data
     * @return boolean
     */
    public function registHistory($id = null, $data = [])
    {
        $data['corp_id'] = $id;
        if (!empty($data['responders']) || !empty($data['corresponding_contens'])) {
            $data['modified_user_id'] = Auth::getUser()->user_id;
            $data['created_user_id'] = Auth::getUser()->user_id;
            $data['modified'] = date('Y-m-d H:i:s');
            $data['created'] = date('Y-m-d H:i:s');
            $register = $this->affCorrespondsRepository->insert($data);
            return $register;
        } else {
            return true;
        }
    }

    /**
     * Save or edit mCorp.
     * If save new mCorp, id is null
     *
     * @param array $data
     * @param integer $id
     * @return boolean | integer
     */
    public function saveOrEditMCorp($data, $id = null)
    {
        $setValue = $this->setValueForSaveData($data);
        $data = $setValue['data'];
        $isAntisocialCreate = $setValue['isAntisocialCreate'];

        if (!empty($id) && Auth::getUser()->auth == 'affiliation') {
            $originalCorp = $this->mCorpRepository->find($id);
        }

        $saveData = formatDataBaseOnTable('m_corps', $data);
        $saveData['support_language_en'] = !empty($saveData['support_language_en']) ? $saveData['support_language_en'] : 0;
        $saveData['support_language_zh'] = !empty($saveData['support_language_zh']) ? $saveData['support_language_zh'] : 0;
        $saveData['modified'] = date('Y-m-d H:i:s');
        $saveData['modified_user_id'] = Auth::getUser()->user_id;

        if (empty($id)) {
            $saveData['created'] = date('Y-m-d H:i:s');
            $saveData['created_user_id'] = Auth::getUser()->user_id;
            $result = $this->mCorpRepository->insertGetId($saveData);
            $lastId = $result;
        } else {
            $result = $this->mCorpRepository->updateCorp($id, $saveData);
            $lastId = $id;
        }

        if ($result) {
            if (!empty($id) && Auth::getUser()->auth == 'affiliation') {
                if ($data['responsibility'] != $originalCorp->responsibility) {
                    // Mail delivery processing
                    $this->sendResponsibilityMail($saveData);
                }
            }

            if ($isAntisocialCreate) {
                $this->antisocialCheckRepository->insert(['corp_id' => $lastId, 'date' => date('Y-m-d H:i:s')]);
            }
            return $lastId;
        } else {
            return null;
        }
    }

    /**
     * Prepare data before save data (in case attribute empty)
     * @param array $data
     * @return array
     */
    private function setValueForSaveData($data)
    {
        $data = $this->setTimeValueForSaveData($data);

        if (empty($data['registration_details_check'])) {
            $data['registration_details_check'] = 0;
        }

        if (empty($data['mobile_mail_none'])) {
            $data['mobile_mail_none'] = 0;
        }

        $data['responsibility'] = trim($data['responsibility_sei']) . ' ' . trim($data['responsibility_mei']);

        $isAntisocialCreate = false;
        if (isset($data['last_antisocial_check'])
            && empty($data['antisocial_check_month'])
            && $data['last_antisocial_check'] === 'OK'
        ) {
            $data['antisocial_check_month'] = ((date('n') + 11) % 12);
            $isAntisocialCreate = true;
        }

        return ['data' => $data, 'isAntisocialCreate' => $isAntisocialCreate];
    }

    /**
     * Prepare data before save data (with attribute relate time)
     *
     * @param array $data
     * @return array
     */
    private function setTimeValueForSaveData($data)
    {
        if (empty($data['support24hour'])) {
            $data['support24hour'] = 0;
        }

        if (empty($data['contactable_support24hour'])) {
            $data['contactable_support24hour'] = 0;
        }

        if (empty($data['contactable_time_other'])) {
            $data['contactable_time_other'] = 0;
        }

        if (empty($data['available_time_other'])) {
            $data['available_time_other'] = 0;
        }

        return $data;
    }
    /**
     * @param array $corp
     * @return array
     */
    private function getToCorpArr($corp)
    {
        $toCorpArr = [];
        if (!empty($corp['mailaddress_pc'])) {
            /* Because there is a possibility that more than one has been specified, split by semicolon */
            $tmpAddrs = explode(";", $corp['mailaddress_pc']);
            /* Store all email addresses */
            foreach ($tmpAddrs as $oneAddr) {
                $toCorpArr[] = $oneAddr;
            }
        }

        if (!empty($corp['mailaddress_mobile'])) {
            /* Because there is a possibility that more than one has been specified, split by semicolon */
            $tmpAddrs = explode(";", $corp['mailaddress_mobile']);
            /* Store all email addresses */
            foreach ($tmpAddrs as $oneAddr) {
                $toCorpArr[] = $oneAddr;
            }
        }

        return $toCorpArr;
    }

    /**
     * Representative change mail transmission process
     * Without return code Function within error processing
     *
     * @param array $corp
     */
    private function sendResponsibilityMail($corp = null)
    {
        $fromCorp = env('ST_MAIL_FROM');
        $subjectCorp = __('affiliation.subject_corp_mail');

        $toST = env('KAMEITEN_MAIL_TO');
        $fromST = env('ST_MAIL_FROM');
        $subjectST = __('affiliation.subject_st_mail');

        if (!empty($corp['mailaddress_pc'])
            || !empty($corp['mailaddress_mobile'])
        ) {
            $toCorpArr = $this->getToCorpArr($corp);
            foreach ($toCorpArr as $toCorp) {
                try {
                    //Franchise Store
                    //Franchise Information Not Used
                    $dataCorp = [
                        'subject' => $subjectCorp,
                        'to' => $toCorp,
                        'from' => $fromCorp,
                        'subjectST' => $subjectST,
                        'toST' => $toST,
                        'fromST' => $fromST
                    ];
                    MailHelper::sendMail($dataCorp['to'], new CorpResponsibility($dataCorp, $corp));
                } catch (Exception $exception) {
                    $msg = 'MailSend: Failure subject:' . $subjectCorp . "\n to:" . $toCorp;
                    logger(__METHOD__ . $msg);
                    MailHelper::sendRawMail(
                        $msg,
                        'ERROR: ' . $dataCorp['subjectCorp'],
                        $dataCorp['fromST'],
                        $dataCorp['toST']
                    );
                }
            }

            try {
                $dataST = [
                    'subject' => $subjectST,
                    'to' => $toST,
                    'from' => $fromST
                ];
                MailHelper::sendMail($dataST['to'], new StResponsibility($dataST, $corp));
            } catch (Exception $exception) {
                logger(__METHOD__ . ' MailSend: Failure subject:' . $subjectST . "\n to admin:" . $toST);
            }
        }
    }

    /**
     * Get select box of affiliation
     *
     * @return array
     */
    private function affiliationSelectBox()
    {
        return [
            'userList' => $this->mUserRepository->dropDownUser(),
            'contractStatus' => getDropList(trans('affiliation_detail.pioneering_agency_situation')),
            'developmentSituation' => getDropList(trans('affiliation_detail.development_situation')),
            'reasonLossDevelopment' => getDropList(trans('affiliation_detail.reason_for_loss_of_development')),
            'holiday' => getDropList(trans('affiliation_detail.holiday')),
            'vacation' => getDropList(trans('affiliation_detail.long_holiday')),
            'freeEstimate' => getDropList(trans('affiliation_detail.free_estimate')),
            'portalSite' => getDropList(trans('affiliation_detail.portalsite')),
            'regSendMethod' => getDropList(trans('affiliation_detail.reg_send_method')),
            'coordinationMethod' => getDropList(trans('affiliation_detail.coordination_method')),
            'corpCommissionType' => getDropList(trans('affiliation_detail.corp_commission_type')),
            'jbrAvailableStatus' => getDropList(trans('affiliation_detail.jbr_available_status')),
            'autoCallFlag' => getDropList(trans('affiliation_detail.auto_call_flag')),
            'collectionMethod' => getDropList(trans('affiliation_detail.collection_method')),
            'liabilityInsurance' => getDropList(trans('affiliation_detail.liability_insurance')),
            'wasteCollectOath' => getDropList(trans('affiliation_detail.waste_collect_oath')),
            'claimCount' => getDropList(trans('affiliation_detail.claim_count')),
            'progSendMethod' => getDropList(trans('affiliation_detail.prog_send_method')),
            'billSendMethod' => getDropList(trans('affiliation_detail.bill_send_method')),
            'developmentResponse' => getDropList(trans('affiliation_detail.development_response')),
            'advertisingStatus' => getDropList(trans('affiliation_detail.advertising_status')),
            'paymentSite' => getDropList(trans('affiliation_detail.payment_site')),
            'auctionStatus' => getDivList('rits.auction_delivery_status', 'rits_config'),
            'auctionMasking' => getDivList('rits.auction_masking', 'affiliation_detail'),
            'prefectureList' => getDivList('rits.prefecture_div', 'rits_config'),
            'affiliationStatus' => config('constant.affiliation_status'),
        ];
    }

    /**
     * @return array
     */
    public function getDataSendDetail()
    {
        // Acquisition of anti-company check history
        $antisocialResultList = $this->antisocialCheckRepository->getResultList();
        $antisocialCheckMonthList = $this->antisocialCheckRepository->getMonthList();

        // Get role of user
        $userRole = Auth::getUser()->auth;
        $antisocialCheckUpdateAuthority = $this->antisocialCheckRepository->isUpdateAuthority($userRole);

        $hiddenAttributeRoleGeneral = [
            config('datacustom.auth_list.popular'),
            config('datacustom.auth_list.accounting')
        ];

        $roleAdmin1 = [
            config('datacustom.auth_list.admin'),
            config('datacustom.auth_list.system')
        ];

        $roleAdmin2 = [
            config('datacustom.auth_list.admin'),
            config('datacustom.auth_list.system'),
            config('datacustom.auth_list.accounting_admin')
        ];

        $dataCreate = [
            'antisocialResultList' => $antisocialResultList,
            'antisocialCheckMonthList' => $antisocialCheckMonthList,
            'antisocialCheckUpdateAuthority' => $antisocialCheckUpdateAuthority,
            'userRole' => $userRole,
            'roleAdmin1' => $roleAdmin1,
            'roleAdmin2' => $roleAdmin2,
            'hiddenAttributeRoleGeneral' => $hiddenAttributeRoleGeneral
        ];

        // Get select box data
        $selectBoxArr = $this->affiliationSelectBox();

        // Merge array to data render view
        $dataCreate = array_merge($selectBoxArr, $dataCreate);
        return $dataCreate;
    }

    /**
     * Soft delete mcorp
     *
     * @param integer $id
     * @return array
     */
    public function deleteSoftMcorp($id)
    {
        try {
            $result = $this->mCorpRepository->deleteSoftById($id);
            if ($result) {
                return ['status' => 200, 'message' => __('affiliation_detail.delete_success')];
            } else {
                return ['status' => 500, 'message' => __('affiliation_detail.delete_fail')];
            }
        } catch (Exception $e) {
            logger(__METHOD__ . $e->getMessage());
            return ['status' => 500, 'message' => __('affiliation.delete_fail')];
        }
    }

    /**
     * Get mCorp with sub list
     *
     * @param integer $id
     * @return array
     */
    public function getMCorpSubList($id)
    {
        $results = $this->mCorpSubRepository->getMCorpSubList($id);
        $holiday = [];
        $developmentResponse = [];

        foreach ($results as $v) {
            switch ($v->item_category) {
                // Holiday
                case config('constant.holiday'):
                    $holiday[] = $v->item_id;
                    break;

                // Reactions during pioneering
                case config('constant.development_reaction'):
                    $developmentResponse[] = $v->item_id;
                    break;
            }
        };
        return ['holiday' => $holiday, 'development_response' => $developmentResponse];
    }

    /**
     * Get credit limit base on mCorp data
     *
     * @param object $mCorp
     * @return string
     */
    public function getCreditLimit($mCorp)
    {
        if ((int)$mCorp->credit_limit <> 0) {
            $checkCredit = $this->commissionInfoService->checkCredit($mCorp->id, null, true);
            $result = number_format((int)$mCorp->credit_limit + (int)$mCorp->add_month_credit - (int)$checkCredit);
            $result .= __('common.yen');
        } else {
            $result = '未設定';
        }
        return $result;
    }

    /**
     * Create affiliation detail with data input
     *
     * @param array $data
     * @return bool
     */
    public function createAffiliationDetail($data)
    {
        DB::beginTransaction();
        try {
            // Create company master and return $id new
            $lastId = $this->saveOrEditMCorp($data['m_corps']);
            $result[] = $lastId;

            if ($lastId) {
                // Set data corp subs
                if (isset($data['m_corp_subs']['development_response'])) {
                    $developmentReaction = $data['m_corp_subs']['development_response'];
                } else {
                    $developmentReaction = [];
                }
                $dataCorpSubs = [
                    'development_reaction' => $developmentReaction,
                    'holiday' => isset($data['m_corp_subs']['holiday']) ? $data['m_corp_subs']['holiday'] : []
                ];

                // Creation of company master incidental information
                $result[] = $this->editMCorpSub($lastId, $dataCorpSubs);

                // Registration of New Year's Holiday
                $data['m_corp_new_years']['corp_id'] = $lastId;
                $result[]  = $this->mCorpNewYearRepository->updateNewYear(null, $data['m_corp_new_years']);

                // Correspondence mail transmission flag correspondence
                $dataAff = $data['affiliation_infos'];
                $dataAff['credit_mail_send_flg'] = (isset($dataAff['allow_credit_mail_send'])) ? 1 : 0;
                $lastAffiliationId = $this->editAffiliation($lastId, $dataAff);
                $result[] = $lastAffiliationId;

                // Registration of affiliated store incidental information
                $dataAffiliationSubs = isset($data['stop_category']) ? $data['stop_category'] : [];
                $result[]  = $this->editAffiliationSubs($lastAffiliationId, $dataAffiliationSubs);

                $result[] = $this->registHistory($lastId, $data['affiliation_correspond']);
            }

            if (!in_array(false, $result) && !in_array(null, $result)) {
                DB::commit();
                return $lastId;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update affiliation detail with data input
     *
     * @param array $data
     * @param integer $id
     * @return bool
     */
    public function updateAffiliationDetail($data, $id)
    {
        DB::beginTransaction();
        try {
            // Create company master and return $id new
            $lastId = $this->saveOrEditMCorp($data['m_corps'], $id);
            $result[] = $lastId;

            if ($lastId) {
                // Set data corp subs
                if (isset($data['m_corp_subs']['development_response'])) {
                    $developmentReaction = $data['m_corp_subs']['development_response'];
                } else {
                    $developmentReaction = [];
                }
                $dataCorpSubs = [
                    'development_reaction' => $developmentReaction,
                    'holiday' => isset($data['m_corp_subs']['holiday']) ? $data['m_corp_subs']['holiday'] : []
                ];

                // Creation of company master incidental information
                $result[] = $this->editMCorpSub($lastId, $dataCorpSubs);

                // Registration of New Year's Holiday
                $data['m_corp_new_years']['corp_id'] = $lastId;
                $result[] = $this->mCorpNewYearRepository->updateNewYear($lastId, $data['m_corp_new_years']);

                // Correspondence mail transmission flag correspondence
                $dataAff = $data['affiliation_infos'];
                $dataAff['credit_mail_send_flg'] = (isset($dataAff['allow_credit_mail_send'])) ? 1 : 0;
                $lastAffiliationId = $this->editAffiliation($lastId, $dataAff);
                $result[] = $lastAffiliationId;

                // Registration of affiliated store incidental information
                $dataAffiliationSubs = isset($data['stop_category']) ? $data['stop_category'] : [];
                $result[] = $this->editAffiliationSubs($lastAffiliationId, $dataAffiliationSubs);

                $result[] = $this->registHistory($lastId, $data['affiliation_correspond']);
            }

            if (!in_array(false, $result) && !in_array(null, $result)) {
                DB::commit();
                return $lastId;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * Check length of file name greater 200 character or not
     *
     * @param object $fileName File
     * @return bool
     */
    public function checkFileName($fileName)
    {
        $originName = $fileName->getClientOriginalName();
        $filename = pathinfo($originName, PATHINFO_FILENAME);
        return (strlen($filename) > 200) ? false : true;
    }
}
