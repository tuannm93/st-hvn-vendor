<?php

namespace App\Services\Report;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpSubRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReportCorpCommissionService
{
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var MCorpSubRepositoryInterface
     */
    protected $mCorpSubsRepository;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepository;

    /**
     * ReportCorpCommissionService constructor.
     *
     * @param DemandInfoRepositoryInterface     $demandInfoRepository
     * @param MCorpSubRepositoryInterface       $mCorpSubsRepository
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     */
    public function __construct(
        DemandInfoRepositoryInterface $demandInfoRepository,
        MCorpSubRepositoryInterface $mCorpSubsRepository,
        CommissionInfoRepositoryInterface $commissionInfoRepository
    ) {
        $this->demandInfoRepository = $demandInfoRepository;
        $this->mCorpSubsRepository = $mCorpSubsRepository;
        $this->commissionInfoRepository = $commissionInfoRepository;
    }

    /**
     * @param array $buildFilter
     * @param array       $order
     * @return array
     */
    public function getCorpCommissionPaginationCondition($buildFilter, $order = [])
    {
        $selectionSystemList = ['2', '3'];

        /**
         * SQL join table
         */
        $subQueryForHearNum = $this->commissionInfoRepository->subQueryForHearNum();
        $query = $this->demandInfoRepository->joinQueryGetReportCorpCommission($subQueryForHearNum);

        /**
         * SQL where condition
         */

        $query->where('demand_infos.del_flg', 0);
        // Life outside the emergency car
        $query->where('m_sites.jbr_flg', 0);

        // "Customer absent" or "[Before the transaction] Merchant checking in progress"
        $query->where(
            function ($condition) use ($selectionSystemList) {
                $condition->where('demand_infos.demand_status', getDivValue('demand_status', 'demand_development'))
                    ->orWhere('demand_infos.demand_status', getDivValue('demand_status', 'need_hearing'))
                    ->orWhere(
                        function ($subCondition) use ($selectionSystemList) {
                            $subCondition->where(
                                function ($subCondition1) use ($selectionSystemList) {
                                    $subCondition1->whereNotIn('demand_infos.selection_system', $selectionSystemList)
                                        ->where('demand_infos.demand_status', getDivValue('demand_status', 'agency_before'));
                                }
                            )->orWhere(
                                function ($subCondition2) {
                                    $subCondition2->where('demand_infos.selection_system', null)
                                        ->where('demand_infos.demand_status', getDivValue('demand_status', 'agency_before'));
                                }
                            );
                        }
                    );
            }
        );

        // Store in function as necessary for filter setting
        $holidayQuery = "SELECT m_items.item_name FROM m_corp_subs INNER JOIN m_items ON m_items.item_category = m_corp_subs.item_category AND m_items.item_id = m_corp_subs.item_id WHERE m_corp_subs.item_category = '休業日' AND m_corp_subs.corp_id = m_corps.id";

        if (isset($buildFilter)) {
            foreach ($buildFilter as $key => $value) {
                $exclusion = '';
                // Change conditions depending on the filtering element
                // A follow-up date
                if ($key == 'demand_infos.follow_date') {
                    // There is a follow-up date
                    if ($value == 2) {
                        $query->where($key, '<>', '');
                    } else {
                        // No follow-up date
                        $query->where(
                            function ($where) use ($key) {
                                $where->whereNull($key)
                                    ->orWhere($key, '');
                            }
                        );
                    }

                    continue;
                }

                // Requested date and time
                $valueBefore = '';
                $valueAfter = '';
                if ($key == 'demand_infos.contact_desired_time') {
                    if ($value == 1) {
                        // This day
                        $valueBefore = date('Y/m/d 00:00:00');
                        $valueAfter = date('Y/m/d 23:59:59');
                    } elseif ($value == 2) {
                        // Next day
                        $valueBefore = date('Y/m/d 00:00:00', strtotime("+ 1 day"));
                        $valueAfter = date('Y/m/d 23:59:59', strtotime("+ 1 day"));
                    } else {
                        // After that
                        $value = date('Y/m/d 00:00:00', strtotime("+ 2 day"));
                        $exclusion = '>=';
                    }

                    if (empty($exclusion)) {
                        $query->where(
                            function ($join) use ($key, $valueBefore, $valueAfter) {
                                $join->where(
                                    function ($subJoin1) use ($key, $valueBefore, $valueAfter) {
                                        $subJoin1->where($key, '>=', $valueBefore)
                                            ->where($key, '<=', $valueAfter);
                                    }
                                )->orWhere(
                                    function ($subJoin2) use ($valueBefore, $valueAfter) {
                                            $subJoin2->where('demand_infos.contact_desired_time_from', '>=', $valueBefore)
                                            ->where('demand_infos.contact_desired_time_from', '<=', $valueAfter);
                                    }
                                );
                            }
                        );
                    } else {
                        $query->where(function ($join) use ($key, $exclusion, $value) {
                            $join->where($key, $exclusion, $value)
                                ->orWhere('demand_infos.contact_desired_time_from', $exclusion, $value);
                        });
                    }

                    continue;
                }

                // Business day
                if ($key == 'demand_infos.holiday') {
                    if (is_array($value) && count($value) === 1) {
                        $value = implode(',', $value);
                        $query = $query->whereRaw(DB::raw("NOT EXISTS(".$holidayQuery." AND m_corp_subs.item_id = ".$value.")"));
                    } else {
                        $value = implode(',', $value);
                        $query = $query->whereRaw(DB::raw("NOT EXISTS(".$holidayQuery." AND m_corp_subs.item_id IN (".$value."))"));
                    }

                     continue;
                }

                // History update time
                if ($key == 'commission_infos.modified') {
                    if ($value == 1) {
                        // Within one hour, add the system date condition first
                        $query->where($key, '>=', date('Y/m/d H:i:s', strtotime("- 1 hours")));
                    } else {
                        // 1 time to drop
                        $query->where($key, '<=', date('Y/m/d H:i:s', strtotime("- 1 hours")));
                    }

                    continue;
                }

                // When searching for free words
                if ($key == 'm_corps.corp_name'
                    || $key == 'm_sites.site_name'
                    || $key == 'm_users.user_name'
                ) {
                    $exclusion = 'LIKE';
                    $value = '%' . $value . '%';
                    if ($key == 'm_users.user_name') {
                        $key = DB::raw('(CASE WHEN demand_infos.modified_user_id = \'AutomaticAuction\' THEN \'自動選定\' ELSE m_users.user_name END)');
                    }
                    $query->where($key, $exclusion, $value);
                    continue;
                }

                // Initial check check
                if ($key == 'commission_infos.first_commission' || $key == 'demand_infos.auction' || $key == 'demand_infos.cross_sell_implement') {
                    if ($value == 1) {
                        // None
                        $query->where(
                            function ($where) use ($key) {
                                $where->whereNull($key)
                                    ->orWhere($key, 0);
                            }
                        );
                    } else {
                        $query->where($key, 1);
                    }
                    continue;
                }

                if (is_array($value)) {
                    $query->whereIn($key, $value);
                } else {
                    $query->where($key, $value);
                }
            }
        }

        /**
         * SQL select column
         */
        $query = $this->setVirtualDetectContactDesiredTime($query, true);
        $query = $this->setVirtualOverLimit($query);

        $query = $query->addSelect(
            'demand_infos.*',
            'm_corps.id as m_corps_id',
            'm_corps.corp_name',
            'm_corps.official_corp_name',
            'm_corps.commission_dial',
            'm_sites.site_name',
            DB::raw('CASE WHEN demand_infos.modified_user_id = \'AutomaticAuction\' THEN \'自動選定\' ELSE m_users.user_name END as user_name'),
            'm_genres.commission_rank',
            'commission_infos.first_commission',
            'affiliation_infos.commission_count',
            'commission_infos.modified as modified2',
            'lock_user.user_name as lock_user_name',
            'visit_time_view.visit_time',
            'visit_time_view.visit_time_to',
            'visit_time_view.visit_adjust_time',
            'visit_time_view.is_visit_time_range_flg',
            DB::raw('CASE m_corps.contactable_support24hour WHEN 1 THEN \'24H対応\' ELSE m_corps.contactable_time_from || \'～\' || m_corps.contactable_time_to END as contactable'),
            DB::raw("(ARRAY_TO_STRING(ARRAY(".$holidayQuery."), ',')) as holiday"),
            DB::raw('(case when demand_infos.follow_date is null then \'Z\' when demand_infos.follow_date = \'\' then \'Y\' else demand_infos.follow_date end) as demand_follow_date')
        );

        /**
         * SQL order data
         */
        if (count($order) > 0) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        } else {
            $query = $query->orderBy('visit_time', 'asc')
                ->orderBy(DB::raw('detect_contact_desired_time'), 'asc')
                ->orderByRaw('auction desc NULLS LAST');
        }

        $result = $query->paginate(config('datacustom.report_number_row'));

        return $result;
    }

    /**
     * @param integer $page
     * @param string $sort
     * @param string $direction
     * @return mixed
     */
    public function getCorpSelectionPaginationCondition($page, $sort, $direction)
    {
        $query = $this->demandInfoRepository->joinQueryGetReportCorpSelection();

        $query->where('m_sites.jbr_flg', 0)
            ->where('demand_infos.del_flg', 0)
            ->where('demand_infos.demand_status', getDivValue('demand_status', 'no_selection'));
        $query = $this->setVirtualDetectContactDesiredTime($query);

        $query = $query->addSelect(
            'demand_infos.*',
            'visit_time_view_sort.visit_time',
            'visit_time_view_sort.visit_adjust_time',
            'm_sites.site_name',
            'm_categories.category_name',
            'm_genres.commission_rank',
            DB::raw('(case when demand_infos.follow_date is null then \'Z\' when demand_infos.follow_date = \'\' then \'Y\' else demand_infos.follow_date end) as demand_follow_date')
        );

        if ($sort && $direction) {
            if ($sort == 'prefecture') {
                $query = $query->orderBy('address1', $direction);
            } else {
                $query = $query->orderBy($sort, $direction);
            }
        } else {
            $query = $query->orderBy('visit_time', 'asc')
                ->orderByRaw('auction desc NULLS LAST');
        }

        $result = $query->paginate($page);

        return $result;
    }

    /**
     * Set the virtual field for sorting the contact due date and time
     *
     * @param object $query
     * @param  boolean $includeVisitTime
     * @return mixed
     */
    public function setVirtualDetectContactDesiredTime($query, $includeVisitTime = false)
    {

        if ($includeVisitTime) {
            $query = $query->select(
                DB::raw(
                    '(CASE WHEN visit_time_view.is_visit_time_range_flg = 1
	            THEN visit_time_view.visit_adjust_time
                WHEN demand_infos.is_contact_time_range_flg = 1
	            THEN demand_infos.contact_desired_time_from
                ELSE demand_infos.contact_desired_time END) as detect_contact_desired_time'
                )
            );
        } else {
            $query = $query->select(
                DB::raw(
                    '(CASE WHEN demand_infos.is_contact_time_range_flg = 1
                 THEN demand_infos.contact_desired_time_from
                 ELSE demand_infos.contact_desired_time END) as detect_contact_desired_time'
                )
            );
        }

        return $query;
    }

    /**
     * Set up virtual field with excessive time limit
     *
     * @param object $query
     * @param  boolean $set
     * @return mixed
     */
    public function setVirtualOverLimit($query, $set = true)
    {
        if ($set) {
            $query = $query->addSelect(
                DB::raw(
                    '(CASE WHEN demand_status IN (1, 2, 3)
                THEN (
	            CASE WHEN m_genres.commission_limit_time IS NULL THEN 0
	            WHEN demand_infos.receive_datetime + cast(m_genres.commission_limit_time || \' minutes\' as interval) < NOW()
	            THEN ROUND(EXTRACT(EPOCH FROM NOW() - demand_infos.receive_datetime - cast(m_genres.commission_limit_time || \' minutes\' as interval)))
                ELSE 0 END)
                ELSE 0 END) as limit_over_sec'
                )
            );
        }

        return $query;
    }

    /**
     * Delete designated general search information
     *
     * @param integer $id
     * @return mixed
     */
    public function deleteCommissionReportSearch($id)
    {
        // Pending for lack file from customer - Waiting ...
        return $id;
    }
}
