<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AddAgreementRequest;
use App\Http\Requests\AgreementUploadFileFormRequest;
use App\Repositories\AgreementAttachedFileRepositoryInterface;
use App\Repositories\AntisocialCheckRepositoryInterface;
use App\Repositories\AutoCommissionCorpRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\AddAgreementService;
use App\Services\Affiliation\AffiliationSearchService;
use App\Services\AffiliationService;
use App\Services\Logic\StepConfirmLogic;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\MessageBag;
use Illuminate\View\View;

class AffiliationController extends BaseController
{
    /**
     * @var AgreementAttachedFileRepositoryInterface
     */
    private $agrAttachedFileRepository;

    /**
     * @var AntisocialCheckRepositoryInterface
     */
    private $antisocialCheckRepository;

    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    private $autoCommissionCorpRepository;

    /**
     * @var AffiliationSearchService
     */
    private $affiliationSearchService;

    /**
     * @var AffiliationService
     */
    private $affiliationService;

    /**
     * @var AddAgreementService
     */
    private $addAgreementService;

    /**
     * @var CorpAgreementRepositoryInterface
     */
    private $corpAgreementRepository;

    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepository;

    /**
     * @var StepConfirmLogic
     */
    private $stepConfirmLogic;

    /**
     * AffiliationController constructor.
     * @param AgreementAttachedFileRepositoryInterface $agrAttachedFileRepository
     * @param AntisocialCheckRepositoryInterface $antisocialCheckRepository
     * @param AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepository
     * @param AffiliationSearchService $affiliationSearchService
     * @param AffiliationService $affiliationService
     * @param AddAgreementService $addAgreementService
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     * @param StepConfirmLogic $stepConfirmLogic
     */
    public function __construct(
        AgreementAttachedFileRepositoryInterface $agrAttachedFileRepository,
        AntisocialCheckRepositoryInterface $antisocialCheckRepository,
        AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepository,
        AffiliationSearchService $affiliationSearchService,
        AffiliationService $affiliationService,
        AddAgreementService $addAgreementService,
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        StepConfirmLogic $stepConfirmLogic
    ) {
        parent::__construct();
        $this->agrAttachedFileRepository = $agrAttachedFileRepository;
        $this->antisocialCheckRepository = $antisocialCheckRepository;
        $this->autoCommissionCorpRepository = $autoCommissionCorpRepository;
        $this->affiliationSearchService = $affiliationSearchService;
        $this->affiliationService = $affiliationService;
        $this->addAgreementService = $addAgreementService;
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->stepConfirmLogic = $stepConfirmLogic;
    }

    /**
     * Index page
     *
     * @param null $phoneNumber
     * @param null $corpStatus
     * @return Factory|View
     */
    public function index($phoneNumber = null, $corpStatus = null)
    {
        $dataView = $this->affiliationSearchService->prepareDataForViewAffiliation();
        $instantSearch = -1;
        if (session()->has(__('affiliation.KEY_SESSION_FOR_AFFILIATION_SEARCH'))) {
            $instantSearch = 1;
            session()->forget(__('affiliation.KEY_SESSION_FOR_AFFILIATION_SEARCH'));
        }

        $tel1 = '';

        if (session()->has(__('datas@AffiliationSearch'))) {
            $data = session()->get('datas@AffiliationSearch');
            $tel1 = $data["tel1"];
            $instantSearch = 1;
            session()->forget(__('datas@AffiliationSearch'));
        }
        $dataView['phoneNumber'] = '';
        $dataView['showTableData'] = false;
        $dataView['corpJoinStatus'] = 1;
        if (!is_null($phoneNumber) && strlen(trim($phoneNumber)) > 0
            && !is_null($corpStatus) && strlen(trim($corpStatus)) > 0
        ) {
            $dataView['phoneNumber'] = $phoneNumber;
            $dataView['corpJoinStatus'] = $corpStatus;
        }
        return view(
            'affiliation.index',
            [
                'data' => $dataView,
                'instantSearch' => $instantSearch,
                'tel1' => $tel1
            ]
        );
    }

    /**
     * Get view add_agreement function
     *
     * @param null $corpId
     * @return Factory|View
     */
    public function getAddAgreement($corpId = null)
    {
        $mCorp = $this->mCorpRepository->getFirstById($corpId);
        $checkCorpId = false;
        if (!$mCorp) {
            $checkCorpId = true;
        }
        $checkCorpKind = $this->addAgreementService->checkCorpKind($mCorp['corp_kind']);
        $checkDisableFlg = $this->addAgreementService->checkDisableFlg(Auth::user()->auth);
        return view('affiliation.add_agreement', compact('mCorp', 'checkCorpKind', 'checkDisableFlg', 'checkCorpId'));
    }

    /**
     * Check and insert data
     *
     * @param AddAgreementRequest $request
     * @param integer|null $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function postAddAgreement(AddAgreementRequest $request, $id = null)
    {
        $userId = Auth::user()['user_id'];
        $corpId = $request->corp_id;
        $corpAgreement['kind'] = $request->kind;
        $corpAgreement['corp_id'] = $corpId;
        $corpAgreement['status'] = 'NotSigned';
        $corpAgreement['agreement_history_id'] = 1;
        $corpAgreement['ticket_no'] = 1;
        $corpAgreement['corp_kind'] = $request->corp_kind;
        $corpAgreement['create_date'] = date('Y-m-d H:i:s');
        $corpAgreement['create_user_id'] = Auth::user()['id'];
        $corpAgreement['update_date'] = date('Y-m-d H:i:s');
        $corpAgreement['update_user_id'] = Auth::user()['id'];
        if (isset($request->agreement_date)) {
            $corpAgreement['agreement_date'] = $request->agreement_date;
        }
        if (isset($request->agreement_flag)) {
            $corpAgreement['agreement_flag'] = $request->agreement_flag ? true : false;
        }
        if (isset($request->hansha_check)) {
            $corpAgreement['hansha_check'] = $request->hansha_check;
            $corpAgreement['hansha_check_user_id'] = $userId;
            $corpAgreement['hansha_check_date'] = date('Y-m-d H:i:s');
        }
        if (isset($request->transactions_law) && $request->transactions_law == 1) {
            $corpAgreement['transactions_law_user_id'] = $userId;
            $corpAgreement['transactions_law_date'] = date('Y-m-d H:i:s');
        }
        if (isset($request->acceptation) && $request->acceptation == 1) {
            $corpAgreement['status'] = 'Complete';
            $corpAgreement['acceptation_user_id'] = $userId;
            $corpAgreement['acceptation_date'] = date('Y-m-d H:i:s');
        }
        if ($this->addAgreementService->addAgreement($corpId, $corpAgreement)) {
            $request->session()->flash('box--success', trans('add_agreement.success'));
        } else {
            $request->session()->flash('box--error', trans('add_agreement.error'));
        }
        return redirect()->route('affiliation.agreement.index', $id);
    }

    /**
     * Detail agreement
     *
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return view
     */
    public function agreement($corpId, $agreementId = null)
    {
        $statusMsg = $this->corpAgreementRepository->getStatusMessage();
        $corpData = $this->mCorpRepository->find($corpId);
        $corpAgreementCnt = $this->corpAgreementRepository->getCountByCorpIdAndStatus($corpId);
        if (!empty($agreementId)) {
            $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId(
                $corpId,
                $agreementId
            );
        } else {
            $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId($corpId, null, true);
        }
        if (!$corpData) {
            return view('affiliation.agreement');
        }
        $corpAgreementList = $this->corpAgreementRepository->getAllByCorpId($corpId);
        $agreementAttachedFileCert = $this->agrAttachedFileRepository->getAllAgreementAttachedFileByCorpIdAndKind(
            $corpId,
            'Cert'
        );
        $lastAntisocialCheck = $this->antisocialCheckRepository->findHistoryByCorpId($corpId, 'first');
        $antisocialCheck = $this->antisocialCheckRepository->getResultList();
        $agreementProvisions = AffiliationService::getAgreementProvisions($corpAgreement);
        $role = Auth::user()['auth'];
        $isRoleAffiliation = AffiliationService::isRole($role, ['affiliation']);
        $disableFlg = !AffiliationService::isRole($role, ['system', 'admin']);
        $isRoleSystem = AffiliationService::isRole($role, ['system']);
        $isReportDownloadUrl = AffiliationService::getUrlDownloadByRoleAndStatusCorpAgreement(
            Auth::user()['auth'],
            $corpAgreement,
            $corpId
        );
        return view(
            'affiliation.agreement',
            compact(
                'corpData',
                'corpAgreement',
                'agreementAttachedFileCert',
                'agreementProvisions',
                'statusMsg',
                'corpAgreementCnt',
                'corpAgreementList',
                'lastAntisocialCheck',
                'isReportDownloadUrl',
                'role',
                'disableFlg',
                'antisocialCheck',
                'isRoleAffiliation',
                'isRoleSystem'
            )
        );
    }

    /**
     * Update agreement
     * @param  Request $request
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return view
     */
    public function agreementUpdate(Request $request, $corpId, $agreementId = null)
    {
        $data = $request->all();
        try {
            $result = $this->affiliationService->agreementUpdate($data, $corpId, $agreementId);
            $request->session()->flash($result['class'], $result['message']);
            return redirect()->route(
                'affiliation.agreement.index',
                ['corpId' => $corpId, 'agreementId' => $agreementId]
            );
        } catch (Exception $ex) {
            $request->session()->flash('box--error', __('agreement.update_error'));
            return redirect()->route(
                'affiliation.agreement.index',
                ['corpId' => $corpId, 'agreementId' => $agreementId]
            );
        }
    }

    /**
     * Check auto commission
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function checkAutoCommission(Request $request)
    {
        try {
            if (!$request->isMethod('get')) {
                throw new Exception();
            }

            $data = $request->all();
            $areaCorpListCnt = $this->mCorpCategoryRepository->getCountAreaCorpListByCorpId($data['corp_id']);
            $autoCommissionCorpListCnt = $this->autoCommissionCorpRepository->countByCorpId($data['corp_id']);
            $result = true;
            if ($areaCorpListCnt != $autoCommissionCorpListCnt) {
                $result = false;
            }
            return response()->json($result);
        } catch (Exception $ex) {
            return response()->json(false);
        }
    }

    /**
     * Update reconfirmation
     *
     * @param  Request $request
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return view
     */
    public function updateReconfirmation(Request $request, $corpId, $agreementId = null)
    {
        try {
            $result = $this->affiliationService->updateReconfirmation($corpId);
            $request->session()->flash($result['class'], $result['message']);
            return redirect()->route(
                'affiliation.agreement.index',
                ['corpId' => $corpId, 'agreementId' => $agreementId ? $agreementId : $result['agreement_id']]
            );
        } catch (Exception $ex) {
            $request->session()->flash('box--error', __('agreement.update_error'));
            return redirect()->route('affiliation.agreement.index', ['corpId' => $corpId]);
        }
    }

    /**
     * Agreement upload file
     *
     * @param  AgreementUploadFileFormRequest $request
     * @param  integer $corpId
     * @param  integer $corpAgreementId
     * @return view
     */
    public function agreementUploadFile(AgreementUploadFileFormRequest $request, $corpId, $corpAgreementId = null)
    {
        try {
            $result = $this->affiliationService->agreementUploadFile($request, $corpId, $corpAgreementId);
            $request->session()->flash($result['class'], $result['message']);
            return redirect()->route(
                'affiliation.agreement.index',
                ['corpId' => $corpId, 'agreementId' => $corpAgreementId]
            );
        } catch (Exception $ex) {
            $request->session()->flash('box--error', __('agreement.error_while_processing'));
            return redirect()->route('affiliation.agreement.index', ['corpId' => $corpId]);
        }
    }

    /**
     * Show page download agreement  file
     *
     * @param  integer $fileId
     * @return view
     */
    public function downloadAgreementFile($fileId)
    {
        $file = $this->agrAttachedFileRepository->findById($fileId);
        $path = str_replace(storage_path('upload/'), "", $file->path);
        return redirect()->route(
            'download.index',
            ['target' => base64_encode($path), 'filename' => base64_encode($file->name)]
        );
    }

    /**
     * @param $corpId
     * @param $agreementId
     * @return $this|Factory|View|string
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function downloadAgreementReport($corpId, $agreementId)
    {
        $role = Auth::user()['auth'];
        $isRoleSystemOrAdmin = AffiliationService::isRole($role, ['system', 'admin']);
        if (!$isRoleSystemOrAdmin) {
            $messageBag = new MessageBag(['do_not_allow' => Lang::get('agreement_system.do_not_allow')]);
            return redirect()->route('affiliation.agreement.index', ['corpId' => $corpId, 'agreementId' => $agreementId])->withErrors($messageBag);
        }

        $file = $this->stepConfirmLogic->downloadAgreementReport($corpId, $agreementId);
        return $file;
    }

    /**
     * Search affiliation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        try {
            $listResult = $this->affiliationSearchService->searchCorpByCondition($request);
        } catch (\Exception $exception) {
            $errorMessage = trans('mcorp_list.exception');
            return \response()->json($errorMessage);
        } catch (\Throwable $e) {
            $errorMessage = trans('mcorp_list.exception');
            return \response()->json($errorMessage);
        }
        return response()->view('affiliation.components.affiliation_table', ['result' => $listResult]);
    }

    /**
     * Download CSV affiliation
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadCSVAffiliation(Request $request)
    {
        $file = $this->affiliationSearchService->getDataDownloadAffiliationCSV($request);
        if (!is_null($file)) {
            return $file->download('csv');
        }
        return redirect()->route('affiliation.index');
    }
}
