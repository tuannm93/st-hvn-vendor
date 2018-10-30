<?php

namespace App\Repositories\Eloquent;

use App\Http\Controllers\Api\CyzenApiController;
use App\Models\DemandNotification;
use App\Repositories\DemandNotificationRepositoryInterface;
use App\Services\Cyzen\CyzenNotificationServices;
use Illuminate\Database\Query\Builder;

class DemandNotificationRepository extends SingleKeyModelRepository implements DemandNotificationRepositoryInterface
{
    /** @var DemandNotification $model */
    protected $model;

    /**
     * CyzenSpotRepository constructor.
     * @param DemandNotification $construct
     */
    public function __construct(DemandNotification $construct)
    {
        $this->model = $construct;
    }

    /**
     * @param $status
     * @return mixed
     */
    public function getListStaffByStatus($status)
    {
        if (is_array($status)) {
            $query = $this->model->select('*')->whereIn('status', $status)->get()->toArray();
        } else {
            $query = $this->model->select('*')->where('status', '=', $status)->get()->toArray();
        }
        return $query;
    }

    /**
     * @param $demandId
     * @param $staffId
     * @param $status
     * @return mixed
     */
    public function updateStatusByStaffAndDemand($demandId, $staffId, $status)
    {
        $demand = $this->model->where(['demand_id' => $demandId, 'user_id' => $staffId])->first();
        if (!empty($demand)) {
            if ((int)$demand->status == CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER
                || (int)$demand->status == CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP
                || (int)$demand->status == CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_CYZEN
            ) {
                return CyzenApiController::API_ERROR_COMMISSION_CHANGED;
            }
            $demand->status = $status;
            if ($demand->save()) {
                return CyzenApiController::API_SUCCESS;
            }
        }
        return CyzenApiController::API_ERROR;
    }

    /**
     * @param $demandId
     * @param $data
     * @return array|mixed
     * @throws \Exception
     */
    public function updateOrCreate($demandId, $data)
    {
        $this->model->where('demand_id', $demandId)->delete();
        if (empty($data)) {
            return [];
        }
        $ids = $this->model->insert($data);
        return $ids;
    }


    /**
     * @param $demandId
     * @param $userId
     * @return mixed
     */
    public function getDetailForMailContent($demandId, $userId)
    {
        $query = $this->model->select(
            'demand_notification.demand_id',
            'demand_notification.user_id',
            'demand_notification.commission_id',
            'demand_notification.draft_start_time',
            'demand_notification.draft_end_time',
            'demand_infos.customer_name as customer_name',
            'demand_infos.customer_tel as customer_phone',
            'demand_infos.address1',
            'demand_infos.address2',
            'demand_infos.address3',
            'm_users.user_name as staff_name',
            'm_staffs.staff_phone as staff_phone',
            'm_corps.id as kameiten_id',
            'm_corps.official_corp_name as kameiten_name',
            'm_corps.commission_dial as kameiten_phone',
            'm_corps.mailaddress_pc as kameiten_mail',
            'm_sites.site_url as site_name',
            'm_genres.genre_name as genre_name',
            'm_categories.category_name as category_name',
            'commission_infos.work_status'
        )
            ->join('demand_infos', 'demand_notification.demand_id', '=', 'demand_infos.id')
            ->join('m_users', 'demand_notification.user_id', '=', 'm_users.user_id')
            ->join('m_corps', 'm_users.affiliation_id', '=', 'm_corps.id')
            ->join('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id')
            ->join('m_categories', 'demand_infos.category_id', '=', 'm_categories.id')
            ->join('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->join('commission_infos', 'demand_notification.commission_id', '=', 'commission_infos.id')
            ->join('m_staffs', 'm_staffs.sp_user_id', '=', 'm_users.user_id')
            ->where('demand_notification.demand_id', '=', $demandId)
            ->where('demand_notification.user_id', '=', $userId)
            ->get()->toArray();
        return $query;
    }

    /**
     * @param $demandId
     * @return mixed
     */
    public function getAllByDemandId($demandId)
    {
        return $this->model->select('*')->where('demand_id', '=', $demandId)->get()->toArray();
    }

    /**
     * @param $staffId
     * @return mixed
     */
    public function getGroupByStaff($staffId)
    {
        $group = \DB::table('demand_notification')
            ->leftJoin('m_staffs', 'm_staffs.sp_user_id', '=', 'demand_notification.user_id')
            ->leftJoin('cyzen_users', 'cyzen_users.id', '=', 'm_staffs.cyzen_user_id')
            ->leftJoin('cyzen_user_groups', 'cyzen_user_groups.user_id', '=', 'cyzen_users.id')
            ->where(['demand_notification.user_id' => $staffId])
            ->select(['cyzen_user_groups.group_id'])->first();

        if (!empty($group)) {
            return $group->group_id;
        } else {
            return '';
        }
    }

    /**
     * @param $demandId
     * @return mixed
     */
    public function getCurrentStatus($demandId)
    {
        return $this->model->select(
            ['commission_id',
            'status',
            'call_time_from',
            'call_time_to',
            'draft_start_time',
            'draft_end_time']
        )->where('demand_id', '=', $demandId)->get()->toArray();
    }

    /**
     * @param $demandId
     * @param $userId
     * @param \Monolog\Logger $logger
     * @return void
     */
    public function updateStatusWhenDeleteSchedule($demandId, $userId, $logger)
    {
        $demand = $this->model->where(['demand_id' => $demandId, 'user_id' => $userId])->first();
        if (!empty($demand)) {
            $demand->status = CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_CYZEN;
            if (!$demand->save()) {
                $logger->error(__FILE__ . ' >>> ' . __LINE__ . ' >>> UPDATE FAIL AT DEMAND ID: ' . $demandId
                    . ' AND USER ID: ' . $userId);
            }
        } else {
            $logger->warning(__FILE__ . ' >>> ' . __LINE__ . ' >>> NO DEMAND AT: ' . $demandId
                . ' AND USER ID: ' . $userId);
        }
    }

    /**
     * @param $scheduleId
     * @return array
     */
    public function getDemandIdAndUserIdFromScheduleId($scheduleId)
    {
        $query = $this->model->select(
            'demand_notification.demand_id as demand_id',
            'demand_notification.user_id as user_id',
            'demand_notification.commission_id as commission_id'
        )
            ->join('cyzen_schedules', 'cyzen_schedules.spot_id', '=', 'demand_notification.spot_id')
            ->join('cyzen_schedule_users', 'cyzen_schedule_users.schedule_id', '=', 'cyzen_schedules.id')
            ->join('cyzen_users', 'cyzen_users.id', '=', 'cyzen_schedule_users.user_id')
            ->where('cyzen_schedules.id', '=', $scheduleId)
            ->whereNotNull('cyzen_schedules.spot_id')->get()->toArray();
        return $query;
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $startTime
     * @param $endTime
     * @param $idDemandNotification
     * @return mixed
     */
    public function checkImpactDraftSchedule($demandId, $userId, $startTime, $endTime, $idDemandNotification)
    {
        $query = $this->model->select(['id', 'demand_id', 'user_id'])
            ->where('demand_notification.user_id', '=', $userId)
            ->where(function ($whereDemand) use ($demandId, $idDemandNotification) {
                /** @var Builder $whereDemand */
                if (!empty($demandId)) {
                    $whereDemand->where('demand_notification.demand_id', '!=', $demandId);
                } else {
                    $whereDemand->where('demand_notification.id', '!=', $idDemandNotification);
                }
            })
            ->where(function ($where) {
                /** @var Builder $where */
                $where->where(function ($where2) {
                    /** @var Builder $where2 */
                    $where2->where('demand_notification.status', '=', CyzenNotificationServices::STATUS_WAIT_FOR_CREATE_CYZEN_SPOT)
                        ->whereNull('demand_notification.spot_id');
                })
                    ->orWhere(function ($where1) {
                        /** @var Builder $where1 */
                        $where1->where('demand_notification.status', '>', CyzenNotificationServices::STATUS_DEMAND_TEMP_REGISTER)
                            ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_END_WORK)
                            ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_CYZEN)
                            ->whereNotNull('demand_notification.spot_id');
                    });
            })
            ->where('demand_notification.status', '!=', CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP)
            ->where(function ($where) use ($startTime, $endTime) {
                /** @var Builder $where */
                $where->where(function ($where2) use ($startTime, $endTime) {
                    /** @var Builder $where2 */
                    $where2->whereRaw("('" . $startTime . "'" . ' between "demand_notification"."draft_start_time" and "demand_notification"."draft_end_time")')
                        ->whereRaw("('" . $endTime . "'" . ' between "demand_notification"."draft_start_time" and "demand_notification"."draft_end_time")');
                })
                    ->orWhereRaw(\DB::raw("('" . $startTime . "'" . ' < "demand_notification"."draft_start_time" and "demand_notification"."draft_start_time" < ' . "'" . $endTime . "')"))
                    ->orWhereRaw(\DB::raw("('" . $startTime . "'" . ' < "demand_notification"."draft_end_time" and "demand_notification"."draft_end_time" < ' . "'" . $endTime . "')"));
            })->get()->toArray();
        return $query;
    }

    /**
     * @param $demandId
     * @param $userId
     * @param $callStart
     * @param $callEnd
     * @param $draftStart
     * @param $draftEnd
     * @param $status
     * @param $commissionId
     * @return mixed
     */
    public function updateInfoByDemandAndStaff(
        $demandId,
        $userId,
        $callStart,
        $callEnd,
        $draftStart,
        $draftEnd,
        $status,
        $commissionId
    ) {
        $demand = $this->model->where(['demand_id' => $demandId, 'user_id' => $userId])->first();
        if (empty($demand)) {
            $demand = new DemandNotification();
        }
        $demand->demand_id = $demandId;
        $demand->user_id = $userId;
        $demand->status = $status;
        $demand->call_time_from = $callStart;
        $demand->call_time_to = $callEnd;
        $demand->draft_start_time = $draftStart;
        $demand->draft_end_time = $draftEnd;
        $demand->commission_id = $commissionId;
        if ($demand->save()) {
            return CyzenApiController::API_SUCCESS;
        }
        return CyzenApiController::API_ERROR;
    }

    /**
     * @param $userId
     * @param $callStart
     * @param $callEnd
     * @param $draftStart
     * @param $draftEnd
     * @param $status
     * @return mixed
     */
    public function insertDemandNotifTemp($userId, $callStart, $callEnd, $draftStart, $draftEnd, $status)
    {
        //BASE ON QUERY
//        $queryId = \DB::insert(
//            "INSERT INTO demand_notification (user_id, call_time_from, call_time_to, draft_start_time, draft_end_time, status) VALUES ('$userId', '$callStart', '$callEnd', '$draftStart', '$draftEnd', '$status')  RETURNING id;"
//        );
        $queryId = \DB::table('demand_notification')->insertGetId(
            [
                'user_id' => $userId,
                'call_time_from' => $callStart,
                'call_time_to' => $callEnd,
                'draft_start_time' => $draftStart,
                'draft_end_time' => $draftEnd,
                'status' => $status
            ]
        );
        return $queryId;
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function deleteDemandNotification($id)
    {
        \DB::delete("DELETE FROM demand_notification WHERE id = $id");
    }

    /**
     * @param $commissionId
     * @return mixed|void
     */
    public function updateByCommisionId($commissionId)
    {
        \DB::statement("update demand_notification set status = "
            .CyzenNotificationServices::STATUS_DELETE_SCHEDULE_FROM_SP." where commission_id = $commissionId");
    }
}
