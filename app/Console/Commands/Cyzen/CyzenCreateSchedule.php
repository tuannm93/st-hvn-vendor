<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenNotificationServices;
use App\Services\Cyzen\CyzenScheduleService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenCreateSchedule extends Command
{
    /** @var \App\Services\Cyzen\CyzenScheduleService $service */
    protected $scheduleService;

    /** @var CyzenNotificationServices $notificationService */
    protected $notificationService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_create_schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create schedule ';

    /**
     * CyzenSchedule constructor.
     * @param CyzenScheduleService $cyzenScheduleServices
     * @param CyzenNotificationServices $cyzenNotificationServices
     * @throws \Exception
     */
    public function __construct(
        CyzenScheduleService $cyzenScheduleServices,
        CyzenNotificationServices $cyzenNotificationServices
    ) {
        parent::__construct();
        $this->scheduleService = $cyzenScheduleServices;
        $this->notificationService = $cyzenNotificationServices;
        $this->notificationService->logger = new Logger('Cyzen');
        $this->notificationService->logger->pushHandler(
            new StreamHandler(
                createLogPathCyzen(CyzenNotificationServices::LOG_PATH_NOTIFICATION_CREATE_DEMAND),
                Logger::INFO
            )
        );
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $this->notificationService->logger->log(Logger::INFO, "==========START CREATE SCHEDULE==========");
        $listStaff = $this->notificationService->getListStaff(CyzenNotificationServices::STATUS_DEMAND_REGISTER);
        if (!empty($listStaff)) {
            foreach ($listStaff as $arr) {
                if (!empty($arr['spot_id']) && !empty($arr['group_id']) && !empty($arr['cyzen_user_id'])) {
                    $this->notificationService->logger->log(Logger::INFO, __LINE__ . ' >>> ' . json_encode($arr));
                    $this->executeCreateSchedule(
                        $arr['demand_id'],
                        $arr['user_id'],
                        $arr['spot_id'],
                        $arr['group_id'],
                        $arr['cyzen_user_id'],
                        $arr['draft_start_time'],
                        $arr['draft_end_time']
                    );
                } else {
                    $this->notificationService->logger->log(Logger::INFO, __LINE__ . ' >>> ' . json_encode($arr));
                }
            }
        } else {
            $this->notificationService->logger->log(Logger::INFO, "NO STAFF TO REGISTER");
        }
        $this->notificationService->logger->log(Logger::INFO, "==========END CREATE SCHEDULE==========");
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $spotId
     * @param $groupId
     * @param $cyzenUserId
     * @param $draftStart
     * @param $draftEnd
     */
    private function executeCreateSchedule($demandId, $userId, $spotId, $groupId, $cyzenUserId, $draftStart, $draftEnd)
    {
        $nameGenreCategory = $this->scheduleService->getGenreAndCategoryNameFromDemandId($demandId);
        $array = [
            'group_id' => $groupId,
            'spot_id' => $spotId,
            'members' => $cyzenUserId,
            'start_date' => gmdate('Y-m-d\TH:i:s', strtotime($draftStart)),
            'end_date' => gmdate('Y-m-d\TH:i:s', strtotime($draftEnd)),
            'title' => $demandId . '-' . $userId,
            'detail' => mb_substr(
                trim('◇' . trans('demand_detail.genre') . '：' . $nameGenreCategory[0]['genre_name'] .
                    "\r\n◇" . trans('demand_detail.category') . ': ' . $nameGenreCategory[0]['category_name'] .
                    "\r\n◇" . trans('demand_detail.content_transfer') . "\r\n" . $nameGenreCategory[0]['contents']),
                0,
                500
            )
        ];
        try {
            $result = $this->scheduleService->createSchedule($array);
            $this->notificationService->logger->log(Logger::INFO, $demandId . '---' . $userId . '---' . $result);
            if (!empty($result)) {
                $this->sendMail($demandId, $userId);
                $this->sendPushNotification($demandId, $userId);
            }
        } catch (GuzzleException $ex) {
            $this->notificationService->logger->log(Logger::CRITICAL, $ex->getMessage());
        }
    }

    /**
     * @param $demandId
     * @param $userId
     */
    private function sendMail($demandId, $userId)
    {
        $this->notificationService->logger->log(
            Logger::INFO,
            "EXECUTED SEND MAIL " . $demandId . '---' . $userId
        );
        $array = [
            'demand_id' => $demandId,
            'user_id' => $userId
        ];
        try {
            $this->notificationService->updateStatusDemandNotification(
                $demandId,
                $userId,
                CyzenNotificationServices::STATUS_ON_DEMAND
            );
            $this->notificationService->executeSendMail(
                $array,
                CyzenNotificationServices::STATUS_ON_DEMAND
            );
        } catch (\Exception $exception) {
            $this->notificationService->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * @param $demandId
     * @param $userId
     */
    private function sendPushNotification($demandId, $userId)
    {
        $this->notificationService->logger->log(
            Logger::INFO,
            "EXECUTED SEND PUSH " . $demandId . '---' . $userId
        );
        $pushMessage = __('cyzen_notifications.message_notification_on_register');
        $this->notificationService->executeSendPush(
            $demandId,
            $userId,
            $pushMessage,
            CyzenNotificationServices::STATUS_ON_DEMAND
        );
    }
}
