<?php

namespace App\Services\Auction;

use App\Repositories\AccumulatedInformationsRepositoryInterface;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\AutoCallRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\RefusalsRepositoryInterface;
use App\Services\Credit\CreditService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuctionService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepository;
    /**
     * @var RefusalsRepositoryInterface
     */
    protected $refusalRepository;
    /**
     * @var AccumulatedInformationsRepositoryInterface
     */
    protected $accumulatedInfoRepo;
    /**
     * @var MCorpRepositoryInterface
     */
    protected $corpRepository;
    /**
     * @var MPostRepositoryInterface
     */
    protected $postRepository;
    /**
     * @var CreditService
     */
    protected $creditService;
    /**
     * @var AutoCallRepositoryInterface
     */
    protected $autoCallItemRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $genreRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    protected $categoryRepo;

    /**
     * AuctionService constructor.
     *
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param RefusalsRepositoryInterface $refusalsRepository
     * @param AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo
     * @param MCorpRepositoryInterface $corpRepository
     * @param MPostRepositoryInterface $postRepository
     * @param AutoCallRepositoryInterface $autoCallItemRepo
     * @param MGenresRepositoryInterface $genreRepo
     * @param MCategoryRepositoryInterface $categoryRepo
     * @param CreditService $creditService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        RefusalsRepositoryInterface $refusalsRepository,
        AccumulatedInformationsRepositoryInterface $accumulatedInfoRepo,
        MCorpRepositoryInterface $corpRepository,
        MPostRepositoryInterface $postRepository,
        AutoCallRepositoryInterface $autoCallItemRepo,
        MGenresRepositoryInterface $genreRepo,
        MCategoryRepositoryInterface $categoryRepo,
        CreditService $creditService
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->refusalRepository = $refusalsRepository;
        $this->accumulatedInfoRepo = $accumulatedInfoRepo;
        $this->corpRepository = $corpRepository;
        $this->postRepository = $postRepository;
        $this->creditService = $creditService;
        $this->autoCallItemRepo = $autoCallItemRepo;
        $this->genreRepo = $genreRepo;
        $this->categoryRepo = $categoryRepo;
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
     * ge div value
     *
     * @param  string $code
     * @param  string $text
     * @return string
     */
    public static function getDivValue($code, $text)
    {
        $data = array_flip(config('rits.' . $code));
        return @$data[$text];
    }

    /**
     * get order by sort item
     *
     * @param  string $sort
     * @param  string $order
     * @param  string $sortValue
     * @return array|string
     */
    public static function getInforOrderSort($sort, $order, $sortValue)
    {
        $orderDisplay = 'desc';
        $icon = '';
        if ($sort == $sortValue) {
            $icon = trans('common.asc');
            if ($order == 'desc') {
                $orderDisplay = 'asc';
                $icon = trans('common.desc');
            }
        }
        return [
            'order_display' => $orderDisplay,
            'icon' => $icon,
        ];
    }

    /**
     * get status detach auction btn
     *
     * @param array $data
     * @return int
     */
    public static function detectAuctionBtn($data)
    {
        $user = Auth::user();
        if ($user['auth'] != 'affiliation') {
            return 0;
        }
        $commissionInfoIds = DB::table('commission_infos')
            ->where('demand_id', $data->id)
            ->where('commit_flg', 1)
            ->pluck('corp_id')->toArray();
        if (in_array($user['affiliation_id'], $commissionInfoIds)) {
            return 1;
        }
        $mSite = DB::table('m_sites')->select('manual_selection_limit', 'auction_selection_limit')
            ->where('id', $data->site_id)->first();
        if (!$mSite) {
            $max = 0;
        } else {
            if ($data->selection_system == 2 || $data->selection_system == 3) {
                $max = $mSite->auction_selection_limit;
            } else {
                $max = $mSite->manual_selection_limit;
            }
        }

        if (count($commissionInfoIds) >= $max) {
            return 2;
        }
        if (strtotime($data->auction_deadline_time) <= strtotime(date('Y-m-d H:i:s'))) {
            return 3;
        }
        if ($data->auction == 1) {
            return 4;
        }
        return 5;
    }

    /**
     * find last commission
     * @param  array $data
     * @param  string $field
     * @return object
     */
    public static function findLastCommission($data, $field = null)
    {
        $commissionInfo = DB::table('commission_infos')
            ->where('demand_id', $data->id)
            ->where('commit_flg', 1)
            ->orderBy('created', 'desc')->first();
        if ($field) {
            if (isset($commissionInfo->$field)) {
                return $commissionInfo->$field;
            }
        }
        return $commissionInfo;
    }

    /**
     * get config jp text
     * @param  string $code
     * @param  string $key
     * @param  boolean $priority
     * @return array|string
     */
    public static function getDivTextJP($code, $key, $priority = false)
    {
        try {
            if (!$priority) {
                if ($key < 10) {
                    $key = '0' . (int)$key;
                }
                if (config('app.locale') == 'jp') {
                    return config('jpstate.kanji')[$key];
                } else {
                    return config('jpstate.romaji')[$key];
                }
            } else {
                return __('auction.' . config('rits.' . $code)[$key]);
            }
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * format currency
     * @param  string $amount
     * @return integer
     */
    public static function yenFormat2($amount)
    {
        if (is_numeric($amount)) {
            return number_format($amount) . __('common.yen');
        } else {
            return '0' . __('common.yen');
        }
    }

    /**
     * masking address3
     * @param  string $address3
     * @return string
     */
    public static function maskingAddress3($address3)
    {
        if (is_null($address3)) {
            return '';
        }
        return mb_substr($address3, 0, 3, "UTF-8") . '*******';
    }

    /**
     * Get drop text
     *
     * @param  string $category
     * @param  integer $itemId
     * @return string
     */
    public static function getDropText($category, $itemId)
    {
        $item = DB::table('m_items')->select('item_name')
            ->where('item_category', $category)
            ->where('item_id', $itemId)
            ->orderBy('sort_order', 'desc')
            ->first();
        if (!$item) {
            return null;
        }
        return $item->item_name;
    }

    /**
     * @param $data
     * @return boolean|mixed
     * @throws Exception
     */
    public function editRefusal($data)
    {
        DB::beginTransaction();
        try {
            $resultsFlg = $this->refusalRepository->updateData($data['auctionInfo']['id'], $data['refusal']);
            if ($resultsFlg) {
                $resultsFlg = $this->auctionInfoRepository->updateFlag($data['auctionInfo']);
            }
            if ($resultsFlg) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } catch (Exception $e) {
            DB::rollback();
            $resultsFlg = false;
        }
        return $resultsFlg;
    }

    /**
     * Update accumulated info with refusal date
     * @param $demandId
     * @param $corpId
     */
    public function updateAccumulatedInfoRefusalDate($demandId, $corpId)
    {
        if (empty($demandId) || empty($corpId)) {
            return;
        }
        try {
            $accumulatedInformation = $this->accumulatedInfoRepo->getInfos($corpId, $demandId);
            if ($accumulatedInformation) {
                $accumulatedInformation->refusal_date = date('Y-m-d H:i');
                $accumulatedInformation->modified_user_id = $corpId;
                $accumulatedInformation->save();
            }
        } catch (Exception $e) {
            logger('AccumulatedInformation fail: ' . $e->getMessage());
        }
        return;
    }

    /**
     * @param integer $corpId
     */
    public function updatePopupStopFlg($corpId)
    {
        $mCorp = $this->corpRepository->getFirstById($corpId);
        if (isset($mCorp)) {
            $mCorp->popup_stop_flg = 1;
            $mCorp->save();
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function checkNumberAuctionInfos($data)
    {
        // Search characters in municipalities for upper and lower case letters (m_posts.address 2)
        $demandCorps = $this->getDemandCorpData($data['demandInfo'], true);
        if (empty($demandCorps)) {
            // If there are 0 merchants that match genre and area, return to manual selection.
            return false;
        }
        $rankCount = 0;
        $beforeList = $this->getBeforeList($data['demandInfo']);
        foreach ($demandCorps as $val) {
            // If lost_flg, del_flg registered in Commission_infos is "1", it will not be subject to bidding
            $rankCount += $this->countRankOrUpdateAuction($val, $data['demandInfo'], $beforeList);
        }
        if ($rankCount == 0) {
            // If there is no member store that matches the unit price rank, return to manual selection.
            return false;
        }
        return true;
    }

    /**
     * @author  thaihv
     * get demand corp data, exclude credit danger
     * @param  array $demandInfo demand info data
     * @param  boolean $onlyDoAuction only check doauction
     * @return array             demand_corp data
     */
    private function getDemandCorpData($demandInfo, $onlyDoAuction = false)
    {
        //In case of automatic consignment, auction is not selected
        if (!((!empty($demandInfo['do_auction'])|| $onlyDoAuction)
                    && $demandInfo['do_auto_selection_category'] == 0)) {
            return [];
        }

        // Acquisition of list of companies corresponding to the case
        $mPostJscd = $this->postRepository->getTargetArea([
            'address1' => $demandInfo['address1'],
            'address2' => $demandInfo['address2'],
        ]);
        if (empty($mPostJscd)) {
            Log::debug('____ empty mPostJscd  _______');
            return [];
        }
        $corpData = [
            'genre_id' => $demandInfo['genre_id'],
            'site_id' => $demandInfo['site_id'],
            'category_id' => $demandInfo['category_id'],
            'address1' => $demandInfo['address1'],
            'jis_cd' => $mPostJscd,
        ];
        $demandCorps = $this->corpRepository->demandCorpData($corpData, true);
        $filterData = [];
        foreach ($demandCorps as $val) {
            // If there is an agency method by genre (not 0) overwrite the variable.
            if ($this->excludeDemandByCreditAndSite($demandInfo, $val->id)) {
                continue;
            }
            $filterData[] = $val;
        }
        return $filterData;
    }

    /**
     * exclude some corp if belongs to exclude site, or credit is danger
     * @author  thaihv
     * @param  array $demandInfo demand info data
     * @return boolean corp tobe excluded
     */
    private function excludeDemandByCreditAndSite($demandInfo, $corpId)
    {
        $resultCredit = $this->creditService->checkCredit($corpId, $demandInfo['genre_id'], false, true);
        if ($resultCredit == config('constant.CREDIT_DANGER')) {
            //If the credit limit is exceeded, subsequent processing is not performed
            return true;
        }
        // In case of an unapproved franchisee, no further processing is done
        if ($demandInfo['site_id'] != config('rits.CREDIT_EXCLUSION_SITE_ID')) {
            if (is_null($demandInfo['category_id'])) {
                return false;
            }
            return !$this->corpRepository->isCommissionStop($corpId);
        }
        return false;
    }

    /**
     * get list auction before by deman_info id
     * @author  thaihv
     * @param  array $demandInfo demand info data
     * @return array  list auction
     */
    private function getBeforeList($demandInfo)
    {
        if (empty($demandInfo['id'])) {
            return [];
        }
        $beforeList = [];
        $beforeData = $this->auctionInfoRepository->getListByDemandId($demandInfo['id']);
        foreach ($beforeData as $val) {
            $beforeList[$val->corp_id] = $val->id;
        }
        return $beforeList;
    }

    /**
     * @author thaihv
     * @param object $corp corp data
     * @param object $demandInfo demand info data
     * @param array $beforeList
     * @return int rank count
     */
    private function countRankOrUpdateAuction($corp, $demandInfo, $beforeList)
    {
        $rankCount = 0;
        //Acquire transmission time per rank
        $rankTime = $this->getRankTime($demandInfo);
        $demandId = !empty($demandInfo['id']) ? $demandInfo['id'] : null;
        // If lost_flg, del_flg registered in Commission_infos is "1", it will not be subject to bidding
       $targetCommissionData = null;
        if (!empty($demandId)) {
            $targetCommissionData = $this->commissionInfoRepository->getFirstByDemandIdAndCorpId($demandId, $corp->id);
        }
        // A franchise with lost_flg and del_flg of "0" in the retrieved pickup data is targeted for bidding
        if (!$targetCommissionData
            || ($targetCommissionData->lost_flg == 0)
            && ($targetCommissionData->del_flg == 0)
        ) {
            //When the category-based method of setting is set, use it for judgment
            $rankCount = $this->countRankTime($corp, $rankCount, $rankTime);
        } elseif (isset($beforeList[$corp->id])) {
            // At the time of re-bidding, the member shop with lost_flg and del_flg being 1
            // declines that it has declined, and sets the declaration flag to 1
            $auctionId = isset($beforeList[$corp->id]) ? $beforeList[$corp->id] : null;
            //To write
            $this->auctionInfoRepository->saveAuction($auctionId, ['refusal_flg' => 1]);
        }
        return $rankCount;
    }

    /**
     * get rank time data
     * @author thaihv
     * @param  object $demandInfo demandInfo
     * @return array  rank time
     */
    private function getRankTime($demandInfo)
    {
        if ($demandInfo['method'] == 'visit') {
            return getPushSendTimeOfVisitTime(
                $demandInfo['auction_start_time'],
                $demandInfo['auction_deadline_time'],
                $demandInfo['genre_id'],
                $demandInfo['address1']
            );
        }
        return getPushSendTimeOfContactDesiredTime(
            $demandInfo['auction_start_time'],
            $demandInfo['auction_deadline_time'],
            $demandInfo['genre_id'],
            $demandInfo['address1']
        );
    }

    /**
     * count rank time
     * @author thaihv
     * @param  object $corp corp data
     * @param  integer $countRank
     * @param  array $rankTime
     * @return integer  rank time counted
     */
    private function countRankTime($corp, $countRank, $rankTime)
    {
        $auctionStatusFlg = $corp->m_cate_auction_status != 0 ? $corp->m_cate_auction_status : $corp->auction_status;
        //Change judgment formula
        if (empty($auctionStatusFlg)
            || $auctionStatusFlg == getDivValue('auction_delivery_status', 'delivery')
            || $auctionStatusFlg == getDivValue('auction_delivery_status', 'deny')
        ) {
            $rank = !empty($corp->commission_unit_price_rank) ? mb_strtolower($corp->commission_unit_price_rank) : 'z';
            if (!empty($rankTime[$rank])) {
                $countRank++;
            }
        }
        return $countRank;
    }

    /**
     * @param integer $demandId
     * @param array $data
     * @return array|bool
     */
    public function getAuctionInfoForAutoCommission($demandId, $data)
    {
        // Obtain the date and time of the visit, the desired date and time of contact
        $visitTimeList = $this->buildVisittime($data['visitTime']);

        if (!empty($visitTimeList)) {
            // When to use the visit date and time
            $preferredDate = getMinVisitTime($visitTimeList);
            $data['demandInfo']['method'] = 'visit';
        } else {
            // When using the contact date and time desired
            if ($data['demandInfo']['is_contact_time_range_flg'] == 0) {
                $preferredDate = $data['demandInfo']['contact_desired_time'];
            }
            if ($data['demandInfo']['is_contact_time_range_flg'] == 1) {
                $preferredDate = $data['demandInfo']['contact_desired_time_from'];
            }
            $data['demandInfo']['method'] = 'tel';
        }

        // To acquire mail transmission time
        // Temporarily set DemandInfo.auction_start_time, DemandInfo.auction_deadline_time
        if (empty($data['demandInfo']['auction_start_time'])) {
            $data['demandInfo']['auction_start_time'] = date('Y-m-d H:i:s');
        }
        if (empty($data['demandInfo']['auction_deadline_time'])) {
            $data['demandInfo']['auction_deadline_time'] = '';
        }
        // When the priority is not set yet
        $judgeResult = $this->buildJudeResult($data['demandInfo'], $preferredDate);
        if ($judgeResult['result_flg'] == 1) {
            //Auction start date and time
            if (!empty($judgeResult['result_date'])) {
                $data['demandInfo']['auction_start_time'] = $judgeResult['result_date'];
            }
            //When the priority is changed, the determination for each priority is performed again
            judgeAuction(
                $data['demandInfo']['auction_start_time'],
                $preferredDate,
                $data['demandInfo']['genre_id'],
                $data['demandInfo']['address1'],
                $data['demandInfo']['auction_deadline_time'],
                $data['demandInfo']['priority']
            );
        }
        $autoCommissions = $this->updateAuctionInfos($demandId, $data, true);
        if (is_array($autoCommissions)) {
            return $this->sortAutoCommission($autoCommissions);
        }
        return $autoCommissions;
    }

    /**
     * build visit time
     * @param  array $visitTimes
     * @return array
     */
    private function buildVisittime($visitTimes)
    {
        $visitTimeList = [];
        foreach ($visitTimes as $val) {
            if ($val['is_visit_time_range_flg'] == 0 && strlen($val['visit_time']) > 0) {
                $visitTimeList[] = $val['visit_time'];
            }
            if ($val['is_visit_time_range_flg'] == 1 && strlen($val['visit_time_from']) > 0) {
                $visitTimeList[] = $val['visit_time_from'];
            }
        }
        return $visitTimeList;
    }

    /**
     * @author  thaihv
     * @param $demandInfo
     * @param $preferredDate
     * @return array
     */
    private function buildJudeResult(&$demandInfo, $preferredDate)
    {
        if (empty($demandInfo['priority'])) {
            $judgeResult = judgeAuction(
                $demandInfo['auction_start_time'],
                $preferredDate,
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time'],
                $demandInfo['priority']
            );
        } elseif ($demandInfo['priority'] == getDivValue('priority', 'asap')) { // When the priority is urgent
            $judgeResult = judgeAsap(
                $demandInfo['auction_start_time'],
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time']
            );
        } elseif ($demandInfo['priority'] == getDivValue('priority', 'immediately')) { // When the priority is urgent
            $judgeResult = judgeImmediately(
                $demandInfo['auction_start_time'],
                $preferredDate,
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time']
            );
        } else { // When the priority is normal
            $judgeResult = judgeNormal(
                $demandInfo['auction_start_time'],
                $demandInfo['genre_id'],
                $demandInfo['address1'],
                $demandInfo['auction_deadline_time'],
                $demandInfo['priority']
            );
        }

        return $judgeResult;
    }

    /**
     * @param integer $demandId
     * @param array $data
     * @return array|bool
     */
    public function updateAuctionInfos($demandId, $data, $autoSelection = false)
    {
        $demandCorps = $this->getDemandCorpData($data['demandInfo'], $autoSelection);
        if (empty($demandCorps)) {
            return true;
        }
        // Acquire transmission time per rank
        $rankTime = $this->buildRankTime($data['demandInfo']);
        // Acquire time to auto call
        $autoCall = $this->getAutoCallInterval($data['demandInfo']['priority']);
        // Acquisition of auto call flag by genre
        $autoCallFlag = $this->getAutoCallFlagMGenre($data['demandInfo']['genre_id']);
        $beforeList = $this->getBeforeList(['id' => $demandId]);
        $auctionData = [];
        foreach ($demandCorps as $val) {
            if ($val->m_cate_auction_status != 0) {
                $auctionStatusFlg = $val->m_cate_auction_status;
            } else {
                $auctionStatusFlg = $val->auction_status;
            }
            // Acquire default commission rate and default commission rate unit
            $val = $this->updateOrderFee($val, $data['demandInfo']['category_id']);
            // If lost_flg, del_flg registered in Commission_infos is "1", it will not be subject to bidding
            $targetCommissionData = $this->commissionInfoRepository->getFirstByDemandIdAndCorpId($demandId, $val->id);
            // A franchise with lost_flg and del_flg of "0" in the retrieved pickup data is targeted for bidding
            if (is_null($targetCommissionData)
                || (($targetCommissionData->lost_flg == 0)
                    && ($targetCommissionData->del_flg == 0)
                )
            ) {
                $ranks = $this->buildAuctionData(
                    $demandId,
                    $val,
                    $rankTime,
                    $autoCall,
                    $autoCallFlag,
                    $auctionStatusFlg,
                    $beforeList,
                    $autoSelection
                );
                if (!empty($ranks)) {
                    $auctionData[] = $ranks;
                }
                continue;
            }
            if (isset($beforeList[$val->id])) {
                $auctionId = isset($beforeList[$val->id]) ? $beforeList[$val->id] : '';
                Log::debug('____ call  $this->auctionInfoRepository->saveAuction()  _______');
                $this->auctionInfoRepository->saveAuction($auctionId, ['refusal_flg' => 1]);
            }
        }
        // In case of bidding ceremony selection (manual or automatic)
        if (!empty($auctionData)) {
            Log::debug('____ call  $this->carryAuctionPushTime()  _______');
            $this->carryAuctionPushTime($auctionData, $rankTime, $autoCall);
            Log::debug('____ call  $this->auctionInfoRepository->saveAuctions()  _______');
            if (!$autoSelection) {
                return $this->auctionInfoRepository->saveAuctions($auctionData);
            }

            return $auctionData;
        }
        return true;
    }

    /**
     * build ranktime
     * @author  thaihv
     * @param  array $demandInfo demand info
     * @return array  rank time data
     */
    private function buildRankTime($demandInfo)
    {

        if (isset($demandInfo['method']) && $demandInfo['method'] == 'visit') {
            return getPushSendTimeOfVisitTime(
                $demandInfo['auction_start_time'],
                $demandInfo['auction_deadline_time'],
                $demandInfo['genre_id'],
                $demandInfo['address1']
            );
        }
        return getPushSendTimeOfContactDesiredTime(
            $demandInfo['auction_start_time'],
            $demandInfo['auction_deadline_time'],
            $demandInfo['genre_id'],
            $demandInfo['address1']
        );
    }

    /**
     * Acquire transmission interval until auto call
     * @param  integer $priority
     * @return mixed
     */
    private function getAutoCallInterval($priority)
    {
        $autoCall = $this->autoCallItemRepo->getItem();
        $time = $autoCall->normal;
        if ($priority == getDivValue('priority', 'asap')) {
            $time = $autoCall->asap;
        } elseif ($priority == getDivValue('priority', 'immediately')) {
            $time = $autoCall->immediately;
        }
        return $time;
    }

    /**
     * @param integer t$genreId
     * @return int
     */
    private function getAutoCallFlagMGenre($genreId)
    {
        $result = $this->genreRepo->findById($genreId);
        return !empty($result) ? $result->auto_call_flag : 1;
    }

    /**
     * update order_fee, order_fee_unit
     * @author  thaihv
     * @param  array $corp m_corps data
     * @param  int $categoryId
     * @return array  corp data updated fee
     */
    private function updateOrderFee($corp, $categoryId)
    {
        // Acquire default commission rate and default commission rate unit
        if (!empty($corp->order_fee) || !is_null($corp->order_fee_unit)) {
            return $corp;
        }
        // If it can not be acquired from m_corp_categories, it is obtained from m_categories
        $mcCategoryData = $this->categoryRepo->getFeeData($categoryId);
        if (empty($mcCategoryData)) {
            // If it can not be acquired from m_categories, it is left blank.
            $corp->order_fee = '';
            $corp->order_fee_unit = '';
        }

        return $corp;
    }

    /**
     * build auction data
     * @author  thaihv
     * @param  int $demandId demand id
     * @param  object $corp corp data
     * @param  array $rankTime list rank
     * @param  [type] $autoCall         auto call
     * @param  int $autoCallFlag auto call flag
     * @param  int $auctionStatusFlg auction status flag
     * @param  array $beforeList m_corps list
     * @return array                    auction data
     */
    private function buildAuctionData(
        $demandId,
        $corp,
        $rankTime,
        $autoCall,
        $autoCallFlag,
        $auctionStatusFlg,
        $beforeList,
        $autoSelection
    ) {
        $auctionData = [];
        $rank = 'z';
        if (!empty($corp->commission_unit_price_rank)) {
            $rank = mb_strtolower($corp->commission_unit_price_rank);
        }
        if (!$this->checkAuctionStatusAndRank(!empty($rankTime[$rank]), $auctionStatusFlg)) {
            return $auctionData;
        }
        $auctionData['id'] = isset($beforeList[$corp->id]) ? $beforeList[$corp->id] : '';
        $auctionData['demand_id'] = $demandId;
        $auctionData['corp_id'] = $corp->id;
        $auctionData['push_time'] = $rankTime[$rank];
        $auctionData['push_flg'] = 0;
        $auctionData['before_push_flg'] = 0;
        $auctionData['display_flg'] = 0;
        $auctionData['refusal_flg'] = 0;
        $auctionData['rank'] = $rank; // For extraction per rank
        if ($autoCall != null && $this->isAutoCallable($autoCallFlag, $corp->auto_call_flag)) {
            $time = strtotime($rankTime[$rank] . '+' . $autoCall . 'minute');
            $auctionData['auto_call_time'] = date('Y-m-d H:i', $time);
            $auctionData['auto_call_flg'] = 0;
        } else {
            $auctionData['auto_call_time'] = null;
            $auctionData['auto_call_flg'] = null;
        }
        if ($autoSelection) {
            $auctionData['affiliationAreaStat'] = [
                'commission_unit_price_category' => $corp->commission_unit_price_category,
                'commission_count_category' => $corp->commission_count_category,
                'commission_unit_price_rank' => $corp->commission_unit_price_rank
            ];
            $auctionData['mCorpCategory'] = [
                'order_fee' => $corp->order_fee,
                'order_fee_unit' => $corp->order_fee_unit,
                'introduce_fee' => $corp->introduce_fee,
                'corp_commission_type' => $corp->corp_commission_type,
            ];
            $auctionData['mCorp'] = [
                'id' => $corp->id
            ];
        }
        return $auctionData;
    }

    /**
     * check isset rank and auction status flag
     * @param  boolean $isRank isset rank
     * @param  int $status auction status flag
     * @return boolean
     */
    private function checkAuctionStatusAndRank($isRank, $status)
    {
        return $isRank
            && (empty($status)
            || $status == getDivValue('auction_delivery_status', 'delivery')
            || $status == getDivValue('auction_delivery_status', 'deny'));
    }

    /**
     * @param int $genre
     * @param int $corp
     * @return bool
     */
    private function isAutoCallable($genre = 1, $corp = 1)
    {
        if ($genre != 1) {
            // If auto call flag by genre is auto call disabled, auto call excluded
            return false;
        } elseif ($corp != 1) {
            // If the auto call flag by company is auto call disabled, it is not subject to auto call
            return false;
        }

        return true;
    }

    /**
     * @param array $auctionData
     * @param array $rankTime
     * @param integer $autoCallInterval
     * @return array
     */
    private function carryAuctionPushTime(&$auctionData, $rankTime, $autoCallInterval)
    {
        if (empty($auctionData)) {
            return [];
        }
        $rankTimeSort = [
            ['rank' => 'a', 'rank_time' => $rankTime['a']],
            ['rank' => 'b', 'rank_time' => $rankTime['b']],
            ['rank' => 'c', 'rank_time' => $rankTime['c']],
            ['rank' => 'd', 'rank_time' => $rankTime['d']],
            ['rank' => 'z', 'rank_time' => $rankTime['z']],
        ];
        // Remove the rank whose rank_time is not set yet
        $rankTimeSort = array_filter($rankTimeSort, function ($vr) {
            return !empty($vr['rank_time']);
        });

        uasort($rankTimeSort, function ($first, $second) {
            if ($first['rank_time'] == $second['rank_time']) {
                return ($first['rank'] < $second['rank']) ? -1 : 1;
            }

            return ($first['rank_time'] < $second['rank_time']) ? -1 : 1;
        });

        foreach ($rankTimeSort as $rank) {
            $ranks = array_filter($auctionData, function ($data) use ($rank) {
                return $data['rank'] == $rank['rank'];
            });
            if (empty($ranks) && empty($highestEmptyRank)) {
                // There are no merchants that fall under the rank
                $highestEmptyRank = $rank['rank'];
            } else {
                // If there is no member store of the highest rank, carry forward advance (= push_time advance)
                if (!empty($highestEmptyRank)) {
                    foreach ($auctionData as $key => $val) {
                        if ($val['rank'] == $rank['rank']) {
                            $val['push_time'] = $rankTime[$highestEmptyRank];
                            if (!empty($val['auto_call_time'])) {
                                $val['auto_call_time'] = date(
                                    'Y-m-d H:i',
                                    strtotime($rankTime[$highestEmptyRank] . '+' . $autoCallInterval . 'minute')
                                );
                            }
                        }
                    }
                }
                break;
            }
        }
    }

    /**
     * do sorting auto commission
     * @param  array $autoCommissions commission data
     * @return array commission sorted
     */
    private function sortAutoCommission($autoCommissions)
    {
        // Perform sort
        uasort($autoCommissions, function ($first, $second) {
            if ($first['push_time'] != $second['push_time']) {
                // AuctionInfo.push_time asc
                return $first['push_time'] < $second['push_time'] ? -1 : 1;
            } elseif ($first['affiliationAreaStat']['commission_unit_price_category']
                != $second['affiliationAreaStat']['commission_unit_price_category']
            ) {
                // AffiliationAreaStat.commission_unit_price_category IS NULL
                if (empty($first['affiliationAreaStat']['commission_unit_price_category'])
                    && !empty($second['affiliationAreaStat']['commission_unit_price_category'])
                ) {
                    return 1;
                } elseif (!empty($first['affiliationAreaStat']['commission_unit_price_category'])
                    && empty($second['affiliationAreaStat']['commission_unit_price_category'])
                ) {
                    return -1;
                }
                return $first['affiliationAreaStat']['commission_unit_price_category']
                > $second['affiliationAreaStat']['commission_unit_price_category'] ? -1 : 1;
            } elseif ($first['affiliationAreaStat']['commission_count_category']
                != $second['affiliationAreaStat']['commission_count_category']
            ) {
                // AffiliationAreaStat.commission_count_category desc
                return $first['affiliationAreaStat']['commission_count_category']
                > $second['affiliationAreaStat']['commission_count_category'] ? -1 : 1;
            }
            return 0;
        });
        return $autoCommissions;
    }

    /**
     * get by id and commission commit flag
     * @param  integer $auctionId
     * @return string
     */
    public function getByIdAndCommissionCommitFlag($auctionId)
    {
        return $this->auctionInfoRepository->getByIdAndCommissionCommitFlag($auctionId);
    }

    /**
     * parse to valid data
     * @param object $data
     * @return mixed
     */
    public function parsingData($data)
    {
        $data['id'] = (int)$data['id'];
        $data['demand_id'] = (int)$data['demand_id'];
        $data['corp_id'] = (int)$data['corp_id'];
        $data['responders'] = isset($data['responders']) ? $data['responders'] : '';
        $data['agreement_check'] = isset($data['agreement_check']) ? (int)$data['agreement_check'] : 0;
        $data['demand_status'] = isset($data['demand_status']) ? (int)$data['demand_status'] : 0;
        $data['site_id'] = isset($data['site_id']) ? (int)$data['site_id'] : 0;
        $data['jbr_available_status'] = isset($data['jbr_available_status']) ? (int)$data['jbr_available_status'] : 0;
        if (isset($data['visit_time_id'])) {
            $data['visit_time_id'] = (int)$data['visit_time_id'];
        }
        return $data;
    }
}
