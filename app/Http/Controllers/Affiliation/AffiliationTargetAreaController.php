<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Services\Affiliation\AffiliationInfoService;
use App\Services\Affiliation\AffiliationTargetService;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use Illuminate\Http\Request;
use Exception;

class AffiliationTargetAreaController extends BaseController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var AffiliationTargetService
     */
    private $affiliationTargetService;
    /**
     * @var AffiliationInfoService
     */
    private $affiliationInfoService;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    private $mCorpCategoryRepo;
    /**
     * @var MPostRepositoryInterface
     */
    private $mPostRepo;
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    private $mCorpTargetAreaRepo;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    private $mTargetAreaRepo;

    /**
     * AffiliationTargetAreaController constructor.
     * @param Request $request
     * @param AffiliationTargetService $affiliationTargetService
     * @param AffiliationInfoService $affiliationInfoService
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepo
     * @param MPostRepositoryInterface $mPostRepo
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepo
     * @param MTargetAreaRepositoryInterface $mTargetAreaRepo
     */
    public function __construct(
        Request $request,
        AffiliationTargetService $affiliationTargetService,
        AffiliationInfoService $affiliationInfoService,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepo,
        MPostRepositoryInterface $mPostRepo,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepo,
        MTargetAreaRepositoryInterface $mTargetAreaRepo
    ) {
        parent::__construct();
        $this->request = $request;
        $this->affiliationTargetService = $affiliationTargetService;
        $this->affiliationInfoService = $affiliationInfoService;
        $this->mCorpCategoryRepo = $mCorpCategoryRepo;
        $this->mPostRepo = $mPostRepo;
        $this->mCorpTargetAreaRepo = $mCorpTargetAreaRepo;
        $this->mTargetAreaRepo = $mTargetAreaRepo;
    }

    /**
     * Target area
     *
     * @param null $corpCategoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function targetArea($corpCategoryId = null)
    {
        if ($corpCategoryId == null) {
            return back();
        }
        try {
            if (!ctype_digit($corpCategoryId)) {
                abort(404);
            }
            $prefList = $this->affiliationTargetService->getPrefList($corpCategoryId);
            $dataGenreAndCategory = $this->mCorpCategoryRepo->getListForGenreAndCategoryByCorpId($corpCategoryId);
            $lastModified = $this->mTargetAreaRepo->getTargetAreaLastModified($corpCategoryId);
            return view(
                'affiliation.targetarea',
                [
                'prefList' => $prefList,
                'dataGenreAndCategory' => $dataGenreAndCategory,
                'corpId' => $corpCategoryId,
                'lastModified' => $lastModified
                ]
            );
        } catch (Exception $e) {
            abort(404);
        }
    }

    /**
     * Target area register
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function targetAreaRegist(Request $request)
    {
        $corpCategoryId = $request->input('corp_category_id');
        $dataRequest = $request->all();
        if (!ctype_digit($dataRequest['corp_id'])) {
            abort(404);
        }
        $this->mPostRepo->registTargetArea($corpCategoryId, $dataRequest);
        $defaultJisCds = $this->mCorpTargetAreaRepo->getJscByCorpId($dataRequest['corp_id']);
        $defaultCount = count($defaultJisCds);
        $targetAreaCount = $this->mTargetAreaRepo->getCorpCategoryTargetAreaCount($corpCategoryId);
        if ($defaultCount === $targetAreaCount) {
            $countHasDefault = count($this->mTargetAreaRepo->countHasJisCdsOfCorpCategory($corpCategoryId, $defaultJisCds));
            if ($defaultCount === $countHasDefault) {
                $resultsFlg = $this->mCorpCategoryRepo->editCorpCategoryTargetAreaType($corpCategoryId, 1);
            } else {
                $resultsFlg = $this->mCorpCategoryRepo->editCorpCategoryTargetAreaType($corpCategoryId, 2);
            }
        } else {
            $resultsFlg = $this->mCorpCategoryRepo->editCorpCategoryTargetAreaType($corpCategoryId, 2);
        }
        if ($resultsFlg == true) {
            $request->session()->flash('Update', trans('aff_corptargetarea.update'));
        } else {
            $request->session()->flash('InputError', trans('aff_corptargetarea.input_error'));
        }
        return back();
    }

    /**
     * return view corp target area
     * @param null $id
     * @param null $initPref
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws Exception
     */
    public function corpTargetArea($id = null, $initPref = null)
    {
        if ($id == null || !ctype_digit($id)) {
            return back();
        }

        $data = $this->request->all();
        $result = $this->affiliationInfoService->getCorpTargetArea($id, $data, $initPref);

        return view('affiliation.corptargetarea', $result);
    }
}
