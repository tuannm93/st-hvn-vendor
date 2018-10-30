<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandAttachedFile;
use App\Repositories\DemandAttachedFileRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DemandAttachedFileRepository extends SingleKeyModelRepository implements DemandAttachedFileRepositoryInterface
{
    /**
     * @var DemandAttachedFile
     */
    public $model;

    /**
     * DemandAttachedFileRepository constructor.
     *
     * @param DemandAttachedFile $model
     */
    public function __construct(DemandAttachedFile $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|DemandAttachedFile|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandAttachedFile();
    }

    /**
     * @param array $data
     * @return \App\Models\Base|bool|mixed
     */
    public function save($data = [])
    {
        return (new $this->model)->fill($data)->save();
    }

    /**
     * @return int
     */
    public function getNextIndex()
    {
        return (int)$this->model->max('id') + 1;
    }

    /**
     * @param array $data
     * @return \App\Models\Base|bool|mixed
     */
    public function create($data)
    {
        return (new $this->model)->create($data);
    }

    /**
     * @param integer $attachedId
     * @return mixed|static
     */
    public function findId($attachedId)
    {
        return $this->model->findOrFail($attachedId);
    }

    /**
     * @param integer $id
     * @return array
     */
    public function findById($id)
    {
        $fields = $this->getAllTableFieldsByAlias('demand_attached_files', 'DemandAttachedFiles');
        $query = $this->model
            ->from('demand_attached_files AS DemandAttachedFiles')
            ->where('DemandAttachedFiles.demand_id', $id)
            ->orderBy('DemandAttachedFiles.id', 'ASC')
            ->select($fields);

        $result = $query->get()->toArray();

        return $result;
    }
    /**
     * find attached file by demand ID
     *
     * @param  integer $demandId
     * @return collection
     */
    public function findByDemandId($demandId)
    {
        return $this->model->where('demand_id', $demandId)->orderBy('id', 'ASC')->get();
    }

    /**
     * Get file download
     * @param integer $id
     * @return mixed
     */
    public function getFileDownload($id)
    {
        $query = $this->model->from('demand_attached_files AS DemandAttachedFiles')
                             ->join('demand_infos AS DemandInfo', function ($join) {
                                 $join->on('DemandInfo.id', '=', 'DemandAttachedFiles.demand_id');
                             })
                             ->join('commission_infos AS CommissionInfo', function ($join) {
                                 $join->on('CommissionInfo.demand_id', '=', 'DemandInfo.id');
                             })
                             ->where('DemandAttachedFiles.id', $id);

        if (Auth::user()->auth == 'affiliation') {
            $query->where('CommissionInfo.corp_id', Auth::user()->affiliation_id)
                  ->where('CommissionInfo.commit_flg', 1);
        }

         $result = $query->select(
             'DemandAttachedFiles.path',
             'DemandAttachedFiles.name'
         )
                         ->first();

         return $result;
    }
}
