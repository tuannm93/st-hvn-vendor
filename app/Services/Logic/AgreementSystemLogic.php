<?php

namespace App\Services\Logic;

use App\Helpers\Util;
use App\Models\AgreementCustomize;
use App\Models\AgreementStatusHistory;
use App\Models\CorpAgreement;
use App\Models\CorpAgreementTempLink;
use App\Models\MCategory;
use App\Models\MCorpCategoriesTemp;
use App\Models\MUser;
use App\Repositories\AgreementCustomizeRepositoryInterface;
use App\Repositories\AgreementEditHistoryRepositoryInterface;
use App\Repositories\AgreementRepositoryInterface;
use App\Repositories\AgreementStatusHistoryRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgreementSystemLogic
{

    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreementRepository;
    /**
     * @var AgreementEditHistoryRepositoryInterface
     */
    protected $agreementEditHistoryRepository;
    /**
     * @var AgreementStatusHistoryRepositoryInterface
     */
    protected $agreementStatusHistoryRepo;
    /**
     * @var AgreementCustomizeRepositoryInterface
     */
    protected $agreementCustomizeRepository;
    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreementTempLinkRepo;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;

    /**
     * AgreementSystemLogic constructor.
     * @param AgreementRepositoryInterface $agreementRepository
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param AgreementEditHistoryRepositoryInterface $agreementEditHistoryRepository
     * @param AgreementStatusHistoryRepositoryInterface $agreementStatusHistoryRepo
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo
     * @param AgreementCustomizeRepositoryInterface $agreementCustomizeRepository
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     */
    public function __construct(
        AgreementRepositoryInterface $agreementRepository,
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        AgreementEditHistoryRepositoryInterface $agreementEditHistoryRepository,
        AgreementStatusHistoryRepositoryInterface $agreementStatusHistoryRepo,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo,
        AgreementCustomizeRepositoryInterface $agreementCustomizeRepository,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository
    ) {
        $this->agreementRepository = $agreementRepository;
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->agreementEditHistoryRepository = $agreementEditHistoryRepository;
        $this->agreementStatusHistoryRepo = $agreementStatusHistoryRepo;
        $this->agreementCustomizeRepository = $agreementCustomizeRepository;
        $this->corpAgreementTempLinkRepo = $corpAgreementTempLinkRepo;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
    }

    /**
     * @return CorpAgreement
     */
    private function createCorpAgreementDefault()
    {
        $corpAgreement = new CorpAgreement();
        $corpAgreement->new_flag = false;
        $corpAgreement->delete_flag = false;
        $corpAgreement->accept_check = false;

        return $corpAgreement;
    }

    /**
     * create new corp agreement
     * @param object $user
     * @return \App\Models\Base|CorpAgreement
     * @throws \Exception
     */
    public function initDataCorpAgreement($user)
    {
        $agreementVersion = $this->agreementRepository->findCurrentVersion();
        $agreement = $this->agreementRepository->find($agreementVersion->agreement_id);

        $corpAgreement = $this->createCorpAgreementDefault();
        $corpAgreement->corp_id = $user->affiliation_id;
        $corpAgreement->status = CorpAgreement::STEP0;
        $corpAgreement->ticket_no = $agreement->ticket_no;
        $corpAgreement->create_date = Carbon::now()->toDateTimeString();
        $corpAgreement->update_date = $corpAgreement->create_date;
        $corpAgreement->create_user_id = $user->id;
        $corpAgreement->update_user_id = $user->id;
        $corpAgreement->agreement_id = $agreement->id;
        $corpAgreement->corp_kind = null;
        $corpAgreement->agreement_flag = null;
        $corpAgreement->version_no = 1;
        $corpAgreement->hansha_check = null;

        $agreementEditHistory = $this->agreementEditHistoryRepository->getFirstAgreementEditHistory();
        if (is_null($agreementEditHistory)) {
            $corpAgreement->agreement_history_id = $agreement->last_history_id;
        } else {
            $corpAgreement->agreement_history_id = $agreementEditHistory->id;
        }
        try {
            DB::beginTransaction();
            $corpAgreement = $this->corpAgreementRepository->save($corpAgreement);
            $agreementStatusHistory = new AgreementStatusHistory();
            $agreementStatusHistory->corps_id = $user->affiliation_id;
            $agreementStatusHistory->corp_agreement_id = $corpAgreement->id;
            $agreementStatusHistory->status = CorpAgreement::STEP0;
            $agreementStatusHistory->delete_flag = false;
            $agreementStatusHistory->version_no = 1;
            $agreementStatusHistory->before_status = null;
            $this->agreementStatusHistoryRepo->save($agreementStatusHistory);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
        return $corpAgreement;
    }

    /**
     * @param object $corpAgreement
     * @param object $user
     * @return \App\Models\Base|CorpAgreementTempLink
     */
    public function initCorpAgreementTempLink($corpAgreement, $user)
    {
        $corpAgreementTempLink = $this->corpAgreementTempLinkRepo->getFirstByCorpIdAndCorpAgreementId($user->affiliation_id, $corpAgreement->id);
        if (is_null($corpAgreementTempLink)) {
            $corpAgreementTempLink = new CorpAgreementTempLink();
            $corpAgreementTempLink->corp_id = $corpAgreement->corp_id;

            $corpAgreementTempLink->corp_agreement_id = $corpAgreement->id;
            $corpAgreementTempLink->modified_user_id = $user->id;
            $corpAgreementTempLink->modified = Carbon::now()->toDateTimeString();
            $corpAgreementTempLink->created_user_id = $corpAgreementTempLink->modified_user_id;
            $corpAgreementTempLink->created = $corpAgreementTempLink->modified;

            $corpAgreementTempLink = $this->corpAgreementTempLinkRepo->save($corpAgreementTempLink);
        }
        return $corpAgreementTempLink;
    }

    /**
     * @param object $user
     * @param integer $corpId
     * @param integer $tempLinkId
     */
    public function initCorpCategoryTemp($user, $corpId, $tempLinkId)
    {
        $corpAgreementTempLinkList = $this->corpAgreementTempLinkRepo->getByCorpIdWith2Record($corpId);
        if (!is_null($corpAgreementTempLinkList) && $corpAgreementTempLinkList->count() == 2) {
            $this->insertMCorpCategoriesTempIfExistCorpAgreementTempLink($user, $corpId, $corpAgreementTempLinkList[1]->id, $corpAgreementTempLinkList[0]->id);
        } else {
            $this->insertMCorpCategoriesTempIfNotExistCorpAgreementTempLink($user, $corpId, $tempLinkId);
        }
    }

    /**
     * @param object $user
     * @param integer $corpId
     * @param integer $tempId
     */
    private function insertMCorpCategoriesTempIfExistCorpAgreementTempLink($user, $corpId, $tempId, $temIdNew)
    {
        $mCorpCategoriesTempList = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag(
            $corpId,
            $tempId,
            MCorpCategoriesTemp::DELETE_FLAG_FALSE,
            MCategory::DISABLE_FLG_FALSE
        );
        foreach ($mCorpCategoriesTempList as $mCorpCategoryTemp) {
            $entity = new MCorpCategoriesTemp();
            $entity->corp_id = $mCorpCategoryTemp->corp_id;
            $entity->genre_id = $mCorpCategoryTemp->genre_id;
            $entity->category_id = $mCorpCategoryTemp->category_id;
            $entity->order_fee = $mCorpCategoryTemp->order_fee;
            $entity->order_fee_unit = $mCorpCategoryTemp->order_fee_unit;
            $entity->introduce_fee = $mCorpCategoryTemp->introduce_fee;
            $entity->note = $mCorpCategoryTemp->m_corp_categories_temp_note;
            $entity->select_list = $mCorpCategoryTemp->select_list;
            $entity->select_genre_category = $mCorpCategoryTemp->select_genre_category;
            $entity->target_area_type = $mCorpCategoryTemp->target_area_type;
            $entity->modified = Carbon::now()->toDateTimeString();
            $entity->modified_user_id = $user->id;
            $entity->created = $mCorpCategoryTemp->modified;
            $entity->created_user_id = $mCorpCategoryTemp->modified_user_id;
            $entity->temp_id = $temIdNew;
            $entity->delete_flag = false;
            $entity->corp_commission_type = $mCorpCategoryTemp->corp_commission_type;
            $entity->version_no = 1;

            $this->mCorpCategoriesTempRepository->save($entity);
        }
    }

    /**
     * @param object $user
     * @param integer $corpId
     * @param integer $tempId
     */
    private function insertMCorpCategoriesTempIfNotExistCorpAgreementTempLink($user, $corpId, $tempId)
    {
        $mCorpCategoriesList = $this->mCorpCategoryRepository->findAllByCorpId($corpId);
        foreach ($mCorpCategoriesList as $mCorpCategoryTemp) {
            $entity = new MCorpCategoriesTemp();
            $entity->corp_id = $mCorpCategoryTemp->corp_id;
            $entity->genre_id = $mCorpCategoryTemp->genre_id;
            $entity->category_id = $mCorpCategoryTemp->category_id;
            $entity->order_fee = $mCorpCategoryTemp->order_fee;
            $entity->order_fee_unit = $mCorpCategoryTemp->order_fee_unit;
            $entity->introduce_fee = $mCorpCategoryTemp->introduce_fee;
            $entity->note = $mCorpCategoryTemp->m_corp_categories_temp_note;
            $entity->select_list = $mCorpCategoryTemp->select_list;
            $entity->select_genre_category = $mCorpCategoryTemp->select_genre_category;
            $entity->target_area_type = $mCorpCategoryTemp->target_area_type;
            $entity->modified = Carbon::now()->toDateTimeString();
            $entity->modified_user_id = $user->id;
            $entity->created = $mCorpCategoryTemp->modified;
            $entity->created_user_id = $mCorpCategoryTemp->modified_user_id;
            $entity->temp_id = $tempId;
            $entity->delete_flag = false;
            $entity->corp_commission_type = $mCorpCategoryTemp->corp_commission_type;
            $entity->version_no = 1;

            $this->mCorpCategoriesTempRepository->save($entity);
        }
    }

    /**
     * get customized agreement of affiliation
     *
     * @param  integer $corpId
     * @return mixed
     */
    public function findCustomizedAgreementByCorpId($corpId)
    {
        $deleteFlag = false;
        $customizeList = $this->agreementCustomizeRepository->findAgreementCustomizeByCorpId($corpId, $deleteFlag);
        $agreementVersion = $this->agreementRepository->findCurrentVersion();
        $agreement = $this->agreementRepository->find($agreementVersion->agreement_id);
        $provisions = $agreement->agreementProvision->load('agreementProvisionItem');
        $arrayProvisions = [];
        if (!checkIsNullOrEmptyCollection($provisions)) {
            $provisions = $provisions->keyBy('id');
            $arrayProvisions = $provisions->toArray();
        }
        foreach ($customizeList as $customize) {
            if ($customize->table_kind == AgreementCustomize::AGREEMENT_PROVISIONS) {
                $arrayProvisions = $this->caseAgreementProvision($customize, $arrayProvisions);
            } else {
                $arrayProvisions = $this->caseAgreementProvisionItem($customize, $arrayProvisions);
            }
        }
        //sort
        $arrayProvisions = $this->sortProvision($arrayProvisions);
        return $arrayProvisions;
    }

    /**
     * @param object $customize
     * @param array $arrayProvisions
     * @return mixed
     */
    private function caseAgreementProvision($customize, $arrayProvisions)
    {
        switch ($customize->edit_kind) {
            case AgreementCustomize::DELETE:
                $customizeProvisionKey = $customize->getCustomizeProvisionKey();
                unset($arrayProvisions[$customizeProvisionKey]);
                break;
            case AgreementCustomize::ADD:
                $newCustomize = $this->patchedAddProvision($customize);
                $arrayProvisions[$newCustomize['customize_id']] = $newCustomize;
                break;
            case AgreementCustomize::UPDATE:
                $arrayProvisions = $this->patchedUpdateProvision($arrayProvisions, $customize);
                break;
        }
        return $arrayProvisions;
    }

    /**
     * @param object $customize
     * @return array
     */
    private function patchedAddProvision($customize)
    {
        $newProvision = [];
        $customizeId = $customize->getCustomizeProvisionKey();
        $newProvision['id'] = $customizeId;
        $newProvision['customize_id'] = $customizeId; //add more
        $newProvision['provisions'] = $customize->content;
        $newProvision['sort_no'] = $customize->sort_no;
        $newProvision['customize_flag'] = true;
        $newItems = [];
        $newProvision['agreement_provision_item'] = $newItems;
        return $newProvision;
    }

    /**
     * @param array $arrayProvisions
     * @param object $customize
     * @return mixed
     */
    private function patchedUpdateProvision($arrayProvisions, $customize)
    {
        $customizeProvisionKey = $customize->getCustomizeProvisionKey();
        if (isset($arrayProvisions[$customizeProvisionKey])) {
            $arrayProvisions[$customizeProvisionKey]['provisions'] = $customize->content;
            $arrayProvisions[$customizeProvisionKey]['sort_no'] = $customize->sort_no;
            $arrayProvisions[$customizeProvisionKey]['customize_flag'] = true;
        } else {
            $provision = [];
            $provision['id'] = $customizeProvisionKey; //=0
            $provision['provisions'] = $customize->content;
            $provision['sort_no'] = $customize->sort_no;
            $provision['customize_flag'] = true;
            $arrayProvisions[$customizeProvisionKey] = $provision;
        }
        return $arrayProvisions;
    }

    /**
     * @param object $customize
     * @param array $arrayProvisions
     * @return mixed
     */
    private function caseAgreementProvisionItem($customize, $arrayProvisions)
    {
        switch ($customize->edit_kind) {
            case AgreementCustomize::DELETE:
                $arrayProvisions = $this->patchedDeleteItem($arrayProvisions, $customize);
                break;
            case AgreementCustomize::ADD:
                $arrayProvisions = $this->patchedAddItem($arrayProvisions, $customize);
                break;
            case AgreementCustomize::UPDATE:
                $arrayProvisions = $this->patchedUpdateItem($arrayProvisions, $customize);
                break;
        }
        return $arrayProvisions;
    }

    /**
     * @param array $arrayProvisions
     * @param object $customize
     * @return mixed
     */
    private function patchedAddItem($arrayProvisions, $customize)
    {
        $customizeProvisionKey = $customize->getCustomizeProvisionKey();
        if (isset($arrayProvisions[$customizeProvisionKey])) {
            $newItem = [];
            $newItem['id'] = 0;
            $newItem['customize_key'] = $customize->getCustomizeItemKey();
            $newItem['item'] = $customize->content;
            $newItem['provisions_id'] = $customize->original_provisions_id;
            $newItem['customize_item_id'] = $customize->customize_item_id;
            $newItem['customize_provisions_id'] = $customize->customize_provisions_id;
            $newItem['sort_no'] = $customize->sort_no;
            $newItem['customize_flag'] = true;
            if (!isset($arrayProvisions[$customizeProvisionKey]['agreement_provision_item'])) {
                $arrayProvisions[$customizeProvisionKey]['agreement_provision_item'] = [];
            }
            array_push($arrayProvisions[$customizeProvisionKey]['agreement_provision_item'], $newItem);
        }
        return $arrayProvisions;
    }

    /**
     * @param array $arrayProvisions
     * @param object $customize
     * @return mixed
     */
    private function patchedDeleteItem($arrayProvisions, $customize)
    {
        $customizeProvisionKey = $customize->getCustomizeProvisionKey();
        if (isset($arrayProvisions[$customizeProvisionKey])) {
            $customizeItemKey = $customize->getCustomizeItemKey();
            $provisionItemList = $arrayProvisions[$customizeProvisionKey]['agreement_provision_item'];

            foreach ($provisionItemList as $key => $value) {
                if ($this->getCustomizeItemKey($value) === $customizeItemKey) {
                    unset($arrayProvisions[$customizeProvisionKey]['agreement_provision_item'][$key]);
                    break;
                }
            }
        }
        return $arrayProvisions;
    }

    /**
     * @param array $provisionItem
     * @return mixed
     */
    private function getCustomizeItemKey($provisionItem)
    {
        if (array_key_exists('customize_key', $provisionItem)) {
            return $provisionItem['customize_key'];
        } else {
            return $provisionItem['id'];
        }
    }

    /**
     * @param array $arrayProvisions
     * @param object $customize
     * @return mixed
     */
    private function patchedUpdateItem($arrayProvisions, $customize)
    {
        $customizeProvisionKey = $customize->getCustomizeProvisionKey();
        if (isset($arrayProvisions[$customizeProvisionKey])) {
            $customizeItemKey = $customize->getCustomizeItemKey();
            $flagCheckCustomizeItemExist = false;
            foreach ($arrayProvisions[$customizeProvisionKey]['agreement_provision_item'] as $key => $value) {
                if ($this->getCustomizeItemKey($value) === $customizeItemKey) {
                    $value['item'] = $customize->content;
                    $value['provisions_id'] = $customize->original_provisions_id;
                    $value['customize_item_id'] = $customize->customize_item_id;
                    $value['customize_provisions_id'] = $customize->customize_provisions_id;
                    $value['sort_no'] = $customize->sort_no;
                    $value['customize_flag'] = true;
                    $arrayProvisions[$customizeProvisionKey]['agreement_provision_item'][$key] = $value;
                    $flagCheckCustomizeItemExist = true;
                    break;
                }
            }
            if (!$flagCheckCustomizeItemExist) {
                $value = [];
                $value['item'] = $customize->content;
                $value['provisions_id'] = $customize->original_provisions_id;
                $value['customize_item_id'] = $customize->customize_item_id;
                $value['customize_provisions_id'] = $customize->customize_provisions_id;
                $value['sort_no'] = $customize->sort_no;
                $value['customize_flag'] = true;
                $arrayProvisions[$customizeProvisionKey]['agreement_provision_item'][$customizeItemKey] = $value;
            }
        }
        return $arrayProvisions;
    }

    /**
     * @param array $array
     * @param string $key
     * @param boolean $string
     * @param boolean $asc
     */
    private function sortArrayByKey(&$array, $key, $string = false, $asc = true)
    {
        if (checkIsNullOrEmpty($array)) {
            return;
        }
        if ($string) {
            usort($array, function ($first, $second) use (&$key, &$asc) {
                if ($asc) {
                    return strcmp(strtolower($first{$key}), strtolower($second{$key}));
                } else {
                    return strcmp(strtolower($first{$key}), strtolower($second{$key}));
                }
            });
        } else {
            usort($array, function ($first, $second) use (&$key, &$asc) {
                if ($first[$key] == $second{$key}) {
                    return 0;
                }
                if ($asc) {
                    return ($first{$key} < $second{$key}) ? -1 : 1;
                } else {
                    return ($first{$key} > $second{$key}) ? -1 : 1;
                }
            });
        }
    }

    /**
     * @param array $arrayProvision
     * @return mixed
     */
    private function sortProvision($arrayProvision)
    {
        foreach ($arrayProvision as $index => $provision) {
            if (array_key_exists('agreement_provision_item', $provision)) {
                $this->sortArrayByKey($arrayProvision[$index]['agreement_provision_item'], 'sort_no');
            }
        }
        $this->sortArrayByKey($arrayProvision, 'sort_no');
        return $arrayProvision;
    }

    /**
     * @param object $user
     * @return null|object
     */
    public function checkFirstCorpAgreementNotComplete($user)
    {
        $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId($user->affiliation_id, null, true);
        if (!is_null($corpAgreement) && $corpAgreement->status !== CorpAgreement::COMPLETE) {
            return $corpAgreement;
        } else {
            return null;
        }
    }

    /**
     * @param object $corpAgreement
     * @param string $status
     * @param object $user
     * @return \App\Models\Base
     */
    public function updateCorpAgreement($corpAgreement, $status, $user)
    {
        if (!is_null($status)) {
            $corpAgreement->status = $status;
        }
        $corpAgreement->update_date = Carbon::now()->toDateTimeString();
        $corpAgreement->update_user_id = $user->id;
        $corpAgreement->version_no += 1;
        $corpAgreement = $this->corpAgreementRepository->save($corpAgreement);
        return $corpAgreement;
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findByCorpIdAndStatusCompleteAndNotNullAcceptationDate($corpId)
    {
        return $this->corpAgreementRepository->findByCorpIdAndStatusCompleteAndNotNullAcceptationDate($corpId);
    }
}
