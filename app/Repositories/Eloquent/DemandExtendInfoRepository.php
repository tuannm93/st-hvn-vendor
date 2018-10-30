<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandExtendInfo;
use App\Repositories\DemandExtendInfoRepositoryInterface;

class DemandExtendInfoRepository extends SingleKeyModelRepository implements DemandExtendInfoRepositoryInterface
{
    /**
     * @var DemandExtendInfo $model
     */
    protected $model;

    /**
     * DemandExtendInfoRepository constructor.
     * @param DemandExtendInfo $model
     */
    public function __construct(DemandExtendInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|DemandExtendInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandExtendInfo();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }


    /**
     * @param array $data
     * @return \App\Models\Base|DemandExtendInfo|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function updateOrCreate($data)
    {
        $demandExtendInfo = $this->model->where('demand_id', $data['demand_id'])->first();
        if (!empty($data['lng']) && !empty($data['lat'])) {
            $geographySQL = 'SELECT ST_GeographyFromText(\'Point(' . $data['lng'] . ' ' . $data['lat'] . ')\')';
        } else {
            $geographySQL = 'SELECT ST_GeographyFromText(\'Point(0 0)\')';
        }
        $geographyData = \DB::select($geographySQL);
        unset($data['lat']);
        unset($data['lng']);
        if (!isset($data['est_start_work']) || empty($data['est_start_work'])) {
            $data['est_start_work'] = null;
        }
        if (!isset($data['est_end_work']) || empty($data['est_end_work'])) {
            $data['est_end_work'] = null;
        }
        $data['location_demand'] = ($geographyData[0])->st_geographyfromtext;
        if ($demandExtendInfo) {
            $this->model->where('demand_id', $data['demand_id'])->update($data);
            return $demandExtendInfo;
        }
        return $this->model->create($data);
    }

    /**
     * @param $demandId
     * @return mixed
     */
    public function getAllByDemandId($demandId)
    {
        try {
            $query = $this->model->select(
                'id',
                'demand_id',
                'contact_time_from',
                'contact_time_to',
                'est_start_work',
                'est_end_work'
            )
                ->addSelect(\DB::raw('ST_X(location_demand::geometry) as lng'))
                ->addSelect(\DB::raw('ST_Y(location_demand::geometry) as lat'))
                ->where('demand_id', '=', $demandId)->first();
            if (!empty($query)) {
                return $query->toArray();
            }
            return [];
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param $id
     * @param $data
     */
    public function updateById($id, $data)
    {
        $this->model->find($id);
        $this->model->location_demand = 'ST_GeographyFromText(\'POINT(' . $data['lng'] . ' ' . $data['lat'] . ')\')';
        if ($data['est_start_work'] != null && $data['est_start_work'] != null) {
            $this->model->est_start_work = $data['est_start_work'];
            $this->model->est_end_work = $data['est_start_work'];
        } else {
            $this->model->contact_time_from = $data['contact_time_from'];
            $this->model->contact_time_to = $data['contact_time_to'];
        }
        $this->model->save();
    }
}
