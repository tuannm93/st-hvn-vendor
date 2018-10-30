<?php

namespace App\Console\Commands\Cyzen;

use App\Services\Cyzen\CyzenNotificationServices;
use Illuminate\Console\Command;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenNotifStartWork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cyzen_notif_start_work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notif when status demand_notification is 2 (called time), 9 (before), 10 (after) ';
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
                createLogPathCyzen(CyzenNotificationServices::LOG_PATH_NOTIFICATION_START_TIME),
                Logger::INFO
            )
        );
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->services->logger->log(Logger::INFO, '==========START SEND NOTIFICATION START WORK==========');
        $curTime = strtotime(now());
        $listStaff = $this->services->getListStaff([
            CyzenNotificationServices::STATUS_ON_DEMAND,
            CyzenNotificationServices::STATUS_CALLED,
            CyzenNotificationServices::STATUS_START_TIME,
            CyzenNotificationServices::STATUS_BEFORE_START_WORK,
            CyzenNotificationServices::STATUS_AFTER_START_WORK
        ]);
        if (!empty($listStaff)) {
            foreach ($listStaff as $arr) {
                $timeStart = strtotime($arr['draft_start_time']);
                $timeDelay = $timeStart + $this->services->timeCheckDelay;
                $timeBefore = $timeStart - $this->services->timeCheckBeforeAndAfter;
                $timeAfter = $timeStart + $this->services->timeCheckBeforeAndAfter;

                $this->services->logger->log(
                    Logger::INFO,
                    "STATUS: " . $arr['demand_id'] . ' --- ' . $arr['status'] . ' --- ' . $arr['user_id']
                    . ' --- ' . date('Y-m-d H:i:s', $curTime) . ' --- ' . date('Y-m-d H:i:s', $timeStart)
                );
                if ($arr['status'] == CyzenNotificationServices::STATUS_ON_DEMAND ||
                    $arr['status'] == CyzenNotificationServices::STATUS_CALLED) {
                    if ($timeBefore - 30 <= $curTime && $curTime <= $timeBefore + 29) {
                        $this->executeBeforeStart($arr);
                    }
                }
                if ($arr['status'] == CyzenNotificationServices::STATUS_BEFORE_START_WORK) {
                    if ($timeStart - 30 <= $curTime && $curTime <= $timeStart + 29) {
                        $this->executeOnTime($arr);
                    }
                }
                if ($arr['status'] == CyzenNotificationServices::STATUS_START_TIME) {
                    if ($timeAfter - 30 <= $curTime && $curTime <= $timeAfter + 29) {
                        $this->executeWhenAfter($arr);
                    }
                }
                if ($arr['status'] == CyzenNotificationServices::STATUS_AFTER_START_WORK) {
                    if ($curTime >= $timeDelay) {
                        $this->executeWhenDelay($arr);
                    }
                }
            }
        } else {
            $this->services->logger->log(Logger::INFO, "NO STAFF CALLED");
        }
        $this->services->logger->log(Logger::INFO, '==========END SEND NOTIFICATION START WORK==========');
    }

    /**
     * execute when have 10' before start work
     * @param $data
     */
    private function executeBeforeStart($data)
    {
        $this->services->logger->log(
            Logger::INFO,
            "EXECUTED BEFORE " . $data['demand_id'] . ' --- ' . $data['user_id']
        );
        $pushMessage = __('cyzen_notifications.message_notification_before_start_work');
        $this->services->executeSendPush(
            $data['demand_id'],
            $data['user_id'],
            $pushMessage,
            CyzenNotificationServices::STATUS_CALLED
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_BEFORE_START_WORK
            );
        } catch (\Exception $exception) {
            $this->services->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * execute when on time
     * @param $data
     */
    private function executeOnTime($data)
    {
        $this->services->logger->log(
            Logger::INFO,
            "EXECUTED ON TIME " . $data['demand_id'] . ' --- ' . $data['user_id']
        );
        $pushMessage = __('cyzen_notifications.message_notification_on_start_work');
        $this->services->executeSendPush(
            $data['demand_id'],
            $data['user_id'],
            $pushMessage,
            CyzenNotificationServices::STATUS_START_TIME
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_START_TIME
            );
        } catch (\Exception $exception) {
            $this->services->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }

    /**
     * @param $data
     */
    private function executeWhenAfter($data)
    {
        $this->services->logger->log(
            Logger::INFO,
            "EXECUTED AFTER TIME " . $data['demand_id'] . '  ---  ' . $data['user_id']
        );
        $pushMessage = __('cyzen_notifications.message_notification_delayed_start_work');
        $this->services->executeSendPush(
            $data['demand_id'],
            $data['user_id'],
            $pushMessage,
            CyzenNotificationServices::STATUS_CALLED
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_AFTER_START_WORK
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
            "EXECUTED DELAYED TIME " . $data['demand_id'] . ' --- ' . $data['user_id']
        );
        try {
            $this->services->updateStatusDemandNotification(
                $data['demand_id'],
                $data['user_id'],
                CyzenNotificationServices::STATUS_DELAY_START_TIME
            );
            $this->services->executeSendMail($data, CyzenNotificationServices::STATUS_DELAY_START_TIME);
        } catch (\Exception $exception) {
            $this->services->logger->log(Logger::CRITICAL, $exception->getMessage());
        }
    }
}
