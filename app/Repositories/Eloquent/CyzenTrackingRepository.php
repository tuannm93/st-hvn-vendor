<?php

namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenTracking;
use App\Models\MStaff;
use App\Repositories\CyzenTrackingRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class CyzenTrackingRepository extends SingleKeyModelRepository implements CyzenTrackingRepositoryInterface
{
    /**
     * @var \App\Models\Cyzen\CyzenTracking $model
     */
    public $model;

    /**
     * CyzenSchedulesRepository constructor.
     *
     * @param \App\Models\Cyzen\CyzenTracking $model
     */
    public function __construct(CyzenTracking $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Cyzen\CyzenTracking|mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function saveData($data)
    {
        $existTrack = $this->model->where(
            [
                'user_id' => $data['user_id'],
                'created_at' => $data['created_at']
            ]
        )->first();

        if (!$existTrack) {
            return $this->model->insert($data);
        }
        return true;
    }

    /**
     * get list
     * @param $listIdCorp
     * @param $lat
     * @param $lng
     * @param $limit
     * @return mixed
     */
    public function getListDistanceOfUser(&$listIdCorp, $lat, $lng, $limit)
    {
        $staffModel = new MStaff();
        $geographyData = 'ST_GeographyFromText(\'Point(' . $lng . ' ' . $lat . ')\')';
        $geometryData = 'ST_GeographyFromText(\'Point(' . $lng . ' ' . $lat . ')\')::geometry';
        $query = $staffModel->select(
            'm_users.affiliation_id as corp_id',
            'm_users.user_id as id_staff',
            'm_users.user_name as name_staff',
            'm_staffs.staff_phone as staff_phone'
        )
            ->addSelect(\DB::raw('ST_Distance_Sphere(tracking_location::geometry, ' . $geometryData . ') as distance'))
            ->join('cyzen_trackings', 'cyzen_trackings.id', '=', \DB::raw('
            (select cyzen_trackings.id from cyzen_trackings where cyzen_trackings.user_id = m_staffs.cyzen_user_id
                and cyzen_trackings.created_at >  now() - interval \'10 minutes\' 
             order by cyzen_trackings.created_at desc limit 1)'))
            ->join('m_users', function ($join) use ($listIdCorp) {
                /** @var JoinClause $join */
                $listId = array_pluck($listIdCorp, 'corp_id');
                $join->on('m_users.user_id', '=', 'm_staffs.sp_user_id')
                    ->whereIn('m_users.affiliation_id', $listId);
            })
            ->where(function ($where) use ($geographyData, $limit) {
                /** @var Builder $where */
                if (!empty($limit)) {
                    /**
                     * ST_DWithin() is function of postgis return bool so can't use where(DB::raw...)
                     * cause sql which generated = 'where ST_DWithin(tracking_location,...) is null => false
                     */
                    $where->whereRaw(\DB::raw('ST_DWithin(tracking_location,' . $geographyData . ', ' . $limit . ')'));
                }
            })
            ->orderBy('distance', 'asc')
            ->get()->toArray();
        return $query;
    }
}
