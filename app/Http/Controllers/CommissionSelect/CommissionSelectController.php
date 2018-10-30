<?php

namespace App\Http\Controllers\CommissionSelect;

use App\Http\Controllers\Controller;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Services\Commission\CommissionSelectExtendService;
use App\Services\Commission\CommissionSelectService;
use App\Services\CommissionInfoService;
use App\Services\MItemService;
use Illuminate\Http\Request;

class CommissionSelectController extends Controller
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    protected $affiliationAreaRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;
    /**
     * @var MItemService
     */
    protected $mItemService;
    /**
     * @var CommissionSelectService
     */
    protected $commissionSelectService;

    /** @var CommissionSelectExtendService $commissionSelectExtendService */
    protected $commissionSelectExtendService;

    /** @var CommissionInfoService */
    protected $commissionInfoService;

    /**
     * CommissionSelectController constructor.
     *
     * @param AffiliationAreaStatRepositoryInterface $affiliationAreaRepo
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param MItemRepositoryInterface $mItemRepository
     * @param MPostRepositoryInterface $mPostRepository
     * @param MItemService $mItemService
     * @param \App\Services\Commission\CommissionSelectService $commissionSelectService
     * @param CommissionSelectExtendService $commissionSelectExtendService
     */
    public function __construct(
        AffiliationAreaStatRepositoryInterface $affiliationAreaRepo,
        MCategoryRepositoryInterface $mCategoryRepository,
        MCorpRepositoryInterface $mCorpRepository,
        MItemRepositoryInterface $mItemRepository,
        MPostRepositoryInterface $mPostRepository,
        MItemService $mItemService,
        CommissionSelectService $commissionSelectService,
        CommissionSelectExtendService $commissionSelectExtendService,
        CommissionInfoService $commissionInfoService
    ) {
        parent::__construct();
        $this->mCorpRepository = $mCorpRepository;
        $this->affiliationAreaRepo = $affiliationAreaRepo;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mPostRepository = $mPostRepository;
        $this->mItemRepository = $mItemRepository;
        $this->mItemService = $mItemService;
        $this->commissionSelectService = $commissionSelectService;
        $this->commissionSelectExtendService = $commissionSelectExtendService;
        $this->commissionInfoService = $commissionInfoService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getButton()
    {
        return view('commission.getButton');
    }

    /**
     * Display page commission_select/display
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        session()->put('data', $request->all());
        return redirect()->route('commissionselect.display');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display(Request $request)
    {
        $data = session('data')['data'];
        $data = $this->commissionSelectService->validateDataAndAddParams($data);
        /* Get max price form affiliation_area_stat */
        $maxPrice = $this->affiliationAreaRepo->getMaxCommissionUnitPriceCategory($data);
        /* Add params in $data */
        $data['category_name'] = '';
        $data['category_default_fee'] = '';
        $data['category_default_fee_unit'] = '';
        $data['category_default_commission_type'] = '';

        if (!empty($data['category_id'])) {
            $data['category_name'] = $this->mCategoryRepository->getListText($data['category_id']);
            $categoryDefaultFee = $this->mCategoryRepository->getDefaultFee($data['category_id']);
            $data['category_default_fee'] = $categoryDefaultFee['category_default_fee'];
            $data['category_default_fee_unit'] = $categoryDefaultFee['category_default_fee_unit'];
            $data['category_default_commission_type'] = $this->mCategoryRepository->getCommissionType($data['category_id']);
        }
        $vacation = $this->mItemService->prepareDataList($this->mItemRepository->getListByCategoryItem(MItemRepository::LONG_HOLIDAYS));
        $corpList = $this->mCorpRepository->searchCorpForPopup($data, false, 0);
        if (!is_array($corpList)) {
            return view(
                'commission.display',
                [
                    'data' => $data,
                    'maxPrice' => $maxPrice,
                    'list' => [],
                    'vacation' => $vacation,
                    'new_list' => [],
                    'errorCommission' => trans('commissionselect.noneCategoryOrJis')
                ]
            );
        }

        $count = count($corpList);
        $corpList2 = $this->commissionSelectService->getNewCorpList($corpList);
        foreach ($corpList2 as $key => $item) {
            $corpList2[$key]['group_corp'] = 1;
        }
        $bEstimated = !empty($data['is_estimated']) && strtolower($data['is_estimated']) == 'true' ? true : false;
        $bSpecific = !empty($data['is_specifytime']) && strtolower($data['is_specifytime']) == 'true' ? true : false;
        $bTargetCheck = isset($data['target_check']) && !empty($data['target_check']) ? true : false;
        $newCorpList = $this->mCorpRepository->searchCorpForPopup($data, true, $count);

        $newCorpList2 = [];
        for ($i = 0; $i < count($newCorpList); $i++) {
            $obj = $newCorpList[$i];
            $obj['commission_unit_price_rank_1'] = 'z';
            $obj['commission_unit_price_rank_2'] = 'z';
            $newCorpList2[] = $obj;
        }
        foreach ($newCorpList2 as $key => $item) {
            $newCorpList2[$key]['group_corp'] = 2;
        }
        $listCorpMerge = array_merge($newCorpList2, $corpList2);
        //filter list 2
        if (!empty($data['time_from']) && !empty($data['time_to']) && !empty($data['lat'])
            && !empty($data['lng']) && !$bTargetCheck) {
            if (count($listCorpMerge) > 0) {
                $newCorpList2 = $this->commissionSelectExtendService->executeFilter(
                    $listCorpMerge,
                    $data['jis_cd'],
                    $data['genre_id'],
                    $data['category_id'],
                    $data['lat'],
                    $data['lng'],
                    $data['time_from'],
                    $data['time_to'],
                    false,
                    $data['exclude_staff_id'],
                    $bEstimated,
                    $bTargetCheck,
                    $bSpecific
                );
            }
        }
        if ($request->ajax() && !isset($data['view'])) {
            return view(
                'commission.component.commission_select_popup',
                [
                    'data' => $data,
                    'maxPrice' => $maxPrice,
                    'list' => $corpList2,
                    'vacation' => $vacation,
                    'new_list' => $newCorpList2,
                    'errorCommission' => null
                ]
            );
        }
        return view(
            'commission.display',
            [
                'data' => $data,
                'maxPrice' => $maxPrice,
                'vacation' => $vacation,
                'new_list' => $newCorpList2,
                'errorCommission' => null
            ]
        );
    }


    /**
     * @param Request $request
     * @return string
     */
    public function mCorpDisplay(Request $request)
    {
        return $this->mCorpSearch($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function mCorpSearch(Request $request)
    {
        $dataRequest = $request->all();
        $mCorps = $this->mCorpRepository->getListForCommissionSelect($dataRequest);
        return json_encode($mCorps, true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCredit(Request $request)
    {
        $genreId      = $request->get('genre_id');
        $siteId       = $request->get('site_id');
        $listCorpId   = $request->get('list_corp_id');
        $listCorpName = $request->get('list_corp_name');

        $countDanger = 0;
        $nameCorp = '';
        $listPositionDanger = [];
        if (!empty($listCorpId)) {
            foreach ($listCorpId as $key => $corpId) {
                $result = $this->commissionInfoService->checkCredit($corpId, $genreId, false);
                if ($result == config('rits.CREDIT_DANGER')) {
                    $listPositionDanger[] = $key;
                    $nameCorp .= $listCorpName[$key];
                    $countDanger++;
                }
            }
        }
        if (in_array($siteId, config('rits.credit_check_exclusion_site_id'))) {
            $countDanger = 0;
        }

        $data = [
          'credit_count' => $countDanger,
          'credit_message' => $nameCorp,
          'list_position_danger' => $listPositionDanger,
        ];

        return response()->json($data);
    }
}
