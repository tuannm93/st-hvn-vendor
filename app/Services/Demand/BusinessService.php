<?php

namespace App\Services\Demand;

use App\Models\DemandInfo;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\MSiteCategoryRepositoryInterface;
use App\Repositories\MSiteGenresRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\Auction\AuctionService;
use App\Services\CommissionService;
use App\Services\Credit\CreditService;

class BusinessService extends BaseDemandInfoService
{
    /**
     * @var CreditService
     */
    public $creditService;
    /**
     * @var CommissionService
     */
    public $commissionService;
    /**
     * @var AuctionService service
     */
    public $auctionService;
    /**
     * @var MPostRepositoryInterface
     */
    public $mPostRepo;

    /**
     * @var MSiteGenresRepositoryInterface
     */
    protected $mSiteGenresRepo;

    /**
     * @var MSiteCategoryRepositoryInterface
     */
    protected $mSiteCategoryRepo;

    /**
     * @var MUserRepositoryInterface
     */
    protected $mUserRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCateRepo;

    /**
     * @var mixed
     */
    protected $demandInfoService;

    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepo;
    /**
     * @var MSiteRepositoryInterface
     */
    protected $mSiteRepo;

    /**
     * BusinessService constructor.
     * @param MPostRepositoryInterface $mPostRepo
     * @param CreditService $creditService
     * @param CommissionService $commissionService
     * @param AuctionService $auctionService
     */
    public function __construct(
        MPostRepositoryInterface $mPostRepo,
        CreditService $creditService,
        CommissionService $commissionService,
        AuctionService $auctionService,
        MSiteGenresRepositoryInterface $mSiteGenresRepo,
        MSiteCategoryRepositoryInterface $mSiteCategoryRepository,
        MUserRepositoryInterface $mUserRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MItemRepositoryInterface $mItemRepository,
        DemandInfoRepositoryInterface $demandInfoRepository,
        MSiteRepositoryInterface $mSiteRepo
    ) {
        $this->mPostRepo = $mPostRepo;
        $this->creditService = $creditService;
        $this->commissionService = $commissionService;
        $this->auctionService = $auctionService;
        $this->mSiteRepo = $mSiteRepo;
        $this->mSiteGenresRepo = $mSiteGenresRepo;
        $this->mSiteCategoryRepo = $mSiteCategoryRepository;
        $this->mUserRepository = $mUserRepository;
        $this->mCateRepo = $mCategoryRepository;

        $this->mItemRepository = $mItemRepository;
        $this->demandInfoRepo = $demandInfoRepository;
    }

    /**
     * @param string $address1
     * @param string $address2
     * @return string
     */
    public function getTargetJisCd($address1, $address2)
    {
        if (!empty($address1)) {
            $address1 = getDivTextJP('prefecture_div', $address1);
        }
        $lowerAddress2 = '';
        $upperAddress2 = '';
        if (!empty($address2)) {
            $upperAddress2 = $address2;
            $upperAddress2 = str_replace('ヶ', 'ケ', $upperAddress2);
            $upperAddress2 = str_replace('ﾉ', 'ノ', $upperAddress2);
            $upperAddress2 = str_replace('ﾂ', 'ツ', $upperAddress2);
            $lowerAddress2 = $address2;
            $lowerAddress2 = str_replace('ケ', 'ヶ', $lowerAddress2);
            $lowerAddress2 = str_replace('ノ', 'ﾉ', $lowerAddress2);
            $lowerAddress2 = str_replace('ツ', 'ﾂ', $lowerAddress2);
        }
        $mPost = $this->mPostRepo->getJiscdByAddress($address1, $lowerAddress2, $upperAddress2);
        if ($mPost) {
            return $mPost->jis_cd;
        }
        return '';
    }

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @param bool $displayPrice
     * @param bool $mailFlg
     * @return int|string
     */
    public function checkCredit($corpId, $genreId, $displayPrice = false, $mailFlg = true)
    {
        return $this->creditService->checkCredit(
            $corpId,
            $genreId,
            $displayPrice,
            $mailFlg
        );
    }

    /**
     * @param array $conditions
     * @param string $check
     * @return \Illuminate\Support\Collection
     */
    public function getCommissionCorpsBy($conditions, $check = '')
    {
        return $this->commissionService->getCorpList($conditions, $check);
    }

    /**
     * @param array $autoCommissions
     * @param object $defaultFee
     * @param array $commissionInfos
     */
    public function buildCommission($autoCommissions, $defaultFee, $commissionInfos)
    {
        return $this->commissionService->buildCommissionData(
            $autoCommissions,
            $defaultFee,
            $commissionInfos
        );
    }

    /**
     * @param integer $demandId
     * @param array $data
     * @return array|bool
     */
    public function getAuctionForAutoCommissionByDemandId($demandId, $data)
    {
        return $this->auctionService->getAuctionInfoForAutoCommission($demandId, $data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function checkAuctionBy($data)
    {
        return $this->auctionService->checkNumberAuctionInfos($data);
    }


    /**
     * @param DemandInfo $demand
     * @param array $unsets
     * @return mixed
     */
    public function unsetData($demand, $unsets)
    {
        foreach ($unsets as $unset) {
            unset($demand->{$unset});
        }
        return $demand;
    }

    /**
     * @param $demand
     * @param $siteId
     * @return mixed
     */
    public function getDataForDetail($demand, $siteId)
    {
        $data = $this->getCommonData();
        $data['crossSellCategoryList'] = $this->mSiteCategoryRepo->getCategoriesBySite($siteId);
        $data['categoriesDropDownList'] = $this->mCateRepo->getListCategoriesForDropDown($demand->genre_id);
        $data['genresDropDownList'] = config('constant.defaultOption') + $this->mSiteGenresRepo->getGenreBySiteStHide($demand->site_id)->toArray();
        $data['mSiteGenresDropDownList'] = $this->mSiteGenresRepo->getMSiteGenresDropDownBySiteId($demand->site_id);
        $data['customerTel'] = $this->demandInfoRepo->checkIdenticallyCustomer($demand->customer_tel);
        $data['enableSiteId'] = in_array($demand->site_id, [861, 863, 889, 890, 1312, 1313, 1314]);

        return $data;
    }

    /**
     * @des get common data for demand
     * @return mixed
     */
    public function getCommonData()
    {
        $data['mSiteDropDownList'] = $this->mSiteRepo->getListMSitesForDropDown();
        $data['userDropDownList'] = $this->mUserRepository->getListUserForDropDown();
        $data['prefectureDiv'] = $this->translatePrefecture();
        $data['priorityDropDownList'] = $this->getPriorityTranslate();
        $data['mItemsDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(BUILDING_TYPE);
        $data['specialMeasureDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(PROJECT_SPECIAL_MEASURES);
        $data['stClaimDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(REQUEST_ST);
        $data['jbrWorkContentDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(JBR_WORK_CONTENTS);
        $data['orderFailReasonDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(REASON_FOR_LOST_NOTE);
        $data['acceptanceStatusDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(ACCEPTANCE_STATUS);
        $data['quickOrderFailReasonDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(REASON_MISSING_CONTACT);
        $data['demandStatusDropDownList'] = $this->mItemRepository->getMItemListByItemCategory(PROPOSAL_STATUS);

        return $data;
    }

    /**
     * @param $demand
     * @return mixed
     */
    public function setData($demand)
    {
        $demand->cross_sell_source_genre = $demand->genre_id;
        $demand->genre_id = '0';
        $demand->category_id = '0';
        $demand->source_demand_id = $demand->id;
        $demand->same_customer_demand_url = route('demand.detail', ['id' => $demand->id]);
        $demand->contents = '';
        $demand->demand_status = 1;
        $demand->demand_status_before = $demand->demand_status;

        return $demand;
    }

    /**
     * Get genre rank by site id
     *
     * @param  integer $siteId
     * @return NULL|[]
     */
    public function getGenreByRank($siteId)
    {
        if (empty($siteId)) {
            return null;
        }

        $site = $this->mSiteRepo->findById($siteId);

        if ($site->cross_site_flg == 1) {
            $siteId = null;
        }

        $rankList = $this->mSiteGenresRepo->getGenreRankBySiteId($siteId);

        return $rankList;
    }

    public function searchCustmerTel ($customerTel)
    {
        return $this->demandInfoRepo->getCustomerTel($customerTel);
    }
}
