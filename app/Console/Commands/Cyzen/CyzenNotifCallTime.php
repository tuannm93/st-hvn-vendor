<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenNotificationServices;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenNotifCallTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_notif_call_time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notif when status demand_notification is 0 (on demand)';

    /**
     * @var CyzenNotificationServices $services
     */
    protected $services;

    /**
     * Create a new command instance.
     *
     * @param CyzenNotificationServices $notificationServices
     * @throws \Exception
     */
    public function __construct(
        CyzenNotificationServices $notificationServices
    ) {
        parent::__construct();
        $this->services = $notificationServices;
        $this->services->timeCheckDelay = (int)env('TIME_CHECK_DELAY', 15) * 60;
        $this->services->timeCheckBeforeAndAfter = (int)env('TIME_CHECK_BEFORE_AND_AFTER', 10) * 60;
        $this->services->logger = new Logger('Cyzen');
        $this->services->logger->pushHandler(
            new StreamHandler(
                createLogPathCyzen(CyzenNotificationServices::LOG_PATH_NOTIFICATION_CALL_TIME),
                Logger::INFO
            )
        );
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->services->logger->log(Logger::INFO, '==========START SEND NOTIFICATION CALL TIME==========');
        $curTime = strtotime(now());
        $listStaff = $this->services->getListStaff(CyzenNotificationServices::STATUS_ON_DEMAND);
        if (!empty($listStaff)) {
            foreach ($listStaff as $arr) {
                $timeCallFrom = strtotime($arr['call_time_from']);
                $timeCallTo = strtotime($arr['call_time_to']);
                if ($curTime >= $timeCallTo + $this->services->timeCheckDelay) {
                    $this->executeWhenDelay($arr);
                }
                if ($timeCallFrom - 30 <= $curTime && $curTime <= $timeCallFrom + 29) {
                    $this->executeOnTime($arr);
                }
            }
        } else {
            $this->services->logger->log(Logger::INFO, "NO STAFF ON DEMAND");
        }
        $this->services->logger->log(Logger::INFO, '==========END SEND NOTIFICATION CALL TIME==========');
    }

    /**
     * @param $data
     */
    private function executeWhenDelay($data)
    {
        $this->services->logger->log(
            Logger::INFO,
            "EXECUTED DELAYED " . $data['demand_id'] . '---' . $data['user_id']
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_DELAYED_CALL_TIME
            );
            $this->services->executeSendMail($data, CyzenNotificationServices::STATUS_DELAYED_CALL_TIME);
        } catch (\Exception $exception) {
            $this->services->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * @param $data
     */
    private function executeOnTime($data)
    {
        $this->services->logger->log(
            Logger::INFO,
            "EXECUTED ON TIME " . $data['demand_id'] . '---' . $data['user_id']
        );
        $pushMessage = __('cyzen_notifications.message_notification_on_call_time');
        $this->services->executeSendPush(
            $data['demand_id'],
            $data['user_id'],
            $pushMessage,
            CyzenNotificationServices::STATUS_RETURN_MOBILE_CALL_TIME
        );
    }
}
