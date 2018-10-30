<?php

namespace App\Services\Command;

use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandCorrespondsRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\VisitTimeRepositoryInterface;

class CheckDeadlinePastAuctionService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;

    /**
     * @var MCategoryRepositoryInterface
     */
    protected $mCategoryRepository;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;

    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;

    /**
     * @var VisitTimeRepositoryInterface
     */
    protected $visitTimeRepository;

    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepository;

    /**
     * @var UpdateDeadlinePastAuctionService
     */
    protected $updateDlPastAuctionService;

    /**
     * @var DemandCorrespondsRepositoryInterface
     */
    protected $demandCorrespondsRepository;

    /**
     * Default user
     * @var string
     */
    private $user = 'system';

    /**
     * CheckDeadlinePastAuctionService constructor.
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param MCategoryRepositoryInterface $mCategoryRepository
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param VisitTimeRepositoryInterface $visitTimeRepository
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param UpdateDeadlinePastAuctionService $pastAuctionService
     * @param DemandCorrespondsRepositoryInterface $demandCorrespondsRepository
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        MCategoryRepositoryInterface $mCategoryRepository,
        MCorpRepositoryInterface $mCorpRepository,
        DemandInfoRepositoryInterface $demandInfoRepository,
        VisitTimeRepositoryInterface $visitTimeRepository,
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        UpdateDeadlinePastAuctionService $pastAuctionService,
        DemandCorrespondsRepositoryInterface $demandCorrespondsRepository
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->mCategoryRepository = $mCategoryRepository;
        $this->mCorpRepository = $mCorpRepository;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->visitTimeRepository = $visitTimeRepository;
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->updateDlPastAuctionService = $pastAuctionService;
        $this->demandCorrespondsRepository = $demandCorrespondsRepository;
    }

    /**
     * Get auction commission list
     *
     * @param array $data
     * @return array
     */
    public function getAuctionCommissionList($data)
    {
        $commissionInfos = [];

        // Bidding flow history
        $correspondingContents = '';
        // Automatic selection history
        $correspondingContents2 = '';

        // Acquire registered auction information
        $auctionInfos = $this->auctionInfoRepository->getAuctionInfoByDemandIdForCheckDeadline($data->id);

        // Acquire registered registration information
        $registedCommission = $this->commissionInfoRepository->findCommissionRegistedByDemandId($data->id);

        foreach ($auctionInfos as $k => $value) {
            if ($data->selection_system == getDivValue('selection_type', 'auction_selection')
                && $value->refusal_flg != 1) {
                continue;
            }

            $correspondingContents = $this->getCorrespondingContents($correspondingContents, $data, $value);

            $commissionInfos = $this->setCommissionInfoWithAuction(
                $registedCommission,
                $value,
                $data->id,
                $commissionInfos
            );
        }

        $checkAutoAuctionSelection = $this->isAutoAuctionSelection($data);

        if ($checkAutoAuctionSelection) {
            // Acquire auction information for automatic selection
            $demandInfo = $this->getDemand($data->id);
            $autoAuctionInfos = $this->updateDlPastAuctionService->getAuctionInfoForAutoCommission(
                $demandInfo['demandInfo']['id'],
                $demandInfo
            );
            $defaultFee = $this->mCategoryRepository->getDefaultFee($demandInfo['demandInfo']['category_id']);
            if ($autoAuctionInfos !== true) {
                foreach ($autoAuctionInfos as $k => $value) {
                    $commissionInfos = $this->setCommissionInfoWithAutoAuction(
                        $registedCommission,
                        $value,
                        $data->id,
                        $commissionInfos,
                        $defaultFee
                    );
                    $correspondingContents2 .= '自動選定 ';
                    $corp = $this->mCorpRepository->find($value['mCorp']['id']);
                    $correspondingContents2 .= '[' . $corp->corp_name . "]\n";
                }
            }
        }

        // Perform sorting together with bid declaration and automatic selection
        $commissionInfos = $this->sortCommissionInfos($commissionInfos);

        if (!empty($correspondingContents)) {
            $commissionInfos['corresponding_contens'][0] = $correspondingContents;
        }

        if (!empty($correspondingContents2)) {
            $commissionInfos['corresponding_contens'][1] = $correspondingContents2;
        }
        return $commissionInfos;
    }

    /**
     * Get content of Corresponding
     * @param string $correspondingContents
     * @param $data
     * @param $value
     * @return string
     */
    private function getCorrespondingContents($correspondingContents, $data, $value)
    {
        // Selection method automatic OR manual
        if (!empty($data->selection_system) &&
            $data->selection_system == getDivValue('selection_type', 'auction_selection')
        ) {
            $correspondingContents .= '入札流れ 手動選定 ';
        } else {
            $correspondingContents .= '入札流れ 自動選定 ';
        }

        // Add company name
        $correspondingContents .= '[' . $value->corp_name . '] ';

        // Reason for declining
        if ($value->refusal_flg == 1) {
            $correspondingContents .= "＜辞退＞";
        }

        $correspondingContents .= "\n";

        $correspondingContents = $this->setCorrespondingContentTime($value, $correspondingContents);
        $correspondingContents = $this->setCostCorrespondingContent($value, $correspondingContents);
        $correspondingContents = $this->setOtherCorrespondingContent($value, $correspondingContents);

        return $correspondingContents;
    }

    /**
     * Set up corresponding content time
     * @param $value
     * @param $correspondingContents
     * @return string
     */
    private function setCorrespondingContentTime($value, $correspondingContents)
    {
        if (!empty($value->corresponds_time1) || !empty($value->corresponds_time2) || !empty($value->corresponds_time3)) {
            $correspondingContents .= '  ■対応時間が合わず ';
            $tempCorrespondsTime = ['corresponds_time1', 'corresponds_time2', 'corresponds_time3'];

            foreach ($tempCorrespondsTime as $cTime) {
                if (!empty($value->{$cTime})) {
                    $correspondingContents .= dateTimeFormat($value->{$cTime}) . ' ';
                }
            }
            $correspondingContents .= "\n";
        }

        return $correspondingContents;
    }

    /**
     * Set up corresponding content cost if it exist
     * @param $value
     * @param $correspondingContents
     * @return string
     */
    private function setCostCorrespondingContent($value, $correspondingContents)
    {
        if (!empty($value->cost_from) || !empty($value->cost_to)) {
            $correspondingContents .= '  ■価格が合わず ' . yenFormat2($value->cost_from) . '～' . yenFormat2($value->cost_to);
            $correspondingContents .= "\n";
        }

        return $correspondingContents;
    }

    /**
     * Set up other corresponding content if it exist
     * @param $value
     * @param $correspondingContents
     * @return string
     */
    private function setOtherCorrespondingContent($value, $correspondingContents)
    {
        $tempOtherContents = [
            'estimable_time_from' => '  ■見積もり日程が対応不可 ',
            'contactable_time_from' => '  ■連絡希望日時が対応不可 ',
            'other_contens' => '  ■その他対応不可理由 ',
        ];

        foreach ($tempOtherContents as $tempKey => $tempContent) {
            if (!empty($value->{$tempKey})) {
                $correspondingContents .= $tempContent . dateTimeFormat($value->{$tempKey});
                $correspondingContents .= "\n";
            }
        }

        return $correspondingContents;
    }

    /**
     * Set commission info in case auction
     * @param $registedCommission
     * @param $value
     * @param $demandId
     * @param $commissionInfos
     * @return mixed
     */
    private function setCommissionInfoWithAuction($registedCommission, $value, $demandId, $commissionInfos)
    {
        $hasCommissionDb = $this->setCommissionDb($registedCommission, $value);

        if ($hasCommissionDb->count() == 0 && $value->refusal_flg == 1) {
            if ($value->corp_commission_type != 2) {
                // Conclusion base
                $orderFee = $value->order_fee;
                $orderFeeUnit = $value->order_fee_unit;
                $commissionType = getDivValue('commission_type', 'normal_commission');
                $commissionStatus = getDivValue('construction_status', 'progression');
            } else {
                // Introduction base
                $orderFee = $value->introduce_fee;
                $orderFeeUnit = 0;
                $commissionType = getDivValue('commission_type', 'package_estimate');
                $commissionStatus = getDivValue('construction_status', 'introduction');
            }

            // Setup data for commission unit price
            $tempCommissionInfos = [
                'business_trip_amount' => 0,
                'commission_unit_price_rank' => '',
                'commission_unit_price_category' => null
            ];

            foreach ($tempCommissionInfos as $tempKey => $tempValue) {
                $value->{$tempKey} = !empty($value->{$tempKey}) ? $value->{$tempKey} : $tempValue;
            }

            $commissionInfos['commissionInfo'][] = [
                'demand_id' => $demandId,
                'corp_id' => $value->corp_id,
                'commit_flg' => 0,
                'commission_type' => $commissionType,
                'lost_flg' => $value->refusal_flg,
                'commission_status' => $commissionStatus,
                'unit_price_calc_exclude' => 0,
                'corp_fee' => $orderFeeUnit == 0 ? $orderFee : null,
                'commission_fee_rate' => $value->corp_commission_type != 2 ? ($orderFeeUnit == 1 ? $orderFee : null) : 100,
                'business_trip_amount' => $value->business_trip_amount,
                'select_commission_unit_price_rank' => $value->commission_unit_price_rank,
                'select_commission_unit_price' => $value->commission_unit_price_category,
                'order_fee_unit' => $orderFeeUnit,
                'modified_user_id' => $this->user,
                'modified' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->user,
                'created' => date('Y-m-d H:i:s'),

                // For sorting
                'sort_push_time' => strtotime($value->push_time),
                'sort_commission_unit_price_category' => $value->commission_unit_price_category,
                'sort_commission_count_category' => $value->commission_count_category,
            ];
        }

        return $commissionInfos;
    }

    /**
     * Set commission DB
     * @param $registedCommission
     * @param $value
     * @return \Illuminate\Support\Collection
     */
    private function setCommissionDb($registedCommission, $value)
    {
        $hasCommissionDb = collect([]);

        if ($registedCommission->count() > 0) {
            $hasCommissionDb = $registedCommission->filter(function ($item) use ($value) {
                return $item->corp_id == $value->corp_id;
            });
        }

        return $hasCommissionDb;
    }

    /**
     * Check auto auction selection
     * @param $data
     * @return bool
     */
    private function isAutoAuctionSelection($data)
    {
        if (!empty($data->selection_system) &&
            $data->selection_system == getDivValue('selection_type', 'automatic_auction_selection')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param integer $demandId
     * @return array
     */
    private function getDemand($demandId)
    {
        // Acquisition of item data
        $results['demandInfo'] = $this->demandInfoRepository->find($demandId)->toArray();
        $results['demandInfo']['recursive'] = 2;

        // Acquiring visit date and time
        $data = $this->visitTimeRepository->findAllWithAuctionInfo($demandId);
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $results['visitTime'][$key]['id'] = $value->id;
                $results['visitTime'][$key]['visit_time'] = $value->visit_time;
                $results['visitTime'][$key]['is_visit_time_range_flg'] = $value->is_visit_time_range_flg;
                $results['visitTime'][$key]['visit_time_from'] = $value->visit_time_from;
                $results['visitTime'][$key]['visit_time_to'] = $value->visit_time_to;
                $results['visitTime'][$key]['visit_adjust_time'] = $value->visit_adjust_time;
                $results['visitTime'][$key]['visit_time_before'] = ($value->is_visit_time_range_flg == 0) ? $value->visit_time : $value->visit_time_from;
                $results['visitTime'][$key]['commit_flg'] = !empty($value->auction_info_id) ? 1 : 0;
            }
        }

        return $results;
    }

    /**
     * Set commission info in case auto auction
     * @param $registedCommission
     * @param $value
     * @param $demandId
     * @param $commissionInfos
     * @param $defaultFee
     * @return mixed
     */
    private function setCommissionInfoWithAutoAuction(
        $registedCommission,
        $value,
        $demandId,
        $commissionInfos,
        $defaultFee
    ) {
        $checkRegister = $this->isRegisterWithAutoAuction($commissionInfos, $value, $registedCommission);

        if ($checkRegister) {
            if ($value['mCorpCategory']['corp_commission_type'] != 2) {
                // Conclusion base
                $orderFee = $value['mCorpCategory']['order_fee'];
                $orderFeeUnit = $value['mCorpCategory']['order_fee_unit'];
                $commissionType = getDivValue('commission_type', 'normal_commission');
                $commissionStatus = getDivValue('construction_status', 'progression');
            } else {
                // Introduction base
                $orderFee = $value['mCorpCategory']['introduce_fee'];
                $orderFeeUnit = 0;
                $commissionType = getDivValue('commission_type', 'package_estimate');
                $commissionStatus = getDivValue('construction_status', 'introduction');
            }

            $orderFee = !empty($orderFee) ? $orderFee : $defaultFee['category_default_fee'];
            $orderFeeUnit = (!empty($value['mCorpCategory']['order_fee']) || !empty($value['mCorpCategory']['introduce_fee'])) ? $orderFeeUnit : $defaultFee['category_default_fee_unit'];

            // Get fee
            if ($orderFeeUnit == 0) {
                $corpFee = $orderFee;
                $commissionFeeRate = null;
            } else {
                $corpFee = null;
                $commissionFeeRate = $orderFee;
            }

            // Setup data for commission unit price
            $value = $this->setCommissionUnitPrice($value);

            $commissionInfos['commissionInfo'][] = [
                'demand_id' => $demandId,
                'corp_id' => $value['mCorp']['id'],
                'commit_flg' => 0,
                'commission_type' => $commissionType,
                'lost_flg' => 0,
                'commission_status' => $commissionStatus,
                'unit_price_calc_exclude' => 0,
                'corp_fee' => $corpFee,
                'commission_fee_rate' => $commissionFeeRate,
                'business_trip_amount' => !empty($demandInfo['demandInfo']['business_trip_amount']) ? $demandInfo['demandInfo']['business_trip_amount'] : 0,
                'select_commission_unit_price_rank' => $value['affiliationAreaStat']['commission_unit_price_rank'],
                'select_commission_unit_price' => $value['affiliationAreaStat']['commission_unit_price_category'],
                'order_fee_unit' => $orderFeeUnit,
                'modified_user_id' => $this->user,
                'modified' => date('Y-m-d H:i:s'),
                'created_user_id' => $this->user,
                'created' => date('Y-m-d H:i:s'),
                // For sorting
                'sort_push_time' => strtotime($value['push_time']),
                'sort_commission_unit_price_category' => $value['affiliationAreaStat']['commission_unit_price_category'],
                'sort_commission_count_category' => $value['affiliationAreaStat']['commission_count_category'],
            ];
        }

        return $commissionInfos;
    }

    /**
     * Check register with auto auction or not
     * @param $commissionInfos
     * @param $value
     * @param $registedCommission
     * @return bool
     */
    private function isRegisterWithAutoAuction($commissionInfos, $value, $registedCommission)
    {
        $hasCommissionDb = collect([]);

        // Do not register already registered suppliers
        if (!empty($commissionInfos)) {
            $hasCommission = array_filter($commissionInfos['commissionInfo'], function ($item) use ($value) {
                return $item['corp_id'] == $value['mCorp']['id'];
            });
        }

        // If you have already registered with DB, do not register
        if ($registedCommission->count() > 0) {
            $hasCommissionDb = $registedCommission->filter(function ($item) use ($value) {
                return $item['corp_id'] == $value['mCorp']['id'];
            });
        }

        if (empty($hasCommission) && $hasCommissionDb->count() == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Setup data for commission unit price
     * @param $value
     * @return mixed
     */
    private function setCommissionUnitPrice($value)
    {
        $tempCommissionInfos = [
            'commission_unit_price_rank' => '',
            'commission_unit_price_category' => null
        ];

        foreach ($tempCommissionInfos as $tempKey => $tempValue) {
            $value['affiliationAreaStat'][$tempKey] = !empty($value['affiliationAreaStat'][$tempKey]) ? $value['affiliationAreaStat'][$tempKey] : $tempValue;
        }

        return $value;
    }

    /**
     * Sort commission infos
     * @param $commissionInfos
     * @return mixed
     */
    private function sortCommissionInfos($commissionInfos)
    {
        if (!empty($commissionInfos)) {
            uasort($commissionInfos, function ($first, $second) {
                if ($first['sort_push_time'] != $second['sort_push_time']) {
                    return $first['sort_push_time'] < $second['sort_push_time'] ? -1 : 1;
                } else {
                    if ($first['sort_commission_unit_price_category'] != $second['sort_commission_unit_price_category']) {
                        if (empty($first['sort_commission_unit_price_category']) && !empty($second['sort_commission_unit_price_category'])) {
                            return 1;
                        } else {
                            if (!empty($first['sort_commission_unit_price_category']) && empty($second['sort_commission_unit_price_category'])) {
                                return -1;
                            } else {
                                return $first['sort_commission_unit_price_category'] > $second['sort_commission_unit_price_category'] ? -1 : 1;
                            }
                        }
                    } else {
                        if ($first['sort_commission_count_category'] != $second['sort_commission_count_category']) {
                            return $first['sort_commission_count_category'] > $second['sort_commission_count_category'] ? -1 : 1;
                        } else {
                            return 0;
                        }
                    }
                }
            });
        }

        return $commissionInfos;
    }

    /**
     * Set data
     *
     * @param array $tmp
     * @param integer $key
     * @param array $row
     * @param integer $userId
     * @param array $commissionInfos
     * @return mixed
     */
    public function setData($tmp, $key, $row, $userId, $commissionInfos)
    {
        $tmp['demandInfo'][$key] = $row;

        if (isset($commissionInfos['commissionInfo'])) {
            foreach ($commissionInfos['commissionInfo'] as $commissionInfo) {
                unset($commissionInfo['sort_push_time']);
                unset($commissionInfo['sort_commission_unit_price_category']);
                unset($commissionInfo['sort_commission_count_category']);
                $tmp['commissionInfo'][] = $commissionInfo;
            }
        }

        if (!empty($commissionInfos['corresponding_contens'][0])) {
            $tmp['demandCorrespond'][] = [
                'demand_id' => $row['id'],
                'corresponding_contens' => $commissionInfos['corresponding_contens'][0],
                'responders' => '入札流れ',
                'correspond_datetime' => date('Y-m-d H:i:s'),
                'created_user_id' => $userId,
                'created' => date('Y-m-d H:i:s'),
                'modified_user_id' => $userId,
                'modified' => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($commissionInfos['corresponding_contens'][1])) {
            $tmp['demandCorrespond'][] = [
                'demand_id' => $row['id'],
                'corresponding_contens' => $commissionInfos['corresponding_contens'][1],
                'responders' => '自動選定',
                'correspond_datetime' => date('Y-m-d H:i:s'),
                'created_user_id' => $userId,
                'created' => date('Y-m-d H:i:s'),
                'modified_user_id' => $userId,
                'modified' => date('Y-m-d H:i:s'),
            ];
        }

        return $tmp;
    }

    /**
     * Save data in table DemandInfo, CommissionInfo, DemandCorrespond
     *
     * @param array $tmp
     */
    public function saveData($tmp)
    {
        if (!empty($tmp['demandInfo'])) {
            $this->demandInfoRepository->insertOrUpdateMultiData($tmp['demandInfo']);
        }

        if (!empty($tmp['commissionInfo'])) {
            $this->commissionInfoRepository->insertOrUpdateMultiData($tmp['commissionInfo']);
        }

        if (!empty($tmp['demandCorrespond'])) {
            $this->demandCorrespondsRepository->insertOrUpdateMultiData($tmp['demandCorrespond']);
        }
    }
}
