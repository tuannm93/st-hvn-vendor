<?php

namespace App\Http\Controllers\GeneralSearch;

use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GeneralSearch\GeneralSearchService;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MGeneralSearchRepositoryInterface;

class GeneralSearchController extends Controller
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var GeneralSearchService
     */
    protected $generalSearchService;
    /**
     * @var string
     */
    public $compMessage = '';
    /**
     * @var MSiteRepositoryInterface
     */
    public $mSiteRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    public $mGenreRepo;
    /**
     * @var MGeneralSearchRepositoryInterface
     */
    public $mGeneralSearchRepo;

    /**
     * construct function
     *
     * @param Request                           $request
     * @param GeneralSearchService              $generalSearchService
     * @param MSiteRepositoryInterface          $mSiteRepo
     * @param MGenresRepositoryInterface        $mGenreRepo
     * @param MGeneralSearchRepositoryInterface $mGeneralSearchRepo
     */
    public function __construct(
        Request $request,
        GeneralSearchService $generalSearchService,
        MSiteRepositoryInterface $mSiteRepo,
        MGenresRepositoryInterface $mGenreRepo,
        MGeneralSearchRepositoryInterface $mGeneralSearchRepo
    ) {
        parent::__construct();
        $this->request = $request;
        $this->generalSearchService = $generalSearchService;
        $this->mSiteRepo = $mSiteRepo;
        $this->mGenreRepo = $mGenreRepo;
        $this->mGeneralSearchRepo = $mGeneralSearchRepo;
    }

    /**
     * index genre
     *
     * @param  null $mGeneralId
     * @return view
     */
    public function index($mGeneralId = null)
    {
        $dataResults = '';
        if (!empty($mGeneralId)) {
            $selectedItem = $this->generalSearchService->searchGeneralSearch($mGeneralId);
            $dataResults = $this->generalSearchService->getDataGeneralSearch($mGeneralId);
        } else {
            $selectedItem = $this->generalSearchService->getDefaultSelectedItem();
        }
        $this->compMessage = $this->request->session()->get('datas@generalSearch');
        $this->request->session()->forget('datas@generalSearch');
        $functionList = $this->generalSearchService::FUNCTION_LIST;
        $mSiteList = $this->generalSearchService->getListSite();
        $mUserlist = $this->generalSearchService->getListUser();
        $permissionSaveDel = $this->generalSearchService->isEnabledDisplaySaveAndDel();
        $permissionBillInfo = $this->generalSearchService->isEnabledDisplayBillInfo();
        $functionListCase = $this->generalSearchService->getFunctionColumnList(GeneralSearchService::FUNCTION_CASE_MANAGEMENT);
        $functionAgencyManagement = $this->generalSearchService->getFunctionColumnList(GeneralSearchService::FUNCTION_AGENCY_MANAGEMENT);
        $functionChargeManagement = $this->generalSearchService->getFunctionColumnList(GeneralSearchService::FUNCTION_CHARGE_MANAGEMENT);
        $functionMemberManagement = $this->generalSearchService->getFunctionColumnList(GeneralSearchService::FUNCTION_MEMBER_MANAGEMENT);
        return view(
            'general_search.index',
            [
            "selectedItem" => $selectedItem,
            "functionList" => $functionList,
            "mSiteList" => $mSiteList,
            "mUserlist" => $mUserlist,
            "permissionSaveDel" => $permissionSaveDel,
            "permissionBillInfo" => $permissionBillInfo,
            "functionListCase" => $functionListCase,
            "functionAgencyManagement" => $functionAgencyManagement,
            "functionChargeManagement" => $functionChargeManagement,
            "functionMemberManagement" => $functionMemberManagement,
            'dataResults' => $dataResults,
            'siteLists' => $this->mSiteRepo->getList(),
            'genreLists' => $this->mGenreRepo->getList(true),
            ]
        );
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regist()
    {
        $data = $this->request->input('data');
        try {
            if (!empty($data['MGeneralSearch']['id'])) {
                $id = $this->generalSearchService->saveGeneralSearch($data);
            } else {
                $id = $this->generalSearchService->saveGeneralSearchAjax($data);
            }
            if (!is_null($id)) {
                $this->request->session()->flash('compMessage', trans('general_search.regist_is_complete'));
                return redirect()->route('general_search.index', $id);
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('errMessage', $e->getMessage());
        }
        return back();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getCsv()
    {
        $data = $this->request->input('data');
        $dataList = $this->generalSearchService->findGeneralSearchToCsv($data['MGeneralSearch']['id'], $data['GeneralSearchItem']['function_id']);
        $fieldList = $this->generalSearchService->csvFormat['default'];
        $fileName = trans('report_index.overall_search') . date('YmdHis');
        $exportService = new ExportService();
        return $exportService->exportCsv($fileName, $fieldList, $dataList);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete()
    {
        $data = $this->request->input('data');
        try {
            $this->generalSearchService->deleteGeneralSearchAll($data['MGeneralSearch']['id']);
            $this->request->session()->flash('compMessage', trans('general_search.deletion_is_complete'));
        } catch (\Exception $e) {
            $this->request->session()->flash('errMessage', $e->getMessage());
        }
        return redirect()->route('general_search.index');
    }
}
