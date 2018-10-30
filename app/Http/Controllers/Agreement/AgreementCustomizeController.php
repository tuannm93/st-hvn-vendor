<?php

namespace App\Http\Controllers\Agreement;

use App\Http\Controllers\Controller;
use App\Repositories\AgreementCustomizeRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\AgreementCustomizeService;
use App\Services\Logic\AgreementSystemLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class AgreementCustomizeController extends Controller
{
    /**
     * @var AgreementCustomizeRepositoryInterface
     */
    protected $agreementCustomize;
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var AgreementCustomizeService
     */
    protected $agreementCustomizeService;

    /**
     * AgreementCustomizeController constructor.
     * @param AgreementCustomizeRepositoryInterface $agreementCustomize
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param AgreementCustomizeService $agreementCustomizeService
     */
    public function __construct(
        AgreementCustomizeRepositoryInterface $agreementCustomize,
        AgreementSystemLogic $agreementSystemLogic,
        MCorpRepositoryInterface $mCorpRepository,
        AgreementCustomizeService $agreementCustomizeService
    ) {
        parent::__construct();
        $this->agreementCustomize = $agreementCustomize;
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->mCorpRepository = $mCorpRepository;
        $this->agreementCustomizeService = $agreementCustomizeService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAgreementCustomizePage()
    {
        return view('agreement_customize.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAllAgreementCustomize(Request $request)
    {
        return $this->agreementCustomizeService->getAllAgreementCustomize($request);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAgreementCustomize($id)
    {
        $this->agreementCustomizeService->deleteAgreementCustomize($id);
        $content = Lang::get('agreement_admin.content_delete_successfully');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function updateAgreementCustomize($id, Request $request)
    {
        $params = $request->all();
        $this->agreementCustomizeService->updateById($id, $params);
        $content = Lang::get('agreement_admin.editing_process_is_complete');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param $corpId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAgreementCustomizeWithCorp($corpId)
    {
        $mCorp = $this->mCorpRepository->getFirstById($corpId);
        $officialCorpName = $mCorp->official_corp_name;
        $arrayProvision = $this->agreementSystemLogic->findCustomizedAgreementByCorpId($corpId);
        return view('agreement_customize.corp.index', compact('arrayProvision', 'officialCorpName', 'corpId'));
    }

    /**
     * @param $corpId
     * @return string
     * @throws \Throwable
     */
    public function getAgreementCustomizeWithCorpViewData($corpId)
    {
        $arrayProvision = $this->agreementSystemLogic->findCustomizedAgreementByCorpId($corpId);
        return view('agreement_customize.corp.data', compact('arrayProvision'))->render();
    }

    /**
     * @param $corpId
     * @return mixed
     */
    public function getAgreementCustomizeProvisionsWithCorp($corpId)
    {
        return $this->agreementSystemLogic->findCustomizedAgreementByCorpId($corpId);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateAgreementCustomizeProvisionWithCorp(Request $request)
    {
        $content = $this->agreementCustomizeService->updateAgreementCustomizeProvisionWithCorp($request->all());
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param Request $request
     */
    public function updateAgreementCustomizeItemWithCorp(Request $request)
    {
        $content = $this->agreementCustomizeService->updateAgreementCustomizeItemWithCorp($request->all());
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteAgreementCustomizeProvisionWithCorp(Request $request)
    {
        $this->agreementCustomizeService->deleteAgreementCustomizeProvisionWithCorp($request->all());
        return $this->getMessageResponseSuccess(Lang::get('agreement_admin.content_delete_successfully'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteAgreementCustomizeItemWithCorp(Request $request)
    {
        $this->agreementCustomizeService->deleteAgreementCustomizeItemWithCorp($request->all());
        return $this->getMessageResponseSuccess(Lang::get('agreement_admin.content_delete_successfully'));
    }
}
