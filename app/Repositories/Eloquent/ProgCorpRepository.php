<?php

namespace App\Repositories\Eloquent;

use App\Models\ProgCorp;
use App\Repositories\ProgCorpRepositoryInterface;
use App\Services\Log\ShellLogService;
use DB;

class ProgCorpRepository extends SingleKeyModelRepository implements ProgCorpRepositoryInterface
{
    /**
     * @var ProgCorp
     */
    protected $model;
    /**
     * @var ShellLogService
     */
    protected $logService;

    /**
     * ProgCorpRepository constructor.
     * @param ProgCorp $model
     * @param ShellLogService $logService
     */
    public function __construct(ProgCorp $model, ShellLogService $logService)
    {
        $this->model = $model;
        $this->logService = $logService;
    }

    /**
     * get ProgCorp with ProgAddDemandInfo, ProgImportFile, MCorp by progcorp id
     *
     * @param  integer $progCorpId progCorp id
     * @return ProgCorp|\Illuminate\Database\Eloquent\Builder
     */
    public function getProgcorpByIdWithRelationship($progCorpId)
    {
        return $this->model->with(['progAddDemandInfos', 'progImportFile', 'mCorp'])
            ->where('id', $progCorpId)
            ->orderBy('modified', 'DESC');
    }

    /**
     * @param integer $id
     * @return mixed|static
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @return mixed|static
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * get progcorp with holiday
     *
     * @param integer $fileId
     * @param  string $corpName
     * @return ProgCorp|\Illuminate\Database\Eloquent\Builder corpdata
     */
    public function getCorpWithHolidayByFileId($fileId, $corpName = '')
    {
        if (!empty($corpName)) {
            return $this->model->whereHas(
                'mCorp',
                function ($q) use ($corpName) {
                    $q->where(
                        function ($orWhere) use ($corpName) {
                            $orWhere->orWhere('corp_name', 'LIKE', '%' . $corpName . '%')
                                ->orWhere('official_corp_name', 'LIKE', '%' . $corpName . '%')
                                ->where('del_flg', 0);
                        }
                    );
                }
            )->where('prog_import_file_id', $fileId);
        }
        return $this->model->with('mCorp')->where('prog_import_file_id', $fileId);
    }

    /**
     * @param array $corpIds
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getHolidayByCorpId(array $corpIds)
    {
        return DB::table('m_corps')
            ->join('m_corp_subs', 'm_corp_subs.corp_id', '=', 'm_corps.id')
            ->join(
                'm_items',
                function ($join) {
                    $join->on('m_items.item_category', '=', 'm_corp_subs.item_category')
                        ->on('m_items.id', '=', 'm_corp_subs.item_id')
                        ->where('m_corp_subs.item_category', 'LIKE', '%休業日%');
                }
            )
            ->whereIn('m_corps.id', $corpIds)
            ->selectRaw('m_corps.id corp_id, string_agg(m_items.item_name, \',\') holidays')
            ->groupBy('m_corps.id')->get();
    }

    /**
     * get corp_id if exist
     *
     * @param integer $corpIds
     * @param integer $importFileId
     * @return mixed
     */
    public function findByMutilCorpIdAndFileId($corpIds, $importFileId)
    {
        return $this->model->select('id', 'corp_id')->whereIn('corp_id', $corpIds)->where('prog_import_file_id', $importFileId)->get();
    }

    /**
     * insert multi data and take multi id
     *
     * @param  array $data
     * @return mixed
     */
    public function insertGetIds($data)
    {
        return $this->model->insertGetId($data);
    }

    /**
     * @param integer $corpId
     * @return int|mixed
     */
    public function countProgCropForShowDialogBox($corpId)
    {
        $query = DB::table('prog_corps')
            ->join(
                DB::raw(
                    '(select id ' .
                    'from prog_import_files ' .
                    'where delete_flag = 0 and release_flag = 1 ' .
                    'order by id desc limit 1) AS ProgImportFile'
                ),
                'prog_corps.prog_import_file_id',
                '=',
                DB::raw('ProgImportFile.id')
            )
            ->where('prog_corps.corp_id', '=', $corpId)
            ->where('prog_corps.progress_flag', '=', 2);
        $count = $query->count();
        return $count;
    }

    /**
     * @author thaihv
     * @param  integer $pCorpId prog_corp_id
     * @param  array   $data    array field update
     * @return boolean          status update
     */
    public function updateProgressCorp($pCorpId, $data)
    {
        return $this->model->where('id', $pCorpId)->update($data);
    }

    /**
     * @author thaihv
     * @param  integer $pCorpId prog_corp_id
     * @return mixed
     */
    public function getDataWithMcorpAndDemandInfoById($pCorpId)
    {
        return $this->model->with(['mCorp', 'progDemandInfo'])->find($pCorpId);
    }

    /**
     * find first prog_corp id data
     *
     * @param  integer $corpId
     * @param  integer $fileId
     * @return mixed
     */
    public function findFirstByCorpIdAndFileId($corpId, $fileId)
    {
        return $this->model->select('id', 'mail_address', 'rev_mail_count')->where([
            ['corp_id', $corpId],
            ['prog_import_file_id', $fileId]
        ])->first();
    }

    /**
     * @param integer $corpId
     * @param integer $fileId
     * @param integer $progressFlag
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findProgCorpWithFlag($corpId, $fileId, $progressFlag)
    {
        return $this->model->select('id')->where(
            [
                ['corp_id', $corpId],
                ['prog_import_file_id', $fileId],
                ['progress_flag', $progressFlag]
            ]
        )->first();
    }

    /**
     * @param array $saveData
     * @return mixed
     * @throws \Exception
     */
    public function updateProgCorp($saveData)
    {
        $this->model->insert($saveData);
        return DB::table('prog_corps')->latest('id')->first();
    }
}
