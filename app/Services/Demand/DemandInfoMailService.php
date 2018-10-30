<?php

namespace App\Services\Demand;

use App\Helpers\MailHelper;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\Eloquent\MItemRepository;
use App\Services\Aws\AwsUtilService;
use Log;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\AccumulatedInformationsRepositoryInterface;

class DemandInfoMailService extends BaseDemandInfoService
{
    /**
     * @var MCorpRepositoryInterface
     */
    public $mCorpRepo;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    public $commissionRepo;
    /**
     * @var DemandInfoRepositoryInterface
     */
    public $demandRepo;
    /**
     * @var AwsUtilService
     */
    public $awsUtilService;
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepo;
    /**
     * @var MUserRepositoryInterface
     */
    public $mUserRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    public $mGenreRepo;

    /**
     * @var AccumulatedInformationsRepositoryInterface
     */
    public $accumulatedInfoRepo;

    /**
     * @var ValidateDemandInfoService
     */
    protected $validateDemandInfoService;

    /**
     * DemandInfoMailService constructor.
     * @param MGenresRepositoryInterface $mGenreRepo
     * @param AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo
     * @param AuctionInfoRepositoryInterface $auctionInfoRepo
     * @param DemandInfoRepositoryInterface $demandRepo
     * @param CommissionInfoRepositoryInterface $commissionRepo
     * @param MCorpRepositoryInterface $mCorpRepo
     * @param MUserRepositoryInterface $mUserRepo
     * @param AwsUtilService $awsUtilService
     * @param ValidateDemandInfoService $validateDemandInfoService
     */
    public function __construct(
        MGenresRepositoryInterface $mGenreRepo,
        AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo,
        AuctionInfoRepositoryInterface $auctionInfoRepo,
        DemandInfoRepositoryInterface $demandRepo,
        CommissionInfoRepositoryInterface $commissionRepo,
        MCorpRepositoryInterface $mCorpRepo,
        MUserRepositoryInterface $mUserRepo,
        AwsUtilService $awsUtilService,
        ValidateDemandInfoService $validateDemandInfoService
    ) {
        $this->auctionInfoRepo = $auctionInfoRepo;
        $this->commissionRepo = $commissionRepo;
        $this->mCorpRepo = $mCorpRepo;
        $this->demandRepo = $demandRepo;
        $this->mUserRepo = $mUserRepo;
        $this->awsUtilService = $awsUtilService;
        $this->validateDemandInfoService = $validateDemandInfoService;
        $this->mGenreRepo = $mGenreRepo;
        $this->accumulatedInfoRepo = $accumulatedInfoRepo;
    }

    /**
     * @param array $corpData
     * @return array
     */
    public function getMailAndFaxByCorpData($corpData)
    {
        Log::debug('___ start get mail and fax _____');
        $mailList = [];
        $faxList = [];
        // Supplier
        foreach ($corpData as $val) {
            if (empty($val['corp_id'])
                || !empty($val['del_flg'])
                || !empty($val['lost_flg'])
                || $val['commit_flg'] != 1
            ) {
                continue;
            }
            /* Specify it as the mail / fax destination only when there is no check in either
            * deletion or pre-order missed order
            * Target affiliated stores with final decision
            */
            $corpInfo = $this->mCorpRepo->getFirstById($val['corp_id']);
            if (!$corpInfo) {
                continue;
            }

            list($mailList, $faxList) = $this->setDataForMailAndFix($corpInfo, $mailList, $faxList);
        }

        return ['mailList' => $mailList, 'faxList' => $faxList];
    }

    /**
     * @param object $corpInfo
     * @param array $mailList
     * @param array $faxList
     * @return array
     */
    private function setDataForMailAndFix($corpInfo, $mailList, $faxList)
    {
        switch ($corpInfo->coordination_method) {
            case getDivValue('coordination_method', 'mail_app'):
                $mailList[] = $corpInfo;
                // Not installing application notification
                break;
            case getDivValue('coordination_method', 'mail_fax_app'):
                $mailList[] = $corpInfo;
                $faxList[] = $corpInfo;
                // Not installing application notification
                break;
            case getDivValue('coordination_method', 'mail_fax'):
                $mailList[] = $corpInfo;
                $faxList[] = $corpInfo;
                break;
            case getDivValue('coordination_method', 'mail'):
                $mailList[] = $corpInfo;
                break;
            case getDivValue('coordination_method', 'fax'):
                $faxList[] = $corpInfo;
                break;
            default:
                break;
        }

        return [$mailList, $faxList];
    }


    /**
     * @param integer $demandId
     * @param integer $corpId
     * @return array|null
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function faxCommission($demandId, $corpId)
    {
        $commissionData = $this->commissionRepo->getWordData($demandId, $corpId);
        if (!empty($commissionData)) {
            $inquiries = $commissionData->demandInfo->inquiries;
            $countIn = $inquiries->count() - 1;
            $inquiryData = '';
            foreach ($inquiries as $key => $inq) {
                $inquiryData .= $inq->mInquiry->inquiry_name.'：'.$inq->answer_note;
                if ($countIn != $key) {
                    $inquiryData .= ', ';
                }
            }

            return $this->makeWordFile($commissionData, $inquiryData);
        }

        return null;
    }


    /**
     * @param array $demandInfo
     * @param array $faxList
     * @param array $mailInfo
     * @return bool
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function sendFax($demandInfo, $faxList, $mailInfo)
    {
        if (empty($mailInfo)) {
            return true;
        }
        if (empty($faxList)) {
            return true;
        }
        Log::debug('___start send fax___');
        // TO address
        $toAddress = env('DEMAND_TO_ADDRESS', getDivText('fax_setting', 'to_address'));
        $subject = getDivText('fax_setting', 'title');
        $result = true;
        foreach ($faxList as $val) {
            if (!empty($val->fax)) {
                $headers = ["Content-Type" => "multipart/mixed; boundary=\"__PHPRECIPE__\"\r\n\r\n"];
                $body = "--__PHPRECIPE__\r\n"."Content-Type: text/plain; charset=\"ISO-2022-JP\"\r\n"."\r\n"."%s\r\n"."--__PHPRECIPE__\r\n";
                // Create and attach an intermediary form
                $fileRes = $this->faxCommission($demandInfo['id'], $val->id);
                $filePath = $fileRes['filePath'];
                // Attachment
                $mailContents = sprintf(getDivText('fax_setting', 'contents'), $val->fax, $val->corp_name);
                $from = env('DEMAND_FROM_ADDRESS', getDivText('mail_setting', 'from_address'));
                try {
                    MailHelper::sendAttachMail($from, $toAddress, $subject, $mailContents, [$filePath]);
                    Log::debug('___end send fax___');
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * @param array $demandInfo
     * @param array $mailList
     * @param object $mailInfo
     * @return bool
     */
    public function sendMail($demandInfo, $mailList, $mailInfo)
    {
        if (empty($mailInfo)) {
            return true;
        }
        if (empty($mailList)) {
            return true;
        }
        Log::debug('___start send mail___');
        $demandFileInfo = $mailInfo->demandAttachedFiles && $mailInfo->demandAttachedFiles->first() ? '添付資料あり（取次管理から確認して下さい。）' : '';
        if ($mailInfo->mSite->jbr_flg == 1 && $demandInfo['jbr_work_contents'] == config('datacustom.jbr_glass_category')) {
            $from = getDivText('mail_setting', 'from_address');
            $subject = sprintf(getDivText('jbr_glass_mail_setting', 'title'), $demandInfo['id']);
            $body = sprintf(getDivText('jbr_glass_mail_setting', 'contents'), $demandInfo['jbr_order_no'], route('login'), $demandInfo['id'], $mailInfo->mSite->site_name, $mailInfo->mCategory->category_name, $mailInfo->customer_name, "〒".$mailInfo->postcode, getDivTextJP('prefecture_div', $mailInfo->address1).$mailInfo->address2.$mailInfo->address3, getDropText('建物種別', $mailInfo->construction_class), $mailInfo->tel1, $mailInfo->tel2, $mailInfo->customer_mailaddress, $mailInfo->contents, $demandFileInfo);
        } elseif ($mailInfo->mSite->jbr_flg == 1) {
            $from = getDivText('mail_setting', 'from_address');
            $subject = sprintf(getDivText('jbr_mail_setting', 'title'), $demandInfo['id']);
            $body = sprintf(getDivText('jbr_glass_mail_setting', 'contents'), $demandInfo['jbr_order_no'], route('login'), $demandInfo['id'], $mailInfo->mSite->site_name, $mailInfo->mCategory->category_name, $mailInfo->customer_name, "〒".$mailInfo->postcode, getDivTextJP('prefecture_div', $mailInfo->address1).$mailInfo->address2.$mailInfo->address3, getDropText('建物種別', $mailInfo->construction_class), $mailInfo->tel1, $mailInfo->tel2, $mailInfo->customer_mailaddress, $mailInfo->contents, $demandFileInfo);
        } elseif ($mailInfo->commissionInfoMail->first()->commission_type == 1) {
            $from = getDivText('package_estimate_mail_setting', 'from_address');
            $subject = sprintf(getDivText('package_estimate_mail_setting', 'title'), $demandInfo['id']);
            $body = sprintf(getDivText('package_estimate_mail_setting', 'contents'), $demandInfo['id'], $mailInfo->receive_datetime, $mailInfo->mUser->user_name, $mailInfo->mSite->site_name, $mailInfo->mSite->site_url, $mailInfo->mSite->note, $mailInfo->mCategory->category_name, $mailInfo->customer_name, "〒".$mailInfo->postcode, getDivTextJP('prefecture_div', $mailInfo->address1).$mailInfo->address2.$mailInfo->address3, getDropText('建物種別', $mailInfo->construction_class), $mailInfo->tel1, $mailInfo->tel2, $mailInfo->customer_mailaddress, $mailInfo->mSite->site_name, $mailInfo->contents, $demandFileInfo, route('commission.detail', ['id' => $mailInfo->commissionInfoMail->first()->id]));
        } else {
            $from = getDivText('normal_commission_mail_setting', 'from_address');
            $subject = sprintf(getDivText('normal_commission_mail_setting', 'title'), $demandInfo['id']);
            $body = sprintf(getDivText('normal_commission_mail_setting', 'contents'), $demandInfo['id'], $mailInfo->receive_datetime, $mailInfo->mUser->user_name, $mailInfo->mSite->site_name, $mailInfo->mSite->site_url, $mailInfo->mSite->note, $mailInfo->mCategory->category_name, $mailInfo->customer_name, "〒".$mailInfo->postcode, getDivTextJP('prefecture_div', $mailInfo->address1).$mailInfo->address2.$mailInfo->address3, getDropText('建物種別', $mailInfo->construction_class), $mailInfo->customer_corp_name, $mailInfo->tel1, $mailInfo->tel2, $mailInfo->customer_mailaddress, $mailInfo->mSite->site_name, $mailInfo->contents, $demandFileInfo, route('commission.detail', ['id' => $mailInfo->commissionInfoMail->first()->id]));
        }
        $bcc = env('DEMAND_BCC', getDivText('bcc_mail', 'to_address'));
        if (env('DEMAND_FROM_ADDRESS')) {
            $from = env('DEMAND_FROM_ADDRESS');
        }
        $result = true;

        $result = $this->sendMailList($mailList, $body, $subject, $from, $bcc, $result);

        return $result;
    }

    /**
     * @param array $mailList
     * @param string $body
     * @param string $subject
     * @param string $from
     * @param string $bcc
     * @param boolean $result
     * @return bool
     */
    private function sendMailList($mailList, $body, $subject, $from, $bcc, $result)
    {
        foreach ($mailList as $val) {
            $toAddressList = [];
            if (!empty($val->mailaddress_pc)) {
                $toAddressList = explode(';', $val->mailaddress_pc);
            }
            if (!empty($val->mailaddress_mobile)) {
                $toAddressListM = explode(';', $val->mailaddress_mobile);
                $toAddressList = array_merge($toAddressList, $toAddressListM);
            }
            foreach ($toAddressList as $toAddress) {
                if (env('DEMAND_TO_ADDRESS')) {
                    $toAddress = env('DEMAND_TO_ADDRESS');
                }
                try {
                    MailHelper::sendRawMail($body, $subject, $from, trim($toAddress), $bcc);
                    Log::debug('___end send mail____');
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * Get list data demand and send it to corp
     * Use in DemandGuideSendMail
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     */
    public function executeDemandGuideSendMail()
    {
        $selectionSystem = [
            getDivValue("selection_type", "auction_selection"),
            getDivValue("selection_type", "automatic_auction_selection"),
        ];
        $list = $this->demandRepo->getForDemandGuideSendMail($selectionSystem);
        foreach ($list as $row) {
            $data = [];
            $data["corpId"] = $row->m_corp_id;
            $data["corpName"] = $row->official_corp_name;
            $data["auctionLink"] = route("auction.index");
            $toAddressList = explode(';', $row->mailaddress_auction);
            $data["prefecture"] = getDivTextJP("prefecture_div", $row->address1);
            $data["demandInfoId"] = $row->id;
            $data["siteName"] = $this->validateDemandInfoService->mSiteRepo->getListText($row->site_id);
            $data["genreName"] = $this->mGenreRepo->getListText($row->genre_id);
            $data["demandInfoCustomer"] = $row->customer_name;
            $address1 = getDivTextJP('prefecture_div', $row->address1);
            $address2 = $row->address2;
            $address3 = maskingAddress3($row->address3);
            $data["address"] = $address1.$address2.$address3;
            $data["constructionClass"] = getDropText(MItemRepository::BUILDING_TYPE, $row->construction_class);
            $data["tel1"] = __('console.tel1');
            $data["tel2"] = __('console.tel2');
            $data["customerMailAddress"] = __('console.customer_mail_address');
            $data["demandInfoContent"] = $row->contents;
            $data["auctionDeadlineTime"] = dateTimeWeekJP($row->auction_deadline_time);
            $data["image"] = route("accumulated.mailOpen", ["demandId" => $row->id, "corpId" => $row->m_corp_id]);
            $subject = sprintf(getDivText('auction_mail_setting', 'title'), $data["genreName"], $address1, $row->id);

            $this->sendMailWithAddresses($toAddressList, $data, $subject);
            \Log::debug('__PUSH_NSN_CORP_ID: ' . $row->m_corp_id . '__DEMAND_ID: ' . $row->id);
            $this->pushMessages($row);

            $this->auctionInfoRepo->updateOrCreate($row->auction_info_id, [
                "push_flg" => 1,
                "modified" => date('Y-m-d H:i'),
                "modified_user_id" => self::USER,
            ]);

            $this->accumulatedInfoRepo->updateOrCreate(null, [
                "demand_id" => $row->id,
                "corp_id" => $row->m_corp_id,
                "demand_regist_date" => $row->created,
                "mail_send_date" => date('Y-m-d H:i'),
                "created_user_id" => self::USER,
                "modified_user_id" => self::USER,
                'created' => date('Y-m-d H:i'),
                'modified' => date('Y-m-d H:i')
            ]);
        }
    }

    /**
     * @param object $row
     */
    private function pushMessages($row)
    {
        if ($row->coordination_method == 6 || $row->coordination_method == 7) {
            $users = $this->mUserRepo->getUserByAffiliationId($row->m_corp_id);
            $pushMessage = trans("demand_guide.push_message");
            $extendData = ['url_redirect' => route('auction.index') . '#index_' . $row->id];

            foreach ($users as $user) {
                try {
                    $this->awsUtilService->publish($user->user_id, $pushMessage, $extendData);
                } catch (\Exception $exception) {
                    Log::error($exception);
                }
            }
        }
    }

    /**
     * @param array $addresses
     * @param array $data
     * @param string $subject
     */
    private function sendMailWithAddresses($addresses, $data, $subject)
    {
        $bcc = getDivText('bcc_mail', 'to_address');
        $from = getDivText('auction_mail_setting', 'from_address');
        $headers = [
            "Content-Type" => "text/html; charset=UTF-8",
            "Content_Language" => "ja",
        ];

        foreach ($addresses as $toAddress) {
            if (!empty($toAddress)) {
                try {
                    MailHelper::sendMailTemplate("demand_guide.email", ["data" => $data], $from, $toAddress, $subject, $bcc, $headers);
                } catch (\Exception $ex) {
                    Log::info($ex->getMessage());
                }
            }
        }
    }

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getMailData($demandId)
    {
        return $this->demandRepo->getMailData($demandId);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function checkModifiedDemand($data)
    {
        // Only when updating (only when holding the item ID in the input data) Check
        if (isset($data['id']) && !empty($data['id'])) {
            //Retrieve current matter information record
            $currentData = $this->demandRepo->findById($data['id']);
            //Check the update date and time
            if ($data['modified'] != $currentData['DemandInfo']['modified'] && isset($currentData['DemandInfo'])) {
                session()->flash('demand_errors.check_modified_demand', __('demand.modifiedNotCheck'));
                return false;
            }
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function executeCheckFollowDate()
    {
        $data = ['follow_date' => null];

        return $this->demandRepo->updateExecuteFollowDate($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateDemand($data)
    {
        // Register deal information
        $saveData = $data;
        if (empty($saveData['id'])) {
            $saveData['created_user_id'] = auth()->user()->user_id;
        }
        $saveData['modified_user_id'] = auth()->user()->user_id;
        // If you change the status of the case while it is in progress
        if ($saveData['demand_status'] == 4 || $saveData['demand_status'] == 5
            && $saveData['do_auto_selection_category'] == 0 && !empty($saveData['id'])
        ) {
            if ($limitoverTime = $this->demandRepo->getLimitoverTime($saveData['id'])) {
                $saveData['commission_limitover_time'] = $limitoverTime;
            }
        }
        if (($saveData['selection_system'] == getDivValue('selection_type', 'automatic_auction_selection')
                && $data['selection_system_before'] === '')
            || !empty($data['do_auto_selection'])
        ) {
            $saveData['modified_user_id'] = 'AutomaticAuction';
            if (empty($saveData['id'])) {
                $saveData['created_user_id'] = 'AutomaticAuction';
            }
        }
        //It indicates that you designated a member shop by area × category
        $saveData = $this->setAutoSelectionCategory($data['do_auto_selection_category'], $saveData);

        Log::debug('___Start insert demand_infos___');
        $newDemandData = $this->demandRepo->updateOrCreate($saveData);
        Log::debug('___End insert demand_infos___');
        return array_merge($saveData, $newDemandData);
    }

    /**
     * @param boolean $autoSelectionCategory
     * @param array $saveData
     * @return mixed
     */
    private function setAutoSelectionCategory($autoSelectionCategory, $saveData)
    {
        if ($autoSelectionCategory == 1) {
            $saveData['modified_user_id'] = 'AutoCommissionCorp';
            if (empty($saveData['id'])) {
                $saveData['created_user_id'] = 'AutoCommissionCorp';
            }
        }

        return $saveData;
    }

    /**
     * @param integer $selectionSystem
     * @param array $demandInfo
     * @param integer $autoComSelectionLimitCount
     * @param array $data
     * @return array
     */
    public function buildRestoreAtError($selectionSystem, $demandInfo, $autoComSelectionLimitCount, $data)
    {
        $restoreAtError = [];
        $restoreAtError['demandInfo']['do_auto_selection_category'] = $demandInfo['do_auto_selection_category'];
        $restoreAtError['commissionInfo'] = $data['commissionInfo'];

        if ($autoComSelectionLimitCount > 0) {
            //Since there is an automatic destination, flag the mail transmission
            $restoreAtError['send_commission_info'] = $data['send_commission_info'];
            //In order to conduct automatic transactions, update the status of cases
            $restoreAtError['demandInfo']['demand_status'] = $demandInfo['demand_status'];
        }

        if ($demandInfo['selection_system'] == $selectionSystem) {
            $restoreAtError['demandInfo']['selection_system'] = $demandInfo['selection_system'];
        }

        return $restoreAtError;
    }


    /**
     * @param string $informationSent
     * @param string $agencyBefore
     * @param string $manualSelection
     * @param string $selectionSystem
     * @param array $demandInfo
     * @param integer $autoSelectionLimitCount
     * @return mixed
     */
    public function updateDemandInfoDataByCorp(
        $informationSent,
        $agencyBefore,
        $manualSelection,
        $selectionSystem,
        $demandInfo,
        $autoSelectionLimitCount
    ) {
        //Store items to be restored when an error occurs

        if ($autoSelectionLimitCount > 0) {
            //Since there is an automatic destination, flag the mail transmission
            //In order to conduct automatic transactions, update the status of cases
            $demandInfo['demand_status'] = $informationSent;
        } else {
            $demandInfo['demand_status'] = $agencyBefore;
        }

        $demandInfo['do_auto_selection_category'] = 1;

        if ($demandInfo['selection_system'] == $selectionSystem) {
            $demandInfo['selection_system'] = $manualSelection;
        }

        return $demandInfo;
    }

    /**
     * @param array $data
     * @return array
     */
    public function makeCommissionInfoData($data)
    {
        $arrayFill = array_fill(
            0,
            30,
            [
                "del_flg" => "0",
                "appointers" => '',
                "first_commission" => 0,
                "commission_note_sender" => '',
                "unit_price_calc_exclude" => "0",
                "commission_note_send_datetime" => '',
                "commit_flg" => 0,
                "lost_flg" => "0",
                "complete_date" => '',
                "commission_status" => '',
                "corp_claim_flg" => "0",
                "introduction_not" => "0",
                "send_mail_fax_datetime" => '',
                "commission_type" => '',
                "corp_id" => "",
                "id" => '',
                "select_commission_unit_price" => '',
                "select_commission_unit_price_rank" => '',
                "send_mail_fax_sender" => '',
                "send_mail_fax_othersend" => '',
                "order_fee_unit" => '',
                "send_mail_fax" => '',
                'demand_id' => 0,
                'position' => 0,
                'mCorp' => [
                    "corp_name" => "",
                    "fax" => "",
                    "mailaddress_pc" => "",
                    "coordination_method" => "",
                    "contactable_time" => "",
                    "holiday" => "日",
                    "commission_dial" => "",
                ],
                'mCorpNewYear' => [
                    "label_01" => "",
                    "label_02" => "",
                    "label_03" => "",
                    "label_04" => "",
                    "label_05" => "",
                    "label_06" => "",
                    "status_01" => "",
                    "status_02" => "",
                    "status_03" => "",
                    "status_04" => "",
                    "status_05" => "",
                    "status_06" => "",
                    "note" => null,
                ],
                'mCorpCategory' => [
                    "order_fee" => "",
                    "order_fee_unit" => "",
                    "note" => '',
                ],
                'affiliationInfo' => [
                    'attention' => '',
                ],
            ]
        );
        $data = array_replace($arrayFill, $data);
        return $data;
    }
}
