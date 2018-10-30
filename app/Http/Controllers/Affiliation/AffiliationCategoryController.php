<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Affiliation\RegistCorpRequest;
use App\Repositories\Eloquent\MCorpNewYearRepository;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MItemRepositoryInterface;
use App\Services\Affiliation\AffiliationCorpService;
use App\Services\Affiliation\AffiliationMCorpCategoryService;
use App\Services\CommissionInfoService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AffiliationCategoryController extends BaseController
{
    /**
     * @var AffiliationCorpService
     */
    private $affiliationCorpService;

    /**
     * @var AffiliationMCorpCategoryService
     */
    private $affMCorpCategoryService;

    /**
     * @var CommissionInfoService
     */
    private $commissionInfoService;

    /**
     * @var MItemRepositoryInterface
     */
    private $mItemRepository;

    /**
     * AffiliationCategoryController constructor.
     *
     * @param AffiliationCorpService $affiliationCorpService
     * @param AffiliationMCorpCategoryService $affMCorpCategoryService
     * @param CommissionInfoService $commissionInfoService
     * @param MItemRepositoryInterface $mItemRepository
     */
    public function __construct(
        AffiliationCorpService $affiliationCorpService,
        AffiliationMCorpCategoryService $affMCorpCategoryService,
        CommissionInfoService $commissionInfoService,
        MItemRepositoryInterface $mItemRepository
    ) {
        parent::__construct();
        $this->middleware('affiliation.check.corpId');
        $this->affiliationCorpService = $affiliationCorpService;
        $this->affMCorpCategoryService = $affMCorpCategoryService;
        $this->commissionInfoService = $commissionInfoService;
        $this->mItemRepository = $mItemRepository;
    }

    /**
     * Detail Affiliation Category
     *
     * @param  integer $id
     * @return Factory|View
     * @throws NotFoundHttpException
     */
    public function category($id)
    {
        if (empty($id) || !is_numeric($id)) {
            throw new NotFoundHttpException();
        }

        if (auth()->user()->affiliation_id > 0 && auth()->user()->affiliation_id != $id) {
            return view('errors.401');
        }
        //region Get Prepare Data
        $corpData = $this->affiliationCorpService->getMCorpData($id);
        if (!isset($corpData)) {
            throw new NotFoundHttpException();
        }
        $affiliationInfo = $this->affiliationCorpService->getAffiliationInfo($id);
        $genreList = $this->affMCorpCategoryService->getGenreList($id);
        $prefList = $this->affiliationCorpService->getPrefList($id);
        $mCorpSub = $this->affiliationCorpService->getMCorpSubByMCorpId($id);

        $auctionDeliveryStatusList = config('rits.auction_delivery_status');
        array_walk($auctionDeliveryStatusList, function (&$value) {
            $value = __("rits_config.$value");
        });

        $prefectureDivList = config('rits.prefecture_div');
        array_walk($prefectureDivList, function (&$value) {
            $value = __("rits_config.$value");
        });
        $mobilePhoneTypeList = $this->mItemRepository->getListByCategoryItem(MItemRepository::LIST_MOBILE_PHONE_TYPES); // Get List Mobile phone types
        $customerInfoContactList = $this->mItemRepository->getListByCategoryItem(MItemRepository::CUSTOMER_INFORMATION_CONTACT_METHOD); //Customer information contact
        $businessHolidayList = $this->mItemRepository->getListByCategoryItem(MItemRepository::HOLIDAYS); // Business Holiday List
        $vacationList = $this->mItemRepository->getListByCategoryItem(MItemRepository::LONG_HOLIDAYS); // Retrieve closed days
        $newYearStatusOptions = MCorpNewYearRepository::NEW_YEAR_STATUS_OPTIONS;
        $corpHolidays = $mCorpSub['holiday'];
        $corpDevelopmentResponse = $mCorpSub['developmentResponse'];
        //endregion

        //region Calculate Data
        $affiliationInfoCredit = null;
        $affiliationInfoCreditRemaining = null;
        if (!empty($affiliationInfo['credit_limit'])) {
            $affiliationInfoCredit = (int)$affiliationInfo['credit_limit'] + (int)$affiliationInfo['add_month_credit'];
            $affiliationInfoCreditRemaining = $affiliationInfoCredit -
                $this->commissionInfoService->checkCredit($id, null, true);
        }
        //endregion

        // Render view
        $viewData = [
            'id' => $id,
            'genreCustomAreaListA' => $genreList['genreCustomAreaListA'],
            'genreCustomAreaListB' => $genreList['genreCustomAreaListB'],
            'genreNormalAreaListA' => $genreList['genreNormalAreaListA'],
            'genreNormalAreaListB' => $genreList['genreNormalAreaListB'],
            'lastItemGenre' => $genreList['lastItemGenre'],
            'corpData' => $corpData,
            'prefList' => $prefList,
            'affiliationInfo' => $affiliationInfo,
            'auctionDeliveryStatusList' => $auctionDeliveryStatusList,
            'prefectureDivList' => $prefectureDivList,
            'mobilePhoneTypeList' => $mobilePhoneTypeList,
            'customerInfoContactList' => $customerInfoContactList,
            'businessHolidayList' => $businessHolidayList,
            'vacationList' => $vacationList,
            'corpHolidays' => $corpHolidays,
            'corpDevelopmentResponse' => $corpDevelopmentResponse,
            'newYearStatusOptions' => $newYearStatusOptions,
            'affiliationInfoCredit' =>
                isset($affiliationInfoCredit) ? number_format($affiliationInfoCredit) : null,
            'affiliationInfoCreditRemaining' =>
                isset($affiliationInfoCreditRemaining) ? number_format($affiliationInfoCreditRemaining) : null,
        ];


        return $this->renderView('affiliation.category', $viewData);
    }

    /**
     *  Regist/Update the Corp
     *
     * @param  $id
     * @param  RegistCorpRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function updateCorp($id, RegistCorpRequest $request)
    {
        $inputData = $request->all();
        $result = $this->affiliationCorpService->updateCorp($id, $inputData['data']);
        if (empty($result)) {
            return redirect()->back()
                ->with('error', trans('affiliation.m_corp_category_error_updating'))->withInput();
        } else {
            if ($result['success']) {
                redirect()->back()->with('success', $result['message']);
            } else {
                if (isset($result['type']) && $result['type'] == 'field') {
                    redirect()->back()->withErrors($result['message'])->withInput();
                } else {
                    redirect()->back()->with('error', $result['message'])->withInput();
                }
            }
        }

        return redirect()->route('affiliation.category', ['id' => $id]);
    }

    /**
     * Update status mCorp category
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function updateStatusMCorpCategory($id, Request $request)
    {
        $inputData = [
            'auction_status' => $request->get('auction_status')
        ];

        $result = $this->affMCorpCategoryService->updateStatusMCorpCategory($id, $inputData);
        if (!$result) {
            return redirect()->back()->withErrors(trans('affiliation.not_empty_affiliation_base_item'));
        }
        return redirect()->back()->with('success', __('common.updated_completed'));
    }
}
