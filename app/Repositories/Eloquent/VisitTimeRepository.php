<?php

namespace App\Repositories\Eloquent;

use App\Repositories\VisitTimeRepositoryInterface;
use App\Models\VisitTime;

class VisitTimeRepository extends SingleKeyModelRepository implements VisitTimeRepositoryInterface
{
    /**
     * @var VisitTime
     */
    protected $model;

    /**
     * VisitTimeRepository constructor.
     *
     * @param VisitTime $model
     */
    public function __construct(VisitTime $model)
    {
        $this->model = $model;
    }

    /**
     * get blank model
     * @return \App\Models\Base|VisitTime|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new VisitTime();
    }

    /**
     * get list visit_time by demand id
     * @param integer $demandId
     * @param bool $first
     * @return array result
     */
    public function findAllByDemandId($demandId, $first = false)
    {
        $result = $this->model->where('demand_id', $demandId);
        if ($first) {
            return $result->first();
        }
        return $result->get()->toarray();
    }

    /**
     * get visit_time by VisitTime.id
     * @param integer $id
     * @return result collection
     */
    public function findById($id)
    {
        $query = $this->model
            ->from('visit_times AS VisitTime')
            ->select('*');

        if (empty($id)) {
            $query->whereNull('VisitTime.id');
        } else {
            $query->where('VisitTime.id', $id);
        }

        $result = $query->first();

        return $result;
    }

    /**
     * save data
     * @param array $data
     * @return mixed
     */
    public function saveMany($data)
    {
        return $this->model->insert($data);
    }

    /**
     * update multiple row visit_times
     * @param array $data
     * @return mixed|void
     */
    public function multipleUpdate($data)
    {
        foreach ($data as $value) {
            $this->model->where('id', $value['id'])->update($value);
        }
    }

    /**
     * Find all visit time with auction info
     * @param integer $demandId
     * @return mixed
     */
    public function findAllWithAuctionInfo($demandId)
    {
        $results = $this->model->leftJoin('auction_infos', 'auction_infos.visit_time_id', '=', 'visit_times.id')
            ->where('visit_times.demand_id', $demandId)
            ->select('visit_times.*', 'auction_infos.id as auction_info_id')
            ->orderBy('visit_times.visit_time')
            ->get();

        return $results;
    }

    /**
     * delete records from visit_times
     * @param array $ids
     * @return mixed
     */
    public function multipleDelete($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * find list visit_times by demand_id
     * @param integer $demandId
     * @return array|mixed
     */
    public function findListByDemandId($demandId)
    {
        $results = [];

        $data = \DB::table('visit_times as VisitTime')
                    ->leftjoin('auction_infos as AuctionInfo', 'VisitTime.id', '=', 'AuctionInfo.visit_time_id')
                    ->where('VisitTime.demand_id', $demandId)
                    ->orderBy('VisitTime.visit_time', 'asc')
                    ->select(['VisitTime.*', 'AuctionInfo.id as AuctionInfoId'])
                    ->get()->toArray();
        if (!empty($data)) {
            $data = collect($data)->toArray();
            $auctionInfoId = $data[0]->AuctionInfoId;
            unset($data[0]->AuctionInfoId);
            $results['VisitTime'] = collect($data[0])->toArray();
            $results['AuctionInfo'] = [
                "id" => $auctionInfoId
            ];
        }

        return $results;
    }
}
