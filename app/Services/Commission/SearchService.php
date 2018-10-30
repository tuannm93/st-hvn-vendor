<?php

namespace App\Services\Commission;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Services\Affiliation\MCorpService;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

const SEPARATE_DATE_TIME = "〜";

class SearchService extends BaseService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    private $mGenresRepository;
    /**
     * @var MUserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    private $mCategoryRepository;
    /**
     * @var MSiteRepositoryInterface
     */
    private $mSiteRepository;
    /**
     * @var MTimeRepositoryInterface
     */
    private $mTimeRepository;
    /**
     * @var MCorpService
     */
    private $mCorpService;

    /**
     * SearchService constructor.
     *
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param MGenresRepositoryInterface        $mGenresRepository
     * @param MUserRepositoryInterface          $userRepository
     * @param MCategoryRepositoryInterface      $mCategoryRepository
     * @param MSiteRepositoryInterface          $mSiteRepository
     * @param MTimeRepositoryInterface          $mTimeRepository
     * @param MCorpService                      $mCorpService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        MGenresRepositoryInterface $mGenresRepository,
        MUserRepositoryInterface $userRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MSiteRepositoryInterface $mSiteRepository,
        MTimeRepositoryInterface $mTimeRepository,
        MCorpService $mCorpService
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->mGenresRepository = $mGenresRepository;
        $this->userRepository = $userRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mSiteRepository = $mSiteRepository;
        $this->mTimeRepository = $mTimeRepository;
        $this->mCorpService = $mCorpService;
    }

    /**
     * Check role of user
     *
     * @param string $role
     * @param array $roleOption
     * @return bool
     */
    public function isRole($role, $roleOption)
    {
        return in_array($role, $roleOption) ? true : false;
    }

    /**
     * Get list commission
     *
     * @param  array $dataRequest
     * @param  array $dataSession
     * @param  integer $affiliationId
     * @return array
     */
    public function search(
        $dataRequest = null,
        $dataSession = null,
        $affiliationId = null
    ) {
        // Initial data
        $result = [];
        $user = Auth::user();
        $isMobile = isset($dataSession['isMobile']) ? $dataSession['isMobile'] : 0;
        $isRoleAffiliation = $this->isRole($user['auth'], ['affiliation']);
        unset($dataSession['isMobile']);
        // Remove isMobile because check length of $dataSession in get condition function
        $calenderEventData = [];
        $corpLastUpdateProfile = null;
        $corpLastUpdateCategories = null;
        $corpLastUpdateArea = null;
        $notEnough = true;
        $lastSearchData = $dataSession;
        $display = (isset($dataSession['display']) && $dataSession['display'] == 1) ? true : false;
        $dataSort = [];
        try {
            // Search data
            if (!$this->isRole($user->auth, ['affiliation']) && $affiliationId == 'none') {
                $affiliationId = null;
                $results = [];
            } else {
                if (isset($dataSession['demand_id']) && $dataSession['demand_id'] > \Config::get('constant.max_integer')) {
                    $result = [
                        'isMobile' => $isMobile,
                        'calenderEventData' => '',
                        'results' => [],
                        'addressDisclosure' => [],
                        'telDisclosure' => [],
                        'siteList' => [],
                        'genres' => [],
                        'isRoleAffiliation' => $isRoleAffiliation,
                        'display' => $display,
                        'corpLastUpdateProfile' => $corpLastUpdateProfile,
                        'corpLastUpdateCategories' => $corpLastUpdateCategories,
                        'corpLastUpdateArea' => $corpLastUpdateArea,
                        'notEnough' => $notEnough,
                        'dataSort' => $dataSort,
                        'affiliationId' => $affiliationId,
                        'lastSearchData' => $lastSearchData,
                        'totalItemNotRead' => []
                    ];

                    return $result;
                }

                $conditions = $this->getSearchConditions($dataSession, $affiliationId);
                $orderBy = $this->formatOrderBySearch($dataRequest);
                $limit = $isMobile ? 10 : 100;
                $results = $this->commissionInfoRepository->searchCommissionInfo($conditions, $orderBy, $limit);
                $dataSort = $this->getDataSort($dataRequest);
                if ($dataSort['sort'] !== null) {
                    if ($dataSort['order'] !== null) {
                        $results = $results->appends(['sort' => $dataSort['sort'], 'order' => $dataSort['order']]);
                    } else {
                        $results = $results->appends(['sort' => $dataSort['sort']]);
                    }
                }
            }

            // Get calendar event of affiliation user
            if ($this->isRole($user->auth, ['affiliation'])) {
                $corpLastUpdateProfile = $this->mCorpService->getLastMCorpUpdateProfile($user->affiliation_id);
                $corpLastUpdateCategories = $this->mCorpService->getLastMCorpUpdateCategory($user->affiliation_id);
                $corpLastUpdateArea = $this->mCorpService->getLastMCorpUpdateArea($user->affiliation_id);
                $notEnough = $this->mCorpService->checkDataOfCorpById($user->affiliation_id);
                $calenderEventData = $this->getCalenderEventData($results);
            }
            $totalItemNotRead = 0;
            foreach ($results as $item) {
                if ($item->commission_infos_app_notread == 1) {
                    $totalItemNotRead++;
                }
            }
            $calenderEventData = json_encode($calenderEventData);
            $siteList = $this->mSiteRepository->getList();
            $genres = $this->mGenresRepository->getList(true);
            $addressDisclosure = $this->mTimeRepository->getByItemCategory('address_disclosure');
            $telDisclosure = $this->mTimeRepository->getByItemCategory('tel_disclosure');
            $result = [
                'isMobile' => $isMobile,
                'calenderEventData' => $calenderEventData,
                'results' => $results,
                'addressDisclosure' => $addressDisclosure,
                'telDisclosure' => $telDisclosure,
                'siteList' => $siteList,
                'genres' => $genres,
                'isRoleAffiliation' => $isRoleAffiliation,
                'display' => $display,
                'corpLastUpdateProfile' => $corpLastUpdateProfile,
                'corpLastUpdateCategories' => $corpLastUpdateCategories,
                'corpLastUpdateArea' => $corpLastUpdateArea,
                'notEnough' => $notEnough,
                'dataSort' => $dataSort,
                'affiliationId' => $affiliationId,
                'lastSearchData' => $lastSearchData,
                'totalItemNotRead' => $totalItemNotRead
            ];
        } catch (\Exception $exception) {
            Log::error('Search commission error at ' . Carbon::now()->format('Y-m-d H:s:i') . ' with message: ' . $exception->getMessage());
        }
        return $result;
    }

    /**
     * Get event of list commission
     *
     * @param  array $commissionData
     * @return array
     */
    public function getCalenderEventData($commissionData)
    {
        if (count($commissionData) == 0) {
            return [];
        }
        $results = [];
        $visitHopeStr = trans('commissioninfos.lbl.date_dialog_visit_hope') . '：';
        $tellHopeStr = trans('commissioninfos.lbl.date_dialog_tell_hope') . '：';
        $ordersCorrespondenceStr = trans('commissioninfos.lbl.date_dialog_order_correspondence') . '：';
        foreach ($commissionData as $item) {
            $flag = $this->getFlagDialogDate($item);
            $data = $this->getDialogDate($item, $flag, $visitHopeStr, $tellHopeStr, $ordersCorrespondenceStr);
            $data['commission_id'] = $item->commission_infos_id;
            $data['demand_id'] = $item->demand_infos_id;
            $data['customer_name'] = $item->demand_infos_customer_name;
            $data['site_name'] = $item->m_sites_site_name;
            $data = $this->addSortDate($data);
            $displayDateSplitArr = preg_split('/[\s]+/', $data['display_date'], -1, PREG_SPLIT_NO_EMPTY);
            $key = (count($displayDateSplitArr) > 0) ? $displayDateSplitArr[0] : 'key';
            if (isset($results[$key])) {
                array_push($results[$key], $data);
            } else {
                $results[$key] = [$data];
            }
        }

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
     * Get date of event to show in popup in view
     *
     * @param  object $item
     * @param  array $flag
     * @param  string $visitHopeStr
     * @param  string $tellHopeStr
     * @param  string $ordersCorrespondenceStr
     * @return array
     */
    private function getDialogDate($item, $flag, $visitHopeStr, $tellHopeStr, $ordersCorrespondenceStr)
    {
        $data = [];
        $data['display_date'] = null;

        if ($flag['isVisitDesiredTimeFlg']
            && (            $flag['isContactTime']
            || $flag['isContactTimeRange']
            || $flag['isVisitTime']
            || $flag['isVisitTimeRange'])
        ) {
            $data['display_date'] = $item->commission_infos_visit_desired_time;
            $data['dialog_display_date'] = $visitHopeStr . dateTimeWeek($item->commission_infos_visit_desired_time);
        } else {
            if ($flag['isContactTime']) {
                $data['display_date'] = $item->demand_infos_contact_desired_time;
                $data['dialog_display_date'] = $tellHopeStr . dateTimeWeek($item->demand_infos_contact_desired_time);
            } elseif ($flag['isContactTimeRange']) {
                $data['display_date'] = $item->demand_infos_contact_desired_time_from;
                $data['dialog_display_date'] = $tellHopeStr .
                    dateTimeWeek($item->demand_infos_contact_desired_time_from) . SEPARATE_DATE_TIME.
                    dateTimeWeek($item->demand_infos_contact_desired_time_to);
            } elseif ($flag['isVisitTime']) {
                $data['display_date'] = $item->visit_time_view_visit_adjust_time;
                $data['dialog_display_date'] = $tellHopeStr . dateTimeWeek($item->visit_time_view_visit_time);
            } else {
                $data['display_date'] = $item->visit_time_view_visit_adjust_time;
                $data['dialog_display_date'] = $tellHopeStr . dateTimeWeek($item->visit_time_view_visit_adjust_time);
            }
        }

        if (isset($item->commission_infos_order_respond_datetime)) {
            $data['display_date'] = $item->commission_infos_order_respond_datetime;
            $data['dialog_display_date'] = $ordersCorrespondenceStr . dateTimeWeek($item->commission_infos_order_respond_datetime);
        }

        return $data;
    }

    /**
     * Get flag of show date
     *
     * @param object $item
     * @return array
     */
    private function getFlagDialogDate($item)
    {
        $flag['isContactTime'] = $item->demand_infos_is_contact_time_range_flg == 0 &&
            isset($item->demand_infos_contact_desired_time);
        $flag['isContactTimeRange'] = $item->demand_infos_is_contact_time_range_flg == 1 &&
            isset($item->demand_infos_contact_desired_time_from) &&
            isset($item->demand_infos_contact_desired_time_to);
        $flag['isVisitTime'] = $item->visit_time_view_is_visit_time_range_flg == 0 &&
            isset($item->visit_time_view_visit_time);
        $flag['isVisitTimeRange'] = $item->visit_time_view_is_visit_time_range_flg == 1 &&
            isset($item->visit_time_view_visit_time);
        $flag['isVisitDesiredTimeFlg'] = isset($item->commission_infos_visit_desired_time) ? true : false;
        return $flag;
    }

    /**
     * Add sort date to list event and using when sort event by date
     *
     * @param array $data
     * @return array
     */
    private function addSortDate($data)
    {
        if (isset($data['dialog_display_date']) && strstr($data['dialog_display_date'], SEPARATE_DATE_TIME)) {
            $fromDate = mb_substr($data['dialog_display_date'], 0, 25);
            $data['sort_date'] = (isset($fromDate) ? substr($fromDate, -5) : '');
        } else {
            $data['sort_date'] = '';
            if (isset($data['dialog_display_date'])) {
                $stringDate = explode('：', $data['dialog_display_date'], 2)[1];
                if (strlen($stringDate) >=5 ) {
                    $data['sort_date'] = substr($data['dialog_display_date'], -5);
                }
            }
        }
        return $data;
    }

    /**
     * Get order of search function by param request
     *
     * @param array $dataRequest
     * @return array
     */
    public function formatOrderBySearch($dataRequest)
    {
        $sortDefault = 'desc';
        $sort = isset($dataRequest['sort']) ? $dataRequest['sort'] : null;
        $orderType = isset($dataRequest['order']) ? $dataRequest['order'] : $sortDefault;
        $order = $this->demandOrderByInSearch($sort, $orderType);
        if (count($order) == 0 && $sort !== 'contact_desired_time') {
            switch ($sort) {
                case 'status':
                    $order = ['status' => $orderType];
                    break;
                case 'visit_time_min':
                    $order = ['visit_time_view.visit_time' => $orderType];
                    break;
                case 'corp_name':
                    $order = ['m_corps.corp_name' => $orderType];
                    break;
                case 'site_name':
                    $order = ['m_sites.site_name' => $orderType];
                    break;
                case 'item_name':
                    $order = ['m_items.item_name' => $orderType,];
                    break;
                default:
                    $order = [
                        'status' => $orderType,
                        'commission_infos.demand_id' => $orderType,
                        'commission_infos.id' => $orderType
                    ];
                    break;
            }
        }
        return $order;
    }

    /**
     * Get order of demand group in search function by param request
     *
     * @param string $sort
     * @param string $orderType
     * @return array
     */
    private function demandOrderByInSearch($sort, $orderType)
    {
        $order = [];
        switch ($sort) {
            case 'selection_system':
                $order = ['demand_infos.selection_system' => $orderType];
                break;
            case 'demand_id':
                $order = ['demand_infos.id' => $orderType];
                break;
            case 'contact_desired_time':
//                $order = ['demand_infos.contact_desired_time' => $orderType];
                break;
            case 'tel1':
                $order = ['demand_infos.tel1' => $orderType];
                break;
            case 'address1':
                $order = ['demand_infos.address1' => $orderType];
                break;
            case 'receive_datetime':
                $order = ['demand_infos.receive_datetime' => $orderType];
                break;
            default:
                break;
        }
        return $order;
    }

    /**
     * Get value of sort data in request param
     *
     * @param array $data
     * @return array
     */
    public function getDataSort($data)
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
     * Get search conditions by request data
     *
     * @param array $data
     * @param integer $id
     * @return array
     */
    public function getSearchConditions($data, $id = null)
    {
        $conditions = [
            'where' => [
                ['commission_infos.commit_flg', '=', 1],
                ['commission_infos.lost_flg', '!=', 1],
                ['commission_infos.del_flg', '=', 0],
                ['commission_infos.introduction_not', '!=', 1]
            ],
            'whereIn' => [],
            'whereOr' => [],
            'whereRaw' => []
        ];
        $conditions = $this->getConditionsByCustomer($data, $conditions);
        if (\Auth::user()->auth == "affiliation") {
            $conditions['where'][] = ['m_corps.id', '=', \Auth::user()->affiliation_id];
        } else {
            if (!empty($id)) {
                $conditions['where'][] = ['m_corps.id', '=', (int) $id];
            }
        }
        $conditions = $this->getConditionForListItem($data, $conditions);
        if (!empty($data['jbr_order_no'])) {
            $conditions['whereRaw'][] = ["z2h_kana(demand_infos.jbr_order_no) = ?", chgSearchValue($data['jbr_order_no'])];
        }
        $conditions = $this->getDatetimeConditions($data, $conditions);
        $conditions = $this->getInitialConditions($data, $conditions);
        return $conditions;
    }


    /**
     * Get datetime conditions by request data
     *
     * @param array $data
     * @param array $conditions
     * @return array
     */
    private function getDatetimeConditions($data, $conditions)
    {
        if (!empty($data['commission_date1'])) {
            $conditions['where'][] = ['commission_infos.commission_note_send_datetime', '>=', $data['commission_date1'] . " 00:00:00"];
        }
        if (!empty($data['commission_date2'])) {
            $conditions['where'][] = ['commission_infos.commission_note_send_datetime', '<=', $data['commission_date2'] . " 23:59:59"];
        }
        if (!empty($data['visit_desired_time'])) {
            $conditions['where'][] = ['visit_time_view.visit_time', '>=', $data['visit_desired_time'] . " 00:00:00"];
            $conditions['where'][] = ['visit_time_view.visit_time', '<=', $data['visit_desired_time'] . " 23:59:59"];
        }
        if (!empty($data['contact_desired_time'])) {
            $conditions['where'][] = ['demand_infos.contact_desired_time', '>=', $data['contact_desired_time'] . " 00:00:00"];
            $conditions['where'][] = ['demand_infos.contact_desired_time', '<=', $data['contact_desired_time'] . " 23:59:59"];
        }
        return $conditions;
    }

    /**
     * Get conditions when initial search
     *
     * @param array $data
     * @param array $conditions
     * @return array
     */
    private function getInitialConditions($data, $conditions)
    {
        if (empty($data)) {
            $commissionStatus = [
                getDivValue('construction_status', 'received'),
                getDivValue('construction_status', 'progression')
            ];
            $conditions['whereIn'][] = ['commission_infos.commission_status', '=', $commissionStatus];
        }
        $demandStatus = [
            getDivValue('demand_status', 'telephone_already'),
            getDivValue('demand_status', 'information_sent')
        ];
        $conditions['whereIn'][] = ['demand_infos.demand_status', '=', $demandStatus];
        return $conditions;
    }

    /**
     * @param array $data
     * @param array $conditions
     * @return array
     */
    private function getConditionForListItem($data, $conditions)
    {
        if (!empty($data['genre_id'])) {
            $conditions['whereIn'][] = ['demand_infos.genre_id', '=', $data['genre_id']];
        }
        if (!empty($data['site_id'])) {
            $conditions['whereIn'][] = ['demand_infos.site_id', '=', $data['site_id']];
        }
        return $conditions;
    }

    /**
     *
     * @param array $data
     * @param array $conditions
     * @return array
     */
    private function getConditionsByCustomer($data, $conditions)
    {
        if (!empty($data['customer_name'])) {
            $conditions['whereRaw'][] = ["z2h_kana(demand_infos.customer_name) like ?", '%' . chgSearchValue($data['customer_name']) . '%'];
        }
        if (!empty($data['demand_id'])) {
            $conditions['where'][] = ['commission_infos.demand_id', '=', chgSearchValue($data['demand_id'])];
        }
        if (!empty($data['customer_tel'])) {
            $conditions['whereOr'][] = [
                ['demand_infos.customer_tel', '=', $data['customer_tel']],
                ['demand_infos.customer_tel', '=', $data['customer_tel']],
                ['demand_infos.customer_tel', '=', $data['customer_tel']]
            ];
        }
        if (!empty($data['commission_status'])) {
            $arrStatus = [];
            foreach ($data['commission_status'] as $commissionStatus) {
                $arrStatus[] = chgSearchValue($commissionStatus);
            }
            $conditions['whereIn'][] = ['commission_infos.commission_status', '=', $arrStatus];
        }
        return $conditions;
    }
}
