<?php

namespace App\Repositories\Eloquent;

use App\Models\Cyzen\CyzenSpot;
use App\Repositories\CyzenSpotRepositoryInterface;

class CyzenSpotRepository extends SingleKeyModelRepository implements CyzenSpotRepositoryInterface
{

    /** @var CyzenSpot $model */
    protected $model;

    /**
     * CyzenSpotRepository constructor.
     * @param CyzenSpot $construct
     */
    public function __construct(CyzenSpot $construct)
    {
        $this->model = $construct;
    }

    /**
     *
     * @return mixed
     */
    public function getLastSpotByCrawlerTime()
    {
        $query = $this->model->orderBy('crawler_time', 'desc')->first();
        return $query;
    }

    /**
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public function updateOrInsertData($id, $data)
    {
        $spotObject = $this->model->find($id);
        /** @var CyzenSpot $spotObject */
        if (empty($spotObject)) {
            $spotObject = $this->getBlankModel();
            $spotObject->id = $id;
        }
        foreach ($data as $key => $value) {
            $spotObject->$key = $value;
        }
        return $spotObject->save();
    }

    /**
     * @return CyzenSpot
     */
    public function getBlankModel()
    {
        return new CyzenSpot();
    }

    /**
     * @return \App\Models\Cyzen\CyzenSpot|mixed
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
        $spot = $this->model->find($data['id']);

        if (!$spot) {
            return $this->model->insert($data);
        }

        foreach ($data as $key => $value) {
            $spot->$key = $value;
        }
        return $spot->save();
    }

    /**
     * @param $demandId
     * @param $groupId
     * @return mixed
     */
    public function checkSpotId($demandId, $groupId)
    {
        return \DB::table('demand_notification')
            ->join('cyzen_spots', 'cyzen_spots.id', '=', 'demand_notification.spot_id')
            ->where(['demand_notification.demand_id' => $demandId, 'demand_notification.group_id' => $groupId])
            ->first();
    }

    /**
     * @param $staffId
     * @return mixed
     */
    public function getTagByStaff($staffId)
    {
        $tag = \DB::table('m_staffs')
            ->leftJoin('cyzen_user_groups', 'cyzen_user_groups.user_id', '=', 'm_staffs.cyzen_user_id')
            ->leftJoin('cyzen_spot_tags', 'cyzen_spot_tags.group_id', '=', 'cyzen_user_groups.group_id')
            ->select('cyzen_spot_tags.spot_tag_id', 'cyzen_user_groups.group_id', 'm_staffs.cyzen_user_id')
            ->where('m_staffs.sp_user_id', '=', $staffId)->first();
        return ($tag) ? $tag : [];
    }
}
