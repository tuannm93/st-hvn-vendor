<?php

namespace App\Http\Controllers\ProgressManagement;

use App\Http\Requests\DeleteCommissionInfoRequest;
use App\Http\Requests\ImportCommissionInfosRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ProgImportFilesRepositoryInterface;
use App\Services\ProgressManagementService;
use http\Exception;

class ProgressManagementCommissionController extends Controller
{
    /**
     * @var ProgImportFilesRepositoryInterface
     */
    protected $progImportFile;

    /**
     * @var ProgressManagementService
     */
    public $pMService;

    /**
     * ProgressManagementCommissionController constructor.
     *
     * @param ProgImportFilesRepositoryInterface $progImportFile
     * @param ProgressManagementService $pMService
     */
    public function __construct(
        ProgImportFilesRepositoryInterface $progImportFile,
        ProgressManagementService $pMService
    ) {
        parent::__construct();
        $this->progImportFile = $progImportFile;
        $this->pMService = $pMService;
    }

    /**
     * Get data to show info in delete commission screen based on fileId
     *
     * @param $fileId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDeleteCommissionInfos($fileId)
    {
        $title = trans('progress_management.delete_commission_infos.title');

        if (empty($fileId) || !ctype_digit($fileId)) {
            return redirect()->back();
        } else {
            $file = $this->progImportFile->find($fileId);
            return view('progress_management.delete_commission_infos', ['title' => $title, 'file' => $file]);
        }
    }

    /**
     * Action delete commission info related to fileId
     *
     * @param DeleteCommissionInfoRequest $request
     * @param $fileId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCommissionInfos(DeleteCommissionInfoRequest $request, $fileId)
    {
        // Get list id of prog_demand want to delete
        $delTarget = $request->get('delete_ids');

        if (empty($delTarget)) {
            $message = ['error' => trans('progress_management.delete_commission_infos.lack_input_message')];
        } else {
            $message = $this->pMService->deleteCommissionInfos($delTarget, $fileId);
        }

        return redirect()->back()->with($message)->withInput();
    }

    /**
     * get import_commission_infos data
     * @param integer $fileId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getImportCommissionInfos($fileId = null)
    {
        $progImportFile = $this->progImportFile->find($fileId);
        if (empty($fileId) || !ctype_digit($fileId) || !$progImportFile) {
            $errorMessage = trans('mcorp_list.exception');
            return view('partials.errors', compact('errorMessage'));
        }
        $lock = config('datacustom.lock');
        return view('progress_management.import_commission_infos', compact('progImportFile', 'lock'));
    }

    /**
     * Update import_commission_infos data
     *
     * @param ImportCommissionInfosRequest $request
     * @param $fileId
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Exception
     */
    public function postImportCommissionInfos(ImportCommissionInfosRequest $request, $fileId)
    {
        try {
            if ($this->pMService->postImportCommissionInfos($request->all(), $fileId)) {
                $request->session()->flash('box--success', trans('progress_management.import_commission_corp.insert_success'));
                return back()->with('oldValue', $request->all());
            } else {
                $request->session()->flash('box--error', trans('progress_management.import_commission_corp.insert_error'));
                return back()->with('oldValue', $request->all());
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
