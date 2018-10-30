<?php

namespace App\Services\Cyzen;

use App\Repositories\CyzenDemandInfoRepositoryInterface;
use App\Repositories\CyzenSchedulesRepositoryInterface;
use App\Repositories\CyzenTrackingRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\Commission\CommissionSelectExtendService;
use Illuminate\Database\QueryException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CyzenDemandServices
{
    /**
     * @var MUserRepositoryInterface $mUser
     */
    protected $mUser;
    /**
     * @var $tracking
     */
    protected $tracking;
    /**
     * @var CyzenSchedulesRepositoryInterface $schedules
     */
    protected $schedules;

    /**
     * @var \Monolog\Logger $logger
     */
    protected $logger;

    /**
     * @var string $logPath
     */
    protected $logPath = 'logs/cyzen/demand.log';

    /**
     * @var \App\Repositories\CyzenDemandInfoRepositoryInterface $demandInfoRepository
     */
    protected $demandInfoRepository;

    /**
     * @var \App\Services\Commission\CommissionSelectExtendService $commissionSelectService
     */
    protected $commissionSelectService;

    /**
     * CyzenDemandServices constructor.
     *
     * @param MUserRepositoryInterface $mUserRepository
     * @param CyzenTrackingRepositoryInterface $tracking
     * @param CyzenSchedulesRepositoryInterface $schedules
     * @param \App\Repositories\CyzenDemandInfoRepositoryInterface $demandInfoRepository
     * @param \App\Services\Commission\CommissionSelectExtendService $commissionSelectExtendService
     * @throws \Exception
     */
    public function __construct(
        MUserRepositoryInterface $mUserRepository,
        CyzenTrackingRepositoryInterface $tracking,
        CyzenSchedulesRepositoryInterface $schedules,
        CyzenDemandInfoRepositoryInterface $demandInfoRepository,
        CommissionSelectExtendService $commissionSelectExtendService
    ) {
        $this->mUser = $mUserRepository;
        $this->tracking = $tracking;
        $this->schedules = $schedules;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->commissionSelectService = $commissionSelectExtendService;

        $this->logger = new Logger('demand');
        $this->logger->pushHandler(new StreamHandler(storage_path($this->logPath), Logger::INFO));
    }

    /**
     * @param $draftSchedule
     * @param $currentTime
     * @param $isSpecificTime
     * @return mixed
     */
    public function refactorDraftSchedule($draftSchedule, $currentTime, $isSpecificTime)
    {
        if (empty($currentTime)) {
            $currentTime = now();
        }
        foreach ($draftSchedule as $key => $item) {
            if ($isSpecificTime && strtotime($item['start_date']) < strtotime($currentTime)) {
                $diff = (strtotime($item['end_date']) - strtotime($item['start_date']))/60;
                $item['start_date'] = date('Y-m-d H:i:s', strtotime($currentTime));
                $item['end_date'] = date('Y-m-d H:i:s', strtotime($item['start_date']." + ".$diff. "minutes"));
                $draftSchedule[$key] = $item;
            }
        }
        return $draftSchedule;
    }

    /**
     * adjust new schedule for staff
     *
     * @param $demandId
     * @param $start
     * @param $end
     * @param array $listCorpId
     * @param string $genreId
     * @param string $categoryId
     * @param bool $isEstimatedTime
     * @param bool $isSpecificTime
     * @return array|mixed
     */
    public function generateStaffDraftSchedule($demandId, $start, $end, array $listCorpId, $genreId, $categoryId, $isEstimatedTime = false, $isSpecificTime = false)
    {
        if (!empty($listCorpId)) {
            try {
                $schedule = $this->demandInfoRepository->getStaffFreeSchedule($listCorpId, $start, $end, $genreId, $categoryId, $isSpecificTime);
                $listStaff = array_unique(array_pluck($schedule, 'id_staff'));
                $corpRegisteredTime = $this->commissionSelectService->getRegisteredRange($listCorpId, $genreId, $categoryId, $isEstimatedTime);
                $scheduleExtend = $this->schedules->getListScheduleInRange($listStaff, $start, $end, $listCorpId, $genreId, $categoryId, $isEstimatedTime);
                $listStaffExtend = array_unique(array_pluck($scheduleExtend, 'id_staff'));

                $listAllStaff = array_merge($listStaff, $listStaffExtend);
                $scheduleInDemandNotification = $this->schedules->getListScheduleInDemandNotification($listAllStaff, $start, $end, $isSpecificTime, $demandId);
                if ($scheduleInDemandNotification['outside'] && !empty($scheduleInDemandNotification['data'])) {
                    $schedule = array_merge($schedule, $scheduleInDemandNotification['data']);
                    $now = strtotime(now());
                    $schedule = collect($schedule)->map(function ($item) use ($now, $start) {
                        if (!isset($item['max_end']) ||
                            (isset($item['max_end']) && strtotime($item['max_end']) < $now && $now <= strtotime($start))) {
                            $item['max_end'] = date('Y-m-d H:i:s', $now);
                        }
                        return $item;
                    })->toArray();
                } elseif (!empty($scheduleInDemandNotification['data'])) {
                    $scheduleExtend = array_merge($scheduleExtend, $scheduleInDemandNotification['data']);
                    $listStaffAfterMerge = array_unique(array_pluck($scheduleExtend, 'id_staff'));
                    $schedule = collect($schedule)->reject(function ($item) use ($listStaffAfterMerge) {
                        if (is_array($listStaffAfterMerge) && in_array($item['id_staff'], $listStaffAfterMerge)) {
                            return true;
                        }
                        return false;
                    })->toArray();
                }

                $draftSchedule = $this->makeDraftSchedule($schedule, $scheduleExtend, $start, $end, $corpRegisteredTime, $isSpecificTime);

                return $draftSchedule;
            } catch (QueryException $ex) {
                \Log::warning(__FILE__ . ' >>> ' . __LINE__ . ' >>> ' . $ex->getMessage());
            }
        }

        return [];
    }

    /**
     * @param $schedule
     * @param $scheduleExtend
     * @param $start
     * @param $end
     * @param $corpRegisteredTime
     * @param $isSpecificTime
     * @return array
     */
    public function makeDraftSchedule($schedule, $scheduleExtend, $start, $end, $corpRegisteredTime, $isSpecificTime)
    {
        $draftSchedules = [];
        usort($schedule, function ($a, $b) {
            if (isset($a['max_end']) && isset($b['max_end']) && ($a['max_end'] > $b['max_end'])) {
                return 1;
            }
        });
        foreach ($schedule as $key => $value) {
            if (isset($value['max_end']) && !empty($value['max_end'])) {
                $draftStart = $value['max_end'];
            } else {
                if ($isSpecificTime) {
                    $draftStart = date('Y-m-d H:i:s');
                } else {
                    $draftStart = $start;
                }
            }
            $draftEnd = date('Y-m-d H:i:s', strtotime($draftStart.' + '.$corpRegisteredTime[$value['corp_id']]. 'minutes'));
            $draftSchedules[$value['id_staff']] = [
                'id_staff' => $value['id_staff'],
                'corp_id' => $value['corp_id'],
                'start_date' => $draftStart,
                'end_date' => $draftEnd
            ];
        }

        $draftSchedulesExtend = $this->makeDraftScheduleExtend($start, $end, $scheduleExtend, $corpRegisteredTime);

        $draftSchedules = array_merge($draftSchedules, $draftSchedulesExtend);

        return $draftSchedules;
    }

    /**
     * @param $start
     * @param $end
     * @param $listStaff
     * @param $corpRegisteredTime
     * @return  mixed
     */
    private function makeDraftScheduleExtend($start, $end, $listStaff, $corpRegisteredTime)
    {
        usort($listStaff, function ($a, $b) {
            if (isset($a['start_date']) && isset($b['start_date']) && ($a['start_date'] > $b['start_date'])) {
                return 1;
            }
        });
        $staffTmp = [];
        $draft = [];
        foreach ($listStaff as $key => $staff) {
            if (isset($draft[$staff['id_staff']]) && $draft[$staff['id_staff']]['is_real']) {
                continue;
            }

            $originStaff = $staff;
            if (!isset($staffTmp[$staff['id_staff']])) {
                $staffTmp[$staff['id_staff']]['start_free'] = (strtotime($start) > strtotime($staff['start_date']))
                    ? $staff['end_date']
                    : date('Y-m-d H:i:s', strtotime($start));
            }

            $free = (strtotime($staff['start_date']) - strtotime($staffTmp[$staff['id_staff']]['start_free']))/60; //in minutes
            $registeredTime = $corpRegisteredTime[$staff['corp_id']];
            if ($free >= $registeredTime) {
                $staff['start_date'] = $staffTmp[$staff['id_staff']]['start_free'];
                $staff['end_date'] = date('Y-m-d H:i:s', strtotime($staff['start_date']." + ".$registeredTime." minutes"));
                $staff['sort'] = $key + 1;
                $staff['is_real'] = true;
                $draft[$staff['id_staff']] = $staff;
            } else {
                unset($draft[$staff['id_staff']]);
                $endDraft = (strtotime($end) - strtotime($staff['end_date']))/60; //in minutes
                if ($endDraft >= $registeredTime) {
                    $staff['start_date'] = $staff['end_date'];
                    $staff['end_date'] = date('Y-m-d H:i:s', strtotime($staff['start_date']." + ".$registeredTime." minutes"));
                    $staff['sort'] = $key + 1;
                    $staff['is_real'] = false;
                    $draft[$staff['id_staff']] = $staff;
                }
            }
            $staffTmp[$staff['id_staff']]['start_free'] = $originStaff['end_date'];
        }
        return $draft;
    }
    /**
     * @param $demandId
     * @return mixed
     */
    public function getDemandStaffs($demandId)
    {
        $staff = \DB::table('demand_notification')
            ->leftJoin('m_users', 'm_users.user_id', '=', 'demand_notification.user_id')
            ->join('m_staffs', 'm_staffs.sp_user_id', '=', 'demand_notification.user_id')
            ->where(['demand_id' => $demandId])->get([
                'demand_notification.commission_id',
                'm_users.user_id',
                'm_users.user_name',
                'm_staffs.staff_phone'
            ])->toArray();
        $staffList = [];
        foreach ($staff as $data) {
            $staffList[$data->commission_id] = [
                'user_id' => $data->user_id,
                'user_name' => $data->user_name,
                'staff_phone' => $data->staff_phone
            ];
        }
        return $staffList;
    }
}
