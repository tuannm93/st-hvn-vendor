<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Services\Affiliation\AffiliationGenreResigningService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AffiliationGenreResignController extends BaseController
{
    /**
     * @var AffiliationGenreResigningService
     */
    protected $affGenreResignService;

    /**
     * AffiliationGenreResignController constructor.
     * @param AffiliationGenreResigningService $affGenreResigningService
     */
    public function __construct(
        AffiliationGenreResigningService $affGenreResigningService
    ) {
        parent::__construct();
        $this->affGenreResignService = $affGenreResigningService;
    }

    /**
     * Index page
     *
     * @param $idCorp
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws NotFoundHttpException
     */
    public function index($idCorp)
    {
        if (empty($idCorp) || !ctype_digit($idCorp)) {
            throw new NotFoundHttpException();
        }
        $dataForView = $this->affGenreResignService->prepareDataForView($idCorp);
        return view('affiliation.genre_resigning', ['data' => $dataForView]);
    }

    /**
     * Update genre resign
     *
     * @param Request $request
     * @return array
     */
    public function updateGenreResign(Request $request)
    {
        $data = $this->prepareRequestData($request);
        $result = $this->affGenreResignService->updateGenreResign($data);
        return $result;
    }

    /**
     * Prepare request data
     *
     * @param Request $request
     * @return array
     */
    private function prepareRequestData(Request $request)
    {
        $listCategories = null;
        $listSelectedCategories = null;
        $idCorp = $request->get('idCorp');
        $commissionTypeCorp = $request->get('corpCommissionType');
        $sCategory = $request->get('listCorpCategoryTemp');
        if ($sCategory != null && strlen(trim($sCategory)) > 0) {
            $listCategories = json_decode($sCategory);
        }
        $sSelectedCategories = $request->get('checkedCategory');
        if ($sSelectedCategories != null && strlen(trim($sSelectedCategories)) > 0) {
            $listSelectedCategories = explode('-', $sSelectedCategories);
        }
        $data = [
            'idCorp' => $idCorp,
            'corpCommissionType' => $commissionTypeCorp,
            'listCategories' => $listCategories,
            'checkedCategory' => $listSelectedCategories
        ];
        return $data;
    }

    /**
     * Reconfirm contract
     *
     * @param Request $request
     * @return array
     */
    public function reconfirmContract(Request $request)
    {
        $kind = 'WEB';
        $isFax = (bool)$request->get('isFax');
        if ($isFax) {
            $kind = 'FAX';
        }
        $result = $this->affGenreResignService->reconfirmResign($request->get('idCorp'), $kind);
        return $result;
    }
}
