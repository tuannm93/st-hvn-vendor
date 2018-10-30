<?php


namespace App\Services\Logic;

use App\Helpers\MailHelper;
use App\Models\AffiliationAreaStat;
use App\Models\CorpAgreement;
use App\Models\MCorpCategory;
use App\Models\MCorpTargetArea;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use App\Services\AgreementService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StepConfirmBusiness
{
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var AgreementService
     */
    protected $agreementService;
    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreeTempLinkRepo;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetAreaRepository;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetAreaRepository;
    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    protected $affiliationAreaStatRepository;

    /**
     * StepConfirmBusiness constructor.
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param AgreementService $agreementService
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreeTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     * @param MTargetAreaRepositoryInterface $mTargetAreaRepository
     * @param AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository
     */
    public function __construct(
        AgreementSystemLogic $agreementSystemLogic,
        AgreementService $agreementService,
        CorpAgreementTempLinkRepositoryInterface $corpAgreeTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository,
        MTargetAreaRepositoryInterface $mTargetAreaRepository,
        AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepository
    ) {
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->corpAgreeTempLinkRepo = $corpAgreeTempLinkRepo;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->agreementService = $agreementService;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
        $this->mTargetAreaRepository = $mTargetAreaRepository;
        $this->affiliationAreaStatRepository = $affiliationAreaStatRepository;
    }

    /**
     * @param object $user
     */
    public function stepConfirmProcess($user)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        if (!is_null($corpAgreement) && $corpAgreement->status == CorpAgreement::STEP5) {
            $mCorp = $this->mCorpRepository->find($corpAgreement->corp_id);
            $corpAgreement = $this->updateCorpAgreementInStepConfirm($mCorp, $corpAgreement, $user);
            $this->updateMCorpInStepConfirm($mCorp, $user);
            $this->sendMailInStepConfirm($corpAgreement);
        }
    }

    /**
     * @param object $mCorp
     * @param object $corpAgreement
     * @param object $user
     * @return \App\Models\Base
     */
    private function updateCorpAgreementInStepConfirm($mCorp, $corpAgreement, $user)
    {
        if ($mCorp->commission_accept_flg == 2 || $mCorp->commission_accept_flg == 3) {
            $corpAgreement->status = CorpAgreement::COMPLETE;
            $corpAgreement->acceptation_date = Carbon::now()->toDateTimeString();
            $corpAgreement->agreement_flag = true;
            $corpAgreement->acceptation_user_id = "SYSTEM";
            $this->commissionAcceptProcess($corpAgreement);
        } else {
            $corpAgreement->status = CorpAgreement::APPLICATION;
        }
        $corpAgreement->original_agreement = $this->agreementService->getOriginalAgreement();
        $corpAgreement->customize_agreement = $this->agreementService->getCustomizeAgreement($mCorp->id);
        $corpAgreement->accept_check = true;
        $corpAgreement = $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, null, $user);

        return $corpAgreement;
    }

    /**
     * update m_corps
     *
     * @param object $mCorp
     * @param object $user
     */
    private function updateMCorpInStepConfirm($mCorp, $user)
    {
        if ($mCorp->commission_accept_flg == 2 || $mCorp->commission_accept_flg == 3) {
            $mCorp->commission_accept_flg = 1;
            $mCorp->commission_accept_date = Carbon::now()->toDateTimeString();
            $mCorp->commission_accept_user_id = "SYSTEM";
            $mCorp->modified = $mCorp->commission_accept_date;
            $mCorp->modified_user_id = $user->id;
            $this->mCorpRepository->save($mCorp);
        }
    }

    /**
     * send email
     *
     * @param object $corpAgreement
     */
    private function sendMailInStepConfirm($corpAgreement)
    {
        if ($corpAgreement->status == CorpAgreement::APPLICATION) {
            try {
                $corpId = $corpAgreement->corp_id;
                $mailSubject = sprintf('《%d》加盟店からの契約申請がありました。', $corpId);
                $url = config('datacustom.php_affiliation_agreement_url');
                $mailContents = sprintf('契約約款確認URL: %s/%d', $url, $corpId);
                $mailTo = config('datacustom.email_address_confirm_agreement.kameiten');
                $mailFrom = config('datacustom.email_address_confirm_agreement.mailback');
                MailHelper::sendRawMail($mailContents, $mailSubject, $mailFrom, $mailTo, null);
            } catch (\Exception $e) {
                Log::error($e);
            }
        }
    }

    /**
     * @param object $corpAgreement
     */
    private function commissionAcceptProcess($corpAgreement)
    {
        $corpId = $corpAgreement->corp_id;
        $tempLink = $this->corpAgreeTempLinkRepo->findLatestByCorpId($corpId);
        $mCorpCategoryList = $this->mCorpCategoryRepository->findAllByCorpId($corpId);
        $mCorpCategoriesTempList = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag($corpId, $tempLink->id, null, null);
        $corpCategoryIdDeleteList = [];
        $updateMCorpCategoryList = [];
        $this->deleteMCorpCategoryWhenCommissionAccept($corpCategoryIdDeleteList, $mCorpCategoryList, $mCorpCategoriesTempList);
        $this->insertOrUpdateMCorpCategoryWhenCommissionAccept($updateMCorpCategoryList, $mCorpCategoriesTempList);
        $this->deleteMCorpTargetAreasWhenCommissionAccept($corpCategoryIdDeleteList);
        $this->insertOrUpdateMCorpTargetAreasWhenCommissionAccept($corpId, $updateMCorpCategoryList);
        $this->updateAffiliationAreaStats($updateMCorpCategoryList);
    }

    /**
     * @param array $corpCategoryIdDeleteList
     * @param array $mCorpCategoryList
     * @param array $mCorpCategoriesTempList
     */
    private function deleteMCorpCategoryWhenCommissionAccept(&$corpCategoryIdDeleteList, $mCorpCategoryList, &$mCorpCategoriesTempList)
    {
        foreach ($mCorpCategoryList as $mCorpCategory) {
            $isDelete = true;
            foreach ($mCorpCategoriesTempList as $mCorpCategoryTemp) {
                if ($mCorpCategoryTemp->delete_flag) {
                    continue;
                }
                if ($mCorpCategory->category_id == $mCorpCategoryTemp->category_id) {
                    $isDelete = false;
                    $mCorpCategoryTemp->m_corp_category_id = $mCorpCategory->m_corp_category_id;
                    break;
                }
            }
            if ($isDelete) {
                array_push($corpCategoryIdDeleteList, $mCorpCategory->m_corp_category_id);
            }
        }
        $this->mCorpCategoryRepository->deleteListItemByArrayId($corpCategoryIdDeleteList);
    }

    /**
     * insert or update m_corp_category
     *
     * @param array $updateMCorpCategoryList
     * @param array $mCorpCategoriesTempList
     */
    private function insertOrUpdateMCorpCategoryWhenCommissionAccept(&$updateMCorpCategoryList, $mCorpCategoriesTempList)
    {
        foreach ($mCorpCategoriesTempList as $mCorpCategoryTemp) {
            if ($mCorpCategoryTemp->delete_flag) {
                $mCorpCategoryTemp->action = "Delete";
                $this->saveMCorpCategoriesTemp($mCorpCategoryTemp);
            } else {
                $mCorpCategory = $this->createMCorpCategory($mCorpCategoryTemp);
                $this->mCorpCategoryRepository->save($mCorpCategory);
                array_push($updateMCorpCategoryList, $mCorpCategory);
                $this->saveMCorpCategoriesTemp($mCorpCategoryTemp);
            }
        }
    }

    /**
     * save m_corp_categories_temp
     *
     * @param object $mCorpCategoryTemp
     */
    private function saveMCorpCategoriesTemp($mCorpCategoryTemp)
    {
        $result = $this->mCorpCategoriesTempRepository->find($mCorpCategoryTemp->m_corp_categories_temp_id);
        $result->action = $mCorpCategoryTemp->action;
        $this->mCorpCategoriesTempRepository->save($result);
    }

    /**
     * @param object $mCorpCategoryTemp
     * @return \App\Models\Base|MCorpCategory|null
     */
    private function createMCorpCategory(&$mCorpCategoryTemp)
    {
        $mCorpCategory = null;
        if ($mCorpCategoryTemp->m_corp_category_id != null) {
            $mCorpCategory = $this->mCorpCategoryRepository->find($mCorpCategoryTemp->m_corp_category_id);
            $mCorpCategoryTemp->action = $this->getUpdateColumn($mCorpCategory, $mCorpCategoryTemp);
        }
        if ($mCorpCategory == null) {
            $mCorpCategory = new MCorpCategory();
            $mCorpCategory->created = Carbon::now()->toDateTimeString();
            $mCorpCategory->created_user_id = Auth::user()->user_id;
            $mCorpCategoryTemp->action = "Add";
        }
        $mCorpCategory->corp_id = $mCorpCategoryTemp->corp_id;
        $mCorpCategory->genre_id = $mCorpCategoryTemp->genre_id;
        $mCorpCategory->category_id = $mCorpCategoryTemp->category_id;
        $mCorpCategory->order_fee = $mCorpCategoryTemp->order_fee;
        $mCorpCategory->order_fee_unit = $mCorpCategoryTemp->order_fee_unit;
        $mCorpCategory->introduce_fee = $mCorpCategoryTemp->introduce_fee;
        $mCorpCategory->note = $mCorpCategoryTemp->note;
        $mCorpCategory->select_list = $mCorpCategoryTemp->select_list;
        $mCorpCategory->select_genre_category = $mCorpCategoryTemp->select_genre_category;
        $mCorpCategory->target_area_type = $mCorpCategoryTemp->target_area_type;
        $mCorpCategory->corp_commission_type = $mCorpCategoryTemp->corp_commission_type;
        $mCorpCategory->modified = Carbon::now()->toDateTimeString();
        $mCorpCategory->modified_user_id = Auth::user()->user_id;

        return $mCorpCategory;
    }

    /**
     * @param object $mCorpCategory
     * @param object $mCorpCategoryTemp
     * @return null|string
     */
    private function getUpdateColumn($mCorpCategory, $mCorpCategoryTemp)
    {
        $result = $this->getUpdateFee($mCorpCategory, $mCorpCategoryTemp);

        $result = $this->getUpdateNote($result, $mCorpCategory, $mCorpCategoryTemp);

        $result = $this->getUpdateSelectList($result, $mCorpCategory, $mCorpCategoryTemp);

        $result = $this->getUpdateCorpCommission($result, $mCorpCategory, $mCorpCategoryTemp);

        return checkIsNullOrEmptyStr($result) ? null : $result;
    }

    /**
     * @param object $mCorpCategory
     * @param object $mCorpCategoryTemp
     * @return string
     */
    private function getUpdateFee($mCorpCategory, $mCorpCategoryTemp)
    {
        $result = "";
        if ($mCorpCategory->order_fee != $mCorpCategoryTemp->order_fee) {
            $result .= (checkIsNullOrEmptyStr($result) ? "" : ",") . "order_fee";
        }
        if ($mCorpCategory->order_fee_unit != $mCorpCategoryTemp->order_fee_unit) {
            $result .= (checkIsNullOrEmptyStr($result) ? "" : ",") . "order_fee_unit";
        }
        if ($mCorpCategory->introduce_fee != $mCorpCategoryTemp->introduce_fee) {
            $result .= (checkIsNullOrEmptyStr($result) ? "" : ",") . "introduce_fee";
        }
        return $result;
    }

    /**
     * @param string $result
     * @param object $mCorpCategory
     * @param object $mCorpCategoryTemp
     * @return string
     */
    private function getUpdateNote($result, $mCorpCategory, $mCorpCategoryTemp)
    {
        if (!checkIsNullOrEmptyStr($mCorpCategory->note) && !checkIsNullOrEmptyStr($mCorpCategoryTemp->note)
            && $mCorpCategory->note != $mCorpCategoryTemp->note
        ) {
            $result .= (checkIsNullOrEmptyStr($result) ? "" : ",") . "note";
        }

        return $result;
    }

    /**
     * @param string $result
     * @param object $mCorpCategory
     * @param object $mCorpCategoryTemp
     * @return string
     */
    private function getUpdateSelectList($result, $mCorpCategory, $mCorpCategoryTemp)
    {
        if (!checkIsNullOrEmptyStr($mCorpCategory->select_list) && !checkIsNullOrEmptyStr($mCorpCategoryTemp->select_list)
            && $mCorpCategory->select_list != $mCorpCategoryTemp->select_list
        ) {
            $result .= (checkIsNullOrEmptyStr($result) ? "" : ",") . "select_list";
        }
        return $result;
    }

    /**
     * @param string $result
     * @param object $mCorpCategory
     * @param object $mCorpCategoryTemp
     * @return mixed
     */
    private function getUpdateCorpCommission($result, $mCorpCategory, $mCorpCategoryTemp)
    {
        if ($mCorpCategory->corp_commission_type != $mCorpCategoryTemp->corp_commission_type) {
            $result .= (checkIsNullOrEmptyStr($result) ? "" : ",") . "corp_commission_type";
        }
        return $result;
    }

    /**
     * @param array $corpCategoryIdDeleteList
     */
    private function deleteMCorpTargetAreasWhenCommissionAccept($corpCategoryIdDeleteList)
    {
        foreach ($corpCategoryIdDeleteList as $corpCategoryId) {
            $this->mTargetAreaRepository->deleteByCorpCategoryId($corpCategoryId);
        }
    }

    /**
     * @param integer $corpId
     * @param array $updateMCorpCategoryList
     */
    private function insertOrUpdateMCorpTargetAreasWhenCommissionAccept($corpId, $updateMCorpCategoryList)
    {
        $mCorpTargetAreaList = $this->mCorpTargetAreaRepository->getListByCorpId($corpId);
        foreach ($updateMCorpCategoryList as $mCorpCategory) {
            $mTargetAreaList = $this->mTargetAreaRepository->findAllByCorpCategoryId($mCorpCategory->id);
            if (!checkIsNullOrEmptyCollection($mTargetAreaList)) {
                foreach ($mCorpTargetAreaList as $mCorpTargetArea) {
                    $mCorpTargetAreas = new MCorpTargetArea();
                    $mCorpTargetAreas->corp_id = $corpId;
                    $mCorpTargetAreas->jis_cd = $mCorpTargetArea->jis_cd;
                    $mCorpTargetAreas->created = Carbon::now()->toDateTimeString();
                    $mCorpTargetAreas->modified = $mCorpTargetAreas->created;
                    $mCorpTargetAreas->created_user_id = Auth::user()->user_id;
                    $mCorpTargetAreas->modified_user_id = $mCorpTargetAreas->created_user_id;

                    $this->mCorpTargetAreaRepository->save($mCorpTargetAreas);
                }
            }
        }
    }

    /**
     * @param array $updateMCorpCategoryList
     */
    private function updateAffiliationAreaStats($updateMCorpCategoryList)
    {
        foreach ($updateMCorpCategoryList as $mCorpCategory) {
            for ($prefectureId = 1; $prefectureId < 48; $prefectureId++) {
                $affiliationAreaStatList = $this->affiliationAreaStatRepository->findByCorpIdAndGenerIdAndPrefecture(
                    $mCorpCategory->corp_id,
                    $mCorpCategory->genre_id,
                    $prefectureId
                );
                if (is_null($affiliationAreaStatList)) {
                    $affiliationAreaStat = new AffiliationAreaStat();
                    $affiliationAreaStat->corp_id = $mCorpCategory->corp_id;
                    $affiliationAreaStat->genre_id = $mCorpCategory->genre_id;
                    $affiliationAreaStat->prefecture = $prefectureId;
                    $affiliationAreaStat->commission_count_category = 0;
                    $affiliationAreaStat->orders_count_category = 0;
                    $affiliationAreaStat->modified_user_id = Auth::user()->user_id;
                    $affiliationAreaStat->modified = Carbon::now()->toDateTimeString();
                    $affiliationAreaStat->created_user_id = $affiliationAreaStat->modified_user_id;
                    $affiliationAreaStat->created = $affiliationAreaStat->modified;

                    $this->affiliationAreaStatRepository->save($affiliationAreaStat);
                }
            }
        }
    }
}
