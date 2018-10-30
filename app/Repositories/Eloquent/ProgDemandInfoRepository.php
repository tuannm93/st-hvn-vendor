<?php

namespace App\Repositories\Eloquent;

use App\Models\ProgDemandInfo;
use App\Repositories\ProgDemandInfoRepositoryInterface;
use App\Services\Log\ShellLogService;
use DB;

class ProgDemandInfoRepository extends SingleKeyModelRepository implements ProgDemandInfoRepositoryInterface
{
    /**
     * @var ProgDemandInfo
     */
    protected $model;
    /**
     * @var ShellLogService
     */
    protected $logService;
    /**
     * ProgDemandInfoRepository constructor.
     *
     * @param ProgDemandInfo $model
     */
    public function __construct(ProgDemandInfo $model, ShellLogService $logService)
    {
        $this->model = $model;
        $this->logService = $logService;
    }

    /**
     * @return \App\Models\Base|ProgDemandInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new ProgDemandInfo();
    }

    /**
     * @param integer $arrayCommissionId
     * @param integer $fileId
     * @return boolean|mixed|null
     * @throws \Exception
     */
    public function delProgDemand($arrayCommissionId, $fileId)
    {
        return $this->model->where('prog_import_file_id', $fileId)
            ->whereIn('commission_id', $arrayCommissionId)
            ->delete();
    }

    /**
     * @param integer $progCorpId
     * @return $this
     */
    public function getProgDemandInfoByProgCorpId($progCorpId)
    {
        return $this->model->with(
            [
                'progCorp.progImportFile',
                'progCorp.mCorp'
            ]
        )->where('prog_corp_id', $progCorpId);
    }

    /**
     * update progress demand info
     *
     * @author thaihv
     * @param  integer $id   demand info id
     * @param  array   $data demand info data
     * @return boolean       result of update db status
     */
    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * find progdemand info by id
     *
     * @author thaihv
     * @param  integer $id demand info id
     * @return Eloquent       instance of
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * insert progdemand info tmp
     *
     * @author thaihv
     * @param  array $data demand info data
     * @return boolean instance of
     */
    public function insertTMP($data)
    {
        return DB::table('prog_demand_info_tmps')->insert($data);
    }

    /**
     * find prog_demand_infos data
     *
     * @param  array   $commissionInfo
     * @param  integer $fileId
     * @return mixed
     */
    public function findByMulticondition($commissionInfo, $fileId)
    {
        return $this->model->where(
            [
                ['corp_id', $commissionInfo['corp_id']],
                ['commission_id', $commissionInfo['commission_infos_id']],
                ['prog_import_file_id', $fileId]
            ]
        )->first();
    }


    /**
     * @param integer $idOrFileId
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|mixed|static[]
     */
    public function getCSVData($idOrFileId, $field)
    {
        $db = $this->model->join('prog_corps', 'prog_demand_infos.prog_corp_id', '=', 'prog_corps.id')
            ->join('m_corps', 'prog_corps.corp_id', '=', 'm_corps.id')
            ->with(['mCorp', 'progCorp'])
            ->orderBy('prog_corps.corp_id', 'ASC')
            ->orderBy('prog_corps.id', 'ASC')
            ->orderBy('prog_demand_infos.receive_datetime', 'ASC')
            ->where($field, $idOrFileId)->get();
        return $db;
    }

    /**
     * @param integer $progDemandId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null|static|static[]
     */
    public function findWithCommissionById($progDemandId)
    {
        return $this->model->with('commissionInfo')->find($progDemandId);
    }

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * find by data tmp
     *
     * @param array $item
     * @param integer $corpId
     * @param  integer $fileId
     * @return integer
     */
    public function findByDataTmp($item, $corpId, $fileId)
    {
        return $this->model
            ->where('id', $item['id'])
            ->where('demand_id', $item['demand_id'])
            ->where('commission_id', $item['commission_id'])
            ->where('corp_id', $corpId)
            ->where('prog_import_file_id', $fileId)
            ->first();
    }

    /**
     * get by corp id and prog import file id
     *
     * @param  integer $corpId
     * @param  integer $progImportFileId
     * @return array object
     */
    public function getByCorpIdAndProgImportFileId($corpId, $progImportFileId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('prog_import_file_id', $progImportFileId)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * @param integer $progCorpId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function findByProgCorpId($progCorpId)
    {
        return $this->model
            ->where('prog_corp_id', $progCorpId)
            ->orderBy('receive_datetime', 'asc')
            ->paginate(config('datacustom.limit_demand_detail'));
    }

    /**
     * @param array $saveData
     * @return mixed|void
     * @throws \Exception
     */
    public function updateProgDemandInfo($saveData)
    {
        $this->model->insert($saveData);
    }
}
