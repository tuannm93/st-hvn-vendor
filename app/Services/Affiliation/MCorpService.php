<?php

namespace App\Services\Affiliation;

use App\Services\BaseService;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;

class MCorpService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepository;

    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    private $mCorpTargetAreaRepository;

    /**
     * MCorpService constructor.
     *
     * @param MCorpRepositoryInterface           $mCorpRepository
     * @param MCorpCategoryRepositoryInterface   $mCorpCategoryRepository
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepository
    ) {
        $this->mCorpRepository = $mCorpRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->mCorpTargetAreaRepository = $mCorpTargetAreaRepository;
    }

    //region Public Functions

    /**
     * Check info of m_corp return true if lack of m_corp information and return false if enough information and
     *
     * @param integer $corpId
     * @return boolean
     */
    public function checkDataOfCorpById($corpId)
    {
        $mCorpColumns = ['id', 'corp_person', 'responsibility', 'address1', 'address2', 'address3', 'tel1', 'tel2', 'coordination_method', 'mailaddress_pc', 'corp_commission_type', 'coordination_method', 'mobile_mail_none', 'mailaddress_mobile', 'commission_dial', 'fax', 'support24hour', 'available_time_other', 'available_time_from', 'available_time_to', 'contactable_time_other', 'contactable_support24hour', 'contactable_time_from', 'contactable_time_to'];
        $mCorp = $this->mCorpRepository->getDataById($corpId, $mCorpColumns)->toArray();
        $countMCorpCategories = $this->mCorpCategoryRepository->getCountByCorpId($corpId);

        if ($countMCorpCategories == 0) {
            return true;
        }

        $checkBaseInfo = $this->isCheckBaseInfo($mCorp);
        if ($checkBaseInfo) {
            return true;
        }

        $checkCorpCommissionType = $this->isCheckCorpCommissionType($mCorp);
        if ($checkCorpCommissionType) {
            return true;
        }

        $checkCoordinationMethod = $this->isCheckCoordinationMethod($mCorp);
        if ($checkCoordinationMethod) {
            return true;
        }

        $checkTimeSupport = $this->isCheckTimeSupport($mCorp);
        if ($checkTimeSupport) {
            return true;
        }

        $checkContactableTime = $this->isCheckContactableTime($mCorp);
        if ($checkContactableTime) {
            return true;
        }

        return false;
    }

    /**
     * @param array $mCorp
     * @return bool
     */
    private function isCheckBaseInfo($mCorp)
    {
        if (strlen($mCorp['corp_person']) == 0) {
            return true;
        }
        if (strlen($mCorp['responsibility']) == 0) {
            return true;
        }
        if (strlen($mCorp['address1']) == 0) {
            return true;
        }
        if (strlen($mCorp['address2']) == 0) {
            return true;
        }
        if (strlen($mCorp['address3']) == 0) {
            return true;
        }
        if (strlen($mCorp['tel1']) == 0) {
            return true;
        }

        return false;
    }

    /**
     * @param array $mCorp
     * @return bool
     */
    private function isCheckCoordinationMethod($mCorp)
    {
        switch ($mCorp['coordination_method']) {
            case getDivValue('coordination_method', 'mail_fax'):
            case getDivValue('coordination_method', 'mail'):
            case getDivValue('coordination_method', 'mail_app'):
            case getDivValue('coordination_method', 'mail_fax_app'):
                if (empty($mCorp['mailaddress_pc'])) {
                    return true;
                }
                break;
            default:
                break;
        }

        if ($mCorp['coordination_method'] == 0) {
            return true;
        }

        switch ($mCorp['coordination_method']) {
            case getDivValue('coordination_method', 'mail_fax'):
            case getDivValue('coordination_method', 'fax'):
            case getDivValue('coordination_method', 'mail_fax_app'):
                if (empty($mCorp['fax'])) {
                    return true;
                }
                break;
            default:
                break;
        }

        return false;
    }

    /**
     * @param array $mCorp
     * @return bool
     */
    private function isCheckCorpCommissionType($mCorp)
    {
        if ($mCorp['corp_commission_type'] != 2) {
            switch ($mCorp['coordination_method']) {
                case getDivValue('coordination_method', 'mail_fax'):
                case getDivValue('coordination_method', 'mail'):
                    if ($mCorp['mobile_mail_none'] != 1 && empty($mCorp['mailaddress_mobile'])) {
                        return true;
                    }
                    break;
                case getDivValue('coordination_method', 'mail_app'):
                case getDivValue('coordination_method', 'mail_fax_app'):
                    if (empty($mCorp['mailaddress_mobile'])) {
                        return true;
                    }
                    break;
                default:
                    break;
            }
            if (empty($mCorp['commission_dial'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $mCorp
     * @return bool
     */
    private function isCheckTimeSupport($mCorp)
    {
        if (($mCorp['support24hour'] != 1) && ($mCorp['available_time_other'] != 1)) {
            return true;
        }
        if (($mCorp['support24hour'] == 1) && ($mCorp['available_time_other'] == 1)) {
            return true;
        }
        if (($mCorp['support24hour'] != 1) && empty($mCorp['available_time_from'])) {
            return true;
        }
        if (($mCorp['support24hour'] != 1) && empty($mCorp['available_time_to'])) {
            return true;
        }

        return false;
    }

    /**
     * @param array $mCorp
     * @return bool
     */
    private function isCheckContactableTime($mCorp)
    {
        if (($mCorp['contactable_support24hour'] != 1) && ($mCorp['contactable_time_other'] != 1)) {
            return true;
        }
        if (($mCorp['contactable_support24hour'] == 1) && ($mCorp['contactable_time_other'] == 1)) {
            return true;
        }
        if (($mCorp['contactable_support24hour'] != 1) && empty($mCorp['contactable_time_from'])) {
            return true;
        }
        if (($mCorp['contactable_support24hour'] != 1) && empty($mCorp['contactable_time_to'])) {
            return true;
        }

        return false;
    }


    /**
     * Get info last update profile of m_corp
     *
     * @param integer $corpId
     * @return string | null
     */
    public function getLastMCorpUpdateProfile($corpId)
    {
        $mCorp = $this->mCorpRepository->getDataById($corpId, ['id', 'modified']);
        return $mCorp !== null ? $mCorp->modified : null;
    }

    /**
     * Get info last update categories of m_corp
     *
     * @param integer $corpId
     * @return string | null
     */
    public function getLastMCorpUpdateCategory($corpId)
    {
        $mCorpCategory = $this->mCorpCategoryRepository->getLastByCorpId($corpId, ['m_corp_categories.id', 'm_corp_categories.modified'], ['column' => 'm_corp_categories.modified', 'dir' => 'desc']);
        return $mCorpCategory !== null ? $mCorpCategory->modified : null;
    }

    /**
     * Get info last update target area of m_corp
     *
     * @param integer $corpId
     * @return string | null
     */
    public function getLastMCorpUpdateArea($corpId)
    {
        $mCorpArea = $this->mCorpTargetAreaRepository->getLastByMCorp($corpId, ['m_corp_target_areas.id', 'm_corp_target_areas.modified'], ['column' => 'm_corp_target_areas.modified', 'dir' => 'desc']);
        return $mCorpArea !== null ? $mCorpArea->modified : null;
    }
}
