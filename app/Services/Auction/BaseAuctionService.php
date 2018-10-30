<?php

namespace App\Services\Auction;

use App\Models\DemandInfo;
use App\Models\MItem;
use App\Repositories\AffiliationAreaStatRepositoryInterface;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;

class BaseAuctionService
{
    /**
     * @var DemandInfo
     */
    protected $demandInfo;

    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepository;

    /**
     * @var MGenresRepositoryInterface
     */
    protected $genreRepo;

    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;

    /**
     * @var MTimeRepositoryInterface
     */
    protected $timeRepository;

    /**
     * @var MSiteRepositoryInterface
     */
    protected $siteRepository;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $corpCategoryRepository;

    /**
     * @var MCategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var AffiliationAreaStatRepositoryInterface
     */
    protected $affiliationAreaStatRepo;

    /**
     * AuctionControllerService constructor.
     *
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param MGenresRepositoryInterface $genreRepo
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param DemandInfo $demandInfo
     * @param \App\Repositories\MTimeRepositoryInterface $timeRepository
     * @param \App\Repositories\MSiteRepositoryInterface $siteRepository
     * @param \App\Repositories\MCorpCategoryRepositoryInterface $corpCategoryRepository
     * @param \App\Repositories\MCategoryRepositoryInterface $categoryRepository
     * @param \App\Repositories\AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepo
     */
    public function __construct(
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        MGenresRepositoryInterface $genreRepo,
        DemandInfoRepositoryInterface $demandInfoRepository,
        DemandInfo $demandInfo,
        MTimeRepositoryInterface $timeRepository,
        MSiteRepositoryInterface $siteRepository,
        MCorpCategoryRepositoryInterface $corpCategoryRepository,
        MCategoryRepositoryInterface $categoryRepository,
        AffiliationAreaStatRepositoryInterface $affiliationAreaStatRepo
    ) {
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->genreRepo = $genreRepo;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->demandInfo = $demandInfo;
        $this->timeRepository = $timeRepository;
        $this->siteRepository = $siteRepository;
        $this->corpCategoryRepository = $corpCategoryRepository;
        $this->categoryRepository = $categoryRepository;
        $this->affiliationAreaStatRepo = $affiliationAreaStatRepo;
    }

    /**
     * @param object $user user info
     * @param array $dataSession
     * @param array $dataSessionForKameiten
     * @return array
     */
    public function indexAuction($user, $dataSession = null, $dataSessionForKameiten = null)
    {
        $auctionAlreadyData = [];
        $calendarEventData = [];
        if ($this->isRole($user->auth, ['affiliation'])) {
            $orderByForKameiten = $this->formatOptionSearch($dataSessionForKameiten);
            $auctionAlreadyData = $this->auctionInfoRepository->getAuctionAlreadyList($orderByForKameiten, $user->affiliation_id);
            $calendarEventData = $this->getCalendarEventData($auctionAlreadyData);
        }
        $calendarEventData = json_encode($calendarEventData);
        $addressDisclosure = $this->timeRepository->getByItemCategory('address_disclosure');
        $telDisclosure = $this->timeRepository->getByItemCategory('tel_disclosure');
        $supportMessageTime = $this->timeRepository->getSupportMessageTime();
        $orderBy = $this->formatOptionSearchUp($dataSession);
        $results = $this->demandInfoRepository->searchDemandInfoList($dataSession, $orderBy);
        $listMGeners = $this->genreRepo->getList(true);
        $dataSort = $this->getDataSort($dataSession);
        $dataSortForKameiten = $this->getDataSort($dataSessionForKameiten);
        $display = isset($dataSession['display']) && $dataSession['display'] == 1 ? false : true;
        $isRoleAffiliation = $this->isRole($user['auth'], ['affiliation']);
        return [
            'auctionAlreadyData' => $auctionAlreadyData,
            'calendarEventData' => $calendarEventData,
            'results' => $results,
            'addressDisclosure' => $addressDisclosure,
            'telDisclosure' => $telDisclosure,
            'supportMessageTime' => $supportMessageTime,
            'listMGeners' => $listMGeners,
            'dataSort' => $dataSort,
            'dataSortForKameiten' => $dataSortForKameiten,
            'isRoleAffiliation' => $isRoleAffiliation,
            'display' => $display,
            'buildingType' => MItem::BUILDING_TYPE,
        ];
    }

    /**
     * @param array $input
     * @return array
     */
    public static function formatOptionSearch($input)
    {
        $sort = isset($input['sort']) ? $input['sort'] : null;
        $orderType = isset($input['order']) ? $input['order'] : 'asc';

        return self::getOrderInformation($sort, $orderType);
    }

    /**
     * get order information
     *
     * @param  string $sort
     * @param  string $orderType
     * @return array
     */
    protected static function getOrderInformation($sort, $orderType)
    {
        switch ($sort) {
            case 'demand_id':
                $order = ['auction_infos.demand_id' => $orderType];
                break;
            case 'visit_time':
                $order = ['visit_times.visit_time' => $orderType];
                break;
            case 'contact_desired_time':
                $order = ['demand_infos.contact_desired_time' => $orderType];
                break;
            case 'genre_name':
                $order = ['m_genres.genre_name' => $orderType];
                break;
            case 'customer_name':
                $order = ['demand_infos.customer_name' => $orderType];
                break;
            case 'tel1':
                $order = ['demand_infos.tel1' => $orderType];
                break;
            case 'address':
                $order = [
                    'demand_infos.address1' => $orderType,
                    'demand_infos.address2' => $orderType,
                    'demand_infos.address3' => $orderType,
                ];
                break;
            default:
                $order = ['visit_times.visit_time' => $orderType];
                break;
        }

        return $order;
    }

    /**
     * check role
     *
     * @param  string $role
     * @param  array $roleOption
     * @return boolean
     */
    public static function isRole($role, $roleOption)
    {
        return in_array($role, $roleOption) ? true : false;
    }

    /**
     * format option search
     *
     * @param  array $input
     * @return array
     */
    public static function formatOptionSearchUp($input)
    {
        $sort = isset($input['sort']) ? $input['sort'] : null;
        $orderType = isset($input['order']) ? $input['order'] : 'asc';
        if ($sort && strpos($sort, 'demand_infos') !== false) {
            if ($sort == 'demand_infos.visit_time_min') {
                $order = ['visit_time.visit_time_min' => $orderType];
            } else {
                $order = [$sort => $orderType];
            }
        } else {
            $order = ['demand_infos.auction_deadline_time' => $orderType];
        }

        return $order;
    }

    /**
     * Get data sort
     *
     * @param  array $data
     * @return array
     */
    public static function getDataSort($data)
    {
        $dataSort['sort'] = null;
        $dataSort['order'] = null;
        if (!empty($data['sort'])) {
            $dataSort['sort'] = $data['sort'];
        }
        if (!empty($data['order'])) {
            $dataSort['order'] = $data['order'];
        }

        return $dataSort;
    }

    /**
     * Get data for calendar event
     *
     * @param array $auctionAlreadyData
     * @return array
     */
    public static function getCalendarEventData($auctionAlreadyData)
    {
        $countAuctionAlreadyData = count($auctionAlreadyData);
        if (!$countAuctionAlreadyData) {
            return [];
        }
        $ordersCorrespondenceStr = __('auction.Orders correspondence string');
        $results = [];
        foreach ($auctionAlreadyData as $auction) {
            $dataArray = self::formatDataAuctionAlready($auction);
            if (isset($auction->order_respond_datetime)) {
                $dataArray['display_date'] = $auction->order_respond_datetime;
                $dataArray['dialog_display_date'] = $ordersCorrespondenceStr.dateTimeWeek($auction->order_respond_datetime);
            }

            $dataArray['commission_id'] = $auction->commission_infos_id;
            $dataArray['demand_id'] = $auction->demand_id;
            $dataArray['customer_name'] = $auction->customer_name;
            $dataArray['site_name'] = $auction->site_name;

            if (isset($dataArray['dialog_display_date']) && strstr($dataArray['dialog_display_date'], '〜')) {
                $fromDate = mb_substr($dataArray['dialog_display_date'], 0, 25);
                $dataArray['sort_date'] = (isset($fromDate) ? substr($fromDate, -5) : '');
            } else {
                $dataArray['sort_date'] = (isset($dataArray['dialog_display_date']) ? substr($dataArray['dialog_display_date'], -5) : '');
            }

            $displayDateSplitArr = preg_split('/[\s]+/', $dataArray['display_date'], -1, PREG_SPLIT_NO_EMPTY);

            if (empty($displayDateSplitArr[0])) {
                $displayDateSplitArr = [];
                $displayDateSplitArr[0] = 'dumm';
            }

            $key = $displayDateSplitArr[0];
            if (isset($results[$key])) {
                array_push($results[$key], $dataArray);
            } else {
                $results[$key] = [$dataArray];
            }
        }

        return self::formatCalendarEventData($results);
    }

    /**
     * format data auction already
     *
     * @param  object $auction
     * @return array
     */
    private static function formatDataAuctionAlready($auction)
    {
        $visitHopeStr = __('auction.Visit hope string');
        $telHopeStr = __('auction.Tel hope string');
        $dataArray['display_date'] = null;
        if ($auction->is_contact_time_range_flg == 0 && isset($auction->contact_desired_time)) {
            return self::contactTimeAndDesiredTime($dataArray, $auction, $visitHopeStr);
        } elseif ($auction->is_contact_time_range_flg == 1 && isset($auction->contact_desired_time_from) && isset($auction->contact_desired_time_to)) {
            return self::visitDesiredTime($dataArray, $auction, $visitHopeStr, $telHopeStr);
        } elseif ($auction->is_visit_time_range_flg == 0 && isset($auction->visit_time)) {
            return self::visitTime($dataArray, $auction, $visitHopeStr);
        } elseif ($auction->is_visit_time_range_flg == 1 && isset($auction->visit_time_from)) {
            return self::visitTimeFrom($dataArray, $auction, $visitHopeStr);
        }

        return $dataArray;
    }

    /**
     * @param array $dataArray
     * @param object $auction
     * @param string $visitHopeStr
     * @return array
     */
    private static function contactTimeAndDesiredTime($dataArray, $auction, $visitHopeStr)
    {
        if (isset($auction->visit_desired_time)) {
            $dataArray['display_date'] = $auction->visit_desired_time;
            $dataArray['dialog_display_date'] = $visitHopeStr.dateTimeWeek($auction->visit_desired_time);
        } else {
            $dataArray['display_date'] = $auction->contact_desired_time;
            $dataArray['dialog_display_date'] = $visitHopeStr.dateTimeWeek($auction->contact_desired_time);
        }

        return $dataArray;
    }

    /**
     * @param array $dataArray
     * @param object $auction
     * @param string $visitHopeStr
     * @param string $telHopeStr
     * @return array
     */
    private static function visitDesiredTime($dataArray, $auction, $visitHopeStr, $telHopeStr)
    {
        if (isset($auction->visit_desired_time)) {
            $dataArray['display_date'] = $auction->visit_desired_time;
            $dataArray['dialog_display_date'] = $visitHopeStr.dateTimeWeek($auction->visit_desired_time);
        } else {
            $dataArray['display_date'] = $auction->contact_desired_time_from;
            $dataArray['dialog_display_date'] = $telHopeStr.dateTimeWeek($auction->contact_desired_time_from)."〜".dateTimeWeek($auction->contact_desired_time_to);
        }

        return $dataArray;
    }

    /**
     * @param array $dataArray
     * @param object $auction
     * @param string $visitHopeStr
     * @return array
     */
    private static function visitTime($dataArray, $auction, $visitHopeStr)
    {
        $dataArray['display_date'] = $auction->visit_time;
        $dataArray['dialog_display_date'] = $visitHopeStr.dateTimeWeek($auction->visit_time);

        return $dataArray;
    }

    /**
     * @param array $dataArray
     * @param object $auction
     * @param string $visitHopeStr
     * @return array
     */
    private static function visitTimeFrom($dataArray, $auction, $visitHopeStr)
    {
        if (isset($auction->visit_desired_time)) {
            $dataArray['display_date'] = $auction->visit_desired_time;
            $dataArray['dialog_display_date'] = $visitHopeStr.dateTimeWeek($auction->visit_desired_time);
        } else {
            $dataArray['display_date'] = $auction->visit_adjust_time;
            $dataArray['dialog_display_date'] = $visitHopeStr.dateTimeWeek($auction->visit_adjust_time);
        }

        return $dataArray;
    }

    /**
     * format calendar event data
     *
     * @param  array $results
     * @return array
     */
    public static function formatCalendarEventData($results)
    {
        $sortResult = [];

        foreach ($results as $resultKey => $valueArr) {
            if (count($valueArr) == 0) {
                $sortResult[$resultKey] = $valueArr;
                continue;
            }

            $keySortDate = [];
            $keyDemandId = [];
            foreach ($valueArr as $key => $value) {
                $keySortDate[$key] = $value['sort_date'];
                $keyDemandId[$key] = $value['demand_id'];
            }
            array_multisort($keySortDate, SORT_ASC, $keyDemandId, SORT_ASC, $valueArr);

            $sortResult[$resultKey] = $valueArr;
        }

        return $sortResult;
    }

    /**
     * Get commission data
     *
     * @param integer $auctionId
     * @return mixed
     */
    public function getCommissionData($auctionId)
    {
        // get maximum number
        $limit = $this->findCommitLimit($auctionId);
        // get number of bids
        $currentNumber = $this->auctionInfoRepository->countByIdAndCommissionCommitFlag($auctionId);
        // When it reaches the upper limit number, it gets the data fixed at the end
        if ($currentNumber >= $limit) {
            return $this->auctionInfoRepository->getByIdAndCommissionCommitFlag($auctionId);
        } else {
            return null;
        }
    }

    /**
     * @param integer $auctionId
     * @return mixed
     */
    private function findCommitLimit($auctionId)
    {
        $data = $this->auctionInfoRepository->getFirstById($auctionId);
        $limitCommit = $this->siteRepository->findMaxLimit($data);

        return $limitCommit;
    }

    /**
     * @param object $user user info
     * @param array $dataRequest
     * @return array
     */
    public function searchAuction($user, $dataRequest = null)
    {
        $addressDisclosure = $this->timeRepository->getByItemCategory('address_disclosure');
        $telDisclosure = $this->timeRepository->getByItemCategory('tel_disclosure');
        $supportMessageTime = $this->timeRepository->getSupportMessageTime();
        $orderBy = $this->formatOptionSearchUp($dataRequest);
        $results = $this->demandInfoRepository->searchDemandInfoList($dataRequest, $orderBy);
        $isRoleAffiliation = $this->isRole($user['auth'], ['affiliation']);
        $buildingType = MItem::BUILDING_TYPE;
        $dataSort = $this->getDataSort($dataRequest);
        return view('auction.search', compact('results', 'addressDisclosure', 'telDisclosure', 'supportMessageTime', 'isRoleAffiliation', 'buildingType', 'dataSort'));
    }

    /**
     * sort auction form kameiten
     * @param object $user user info
     * @param array $dataRequest
     * @return array
     */
    public function sortForKameiten($user, $dataRequest = null)
    {
        $addressDisclosure = $this->timeRepository->getByItemCategory('address_disclosure');
        $telDisclosure = $this->timeRepository->getByItemCategory('tel_disclosure');
        $orderByForKameiten = $this->formatOptionSearch($dataRequest);
        $auctionAlreadyData = $this->auctionInfoRepository->getAuctionAlreadyList($orderByForKameiten, $user->affiliation_id);
        $dataSortForKameiten = $this->getDataSort($dataRequest);
        return view('auction.kameiten', compact('auctionAlreadyData', 'addressDisclosure', 'telDisclosure', 'auctionAlreadyData', 'dataSortForKameiten'));
    }
}
