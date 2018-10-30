<?php

namespace App\Services\Report;

use App\Repositories\ApprovalRepositoryInterface;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\DB;

class ReportAdminService
{
    /**
     * @var ApprovalRepositoryInterface
     */
    protected $approvalRepo;

    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgrTempLinkRepo;

    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCateTempRepo;

    /**
     * ReportAdminService constructor.
     * @param ApprovalRepositoryInterface $approvalRepo
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepo
     */
    public function __construct(
        ApprovalRepositoryInterface $approvalRepo,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepo
    ) {
        $this->approvalRepo = $approvalRepo;
        $this->corpAgrTempLinkRepo = $corpAgreementTempLinkRepo;
        $this->mCorpCateTempRepo = $mCorpCategoriesTempRepo;
    }

    /**
     * @param integer $groupId
     * @param array $approvalIds
     * @param string $action
     * @param integer $userId
     * @return boolean
     */
    public function postCorpCategoryAppAdmin($groupId, $approvalIds, $action, $userId)
    {
        $approvals = $this->approvalRepo->getApprovalForCropCategoryAppAdminService($groupId, $approvalIds, $userId);
        $corpId = null;
        $status = -1;
        if (count($approvals) && $approvals[0]->corp_id) {
            $corpId = $approvals[0]->corp_id;
        }
        if ($action == "approval") {
            $status = 1;
        } elseif ($action == "rejection") {
            $status = 2;
        }
        if ($status > 0) {
            return $this->saveCorpCategoryTemp($corpId, $approvals, $status, $userId);
        }

        return false;
    }

    /**
     * @param integer $corpId
     * @param array $approvals
     * @param string $status
     * @param integer $userId
     * @return bool
     */
    private function saveCorpCategoryTemp($corpId, $approvals, $status, $userId)
    {
        if (empty($approvals)) {
            return false;
        }
        $corpCategory = $this->corpAgrTempLinkRepo->getByCropIdWithRelation($corpId);
        if ($corpCategory) {
            $agreementFlag = $this->checkAgreementFlag($corpCategory);
            DB::beginTransaction();
            $tempId = null;
            if ($this->isAgreementFlagStatus($agreementFlag, $status)) {
                try {
                    $tempId = $this->corpAgrTempLinkRepo->getBlankModel()->insertGetId([
                        "corp_id" => $corpId,
                        "modified" => date('Y-m-d h:i:s'),
                        "created" => date('Y-m-d h:i:s'),
                        "created_user_id" => $userId,
                        "modified_user_id" => $userId
                    ]);
                } catch (\Exception $exception) {
                    DB::rollback();

                    return false;
                }
            }

            $saveCorpCategory = [];
            $saveApproval = [];
            $iCount = 0;
            $nCount = 0;
            foreach ($corpCategory->mCorpCategoriesTemps as $mCorpCategoryTemp) {
                $upFlag = false;
                foreach ($approvals as $approval) {
                    if ($this->isUpdateCorpCategory($mCorpCategoryTemp, $approval)) {
                        $upFlag = true;
                        if ($userId == $approval->application_user_id) {
                            logger('カテゴリ手数料申請 ユーザ重複エラー: approvals_id: '.$approval->id.' user_id: '.$userId);
                            return false;
                        }
                        $saveCorpCategory[$iCount] = $mCorpCategoryTemp->toArray();
                        $saveCorpCategory[$iCount]["order_fee"] = $approval->order_fee;
                        $saveCorpCategory[$iCount]["order_fee_unit"] = $approval->order_fee_unit;
                        $saveCorpCategory[$iCount]["introduce_fee"] = $approval->introduce_fee;
                        $saveCorpCategory[$iCount]["note"] = $approval->note;
                        $saveCorpCategory[$iCount]["corp_commission_type"] = $approval->corp_commission_type;
                        $saveCorpCategory[$iCount]["modified"] = date('Y-m-d h:i:s');
                        $saveCorpCategory[$iCount]["modified_user_id"] = $userId;
                        $this->setDataCorpCategoryTempId($tempId, $iCount, $saveCorpCategory);
                        $saveApproval[$nCount]["id"] = $approval->id;
                        $saveApproval[$nCount]["application_section"] = $approval->application_section;
                        $saveApproval[$nCount]["approval_user_id"] = $userId;
                        $saveApproval[$nCount]["approval_datetime"] = date('Y-m-d h:i:s');
                        $saveApproval[$nCount]["status"] = $status;
                        $saveApproval[$nCount]["modified"] = date('Y-m-d h:i:s');
                        $saveApproval[$nCount]["modified_user_id"] = $userId;

                        $iCount++;
                        $nCount++;
                    }
                }
                $this->setDataCorpCategory($tempId, $upFlag, $iCount, $mCorpCategoryTemp, $saveCorpCategory);
            }
            $this->updateReport($saveApproval, $status, $saveCorpCategory);
            DB::commit();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param integer $tempId
     * @param integer $iCount
     * @param array $saveCorpCategory
     */
    public function setDataCorpCategoryTempId($tempId, $iCount, &$saveCorpCategory)
    {
        if (!empty($tempId)) {
            $saveCorpCategory[$iCount]["temp_id"] = $tempId;
            $saveCorpCategory[$iCount]["created"] = null;
            $saveCorpCategory[$iCount]["created_user_id"] = null;
            $saveCorpCategory[$iCount]["create_date"] = null;
            $saveCorpCategory[$iCount]["create_user_id"] = null;
            $saveCorpCategory[$iCount]["update_date"] = null;
            $saveCorpCategory[$iCount]["update_user_id"] = null;
            $saveCorpCategory[$iCount]["delete_date"] = null;
            $saveCorpCategory[$iCount]["delete_flag"] = false;
        }
    }

    /**
     * @param boolean $agreementFlag
     * @param integer $status
     * @return bool
     */
    public function isAgreementFlagStatus($agreementFlag, $status)
    {
        if ($agreementFlag && $status == 1) {
            return true;
        }
        return false;
    }
    /**
     * @param object $mCorpCategoryTemp
     * @param object $approval
     * @return bool
     */
    public function isUpdateCorpCategory($mCorpCategoryTemp, $approval)
    {
        if ($mCorpCategoryTemp->corp_id == $approval->corp_id && $mCorpCategoryTemp->category_id == $approval->category_id) {
            return true;
        }
        return false;
    }
    /**
     * @param integer $tempId
     * @param boolean $upFlag
     * @param integer $iCount
     * @param object $mCorpCategoryTemp
     * @param array $saveCorpCategory
     */
    public function setDataCorpCategory($tempId, $upFlag, &$iCount, $mCorpCategoryTemp, &$saveCorpCategory)
    {
        if (!empty($tempId) && !$upFlag) {
            $saveCorpCategory[$iCount] = $mCorpCategoryTemp->toArray();
            $saveCorpCategory[$iCount]["temp_id"] = $tempId;
            $saveCorpCategory[$iCount]["created"] = null;
            $saveCorpCategory[$iCount]["modified"] = null;
            $saveCorpCategory[$iCount]["created_user_id"] = null;
            $saveCorpCategory[$iCount]["modified_user_id"] = null;
            $saveCorpCategory[$iCount]["create_date"] = null;
            $saveCorpCategory[$iCount]["create_user_id"] = null;
            $saveCorpCategory[$iCount]["update_date"] = null;
            $saveCorpCategory[$iCount]["update_user_id"] = null;
            $saveCorpCategory[$iCount]["delete_date"] = null;
            $saveCorpCategory[$iCount]["delete_flag"] = false;
            $iCount++;
        }
    }
    /**
     * @param array $saveApproval
     * @param integer $status
     * @param array $saveCorpCategory
     * @return bool
     */
    private function updateReport($saveApproval, $status, $saveCorpCategory)
    {
        if (count($saveApproval) > 0) {
            foreach ($saveApproval as $approval) {
                try {
                    $this->approvalRepo->getBlankModel()->where("id", $approval["id"])->update($approval);
                } catch (\Exception $exception) {
                    DB::rollback();

                    return false;
                }
            }
        }
        if ($status == 1) {
            foreach ($saveCorpCategory as $corpCategory) {
                try {
                    $this->mCorpCateTempRepo->getBlankModel()->where("id", $corpCategory["id"])->update($corpCategory);
                } catch (\Exception $exception) {
                    DB::rollback();
                    return false;
                }
            }
        }
    }

    /**
     * Check Agreement Flag
     * @param object $corpCategory
     * @return bool
     */
    private function checkAgreementFlag($corpCategory)
    {
        if (!empty($corpCategory->corp_agreement_id) && in_array($corpCategory->corpAgreement->status, [
                "Complete",
                "Application",
            ])) {
            return true;
        }
        return false;
    }
}
