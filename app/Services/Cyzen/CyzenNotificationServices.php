<?php

namespace App\Services\Cyzen;

use App\Helpers\MailHelper;
use App\Mail\CyzenMailCallCenter;
use App\Mail\CyzenMailHeadQuarter;
use App\Repositories\CyzenDemandInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\DemandNotificationRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\Aws\AwsUtilService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenNotificationServices extends BaseCyzenServices
{
    const STATUS_WAIT_FOR_CREATE_CYZEN_SPOT = -3; #use to prevent duplicate create spot on cyzen
    const STATUS_DEMAND_TEMP_REGISTER = -2;
    const STATUS_DEMAND_REGISTER = -1;
    const STATUS_ON_DEMAND = 0;
    const STATUS_DELAYED_CALL_TIME = 1;
    const STATUS_CALLED = 2;
    const STATUS_START_TIME = 3;
    const STATUS_DELAY_START_TIME = 4;
    const STATUS_STARTED_WORK = 5;
    const STATUS_END_TIME = 6;
    const STATUS_DELAY_END_TIME = 7;
    const STATUS_END_WORK = 8;
    const STATUS_BEFORE_START_WORK = 9;
    const STATUS_AFTER_START_WORK = 10;
    const STATUS_RETURN_MOBILE_CALL_TIME = 11; #only use for return mobile
    const STATUS_DELETE_SCHEDULE_FROM_CYZEN = 12; #use mark schedule deleted from cyzen
    const STATUS_DELETE_SCHEDULE_FROM_SP = 13;
    const LOG_PATH_NOTIFICATION = "logs/cyzen/notification";
    const LOG_PATH_NOTIFICATION_CALL_TIME = 'logs/cyzen/notif_call_time';
    const LOG_PATH_NOTIFICATION_START_TIME = 'logs/cyzen/notif_start_time';
    const LOG_PATH_NOTIFICATION_END_TIME = 'logs/cyzen/notif_end_time';
    const LOG_PATH_NOTIFICATION_CREATE_DEMAND = "logs/cyzen/notif_create_demand";
    /** @var float|int */
    public $timeCheckDelay = 15 * 60;
    /** @var float|int */
    public $timeCheckBeforeAndAfter = 10 * 60;
    /** @var DemandNotificationRepositoryInterface $demandNotification */
    protected $demandNotification;
    /** @var DemandInfoRepositoryInterface $demandInfos */
    protected $demandInfos;
    /** @var MCorpRepositoryInterface $corpRepos */
    public $corpRepos;
    /** @var CyzenDemandInfoRepositoryInterface $cyzenDemandInfoRepo */
    protected $cyzenDemandInfoRepo;
    /** @var AwsUtilService $awsService */
    protected $awsService;

    /** @var string */
    private $mailFrom = '';

    /**
     * CyzenNotificationServices constructor.
     * @param DemandNotificationRepositoryInterface $demandNotificationInterface
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param CyzenDemandInfoRepositoryInterface $cyzenDemandInfoInterface
     * @param AwsUtilService $awsUtilService
     * @throws \Exception
     */
    public function __construct(
        DemandNotificationRepositoryInterface $demandNotificationInterface,
        MCorpRepositoryInterface $mCorpRepository,
        CyzenDemandInfoRepositoryInterface $cyzenDemandInfoInterface,
        AwsUtilService $awsUtilService
    ) {
        parent::__construct();
        $this->demandNotification = $demandNotificationInterface;
        $this->corpRepos = $mCorpRepository;
        $this->cyzenDemandInfoRepo = $cyzenDemandInfoInterface;
        $this->awsService = $awsUtilService;
        $this->mailFrom = env('ST_MAIL_FROM', 'mailback@rits-c.jp');
        $this->logger = new Logger('Cyzen');
        $this->logger->pushHandler(
            new StreamHandler(
                createLogPathCyzen(CyzenNotificationServices::LOG_PATH_NOTIFICATION),
                Logger::INFO
            )
        );
    }

    /**
     * @param $statusDemand
     * @return mixed
     */
    public function getListStaff($statusDemand)
    {
        $listStaff = $this->demandNotification->getListStaffByStatus($statusDemand);
        return $listStaff;
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $message
     * @param $status
     */
    public function executeSendPush($demandId, $userId, $message, $status)
    {
        $extendData = $this->createExtendPushNotification($demandId, $status);
        try {
            $this->awsService->publish($userId, $message, $extendData);
        } catch (\Exception $exception) {
            $this->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * @param $demandId
     * @param int $statusId
     * @return array
     */
    private function createExtendPushNotification($demandId, $statusId)
    {
        $message = [
            'demandId' => $demandId,
            'type' => $statusId
        ];
        return $message;
    }

    /**
     * @param $demandId
     * @param $staffId
     * @param $status
     * @return mixed
     */
    public function updateStatusDemandNotification($demandId, $staffId, $status)
    {
        $query = $this->demandNotification->updateStatusByStaffAndDemand($demandId, $staffId, $status);
        return $query;
    }

    /**
     * @param $data
     * @param $status
     * @param bool $sendCC
     */
    public function executeSendMail($data, $status, $sendCC = true)
    {
        try {
            $result = $this->getInfoDemandForMail($data['demand_id'], $data['user_id']);
            $listMailHQ = $this->getAddressMailAffiliation($data['user_id']);
            if (count($listMailHQ) > 0) {
                $title = $this->getMailSubject($status, $data['demand_id']);
                $result[0]['message'] = $title;
                $dataMail = [
                    'from' => $this->mailFrom,
                    'subject' => $title,
                    'content' => $result[0]
                ];
                $dataMail['template'] = $this->createTemplateByStatus($status);
                foreach ($listMailHQ as $mailHQ) {
                    if (isset($mailHQ) && strlen(trim($mailHQ)) > 0) {
                        MailHelper::sendMail($mailHQ, new CyzenMailHeadQuarter($dataMail));
                    }
                }
            }
            if ($sendCC) {
                $mailCC = env('MAIL_CALL_CENTER');
                if (strlen(trim($mailCC)) > 0) {
                    $title = $this->getMailSubject($status, $data['demand_id']);
                    $result[0]['message'] = $title;
                    $dataMail = [
                        'from' => $this->mailFrom,
                        'subject' => $title,
                        'content' => $result[0]
                    ];
                    $dataMail['template'] = $this->createTemplateByStatus($status);
                    MailHelper::sendMail($mailCC, new CyzenMailCallCenter($dataMail));
                }
            }
        } catch (\Exception $exception) {
            $this->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * @param $demandId
     * @param $userId
     * @return mixed
     */
    public function getInfoDemandForMail($demandId, $userId)
    {
        $result = $this->demandNotification->getDetailForMailContent($demandId, $userId);
        if (strpos(trim($result[0]['site_name']), 'http') !== 0) {
            $result[0]['site_name'] = 'http://' . $result[0]['site_name'];
        }
        $result[0]['message'] = '';
        if (!empty($result)) {
            $result[0]['schedule'] = $result[0]['draft_start_time'] . ' - ' . $result[0]['draft_end_time'];
            $result[0]['location'] = trim(getDivTextJP('prefecture_div', $result[0]['address1']) .
                ' ' . $result[0]['address2'] . ' ' . $result[0]['address3']);
            $result[0]['work_status'] = $this->cyzenDemandInfoRepo->getStatusName($result[0]['work_status']);
        }
        return $result;
    }

    /**
     * @param $staffId
     * @return mixed
     */
    public function getAddressMailAffiliation($staffId)
    {
        if (!empty($staffId)) {
            $result = $this->corpRepos->getMailByUserId($staffId);
            if (strlen(trim($result[0]['mailaddress_pc'])) > 0) {
                $listMail = preg_split('/;/i', $result[0]['mailaddress_pc'], -1, PREG_SPLIT_NO_EMPTY);
                if (count($listMail) > 0) {
                    return $listMail;
                }
            }
        }
        return [];
    }

    /**
     * @param $statusDemand
     * @param $demandId
     * @return string
     */
    public function getMailSubject($statusDemand, $demandId)
    {
        $mapping = [
            CyzenNotificationServices::STATUS_ON_DEMAND =>
                __('cyzen_notifications.message_mail_on_demand', ['id' => $demandId]),
            CyzenNotificationServices::STATUS_CALLED =>
                __('cyzen_notifications.message_mail_on_call', ['id' => $demandId]),
            CyzenNotificationServices::STATUS_DELAYED_CALL_TIME =>
                __('cyzen_notifications.message_mail_delay_call', ['id' => $demandId]),
            CyzenNotificationServices::STATUS_STARTED_WORK =>
                __('cyzen_notifications.message_mail_start_work', ['id' => $demandId]),
            CyzenNotificationServices::STATUS_DELAY_START_TIME =>
                __('cyzen_notifications.message_mail_delay_start_work', ['id' => $demandId]),
            CyzenNotificationServices::STATUS_END_WORK =>
                __('cyzen_notifications.message_mail_on_end_work', ['id' => $demandId]),
            CyzenNotificationServices::STATUS_DELAY_END_TIME =>
                __('cyzen_notifications.message_mail_delay_end_work', ['id' => $demandId])
        ];
        return $mapping[$statusDemand];
    }

    /**
     * @param $status
     * @return string
     */
    private function createTemplateByStatus($status)
    {
        $mapping = [
            CyzenNotificationServices::STATUS_ON_DEMAND => 'email_template.cyzen_mail_create_demand',
            CyzenNotificationServices::STATUS_DELAYED_CALL_TIME => 'email_template.cyzen_mail_delayed_call',
            CyzenNotificationServices::STATUS_CALLED => 'email_template.cyzen_mail_called',
            CyzenNotificationServices::STATUS_DELAY_START_TIME => 'email_template.cyzen_mail_delayed_start_work',
            CyzenNotificationServices::STATUS_STARTED_WORK => 'email_template.cyzen_mail_started_work',
            CyzenNotificationServices::STATUS_DELAY_END_TIME => 'email_template.cyzen_mail_delayed_end_work',
            CyzenNotificationServices::STATUS_END_WORK => 'email_template.cyzen_mail_end_work'
        ];
        return $mapping[$status];
    }

    /**
     * @param $userId
     * @return string
     */
    public function getGroupIdByUser($userId)
    {
        return $this->demandNotification->getGroupByStaff($userId);
    }
}
