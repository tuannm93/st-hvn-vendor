<?php

namespace App\Services\Affiliation;

use App\Repositories\AgreementAttachedFileRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class AffiliationAgreementUploadService
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var AgreementAttachedFileRepositoryInterface
     */
    protected $agrAttachedFileRepository;

    /**
     * AffiliationService constructor.
     *
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param AgreementAttachedFileRepositoryInterface $agrAttachedFileRepository
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        AgreementAttachedFileRepositoryInterface $agrAttachedFileRepository
    ) {
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->agrAttachedFileRepository = $agrAttachedFileRepository;
    }

    /**
     * agreement upload file
     *
     * @param  object  $request
     * @param  integer $corpId
     * @param  integer $corpAgreementId
     * @return array
     * @throws Exception
     */
    public function agreementUploadFile($request, $corpId, $corpAgreementId = null)
    {
        DB::beginTransaction();
        try {
            $result['class'] = 'box--error';
            if ($request->has('upload_file_path')) {
                if (!$request->has('upload_file_path')) {
                    throw new Exception(Lang::get('agreement.file_does_not_exist'));
                }
                $files = $request->file('upload_file_path');
                $dataUnLink = [];
                foreach ($files as $key => $file) {
                    $fileName = $file->getClientOriginalName();
                    $fileExtension = $file->getClientOriginalExtension();
                    $fileTmpName = $file->getRealPath();
                    $fileType = $file->getMimeType();
                    $prefix = storage_path('upload/');
                    $saveDir = $prefix . $corpId . '/';
                    if (!$this->checkPermissionFileAndFolder($fileTmpName, $saveDir)) {
                        $result['message'] = Lang::get('agreement.error_while_processing');
                        return $result;
                    }
                    $dataAttachedFile = $this->formatInputDataAttachedFile(
                        $corpId,
                        $corpAgreementId,
                        $fileName,
                        $fileType
                    );
                    $resultInsert = $this->agrAttachedFileRepository->insertOrUpdateItem($dataAttachedFile);
                    if (!$resultInsert['status']) {
                        throw new Exception('error_while_processing');
                    }
                    $attachedFile = $resultInsert['item'];
                    $fileStorage = $attachedFile->id . '.' . $fileExtension;
                    $file->move($saveDir, $fileStorage);
                    $dataUpdateFile['path'] = $saveDir . $fileStorage;
                    $resultInsert = $this->agrAttachedFileRepository->insertOrUpdateItem(
                        $dataUpdateFile,
                        $attachedFile
                    );
                    $dataUnLink[] = $dataUpdateFile['path'];
                    if (!$resultInsert['status']) {
                        foreach ($dataUnLink as $key => $link) {
                            unlink($link);
                        }
                        $result['message'] = Lang::get('agreement.error_while_processing');
                        return $result;
                    }
                }
                $result['message'] = Lang::get('agreement.upload_file_success');
                $result['class'] = 'box--success';
            } else {
                $result = $this->deleteFile($result, $request, $corpId);
            }
            DB::commit();
            return $result;
        } catch (Exception $ex) {
            DB::rollback();
            throw new Exception();
        }
    }

    /**
     * delete file
     * @param  array $result
     * @param  object $request
     * @param  integer $corpId
     * @return array
     */
    public function deleteFile($result, $request, $corpId)
    {
        if ($request->has('file_id')) {
            $fileId = $request->input('file_id');
            $file = $this->agrAttachedFileRepository->findByCorpIdAndId($corpId, $fileId);
            if (!$file) {
                $result['message'] = Lang::get('agreement.file_does_not_exist');
                return $result;
            }

            $arr = explode('.', $file->name);
            $fileExtension = array_pop($arr);
            $fileName = $file->id . '.' . $fileExtension;
            $rootUploadFolder = storage_path('upload/');
            $filePath = $rootUploadFolder . $corpId . '/' . $fileName;
            if(!is_dir($filePath)){
                mkdir($filePath, 0777, true);
            }
            $fileCreateDate = strtotime($file->create_date);
            $agreement = $this->corpAgreementRepository->findByCorpIdAndStatus(
                $corpId,
                ['Complete', 'Application']
            );
            $agreementUpdateDate = empty($agreement->update_date) ? 0 : strtotime($agreement->acceptation_date);
            if (!Storage::exists($filePath)) {
                $result['message'] = Lang::get('agreement.file_does_not_exist');
                return $result;
            }
            if ($fileCreateDate < $agreementUpdateDate) {
                $result['message'] = Lang::get('agreement.do_not_delete_file');
                return $result;
            }
            if (!$file->delete()) {
                $result['message'] = Lang::get('agreement.file_does_not_exist');
                return $result;
            }
            if (!is_file($filePath)) {
                DB::commit();
                $result['message'] = Lang::get('agreement.file_does_not_exist');
                return $result;
            }
            if (!unlink($filePath)) {
                $result['message'] = Lang::get('agreement.file_does_not_exist');
                return $result;
            }
            $result['message'] = Lang::get('agreement.delete_file_success');
            $result['class'] = 'box--success';
            return $result;
        }
    }
    /**
     * check permission file and folder
     *
     * @param  string $fileName
     * @param  string $folderName
     * @return boolean
     */
    private function checkPermissionFileAndFolder($fileName = null, $folderName = null)
    {
        $result = true;
        if ($fileName) {
            if (!is_file($fileName)) {
                $result = false;
            }
            if (!is_readable($fileName)) {
                $result = false;
            }
        }
        if ($folderName) {
            if (!is_dir($folderName)) {
                if (!mkdir($folderName, 0755, true)) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * format input data attached file
     *
     * @param  integer $corpId
     * @param  integer $corpAgreementId
     * @param  string  $fileName
     * @param  string  $fileType
     * @return array
     */
    private function formatInputDataAttachedFile($corpId, $corpAgreementId, $fileName, $fileType)
    {
        $userId = Auth::user()['id'];
        $timeNow = date('Y-m-d H:i:s');
        $dataAttachedFile['corp_id'] = $corpId;
        $dataAttachedFile['corp_agreement_id'] = $corpAgreementId;
        $dataAttachedFile['kind'] = 'Cert';
        $dataAttachedFile['path'] = '';
        $dataAttachedFile['name'] = $fileName;
        $dataAttachedFile['content_type'] = $fileType;
        $dataAttachedFile['version_no'] = 1;
        $dataAttachedFile['create_date'] = $timeNow;
        $dataAttachedFile['create_user_id'] = $userId;
        $dataAttachedFile['update_date'] = $timeNow;
        $dataAttachedFile['update_user_id'] = $userId;

        return $dataAttachedFile;
    }
}
