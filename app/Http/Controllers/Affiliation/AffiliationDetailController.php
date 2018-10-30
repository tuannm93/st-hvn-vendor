<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AffiliationAddRequest;
use App\Repositories\AffiliationCorrespondsRepositoryInterface;
use App\Repositories\AffiliationStatsRepositoryInterface;
use App\Repositories\AffiliationSubsRepositoryInterface;
use App\Repositories\AntisocialCheckRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\ReputationCheckRepositoryInterface;
use App\Services\Affiliation\AffiliationDetailService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliationDetailController extends BaseController
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var AntisocialCheckRepositoryInterface
     */
    protected $antisocialCheckRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;
    /**
     * @var AffiliationSubsRepositoryInterface
     */
    protected $affiliationSubsRepository;
    /**
     * @var AffiliationStatsRepositoryInterface
     */
    protected $affiliationStatsRepository;
    /**
     * @var AffiliationCorrespondsRepositoryInterface
     */
    protected $affCorrespondRepository;
    /**
     * @var ReputationCheckRepositoryInterface
     */
    protected $reputationCheckRepository;
    /**
     * @var AffiliationDetailService
     */
    private $affiliationDetailService;

    /**
     * AffiliationDetailController constructor.
     *
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param AntisocialCheckRepositoryInterface $antisocialCheckRepository
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param AffiliationSubsRepositoryInterface $affiliationSubsRepository
     * @param AffiliationStatsRepositoryInterface $affiliationStatsRepository
     * @param AffiliationCorrespondsRepositoryInterface $affCorrespondRepository
     * @param ReputationCheckRepositoryInterface $reputationCheckRepository
     * @param AffiliationDetailService $affiliationDetailService
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        MCorpRepositoryInterface $mCorpRepository,
        AntisocialCheckRepositoryInterface $antisocialCheckRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        AffiliationSubsRepositoryInterface $affiliationSubsRepository,
        AffiliationStatsRepositoryInterface $affiliationStatsRepository,
        AffiliationCorrespondsRepositoryInterface $affCorrespondRepository,
        ReputationCheckRepositoryInterface $reputationCheckRepository,
        AffiliationDetailService $affiliationDetailService
    ) {
        parent::__construct();
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->antisocialCheckRepository = $antisocialCheckRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->affiliationSubsRepository = $affiliationSubsRepository;
        $this->affiliationStatsRepository = $affiliationStatsRepository;
        $this->affCorrespondRepository = $affCorrespondRepository;
        $this->reputationCheckRepository = $reputationCheckRepository;
        $this->affiliationDetailService = $affiliationDetailService;
    }

    /**
     * Get raw data and return create view of affiliation
     *
     * @return Factory|View
     */
    public function create()
    {
        $dataCreate = $this->affiliationDetailService->getDataSendDetail();

        // Acquisition of Contract STOP category
        $stopCategoryList = $this->mCategoryRepository->getStopCategoryList(null);

        // Acquisition of affiliated store incidental information
        $stopCategory = $this->affiliationSubsRepository->getAffiliationSubsList(null);

        $dataCreate = array_merge([
            'stopCategoryList' => $stopCategoryList,
            'stopCategory' => $stopCategory
        ], $dataCreate);
        return view('affiliation.create', $dataCreate);
    }

    /**
     * Get data and return edit view of affiliation
     *
     * @param null $id
     * @return Factory|View
     */
    public function detail($id)
    {
        if (!ctype_digit($id)) {
            return redirect()->route('affiliation.detail.create');
        }

        // Acquisition of company information
        $mCorp = $this->mCorpRepository->getDataAffiliationById($id);

        if (empty($mCorp)) {
            return redirect()->route('affiliation.detail.create');
        }

        $dataCreate = $this->affiliationDetailService->getDataSendDetail();

        // Acquisition of company master incidental information
        $mCorpSubList = $this->affiliationDetailService->getMCorpSubList($id);

        // Acquisition of statistical information by franchise store genre
        $affiliationStatsList = $this->affiliationStatsRepository->getAffiliationStatsList($id);

        // Search conditions for franchise store correspondence history data
        // Acquisition of member store correspondence history data
        $conditions = ['affiliation_corresponds.corp_id' => $id];
        $historyData = $this->affCorrespondRepository->getAffiliationCorrespond($conditions);

        // Acquisition of rumor check history
        $reputationChecks = $this->reputationCheckRepository->findHistoryByCorpId($id, 'all');

        // Acquisition of contract for each company (corp_agreement)
        $corpAgreement = $this->corpAgreementRepository->findByCorpId($id);

        $antisocialChecks = $this->antisocialCheckRepository->findHistoryByCorpId($id, 'all');

        $creditLimitData = $this->affiliationDetailService->getCreditLimit($mCorp);

        // Acquisition of Contract STOP category
        $stopCategoryList = $this->mCategoryRepository->getStopCategoryList($id);

        // Acquisition of affiliated store incidental information
        $stopCategory = $this->affiliationSubsRepository->getAffiliationSubsList($id);

        $dataUpdate = [
            'mCorp' => $mCorp,
            'holidayChecked' => $mCorpSubList['holiday'],
            'developmentResponseChecked' => $mCorpSubList['development_response'],
            'affiliationStatsList' => $affiliationStatsList,
            'historyData' => $historyData,
            'reputationChecks' => $reputationChecks,
            'corpAgreement' => $corpAgreement,
            'antisocialChecks' => $antisocialChecks,
            'creditLimitData' => $creditLimitData,
            'stopCategoryList' => $stopCategoryList,
            'stopCategory' => $stopCategory,
        ];

        $dataUpdate = array_merge($dataCreate, $dataUpdate);

        return view('affiliation.detail', $dataUpdate);
    }

    /**
     * Insert affiliation detail
     *
     * @param AffiliationAddRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function postDetail(AffiliationAddRequest $request)
    {
        $data = $request->all()['data'];
        if (isset($data['affiliation_infos']['reg_pdf_path'])) {
            $checkFileName = $this->affiliationDetailService->checkFileName($data['affiliation_infos']['reg_pdf_path']);
            if (!$checkFileName) {
                return redirect()->back()->with([
                    'danger' => __('affiliation_detail.save_fail'),
                    'pdf_mess' => __('affiliation.pdf_invalid_max_char')
                ])->withInput();
            }
        };
        $lastId = $this->affiliationDetailService->createAffiliationDetail($data);
        if ($lastId) {
            return redirect()->route('affiliation.detail.edit', $lastId)->with(
                'success',
                __('affiliation_detail.create_success')
            );
        } else {
            return redirect()->back()->with('danger', __('affiliation_detail.save_fail'))->withInput();
        }
    }

    /**
     * Update affiliation detail
     *
     * @param AffiliationAddRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function updateDetail(AffiliationAddRequest $request, $id)
    {
        $data = $request->all()['data'];
        if (isset($data['affiliation_infos']['attention'])) {
            $data['affiliation_infos']['attention'] = str_replace("\r\n", "\n", $data['affiliation_infos']['attention']);
        }
        if (isset($data['affiliation_infos']['reg_pdf_path'])) {
            $checkFileName = $this->affiliationDetailService->checkFileName($data['affiliation_infos']['reg_pdf_path']);
            if (!$checkFileName) {
                return redirect()->back()->with([
                    'danger' => __('affiliation_detail.save_fail'),
                    'pdf_mess' => __('affiliation.pdf_invalid_max_char')
                ])->withInput();
            }
        };

        $lastId = $this->affiliationDetailService->updateAffiliationDetail($data, $id);
        if ($lastId) {
            return redirect()->route('affiliation.detail.edit', $lastId)->with(
                'success',
                __('affiliation_detail.create_success')
            );
        } else {
            return redirect()->back()->with('danger', __('affiliation_detail.save_fail'))->withInput();
        }
    }

    /**
     * Delete affiliation detail
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDetail($id)
    {
        $result = $this->affiliationDetailService->deleteSoftMcorp($id);
        if ($result['status'] == 200) {
            return redirect()->route('affiliation.index');
        } else {
            return redirect()->back()->with('danger', $result['message']);
        }
    }

    /**
     * Set session for back search of affiliation
     *
     * @return boolean[]
     */
    public function setSessionForBackAffiliationSearch()
    {
        session([__('affiliation.KEY_SESSION_FOR_AFFILIATION_SEARCH') => true]);
        return ['result' => true];
    }

    /**
     * Get affiliation history
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAffiliationHistoryInput($id)
    {
        $data = $this->affCorrespondRepository->find($id);
        return response()->json($data);
    }

    /**
     * Create affiliation history
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAffiliationHistoryInput(Request $request, $id)
    {
        try {
            $data = [];
            $data = $request->all();
            if (!empty($data['data_history']['responders'])
                && strlen($data['data_history']['corresponding_contens']) <= 1000
                && !empty($data['data_history']['correspond_datetime'])) {
                $this->affCorrespondRepository->updateAffiliationCorrespondWithId($id, $data['data_history']);
            }
        } catch (Exception $e) {
            logger(__METHOD__ . "Error: " . $e->getMessage());
        }
        return redirect()->route('affiliation.detail.edit', $data['affiliation_id']);
    }
}
