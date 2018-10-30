<?php

namespace App\Repositories\Eloquent;

use App\Models\ProgAddDemandInfo;
use App\Repositories\ProgAddDemandInfoRepositoryInterface;

class ProgAddDemandInfoRepository extends SingleKeyModelRepository implements ProgAddDemandInfoRepositoryInterface
{
    /**
     * @var ProgAddDemandInfo
     */
    protected $model;

    /**
     * ProgAddDemandInfoRepository constructor.
     *
     * @param ProgAddDemandInfo $model
     */
    public function __construct(ProgAddDemandInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param integer $pCorpId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getDataByProgCorpId($pCorpId)
    {
        return $this->model->where('prog_corp_id', $pCorpId)->orderBy('sequence', 'ASC')->get();
    }

    /**
     * @param integer $progAddId
     * @param array $data
     * @return bool|mixed
     */
    public function updateById($progAddId, $data)
    {
        return $this->model->where('id', $progAddId)->update($data);
    }

    /**
     * @param integer $idOrFileId
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|mixed|static[]
     */
    public function getCSVData($idOrFileId, $field)
    {
        $db = $this->model->join('prog_corps', 'prog_add_demand_infos.prog_corp_id', '=', 'prog_corps.id')
            ->join('m_corps', 'prog_corps.corp_id', '=', 'm_corps.id')
            ->with(['mCorp', 'progCorp'])
            ->orderBy('prog_corps.corp_id', 'ASC')
            ->orderBy('prog_corps.id', 'ASC')
            ->where($field, $idOrFileId)->get();
        return $db;
    }

    /**
     * @param $ids
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * @param $data
     * @return array
     */
    public function insertGetIds($data)
    {
        try {
            $lastId = $this->model->max('id');
            $this->model->insert($data);
            $ids = $this->model->select('id')->where('id', '>', $lastId)->get();
            $idList = [];
            foreach ($ids as $value) {
                $idList[] = $value->id;
            }

            return $idList;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * get by corp id and prog import file id
     *
     * @param  integer $corpId
     * @param  integer $fileId
     * @return array object
     */
    public function getByCorpIdAndProgImportFileId($corpId, $fileId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('prog_import_file_id', $fileId)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * delete by
     * @param  integer $progCorpId
     * @param  intege $progImportFileId
     * @param  string $modified
     * @param  array $litsIdNotDelete
     * @return boolean
     */
    public function deleteBy($progCorpId, $progImportFileId, $modified, $litsIdNotDelete)
    {
        return $this->model
            ->where('prog_corp_id', $progCorpId)
            ->where('prog_import_file_id', $progImportFileId)
            ->where('modified', '<', $modified)
            ->whereNotIn('id', $litsIdNotDelete)
            ->delete();
    }
}
