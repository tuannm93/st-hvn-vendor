<?php

namespace App\Http\Controllers\AutoCommissionCorp;

use App\Http\Requests\AutoCommissionCorpAddRequest;
use App\Repositories\AutoCommissionCorpRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Services\AuctionSettingService;
use App\Services\CorpAddAutoCommissionCorpService;
use App\Services\AutoCommissionCorpService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AutoCommissionCorpController extends Controller
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;
    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    protected $autoCommissionCorpRepository;
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;
    /**
     * @var CorpAddAutoCommissionCorpService
     */
    protected $addAutoCommissionCorpService;
    /**
     * @var AuctionSettingService
     */
    protected $auctionSettingService;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenres;
    /**
     * @var AutoCommissionCorpService
     */
    protected $autoCommissionCorpService;

    /**
     * AutoCommissionCorpController constructor.
     *
     * @param MCorpRepositoryInterface              $mCorpRepository
     * @param MCorpCategoryRepositoryInterface      $mCorpCategoryRepository
     * @param AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepository
     * @param MPostRepositoryInterface $mPostRepository
     * @param CorpAddAutoCommissionCorpService $addAutoCommissionCorpService
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param AuctionSettingService $auctionSettingService
     * @param MGenresRepositoryInterface $mGenres
     * @param AutoCommissionCorpService $autoCommissionCorpService
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository,
        AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepository,
        MPostRepositoryInterface $mPostRepository,
        CorpAddAutoCommissionCorpService $addAutoCommissionCorpService,
        MCategoryRepositoryInterface $mCategoryRepository,
        AuctionSettingService $auctionSettingService,
        MGenresRepositoryInterface $mGenres,
        AutoCommissionCorpService $autoCommissionCorpService
    ) {
        parent::__construct();
        $this->mCorpRepository = $mCorpRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
        $this->autoCommissionCorpRepository = $autoCommissionCorpRepository;
        $this->mPostRepository = $mPostRepository;
        $this->addAutoCommissionCorpService = $addAutoCommissionCorpService;
        $this->auctionSettingService = $auctionSettingService;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mGenres = $mGenres;
        $this->autoCommissionCorpService = $autoCommissionCorpService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $genreCategoryList = $this->autoCommissionCorpService->pluckListGenreRelated();
        $prefectureList = config('constant.state_list');
        return view(
            'auto_commission_corp.index',
            [
            'genreCategoryList' => $genreCategoryList,
            'prefectureList' => $prefectureList
            ]
        );
    }

    /**
     * get view add_agreement function
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAdd()
    {
        $jisCd = config('datacustom.state_list');
        return view('auto_commission_corp.add', compact('jisCd'));
    }

    /**
     * insert auto_commission_corps data
     *
     * @param  AutoCommissionCorpAddRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(AutoCommissionCorpAddRequest $request)
    {
        $this->autoCommissionCorpRepository->deleteBy(
            $request->jis_cd,
            $request->category_id,
            $request->corp_id
        );
        $saveData = [];
        $i= 0;
        foreach ($request->jis_cd as $valAddressCommission) {
            $prefecture['address1'] = $valAddressCommission;
            $jisCdResult = $this->getJiscd($prefecture);
            foreach ($request->category_id as $valCategoryIdCommission) {
                foreach ($jisCdResult as $key => $value) {
                    $saveData[$i]['corp_id'] = $request->corp_id;
                    $saveData[$i]['category_id'] = $valCategoryIdCommission;
                    $saveData[$i]['sort'] = 1;
                    $saveData[$i]['jis_cd'] = $value['jis_cd'];
                    $saveData[$i]['created_user_id'] = Auth::user()->user_id;
                    $saveData[$i]['created'] = date('Y-m-d H:i:s');
                    $saveData[$i]['modified_user_id'] = Auth::user()->user_id;
                    $saveData[$i]['modified'] = date('Y-m-d H:i:s');
                    $saveData[$i]['process_type'] = $request->process_type;
                    $i++;
                }
            }
        }
        $this->autoCommissionCorpRepository->insert($saveData);
        $request->session()->flash('alert-success', trans('auto_commission_corp.insert_success'));
        return redirect()->route('autoCommissionCorp.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorpList(Request $request)
    {
        $limitSearch = 50;
        $searchKey = $request->search_key;
        $searchValue = $request->search_value;
        $dataMcorp = $this->mCorpRepository->searchByCorpIdOrCorpName($searchKey, $searchValue, $limitSearch, false);
        $countData = $this->mCorpRepository->searchByCorpIdOrCorpName($searchKey, $searchValue, $limitSearch, true);
        $data['count'] = $countData;
        $data['results'] = $dataMcorp;
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenreList(Request $request)
    {
        $corpId = $request->corp_id;
        $dataMGenre = $this->mCorpCategoryRepository->getGenresByCorpId($corpId);
        return response()->json($dataMGenre);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryList(Request $request)
    {
        $corpId = $request->corp_id;
        $genreId = $request->genre_id;
        $dataCategory = $this->mCorpCategoryRepository->getCategoriesByGenreIdCorpId($genreId, $corpId);
        return response()->json($dataCategory);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function getJiscd($data)
    {
        $dataAddress = $this->addAutoCommissionCorpService->getJiscd($data);
        $results = $this->mPostRepository->findByAddress1($dataAddress);
        return $results;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCorpSelect()
    {
        $dataView = $this->autoCommissionCorpService->prepareDataForViewCorpSelect();
        return view(
            'auto_commission_corp.corp_select',
            [
            'data' => $dataView
            ]
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCategoryByGenreId(Request $request)
    {
        $genreId = $request->idGenre;
        $listCate = $this->mCategoryRepository->getList($genreId);
        return $listCate;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getListCorpByCategoryAndPref(Request $request)
    {
        $categoryId = $request->cate;
        $prefId = $request->pref;
        $data = $this->autoCommissionCorpService->getListCorpByCategoryPref($categoryId, $prefId);
        return $data;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function getCorpAdd(Request $request)
    {
        try {
            $getGenreName = '';
            $getCategoryName = '';
            $data = $request->only('genre_id', 'category_id', 'pref_cd');
            $corpListResult = $this->autoCommissionCorpRepository->getByCategoryGenreAndPrefCd($data);
            $prefecture = $this->addAutoCommissionCorpService->getPrefecture($request->pref_cd);
            $dropListGenre = $this->auctionSettingService->getDropListGenre(true)->toArray();
            $dropCategory = $this->mCategoryRepository->getDropListCategory($request->genre_id)->toArray();
            if (isset($data['genre_id'])) {
                $getGenreName = $this->addAutoCommissionCorpService->checkUndefinedOffsetKey($data['genre_id'], $dropListGenre);
            }
            if (isset($data['category_id'])) {
                $getCategoryName = $this->addAutoCommissionCorpService->checkUndefinedOffsetKey($data['category_id'], $dropCategory);
            }
            $corpSelectionList = empty($corpListResult[1]) ? [] : $corpListResult[1]; //when process_type column data = 1
            $corpCommissionList = empty($corpListResult[2]) ? [] : $corpListResult[2]; //when process_type column data = 2
            return view('auto_commission_corp.corp_add', compact('corpCommissionList', 'corpSelectionList', 'prefecture', 'getGenreName', 'getCategoryName', 'data'));
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * insert auo_commission_corps data
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function postCorpAdd(Request $request)
    {
        try {
            $corpListResult = $this->autoCommissionCorpRepository->getByCategoryGenreAndPrefCd($request->all(), 'auto_corp', false);
            $resultFlag = $this->addAutoCommissionCorpService->deleteMultiRecord($corpListResult);
            if (!$resultFlag) {
                $request->session()->flash('alert-error', trans('auto_commission_corp.insert_error'));
                return redirect()->route('autoCommissionCorp.index');
            }
            $jiscdResult = $this->getJiscd($request->all());
            $saveData = $this->addAutoCommissionCorpService->setDataToSave($request->all(), $jiscdResult);
            if (isset($saveData)) {
                $this->autoCommissionCorpRepository->insert($saveData);
                $request->session()->flash('alert-success', trans('auto_commission_corp.insert_success'));
            } else {
                $request->session()->flash('alert-error', trans('auto_commission_corp.insert_error'));
            }
            return redirect()->route('autoCommissionCorp.index');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorpAddList(Request $request)
    {
        try {
            $limitSearch = 50;
            $data = $request->all();
            $dataMcorp = $this->mCorpRepository->searchCorpAddList($data, $limitSearch, false);
            $countData = $this->mCorpRepository->searchCorpAddList($data, $limitSearch, true);
            $results['count'] = $countData;
            $results['results'] = $dataMcorp;
            return response()->json($results);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCorpList(Request $request)
    {
        $listCorpSelected = $request->listCorp;
        $listIdPref = $request->listPref;
        $listIdPrefString = array_map(
            function ($obj) {
                if (strlen($obj) == 2) {
                    return '\'' . $obj . '\'';
                } else {
                    return '\'0' . $obj . '\'';
                }
            },
            $listIdPref
        );
        $listIdCate = $request->listCate;
        $typeSearch = $request->type;
        $valueSearch = $request->search;
        $result = $this->mCorpRepository->searchByCategoryPref(
            $listIdCate,
            $listIdPrefString,
            $listCorpSelected,
            $typeSearch,
            $valueSearch
        );
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCorpSelect(Request $request)
    {
        $listPref = $request->pref;
        $listCate = $request->cate;
        $listCommissCorp = $request->commissionCorp;
        $listSelectCorp = $request->selectedCorp;
        $bSuccess = $this->autoCommissionCorpService->editListCorpSelect(
            $listCate,
            $listPref,
            $listCommissCorp,
            $listSelectCorp
        );
        return response()->json(['code' => $bSuccess]);
    }

    /**
     * Search auto commission corp with list genre id
     *
     * @param  Request $request
     * @return string
     */
    public function searchAutoCommissionCorp(Request $request)
    {
        $arrGenreId = $request->genre_id;
        $data = $this->autoCommissionCorpService->searchAutoCommissionCorp($arrGenreId);
        return json_encode($data);
    }

    /**
     * Search auto commission corp all
     *
     * @return int|string
     */
    public function searchAutoCommissionCorpAll()
    {
        $data = $this->autoCommissionCorpService->getAllAutoCommissionCorp();
        return json_encode($data);
    }
}
