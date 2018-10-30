<?php

namespace App\Http\Controllers\Agreement;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgreementProvisionItemRequest;
use App\Http\Requests\AgreementProvisionRequest;
use App\Repositories\AgreementRevisionLogRepositoryInterface;
use Illuminate\Http\Request;
use App\Repositories\AgreementRepositoryInterface;
use App\Services\AgreementProvisionService;
use App\Services\AgreementRevisionLogService;
use Illuminate\Support\Facades\Lang;

class AgreementProvisionsController extends Controller
{

    /**
     * @var AgreementRevisionLogRepositoryInterface
     */
    protected $agreementRevisionLogRepository;
    /**
     * @var AgreementProvisionService
     */
    protected $agreementProvisionService;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreementRepository;
    /**
     * @var AgreementRevisionLogService
     */
    protected $agreementRevisionLogService;

    /**
     * AgreementProvisionsController constructor.
     *
     * @param AgreementProvisionService               $agreementProvisionService
     * @param AgreementRepositoryInterface            $agreementRepository
     * @param AgreementRevisionLogRepositoryInterface $agreementRevisionLogRepository
     * @param AgreementRevisionLogService             $agreementRevisionLogService
     */
    public function __construct(
        AgreementProvisionService $agreementProvisionService,
        AgreementRepositoryInterface $agreementRepository,
        AgreementRevisionLogRepositoryInterface $agreementRevisionLogRepository,
        AgreementRevisionLogService $agreementRevisionLogService
    ) {
        parent::__construct();
        $this->agreementProvisionService = $agreementProvisionService;
        $this->agreementRepository = $agreementRepository;
        $this->agreementRevisionLogRepository = $agreementRevisionLogRepository;
        $this->agreementRevisionLogService = $agreementRevisionLogService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAgreementProvisions()
    {
        $agreementProvisions = $this->agreementProvisionService->getAgreementProvisionAndItemData();
        return view('agreement_provisions.index', compact('agreementProvisions'));
    }

    /**
     * Get all provisions and id to show in insert provision-item popup
     *
     * @return provisions for insert provision-item
     */
    public function getAgreementProvisionData()
    {
        return $this->agreementProvisionService->getAgreementProvisionData();
    }

    /**
     * Render data and html form of provisions and provisions-item of index file
     *
     * @return html form within data in response
     * @throws \Throwable
     */
    public function getViewData()
    {
        $agreementProvisions = $this->agreementProvisionService->getAgreementProvisionAndItemData();
        return view('agreement_provisions.agreement_provision_data', compact('agreementProvisions'))->render();
    }

    /**
     * Function for insert/update provision
     * Write log into agreement_provisions_edit_logs
     *
     * @param  AgreementProvisionRequest $request
     * @return message
     */
    public function postAgreementProvision(AgreementProvisionRequest $request)
    {
        $provision = $request->input('agreementProvision');
        $provision['sort_no'] = mb_convert_kana($provision['sort_no'], "KVa");
        $agreement = $this->agreementRepository->getFirstAgreement();
        return $this->getMessageResponseSuccess($this->agreementProvisionService->saveAgreementProvision($provision, $agreement));
    }

    /**
     * Function for insert/update provision-item
     * Write log into agreement_provisions_edit_logs
     *
     * @param  AgreementProvisionItemRequest $request
     * @return message
     */
    public function postAgreementProvisionItem(AgreementProvisionItemRequest $request)
    {
        $provisionItem = $request->input('agreementProvisionItem');
        $provisionItem['sort_no'] = mb_convert_kana($provisionItem['sort_no'], "KVa");
        return $this->getMessageResponseSuccess($this->agreementProvisionService->saveAgreementProvisionItem($provisionItem));
    }

    /**
     * Function for delete item of provision
     * Write log into agreement_provisions_edit_logs
     *
     * @param  $id
     * @return message
     */
    public function deleteItem($id)
    {
        $this->agreementProvisionService->deleteItem($id);
        $content = Lang::get('agreement_admin.the_item_deletion_processing_is_completed');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * Function for delete provision and items of provision
     *
     * @param  $id
     * @return mixed
     */
    public function deleteProvision($id)
    {
        $this->agreementProvisionService->deleteProvision($id);
        $content = Lang::get('agreement_admin.the_text_deleting_processing_is_completed');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContractTermsRevisionHistoryView()
    {
        return view('agreement_provisions.contract_terms_revision_history');
    }

    /**
     * Get data of datatable with search, sort, paging condition
     *
     * @param  Request $request
     * @return json data for datatable form
     */
    public function getContractTermsRevisionHistoryData(Request $request)
    {
        return $this->agreementRevisionLogService->getContractTermsRevisionHistoryData($request);
    }

    /**
     * Get detail data of each line include: content, id, time, update user
     *
     * @param  $id
     * @return json data
     */
    public function getContractTermsRevisionHistoryDetail($id)
    {
        return $this->agreementRevisionLogRepository->findByIdJoinWithMUser($id);
    }

    /**
     * Version up data of provision and provision-item
     * Insert new record into agreement_revision_logs with content is all data of provisions and items
     *
     * @return message
     */
    public function versionUp()
    {
        $this->agreementProvisionService->versionUp($this->agreementProvisionService->getAgreementProvisionAndItemData());
        $content = Lang::get('agreement_admin.version_up_complete');
        return $this->getMessageResponseSuccess($content);
    }
}
