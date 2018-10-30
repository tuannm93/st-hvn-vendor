<?php


namespace App\Services\Logic;

use App\Models\AgreementAttachedFile;
use App\Models\CorpAgreement;
use App\Repositories\AgreementAttachedFileRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

class Step5Logic
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var AgreementAttachedFileRepositoryInterface
     */
    protected $attachedFileRepository;

    /**
     * Step5Logic constructor.
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param AgreementAttachedFileRepositoryInterface $attachedFileRepository
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        AgreementSystemLogic $agreementSystemLogic,
        AgreementAttachedFileRepositoryInterface $attachedFileRepository
    ) {
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->attachedFileRepository = $attachedFileRepository;
    }


    /**
     * @param integer $corpId
     * @return array
     */
    public function getStep5($corpId)
    {
        $listFile = $this->attachedFileRepository->getAllAgreementAttachedFileByCorpIdAndKind($corpId, AgreementAttachedFile::CERT);
        $corpAgreement = $this->corpAgreementRepository->findByCorpId($corpId);
        return ['listFile' => $listFile, 'corpAgreementId' => $corpAgreement->id];
    }

    /**
     * @param object $user
     */
    public function step5Process($user)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        if (!is_null($corpAgreement)) {
            if ($corpAgreement->status == CorpAgreement::STEP4) {
                $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, CorpAgreement::STEP5, $user);
            }
        }
    }

    /**
     * @param $corpId
     * @param $corpAgreementId
     * @param $fileUpload
     * @return MessageBag
     */
    public function uploadFile($corpId, $corpAgreementId, $fileUpload)
    {
        $agreementAttachFile = null;
        try {
            $agreementAttachFile = $this->createAgreementAttachFileObject($corpId, $corpAgreementId, $fileUpload);
            $agreementAttachFile = $this->attachedFileRepository->save($agreementAttachFile);

            $fullPath = $this->moveFileInFolder($fileUpload, $corpId, $agreementAttachFile->id);

            $agreementAttachFile->path = $fullPath;
            $this->attachedFileRepository->save($agreementAttachFile);
        } catch (\Exception $exception) {
            Log::error($exception);
            $this->attachedFileRepository->delete($agreementAttachFile);
            $errors = new MessageBag();
            $errors->add('upload_file_error', Lang::get('agreement_system.upload_file_error'));
            return $errors;
        }
    }

    /**
     * @param $corpId
     * @param $agreementAttachedFileId
     * @return MessageBag
     */
    public function deleteFile($corpId, $agreementAttachedFileId)
    {
        $errors = new MessageBag();
        try {
            if ($agreementAttachedFileId == null) {
                $errors->add('errors_selected', Lang::get('agreement_system.errors_selected'));
            } else {
                $agreementAttachedFile = $this->attachedFileRepository->find($agreementAttachedFileId);
                if ($agreementAttachedFile != null) {
                    $corpAgreementCompleteLatest = $this->agreementSystemLogic->findByCorpIdAndStatusCompleteAndNotNullAcceptationDate($corpId);
                    if ($corpAgreementCompleteLatest == null
                        || (strtotime($agreementAttachedFile->update_date) > strtotime($corpAgreementCompleteLatest->acceptation_date))) {
                        $this->attachedFileRepository->delete($agreementAttachedFile);
                        try {
                            unlink($this->generateFilePath($agreementAttachedFile));
                        } catch (\Exception $exception) {
                            Log::error($exception);
                            $errors->add('not_found_file', Lang::get('agreement_system.not_found_file'));
                        }
                    } else {
                        $errors->add('errors_file_delete', Lang::get('agreement_system.errors_file_delete'));
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            $errors->add('errors_system_error', Lang::get('agreement_system.errors_system_error'));
        }
        return $errors;
    }

    /**
     * @param $attachFile
     * @return string
     */
    private function generateFilePath($attachFile)
    {
        if (is_null($attachFile))
            return '';

        $elements = explode(".", $attachFile->name);
        $extension = strtolower($elements[1]);

        $rootPath = storage_path('upload/');
        $corpPath = $rootPath . $attachFile->corp_id . '/';
        if(!is_dir($corpPath)){
            mkdir($corpPath, 0777, true);
        }
        $pathFile = $attachFile->id . "." . $extension;
        return  $corpPath . $pathFile;
    }


    /**
     * @param $file
     * @param $corpId
     * @param $agreementAttachedFileId
     * @return string
     */
    private function moveFileInFolder($file, $corpId, $agreementAttachedFileId)
    {
        $fileName = $file->getClientOriginalName();
        $elements = explode(".", $fileName);
        $extension = strtolower($elements[1]);

        $rootPath = storage_path('upload/');
        $corpPath = $rootPath . '/' . $corpId;

        $fileNameCreate = $agreementAttachedFileId . "." . $extension;


        if (!is_dir($corpPath)){
            mkdir($corpPath, 0777, true);
        }
        $file->move($corpPath . '/', $fileNameCreate);

        return $corpPath . '/' . $fileNameCreate;
    }

    /**
     * @param $corpId
     * @param $corpAgreementId
     * @param $fileUpload
     * @return AgreementAttachedFile
     */
    private function createAgreementAttachFileObject($corpId, $corpAgreementId, $fileUpload)
    {
        $name = $fileUpload->getClientOriginalName();
        $contentType = $fileUpload->getClientMimeType();
        $agreementAttachFile = new AgreementAttachedFile();
        $agreementAttachFile->corp_id = $corpId;
        $agreementAttachFile->corp_agreement_id = $corpAgreementId;
        $agreementAttachFile->kind = AgreementAttachedFile::CERT;
        $agreementAttachFile->delete_flag = false;
        $agreementAttachFile->create_date = Carbon::now()->toDateTimeString();
        $agreementAttachFile->update_date = $agreementAttachFile->create_date;
        $agreementAttachFile->create_user_id = Auth::user()->id;
        $agreementAttachFile->update_user_id = $agreementAttachFile->create_user_id;
        $agreementAttachFile->name = $name;
        $agreementAttachFile->content_type = $contentType;
        return $agreementAttachFile;
    }

    /**
     * @param $agreementFileId
     * @return string
     */
    public function getThumbnail2($agreementFileId)
    {
        $agreementAttachFile = $this->attachedFileRepository->find($agreementFileId);
        $pathFile = $this->generateFilePath($agreementAttachFile);
        $fileAttach['content'] = Storage::get($pathFile);
        $fileAttach['contentType'] = $agreementAttachFile->content_type;


        return $fileAttach;
    }

    public function generateFile($attachFileId) {

        $attachFile = $this->attachedFileRepository->find($attachFileId);

        $path = $this->generateFilePath($attachFile);

        if ($path == '' || !File::exists($path)) {
            return null;
            //abort(404);
        }

        $file = File::get($path);

        $response = response()->make($file, 200);
        $response->header("Content-Type", $attachFile["content_type"]);

        return $response;
    }
}
