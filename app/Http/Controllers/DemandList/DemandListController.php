<?php

namespace App\Http\Controllers\DemandList;

use App\Http\Controllers\Controller;
use App\Http\Requests\Demand\DemandAttachedFileRequest;
use App\Http\Requests\DemandListRequest;
use App\Models\DemandInfo;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\Eloquent\MItemRepository;
use App\Services\Cyzen\CyzenDemandServices;
use App\Services\Demand\BusinessService;
use App\Services\Demand\DemandExtendInfoService;
use App\Services\Demand\DemandInfoService;
use App\Services\Demand\DemandNotificationService;
use App\Services\DemandListDetailService;
use App\Services\ExportService;
use App\Services\MItemService;
use App\Services\UploadFile\UploadFile;
use Auth;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Http\Request;

class DemandListController extends Controller
{
    /**
     * @const NON_NOTIFICATION
     */
    const NON_NOTIFICATION = '非通知';
    /**
     *
     * @var $siteIdEnable
     */
    public static $siteIdEnable = [861, 863, 889, 890, 1312, 1313, 1314];
    /**
     * @var boolean
     */
    public $defaultDisplay = false;
    /**
     * @var DemandInfoRepositoryInterface
     */
    public $demandinfoRepository;
    /**
     * @var DemandInfoService
     */
    public $demandInfoService;
    /**
     * @var MItemService
     */
    public $mItemService;
    /**
     * @var array
     */
    public $additionalSearchParams = [];
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var BusinessService
     */
    protected $demandService;
    /**
     * @var DemandListDetailService
     */
    protected $demandListDetailService;
    /**
     * @var ExportService
     */
    protected $exportService;

    /**
     * @var CyzenDemandServices $cyzenDemandServices
     */
    protected $cyzenDemandServices;

    /**
     * @var DemandExtendInfoService $demandExtendInfoService
     */
    protected $demandExtendInfoService;

    /**
     * @var DemandNotificationService $demandNotificationService
     */
    protected $demandNotificationService;

    /**
     * DemandListController constructor.
     * @param Request $request
     * @param DemandInfoRepositoryInterface $demandinfoRepository
     * @param DemandInfoService $demandInfoService
     * @param MItemService $mItemService
     * @param BusinessService $demandService
     * @param ExportService $exportService
     * @param DemandListDetailService $demandListDetailService
     * @param CyzenDemandServices $cyzenDemandServices
     * @param DemandExtendInfoService $demandExtendInfoService
     */
    public function __construct(
        Request $request,
        DemandInfoRepositoryInterface $demandinfoRepository,
        DemandInfoService $demandInfoService,
        MItemService $mItemService,
        BusinessService $demandService,
        ExportService $exportService,
        DemandListDetailService $demandListDetailService,
        CyzenDemandServices $cyzenDemandServices,
        DemandExtendInfoService $demandExtendInfoService,
        DemandNotificationService $demandNotificationService
    ) {
        parent::__construct();
        $this->request = $request;
        $this->demandinfoRepository = $demandinfoRepository;
        $this->demandInfoService = $demandInfoService;
        $this->mItemService = $mItemService;
        $this->demandService = $demandService;
        $this->exportService = $exportService;
        $this->demandListDetailService = $demandListDetailService;
        $this->cyzenDemandServices = $cyzenDemandServices;
        $this->demandExtendInfoService = $demandExtendInfoService;
        $this->demandNotificationService = $demandNotificationService;
    }

    /**
     * index function
     *
     * @param  Request $request
     * @return view
     */
    public function index(Request $request, $id = null, $bCheck = false)
    {
        $this->defaultDisplay = true;
        $data = $request->query();
        if ($request->isMethod('get')) {
            if (!empty($data['cti'])) {
                $sessionKeyForAffiliationSearch = self::$sessionKeyForAffiliationSearch;
                $sessionKeyForDemandSearch = self::$sessionKeyForDemandSearch;
                return $this->demandInfoService->checkAffiliationResults(
                    $request,
                    $data,
                    $sessionKeyForAffiliationSearch,
                    $sessionKeyForDemandSearch
                );
            } elseif (!empty($data['customer_tel'])) {
                $request->session()->put(self::$sessionKeyForDemandSearch, $data);
                $this->search($id, $bCheck);
            }
        }
        return $this->search($id, $bCheck);
    }

    /**
     * search demand function
     * @param null $id
     * @param bool $bCheck
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search($id = null, $bCheck = false)
    {
        $data = [];
        $triggerSearch = false;
        if (!empty($id)) {
            $triggerSearch = true;
            $listCorp = $this->getMCorp($id);
            if (!empty($listCorp)) {
                $this->request->merge(
                    [
                        'corp_name' => $listCorp['corp_name'],
                        'corp_name_kana' => $listCorp['corp_name_kana']
                    ]
                );
            }
            $data = $this->demandListPost();
        }
        try {
            if ($this->request->isMethod('get')) {
                $data = $this->demandListGet();
            }
        } catch (\Exception $e) {
            abort('404');
        }
        $this->setParameterSession(); //set parameter session
        // update b check
        $data = $this->unifyBCheck($data, $bCheck);
        $checkParam  = $this->checkParamIsset($data);
        if ($checkParam == true) {
            $triggerSearch = true;
        }
        $demandInfos = $this->demandinfoRepository->getDemandInfo($data);
        return view('demand_list.index', [
            "defaultDisplay" => $this->defaultDisplay,
            "demandInfos" => $demandInfos,
            "auth" => Auth::user()->auth,
            'siteLists' => $this->demandListDetailService->mSiteRepo->getList(),
            'itemLists' => $this->mItemService->prepareDataList($this->demandListDetailService->mItemRepo->getListByCategoryItem(MItemRepository::PROPOSAL_STATUS)),
            'conditions' => $data,
            'triggerSearch' => $triggerSearch
        ]);
    }

    /**
     * @param DemandListRequest $demandrequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchDemand(DemandListRequest $demandrequest)
    {
        $showCSV = false;
        try {
            if ($demandrequest->isMethod('post')) {
                $dataRequest = $this->request->all();
                if (!empty($dataRequest['data']['corp_id']) && $dataRequest['data']['corp_id'] <= config('constant.max_integer')) {
                    $listCorp = $this->getMCorp($dataRequest['data']['corp_id']);
                    if (!empty($listCorp)) {
                        $dataRequest['data']['corp_name'] = $listCorp['corp_name'];
                        $dataRequest['data']['corp_name_kana'] = $listCorp['corp_name_kana'];
                        $this->request->merge($dataRequest);
                    }
                }
                $data = $this->demandListPost();
            }
        } catch (\Exception $e) {
            abort('404');
        }
        if ($demandrequest->input('submit-csv') != '' && $demandrequest->input('submit-csv') == 'csv' && (Auth::user()->auth == 'system'
                || Auth::user()->auth == 'admin' || Auth::user()->auth == 'accounting_admin')
        ) {
            $dataList = [];

            if ($data['id'] <= config('constant.max_integer') && $data['corp_id'] <= config('constant.max_integer')) {
                $dataList = $this->demandInfoService->convertDataCsv($this->demandinfoRepository->getDataCsv($data));
            }

            $fileName = trans('demandlist.csv_demand_name') . '_' . Auth::user()->user_id;
            $fieldList = DemandInfo::csvFormat();

            return $this->exportService->exportCsv($fileName, $fieldList['default'], $dataList);
        } else {
            $demandInfos = [];

            if ($data['id'] <= config('constant.max_integer') && $data['corp_id'] <= config('constant.max_integer')) {
                $demandInfos = $this->demandinfoRepository->getDemandInfo($data);
            }
            if ((isset($data['b_check']) && $data['b_check'] != 1 && $data['b_check'] != 'on') || !isset($data['b_check'])) {
                $showCSV = true;
            }
            if ($demandrequest->ajax()) {
                return view(
                    'demand_list.demand_list_table',
                    [
                        "defaultDisplay" => $this->defaultDisplay,
                        "demandInfos" => $demandInfos,
                        "auth" => Auth::user()->auth,
                        'siteLists' => $this->demandListDetailService->mSiteRepo->getList(),
                        'itemLists' => $this->mItemService->prepareDataList($this->demandListDetailService->mItemRepo->getListByCategoryItem(MItemRepository::PROPOSAL_STATUS)),
                        'conditions' => $data,
                        'showCSV' => $showCSV
                    ]
                );
            }
            //Sanitize::clean before show
            return view(
                'demand_list.index',
                [
                    "defaultDisplay" => $this->defaultDisplay,
                    "demandInfos" => $demandInfos,
                    "auth" => Auth::user()->auth,
                    'siteLists' => $this->demandListDetailService->mSiteRepo->getList(),
                    'itemLists' => $this->mItemService->prepareDataList($this->demandListDetailService->mItemRepo->getListByCategoryItem(MItemRepository::PROPOSAL_STATUS)),
                    'conditions' => $data,
                    'showCSV' => $showCSV
                ]
            );
        }
    }

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetail($id)
    {
        $demand = $this->demandinfoRepository->getDemandByIdWithRelations($id);
        if (!$demand) {
            return redirect()->route('demand.get.create');
        }

        $customerTel = $this->demandinfoRepository->checkIdenticallyCustomer($demand->customer_tel);
        $userDropDownList = $this->demandListDetailService->mUserRepo->getListUserForDropDown();
        $genresDropDownList = $this->demandListDetailService->mSiteGenresRepo->getGenreBySiteStHide($demand->site_id);

        $categoriesDropDownList = $this->demandListDetailService->mCategoryRepo->getListCategoriesForDropDown($demand->genre_id);
        $mSiteDropDownList = $this->demandListDetailService->mSiteRepo->getListMSitesForDropDown();
        $mSiteGenresDropDownList = $this->demandListDetailService->mSiteGenresRepo->getMSiteGenresDropDownBySiteId($demand->site_id);

        $mItemsDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(BUILDING_TYPE);
        $prefectureDiv = $this->demandInfoService->translatePrefecture();
        $selectionSystemList = $this->demandInfoService->getSelectionSystemList($demand);
        $listStaff = $this->cyzenDemandServices->getDemandStaffs($id);
        $demandExtenInfoData = $this->demandExtendInfoService->getAllByDemandId($id);
        $notificationStatus = $this->demandNotificationService->statusAccordingCommision($demand->demandNotification);

        $priorityDropDownList = $this->demandInfoService->getPriorityTranslate();
        $stClaimDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(REQUEST_ST);
        $jbrWorkContentDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(JBR_WORK_CONTENTS);
        $specialMeasureDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(PROJECT_SPECIAL_MEASURES);
        $demandStatusDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(PROPOSAL_STATUS);
        $orderFailReasonDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(REASON_FOR_LOST_NOTE);
        $acceptanceStatusDropDownList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(ACCEPTANCE_STATUS);
        $quickOrderFailReasonList = $this->demandListDetailService->mItemRepo->getMItemListByItemCategory(REASON_MISSING_CONTACT);
        $vacationLabels = $this->demandListDetailService->mItemRepo->getMItemList(LONG_HOLIDAYS, date('Y/m/d'));


        $rankList = $this->demandService->getGenreByRank($demand->site_id);

        $enableSiteId = in_array($demand->site_id, [861, 863, 889, 890, 1312, 1313, 1314]);
        return view('demand.detail', [
            'demand' => $demand,
            'userDropDownList' => $userDropDownList,
            'genresDropDownList' => $genresDropDownList,
            'categoriesDropDownList' => $categoriesDropDownList,
            'mSiteDropDownList' => $mSiteDropDownList,
            'mSiteGenresDropDownList' => $mSiteGenresDropDownList,
            'mItemsDropDownList' => $mItemsDropDownList,
            'prefectureDiv' => $prefectureDiv,
            'selectionSystemList' => $selectionSystemList,
            'priorityDropDownList' => $priorityDropDownList,
            'stClaimDropDownList' => $stClaimDropDownList,
            'jbrWorkContentDropDownList' => $jbrWorkContentDropDownList,
            'specialMeasureDropDownList' => $specialMeasureDropDownList,
            'demandStatusDropDownList' => $demandStatusDropDownList,
            'orderFailReasonDropDownList' => $orderFailReasonDropDownList,
            'acceptanceStatusDropDownList' => $acceptanceStatusDropDownList,
            'quickOrderFailReasonDropDownList' => $quickOrderFailReasonList,
            'vacationLabels' => $vacationLabels,
            'rankList' => $rankList,
            'customerTel' => $customerTel,
            'enableSiteId' => $enableSiteId,
            'listStaff' => $listStaff,
            'demandExtenInfoData' => $demandExtenInfoData,
            'notificationStatus' => $notificationStatus
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAttachedFile($id)
    {
        $demandAttachedFile = $this->demandListDetailService->demandAttachedFileRepo->findId($id);
        $demandAttachedFile->delete();

        return redirect()->back()->with('actionSuccess',__('demand.delete_file_success'));
    }

    /**
     * @param DemandAttachedFileRequest $request
     * @param null $demandId
     * @return $this
     * @throws \Exception
     * @throws \Throwable
     */
    public function uploadAttachedFile(DemandAttachedFileRequest $request, $demandId = null)
    {
        if (!$request->hasFile('demand_attached_file')) {
            return redirect()->back()->with('error_msg_input', __('demand.the_file_field_is_required'));
        }

        $uploadFiles = $request->file('demand_attached_file');

        \DB::transaction(function () use ($uploadFiles, $demandId) {
            foreach ($uploadFiles as $key => $file) {
                $currentDate = date('Y-m-d H:i:s');
                $userId = Auth::user()->id;
                $pathUpload = storage_path('upload/' . $demandId);
                if(!is_dir($pathUpload)){
                    mkdir($pathUpload, 0777, true);
                }
                $nextIndex = $this->demandListDetailService->demandAttachedFileRepo->getNextIndex();

                try {
                    $fileUpload = new UploadFile($file, $pathUpload, $nextIndex);
                    $fileName = $fileUpload->upload();
                } catch (\Exception $e) {
                    \Log::debug('Upload file error ');
                    return redirect()->back()->with('error_msg_input', __('demand.error_msg_input'));
                }

                $this->demandListDetailService->demandAttachedFileRepo->create(
                    [
                        'id' => $nextIndex,
                        'demand_id' => $demandId,
                        'path' => $pathUpload . '/' .$fileName,
                        'name' => $fileUpload->getOriginalName(),
                        'content_type' => $fileUpload->getMiMeType(),
                        'create_date' => $currentDate,
                        'create_user_id' => $userId,
                        'update_date' => $currentDate,
                        'update_user_id' => $userId
                    ]
                );
            }
        });

        return redirect()->back()->with('actionSuccess',__('demand.upload_file_success'));
    }

    /**
     * get corp by id function
     *
     * @param  integer $id
     * @return array
     */
    private function getMCorp($id = null)
    {
        $this->additionalSearchParams['corp_id'] = $id;
        $result = $this->demandListDetailService->mCorpRepo->getFirstById($id, true);
        if (!empty($result)) {
            $this->additionalSearchParams['corp_name'] = $result['corp_name'];
            $this->additionalSearchParams['corp_name_kana'] = $result['corp_name_kana'];
        }
        return $result;
    }

    /**
     * set again data request post function
     *
     * @return array
     */
    private function demandListPost()
    {

        $dataRequest = $this->request->all();
        $data = $dataRequest;
        if (isset($dataRequest['data'])) {
            $data = $dataRequest['data'];
            if (isset($data['b_check']) && $data['b_check'] == 'on') {
                $data['demand_status'] = [3];
            }
        }
        if (isset($dataRequest['sortName']) && isset($dataRequest['orderBy'])) {
            $data['sort'] = $dataRequest['sortName'];
            $data['direction'] = $dataRequest['orderBy'];
        }
        unset($data['csv_out']);
        $this->request->session()->forget(self::$sessionKeyForDemandSearch);
        $this->request->session()->put(self::$sessionKeyForDemandSearch, $data);
        return $data;
    }

    /**
     * set again data request get function
     *
     * @return array
     */
    private function demandListGet()
    {
        $data = $this->request->session()->get(self::$sessionKeyForDemandSearch);
        if (!empty($data)) {
            $data += ['page' => $this->request->input('page')];
        }
        return $data;
    }

    /**
     * save session new data $sessionKeyForDemandParameter
     */
    private function setParameterSession()
    {
        $this->request->session()->forget(self::$sessionKeyForDemandParameter);
        $this->request->session()->put(self::$sessionKeyForDemandParameter, $this->request->get('named'));
    }

    /**
     * @param $data
     * @param $bCheck
     * @return array
     */
    private function unifyBCheck($data, $bCheck)
    {
        if ($bCheck || (isset($data['b_check']) && $data['b_check'])) {
            $bCheck = true;
        } else {
            $bCheck = false;
        }
        $data['b_check'] = $bCheck;
        $this->additionalSearchParams['b_check'] = $bCheck;
        if (isset($data['corp_id']) && $data['corp_id']) {
            $this->getMCorp($data['corp_id']);
        }
        if ($data['b_check']) {
            $demandStatus = strval(getDivValue('demand_status', 'agency_before'));
            $this->additionalSearchParams['demand_status'] = [$demandStatus];
        }
        $this->request->merge($this->additionalSearchParams);
        $data = array_merge($data, $this->additionalSearchParams);
        $this->request->session()->forget(self::$sessionKeyForDemandSearch);
        $this->request->session()->put(self::$sessionKeyForDemandSearch, $data);
        return $data;
    }

    /**
     * @param $datas
     * @return bool
     */
    private function checkParamIsset($datas)
    {
        foreach ($datas as $data) {
            if ( $data != '' ) {
                return true;
            }
        }
        return false;
    }
}
