<?php

namespace App\Services;

use App\Repositories\ProgImportFilesRepositoryInterface;
use App\Repositories\ProgItemRepositoryInterface;

const PROGRESS_ITEM_ID = 1;


class ProgressManagementItemService
{
    /**
     * @var ProgItemRepositoryInterface
     */
    protected $progressItem;

    /**
     * @var ProgImportFilesRepositoryInterface
     */
    public $progImportFileRepo;

    /**
     * @var integer
     */
    public $filePaginate = 30; // 500
    /**
     * ProgressManagementItemService constructor.
     *
     * @param ProgItemRepositoryInterface $progressItem
     */
    public function __construct(
        ProgItemRepositoryInterface $progressItem,
        ProgImportFilesRepositoryInterface $progImportFileRepo
    ) {
        $this->progressItem = $progressItem;
        $this->progImportFileRepo = $progImportFileRepo;
    }

    /**
     * @return mixed
     */
    public function getProgressItem()
    {
        return $this->progressItem->findById(PROGRESS_ITEM_ID);
    }

    /**
     * @param $id
     * @param $data
     * @return boolean
     */
    public function updateItem($id, $data)
    {
        try {
            $item = $this->progressItem->findById($id);
            if ($item !== null) {
                $data['modified_user_id'] = \Auth::user()->user_id;
                $data['modified'] = \Carbon\Carbon::now();
                $this->progressItem->update($item, $data);
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getImportFileNotDelete()
    {
        return $this->progImportFileRepo->getImportFileNotDelete($this->filePaginate);
    }

    /**
     * delete prog file input (prog_import_files)
     * @param $fileId
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function deleteFile($fileId)
    {
        $message = trans('progress_management.index.fail_delete');
        $updated = $this->progImportFileRepo->updateDelete(
            $fileId,
            [
                'delete_flag' => 1,
                'modified_user_id' => auth()->user()->user_id,
            ]
        );
        if ($updated) {
            $message = trans('progress_management.index.success_delete');
        }

        return $message;
    }
}
