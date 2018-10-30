<?php

namespace App\Http\Controllers\Agreement;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgreementAttachFileRequest;
use App\Http\Requests\AgreementSystemRequest;
use App\Models\MCorpCategoriesTemp;
use App\Services\AreaDialogService;
use App\Services\CategoryDialogService;
use App\Services\Logic\Step0Logic;
use App\Services\Logic\Step1Logic;
use App\Services\Logic\Step2Logic;
use App\Services\Logic\Step3Logic;
use App\Services\Logic\Step4Logic;
use App\Services\Logic\Step5Logic;
use App\Services\Logic\StepConfirmLogic;
use App\Services\MAddress1Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

class AgreementSystemController extends Controller
{

    /**
     * @var CategoryDialogService
     */
    protected $categoryDialogService;
    /**
     * @var MAddress1Service
     */
    protected $mAddress1Service;
    /**
     * @var AreaDialogService
     */
    protected $areaDialogService;

    /**
     * @var Step0Logic
     */
    protected $step0Logic;

    /**
     * @var Step1Logic
     */
    protected $step1Logic;

    /**
     * @var Step2Logic
     */
    protected $step2Logic;

    /**
     * @var Step3Logic
     */
    protected $step3Logic;

    /**
     * @var Step4Logic
     */
    protected $step4Logic;

    /**
     * @var Step5Logic
     */
    protected $step5Logic;

    /**
     * @var StepConfirmLogic
     */
    protected $stepConfirmLogic;

    /**
     * AgreementSystemController constructor.
     *
     * @param \App\Services\CategoryDialogService $categoryDialogService
     * @param \App\Services\MAddress1Service $mAddress1Service
     * @param \App\Services\AreaDialogService $areaDialogService
     * @param \App\Services\Logic\Step0Logic $step0Logic
     * @param \App\Services\Logic\Step1Logic $step1Logic
     * @param \App\Services\Logic\Step2Logic $step2Logic
     * @param \App\Services\Logic\Step3Logic $step3Logic
     * @param \App\Services\Logic\Step4Logic $step4Logic
     * @param \App\Services\Logic\Step5Logic $step5Logic
     * @param \App\Services\Logic\StepConfirmLogic $stepConfirmLogic
     */
    public function __construct(
        CategoryDialogService $categoryDialogService,
        MAddress1Service $mAddress1Service,
        AreaDialogService $areaDialogService,
        Step0Logic $step0Logic,
        Step1Logic $step1Logic,
        Step2Logic $step2Logic,
        Step3Logic $step3Logic,
        Step4Logic $step4Logic,
        Step5Logic $step5Logic,
        StepConfirmLogic $stepConfirmLogic
    ) {
        parent::__construct();
        $this->categoryDialogService = $categoryDialogService;
        $this->mAddress1Service = $mAddress1Service;
        $this->areaDialogService = $areaDialogService;
        $this->step0Logic = $step0Logic;
        $this->step1Logic = $step1Logic;
        $this->step2Logic = $step2Logic;
        $this->step3Logic = $step3Logic;
        $this->step4Logic = $step4Logic;
        $this->step5Logic = $step5Logic;
        $this->stepConfirmLogic = $stepConfirmLogic;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function getStep0()
    {
        $mCorp = \Request::get('mCorp');
        $corpId = $mCorp->id;
        try {
            $corpAgreementList = $this->step0Logic->getStep0($corpId, $this->getUser());
        } catch (\Exception $ex) {
            return redirect()->back()->with('errors', Lang::get('agreement_system.failed_update'));
        }
        return view('agreement.system.step0', compact('corpAgreementList'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postStep0Proceed()
    {
        $this->step0Logic->step0Process($this->getUser());
        return redirect()->route('agreementSystem.getStep1');
    }

    /**
     * initial step1
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStep1()
    {
        $currentStepName = Lang::get('agreement_system.step') . '[1] ' . Lang::get('progress.agreement_of_terms_conditions');
        $currentStep = 1;
        $mCorp = \Request::get('mCorp');
        $corpId = $mCorp->id;
        $result = $this->step1Logic->getStep1($corpId);
        $arrayProvision = $result['arrayProvision'];
        $corpAgreementId = $result['corpAgreementId'];
        return view('agreement.system.step1', compact('currentStep', 'currentStepName', 'arrayProvision', 'corpAgreementId'));
    }

    /**
     * execute step1
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postStep1Proceed()
    {
        $this->step1Logic->step1Process($this->getUser());
        return redirect()->route('agreementSystem.getStep2');
    }

    /**
     * Company information input screen - initial step 2
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStep2()
    {
        $currentStepName = trans('agreement_system.step') . '[2] ' . trans('progress.register_basic_information');
        $currentStep = 2;
        $mCorp = \Request::get('mCorp');
        $viewData = $this->step2Logic->getStep2($mCorp);
        $viewData ['currentStep'] = $currentStep;
        $viewData ['currentStepName'] = $currentStepName;
        $viewData ['mCorp'] = $mCorp;

        return view('agreement.system.step2', $viewData);
    }

    /**
     * Company information input screen - process step 2
     *
     * @param  AgreementSystemRequest $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function postStep2(AgreementSystemRequest $request)
    {
        try {
            $requestData = $this->step2Logic->convertRequestData($request->only('affiliationInfo', 'mCorp', 'holidays'));
            if ($this->step2Logic->updateData($requestData)) {
                $this->step2Logic->step2Process($this->getUser(), $requestData['mCorp']['corp_kind']);
                return redirect()->route('agreementSystem.getStep3');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Genre selection screen - initial step3
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStep3()
    {
        $currentStepName = Lang::get('agreement_system.step') . '[3] ' . Lang::get('progress.registering_available_compatible_genres_and_compatible_areas');
        $currentStep = 3;
        $corpId = \Request::get('mCorp')->id;

        $prefList = $this->step3Logic->getPrefList($corpId);
        $corpCategoryList = $this->step3Logic->getStep3($corpId);

        $viewData = [
            'currentStep' => $currentStep,
            'currentStepName' => $currentStepName,
            'prefList' => $prefList,
            'corpCategoryList' => $corpCategoryList];
        return view('agreement.system.step3', $viewData);
    }

    /**
     * Genre selection screen - process step3
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function postStep3()
    {
        try {
            $this->step3Logic->step3Process($this->getUser());
            return redirect()->route('agreementSystem.getStep4');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * initial step4
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStep4()
    {
        $currentStepName = Lang::get('agreement_system.step') . '[4] ' . Lang::get('progress.registering_available_compatible_genres_and_compatible_areas');
        $currentStep = 4;
        $corpId = \Request::get('mCorp')->id;
        $corpCategoryList = $this->step4Logic->getStep4($corpId);

        $viewData = [
            'currentStep' => $currentStep,
            'corpCategoryList' => $corpCategoryList,
            'currentStepName' => $currentStepName];
        return view('agreement.system.step4', $viewData);
    }

    /**
     * Genre selection screen - process step4
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postStep4()
    {
        $this->step4Logic->step4Process($this->getUser());
        return redirect()->route('agreementSystem.getStep5');
    }

    /**
     * initial step5
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStep5()
    {
        $currentStepName = Lang::get('agreement_system.step') . '[5] ' . Lang::get('progress.title_step5_document');
        $currentStep = 5;
        $mCorp = \Request::get('mCorp');
        $corpId = $mCorp->id;
        $result = $this->step5Logic->getStep5($corpId);
        $fileList = $result['listFile'];

        foreach($fileList as $file) {
            $file->imageUrl = route('agreementSystem.get.attachfile', ['attachFileId' => $file->id]);
        }

        $viewData = [
            'currentStep' => $currentStep,
            'currentStepName' => $currentStepName,
            'fileList' => $fileList,
            'companyKind' => $mCorp->corp_kind,
            'corpAgreementId' => $result['corpAgreementId']];
        return view('agreement.system.step5', $viewData);
    }

    /**
     * get list categories of affiliation in agreement
     *
     * @return string
     * @throws \Throwable
     */
    public function postStep5()
    {
        $mCorp = \Request::get('mCorp');
        $corpId = $mCorp->id;
        $result = $this->step5Logic->getStep5($corpId);
        if ($result['listFile']->isEmpty()) {
            Session::flash('missing_document_file', Lang::get('agreement_system.missing_file'));
            return redirect()->route('agreementSystem.getStep5');
        }
        $this->step5Logic->step5Process($this->getUser());
        return redirect()->route('agreementSystem.confirm');
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function getListCategoryDialog()
    {
        $corpId = \Request::get('mCorp')->id;
        $genreArray = $this->categoryDialogService->getListCategoryDialog($corpId);
        $genreGroupBase = $genreArray['genreGroupBase'];
        $genreGroupIntro = $genreArray['genreGroupIntro'];
        $genreGroupName = $genreArray['genreGroupName'];
        $genreNote = $this->categoryDialogService->getListGenreNote();
        $selectList = MCorpCategoriesTemp::SELECT_LIST;
        return view(
            'agreement.system.category_dialog_body',
            ['genreGroupBase' => $genreGroupBase,
                'genreGroupIntro' => $genreGroupIntro, 'genreGroupName' => $genreGroupName, 'genreNote' => $genreNote,
                'selectList' => $selectList]
        )->render();
    }

    /**
     * insert, update, delete these categories of affiliation in agreement
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postListCategoryDialog(Request $request)
    {
        $data = $request->all();
        $corpId = \Request::get('mCorp')->id;
        $this->categoryDialogService->postListCategoryDialog($data, $corpId);
        return redirect()->route('agreementSystem.getStep3');
    }

    /**
     * get list provisions of affiliation in agreement
     *
     * @return string
     * @throws \Throwable
     */
    public function getListAreaDialog()
    {
        $corpId = \Request::get('mCorp')->id;
        $addressAreaList = $this->mAddress1Service->getListArea($corpId);
        return view('agreement.system.area_dialog_body', ['addressAreaList' => $addressAreaList])->render();
    }

    /**
     * insert, update, delete these provisions of affiliation in agreement
     *
     * @param  Request $request
     * @return string
     * @throws \Throwable
     */
    public function postAreaDialog(Request $request)
    {
        $corpId = \Request::get('mCorp')->id;
        $addressCd = $request->input('addressCd');
        $address1 = $request->input('address1');
        $postList = $this->areaDialogService->getListPostDialog($corpId, $addressCd);
        $viewMode = true;
        return view('agreement.system.post_dialog_body', ['postList' => $postList, 'address1' => $address1, 'addressCd' => $addressCd, 'viewMode' => $viewMode])->render();
    }

    /**
     * get list districts of affiliation in agreement
     *
     * @param  Request $request
     * @return string
     * @throws \Throwable
     */
    public function postViewAreaDialog(Request $request)
    {
        $corpId = \Request::get('mCorp')->id;
        $addressCd = $request->input('addressCd');
        $address1 = $request->input('address1');
        $postList = $this->areaDialogService->getListPostDialog($corpId, $addressCd);
        $viewMode = false;
        return view('agreement.system.post_dialog_body', ['postList' => $postList, 'address1' => $address1, 'addressCd' => $addressCd, 'viewMode' => $viewMode])->render();
    }

    /**
     * insert, update, delete these districts of affiliation in agreement
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPostDialog(Request $request)
    {
        $data = $request->all();
        $corpId = \Request::get('mCorp')->id;
        $this->areaDialogService->postListPostDialog($data, $corpId);
        return redirect()->route('agreementSystem.getStep3');
    }

    /**
     * delete file
     *
     * @param  Request $request
     * @return $this
     */
    public function deleteFile(Request $request)
    {
        $agreementAttachedFileId = $request->get('agreementAttachedFileId');
        $corpId = \Request::get('mCorp')->id;
        $errors = $this->step5Logic->deleteFile($corpId, $agreementAttachedFileId);
        return $this->getStep5()->withErrors($errors);
    }

    /**
     * store file
     *
     * @param  AgreementAttachFileRequest $request
     * @return $this
     */
    public function uploadFile(AgreementAttachFileRequest $request)
    {
        $fileUpload = $request->file('fileUpload');
        $corpAgreementId = $request->get('corpAgreementId');
        $corpId = \Request::get('mCorp')->id;
        $errors = $this->step5Logic->uploadFile($corpId, $corpAgreementId, $fileUpload);
        return redirect()->route('agreementSystem.step5.get.fileUpload')->withErrors($errors);
    }

    /**
     * get file
     *
     * @param  Request $request
     * @return mixed
     */
    public function getThumbnail2(Request $request)
    {
        $agreementFileId = $request->get('agreementFileId');
        $fileAttach = $this->step5Logic->getThumbnail2($agreementFileId);
        $response = response()->make($fileAttach['content'], 200);
        $response->header("Content-Type", $fileAttach['contentType']);
        return $response;
    }

    public function getAttachFile(Request $request) {
        $attachFileId = $request->get('attachFileId');
        return $this->step5Logic->generateFile($attachFileId);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getConfirm()
    {
        $currentStepName = Lang::get('agreement_system.step') . '[7] ' . Lang::get('progress.title_step_confirm');
        $currentStep = 7;

        $mCorp = \Request::get('mCorp');

        $viewData = $this->stepConfirmLogic->getConfirm($mCorp);
        $viewData['mCorp'] = $mCorp;
        $viewData['currentStep'] = $currentStep;
        $viewData['currentStepName'] = $currentStepName;
        return view('agreement.system.confirm', $viewData);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postConfirm()
    {
        $corpAgreementId = $this->stepConfirmLogic->postConfirm($this->getUser());
        return redirect()->route('agreementSystem.getComplete', ['corpAgreementId' => $corpAgreementId]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getComplete()
    {
        return view('agreement.system.complete');
    }
}
