<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandInfo;
use App\Services\Auction\AuctionService;
use Auth;
use DB;

class BaseDemandInfoRepository extends SingleKeyModelRepository
{
    /**
     * @var DemandInfo
     */
    protected $model;
    /**
     * @var array
     */
    public static $demandStatusForSearch = [1, 2, 3, 4];
    /**
     * @var array
     */
    public static $auditionSelection = [2, 3];

    /**
     * BaseDemandInfoRepository constructor.
     * @param DemandInfo $model
     */
    public function __construct(
        DemandInfo $model
    ) {
        $this->model = $model;
    }
    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @param array $orders
     * @return mixed
     */
    public function queryForGetJbrCommissionReport($orders = [])
    {
        $query = $this->model->select(
            DB::raw(
                '(CASE m_corps.contactable_support24hour WHEN 1
                                THEN \'24H対応\' ELSE m_corps.contactable_time_from || \'</br>~</br>\' || m_corps.contactable_time_to END)
                                as demand_info_contactable'
            )
        )
            ->addSelect(
                DB::raw(
                    '((ARRAY_TO_STRING(ARRAY(SELECT "m_items"."item_name"
                                FROM m_corp_subs mcs INNER JOIN m_items ON "m_items"."item_category" = "mcs"."item_category"
                                AND "m_items"."item_id" = "mcs"."item_id" WHERE "mcs"."item_category" = \'休業日\'
                                AND "mcs"."corp_id" = "m_corps"."id"), \',\'))) AS "demand_infos_holiday"'
                )
            )
            ->addSelect(
                DB::raw(
                    '(CASE WHEN demand_status IN (1, 2, 3) THEN ( CASE WHEN "m_genres"."commission_limit_time" IS NULL THEN 0
                                WHEN "demand_infos"."receive_datetime" + cast("m_genres"."commission_limit_time" || \' minutes\' as interval) < NOW()
                                THEN ROUND(EXTRACT(EPOCH FROM NOW() - "demand_infos"."receive_datetime" - cast("m_genres"."commission_limit_time" || \' minutes\' as interval)))
                                ELSE 0 END ) ELSE 0 END) AS "demand_info_limit_over_sec"'
                )
            )
            ->addSelect(
                DB::raw(
                    '((CASE WHEN "demand_infos"."follow_date" is null THEN \'Z\'
                                WHEN "demand_infos"."follow_date" = \'\' THEN \'Y\' ELSE "demand_infos"."follow_date" END))
                                AS "demand_infos_demand_follow_date"'
                )
            )
            ->addSelect(
                DB::raw(
                    '((CASE WHEN "demand_infos"."follow_date" is null THEN \'Z\'
                            WHEN "demand_infos"."follow_date" = \'\' THEN \'Y\'
                            ELSE "demand_infos"."follow_date" END))
                            AS "demand_infos_demand_follow_date"'
                )
            )
            ->addSelect(
                DB::raw(
                    'visit_time_view.*, m_genres.*, m_sites.*, m_corps.corp_name as corp_name,
                                m_corps.id as m_corp_id, m_corps.commission_dial as commission_dial, demand_infos.*,
                                commission_infos.first_commission as first_commission, m_user_info.user_name as m_user_name,
                                commission_infos.modified as commission_info_modified'
                )
            )
            ->addSelect(DB::raw('(SELECT count(demand_infos.id) as total_record FROM demand_infos)'))
            ->addSelect(DB::raw('CAST(site_id AS char(255))'))
            ->leftJoin(
                DB::raw('public.visit_time_view as visit_time_view'),
                'visit_time_view.demand_id',
                '=',
                'demand_infos.id'
            )
            ->join(DB::raw('public.m_sites as m_sites'), function ($join) {
                $join->on('m_sites.id', '=', 'demand_infos.site_id')
                    ->where(function ($q) {
                        $q->where('m_sites.jbr_flg', 1)->orWhere('m_sites.id', 1314);
                    });
            })
            ->leftJoin(
                DB::raw(
                    '(SELECT min(id) AS id, demand_id FROM commission_infos WHERE lost_flg = 0
                                AND del_flg = 0 AND introduction_not = 0 GROUP BY demand_id) demand_commission_info'
                ),
                'demand_infos.id',
                '=',
                'demand_commission_info.demand_id'
            )
            ->leftJoin('commission_infos', 'demand_commission_info.id', '=', 'commission_infos.id')//commission_infos
            ->leftJoin('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id')
            ->leftJoin('affiliation_infos', 'affiliation_infos.corp_id', '=', 'm_corps.id')//affiliation_infos
            ->leftJoin(
                DB::raw('m_users as m_user_info'),
                'commission_infos.modified_user_id',
                '=',
                'm_user_info.user_id'
            )
            ->leftJoin('m_genres', function ($join) {
                $join->on('demand_infos.genre_id', '=', 'm_genres.id')->where('m_genres.valid_flg', '=', 1);
            })
            ->join('m_address1', function ($join) {
                $join->on(DB::raw("lpad(demand_infos.address1, 2, '0')"), '=', 'm_address1.address1_cd');
            })
            ->leftJoin(DB::raw('m_users as lock_users'), 'lock_users.id', '=', 'demand_infos.lock_user_id')
            ->whereIn('demand_infos.demand_status', self::$demandStatusForSearch)
            ->whereNotIn('demand_infos.selection_system', self::$auditionSelection)
            ->where('demand_infos.del_flg', 0);

        count($orders) == 0 ? $query->orderBy('visit_time_view.visit_time', 'asc')
            ->orderBy(
                DB::raw(
                    '(CASE WHEN "visit_time_view"."is_visit_time_range_flg" = 1 THEN "visit_time_view"."visit_adjust_time"
                                    WHEN "demand_infos"."is_contact_time_range_flg" = 1 THEN "demand_infos"."contact_desired_time_from"
                                    ELSE "demand_infos"."contact_desired_time" END)'
                ),
                'asc'
            )
            ->orderBy('demand_infos.auction', 'desc NULL LAST')
            : $this->generateOrderBy($query, $orders);
        return $query;
    }

    /**
     * order data
     * @param object $query
     * @param array $orders
     * @return mixed
     */
    private function generateOrderBy($query, $orders)
    {
        foreach ($orders as $nameSort => $sortBy) {
            $this->sortByKey($query, $nameSort, $sortBy);
        }
        $query->orderBy('demand_infos.id', 'asc');

        return $query;
    }

    /**
     * order data
     * @param object $query
     * @param string $orderKey
     * @param string $orderType
     * @return mixed
     */
    private function sortByKey($query, $orderKey, $orderType)
    {
        return array_key_exists(
            $orderKey,
            $this->model->conditionForOrder
        ) && $orderType != '' ? $query->orderBy(
            DB::raw($this->model->conditionForOrder[$orderKey]),
            $orderType
        ) : $query;
    }

    /**
     * @param object $query
     * @param string $setting
     * @return mixed
     */
    public function setJoinCondition($query, $setting)
    {
        $receiveDatetime = '"DemandInfoLatest"."receive_datetime"';
        $query->leftJoin(
            'demand_infos as DemandInfoLatest',
            function ($join) use ($setting, $receiveDatetime) {
                $join->on('DemandInfoLatest.id', 'demand_infos.id');
                $join->where('DemandInfoLatest.order_fail_reason', 38);
                $join->whereRaw("cast(" . $receiveDatetime . " as date) > (select cast( now() as date)  - cast('" . $setting['immediate_date'] . " day' as interval) as date)");
            }
        );
        $query->join('m_address1', 'm_address1.address1_cd', '=', 'demand_infos.address1');
        $query->join(
            DB::raw('(SELECT jis_cd, address1, address2 FROM m_posts AS "MPostA" WHERE 1 = 1 GROUP BY jis_cd, address1, address2) as "MPost"'),
            function ($join) {
                $join->on([
                    ['MPost.address1', '=', 'm_address1.address1'],
                    ['MPost.address2', '=', 'demand_infos.address2']
                ]);
            }
        );
        $query->leftJoin('not_corresponds', function ($join) {
            $join->on([
                ['not_corresponds.jis_cd', '=', 'MPost.jis_cd'],
                ['not_corresponds.prefecture_cd', '=', 'demand_infos.address1'],
                ['not_corresponds.genre_id', '=', 'demand_infos.genre_id']
            ]);
        });
        return $query;
    }

    /**
     * Get list data demand by selection_system
     * Use in DemandInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param array $selectionSystem
     * @return mixed
     */
    public function getForDemandGuideSendMail($selectionSystem)
    {
        return $this->model->join("auction_infos", "demand_infos.id", "=", "auction_infos.demand_id")
            ->join("m_corps", "m_corps.id", "=", "auction_infos.corp_id")
            ->where("auction_infos.push_flg", 0)
            ->whereIn("demand_infos.selection_system", $selectionSystem)
            ->where("demand_infos.push_stop_flg", 0)
            ->where("auction_infos.push_time", "<", date("Y/m/d H:i"))
            ->where("demand_infos.del_flg", 0)
            ->select(["demand_infos.*", "auction_infos.id as auction_info_id", "auction_infos.push_flg",
                "m_corps.mailaddress_auction", "m_corps.official_corp_name", "m_corps.coordination_method",
                "m_corps.id as m_corp_id"])->get();
    }

    /**
     * @author thaihv
     * @param  itn $customerTel customer telephone
     * @return mixed
     */
    public function getFirstDemandByTel($customerTel)
    {
        return $this->model->where('customer_tel', $customerTel)->select('demand_status')
            ->orderBy('demand_status', 'ASC')->first();
    }

    /**
     * Get list auction auto call.
     *
     * @param bool $autoCallFlg
     * @return mixed
     */
    public function getAutoCallList($autoCallFlg)
    {
        $tableColumn = [
            'auction_infos.id as auction_id',
            'auction_infos.auto_call_flg as auction_auto_call_flg',
            'm_corps.id as m_corp_id',
            'm_corps.official_corp_name as m_corp_official_corp_name',
            'm_corps.commission_dial as m_corp_commission_dial',
            'm_corps.auto_call_flag as m_corp_auto_call_flag',
            'm_genres.auto_call_flag as m_genres_auto_call_flag'
        ];
        return $this->model
            ->select($tableColumn)
            ->join('auction_infos', 'auction_infos.demand_id', '=', 'demand_infos.id')
            ->join('m_corps', 'auction_infos.corp_id', '=', 'm_corps.id')
            ->join('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id')
            ->where(
                function ($query) use ($autoCallFlg) {
                    $query->whereIn(
                        'demand_infos.selection_system',
                        [
                            getDivValue('selection_type', 'auction_selection'),
                            getDivValue('selection_type', 'automatic_auction_selection')
                        ]
                    );
                    $query->where('demand_infos.push_stop_flg', '=', 0);
                    $query->where('auction_infos.refusal_flg', '=', 0);
                    $query->where('auction_infos.auto_call_time', '<', date('Y/m/d H:i'));
                    $query->where('auction_infos.auto_call_flg', '=', $autoCallFlg ? 1 : 0);
                    $query->where('m_corps.auto_call_flag', '=', 1);
                    $query->where('m_genres.auto_call_flag', '=', 1);
                    $query->where('demand_infos.del_flg', '=', 0);
                }
            )->get();
    }

    /**
     * @param null $customerTel
     * @return int|null
     */
    public function checkIdenticallyCustomer($customerTel = null)
    {

        if (!empty($customerTel)) {
            return $this->model->where('customer_tel', "$customerTel")->where('demand_status', '!=', 0)->count();
        }

        return null;
    }

    /**
     * @param integer $id
     * @return array
     */
    public function findById($id)
    {
        $results = [];
        $data = $this->model->with('commissionInfo')
            ->with('introduceInfo')
            ->with('demandCorrespondHistory')
            ->with('demandInquiryAnswers')
            ->with('demandInquiryAnswers.mInquiry')
            ->where('id', $id)->first();

        if (!empty($data)) {
            $data = $data->toArray();

            $results['CommissionInfo'] = $data['commission_info'];
            unset($data['commission_info']);
            $results['IntroduceInfo'] = $data['introduce_info'];
            unset($data['introduce_info']);
            $results['DemandCorrespondHistory'] = $data['demand_correspond_history'];
            unset($data['demand_correspond_history']);
            $results['MInquiry'] = $data['demand_inquiry_answers'];
            unset($data['demand_inquiry_answers']);
            $results['DemandInfo'] = $data;
        }

        return $results;
    }

    /**
     * search demand info list
     *
     * @param  array $data
     * @param  null  $orderBy
     * @return array object
     */
    public function searchDemandInfoList($data = null, $orderBy = null)
    {
        $role = Auth::user()->auth;
        $isRoleAffiliation = AuctionService::isRole($role, ['affiliation']);
        $dateNow = date('Y-m-d H:i');

        $selectionType = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ];
        $fields = '(SELECT ARRAY_TO_STRING(ARRAY( SELECT visit_time FROM visit_times WHERE visit_times.demand_id = "demand_infos"."id" ORDER BY visit_times.visit_time ASC ),\'｜\')) as "visit_time"';
        $condition = $this->checkRoleSearchDemandInfoList($isRoleAffiliation, $fields);
        $condition = $this->queryJoinSearchDemandInfoList($condition, $selectionType, $isRoleAffiliation, $dateNow);
        if (!empty($data['genre_id'])) {
            $condition = $condition->whereIn('demand_infos.genre_id', $data['genre_id']);
        }
        if (!empty($data['address1'])) {
            $condition = $condition->whereIn('demand_infos.address1', $data['address1']);
        }
        if (!empty($data['display'])) {
            $condition = $condition->where('commission_infos.id', null);
        }
        if ($isRoleAffiliation) {
            $condition = $condition
                ->groupBy(
                    'demand_infos.id',
                    'm_sites.site_name',
                    'm_sites.site_url',
                    'm_genres.genre_name',
                    'auction_infos.id',
                    'visit_time.visit_time_min',
                    'm_corps.auction_masking',
                    'visit_times_pop.prop',
                    'm_genres.auction_fee',
                    'select_genre_prefectures.auction_fee',
                    'deman_attached_file.demand_attached_file_id'
                );
        } else {
            $condition = $condition
                ->groupBy(
                    'demand_infos.id',
                    'm_sites.site_name',
                    'm_sites.site_url',
                    'm_genres.genre_name',
                    'visit_time.visit_time_min',
                    'auction_infos.push_time',
                    'visit_times_pop.prop',
                    'm_genres.auction_fee',
                    'select_genre_prefectures.auction_fee',
                    'deman_attached_file.demand_attached_file_id'
                );
        }
        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $condition->orderBy($key, $value);
            }
        }
        return $condition->paginate(config('rits.list_limit'));
    }

    /**
     * @param $isRoleAffiliation
     * @param $fields
     * @return mixed
     */
    private function checkRoleSearchDemandInfoList($isRoleAffiliation, $fields)
    {
        if ($isRoleAffiliation) {
            return $this->model
                ->select(
                    'demand_infos.*',
                    'm_sites.site_name',
                    'm_sites.site_url',
                    'm_genres.genre_name',
                    'auction_infos.id as auction_info_id',
                    'visit_time.visit_time_min',
                    'm_corps.auction_masking',
                    'visit_times_pop.prop',
                    'm_genres.auction_fee',
                    'select_genre_prefectures.auction_fee',
                    'deman_attached_file.demand_attached_file_id',
                    DB::raw($fields)
                );
        }
        return $this->model
            ->select(
                'demand_infos.*',
                'm_sites.site_name',
                'm_sites.site_url',
                'm_genres.genre_name',
                'visit_time.visit_time_min',
                'auction_infos.push_time',
                'visit_times_pop.prop',
                'm_genres.auction_fee',
                'select_genre_prefectures.auction_fee',
                'deman_attached_file.demand_attached_file_id',
                DB::raw($fields)
            );
    }

    /**
     * queryJoinSearchDemandInfoList function
     * @param mixed $condition
     * @param mixed $selectionType
     * @param boolean $isRoleAffiliation
     * @param mixed $dateNow
     * @return mixed
     */
    private function queryJoinSearchDemandInfoList($condition, $selectionType, $isRoleAffiliation, $dateNow)
    {
        return $condition->leftJoin('m_sites', 'm_sites.id', '=', 'demand_infos.site_id')
            ->leftJoin('m_genres', 'm_genres.id', '=', 'demand_infos.genre_id')
            ->join(
                'auction_infos',
                function ($join) use ($isRoleAffiliation) {
                    $join->on('auction_infos.demand_id', '=', 'demand_infos.id');
                    $join->where('auction_infos.refusal_flg', '!=', 1);
                    $join->where('auction_infos.push_flg', 1);
                    $user = Auth::user();
                    if ($isRoleAffiliation) {
                        $join->where('auction_infos.corp_id', $user->affiliation_id);
                    }
                }
            )
            ->leftJoin(
                DB::raw('(SELECT demand_id as visit_time_demand_id, MIN(visit_time) as visit_time_min FROM (SELECT demand_id, case when is_visit_time_range_flg = 0 then visit_time else visit_time_from end as visit_time from visit_times) as A GROUP BY visit_time_demand_id) as visit_time'),
                'visit_time.visit_time_demand_id',
                '=',
                'demand_infos.id'
            )
            ->leftJoin(
                DB::raw('(SELECT demand_id as visit_times_pop_demand_id, min(concat(case when is_visit_time_range_flg = 0 then visit_time else visit_time_from end, \'|\', is_visit_time_range_flg, \'|\', visit_time_to, \'|\', visit_adjust_time)) as prop from visit_times as A group by visit_times_pop_demand_id) as visit_times_pop'),
                'visit_times_pop.visit_times_pop_demand_id',
                '=',
                'demand_infos.id'
            )
            ->join('m_corps', 'm_corps.id', '=', 'auction_infos.corp_id')
            ->leftJoin(
                'select_genre_prefectures',
                function ($join) {
                    $join->on('select_genre_prefectures.genre_id', '=', 'demand_infos.genre_id');
                    $join->on('select_genre_prefectures.prefecture_cd', '=', 'demand_infos.address1');
                }
            )
            ->leftJoin('commission_infos', 'commission_infos.demand_id', '=', 'demand_infos.id')
            ->leftJoin(
                DB::raw('(select max(id) as demand_attached_file_id, demand_id as demand_attached_file_demand_id from demand_attached_files where delete_flag = false group by demand_attached_file_demand_id) as deman_attached_file'),
                'deman_attached_file.demand_attached_file_demand_id',
                '=',
                'demand_infos.id'
            )
            ->whereNotIn('demand_infos.demand_status', ["6", "9"])
            ->where(
                function ($query) use ($dateNow) {
                    $query->orWhere('visit_time.visit_time_min', '>', $dateNow);
                    $query->orWhere(
                        function ($query) use ($dateNow) {
                            $query->where('visit_time.visit_time_min', null);
                            $query->where(
                                function ($query) use ($dateNow) {
                                    $query->orWhere(
                                        function ($query) use ($dateNow) {
                                            $query->where('demand_infos.is_contact_time_range_flg', 0);
                                            $query->where('demand_infos.contact_desired_time', '>', $dateNow);
                                        }
                                    );
                                    $query->orWhere(
                                        function ($query) use ($dateNow) {
                                            $query->where('demand_infos.is_contact_time_range_flg', 1);
                                            $query->where('demand_infos.contact_desired_time_from', '>', $dateNow);
                                        }
                                    );
                                }
                            );
                        }
                    );
                }
            )
            ->whereIn('demand_infos.selection_system', $selectionType)
            ->where('demand_infos.del_flg', 0);
    }
    /**
     * Check deadline past auction to command cron tab
     *
     * @param object $subQuery
     * @return mixed
     */
    public function commandCheckDeadlinePastAuction($subQuery)
    {
        $selectionSystem = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ];

        $results = $this->model->leftJoin(DB::raw("({$subQuery->toSql()}) as sub_commission_info"), function ($join) {
            $join->on('sub_commission_info.demand_id', '=', 'demand_infos.id');
        })->mergeBindings($subQuery->getQuery())
            ->whereNull('sub_commission_info.demand_id')
            ->where('demand_infos.auction', 0)
            ->whereIn('demand_infos.selection_system', $selectionSystem)
            ->where('demand_infos.auction_deadline_time', '<', date("Y/m/d H:i"))
            ->select(
                'demand_infos.id',
                'demand_infos.auction',
                'demand_infos.selection_system',
                'demand_infos.site_id'
            )->get();
        return $results;
    }

    /**
 * get data execute normal
 * @param  date $date
 * @return array object
 */
    public function getDataExecuteNormal($date)
    {
        $selectionSystem = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ];
        $demandStatus = [
            getDivValue("demand_status", "telephone_already"),
            getDivValue("demand_status", "information_sent")
        ];
        return $this->model
            ->select(
                'demand_infos.*',
                'auction_infos.id as auction_infos_id',
                'auction_infos.before_push_flg',
                'visit_times.visit_time',
                'm_corps.mailaddress_auction',
                'm_corps.official_corp_name'
            )
            ->join('auction_infos', 'demand_infos.id', '=', 'auction_infos.demand_id')
            ->join(
                'visit_times',
                function ($joins) {
                    $joins->on('demand_infos.id', '=', 'visit_times.demand_id');
                    $joins->on('auction_infos.visit_time_id', '=', 'visit_times.id');
                }
            )
            ->join('m_corps', 'auction_infos.corp_id', '=', 'm_corps.id')
            ->whereRaw('exists(SELECT demand_id FROM commission_infos INNER JOIN demand_infos ON commission_infos.demand_id = demand_infos.id WHERE commission_infos.commit_flg = 1 AND commission_infos.commission_status != ' . getDivValue(
                "construction_status",
                "order_fail"
            ) . ' AND demand_id = demand_infos.id)')
            ->where('auction_infos.before_push_flg', 0)
            ->whereIn('demand_infos.selection_system', $selectionSystem)
            ->where('demand_infos.priority', getDivValue('priority', 'normal'))
            ->where('visit_times.visit_time', '<', $date)
            ->whereIn('demand_infos.demand_status', $demandStatus)
            ->get();
    }

    /**
     * @param object $subQuery
     * @return mixed
     */
    public function commandCheckCorpRefusal($subQuery)
    {
        $selectionSystem = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ];

        $results = $this->model->leftJoin(DB::raw("({$subQuery->toSql()}) as sub_commission_info"), function ($join) {
            $join->on('sub_commission_info.demand_id', '=', 'demand_infos.id');
        })->mergeBindings($subQuery->getQuery())
            ->whereNull('sub_commission_info.demand_id')
            ->where('demand_infos.auction', 0)
            ->whereIn('demand_infos.selection_system', $selectionSystem)
            ->select(
                'demand_infos.id',
                'demand_infos.auction',
                'demand_infos.selection_system'
            )->get();
        return $results;
    }

    /**
     * get data excute immediately
     *
     * @param  date $date
     * @return array object
     */
    public function getDataExecuteImmediately($date)
    {
        $selectionSystem = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ];
        $demandStatus = [
            getDivValue("demand_status", "telephone_already"),
            getDivValue("demand_status", "information_sent")
        ];
        return $this->model
            ->select(
                'demand_infos.*',
                'auction_infos.id as auction_infos_id',
                'auction_infos.before_push_flg',
                'visit_times.visit_time',
                'm_corps.mailaddress_auction',
                'm_corps.official_corp_name'
            )
            ->join('auction_infos', 'demand_infos.id', '=', 'auction_infos.demand_id')
            ->join(
                'visit_times',
                function ($joins) {
                    $joins->on('demand_infos.id', '=', 'visit_times.demand_id');
                    $joins->on('auction_infos.visit_time_id', '=', 'visit_times.id');
                }
            )
            ->join('m_corps', 'auction_infos.corp_id', '=', 'm_corps.id')
            ->whereRaw('exists(SELECT demand_id FROM commission_infos INNER JOIN demand_infos ON commission_infos.demand_id = demand_infos.id WHERE commission_infos.commit_flg = 1 AND commission_infos.commission_status != ' . getDivValue(
                "construction_status",
                "order_fail"
            ) . ' AND demand_id = demand_infos.id)')
            ->where('auction_infos.before_push_flg', 0)
            ->whereIn('demand_infos.selection_system', $selectionSystem)
            ->where('demand_infos.priority', getDivValue('priority', 'immediately'))
            ->where('visit_times.visit_time', '<', $date)
            ->whereIn('demand_infos.demand_status', $demandStatus)
            ->get();
    }

    /**
     * @param integer $demandInfoId
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getWorData($demandInfoId, $corpId)
    {
        return $this->model->whereHas('mSite')
            ->whereHas('commissionWord', function ($q) use ($corpId) {
                $q->where('corp_id', $corpId);
            })
            ->with('mUser')
            ->where('id', $demandInfoId)
            ->first();
    }

    /**
     * @param array $fields
     * @param array $sortData
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDataUnSentList($fields, $sortData)
    {
        $fields[] = DB::raw(
            '(CASE WHEN demand_infos.is_contact_time_range_flg = 1
                     THEN demand_infos.contact_desired_time_from
                     ELSE demand_infos.contact_desired_time END) as detect_contact_desired_time'
        );
        $query = $this->model
            ->select($fields)
            ->join('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->join(
                'commission_infos',
                function ($joins) {
                    $joins->on('demand_infos.id', '=', 'commission_infos.demand_id');
                    $joins->where('commission_infos.commit_flg', '=', 1);
                }
            )
            ->join('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id')
            ->where('m_sites.jbr_flg', 0)
            ->where('demand_infos.demand_status', getDivValue('demand_status', 'telephone_already'))
            ->where('demand_infos.del_flg', 0);

        foreach ($sortData as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query->paginate(config('rits.report_list_limit'));
    }

    /**
     * Update del_flg of demand info
     *
     * {@inheritDoc}
     *
     * @see \App\Repositories\DemandInfoRepositoryInterface::deleteByDemandId()
     */
    public function deleteByDemandId($id)
    {
        $demandInfo = $this->model->where('id', $id)->first();
        $demandInfo->del_flg = 1;
        $demandInfo->save();

        return $demandInfo;
    }

    /**
     * @param array $data
     * @return bool|int
     */
    public function updateExecuteFollowDate($data)
    {
        return $this->model->where('follow_date', '!=', '')
            ->whereNotNull('follow_date')
            ->where('follow_date', '<', date('Y/m/d H:i'))
            ->where('del_flg', 0)->update($data);
    }

    /**
     * @param string $setting
     * @return mixed
     */
    public function getDemandInfos($setting)
    {
        $query = $this->model->select(
            'MPost.jis_cd',
            'demand_infos.address1',
            'demand_infos.address2',
            'demand_infos.genre_id',
            'not_corresponds.id as not_correspond_id',
            'not_corresponds.created as not_correspond_created',
            DB::raw('count(demand_infos.*) as DemandInfo__not_correspond_count_year'),
            DB::raw('count("DemandInfoLatest".*) as DemandInfo__not_correspond_count_latest')
        );
        $query = $this->setJoinCondition($query, $setting);
        $query = $query->whereRaw("demand_infos.address1 != '" . getDivValue(
            'prefecture_div',
            MItemRepository::UNKNOWN
        ) . "'")
            ->where('demand_infos.order_fail_reason', 38)
            ->whereRaw("cast(demand_infos.receive_datetime as date) > (select cast( now() as date)  - cast('1 years' as interval) as date)");
        $query = $query->groupBy(
            'MPost.jis_cd',
            'demand_infos.address1',
            'demand_infos.address2',
            'demand_infos.genre_id',
            'not_correspond_id'
        );
        return $query = $query->get();
    }
}
