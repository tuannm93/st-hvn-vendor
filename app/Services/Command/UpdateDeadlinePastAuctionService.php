<?php

namespace App\Services\Command;

use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\AutoCallRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Services\CommissionInfoService;
use Illuminate\Support\Facades\Log;
use Lang;

class UpdateDeadlinePastAuctionService
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
     * @var MCorpRepositoryInterface
     */
    protected $corpRepository;
    /**
     * @var MPostRepositoryInterface
     */
    protected $postRepository;
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
     * @var CommissionInfoService
     */
    protected $commissionInfoService;

    /**
     * UpdateDeadlinePastAuctionService constructor.
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param MCorpRepositoryInterface $corpRepository
     * @param MPostRepositoryInterface $postRepository
     * @param AutoCallRepositoryInterface $autoCallItemRepo
     * @param MGenresRepositoryInterface $genreRepo
     * @param MCategoryRepositoryInterface $categoryRepo
     * @param CommissionInfoService $commissionInfoService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        MCorpRepositoryInterface $corpRepository,
        MPostRepositoryInterface $postRepository,
        AutoCallRepositoryInterface $autoCallItemRepo,
        MGenresRepositoryInterface $genreRepo,
        MCategoryRepositoryInterface $categoryRepo,
        CommissionInfoService $commissionInfoService
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->corpRepository = $corpRepository;
        $this->postRepository = $postRepository;
        $this->autoCallItemRepo = $autoCallItemRepo;
        $this->genreRepo = $genreRepo;
        $this->categoryRepo = $categoryRepo;
        $this->commissionInfoService = $commissionInfoService;
    }

    /**
     * @param integer $demandId
     * @param array $data
     * @return array|bool
     */
    public function getAuctionInfoForAutoCommission($demandId, $data)
    {
        // Obtain the date and time of the visit, the desired date and time of contact
        $visitTimeList = $this->getVisitTimeList($data);

        $result = $this->getPreferredDate($data, $visitTimeList);
        $data = $result['data'];
        $preferredDate = $result['preferredDate'];

        // To acquire mail transmission time
        // Temporarily set DemandInfo.auction_start_time, DemandInfo.auction_deadline_time
        $data = $this->fillAuctionTime($data);

        $priority = $data['demandInfo']['priority'];
        // When the priority is not set yet
        $judgeResult = $this->buildJudeResult($data['demandInfo'], $preferredDate);
        if ($judgeResult['result_flg'] == 1) {
            //Auction start date and time
            if (!empty($judgeResult['result_date'])) {
                $data['demandInfo']['auction_start_time'] = $judgeResult['result_date'];
            }
            //When the priority is changed, the determination for each priority is performed again
            judgeAuction($data['demandInfo']['auction_start_time'], $preferredDate, $data['demandInfo']['genre_id'], $data['demandInfo']['address1'], $data['demandInfo']['auction_deadline_time'], $priority);
        }
        $autoCommissions = $this->updateAuctionInfos($demandId, $data, true);

        // Perform sort
        $autoCommissions = $this->sortAutoCommission($autoCommissions);

        return $autoCommissions;
    }

    /**
     * @param $autoCommissions
     * @return array
     */
    private function sortAutoCommission($autoCommissions)
    {
        if (is_array($autoCommissions)) {
            uasort($autoCommissions, function ($first, $second) {
                if ($first['push_time'] != $second['push_time']) {
                    // AuctionInfo.push_time asc
                    return $first['push_time'] < $second['push_time'] ? -1 : 1;
                } elseif ($first['affiliationAreaStat']['commission_unit_price_category'] != $second['affiliationAreaStat']['commission_unit_price_category']) {
                    // AffiliationAreaStat.commission_unit_price_category IS NULL
                    return $this->compareValueCommissionUnitPriceCategory($first, $second);
                } elseif ($first['affiliationAreaStat']['commission_count_category'] != $second['affiliationAreaStat']['commission_count_category']) {
                    // AffiliationAreaStat.commission_count_category desc
                    return $first['affiliationAreaStat']['commission_count_category'] > $second['affiliationAreaStat']['commission_count_category'] ? -1 : 1;
                } else {
                    return 0;
                }
            });
        }
        return $autoCommissions;
    }

    /**
     * @param $first
     * @param $second
     * @return int
     */
    private function compareValueCommissionUnitPriceCategory($first, $second)
    {
        if (empty($first['affiliationAreaStat']['commission_unit_price_category']) && !empty($second['affiliationAreaStat']['commission_unit_price_category'])) {
            return 1;
        } elseif (!empty($first['affiliationAreaStat']['commission_unit_price_category']) && empty($second['affiliationAreaStat']['commission_unit_price_category'])) {
            return -1;
        } else { // AffiliationAreaStat.commission_unit_price_category desc
            return $first['affiliationAreaStat']['commission_unit_price_category'] > $second['affiliationAreaStat']['commission_unit_price_category'] ? -1 : 1;
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function getVisitTimeList($data)
    {
        $visitTimeList = [];
        if (!empty($data['visitTime'])) {
            foreach ($data['visitTime'] as $val) {
                if ($val['is_visit_time_range_flg'] == 0 && strlen($val['visit_time']) > 0) {
                    $visitTimeList[] = $val['visit_time'];
                }
                if ($val['is_visit_time_range_flg'] == 1 && strlen($val['visit_time_from']) > 0) {
                    $visitTimeList[] = $val['visit_time_from'];
                }
            }
        }
        return $visitTimeList;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function fillAuctionTime($data)
    {
        if (empty($data['demandInfo']['auction_start_time'])) {
            $data['demandInfo']['auction_start_time'] = date('Y-m-d H:i:s');
        }
        if (empty($data['demandInfo']['auction_deadline_time'])) {
            $data['demandInfo']['auction_deadline_time'] = '';
        }
        return $data;
    }

    /**
     * @param $data
     * @param $visitTimeList
     * @return array
     */
    private function getPreferredDate($data, $visitTimeList)
    {
        $preferredDate = "";
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
        return ['data' => $data, 'preferredDate' => $preferredDate];
    }


    /**
     * @param $data
     * @return array
     */
    private function getRankTime($data)
    {
        if ($data['demandInfo']['method'] == 'visit') {
            $rankTime = getPushSendTimeOfVisitTime($data['demandInfo']['auction_start_time'], $data['demandInfo']['auction_deadline_time'], $data['demandInfo']['genre_id'], $data['demandInfo']['address1']);
        } else {
            $rankTime = getPushSendTimeOfContactDesiredTime($data['demandInfo']['auction_start_time'], $data['demandInfo']['auction_deadline_time'], $data['demandInfo']['genre_id'], $data['demandInfo']['address1']);
        }
        return $rankTime;
    }

    /**
     * @param integer $demandId
     * @param array $data
     * @param bool $autoSelection
     * @return array|bool
     */
    public function updateAuctionInfos($demandId, $data, $autoSelection = false)
    {
        //In case of automatic consignment, auction is not selected
        Log::debug('____ start update auction info _______');
        if ((!empty($data['demandInfo']['do_auction']) || $autoSelection) && (empty($data['demandInfo']['do_auto_selection_category']) || $data['demandInfo']['do_auto_selection_category'] == 0)) {
            // Acquire transmission time per rank
            $rankTime = $this->getRankTime($data);
            // Acquire time to auto call
            $autoCall = $this->getAutoCallInterval($data['demandInfo']['priority']);
            // Acquisition of auto call flag by genre
            $autoCallFlag = $this->getAutoCallFlagMGenre($data['demandInfo']['genre_id']);
            // Acquisition of list of companies corresponding to the case
            // Search characters in municipalities for upper and lower case letters (m_posts.address 2)
            $mPostJscd = $this->postRepository->getTargetArea([
                'address1' => $data ['demandInfo'] ['address1'],
                'address2' => $data['demandInfo']['address2'],
            ]);

            if (empty($mPostJscd)) {
                Log::debug('____ empty mPostJscd  _______');
                //In the case of nonexistent areas, we can not auction, so we will exit here.
                // We have already made 0 as a target so that manual selection is made by separate processing.
                return true;
            }
            $corpData = [
                'genre_id' => $data['demandInfo']['genre_id'],
                'site_id' => $data['demandInfo']['site_id'],
                'category_id' => $data['demandInfo']['category_id'],
                'address1' => $data['demandInfo']['address1'],
                'jis_cd' => $mPostJscd,
            ];

            $results = $this->corpRepository->demandCorpData($corpData, true);
            if ($results->count() > 0) {
                $beforeList = [];
                $beforeData = $this->auctionInfoRepository->getListByDemandId($demandId);
                foreach ($beforeData as $key => $val) {
                    $beforeList[$val->corp_id] = $val->id;
                }
                $idx = 0;
                $auctionData = [];
                $autoAuctionData = [];
                foreach ($results as $key => $val) {
                    // If there is an agency method by genre (not 0) overwrite the variable.
                    if ($val->m_cate_auction_status != 0) {
                        $auctionStatusFlg = $val->m_cate_auction_status;
                    } else {
                        $auctionStatusFlg = $val->auction_status;
                    }
                    //Check if accumulation of credit unit price of transaction data has reached the limit
                    $resultCredit = $this->commissionInfoService->checkCredit($val->id, $data['demandInfo']['genre_id'], false);
                    $resultCredit = [$resultCredit];
                    if (in_array(config('constant.CREDIT_DANGER'), $resultCredit)) {
                        //If the credit limit is exceeded, subsequent processing is not performed
                        continue;
                    }
                    if ($data['demandInfo']['site_id'] != config('rits.CREDIT_EXCLUSION_SITE_ID')) {
                        if (is_null($data['demandInfo']['category_id'])) {
                            $agrementLicense = true;
                        } else {
                            $agrementLicense = $this->corpRepository->isCommissionStop($val->id);
                        }
                        if ($agrementLicense === false) {
                            continue;
                        }
                    }

                    // Acquire default commission rate and default commission rate unit
                    if (empty($val->order_fee) || is_null($val->order_fee_unit)) {
                        // If it can not be acquired from m_corp_categories, it is obtained from m_categories
                        $mcCategoryData = $this->categoryRepo->getFeeData($data['demandInfo']['category_id']);
                        if (empty($mcCategoryData)) {
                            // If it can not be acquired from m_categories, it is left blank.
                            $mcCategoryData = ['category_default_fee' => '', 'category_default_fee_unit' => ''];
                        }
                        $val->order_fee = $mcCategoryData['category_default_fee'];
                        $val->order_fee_unit = $mcCategoryData['category_default_fee_unit'];
                    }
                    // If lost_flg, del_flg registered in Commission_infos is "1", it will not be subject to bidding
                    $targetCommissionData = $this->commissionInfoRepository->getFirstByDemandIdAndCorpId($demandId, $val->id);
                    // A franchise with lost_flg and del_flg of "0" in the retrieved pickup data is targeted for bidding
                    if (is_null($targetCommissionData) || (($targetCommissionData->lost_flg == 0) && ($targetCommissionData->del_flg == 0))) {
                        if (!empty($val->commission_unit_price_rank)) {
                            $rank = mb_strtolower($val->commission_unit_price_rank);
                            if (isset($rankTime[$rank])) {
                                $as = false;
                                if (empty($auctionStatusFlg) || $auctionStatusFlg == getDivValue('auction_delivery_status', 'delivery') || $auctionStatusFlg == getDivValue('auction_delivery_status', 'deny')) {
                                    $as = true;
                                }
                                if (!empty($rankTime[$rank]) && $as) {
                                    //ORANGE-250 CHG E
                                    $auctionData[$idx]['id'] = isset($beforeList[$val->id]) ? $beforeList[$val->id] : '';
                                    $auctionData[$idx]['demand_id'] = $demandId;
                                    $auctionData[$idx]['corp_id'] = $val->id;
                                    $auctionData[$idx]['push_time'] = $rankTime[$rank];
                                    $auctionData[$idx]['push_flg'] = 0;
                                    $auctionData[$idx]['before_push_flg'] = 0;
                                    $auctionData[$idx]['display_flg'] = 0;
                                    $auctionData[$idx]['refusal_flg'] = 0;
                                    $auctionData[$idx]['rank'] = $rank; // For extraction per rank
                                    if ($autoCall != null && $this->isAutoCallable($autoCallFlag, $val->auto_call_flag)) {
                                        $auctionData[$idx]['auto_call_time'] = date('Y-m-d H:i', strtotime($rankTime[$rank] . '+' . $autoCall . 'minute'));
                                        $auctionData[$idx]['auto_call_flg'] = 0;
                                    } else {
                                        $auctionData[$idx]['auto_call_time'] = null;
                                        $auctionData[$idx]['auto_call_flg'] = null;
                                    }
                                    $idx++;
                                }
                                //Create data for automatic selection
                                if (!empty($rankTime[$rank]) && (empty($auctionStatusFlg) || $auctionStatusFlg == getDivValue('auction_delivery_status', 'delivery') || $auctionStatusFlg == getDivValue('auction_delivery_status', 'ng'))) {
                                    $autoAuctionData[] = [
                                        'id' => isset($beforeList[$val->id]) ? $beforeList[$val->id] : '',
                                        'demand_id' => $demandId,
                                        'corp_id' => $val->id,
                                        'push_time' => $rankTime[$rank],
                                        'push_flg' => 0,
                                        'before_push_flg' => 0,
                                        'display_flg' => 0,
                                        'refusal_flg' => 0,
                                        'rank' => $rank,
                                        'affiliationAreaStat' => [
                                            'commission_unit_price_category' => $val->commission_unit_price_category,
                                            'commission_count_category' => $val->commission_count_category,
                                            'commission_unit_price_rank' => $val->commission_unit_price_rank,
                                        ],
                                        'mCorpCategory' => [
                                            'order_fee' => $val->order_fee,
                                            'order_fee_unit' => $val->order_fee_unit,
                                            'introduce_fee' => $val->introduce_fee,
                                            'corp_commission_type' => $val->corp_commission_type,
                                        ],
                                        'mCorp' => [
                                            'id' => $val->id,
                                        ],
                                    ];
                                }
                            }
                        } else {
                            // If the rank is blank, it shall be handled as the first round of auction and shall be subject to auction.
                            $rank = 'z';
                            if (isset($rankTime[$rank])) {
                                $as = false;
                                if (empty($auctionStatusFlg) || $auctionStatusFlg == getDivValue('auction_delivery_status', 'delivery') || $auctionStatusFlg == getDivValue('auction_delivery_status', 'deny')) {
                                    $as = true;
                                }
                                if (!empty($rankTime[$rank]) && $as) {
                                    $auctionData[$idx]['id'] = isset($beforeList[$val->id]) ? $beforeList[$val->id] : '';
                                    $auctionData[$idx]['demand_id'] = $demandId;
                                    $auctionData[$idx]['corp_id'] = $val->id;
                                    $auctionData[$idx]['push_time'] = $rankTime[$rank];
                                    $auctionData[$idx]['push_flg'] = 0;
                                    $auctionData[$idx]['before_push_flg'] = 0;
                                    $auctionData[$idx]['display_flg'] = 0;
                                    $auctionData[$idx]['refusal_flg'] = 0;
                                    $auctionData[$idx]['rank'] = $rank; // ランク毎抽出用

                                    if ($autoCall !== null && $this->isAutoCallable($autoCallFlag, $val->auto_call_flag)) {
                                        $auctionData[$idx]['auto_call_time'] = date('Y-m-d H:i', strtotime($rankTime[$rank] . '+' . $autoCall . 'minute'));
                                        $auctionData[$idx]['auto_call_flg'] = 0;
                                    } else {
                                        $auctionData[$idx]['auto_call_time'] = null;
                                        $auctionData[$idx]['auto_call_flg'] = null;
                                    }
                                    $idx++;
                                }
                                // Create data for automatic selection
                                if (!empty($rankTime[$rank]) && (empty($auctionStatusFlg) || $auctionStatusFlg == getDivValue('auction_delivery_status', 'delivery') || $auctionStatusFlg == getDivValue('auction_delivery_status', 'ng'))) {
                                    $autoAuctionData[] = [
                                        'id' => isset($beforeList[$val->id]) ? $beforeList[$val->id] : '',
                                        'demand_id' => $demandId,
                                        'corp_id' => $val->id,
                                        'push_time' => $rankTime[$rank],
                                        'push_flg' => 0,
                                        'before_push_flg' => 0,
                                        'display_flg' => 0,
                                        'refusal_flg' => 0,
                                        'rank' => $rank,
                                        'affiliationAreaStat' => [
                                            'commission_unit_price_category' => $val->commission_unit_price_category,
                                            'commission_count_category' => $val->commission_count_category,
                                            'commission_unit_price_rank' => $val->commission_unit_price_rank,
                                        ],
                                        'mCorpCategory' => [
                                            'order_fee' => $val->order_fee,
                                            'order_fee_unit' => $val->order_fee_unit,
                                            'introduce_fee' => $val->introduce_fee,
                                            'corp_commission_type' => $val->corp_commission_type,
                                        ],
                                        'mCorp' => [
                                            'id' => $val->id,
                                        ],
                                    ];
                                }
                            }
                        }
                    } else { // lost_flg,del_flg check
                        if (isset($beforeList[$val->id])) {
                            // At the time of re-bidding, the member shop with lost_flg and del_flg being 1 declines that it has declined, and sets the declaration flag to 1
                            $auctionId = isset($beforeList[$val->id]) ? $beforeList[$val->id] : '';
                            if (!$autoSelection) {
                                // 書き込む
                                Log::debug('____ call  $this->auctionInfoRepository->saveAuction()  _______');
                                $this->auctionInfoRepository->saveAuction($auctionId, ['refusal_flg' => 1]);
                            }
                        }
                    }
                }
                if (!$autoSelection) {
                    // In case of bidding ceremony selection (manual or automatic)
                    if (0 < count($auctionData)) {
                        // $auctionData['demandInfo']['genre_id'] = $data['demandInfo']['genre_id'];
                        // $auctionData['demandInfo']['prefecture'] = $data['demandInfo']['address1'];
                        Log::debug('____ call  $this->carryAuctionPushTime()  _______');
                        $this->carryAuctionPushTime($auctionData, $rankTime, $autoCall);
                        Log::debug('____ call  $this->auctionInfoRepository->saveAuctions()  _______');
                        $this->auctionInfoRepository->saveAuctions($auctionData);

                        return $auctionData;
                    }
                } else {
                    // In case of automatic selection
                    if (0 < count($autoAuctionData)) {
                        // $autoAuctionData['demandInfo']['genre_id'] = $data['demandInfo']['genre_id'];
                        // $autoAuctionData['demandInfo']['prefecture'] = $data['demandInfo']['address1'];
                        Log::debug('____ call  $this->carryAuctionPushTime()  _______');
                        $this->carryAuctionPushTime($autoAuctionData, $rankTime, $autoCall);
                    }

                    return $autoAuctionData;
                }
            }
        }

        return true;
    }

    /**
     * Acquire transmission interval until auto call
     *
     * @param  integer $priority
     * @return mixed
     */
    private function getAutoCallInterval($priority)
    {
        $autoCall = $this->autoCallItemRepo->getItem();
        if ($priority == getDivValue('priority', 'asap')) {
            $time = $autoCall->asap;
        } elseif ($priority == getDivValue('priority', 'immediately')) {
            $time = $autoCall->immediately;
        } else {
            $time = $autoCall->normal;
        }

        return $time;
    }

    /**
     * @param integer $genreId
     * @return int
     */
    private function getAutoCallFlagMGenre($genreId)
    {

        $result = $this->genreRepo->findById($genreId);

        return !empty($result) ? $result->auto_call_flag : 1;
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
            [
                'rank' => 'a',
                'rank_time' => $rankTime['a'],
            ],
            [
                'rank' => 'b',
                'rank_time' => $rankTime['b'],
            ],
            [
                'rank' => 'c',
                'rank_time' => $rankTime['c'],
            ],
            [
                'rank' => 'd',
                'rank_time' => $rankTime['d'],
            ],
            [
                'rank' => 'z',
                'rank_time' => $rankTime['z'],
            ],
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
            if (empty($ranks)) {
                // There are no merchants that fall under the rank
                if (empty($highestEmptyRank)) {
                    $highestEmptyRank = $rank['rank'];
                }
            } else {
                // If there is no member store of the highest rank, carry forward advance (= push_time advance)
                if (!empty($highestEmptyRank)) {
                    foreach ($auctionData as $key => $val) {
                        if ($val['rank'] == $rank['rank']) {
                            $auctionData[$key]['push_time'] = $rankTime[$highestEmptyRank];
                            if (!empty($auctionData[$key]['auto_call_time'])) {
                                $auctionData[$key]['auto_call_time'] = date('Y-m-d H:i', strtotime($rankTime[$highestEmptyRank] . '+' . $autoCallInterval . 'minute'));
                            }
                        }
                    }
                }
                break;
            }
        }
    }

    /**
     * @author  thaihv
     * @param array $demandInfo
     * @param string $preferredDate
     * @return array
     */
    private function buildJudeResult(&$demandInfo, $preferredDate)
    {
        if (empty($demandInfo['priority'])) {
            $judgeResult = judgeAuction($demandInfo['auction_start_time'], $preferredDate, $demandInfo['genre_id'], $demandInfo['address1'], $demandInfo['auction_deadline_time'], $demandInfo['priority']);
        } elseif ($demandInfo['priority'] == getDivValue('priority', 'asap')) {
            // When the priority is urgent
            $judgeResult = judgeAsap($demandInfo['auction_start_time'], $demandInfo['genre_id'], $demandInfo['address1'], $demandInfo['auction_deadline_time']);
        } elseif ($demandInfo['priority'] == getDivValue('priority', 'immediately')) {
            // When the priority is urgent
            $judgeResult = judgeImmediately($demandInfo['auction_start_time'], $preferredDate, $demandInfo['genre_id'], $demandInfo['address1'], $demandInfo['auction_deadline_time']);
        } else {
            // When the priority is normal
            $judgeResult = judgeNormal($demandInfo['auction_start_time'], $demandInfo['genre_id'], $demandInfo['address1'], $demandInfo['auction_deadline_time'], $demandInfo['priority']);
        }

        return $judgeResult;
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
