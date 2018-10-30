<?php

namespace App\Services\Demand;

use App\Helpers\Sanitize;
use App\Models\AuctionGenre;
use App\Services\BaseService;
use PhpOffice\PhpWord\TemplateProcessor;

class BaseDemandInfoService extends BaseService
{
    const NON_NOTIFICATION = '非通知';
    const USER = "SYSTEM";

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @return array|boolean|\Illuminate\Config\Repository|mixed
     */
    public function translatePrefecture()
    {
        return $this->translate(config('rits.prefecture_div'), 'rits_config');
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getPriorityTranslate()
    {
        return $this->translate(config('rits.priority'), 'auction');
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function translateSelectionSystem()
    {
        return $this->translate(config('rits.selection_type'), 'auto_commission_corp', false);
    }

    /**
     * @param object $commissionData
     * @param string $inquiryData
     * @param bool $isMailFile
     * @return array
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function makeWordFile($commissionData, $inquiryData = '', $isMailFile = true)
    {
        $demandInfo = $commissionData->demandInfo;
        if ($demandInfo->mSite->jbr_flg == 1 && $demandInfo->jbr_work_contents == config('datacustom.jbr_glass_category')) {
            $template = config('datacustom.commission_template_jbrglass');
        } elseif ($demandInfo->mSite->jbr_flg == 1) {
            $template = config('datacustom.commission_template_jbr');
        } else {
            if (isset($commissionData->commission_type) && $commissionData->commission_type == 1) {
                $template = config('datacustom.commission_template_introduce');
            } else {
                $template = config('datacustom.commission_template');
            }
        }

        $document = new TemplateProcessor($template);
        /* Conversion source character
        *(If WORD can not be opened due to a character, add characters before conversion and converted)
        */
        $org = ["“", "”", "−"];
        //Translated character
        $new = ["\"", "\"", "-"];
        //Data setting
        $document->setValue('corp_name', Sanitize::html($commissionData->mCorp->official_corp_name));
        $document->setValue('confirmd_fee_rate', $commissionData->commission_fee_rate);
        $document->setValue('demand_id', $demandInfo->id);
        $document->setValue('site_name', Sanitize::html($demandInfo->mSite->site_name));
        $document->setValue('note', Sanitize::html($demandInfo->mSite->note));
        $document->setValue('customer_name', Sanitize::html(str_replace($org, $new, $demandInfo->customer_name)));

        $customerAddress = getDivTextJP('prefecture_div', $demandInfo->address1)
            . $demandInfo->address2
            . $demandInfo->address3
            . $demandInfo->address4
            . $demandInfo->building
            . $demandInfo->room;
        $customerAddress = str_replace($org, $new, $customerAddress);
        $document->setValue('address', Sanitize::html($customerAddress));
        $document->setValue('construction_class', getDropText('建物種別', $demandInfo->construction_class));

        $document->setValue('tel1', $demandInfo->tel1);
        $document->setValue('tel2', $demandInfo->tel2);
        $document->setValue(
            'contents',
            str_replace(
                "\n",
                "<w:br/>",
                Sanitize::html(str_replace($org, $new, $demandInfo->contents))
            )
        );
        $document->setValue('contents1', $inquiryData);
        $document->setValue('receptionist', Sanitize::html($demandInfo->mUser->user_name));
        $document->setValue('commission_id', $commissionData->id);

        $document->setValue('jbr_order_no', Sanitize::html($demandInfo->jbr_order_no));
        $document->setValue(
            'jbr_work_contents',
            Sanitize::html(getDropText('[JBR様]作業内容', $demandInfo->jbr_work_contents))
        );

        $filePath = config('datacustom.commission_tmp_dir')
            . sprintf('commission_%s_%s.docx', $demandInfo->id, $commissionData->id);
        $document->saveAs($filePath);
        if ($isMailFile) {
            $fileName = mb_encode_mimeheader(
                mb_convert_encoding(
                    sprintf(
                        '%s_%s_%s.docx',
                        __('commission.commission_print_name'),
                        $commissionData->mCorp->official_corp_name,
                        $demandInfo->id
                    ),
                    'ISO-2022-JP',
                    'UTF-8'
                )
            );
        } else {
            $fileName = mb_convert_encoding(
                sprintf(
                    '%s_%s_%s.docx',
                    __('commission.commission_print_name'),
                    $commissionData->mCorp->official_corp_name,
                    $demandInfo->id
                ),
                'SJIS-win',
                'UTF-8'
            );
        }

        return ['fileName' => $fileName, 'filePath' => $filePath];
    }

    /**
     * @param array $attributes
     * @return bool
     */
    public function validateDemandInquiryAnswer($attributes)
    {
        if (!isset($attributes['demandInquiryAnswer'])) {
            return false;
        }
        return !isset($attributes['demandInquiryAnswer']['demand_id'])
            || empty($attributes['demandInquiryAnswer']['demand_id'])
            || !is_int($attributes['demandInquiryAnswer']['demand_id']
                || !is_int($attributes['demandInquiryAnswer']['inquiry_id']));
    }

    /**
     * @author thaihv
     * add some default data for demand input data
     * @param array $demandData
     * @param int $defaultValue
     * @return mixed
     */
    public function addDefaultValueForDemand($demandData, $defaultValue = 0)
    {
        $requestMerge = [
            'nighttime_takeover',
            'mail_demand',
            'cross_sell_implement',
            'cross_sell_call',
            'riro_kureka',
            'remand',
            'sms_reorder',
            'corp_change',
            'low_accuracy',
            'do_auction',
            'follow',
            'auction',
            'calendar_flg'
        ];

        foreach ($requestMerge as $merge) {
            if (!isset($demandData[$merge])) {
                $demandData[$merge] = $defaultValue;
            }
        }
        $demandData['priority'] = $demandData['priority'] ?? '';
        $demandData['pet_tombstone_demand'] = $demandData['pet_tombstone_demand'] ?? null;
        // remove demand_status
        // make sure that demand_status don't receive from view
        $demandData['selection_system'] = isset($demandData['selection_system']) ? $demandData['selection_system'] : 0;
        $demandData['do_auto_selection_category'] = 0;
        $demandData['do_auto_selection'] = 0;
        //Initialize the selection flag indicating that the member shop was designated by region × category
//        $demandData['selection_system_before'] = '';
        if (!isset($demandData['demand_status'])) {
            $demandData['demand_status'] = '';
        }
        return $demandData;
    }

    /**
     * replaceSpace
     * @author thaihv
     * @param  array $demandInfo demand info data
     * @return array            demand info data
     */
    public function replaceSpace($demandInfo)
    {
        array_walk($demandInfo, function (&$demandItem) {
            $demandItem = preg_replace('/^[ 　]+/u', '', $demandItem);
            $demandItem = preg_replace('/[ 　]+$/u', '', $demandItem);
        });

        return $demandInfo;
    }

    /**
     * @author thaihv
     * @param  array $demandInfo demand info data
     * @return array             demand info data
     */
    public function checkDemandInfoDoAuction($demandInfo)
    {
        $arraySelection = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ]; //[2,3]

        if (isset($demandInfo['selection_system'])
            && in_array($demandInfo['selection_system'], $arraySelection)
            && $demandInfo['selection_system_before'] == '') {
            $demandInfo['do_auction'] = 1;
        }

        return $demandInfo;
    }

    /**
     * @author thaihv
     * @param  array $demandInfoData demand info data
     * @return array                 demand info data
     */
    public function checkDoAutoSelection($demandInfoData)
    {
        if (isset($demandInfoData['selection_system'])
            && $demandInfoData['selection_system'] == getDivValue('selection_type', 'auto_selection')) {
            $demandInfoData['do_auto_selection'] = 1;
        }

        return $demandInfoData;
    }

    /**
     * update demand correspond content
     * @author  thaihv
     * @param  array $demandCorrespond demand correspond data
     * @return array                   demand correspond data
     */
    public function updateCorrespondContent($demandCorrespond)
    {
        if ($demandCorrespond['corresponding_contens'] != "") {
            $demandCorrespond['corresponding_contens'] = "ワンタッチ失注で登録\r\n"
                . $demandCorrespond['corresponding_contens'];//Orange-1155
            return $demandCorrespond;
        }

        $demandCorrespond['corresponding_contens'] = "ワンタッチ失注で登録";
        return $demandCorrespond;
    }

    /**
     * validate commission type div
     * @author  thaihv
     * @param  int $typeDiv commission type div
     * @param  boolean $isCommissionExists exist commission
     * @param  int $demandStatus demand status
     * @return boolean
     */
    public function validateCommissionTypeDiv($typeDiv, $isCommissionExists, $demandStatus)
    {
        $phoneReady = getDivValue('demand_status', 'telephone_already');
        $infoSent = getDivValue('demand_status', 'information_sent');
        if ($typeDiv == 2 && !$isCommissionExists && ($demandStatus == $phoneReady || $demandStatus == $infoSent)) {
            return false;
        }

        return true;
    }

    /**
     * validate selection systemtype
     * @author  thaihv
     * @param  array $data all data
     * @return boolean
     */
    public function validateSelectSystemType($data)
    {
        $isPassed = $this->validateReBiddingNotSelect($data);
        if (!$isPassed) {
            return $isPassed;
        }

        $isPassed1 = $this->validateDataInconsistency($data);
        $isPassed = $this->checkAuctionSettingGenre($data);

        if (!$isPassed || !$isPassed1) {
            return false;
        }

        if (isset($data['commissionInfo']) && !empty($data['commissionInfo'])) {
            foreach ($data['commissionInfo'] as $commissionData) {
                if (isset($commissionData['corp_claim_flg']) && !empty($commissionData['corp_claim_flg']) && !$commissionData['commit_flg']) {
                    session()->flash('error_msg_input', __('demand.error_miss_input'));
                    session()->flash(
                        'demand_errors.check_selection_system',
                        __('demand.validation_error.check_selection_system')
                    );
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * validate selection systemtype
     * @author  thaihv
     * @param  array $data all data
     * @return boolean
     */
    public function checkErrSendCommissionInfo($data)
    {
        if (isset($data['send_commission_info']) && !empty($data['send_commission_info'])) {
            $commissionFlgCount = 0;
            foreach ($data['commissionInfo'] as $commisionData) {
                if ($commisionData['corp_id'] && isset($commisionData['commit_flg']) && $commisionData['commit_flg']) {
                    $commissionFlgCount = $commissionFlgCount + 1;
                }
            }
            if ($commissionFlgCount == 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @author  thaihv
     * @param array $demandInfo
     * @param string $preferredDate
     * @return array
     */
    public function buildJudeResult(&$demandInfo, $preferredDate)
    {
        if (empty($demandInfo['priority'])) {
            $judgeResult = judgeAuction(
                $demandInfo['auction_start_time'],
                $preferredDate,
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time'],
                $demandInfo['priority']
            );
        } elseif ($demandInfo['priority'] == getDivValue('priority', 'asap')) { // When the priority is urgent
            $judgeResult = judgeAsap(
                $demandInfo['auction_start_time'],
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time']
            );
        } elseif ($demandInfo['priority'] == getDivValue('priority', 'immediately')) { // When the priority is urgent
            $judgeResult = judgeImmediately(
                $demandInfo['auction_start_time'],
                $preferredDate,
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time']
            );
        } else { // When the priority is normal
            $judgeResult = judgeNormal(
                $demandInfo['auction_start_time'],
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time'],
                $demandInfo['priority']
            );
        }

        return $judgeResult;
    }

    /**
     * @author thaihv
     * @param array $demandInfo
     * @param boolean $auctionFlg
     * @param boolean $auctionNoneFlg
     * @return mixed
     */
    public function updateDemandInfoDataByFlg($demandInfo, $auctionFlg, $auctionNoneFlg)
    {
        if ((!$auctionFlg) || (!$auctionNoneFlg)) {
            // Selection method
            $demandInfo['selection_system'] = getDivValue('selection_type', 'manual_selection');
            // Proposal status
            $demandInfo['demand_status'] = getDivValue('demand_status', 'no_selection');
            // Auction start date and time
            $demandInfo['auction_start_time'] = '';
            //Auction start date and time
            $demandInfo['auction_deadline_time'] = '';
            // Empty the auction execution flag so that auction_infos is not generated
            $demandInfo['do_auction'] = '';
        } else {
            // Auction flow case flag
            $demandInfo['auction'] = 0;
            // Auction mail STOP flag
            $demandInfo['push_stop_flg'] = 0;
            // Proposal status
            $demandInfo['demand_status'] = getDivValue('demand_status', 'agency_before');
        }

        return $demandInfo;
    }

    /**
     * @param array $demandInfo
     * @return mixed
     */
    public function updateDemandInfoDataByQuickOrder($demandInfo)
    {
        $demandInfo = $this->setDataForDemandInfo($demandInfo);
        $demandInfo = $this->setAddress2ForDemandInfo($demandInfo);

        $demandInfo['is_contact_time_range_flg'] = 0;
        $demandInfo['contact_desired_time'] = date('Y/m/d H:i');

        switch ($demandInfo['quick_order_fail_reason']) {
            case 2:
                $demandInfo['order_fail_reason'] = null;
                $demandInfo['acceptance_status'] = 3;
                $demandInfo['demand_status'] = 9;
                break;
            case 3:
                $demandInfo['order_fail_reason'] = 35;
                $demandInfo['acceptance_status'] = 3;
                $demandInfo['demand_status'] = 6;
                break;
            case 4:
                $demandInfo['order_fail_reason'] = 37;
                $demandInfo['acceptance_status'] = 3;
                $demandInfo['demand_status'] = 6;
                break;
            case 5:
                $demandInfo['order_fail_reason'] = 36;
                $demandInfo['acceptance_status'] = 3;
                $demandInfo['demand_status'] = 6;
                break;
            case 6:
                $demandInfo['order_fail_reason'] = 38;
                $demandInfo['acceptance_status'] = 1;
                $demandInfo['demand_status'] = 6;
                break;

            default:
                $demandInfo['order_fail_reason'] = 35;
                $demandInfo['acceptance_status'] = 2;
                $demandInfo['demand_status'] = 6;
                break;
        }

        if ($demandInfo['demand_status'] == 6) {
            $demandInfo['order_fail_date'] = date('Y/m/d');
        }
        return $demandInfo;
    }

    /**
     * @param array $arrTranslate
     * @param string $langFile
     * @param bool $defaultOption
     * @return array|\Illuminate\Config\Repository|mixed
     */
    private function translate($arrTranslate, $langFile, $defaultOption = true)
    {
        $langArr = array_map(
            function ($div) use ($langFile) {
                return __($langFile . '.' . $div);
            },
            $arrTranslate
        );

        return !$defaultOption ? $langArr : config('constant.defaultOption') + $langArr;
    }

    /**
     * @param $data
     * @return bool
     */
    private function checkAuctionSettingGenre($data)
    {
        if ($data['demandInfo']['selection_system'] == getDivValue('selection_type', 'auction_selection')
            || $data['demandInfo']['selection_system'] == getDivValue('selection_type', 'automatic_auction_selection')
            || $data['demandInfo']['selection_system'] == getDivValue('selection_type', 'auto_selection')
        ) {
            $auctionGenreData = AuctionGenre::where('genre_id', $data['demandInfo']['genre_id'])->count();
            if (empty($auctionGenreData)) {
                session()->flash(
                    'demand_errors.check_selection_system',
                    __('demand.error_auction_setting_genre')
                );
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function validateReBiddingNotSelect($data)
    {
        $isPassed = true;
        if (($data['demandInfo']['selection_system'] == 2 || $data['demandInfo']['selection_system'] == 3)
            && (
                $data['demandInfo']['selection_system_before'] != 2
                && $data['demandInfo']['selection_system_before'] != 3
                && $data['demandInfo']['selection_system_before'] != ''
            )
        ) {
            if ($data['demandInfo']['demand_status'] != 1 || $data['demandInfo']['do_auction'] != 2) {
                session()->flash('error_msg_input', __('demand.reBiddingNotSelect'));
                $isPassed = false;
            }
        }
        return $isPassed;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function validateDataInconsistency($data)
    {
        $isPassed = true;

        if ($data['demandInfo']['selection_system'] == getDivValue('selection_type', 'auto_selection')
            && !empty($data['demandInfo']['do_auto_selection'])) {
            if ($data['demandInfo']['demand_status'] != 1 && $data['demandInfo']['do_auto_selection_category'] != 1) {
                session()->flash('error_msg_input', __('demand.dataInconsistency'));
                $isPassed = false;
            }
        }

        return $isPassed;
    }

    /**
     * set data DemandInfo
     * @param array $demandInfo
     * @return array
     */
    private function setDataForDemandInfo($demandInfo)
    {
        $demandInfo['site_id'] = !empty($demandInfo['site_id']) ? $demandInfo['site_id'] : 647;
        $demandInfo['genre_id'] = !empty($demandInfo['genre_id']) ? $demandInfo['genre_id'] : 673;
        $demandInfo['category_id'] = !empty($demandInfo['category_id']) ? $demandInfo['category_id'] : 470;
        $demandInfo['customer_name'] = !empty($demandInfo['customer_name']) ? $demandInfo['customer_name'] : '不明';
        $demandInfo['customer_tel'] = (empty($demandInfo['customer_tel']) || $demandInfo['customer_tel'] == '非通知') ?
            '9999999999' : trim($demandInfo['customer_tel']);
        $demandInfo['tel1'] = !empty($demandInfo['tel1']) ? $demandInfo['tel1'] : '9999999999';
        $demandInfo['address1'] = !empty($demandInfo['address1']) ? $demandInfo['address1'] : '99';
        $demandInfo['construction_class'] = !empty($demandInfo['construction_class']) ?
            $demandInfo['construction_class'] : 7;

        return $demandInfo;
    }

    /**
     * @param array $demandInfo
     * @return mixed
     */
    private function setAddress2ForDemandInfo($demandInfo)
    {
        $address2 = '不明';
        if (!empty($demandInfo['address2'])) {
            if (is_numeric($demandInfo['address2'])) {
                $address2 = getDivTextJP('prefecture_div', $demandInfo['address2']);
            } else {
                $address2 = $demandInfo['address2'];
            }
        }
        $demandInfo['address2'] = $address2;

        return $demandInfo;
    }
}
