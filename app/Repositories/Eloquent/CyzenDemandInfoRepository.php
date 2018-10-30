<?php
/**
 * Created by PhpStorm.
 * User: ducho
 * Date: 6/8/2018
 * Time: 9:39 AM
 */

namespace App\Repositories\Eloquent;

use App\Models\MStaff;
use App\Repositories\CyzenDemandInfoRepositoryInterface;
use App\Services\Cyzen\CyzenNotificationServices;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder;

class CyzenDemandInfoRepository extends SingleKeyModelRepository implements CyzenDemandInfoRepositoryInterface
{
    /**
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function apiDemandInfo($id, $userId)
    {
        $demandInfo = \DB::table('demand_infos')
            ->leftJoin('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'demand_infos.category_id')
            ->leftJoin('demand_extend_infos', 'demand_infos.id', '=', 'demand_extend_infos.demand_id')
            ->leftJoin('m_sites', 'm_sites.id', '=', 'demand_infos.site_id')
            ->join('demand_notification', function ($join) use ($userId) {
                /** @var JoinClause $join */
                $join->on('demand_notification.demand_id', '=', 'demand_infos.id')
                    ->where('demand_notification.user_id', '=', $userId);
            })
            ->select(
                "demand_infos.id",
                "demand_infos.customer_name",
                "demand_infos.customer_tel",
                "demand_infos.address1",
                "demand_infos.address2",
                "demand_infos.address3",
                "demand_infos.customer_mailaddress",
                "demand_infos.genre_id",
                "demand_infos.category_id",
                "m_categories.category_name",
                "m_genres.genre_name",
                "demand_notification.draft_start_time as start",
                "demand_notification.draft_end_time as end",
                "demand_notification.spot_id as spot_id",
                "m_sites.site_name",
                \DB::raw("ST_X(demand_extend_infos.location_demand::geometry) as lng"),
                \DB::raw("ST_Y(demand_extend_infos.location_demand::geometry) as lat")
            )
            ->where('demand_infos.id', '=', $id)
            ->get()->toArray();
        return $demandInfo;
    }

    /**
     * @param $demandId
     * @param $staffId
     * @param bool $isName
     * @return mixed
     */
    public function getDemandStatus($demandId, $staffId, $isName = true)
    {
        $info = \DB::table('demand_notification')
            ->join('commission_infos', 'commission_infos.id', '=', 'demand_notification.commission_id')
            ->where(['demand_notification.demand_id' => $demandId, 'user_id' => $staffId])
            ->select('commission_infos.work_status')->get()->toArray();
        if (!empty($info)) {
            if ($isName) { //read status name
                return $this->getStatusName($info[0]->work_status);
            } else {
                return $info[0]->work_status;
            }
        }
        return '';
    }

    /**
     * @param $id
     * @return string
     */
    public function getStatusName($id)
    {
        $name = \DB::table('m_items')
            ->where(['item_category' => '作業状態', 'item_id' => $id])
            ->select('item_name')
            ->get()->toArray();
        if (!empty($name)) {
            return $name[0]->item_name;
        }
        return '';
    }

    /**
     * @param $demandId
     * @param $status
     * @param $staffId
     * @return mixed
     */
    public function updateStatusCommissionInfo($demandId, $status, $staffId)
    {

        switch ($status) {
            case CyzenNotificationServices::STATUS_STARTED_WORK:
                $workStatus = 2;
                break;
            case CyzenNotificationServices::STATUS_END_WORK:
                $workStatus = 3;
                break;
        }
        if (isset($workStatus)) {
            return \DB::table('commission_infos')
                ->join('demand_notification', 'commission_infos.id', '=', 'demand_notification.commission_id')
                ->where(['demand_notification.demand_id' => $demandId, 'user_id' => $staffId])
                ->update(['work_status' => $workStatus]);
        } else {
            return false;
        }
    }

    /**
     * @param $demandId
     * @param $userId
     * @return mixed
     */
    public function getNotificationStatus($demandId, $userId)
    {
        $status = \DB::table('demand_notification')->where(['demand_id' => $demandId, 'user_id' => $userId])
            ->get(['status'])->first();

        return ($status) ? $status->status : '';
    }

    /**
     * @param $listCorpId
     * @param $start
     * @param $end
     * @param $genreId
     * @param $categoryId
     * @param bool $isSpecificTime
     * @return mixed
     */
    public function getStaffFreeSchedule($listCorpId, $start, $end, $genreId, $categoryId, $isSpecificTime = false)
    {
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));

        $mStaffModel = new MStaff();
        /** @var Builder $mStaffModel */
        $schedules = $mStaffModel
            ->select([
                'm_staffs.sp_user_id as id_staff',
                'cyzen_users.id as cyzen_user_id',
                'm_users.user_name as name_staff',
                'm_staffs.corp_id as corp_id'
            ])
            ->join('m_users', 'm_staffs.sp_user_id', '=', 'm_users.user_id')
            ->leftJoin('corp_registered_schedule', 'corp_registered_schedule.corp_id', '=', 'm_staffs.corp_id')
            ->leftJoin('cyzen_users', 'cyzen_users.id', '=', 'm_staffs.cyzen_user_id')
            ->leftJoin('cyzen_schedule_users', 'cyzen_schedule_users.user_id', '=', 'cyzen_users.id')
            ->leftJoin('cyzen_schedules', 'cyzen_schedules.id', '=', 'cyzen_schedule_users.schedule_id')
            ->whereIn('m_staffs.corp_id', $listCorpId);

        if (!$isSpecificTime) {
            $schedules->whereNotIn('m_staffs.sp_user_id', function ($query) use ($start, $end, $listCorpId) {
                /** @var Builder $query */
                $query->select(['m_staffs.sp_user_id'])
                    ->from('cyzen_users')
                    ->leftJoin('m_staffs', 'cyzen_users.id', '=', 'm_staffs.cyzen_user_id')
                    ->leftJoin('cyzen_schedule_users', 'cyzen_users.id', '=', 'cyzen_schedule_users.user_id')
                    ->leftJoin('cyzen_schedules', 'cyzen_schedule_users.schedule_id', '=', 'cyzen_schedules.id')
                    ->where(function ($query) use ($listCorpId) {
                        /** @var Builder $query */
                        $query->whereIn('m_staffs.corp_id', $listCorpId);
                        $query->where('cyzen_schedules.id', '<>', 'null');
                    })
                    ->where(function ($query) use ($start, $end) {
                        /** @var Builder $query */
                        $query->orWhereRaw(\DB::raw("'" . $start . "'" . ' between "cyzen_schedules"."start_date" and "cyzen_schedules"."end_date"'))
                            ->orWhereRaw(\DB::raw("('" . $start . "'" . ' < "cyzen_schedules"."start_date" and "cyzen_schedules"."start_date" < '. "'" . $end . "')"))
                            ->orWhereRaw(\DB::raw("('" . $start . "'" . ' < "cyzen_schedules"."end_date"  and "cyzen_schedules"."end_date"  < '. "'" . $end . "')"));
                    });
            });
        }


        $result = $schedules->distinct()->get()->toArray();

        if (!empty($result) && $isSpecificTime) {
            $output = $this->filterClosetSchedule($result, $start);
            return $output;
        }
        return $result;
    }

    /**
     * @param $result
     * @param $start
     * @return array
     */
    private function filterClosetSchedule($result, $start)
    {
        $staffs = array_unique(array_pluck($result, 'id_staff'));
        $mStaffModel = new MStaff();

        $list = $mStaffModel
            ->select([
                'm_staffs.sp_user_id as id_staff',
                'cyzen_users.id as cyzen_user_id',
                'm_users.user_name as name_staff',
                'm_staffs.corp_id as corp_id',
                \DB::raw('MAX(cyzen_schedules.end_date) as max_end')
            ])
            ->join('m_users', 'm_staffs.sp_user_id', '=', 'm_users.user_id')
            ->groupBy(['m_staffs.sp_user_id', 'cyzen_users.id', 'm_staffs.corp_id', 'name_staff'])
            ->leftJoin('cyzen_users', 'cyzen_users.id', '=', 'm_staffs.cyzen_user_id')
            ->leftJoin('cyzen_schedule_users', 'cyzen_schedule_users.user_id', '=', 'cyzen_users.id')
            ->leftJoin('cyzen_schedules', 'cyzen_schedules.id', '=', 'cyzen_schedule_users.schedule_id')
            ->whereIn('m_staffs.sp_user_id', $staffs)
            ->where('cyzen_schedules.end_date', '<=', $start)
            ->get()->toArray();

        if (empty($list)) {
            return $result;
        } else {
            $listExtend = [];
            foreach ($list as $key => $item) {
                $listExtend[$item['id_staff']] = $item;
            }

            foreach ($result as $key => $item) {
                if (isset($listExtend[$item['id_staff']])) {
                    $result[$key] = $listExtend[$item['id_staff']];
                }
            }
            return $result;
        }
    }
}
