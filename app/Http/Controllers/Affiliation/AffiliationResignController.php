<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Services\Affiliation\AffiliationResignService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AffiliationResignController extends BaseController
{
    /**
     * @var AffiliationResignService
     */
    protected $affiliationResignService;

    /**
     * AffiliationResignController constructor.
     *
     * @param AffiliationResignService $affResignService
     */
    public function __construct(AffiliationResignService $affResignService)
    {
        parent::__construct();
        $this->affiliationResignService = $affResignService;
    }

    /**
     * @param $idCorp
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws NotFoundHttpException
     */
    public function index($idCorp)
    {
        if (empty($idCorp) || !ctype_digit($idCorp)) {
            throw new NotFoundHttpException();
        }
        $dataForView = $this->affiliationResignService->prepareDataForView($idCorp);
        return view('affiliation.resigning', ['data' => $dataForView]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function updateInfoResign(Request $request)
    {
        return $this->affiliationResignService->updateResign($request);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function updateReconfirm(Request $request)
    {
        return $this->affiliationResignService->updateReconfirm($request);
    }
}
