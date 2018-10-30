<?php

namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenSchedule;
use App\Models\Cyzen\CyzenScheduleUser;
use App\Models\DemandNotification;
use App\Repositories\CyzenSchedulesRepositoryInterface;
use App\Services\Cyzen\CyzenNotificationServices;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class CyzenSchedulesRepository extends SingleKeyModelRepository implements CyzenSchedulesRepositoryInterface
{
    /**
     * @var CyzenSchedule $model
     */
    public $model;

    /**
     * @var \App\Models\Cyzen\CyzenScheduleUser $scheduleUserModel
     */
    public $scheduleUserModel;

    /**
     * @var \App\Models\DemandNotification $demandNotificationModel
     */
    public $demandNotificationModel;

    /**
     * CyzenSchedulesRepository constructor.
     *
     * @param \App\Models\Cyzen\CyzenSchedule $model
     * @param \App\Models\Cyzen\CyzenScheduleUser $cyzenScheduleUser
     * @param \App\Models\DemandNotification $demandNotification
     */
    public function __construct(
        CyzenSchedule $model,
        CyzenScheduleUser $cyzenScheduleUser,
        DemandNotification $demandNotification
    ) {
        $this->model = $model;
        $this->scheduleUserModel = $cyzenScheduleUser;
        $this->demandNotificationModel = $demandNotification;
    }

    /**
     * @return \App\Models\Cyzen\CyzenSchedule|mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $field
     * @return array|mixed
     */
    public function getAllGroupBy($field)
    {
        return $this->model->pluck($field)->toArray();
    }

    /**
     * @param $data
     * @return bool|\Exception|mixed
     * @throws \Exception
     */
    public function saveSchedule($data)
    {
        $schedule = $this->model->find($data['id']);

        if (!$schedule) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $schedule->$key = $value;
        }

        return $schedule->save();
    }

    /**
     * @param $key
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function checkForeignKey($key, $model)
    {
        $hasDataRelation = $model->find($key);

        return ($hasDataRelation) ? true : false;
    }

    /**
     * @return \App\Models\Base|CyzenSchedule|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CyzenSchedule();
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function saveData($data)
    {
        $schedule = $this->model->find($data['id']);

        if (!$schedule) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $schedule->$key = $value;
        }

        return $schedule->save();
    }

    /**
     * @return mixed
     */
    public function getCountData()
    {
        $query = $this->model->count('id');

        return $query;
    }

    /**
     * @return mixed
     */
    public function getLastUpdatedDate()
    {
        $query = $this->model->select('*')->orderBy('updated_at', 'desc')->first();

        return $query;
    }

    /**
     * @param $data
     * @return void
     * @throws \Exception
     */
    public function saveScheduleUser($data)
    {
        $old = $this->scheduleUserModel->where('schedule_id', $data[0]['schedule_id']);
        if (!empty($old)) {
            $old->delete();
        }
        $this->scheduleUserModel->insert($data);
    }

    /**
     * @param $listIdCorp
     * @param $startTime
     * @param $endTime
     * @param $genreId
     * @param $categoryId
     * @param $isEstimateTime
     * @return mixed
     */
    public function getListScheduleOfUse(&$listIdCorp, $startTime, $endTime, $genreId, $categoryId, $isEstimateTime)
    {
        $query = $this->model->select(
            'm_users.user_id as id_staff',
            'm_users.affiliation_id as corp_id',
            'm_users.user_name as name_staff',
            'm_staffs.staff_phone as staff_phone',
            'cyzen_schedules.start_date as start_date',
            'cyzen_schedules.end_date as end_date'
        )
            ->join('cyzen_schedule_users', 'cyzen_schedules.id', '=', 'cyzen_schedule_users.schedule_id')
            ->join('cyzen_users', 'cyzen_schedule_users.user_id', '=', 'cyzen_users.id')
            ->rightJoin('m_staffs', 'm_staffs.cyzen_user_id', '=', 'cyzen_users.id')
            ->join('m_users', function ($join) use ($listIdCorp) {
                /** @var JoinClause $join */
                $listId = array_pluck($listIdCorp, 'corp_id');
                $join->on('m_users.user_id', '=', 'm_staffs.sp_user_id')
                    ->whereIn('m_users.affiliation_id', $listId);
            });
        $query->whereNotIn('m_users.user_id', function ($where) use ($listIdCorp, $startTime, $endTime) {
            /** @var Builder $where */
            $where->select('m_users.user_id')
                ->from('cyzen_schedules')
                ->join('cyzen_schedule_users', 'cyzen_schedules.id', '=', 'cyzen_schedule_users.schedule_id')
                ->join('cyzen_users', 'cyzen_schedule_users.user_id', '=', 'cyzen_users.id')
                ->rightJoin('m_staffs', 'm_staffs.cyzen_user_id', '=', 'cyzen_users.id')
                ->join('m_users', function ($join) use ($listIdCorp) {
                    /** @var JoinClause $join */
                    $listId = array_pluck($listIdCorp, 'corp_id');
                    $join->on('m_users.user_id', '=', 'm_staffs.sp_user_id')
                        ->whereIn('m_users.affiliation_id', $listId);
                })
                ->orWhereRaw("'" . $startTime . "'" . ' between "cyzen_schedules"."start_date" and "cyzen_schedules"."end_date"')
                ->orWhereRaw(\DB::raw("('" . $startTime . "'" . ' < "cyzen_schedules"."start_date" and "cyzen_schedules"."start_date" < ' . "'" . $endTime . "')"))
                ->orWhereRaw(\DB::raw("('" . $startTime . "'" . ' < "cyzen_schedules"."end_date" and "cyzen_schedules"."end_date" < ' . "'" . $endTime . "')"));
        });

        $result = $query->get()->toArray();
        return $result;
    }

    /**
     * @param Builder $query
     * @param $genreId
     * @param $categoryId
     * @param $startTime
     * @param $endTime
     */
    private function addQueryForEstimatedTime(&$query, $genreId, $categoryId, $startTime, $endTime)
    {
        $query->leftJoin('corp_registered_schedule', function ($join) {
            /** @var JoinClause $join */
            $join->on('m_users.affiliation_id', '=', 'corp_registered_schedule.corp_id');
        });
        $query->where(function ($where) use (
            $startTime,
            $endTime,
            $genreId,
            $categoryId
        ) {
            /** @var Builder $where */
            $where->where(function ($where2) use (
                $startTime,
                $endTime,
                $genreId,
                $categoryId
            ) {
                /** @var Builder $where2 */
                $diffTime = (strtotime($endTime) - strtotime($startTime)) / 60;
                $where2->where('corp_registered_schedule.genre_id', '=', $genreId)
                    ->where('corp_registered_schedule.category_id', '=', $categoryId)
                    ->where('corp_registered_schedule.time_finish', '<=', $diffTime);
            })
                ->orWhereNull('corp_registered_schedule.corp_id');
        });
    }

    /**
     * @param $data
     * @return mixed
     */
    public function updateDemandNotification($data)
    {
        $demand = $this->demandNotificationModel->where([
            'demand_id' => $data['demand_id'],
            'user_id' => $data['user_id']
        ])
            ->first();

        if (!empty($demand)) {
            foreach ($data as $key => $value) {
                $demand->$key = $value;
            }
            return $demand->save();
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getStaffFromUserId($id)
    {
        $staff = \DB::table('m_staffs')->where(['cyzen_user_id' => $id])->get(['sp_user_id'])->first();
        if (!empty($staff)) {
            return $staff->sp_user_id;
        }
        return '';
    }

    /**
     * @param $spotId
     * @return mixed
     */
    public function getDemandIdFromSpot($spotId)
    {
        $demand = \DB::table('cyzen_spots')->where(['id' => $spotId])->get(['spot_name'])->first();
        if (!empty($demand)) {
            return $demand->spot_name;
        }
        return '';
    }

    /**
     * @param $listStaff
     * @param $start
     * @param $end
     * @param $listIdCorp
     * @param $genreId
     * @param $categoryId
     * @param $isEstimatedTime
     * @return array|mixed
     */
    public function getListScheduleInRange(
        $listStaff,
        $start,
        $end,
        $listIdCorp,
        $genreId,
        $categoryId,
        $isEstimatedTime
    ) {
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));

        $query = $this->model->select(
            'm_users.user_id as id_staff',
            'm_users.affiliation_id as corp_id',
            'm_users.user_name as name_staff',
            'm_staffs.staff_phone as staff_phone',
            'cyzen_schedules.start_date as start_date',
            'cyzen_schedules.end_date as end_date'
        )
            ->join('cyzen_schedule_users', 'cyzen_schedules.id', '=', 'cyzen_schedule_users.schedule_id')
            ->join('cyzen_users', 'cyzen_schedule_users.user_id', '=', 'cyzen_users.id')
            ->rightJoin('m_staffs', 'm_staffs.cyzen_user_id', '=', 'cyzen_users.id')
            ->join('m_users', function ($join) use ($listIdCorp, $listStaff) {
                /** @var JoinClause $join */
                $join->on('m_users.user_id', '=', 'm_staffs.sp_user_id')
                    ->whereIn('m_users.affiliation_id', $listIdCorp)
                    ->whereNotIn('m_users.user_id', $listStaff);
            })
            ->orWhereRaw(\DB::raw("'" . $start . "'" . ' between "cyzen_schedules"."start_date" and "cyzen_schedules"."end_date"'))
            ->orWhereBetween('cyzen_schedules.start_date', [$start, $end])
            ->orWhereBetween('cyzen_schedules.end_date', [$start, $end])
            ->orderBy('end_date');

        $result = $query->get()->toArray();
        return $result;
    }

    /**
     * @param $corpId
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getCorpRegisteredSchedule($corpId, $genreId, $categoryId)
    {
        $data = \DB::table('corp_registered_schedule')->where([
            'genre_id' => $genreId,
            'category_id' => $categoryId
        ])->whereIn('corp_id', $corpId)->get()->toArray();

        return $data;
    }

    /**
     * @param $listStaff
     * @param $start
     * @param $end
     * @param $isSpecificTime
     * @param $demandId
     * @return mixed
     */
    public function getListScheduleInDemandNotification($listStaff, $start, $end, $isSpecificTime, $demandId)
    {
        if (!$isSpecificTime) {
            $query = $this->demandNotificationModel->select([
                'demand_notification.user_id as id_staff',
                'm_users.affiliation_id as corp_id',
                'm_users.user_name as name_staff',
                'demand_notification.draft_start_time as start_date',
                'demand_notification.draft_end_time as end_date'
            ])
                ->join('m_users', 'm_users.user_id', '=', 'demand_notification.user_id')
                ->whereIn('demand_notification.user_id', $listStaff)
                ->where(function ($where) use ($start, $end) {
                    /** @var JoinClause $where */
                    $where->orWhereRaw("'" . $start . "'" . ' between "demand_notification"."draft_start_time" and "demand_notification"."draft_end_time"');
                    $where->orWhereRaw("('" . $start . "'" . ' < "demand_notification"."draft_start_time" and "demand_notification"."draft_start_time" < ' . "'" . $end . "')");
                    $where->orWhereRaw("('" . $start . "'" . ' < "demand_notification"."draft_end_time" and "demand_notification"."draft_end_time" < ' . "'" . $end . "')");
                })
                ->where('spot_id', '!=', null)
                ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_END_WORK)
                ->where('demand_notification.status', '>', CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER)
                ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_CYZEN)
                ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP);

            if (!empty($demandId)) {
                $query = $query->where('demand_id', '!=', $demandId);
            }

            $output = $query->get()->toArray();

            $result = [
                'data' => $output,
                'outside' => false
            ];
        } else {
            $query = $this->demandNotificationModel
                ->select([
                    'demand_notification.user_id as id_staff',
                    'demand_notification.cyzen_user_id as cyzen_user_id',
                    'm_users.user_name as name_staff',
                    'm_users.affiliation_id as corp_id',
                    \DB::raw('MAX(demand_notification.draft_end_time) as max_end')
                ])
                ->groupBy(['demand_notification.user_id', 'demand_notification.cyzen_user_id', 'm_users.affiliation_id', 'm_users.user_name'])
                ->join('m_users', 'm_users.user_id', '=', 'demand_notification.user_id')
                ->whereIn('demand_notification.user_id', $listStaff)
                ->where('demand_notification.draft_end_time', '<=', $start)
                ->where('spot_id', '!=', null)
                ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_END_WORK)
                ->where('demand_notification.status', '>', CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER)
                ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_CYZEN)
                ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP);
            $output = $query->get()->toArray();
            $result = [
                'data' => $output,
                'outside' => true
            ];
        }
        return $result;
    }

    /**
     * @param $scheduleId
     * @return void
     * @throws \Exception
     */
    public function deleteScheduleById($scheduleId)
    {
        $this->model->where('id', '=', $scheduleId)->delete();
    }
}
