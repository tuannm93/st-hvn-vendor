<?php

namespace App\Services\Affiliation;

use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\AgreementRepositoryInterface;

class AffiliationAgreementFormatService
{
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    protected $mCorpTargetAreaRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreementRepository;

    /**
     * AffiliationAgreementFormatService constructor.
     *
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param AgreementRepositoryInterface $agreementRepository
     */
    public function __construct(
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        AgreementRepositoryInterface $agreementRepository
    ) {
        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->agreementRepository = $agreementRepository;
    }

    /**
     * format input data corp
     *
     * @param  object $corpData
     * @param  array  $data
     * @param  $userId
     * @return object mCorp
     */
    public function formatInputDataCorp($corpData, $data, $userId)
    {
        if (isset($data['MCorp']['commission_accept_flg'])
            && $corpData->commission_accept_flg != $data['MCorp']['commission_accept_flg']
        ) {
            $corpData->commission_accept_flg = $data['MCorp']['commission_accept_flg'];
            $corpData->commission_accept_date = date('Y-m-d H:i:s');
            $corpData->commission_accept_user_id = $userId;
        }
        return $corpData;
    }

    /**
     * format input data corp agreement type insert
     *
     * @param  integer $corpId
     * @param  array   $data
     * @param  integer $userId
     * @return array
     */
    public function formatInputDataCorpAgreementTypeInsert($corpId, $data, $userId)
    {
        $dateNow = date('Y-m-d H:i:s');
        $dataCorpAgreement['corp_id'] = $corpId;
        $dataCorpAgreement['status'] = 'NotSigned';

        if (isset($data['CorpAgreement']['agreement_date'])) {
            $dataCorpAgreement['agreement_date'] = $data['CorpAgreement']['agreement_date'];
        }

        if (isset($data['CorpAgreement']['agreement_flag'])) {
            $dataCorpAgreement['agreement_flag'] = $data['CorpAgreement']['agreement_flag'] ? true : false;
        }

        if (isset($data['CorpAgreement']['corp_kind'])) {
            $dataCorpAgreement['corp_kind'] = $data['CorpAgreement']['corp_kind'];
        }

        if (isset($data['CorpAgreement']['hansha_check'])) {
            $dataCorpAgreement['hansha_check'] = $data['CorpAgreement']['hansha_check'];
            $dataCorpAgreement['hansha_check_user_id'] = $userId;
            $dataCorpAgreement['hansha_check_date'] = $dateNow;
        }

        if (isset($data['CorpAgreement']['transactions_law'])
            && $data['CorpAgreement']['transactions_law'] == 1
        ) {
            $dataCorpAgreement['transactions_law_user_id'] = $userId;
            $dataCorpAgreement['transactions_law_date'] = $dateNow;
        }
        $this->formatCorpAgreementStatus($data, $dataCorpAgreement, $userId, $dateNow);
        return $dataCorpAgreement;
    }

    /**
     * format corp agreement status
     * @param  array $data
     * @param  array $dataCorpAgreement
     * @param  integer $userId
     * @param  string $dateNow
     */
    private function formatCorpAgreementStatus($data, &$dataCorpAgreement, $userId, $dateNow)
    {
        if (isset($data['CorpAgreement']['acceptation'])
            && $data['CorpAgreement']['acceptation'] == 1
            && !empty($dataCorpAgreement['status'])
            && $dataCorpAgreement['status'] == "Application"
        ) {
            $dataCorpAgreement['status'] = 'Complete';
            $dataCorpAgreement['acceptation_user_id'] = $userId;
            $dataCorpAgreement['acceptation_date'] = $dateNow;
        }
    }

    /**
     * get data category from category temp
     *
     * @param  array  $arrayField
     * @param  object $categoryTemp
     * @return array
     */
    public function getDataCategoryFromCategoryTemp($arrayField, $categoryTemp)
    {
        $arrayValue['id'] = isset($categoryTemp->mcc_id) ? $categoryTemp->mcc_id : '';
        foreach ($arrayField as $field) {
            $arrayValue[$field] = isset($categoryTemp->$field) ? $categoryTemp->$field : null;
        }
        return $arrayValue;
    }

    /**
     * check update category
     *
     * @param  object $mCorpCategory
     * @return string
     */
    public function checkUpdateCategory($mCorpCategory)
    {
        $updateColumn = $this->getRawUpdateColumn($mCorpCategory);

        if (count($updateColumn) > 0) {
            $retStr = implode(',', $updateColumn);
            if ($retStr !== '') {
                $retStr = 'Update:' . $retStr;
            }
        } else {
            $retStr = null;
        }

        return $retStr;
    }

    /**
     * @param $mCorpCategory
     * @return array
     */
    public function getRawUpdateColumn($mCorpCategory)
    {
        $updateColumn = [];
        if (!empty($mCorpCategory->action)) {
            $updateColumn[] = $mCorpCategory->action;
            return $updateColumn;
        }
        if ($mCorpCategory->mcc_order_fee !== $mCorpCategory->order_fee) {
            $updateColumn[] = 'order_fee';
        }
        if ($mCorpCategory->mcc_order_fee_unit !== $mCorpCategory->order_fee_unit) {
            $updateColumn[] = 'order_fee_unit';
        }
        if ($mCorpCategory->mcc_introduce_fee !== $mCorpCategory->introduce_fee) {
            $updateColumn[] = 'introduce_fee';
        }
        if ($mCorpCategory->mcc_corp_commission_type !== $mCorpCategory->corp_commission_type) {
            $updateColumn[] = 'corp_commission_type';
        }

        $this->getDataUpdate($mCorpCategory, $updateColumn);
        return $updateColumn;
    }

    /**
     * get data update
     * @param  array $mCorpCategory
     * @param  array $updateColumn
     */
    public function getDataUpdate($mCorpCategory, &$updateColumn)
    {
        $srcNote = empty($mCorpCategory->mcc_note) ? '' : $mCorpCategory->mcc_note;
        $destNote = empty($mCorpCategory->note) ? '' : $mCorpCategory->note;

        $srcSelectList = empty($mCorpCategory->mcc_select_list) ? '' : $mCorpCategory->mcc_select_list;
        $destSelectList = empty($mCorpCategory->select_list) ? '' : $mCorpCategory->select_list;

        if ($srcNote !== $destNote) {
            $updateColumn[] = 'note';
        }
        if ($srcSelectList !== $destSelectList) {
            $updateColumn[] = 'select_list';
        }
    }

    /**
     * check edit corp category
     *
     * @param  array   $data
     * @param  integer $categoryId
     * @param  integer $count
     * @return boolean
     */
    public function checkEditCorpCategory($data, $categoryId, $count)
    {
        $resultsFlg = true;
        for ($i = 0; $i < $count; $i++) {
            if (!empty($data [$i] ['category_id'])) {
                if ($data [$i] ['category_id'] == $categoryId) {
                    $resultsFlg = false;
                    break;
                }
            }
        }
        return $resultsFlg;
    }

    /**
     * format input data corp agreement type update
     *
     * @param  object  $corpAgreement
     * @param  array   $data
     * @param  integer $userId
     * @return object
     */
    public function formatInputDataCorpAgreementTypeUpdate($corpAgreement, $data, $userId)
    {
        $dateNow = date('Y-m-d H:i:s');
        if (isset($data['CorpAgreement']['agreement_date'])) {
            $corpAgreement->agreement_date = $data['CorpAgreement']['agreement_date'];
        }

        if (isset($data['CorpAgreement']['agreement_flag'])) {
            $corpAgreement->agreement_flag = $data['CorpAgreement']['agreement_flag'] ? true : false;
        }

        if (isset($data['CorpAgreement']['corp_kind'])) {
            $corpAgreement->corp_kind = $data['CorpAgreement']['corp_kind'];
        }

        $this->formatCorpAgreementHashaCheck($data, $corpAgreement, $userId, $dateNow);
        $this->formatCorpAgreementTransactionLawInfo($data, $corpAgreement, $userId, $dateNow);
        $this->formatCorpAgreementAcceptationInfo($data, $corpAgreement, $userId, $dateNow);
        return $corpAgreement;
    }

    /**
     * format corp agreement hasha check
     * @param  array $data
     * @param  object $corpAgreement
     * @param  integer $userId
     * @param  string $dateNow
     */
    public function formatCorpAgreementHashaCheck($data, &$corpAgreement, $userId, $dateNow)
    {
        if (isset($data['CorpAgreement']['hansha_check'])) {
            if (!isset($corpAgreement->hansha_check)
                || (isset($corpAgreement->hansha_check)
                && $corpAgreement->hansha_check != $data['CorpAgreement']['hansha_check'])
            ) {
                $corpAgreement->hansha_check = $data['CorpAgreement']['hansha_check'];
                $corpAgreement->hansha_check_user_id = $userId;
                $corpAgreement->hansha_check_date = $dateNow;
            }
        }
    }

    /**
     * format corp agreement transaction law info
     * @param  array $data
     * @param  object $corpAgreement
     * @param  integer $userId
     * @param  string $dateNow
     */
    public function formatCorpAgreementTransactionLawInfo($data, &$corpAgreement, $userId, $dateNow)
    {
        if (isset($data['CorpAgreement']['transactions_law'])
            && $data['CorpAgreement']['transactions_law'] == 1
        ) {
            if (!isset($corpAgreement->transactions_law_date)) {
                $corpAgreement->transactions_law_user_id = $userId;
                $corpAgreement->transactions_law_date = $dateNow;
            }
        } else {
            $corpAgreement->transactions_law_user_id = null;
            $corpAgreement->transactions_law_date = null;
        }
    }

    /**
     * format corp agreement acceptation info
     * @param  array $data
     * @param  object $corpAgreement
     * @param  integer $userId
     * @param  string $dateNow
     */
    public function formatCorpAgreementAcceptationInfo($data, &$corpAgreement, $userId, $dateNow)
    {
        if (isset($data['CorpAgreement']['acceptation'])
            && $data['CorpAgreement']['acceptation'] == 1
        ) {
            $this->formatCorpAgreementAcceptation($corpAgreement, $userId, $dateNow);
        } else {
            if (!empty($data['agreement_remand_flg']) && $data['agreement_remand_flg']) {
                $corpAgreement->status = 'Reconfirmation';
            }
            $corpAgreement->acceptation_user_id = null;
            $corpAgreement->acceptation_date = null;
        }
    }

    /**
     * format corp agreement acceptation
     * @param  object $corpAgreement
     * @param  integer $userId
     * @param  string $dateNow
     */
    public function formatCorpAgreementAcceptation(&$corpAgreement, $userId, $dateNow)
    {
        if (!isset($corpAgreement->acceptation_date)) {
            $corpAgreement->acceptation_user_id = $userId;
            $corpAgreement->acceptation_date = $dateNow;
        }

        if (!empty($corpAgreement->status) && $corpAgreement->status == "Application") {
            $corpAgreement->status = 'Complete';
        }
        if (!empty($corpAgreement->kind) && $corpAgreement->kind == 'FAX') {
            $corpAgreement->status = 'Complete';
        }
    }

    /**
     * get list by corp_id
     *
     * @param integer $corpId
     * @return array|mixed
     */
    public function getListByCorpId($corpId)
    {
        return $this->mCorpTargetAreaRepository->getListByCorpId($corpId);
    }

    /**
     * find last agreement
     * @return array|mixed
     */
    public function findLastAgreement()
    {
        return $this->agreementRepository->findLastItem();
    }

    /**
     * count by category id and genre id
     *
     * @param  integer $categoryId
     * @param  integer $genreId
     * @return integer
     */
    public function countByCategoryIdAndGenreId($categoryId, $genreId)
    {
        return $this->mCategoryRepository->countByCategoryIdAndGenreId($categoryId, $genreId);
    }
}
