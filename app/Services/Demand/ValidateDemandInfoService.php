<?php

namespace App\Services\Demand;

use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\SelectGenrePrefectureRepositoryInterface;
use App\Repositories\SelectGenreRepositoryInterface;

class ValidateDemandInfoService extends BaseDemandInfoService
{
    /**
     * @var SelectGenreRepositoryInterface
     */
    public $selectGenreRepo;
    /**
     * @var SelectGenrePrefectureRepositoryInterface
     */
    public $selectGenrePrefectureRepo;
    /**
     * @var MSiteRepositoryInterface
     */
    public $mSiteRepo;

    /**
     * ValidateDemandInfoService constructor.
     * @param MSiteRepositoryInterface $mSiteRepo
     * @param SelectGenrePrefectureRepositoryInterface $selectGenrePrefectureRepo
     * @param SelectGenreRepositoryInterface $selectGenreRepo
     */
    public function __construct(
        MSiteRepositoryInterface $mSiteRepo,
        SelectGenrePrefectureRepositoryInterface $selectGenrePrefectureRepo,
        SelectGenreRepositoryInterface $selectGenreRepo
    ) {
        $this->mSiteRepo = $mSiteRepo;
        $this->selectGenreRepo = $selectGenreRepo;
        $this->selectGenrePrefectureRepo = $selectGenrePrefectureRepo;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function validateDemandInfo($attributes)
    {
        $checkOrderDate = isset($attributes['demandInfo']['order_date'])
            ? $this->checkDateFormat($attributes['demandInfo']['order_date']) : true;
        $allValid = [
            $checkOrderDate,
            $this->checkStaffCorp($attributes),
            $this->checkFollowDate($attributes),
            $this->checkDemandStatus($attributes),
            $this->checkDemandStatusAdvance($attributes),
            $this->checkDemandStatusIntroduce($attributes),
            $this->checkDemandStatusIntroduceMail($attributes),
            $this->checkDemandStatusSelectionType($attributes),
            $this->checkDemandStatusConfirm($attributes),
            $this->checkOrderFailReason($attributes),
            $this->checkReservationDemandNotEmpty($attributes),
            $this->checkCrossSellSiteNotEmpty($attributes),
            $this->checkCrossSellGenreNotEmpty($attributes),
            $this->checkSourceDemandIdNotEmpty($attributes),
            $this->checkCustomerTel($attributes['demandInfo']['customer_tel']),
            $this->checkTel1($attributes),
            $this->checkContentsString($attributes['demandInfo']['contents']),
            $this->checkContactDesiredTime2($attributes),
            $this->checkContactDesiredTime3($attributes),
            $this->checkContactDesiredTime4($attributes),
            $this->checkRequireTo($attributes),
            $this->checkContactDesiredTime5($attributes),
            $this->checkContactDesiredTime6($attributes),
            $this->checkEstimatedTime1($attributes), // estimated
            $this->checkEstimatedRequireFrom($attributes),
            $this->checkEstimatedRequireTo($attributes),
            $this->checkContactEstimatedTimeFrom($attributes),
            $this->checkContactEstimatedTimeTo($attributes),
            $this->checkRequireFrom($attributes),
            $this->checkPetTombstoneDemandNotEmpty($attributes),
            $this->checkSmsDemandNotEmpty(),
            $this->checkOrderNoMarriageNotEmpty($attributes),
            $this->checkJbrOrderNo($attributes),
            $this->checkJbrWorkContents($attributes),
            $this->checkJbrCategory($attributes),
            $this->checkJbrCategory2($attributes),
            $this->checkOrderFailDate($attributes),
            $this->checkSelectionSystem($attributes),
            $this->checkDoAuction($attributes),
            $this->checkDemandStatusIntroduceMail2($attributes)
        ];
        return !in_array(false, $allValid);
    }

    /**
     * Check demand status introduce mail
     *
     * @param array $data
     * @return bool
     */
    private function checkDemandStatusIntroduceMail2($data)
    {
        if (!($data['not-send'] == 0 && $data['send_commission_info'] == 0)) {
            return true;
        }
        $isPassed = $this->checkSelectionSystemByAuctionType($data['demandInfo']['selection_system']);
        if ($isPassed) {
            return $isPassed;
        }

        $isPassed = $this->checkSelectionSystemByAutoAuctionType($data['demandInfo']['selection_system']);
        if ($isPassed) {
            return $isPassed;
        }


        $isPassed = $this->checkDemandStatusBy($data);

        return $isPassed;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function checkDemandStatusBy($data)
    {
        if (!($data['not-send'] == 0 && $data['send_commission_info'] == 0)) {
            return true;
        }
        if (isset($data['demandInfo']['demand_status'])
            && (
                $data['demandInfo']['demand_status'] == getDivValue('demand_status', 'telephone_already')
                || $data['demandInfo']['demand_status'] == getDivValue('demand_status', 'information_sent')
            )
        ) {
            for ($i = 0; $i < 30; $i++) {
                if (isset($data['commissionInfo'][$i])) {
                    if (($data['commissionInfo'][$i]['commit_flg'] == 1)
                        && (
                            ($data['commissionInfo'][$i]['send_mail_fax'] == 1)
                            || ($data['commissionInfo'][$i]['send_mail_fax_othersend'] == 1)
                        )
                    ) {
                        return true;
                    }
                }
            }

            session()->flash(
                'demand_errors.check_demand_status_introduce_email2',
                __('demand.validation_error.mail_not_select')
            );
            return false;
        }
        return true;
    }

    /**
     * @param integer $selectionSystem
     * @return bool
     */
    private function checkSelectionSystemByAutoAuctionType($selectionSystem)
    {
        $isPassed = false;
        if (isset($selectionSystem)
            && $selectionSystem == getDivValue('selection_type', 'automatic_auction_selection')
        ) {
            $isPassed = true;
        }
        return $isPassed;
    }

    /**
     * @param integer $selectionSystem
     * @return bool
     */
    private function checkSelectionSystemByAuctionType($selectionSystem)
    {
        $isPassed = false;
        if (isset($selectionSystem)
            && $selectionSystem == getDivValue('selection_type', 'auction_selection')
        ) {
            $isPassed = true;
        }
        return $isPassed;
    }

    /**
     * @param $attributes
     * @return bool
     */
    private function checkStaffCorp($attributes)
    {
        if ($attributes['demandInfo']['demand_status'] === '5') {
            $listCorp = [];
            foreach ($attributes['commissionInfo'] as $data) {
                if (isset($data['commit_flg']) &&  $data['commit_flg'] === '1') {
                    array_push($listCorp, $data);
                }
            }
            if (count($listCorp) > 1) {
                for ($i = 0; $i < count($listCorp) - 1; $i++) {
                    for ($j = $i+1; $j < count($listCorp); $j++) {
                        if ($listCorp[$i]['corp_id'] === $listCorp[$j]['corp_id']) {
                            session()->flash('check_staff_in_corp', __('demand.validation_error.check_staff_in_corp'));
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDoAuction($attributes)
    {
        if (isset($attributes['demandInfo']['do_auction'])
            && !empty($attributes['demandInfo']['do_auction'])
            && $attributes['demandInfo']['selection_system'] != getDivValue('selection_type', 'auction_selection')
            && $attributes['demandInfo']['selection_system'] !=
            getDivValue('selection_type', 'automatic_auction_selection')
        ) {
            session()->flash('demand_errors.check_do_auction', __('demand.validation_error.check_do_auction'));
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkSelectionSystem($attributes)
    {
        $check = true;

        if (!empty($attributes['demandInfo']['do_auction'])) {
            if ($attributes['demandInfo']['do_auction'] == 1 || $attributes['demandInfo']['do_auction'] == 2) {
                if ($attributes['demandInfo']['selection_system'] == getDivValue('selection_type', 'auction_selection')
                    || $attributes['demandInfo']['selection_system'] ==
                    getDivValue('selection_type', 'automatic_auction_selection')) {
                    $check = $this->checkGenrePrefecture(
                        $attributes['demandInfo']['genre_id'],
                        $attributes['demandInfo']['address1'],
                        $check
                    );
                }
            }
        }

        if (!$check) {
            session()->flash(
                'demand_errors.check_selection_system',
                __('demand.validation_error.check_selection_system')
            );
            return false;
        }

        return true;
    }

    /**
     * @param integer $genreId
     * @param string $address1
     * @param boolean $check
     * @return bool
     */
    private function checkGenrePrefecture($genreId, $address1, $check)
    {

        $genreData = $this->selectGenreRepo->findByGenreId($genreId);

        if (empty($genreData)) {
            $check = false;
        }

        if ($genreData->select_type != getDivValue('selection_type', 'auction_selection')
            && $genreData->select_type != getDivValue('selection_type', 'automatic_auction_selection')
        ) {
            $check = false;
        } else {
            $genrePrefectureData = $this->selectGenrePrefectureRepo->getByGenreIdAndPrefectureCd(
                [
                    'genre_id' => $genreId,
                    'address1' => $address1
                ]
            );
            if (!empty($genrePrefectureData)
                && $genrePrefectureData->selection_type != ""
                && $genrePrefectureData->selection_type != null
                && ($genrePrefectureData->selection_type !=
                    getDivValue('selection_type', 'auction_selection')
                    && $genrePrefectureData->selection_type !=
                    getDivValue('selection_type', 'automatic_auction_selection'))
            ) {
                $check = false;
            }
        }
        return $check;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkOrderFailDate($attributes)
    {
        if (empty($attributes['demandInfo']['order_fail_date'])
            && $attributes['demandInfo']['demand_status'] == getDivValue('demand_status', 'order_fail')
        ) {
            session()->flash('demand_errors.check_date_format', __('demand.validation_error.check_date_format'));
            return false;
        }

        return true;
    }

    /**
     * @param integer $siteId
     * @return bool
     */
    private function checkJbrSite($siteId)
    {
        $rslt = false;
        if (empty($siteId)) {
            return $rslt;
        }
        $site = $this->mSiteRepo->findById($siteId);
        if ($site['jbr_flg'] == 1) {
            $rslt = true;
        }
        return $rslt;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkJbrCategory2($attributes)
    {
        if (isset($attributes['demandInfo']['jbr_category']) && empty($attributes['demandInfo']['jbr_category'])) {
            $jbr = $this->checkJbrSite($attributes['demandInfo']['site_id']);

            if ($jbr) {
                session()->flash(
                    'demand_errors.check_jbr_category2',
                    __('demand.validation_error.check_jbr_not_empty')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkJbrCategory($attributes)
    {
        if (isset($attributes['demandInfo']['jbr_category']) && empty($attributes['demandInfo']['jbr_category'])
            && $attributes['demandInfo']['jbr_work_contents'] == getDivValue('jbr_work', 'pest_extermination')) {
            session()->flash(
                'demand_errors.check_jbr_category',
                __('demand.validation_error.check_jbr_category_not_empty')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkJbrWorkContents($attributes)
    {
        if (empty($attributes['demandInfo']['jbr_work_contents'])) {
            $jbr = $this->checkJbrSite($attributes['demandInfo']['site_id']);
            if ($jbr) {
                session()->flash(
                    'demand_errors.check_jbr_work_contents',
                    __('demand.validation_error.check_jbr_not_empty')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkJbrOrderNo($attributes)
    {
        if (empty($attributes['demandInfo']['jbr_order_no'])) {
            $jbr = $this->checkJbrSite($attributes['demandInfo']['site_id']);

            if ($jbr) {
                session()->flash('demand_errors.check_jbr_order_no', __('demand.validation_error.check_jbr_not_empty'));

                return false;
            }
        }

        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDemandStatusSelectionType($attributes)
    {
        if (isset($attributes['demandInfo']['selection_system'])
            && $attributes['demandInfo']['selection_system'] != getDivValue('selection_type', 'auction_selection')) {
            return true;
        }

        if (isset($attributes['demandInfo']['selection_system'])
            && $attributes['demandInfo']['selection_system'] !=
            getDivValue('selection_type', 'automatic_auction_selection')
        ) {
            return true;
        }

        if (!empty($attributes['demandInfo']['do_auction'])
            && $attributes['demandInfo']['demand_status'] != getDivValue('demand_status', 'no_selection')
        ) {
            session()->flash(
                'demand_errors.check_demand_status_selection_type',
                __('demand.validation_error.check_demand_status_selection_type')
            );

            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDemandStatusConfirm($attributes)
    {
        $commissionFlgCount = 0;
        $commissionFlgCount = $this->getFlagCount($attributes['commissionInfo'], $commissionFlgCount);
        if (isset($attributes['quick_order_fail'])
            && $attributes['demandInfo']["quick_order_fail_reason"] != "") {
            return true;
        }
        if ($commissionFlgCount == 0) {
            return true;
        } else {
            switch ($attributes['demandInfo']['demand_status']) {
                case 4:
                case 5:
                case 6:
                    return true;
                    break;
                default:
                    session()->flash(
                        'demand_errors.check_demand_status_confirm',
                        __('demand.validation_error.check_demand_status_confirm')
                    );
                    return false;
                    break;
            }
        }
    }

    /**
     * @param array $commissionInfo
     * @param integer $commissionFlgCount
     * @return int
     */
    private function getFlagCount($commissionInfo, $commissionFlgCount)
    {
        if (!empty($commissionInfo)) {
            foreach ($commissionInfo as $data) {
                if ($data['corp_id']
                    && isset($data['commit_flg'])
                    && $data['commit_flg']) {
                    $commissionFlgCount = $commissionFlgCount + 1;
                }
            }
        }
        return $commissionFlgCount;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkOrderFailReason($attributes)
    {
        if (empty($attributes['demandInfo']['order_fail_reason'])
            && $attributes['demandInfo']['demand_status'] == getDivValue('demand_status', 'order_fail')
        ) {
            session()->flash(
                'demand_errors.check_order_fail_reason',
                __('demand.validation_error.check_order_fail_reason')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkReservationDemandNotEmpty($attributes)
    {
        if ($attributes['demandInfo']['site_id'] != 585) {
            if (isset($attributes['demandInfo']['reservation_demand'])
                && $attributes['demandInfo']['reservation_demand'] == null
            ) {
                session()->flash(
                    'demand_errors.check_reservation_demand_not_empty',
                    __('demand.validation_error.not_empty')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkCrossSellSiteNotEmpty($attributes)
    {
        if (in_array($attributes['demandInfo']['site_id'], [861, 863, 889, 890, 1312, 1313, 1314])
            && $attributes['demandInfo']['cross_sell_source_site'] == null) {
            session()->flash(
                'demand_errors.check_cross_sell_site_not_empty',
                __('demand.validation_error.not_empty')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkCrossSellGenreNotEmpty($attributes)
    {
        if (in_array($attributes['demandInfo']['site_id'], [861, 863, 889, 890, 1312, 1313, 1314])
            && $attributes['demandInfo']['cross_sell_source_genre'] == null) {
            session()->flash(
                'demand_errors.check_cross_sell_genre_not_empty',
                __('demand.validation_error.not_empty')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkSourceDemandIdNotEmpty($attributes)
    {
        if (in_array($attributes['demandInfo']['site_id'], [861, 863, 889, 890, 1312, 1313, 1314])
            && $attributes['demandInfo']['source_demand_id'] == null
        ) {
            session()->flash(
                'demand_errors.check_source_demand_id_not_empty',
                __('demand.validation_error.not_empty')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attribute
     * @return bool
     */
    private function checkCustomerTel($attribute)
    {
        if (!empty($attribute) && !ctype_digit($attribute) && $attribute != '非通知') {
            session()->flash('demand_errors.check_customer_tel', __('demand.validation_error.check_customer_tel'));
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkTel1($attributes)
    {
        if (empty($attributes['demandInfo']['tel1'])
            && $attributes['demandInfo']['demand_status'] != getDivValue('demand_status', 'order_fail')
        ) {
            session()->flash('demand_errors.check_tel1', __('demand.validation_error.check_tel1'));
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkOrderNoMarriageNotEmpty($attributes)
    {
        $checkSites = [477, 492, 906, 917, 460, 494, 727, 743, 758, 760, 907, 919];

        if ($attributes['demandInfo']['genre_id'] == 620
            || in_array($attributes['demandInfo']['site_id'], $checkSites)
        ) {
            if ($attributes['demandInfo']['order_no_marriage']  == null) {
                session()->flash(
                    'demand_errors.check_order_no_marriage_not_empty',
                    __('demand.validation_error.not_empty')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function checkSmsDemandNotEmpty()
    {
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkPetTombstoneDemandNotEmpty($attributes)
    {
        if ($attributes['demandInfo']['genre_id'] == 509
            && $attributes['demandInfo']['pet_tombstone_demand']  == null
        ) {
            session()->flash(
                'demand_errors.pet_tombstone_demand',
                __('demand.validation_error.check_pet_tombstone_demand_not_empty')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkRequireFrom($attributes)
    {
        if (!isset($attributes['demandInfo']['contact_desired_time_to'])) {
            return true;
        }

        if (strlen($attributes['demandInfo']['contact_desired_time_to']) == 0) {
            return true;
        }

        if (!isset($attributes['demandInfo']['contact_desired_time_from'])) {
            session()->flash(
                'demand_errors.contact_desired_time_from',
                __('demand.validation_error.check_required_from')
            );
            return false;
        }

        if (strlen($attributes['demandInfo']['contact_desired_time_from']) == 0) {
            session()->flash(
                'demand_errors.contact_desired_time_from',
                __('demand.validation_error.check_required_from')
            );
            return false;
        }
        return true;
    }

    /**
     * @param $attributes
     * @return bool
     */
    private function checkEstimatedRequireFrom($attributes)
    {
        if (!isset($attributes['demandInfo']['contact_estimated_time_to'])) {
            return true;
        }

        if (strlen($attributes['demandInfo']['contact_estimated_time_to']) == 0) {
            return true;
        }

        if (!isset($attributes['demandInfo']['contact_estimated_time_from'])) {
            session()->flash(
                'demand_errors.contact_estimated_time_from',
                __('demand.validation_error.check_required_from')
            );
            return false;
        }

        if (strlen($attributes['demandInfo']['contact_estimated_time_from']) == 0) {
            session()->flash(
                'demand_errors.contact_estimated_time_from',
                __('demand.validation_error.check_required_from')
            );
            return false;
        }
        return true;
    }
    /**
     * @param $attributes
     * @return bool
     */
    private function checkEstimatedTime1($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_estimated_time_to"])
            && !empty($attributes['demandInfo']["contact_estimated_time_from"])
        ) {
            if (strtotime($attributes['demandInfo']["contact_estimated_time_to"]) <
                strtotime($attributes['demandInfo']["contact_estimated_time_from"])
            ) {
                session()->flash(
                    'demand_errors.check_contact_estimated_time1',
                    __('demand.validation_error.past_date_time_2')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkContactDesiredTime6($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_desired_time_to"])
            && !empty($attributes['demandInfo']["contact_desired_time_from"])
        ) {
            if (strtotime($attributes['demandInfo']["contact_desired_time_to"]) <
                strtotime($attributes['demandInfo']["contact_desired_time_from"])
            ) {
                session()->flash(
                    'demand_errors.check_contact_desired_time6',
                    __('demand.validation_error.past_date_time_2')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkContactDesiredTime5($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_desired_time_to"])
            && !empty($attributes['demandInfo']['do_auction'])
            && strtotime($attributes['demandInfo']["contact_desired_time_to"]) <
            strtotime(date('Y/m/d H:i'))
        ) {
            session()->flash(
                'demand_errors.check_contact_desired_time5',
                __('demand.validation_error.past_date_time')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkRequireTo($attributes)
    {
        if (!isset($attributes['demandInfo']['contact_desired_time_from'])) {
            return true;
        }

        if (strlen($attributes['demandInfo']['contact_desired_time_from']) == 0) {
            return true;
        }

        if (!isset($attributes['demandInfo']['contact_desired_time_to'])) {
            session()->flash('demand_errors.check_require_to', __('demand.validation_error.check_require_to'));

            return false;
        }

        if (strlen($attributes['demandInfo']['contact_desired_time_to']) == 0) {
            session()->flash('demand_errors.check_require_to', __('demand.validation_error.check_require_to'));

            return false;
        }

        return true;
    }

    /**
     * @param $attributes
     * @return bool
     */
    private function checkEstimatedRequireTo($attributes)
    {
        if (!isset($attributes['demandInfo']['contact_estimated_time_from'])) {
            return true;
        }

        if (strlen($attributes['demandInfo']['contact_estimated_time_from']) == 0) {
            return true;
        }

        if (!isset($attributes['demandInfo']['contact_estimated_time_to'])) {
            session()->flash('demand_errors.check_estimated_require_to', __('demand.validation_error.check_require_to'));

            return false;
        }

        if (strlen($attributes['demandInfo']['contact_estimated_time_to']) == 0) {
            session()->flash('demand_errors.check_estimated_require_to', __('demand.validation_error.check_require_to'));

            return false;
        }

        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkContactDesiredTime4($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_desired_time_from"])
            && !empty($attributes['demandInfo']['do_auction'])
            && strtotime($attributes['demandInfo']["contact_desired_time_from"]) <
            strtotime(date('Y/m/d H:i'))) {
            session()->flash(
                'demand_errors.check_contact_desired_time4',
                __('demand.validation_error.past_date_time')
            );
            return false;
        }
        return true;
    }

    /**
     * @param $attributes
     * @return bool
     */
    private function checkContactEstimatedTimeFrom($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_estimated_time_from"])
            && !empty($attributes['demandInfo']['do_auction'])
            && strtotime($attributes['demandInfo']["contact_estimated_time_from"]) <
            strtotime(date('Y/m/d H:i'))) {
            session()->flash(
                'demand_errors.check_contact_estimated_time_from',
                __('demand.validation_error.past_date_time')
            );
            return false;
        }
        return true;
    }

    /**
     * @param $attributes
     * @return bool
     */
    private function checkContactEstimatedTimeTo($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_estimated_time_to"])
            && !empty($attributes['demandInfo']['do_auction'])
            && strtotime($attributes['demandInfo']["contact_estimated_time_to"]) <
            strtotime(date('Y/m/d H:i'))) {
            session()->flash(
                'demand_errors.check_contact_estimated_time_to',
                __('demand.validation_error.past_date_time')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkContactDesiredTime3($attributes)
    {
        if (!empty($attributes['demandInfo']["contact_desired_time"])
            && !empty($attributes['demandInfo']['do_auction'])
            && strtotime($attributes['demandInfo']["contact_desired_time"]) < strtotime(date('Y/m/d H:i'))) {
            session()->flash(
                'demand_errors.check_contact_desired_time3',
                __('demand.validation_error.past_date_time')
            );
            return false;
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkContactDesiredTime2($attributes)
    {
        if (empty($attributes['demandInfo']['contact_desired_time'])
            && empty($attributes['demandInfo']['contact_desired_time_from'])
            && empty($attributes['demandInfo']['contact_estimated_time_from'])
        ) {
            foreach ($attributes['visitTime'] as $val) {
                if (!empty($val['visit_time'])
                    || (!empty($val['visit_time_from'])
                        && !empty($val['visit_time_to']))
                ) {
                    return true;
                }
            }

            session()->flash('demand_errors.check_contact_desired_time2', __('demand.validation_error.not_empty'));
            return false;
        }
        return true;
    }

    /**
     * @param array $attribute
     * @return bool
     */
    private function checkContentsString($content)
    {
        if (!empty($content)) {
            foreach (getDivList('datacustom.'.'word_ban') as $keyword) {
                if (strstr($content, $keyword)) {
                    session()->flash(
                        'demand_errors.check_contents_string',
                        __('demand.validation_error.check_contents_string')
                    );
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDemandStatus($attributes)
    {
        if (isset($attributes['demandInfo']['selection_system'])
            && in_array(
                $attributes['demandInfo']['selection_system'],
                [
                    getDivValue('selection_type', 'auction_selection'),
                    getDivValue('selection_type', 'automatic_auction_selection')
                ]
            )
        ) {
            return true;
        }

        if (in_array(
            $attributes['demandInfo']['demand_status'],
            [getDivValue('demand_status', 'no_selection'), getDivValue('demand_status', 'no_guest')]
        )
        ) {
            return true;
        }
        for ($i = 0; $i < 30; $i++) {
            if (isset($attributes['commissionInfo'][$i])
                && !empty($attributes['commissionInfo'][$i]['corp_id'])
                && empty($attributes['commissionInfo'][$i]['lost_flg'])
            ) {
                return true;
            }
        }

        session()->flash('demand_errors.check_demand_status', __('demand.validation_error.check_demand_status'));

        return false;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDemandStatusAdvance($attributes)
    {
        $demandStatus = $attributes['demandInfo']['demand_status'];

        if (isset($attributes['demandInfo']['selection_system'])
            && in_array(
                $attributes['demandInfo']['selection_system'],
                [
                    getDivValue('selection_type', 'auction_selection'),
                    getDivValue('selection_type', 'automatic_auction_selection')
                ]
            )
        ) {
            return true;
        }

        if (in_array(
            $demandStatus,
            [
                getDivValue('demand_status', 'telephone_already'),
                getDivValue('demand_status', 'information_sent')
            ]
        )
        ) {
            for ($i = 0; $i < 30; $i++) {
                if (isset($attributes['commissionInfo'][$i])) {
                    if (!empty($attributes['commissionInfo'][$i]['corp_id'])) {
                        if (empty($attributes['commissionInfo'][$i]['commit_flg'])
                            && empty($attributes['commissionInfo'][$i]['lost_flg'])
                        ) {
                            session()->flash(
                                'demand_errors.check_demand_status_advance',
                                __('demand.validation_error.check_demand_status_advance')
                            );
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDemandStatusIntroduce($attributes)
    {
        if (isset($attributes['demandInfo']['demand_status'])
            && $attributes['demandInfo']['demand_status'] == getDivValue('demand_status', 'no_selection')
        ) {
            $corpCnt = 0;
            $lostCnt = 0;
            $delCnt  = 0;

            for ($i = 0; $i < 30; $i++) {
                list($corpCnt, $lostCnt, $delCnt) = $this->countCommissionInfo($attributes, $corpCnt, $lostCnt, $delCnt, $i);
            }
            if ($corpCnt != ($lostCnt + $delCnt)) {
                session()->flash(
                    'demand_errors.check_demand_status_introduce',
                    __('demand.validation_error.check_demand_status_introduce')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $attributes
     * @param integer t$corpCnt
     * @param integer $lostCnt
     * @param integer $delCnt
     * @param integer $index
     * @return array
     */
    private function countCommissionInfo($attributes, $corpCnt, $lostCnt, $delCnt, $index)
    {
        if (isset($attributes['commissionInfo']) && !empty($attributes['commissionInfo'][$index]['corp_id'])) {
            $corpCnt++;
        }

        if (isset($attributes['commissionInfo'])
            && !empty($attributes['commissionInfo'][$index]['corp_id'])
            && $attributes['commissionInfo'][$index]['lost_flg'] == 1) {
            $lostCnt++;
        } elseif (isset($attributes['commissionInfo'])
            && !empty($attributes['commissionInfo'][$index]['corp_id'])
            && $attributes['commissionInfo'][$index]['del_flg'] == 1) {
            $delCnt++;
        }

        return [$corpCnt, $lostCnt, $delCnt];
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkDemandStatusIntroduceMail($attributes)
    {
        $isPassed = isset($attributes['demandInfo']['send_commission_info']) && $this->checkSendCommissionInfo($attributes['demandInfo']['send_commission_info']);

        if ($isPassed) {
            return $isPassed;
        }

        if (isset($attributes['demandInfo']['demand_status']) && $attributes['demandInfo']['demand_status'] == getDivValue('demand_status', 'agency_before')) {
            for ($i = 0; $i < 30; $i++) {
                if (isset($attributes['commissionInfo'][$i])
                    && !empty($attributes['commissionInfo'][$i]['send_mail_fax'])
                    && $attributes['commissionInfo'][$i]['send_mail_fax'] == 1
                    && ($attributes['commissionInfo'][$i]['lost_flg'] == 0)
                    && ($attributes['commissionInfo'][$i]['del_flg'] == 0)
                ) {
                    session()->flash(
                        'demand_errors.check_demand_status_introduce_email',
                        __('demand.validation_error.check_demand_status_introduce_email')
                    );
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param boolean $sendCommissionInfo
     * @return bool
     */
    private function checkSendCommissionInfo($sendCommissionInfo)
    {
        $isPassed = false;
        if (isset($sendCommissionInfo)
            && $sendCommissionInfo == 1) {
            $isPassed = true;
        }
        return $isPassed;
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function checkFollowDate($attributes)
    {
        $check = empty($attributes['demandInfo']['follow_date'])
            || $attributes['demandInfo']['follow_date'] instanceof \DateTime
            || (strtotime($attributes['demandInfo']['follow_date']) > strtotime(date('Y/m/d')));

        if (!$check) {
            session()->flash('demand_errors.follow_date', __('demand.validation_error.check_follow_date'));
            return false;
        }
        return true;
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function checkDateFormat($attribute)
    {
        $datePart = explode('/', $attribute);
        $check = true;

        switch (true) {
            case count($datePart) < 3:
                $check = false;
                break;
            case strlen($datePart[0]) < 4:
                $check = false;
                break;
            case strlen($datePart[1]) < 2:
                $check = false;
                break;
            case strlen($datePart[2]) < 2:
                $check = false;
                break;
        }

        if (!$check) {
            session()->flash('demand_errors.check_date_format', __('demand.validation_error.check_date_format'));
            return false;
        }
        return true;
    }
}
