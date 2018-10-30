<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\MCorpRepository;
use App\Services\Ajax\AjaxService;
use App\Services\Ajax\InitializeAjaxService;
use App\Services\AutoCommissionCorpService;
use App\Services\Commission\CalculatorService;
use App\Services\Commission\CommissionSelectExtendService;
use App\Services\Commission\CommissionSelectService;
use App\Services\CommissionService;
use App\Services\Credit\CreditService;
use App\Services\Demand\DemandInfoService;
use App\Services\GeneralSearch\GeneralSearchService;
use Auth;
use Exception;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    const BROWSE_COUNT_THRESHOLD = 5;

    const CACHED_STORE_TIME = 2000;

    const BROWSE_COUNT_POLLING = 5000;

    const BROWSE_COUNT_POLLING_STOP_THRESHOLD = 900;

    /**
     * @var GeneralSearchService
     */
    public $generalSearchServices;
    /**
     * @var DemandInfoService
     */
    public $demandInfoService;
    /**
     * @var AjaxService
     */
    public $ajaxService;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var AutoCommissionCorpService
     */
    protected $autoCommissionCorpService;
    /**
     * @var CalculatorService
     */
    protected $commissionCalculatorService;
    /**
     * @var CommissionService
     */
    protected $commissionService;
    /**
     * @var CreditService
     */
    protected $creditService;

    /**
     * @var InitializeAjaxService
     */
    protected $initializeAjaxService;

    /**
     * @var MCorpRepository
     */
    protected $mCorpRepo;

    /**
     * @var CommissionSelectService $commissionSelectService
     */
    protected $commissionSelectService;

    /**
     * @var CommissionSelectExtendService $commissionSelectExtendService
     */
    protected $commissionSelectExtendService;

    /**
     * AjaxController constructor.
     * @param Request $request
     * @param AutoCommissionCorpService $autoCommissionCorpService
     * @param CalculatorService $commissionCalculatorService
     * @param GeneralSearchService $generalSearchServices
     * @param DemandInfoService $demandInfoService
     * @param AjaxService $ajaxService
     * @param CommissionService $commissionService
     * @param CreditService $creditService
     * @param InitializeAjaxService $initializeAjaxService
     */
    public function __construct(
        Request $request,
        AutoCommissionCorpService $autoCommissionCorpService,
        CalculatorService $commissionCalculatorService,
        GeneralSearchService $generalSearchServices,
        DemandInfoService $demandInfoService,
        AjaxService $ajaxService,
        CommissionService $commissionService,
        CreditService $creditService,
        InitializeAjaxService $initializeAjaxService,
        MCorpRepository $mCorpRepository,
        CommissionSelectExtendService $commissionSelectExtendService,
        CommissionSelectService $commissionSelectService
    ) {
        parent::__construct();
        $this->request = $request;
        $this->autoCommissionCorpService = $autoCommissionCorpService;
        $this->commissionCalculatorService = $commissionCalculatorService;
        $this->generalSearchServices = $generalSearchServices;
        $this->demandInfoService = $demandInfoService;
        $this->ajaxService = $ajaxService;
        $this->commissionService = $commissionService;
        $this->creditService = $creditService;
        $this->initializeAjaxService = $initializeAjaxService;
        $this->mCorpRepo = $mCorpRepository;
        $this->commissionSelectExtendService = $commissionSelectExtendService;
        $this->commissionSelectService = $commissionSelectService;
    }

    /**
     * search corp target area
     *
     * @param integer $id
     * @param string $address1
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchCorpTargetArea($id = null, $address1 = null)
    {
        $checked = false;
        if ($this->request->input('checked') == true) {
            $checked = true;
        }
        $data = $this->ajaxService->mPostRepo->searchCorpTargetArea($id, $address1);

        return view('ajax.corp_target_area', [
            "list" => $data,
            'checked' => $checked,
        ]);
    }

    /**
     * search address data by zip
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAddressByZip(Request $request)
    {
        $zipCode = $request->query('zip');
        $zipCode = str_replace('-', '', $zipCode);
        $result = $this->ajaxService->mPostRepo->searchAddressByZip($zipCode);
        $request->session()->reflash();
        return response()->json($result);
    }

    /**
     * get calendar view
     *
     * @param  Request $request
     * @return string
     * @throws \Throwable
     */
    public function getCalenderView(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        if (empty($year)) {
            $year = date('Y');
        }
        if (empty($month)) {
            $month = date('m');
        }
        $request->session()->reflash();
        return getDateWeekMonth($year, $month);
    }

    /**
     * get ExclusionTime
     *
     * @param  $pattern
     * @return string
     */
    public function exclusionPattern(Request $request, $pattern = null)
    {
        if (empty($pattern)) {
            return response()->json([
                'result' => '',
            ]);
        }
        $day = '';
        $result = '';
        $data = $this->ajaxService->exclusionTimeRepo->findByPattern($pattern);
        $holiday = \Config::get('datacustom.holiday');
        array_walk($holiday, function (&$value) {
            $value = trans('divlist.' . $value);
        });
        foreach ($holiday as $key => $val) {
            if (!empty($data['exclusion_day']) && judgeHoliday($data['exclusion_day'], $key)) {
                if (!empty($day)) {
                    $day .= ', ';
                }
                $day .= $val;
            }
        }
        if (!empty($data['exclusion_time_from'])) {
            $result .= trans('genre_detail.exclusion_time') . ' : ';
            $result .= $data['exclusion_time_from'] . trans('common.wavy_seal') . $data['exclusion_time_to'];
        }
        if (!empty($day)) {
            $result .= ' ' . trans('genre_detail.exclusion_date') . ' : ';
            $result .= $day;
        }
        $request->session()->reflash();
        return response()->json([
            'result' => '【' . $result . '】',
        ]);
    }

    /**
     * get MGeneralSearch
     *
     * @return string
     * @throws Exception
     */
    public function searchMGeneralSearch()
    {
        $whereConditions = [];
        $orwhereConditions = [];
        try {
            switch (Auth::user()->auth) {
                case "popular":
                    array_push($whereConditions, ['auth_popular', '=', 1]);
                    break;
                case "admin":
                case 'system':
                    array_push($whereConditions, ['auth_admin', '=', 1]);
                    array_push($orwhereConditions, ['auth_popular', '=', 1]);
                    break;
                case 'accounting_admin':
                    array_push($whereConditions, ['auth_accounting_admin', '=', 1]);
                    array_push($orwhereConditions, ['auth_popular', '=', 1]);
                    break;
                case 'accounting':
                    array_push($whereConditions, ['auth_accounting', '=', 1]);
                    array_push($orwhereConditions, ['auth_popular', '=', 1]);
                    break;
                case 'affiliation':
                default:
            }
            if (!empty($whereConditions)) {
                $results = $this->initializeAjaxService->mGeneralSearchRepo->findGeneralSearchAuth(
                    $whereConditions,
                    $orwhereConditions
                );
            } else {
                $results = [];
            }
            return json_encode($results);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function csvPreview(Request $request)
    {
        try {
            $dataForm = $request->data;
            $dataForm['MGeneralSearch']['definition_name'] = "temporary";
            $dataForm['MGeneralSearch']['auth_admin'] = 1;
            $dataForm['MGeneralSearch']['id'] = "";
            $generalId = $this->generalSearchServices->saveGeneralSearchAjax($dataForm);
            $results = $this->generalSearchServices->getCsvPreview($generalId);
            $this->generalSearchServices->deleteGeneralSearchAll($generalId);

            return json_encode($results);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @extendDes all function for demand detail
     * @author Dung.PhamVan@nashtechglobal.com
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @des get category list by genre_id
     */
    public function getCategoryListByGenreId(Request $request)
    {
        $categories = $this->ajaxService->mCategoryRepo->getListStHide($request->get('genreId'));
        $request->session()->reflash();
        return response()->json([
            'code' => 1,
            'data' => $categories,
            'message' => 'get successfully',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInquiryItemData(Request $request)
    {
        $request->session()->reflash();
        return response()->json([
            'code' => 1,
            'message' => 'get data successfully',
            'data' => $this->ajaxService->mGenreRepo->findById($request->get('genreId')),
        ]);
    }

    /**
     * @param Request $request
     * @param null $demandId
     * @return \Illuminate\Http\JsonResponse
     */
    public function writeBrowse(Request $request, $demandId = null)
    {
        $cached = \Cache::get($demandId);
        $userId = auth()->user()->user_id;

        if (empty($cached)) {
            $cached[] = [
                'user_id' => $userId,
                'last_date' => time(),
            ];
        } else {
            $checked = false;
            foreach ($cached as $key => $cache) {
                if ($cache['user_id'] == $userId) {
                    $cached[$key]['last_date'] = time();
                    $checked = true;
                }
            }
            if (!$checked) {
                $cached[] = [
                    'user_id' => $userId,
                    'last_date' => time(),
                ];
            }
        }

        \Cache::put($demandId, $cached, 5000);
        $request->session()->reflash();
        return $this->responseJson(
            1,
            'cached demand ' . $demandId . ' with ' . count(\Cache::get($demandId)) . ' successfully',
            ''
        );
    }

    /**
     * @param $code
     * @param $message
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseJson($code, $message, $data)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @param Request $request
     * @param null $demandId
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function countBrowse(Request $request, $demandId = null)
    {
        $cached = \Cache::get($demandId);

        if (!$cached) {
            return $this->responseJson(0, 'get total current user successfully', 0);
        }
        $totalViews = count(array_filter($cached, function ($user) {
            return $user['last_date'] >= time() - self::BROWSE_COUNT_THRESHOLD;
        }));
        $request->session()->reflash();
        return $this->responseJson(1, 'get total current user successfully', $totalViews);
    }

    /**
     * @param Request $request
     */
    public function countBrowseList(Request $request)
    {
        $returnList = [];
        try {
            if (!$request->all()) {
                return;
            }
            $demandIds = explode(',', $request->input('id'));
            foreach ($demandIds as $demandId) {
                $data = \Cache::get($demandId);
                $count = 0;
                if ($data) {
                    foreach ($data as $item) {
                        if ($item['last_date'] >= time()- self::BROWSE_COUNT_THRESHOLD) {
                            $count++;
                        }
                    }
                }
                $returnList[] = ['demand_id' => $demandId, 'count' => $count];
            }
        } catch (Exception $e) {
            \Log::error($e->getMessage());
        }

        echo json_encode($returnList);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSiteData(Request $request)
    {
        $contentType = $request->get('content');
        $siteId = $request->get('site_id');

        $return = [
            'code' => 1,
            'message' => 'get data successfully',
        ];
        $data = $contentType == 1 ? $this->ajaxService->mSiteRepo->findById($siteId) :
            $this->ajaxService->mSiteRepo->getWithCommissionType($siteId);
        $request->session()->reflash();
        return response()->json(array_replace($return, [
            'data' => $data,
        ]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelectionSystemList(Request $request)
    {
        $genreId = $request->get('genre_id');
        $address1 = $request->get('address1');

        $selectionSystem = '';

        if (!empty($address1)) {
            $genrePrefecture = $this->initializeAjaxService->selectGenrePrefectureRepo->getByGenreIdAndPrefectureCd([
                'genre_id' => $genreId,
                'address1' => $address1,
            ]);

            if ($genrePrefecture) {
                $selectionSystem = $genrePrefecture->selection_type;
            }
        }

        if ($selectionSystem === '') {
            $selectGenre = $this->ajaxService->selectGenreRepo->findByGenreId($genreId);

            if ($selectGenre) {
                $selectionSystem = $selectGenre->select_type;
            }
        }
        $request->session()->reflash();
        return $this->responseJson(1, 'get data successfully', [
            'selection_system' => $this->renderSelectionSystemOption($selectionSystem),
            'default_value' => $selectionSystem,
        ]);
    }

    /**
     * @param $selection
     * @return array
     */
    private function renderSelectionSystemOption($selection)
    {
        $defaultSelection = getDivValue('selection_type', 'manual_selection');
        $option = [$selection => __('auto_commission_corp.' . config('rits.selection_type')[(int)$selection])];
        $uniqueSelection = array_unique(
            array_replace([
            $defaultSelection => __('auto_commission_corp.' . config('rits.selection_type')['0']),
            ], $option)
        );

        return $uniqueSelection;
    }

    /**
     * @param $corpId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMCorp(Request $request, $corpId, $categoryId = null)
    {
        $mCorp = $this->ajaxService->mCorpRepo->findAllAttribute($corpId);
        $feeData = $this->ajaxService->mCorpRepo->getFeeData($corpId, $categoryId);

        $feeCommission = $request->get('fee_data');
        $request->session()->reflash();
        return view('demand.m_corp_detail', compact('mCorp', 'feeData', 'feeCommission'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTravelExpenses(Request $request)
    {
        $genreId = $request->get('genre_id');
        $address = $request->get('address1');
        $data = $this->initializeAjaxService->selectGenrePrefectureRepo->getByGenreIdAndPrefectureCd([
            'genre_id' => $genreId,
            'address1' => $address,
        ]);
        $businessTripMount = $data ? $data->business_trip_amount : '';
        $request->session()->reflash();
        return $this->responseJson(1, '', [
            'business_trip_amount' => $businessTripMount,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkExistsAutoCommissionCorps(Request $request)
    {
        $siteId = $request->get('site_id');
        $genreId = $request->get('genre_id');
        $categoryId = $request->get('category_id');
        $prefectureCode = $request->get('prefecture_code');

        if (empty($request->except('_token'))) {
            return $this->responseJson(0, 'No data', 0);
        }
//        $parseAddress = $this->ajaxService->parseData($prefectureCode);
        $jisCd = $this->ajaxService->mPostRepo->getTargetArea(['address1' => $prefectureCode, 'address2' => null]);
        if ($jisCd == null) {
            return $this->responseJson(0, 'No data', 0);
        }

        $conditions = [
            'category_id' => $categoryId,
            'jis_cd' => $jisCd,
        ];
        $commissionCorps = $this->mCorpRepo->searchCorpForPopup($conditions, '', 0);
        $newCommissionCorps = $this->commissionService->getCorpList($conditions, 'new');
        $autoCommissionCorp = $this->initializeAjaxService->autoCommissionCorpRepo->getByCategoryGenreAndPrefCd([
            'pref_cd' => $prefectureCode,
            'category_id' => $categoryId
        ], null, false, 'prefecture_div');

        $result = 0;
        foreach ($autoCommissionCorp as $key => $value) {
            $targetCommissionCorp = null;
            foreach ($newCommissionCorps as $key => $newCorps) {
                if ($newCorps->MCorp_id == $value->m_corps_id) {
                    $targetCommissionCorp = $newCorps;
                    break;
                }
            }

            $targetCommissionCorp = (Object)$this->ajaxService->checkTargetCommissionCorp(
                $commissionCorps,
                $targetCommissionCorp,
                $value
            );

            if ($targetCommissionCorp === null) {
                continue;
            }
            $corpId = null;
            try {
                $corpId = $targetCommissionCorp->corp_id;
            } catch (Exception $ex) {
                \Log::error(__FILE__ . ' >>> ' . __LINE__ . ': ' . $ex->getMessage());
            }
            $resultCredit = config('rits.CREDIT_NORMAL');

            $resultCredit = $this->getCredit(
                $siteId,
                config('rits.credit_check_exclusion_site_id'),
                $genreId,
                $resultCredit,
                $corpId
            );

            if ($resultCredit == config('rits.CREDIT_DANGER')) {
                continue;
            }
            $resultCrossSiteFlg = $this->ajaxService->mSiteRepo->getCrossSiteFlg(1);
            if (in_array($siteId, $resultCrossSiteFlg)) {
                continue;
            }
            if (in_array($siteId, (config('rits.arrSiteId')))) {
                continue;
            }

            $result = $value->process_type;
            break;
        }
        $request->session()->reflash();
        return $this->responseJson(1, '', $result);
    }

    /**
     * @param $val
     * @param $arr
     * @param $genreId
     * @param $result
     * @param null $targetId
     * @return int|string
     */
    private function getCredit(
        $val,
        $arr,
        $genreId,
        $result,
        $targetId = null
    ) {
        if (!in_array($val, $arr)) {
            $result = $this->creditService->checkCredit($targetId, $genreId, false, true);
        }

        return $result;
    }

    /**
     * @param $jisCd
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkJisCd(Request $request, $jisCd)
    {
        $request->session()->reflash();
        if ($jisCd == null) {
            return $this->responseJson(0, 'No data', 0);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGenreListBySiteId(Request $request)
    {
        $siteId = $request->get('site_id');
        $hideFlg = $request->get('hideflg', true);

        $data = $this->initializeAjaxService->mSiteGenresRepo->getGenreBySiteStHide($siteId, $hideFlg);
        $request->session()->reflash();
        return $this->responseJson(1, 'ok', $data);
    }

    /**
     * @des End functions for demand detail
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMsText(Request $request)
    {
        $result = ['message_failure' => __('commission_corresponds.message_failure')];
        $res = $request->all();

        if ($res) {
            foreach ($res as $field => $vals) {
                foreach ($vals as $val) {
                    $key = str_replace('.', '_', $val);
                    $result[$field][$key] = __($val);
                }
            }
        }
        $request->session()->reflash();
        return response()->json($result);
    }

    /**
     * @param null $date
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTaxRate($date = null)
    {
        $result = '';
        if (!empty($date)) {
            $date = str_replace("-", "/", $date);

            if (preg_match(
                '/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/',
                $date
            )) {
                $result = $this->commissionCalculatorService->getTaxRates($date);
            }
        }

        return view('ajax.tax_rate', [
            'tax_rate' => $result,
        ]);
    }

    /**
     * @param null $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaxRateOnly($date = null)
    {
        $result = '';

        if (!empty($date)) {
            $date = str_replace("-", "/", $date);

            if (preg_match(
                '/^([1-9][0-9]{3})\/(0[1-9]{1}|1[0-2]{1})\/(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/',
                $date
            )) {
                $result = $this->commissionCalculatorService->getTaxRates($date);
            }
        }

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateBillInfo(Request $request)
    {
        $result = [];

        try {
            $data = $request->all();

            $result = $this->commissionCalculatorService->calcBillPrice(
                $data['CommissionInfo']['id'],
                $data['CommissionInfo']['commission_status'],
                !empty($data['CommissionInfo']['complete_date']) ? $data['CommissionInfo']['complete_date'] : null,
                isset($data['CommissionInfo']['construction_price_tax_exclude'])
                    ? $data['CommissionInfo']['construction_price_tax_exclude'] : null
            );
        } catch (Exception $e) {
            logger($e->getMessage());
            $result['status'] = 500;
        }

        return response()->json($result);
    }

    /**
     * @param null $corpId
     * @param null $address1
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchTargetArea($corpId = null, $address1 = null)
    {
        $data = $this->ajaxService->mPostRepo->searchTargetArea($corpId, $address1);

        return view('ajax.target_area', [
            "list" => $data,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserList(Request $request)
    {
        $request->session()->reflash();
        return $this->responseJson(1, 'get data successfully', $this->ajaxService->mUserRepo->getListUserForDropDown());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDefaultFee(Request $request)
    {
        $categoryId = $request->get('category_id');
        $defaultFee = $this->ajaxService->mCategoryRepo->getDefaultFee($categoryId);
        $request->session()->reflash();
        return $this->responseJson(1, 'get data successfully', ['default_fee' => $defaultFee]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryList2(Request $request)
    {
        $categoryList = $this->initializeAjaxService->mSiteGenresRepo->getMSiteGenresDropDownBySiteId($request->get('site_id'));
        $request->session()->reflash();
        return $this->responseJson(1, '', [
            'category_list' => $categoryList,
            'site_id' => $request->get('site_id'),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommissionMaxLimit(Request $request)
    {
        $default = [
            'manual_selection_limit' => 1,
            'auction_selection_limit' => 1,
        ];
        $mSite = $this->ajaxService->mSiteRepo->getSelectionLimit($request->get('m_site_id'));
        $request->session()->reflash();
        return $this->responseJson(1, '', array_merge($default, $mSite));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function attentionData(Request $request)
    {
        $genreId = empty($request->get('genre_id')) ? null : $request->get('genre_id');
        $mGenreData = $this->ajaxService->mGenreRepo->find($genreId);
        $attention = $mGenreData["MGenre"]['attention'];
        $request->session()->reflash();
        return $this->responseJson(1, 'ok', $attention);
    }

    /**
     * @param null $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquiryList($category = null)
    {
        $result = $this->initializeAjaxService->mInquiryRepo->getListInquiryByCategory($category);
        if (count($result) > 0) {
            foreach ($result as &$arr) {
                $arr['answer'] = $this->initializeAjaxService->mAnswerRepo->dropDownAnswer($arr['id'], true);
            }
        }

        return view('ajax.inquiry_list', ['data' => $result]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function commissionChange(Request $request)
    {
        $num = empty($request->get('num')) ? null : $request->get('num');
        $categoryId = empty($request->get('category_id')) ? null : $request->get('category_id');
        $corpId = empty($request->get('corp_id')) ? null : $request->get('corp_id');
        $data = $this->ajaxService->mCorpRepo->getCommissionChangeByCategoryIdAndCorpId($num, $categoryId, $corpId);
        $request->session()->reflash();
        return $this->responseJson(1, 'get data successfully', $data);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function countAffiliation(Request $request)
    {
        $data = $request['data'];
        $data = $this->commissionSelectService->validateDataAndAddParams($data);
        if (empty($data['jis_cd'])) {
            return 0;
        }
        $totalList = null;
        $corpList = $this->mCorpRepo->searchCorpForPopup($data, false, 0);
        $count = count($corpList);
        $newCorpList = $this->mCorpRepo->searchCorpForPopup($data, true, $count);
        $listCorpMerge = array_merge($newCorpList, $corpList);
        $bEstimated = !empty($data['is_estimated']) && strtolower($data['is_estimated']) == 'true' ? true : false;
        $bSpecific = isset($data['is_specifyTime']) && !empty($data['is_specifyTime']) && strtolower($data['is_specifyTime']) == 'true' ? true : false;
        if (count($listCorpMerge) > 0) {
            if (!empty($data['time_from']) && !empty($data['time_to']) && !empty($data['lat']) && !empty($data['lng'])) {
                try {
                    $totalList = $this->commissionSelectExtendService->executeFilter(
                        $listCorpMerge,
                        $data['jis_cd'],
                        $data['genre_id'],
                        $data['category_id'],
                        $data['lat'],
                        $data['lng'],
                        $data['time_from'],
                        $data['time_to'],
                        true,
                        '',
                        $bEstimated,
                        false,
                        $bSpecific
                    );
                } catch (Exception $ex) {
                    \Log::error(__FILE__ . ' >>> ' . __LINE__ . ': ' . $ex->getMessage());
                }
            }
        }
        $request->session()->reflash();
        return $totalList;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decreaseBrowserCache(
        Request $request
    ) {
        $demandId = $request->get('demand_id');
        $cached = \Cache::get($demandId);
        if (!empty($cached)) {
            $data = array_filter($cached, function ($item) {
                return $item['user_id'] == auth()->user()->user_id;
            });

            if (!$data) {
                unset($cached[array_keys($data)[0]]);
            }
        }
        $request->session()->reflash();
        return $this->responseJson(1, 'Delete cache successfully', '');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNowDate(Request $request)
    {
        $nowDatetime = date("Y/m/d H:i");
        $request->session()->reflash();
        return response()->json($nowDatetime);
    }
}
