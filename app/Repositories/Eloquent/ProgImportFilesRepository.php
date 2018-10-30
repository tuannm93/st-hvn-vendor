<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ProgImportFilesRepositoryInterface;
use App\Models\ProgImportFile;
use App\Services\Log\ShellLogService;
use Illuminate\Support\Facades\DB;

class ProgImportFilesRepository extends SingleKeyModelRepository implements ProgImportFilesRepositoryInterface
{
    /**
     * @var ProgImportFile
     */
    protected $model;
    /**
     * @var ShellLogService
     */
    protected $logService;

    /**
     * ProgImportFilesRepository constructor.
     * @param ProgImportFile $model
     * @param ShellLogService $logService
     */
    public function __construct(ProgImportFile $model, ShellLogService $logService)
    {
        $this->model = $model;
        $this->logService = $logService;
    }

    /**
     * @return \App\Models\Base|ProgImportFile|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new ProgImportFile();
    }

    /**
     * check exist row in database
     *
     * @author thaihv
     * @param  integer $id id of prog import file
     * @return boolean
     */
    public function findById($id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        return $this->model->where('delete_flag', 0)->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getImportFileReleased()
    {
        $query = $this->model->select('id')
            ->where('delete_flag', '=', 0)
            ->where('release_flag', '=', 1)
            ->orderBy('id', 'desc')
            ->limit(1);
        return $query->first();
    }
    /**
     * paginate import file not delete
     *
     * @author thaihv
     * @param  integer $paginate number of page
     * @return collection           list files
     */
    public function getImportFileNotDelete($paginate = 100)
    {
        return $this->model->where('delete_flag', 0)->orderBy('id', 'DESC')->paginate($paginate);
    }

    /**
     * @param integer $id
     * @param array $data
     * @return bool|mixed
     */
    public function updateDelete($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * check exist row in database
     *
     * @author thaihv
     * @param  integer $id id of prog import file
     * @return boolean
     */
    public function findNotDeleteById($id)
    {
        if (!is_numeric($id)) {
            return null;
        }
        return $this->model->where('delete_flag', 0)->find($id);
    }

    /**
     * find by id and release flag
     * @return object
     */
    public function findItemReleaseFlagLastest()
    {
        return $this->model
            ->where('delete_flag', 0)
            ->where('release_flag', 1)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @param array $saveData
     * @return mixed
     * @throws \Exception
     */
    public function updateProgImportFile($saveData)
    {
        $this->model->insert($saveData);
        return DB::table('prog_import_files')->latest('id')->first();
    }
}
