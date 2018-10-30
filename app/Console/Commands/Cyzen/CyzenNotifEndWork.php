<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenNotificationServices;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenNotifEndWork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_notif_end_work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notif when status demand_notification is 0 (on demand)';

    /** @var CyzenNotificationServices $services */
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
                createLogPathCyzen(CyzenNotificationServices::LOG_PATH_NOTIFICATION_END_TIME),
                Logger::INFO
            )
        );
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->services->logger->log(Logger::INFO, '==========START SEND NOTIFICATION END WORK==========');
        $curTime = strtotime(now());
        $listStaff = $this->services->getListStaff([
            CyzenNotificationServices::STATUS_STARTED_WORK,
            CyzenNotificationServices::STATUS_END_TIME
        ]);
        if (!empty($listStaff)) {
            foreach ($listStaff as $arr) {
                $timeEnd = strtotime($arr['draft_end_time']);
                if ($arr['status'] == CyzenNotificationServices::STATUS_STARTED_WORK) {
                    if ($timeEnd - 30 <= $curTime && $curTime <= $timeEnd + 29) {
                        $this->executeOnTime($arr);
                    }
                }
                if ($arr['status'] == CyzenNotificationServices::STATUS_END_TIME) {
                    if ($curTime >= $timeEnd + $this->services->timeCheckDelay) {
                        $this->executeWhenDelay($arr);
                    }
                }
            }
        } else {
            $this->services->logger->log(Logger::INFO, "NO STAFF STARTED WORK");
        }
        $this->services->logger->log(Logger::INFO, '==========END SEND NOTIFICATION END WORK==========');
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
        $pushMessage = __('cyzen_notifications.message_notification_on_end_work');
        $this->services->executeSendPush(
            $data['demand_id'],
            $data['user_id'],
            $pushMessage,
            CyzenNotificationServices::STATUS_END_TIME
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_END_TIME
            );
        } catch (\Exception $exception) {
            $this->services->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * @param $data
     */
    private function executeWhenDelay($data)
    {
        $this->services->logger->log(
            Logger::INFO,
            "EXECUTED DELAY " . $data['demand_id'] . '---' . $data['user_id']
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_DELAY_END_TIME
            );
            $this->services->executeSendMail($data, CyzenNotificationServices::STATUS_DELAY_END_TIME);
        } catch (\Exception $exception) {
            $this->services->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }
}
