<?php

namespace App\Services\Credit;

use App\Helpers\MailHelper;
use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Services\CommissionInfoService;

class CreditService
{
    const CREDIT_NORMAL = 'normal';
    const CREDIT_WARNING = 'warning';
    const CREDIT_DANGER = 'danger';
    const MAIL_DANGER_TEMPLATE = 'email_template.credit_danger';
    const MAIL_WARNING_TEMPLATE = 'email_template.credit_warning';
    const BCC_CREDIT_MAIL_TO = 'yoshin@sharing-tech.jp';

    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoService;

    /**
     * @var AffiliationInfoRepositoryInterface
     */
    protected $affiliationRepository;

    /**
     * CreditService constructor.
     *
     * @param CommissionInfoService  $commissionInfoService
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     */
    public function __construct(
        AffiliationInfoRepositoryInterface $affiliationInfoRepository,
        CommissionInfoService $commissionInfoService
    )
    {
        $this->commissionInfoService = $commissionInfoService;
        $this->affiliationRepository = $affiliationInfoRepository;
    }

    /**
     * @var array
     */
    public static $exceptCorpId = [1751, 1755, 3539];

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @param bool $displayPrice
     * @param bool $mailFlg
     * @return int|string
     */
    public function checkCredit($corpId = null, $genreId = null, $displayPrice = false, $mailFlg = false)
    {
        if (in_array($corpId, self::$exceptCorpId)) {
            return $displayPrice ? 0 : self::CREDIT_NORMAL;
        }
        $priceResult = $this->commissionInfoService->checkCreditSumPrice($corpId, $genreId, $displayPrice);

        if ($mailFlg && in_array($priceResult, [self::CREDIT_DANGER, self::CREDIT_WARNING])) {
            $price = $this->commissionInfoService->checkCreditSumPrice($corpId, null, true);
            $this->sendMailToAll($corpId, $priceResult, $price);
        }
        return $priceResult;
    }

    /**
     * @param integer $corpId
     * @param boolean $creditFlg
     * @param float $price
     */
    public function sendMailToAll($corpId = null, $creditFlg = null, $price = null)
    {
        $affiliation = $this->affiliationRepository->findAffiliationInfoByCorpId($corpId);
        $sendFlg = false;
        if (!$affiliation['credit_limit']) {
            return;
        }
        $mailData = [
            'id' => $affiliation['id'],
            'from' => env('CREDIT_INFO_MAIL_FROM', 'mailback@rits-c.jp'),
            'name' => 'シェアリングテクノロジー株式会社',
            'corp_name' => $affiliation['m_corp']['official_corp_name'],
            'virtual_account' => $affiliation['virtual_account']
        ];
        if ($creditFlg == self::CREDIT_WARNING && $affiliation['credit_mail_send_flg'] == 0) {
            $sendFlg = true;
            $mailData = array_merge(
                $mailData,
                [
                'subject' => '【重要】与信限度額残高のお知らせ《' . $affiliation['m_corp']['id'] . '》',
                'price' => $affiliation['credit_limit'] + $affiliation['add_month_credit'] - $price,
                'template' => self::MAIL_WARNING_TEMPLATE,
                'credit_mail_send_flg' => 1
                ]
            );
        }
        if ($creditFlg == self::CREDIT_DANGER && $affiliation['credit_mail_send_flg'] != 2) {
            $sendFlg = true;
            $mailData = array_merge(
                $mailData,
                [
                'subject' => '【重要】お取引が与信限度額残高に達している可能性がございます《' . $affiliation['m_corp']['id'] . '》',
                'price' => $affiliation['credit_limit'] + $affiliation['add_month_credit'] - $price,
                'template' => self::MAIL_DANGER_TEMPLATE,
                'credit_mail_send_flg' => 2
                ]
            );
        }

        if (!$sendFlg) {
            return false;
        }

        $emailsForPC = explode(';', $affiliation['m_corp']['mailaddress_pc']);
        $emailForMobile = explode(';', $affiliation['m_corp']['mailaddress_mobile']);

        \Log::debug('CREDIT_DANGER------------- START SEND EMAIL -----------------');

        $this->sendMail($emailsForPC, $mailData);
        $this->sendMail($emailForMobile, $mailData, 'Mobile');

        \Log::debug('CREDIT_DANGER------------- END SEND EMAIL -----------------');

        \Log::debug('CREDIT_DANGER------------- START SEND EMAIL TO ST SYSTEM -----------------');
        $mailData['to'] = env('CREDIT_BCC', self::BCC_CREDIT_MAIL_TO);
        MailHelper::sendCreditMail($mailData);

        \Log::debug('CREDIT_DANGER------------- END SEND EMAIL TO ST SYSTEM -----------------');

        $this->affiliationRepository->updateById(
            $affiliation['id'],
            [
            'credit_mail_send_flg' => $mailData['credit_mail_send_flg']
            ]
        );

        return true;
    }

    /**
     * @param array $emails
     * @param array $mailData
     * @param string $type
     */
    private function sendMail($emails, $mailData, $type = 'PC')
    {
        if (empty($emails)) {
            return;
        }
        foreach ($emails as $email) {
            $mailData['to'] = trim(env('CREDIT_AFF', $email));
            if (empty($mailData['to'])) {
                continue;
            }
            try {
                MailHelper::sendCreditMail($mailData);
                if (!MailHelper::failures()) {
                    continue;
                }
                $this->logging($type, $mailData);
            } catch (\Exception $ex) {
                \Log::debug('CREDIT_MAIL_ERROR - Email: ' . $mailData['to']);
                \Log::error($ex->getMessage());
            }
        }
        return;
    }

    /**
     * @param string $type
     * @param array $data
     * @return bool
     */
    public function logging($type, $data)
    {
        return \Log::error(
            'MailSend FAILURE: AffiliationInfo_Id: '.$data['id'].
            ' ' . $type .'Address: '.$data['to'].' Template: '.$data['template']
        );
    }
}
