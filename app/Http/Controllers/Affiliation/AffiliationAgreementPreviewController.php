<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Models\MCorp;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\NotCorrespondItemRepositoryInterface;
use App\Services\AddAgreementService;
use App\Services\Affiliation\AffiliationAgreementPreviewService;
use Illuminate\Http\Request;

class AffiliationAgreementPreviewController extends BaseController
{
    /**
     * @var AffiliationAgreementPreviewService
     */
    protected $affAgrPreviewService;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;
    /**
     * @var MCorpSubRepositoryInterface
     */
    protected $mCorpSubRepo;
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepo;
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepo;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var NotCorrespondItemRepositoryInterface
     */
    protected $notCorrespondItemRepository;

    /**
     * AffiliationAgreementPreviewController constructor.
     * @param AffiliationAgreementPreviewService $affAgrPreviewService
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MCorpSubRepositoryInterface $mCorpSubRepository
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param MPostRepositoryInterface $mPostRepo
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param NotCorrespondItemRepositoryInterface $notCorrespondItemRepository
     */
    public function __construct(
        AffiliationAgreementPreviewService $affAgrPreviewService,
        MCorpRepositoryInterface $mCorpRepository,
        MCorpSubRepositoryInterface $mCorpSubRepository,
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        MPostRepositoryInterface $mPostRepo,
        DemandInfoRepositoryInterface $demandInfoRepository,
        NotCorrespondItemRepositoryInterface $notCorrespondItemRepository
    ) {
        parent::__construct();
        $this->affAgrPreviewService = $affAgrPreviewService;
        $this->mCorpRepo = $mCorpRepository;
        $this->mCorpSubRepo = $mCorpSubRepository;
        $this->corpAgreementRepo = $corpAgreementRepository;
        $this->mPostRepo = $mPostRepo;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->notCorrespondItemRepository = $notCorrespondItemRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAgreementPreview(Request $request)
    {
        if (empty($request['corpId']) || empty($request['corpAgreementId'])) {
            $errorMessage = trans('mcorp_list.exception');
            return \view('partials.errors', compact('errorMessage'));
        }
        $corpId = $request['corpId'];
        $corpAgreementId = $request['corpAgreementId'];
        $mCorp = $this->mCorpRepo->getCorpData($corpId);
        $mCorpSubs = $this->mCorpSubRepo->getMCorpSubData($corpId);
        $corpAgreement = $this->corpAgreementRepo->findById($corpAgreementId);
        $corpAreas = $this->affAgrPreviewService->getCorpArea($corpId);
        $categories = $this->affAgrPreviewService->getCategories($corpId, $corpAgreementId);
        $checkListedKind = $this->affAgrPreviewService->checkListedKind($mCorp['listed_kind']);
        $checkCorpKind = AddAgreementService::checkCorpKind($mCorp['corp_kind']);
        $address1 = getDivTextJP('prefecture_div', $mCorp['address1']);
        $representative = getDivTextJP('prefecture_div', $mCorp['representative_address1']);
        $mobileTelType = getDropText(MCorp::MOBILE_TEL_TYPE, $mCorp['mobile_tel_type']);
        $coordination = getDropText(MCorp::COORDINATION_METHOD, $mCorp['coordination_method']);
        return view(
            'affiliation.agreement_preview',
            compact(
                'mCorp',
                'mCorpSubs',
                'corpAgreement',
                'corpAreas',
                'categories',
                'checkListedKind',
                'checkCorpKind',
                'address1',
                'representative',
                'mobileTelType',
                'coordination'
            )
        );
    }

    /**
     * search corp_target_target_areas data
     *
     * @param  $corpId
     * @param  $address1
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function searchCorpTargetArea($corpId, $address1)
    {
        $results = $this->mPostRepo->findByAddress1AndCorpId($corpId, $address1);
        return response()->json(\view('affiliation.component.corp_target_area', compact('results'))->render());
    }
}
