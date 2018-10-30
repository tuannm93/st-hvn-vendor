<?php

namespace App\Repositories\Eloquent;

use App\Models\DemandInfo;
use App\Models\MCategory;
use App\Models\MGenre;
use App\Models\MSite;
use App\Models\MUser;
use App\Repositories\DemandInfoRepositoryInterface;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DemandInfoRepository extends BaseDemandInfoRepository implements DemandInfoRepositoryInterface
{
    /**
     * @var MSite
     */
    public $mSite;
    /**
     * @var MGenre
     */
    public $mGenre;
    /**
     * @var MCategory
     */
    public $mCategory;
    /**
     * @var MUser
     */
    public $mUser;
    /**
     * @var mixed
     */
    public $demandInfoService;
    /**
     * @var DemandInfo
     */
    protected $model;
    /**
     * @var string
     */
    protected $whererawConditions = '';
    /**
     * @var CommissionInfoRepository $commissionRepo
     */
    protected $commissionRepo;

    /**
     * DemandInfoRepository constructor.
     * @param DemandInfo $model
     * @param \App\Repositories\Eloquent\CommissionInfoRepository $commissionRepo
     */
    public function __construct(
        DemandInfo $model,
        CommissionInfoRepository $commissionRepo
    ) {
        parent::__construct($model);
        $this->commissionRepo = $commissionRepo;
    }

    /**
     * @return \App\Models\Base|DemandInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new DemandInfo();
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDemandInfo($data)
    {
        $queryDemand = $this->getQueryDemandInfo($data);
        $demandInfo = $queryDemand->paginate(\Config::get('datacustom.deman_number_row'));
        //return data width conditions
        return $demandInfo;
    }

    /**
     * get data for export csv
     * @param array $conditions
     * @param string $type
     * @return array
     */
    public function getDataCsv($conditions = [], $type = 'Demand')
    {
        $queryDemand = $this->getQueryDemandInfo($conditions);
        if ($type == 'Demand') {
            $queryDemand->leftjoin('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id');
            $queryDemand->leftjoin(
                'commission_infos',
                function ($join) {
                    $join->on('demand_infos.id', '=', 'commission_infos.demand_id');
                    $join->on(
                        function ($query) {
                            $query->on('commission_infos.commit_flg', DB::raw('1'));
                            $query->orOn('commission_infos.commission_type', DB::raw('1'));
                        }
                    );
                }
            );
            $queryDemand->leftjoin('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id');
        } else {
            $queryDemand->leftjoin('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id');
            $queryDemand->leftjoin('commission_infos', 'demand_infos.id', '=', 'commission_infos.demand_id');
            $queryDemand->leftjoin('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id');
        }
        $demandInfo = $queryDemand->select(
            'demand_infos.*',
            'commission_infos.commit_flg',
            'commission_infos.commission_type',
            'commission_infos.appointers',
            'commission_infos.first_commission',
            'commission_infos.corp_fee',
            'commission_infos.attention',
            'commission_infos.commission_dial',
            'commission_infos.tel_commission_datetime',
            'commission_infos.tel_commission_person',
            'commission_infos.commission_fee_rate',
            'commission_infos.commission_note_send_datetime',
            'commission_infos.commission_note_sender',
            'commission_infos.commission_status',
            'commission_infos.commission_order_fail_reason',
            'commission_infos.complete_date as commission_infos_complete_date',
            'commission_infos.order_fail_date as commission_infos_order_fail_date',
            'commission_infos.estimate_price_tax_exclude',
            'commission_infos.construction_price_tax_exclude',
            'commission_infos.construction_price_tax_include',
            'commission_infos.deduction_tax_include',
            'commission_infos.deduction_tax_exclude',
            'commission_infos.confirmd_fee_rate',
            'commission_infos.unit_price_calc_exclude',
            'commission_infos.report_note',
            'commission_infos.corp_id',
            'commission_infos.send_mail_fax',
            'commission_infos.send_mail_fax_datetime',
            'commission_infos.id AS commission_id',
            'm_sites.site_name',
            'm_genres.genre_name',
            'm_categories.category_name',
            'm_corps.corp_name',
            'm_corps.official_corp_name'
        )->get()->toarray();
        return $demandInfo;
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQueryDemandInfo($data)
    {
        $queryDemand = DB::table('demand_infos');
        $whereConditions = [];
        $orwhereConditions = [];
        if (!empty($data['corp_id'])) {
            $queryDemand->whereExists(
                function ($query) use ($data) {
                    $query->select("demand_id")
                        ->from("commission_infos")
                        ->join("m_corps", "commission_infos.corp_id", "=", "m_corps.id")
                        ->where('corp_id', $data['corp_id'])
                        ->whereRaw('"demand_infos"."id"="commission_infos"."demand_id"')
                        ->where('commission_infos.del_flg', 0);
                }
            );
        } elseif (!empty($data['corp_name']) || !empty($data['corp_name_kana'])) {
            if (!empty($data['corp_name']) && !empty($data['corp_name_kana'])) {
                $this->whererawConditions = "m_corps.corp_name like " . "'%" . $data['corp_name'] . "%'";
                $this->whererawConditions = $this->whererawConditions . "AND m_corps.corp_name_kana like " . "'%" . $data['corp_name_kana'] . "%'";
            } elseif (!empty($data['corp_name'])) {
                $this->whererawConditions = "m_corps.corp_name like " . "'%" . $data['corp_name'] . "%'";
            } elseif (!empty($data['corp_name_kana'])) {
                $this->whererawConditions = "m_corps.corp_name_kana like " . "'%" . $data['corp_name_kana'] . "%'";
            }
            $queryDemand->whereExists(
                function ($query) {
                    $query->select("demand_id")
                        ->from("commission_infos")
                        ->join("m_corps", "commission_infos.corp_id", "=", "m_corps.id")
                        ->whereRaw($this->whererawConditions)
                        ->whereRaw('"demand_infos"."id"="commission_infos"."demand_id"')
                        ->where('commission_infos.del_flg', 0);
                }
            );
        }
        if (!empty($data['demand_status']) && is_array($data['demand_status']) && !array_diff(
            $data['demand_status'],
            [1, 3]
        )) {
            if (isset($data['lost_flg_filter']) && $data['lost_flg_filter'] == 'on') {
                $queryDemand->whereRaw('exists( SELECT demand_id FROM commission_infos WHERE "commission_infos"."lost_flg" = 1 AND "demand_infos"."id" = "commission_infos"."demand_id") = false');
            }
        }
        if (!empty($data['customer_tel'])) {
            $queryDemand->where(
                function ($query) use ($data) {
                    $query->orWhere('demand_infos.customer_tel', $data['customer_tel']);
                    $query->orWhere('demand_infos.tel1', $data['customer_tel']);
                    $query->orWhere('demand_infos.tel2', $data['customer_tel']);
                }
            );
        }
        if (!empty($data['customer_name'])) {
            array_push($whereConditions, ['demand_infos.customer_name', 'like', '%' . $data['customer_name'] . '%']);
        }
        if (!empty($data['id'])) {
            array_push($whereConditions, ['demand_infos.id', '=', chgSearchValue($data['id'])]);
        }
        if (!empty($data['site_tel'])) {
            array_push($whereConditions, ['m_sites.site_tel', '=', chgSearchValue($data['site_tel'])]);
        }
        if (!empty($data['from_contact_desired_time'])) {
            $queryDemand->where(DB::raw('(CASE WHEN demand_infos.is_contact_time_range_flg = 1
	                THEN demand_infos.contact_desired_time_from
                    ELSE demand_infos.contact_desired_time END)'), '>=', $data['from_contact_desired_time']);
        }
        if (!empty($data['to_contact_desired_time'])) {
            $queryDemand->where(DB::raw('(CASE WHEN demand_infos.is_contact_time_range_flg = 1
	                THEN demand_infos.contact_desired_time_from
                    ELSE demand_infos.contact_desired_time END)'), '<=', $data['to_contact_desired_time']);
        }
        if (!empty($data['demand_status'])) {
            $queryDemand->whereIn('demand_infos.demand_status', $data['demand_status']);
        }
        if (!empty($data['site_id'])) {
            $queryDemand->whereIn('demand_infos.site_id', $data['site_id']);
        }
        if (!empty($data['jbr_order_no'])) {
            array_push($whereConditions, ['demand_infos.jbr_order_no', '=', $data['jbr_order_no']]);
        }
        if (!empty($data['from_receive_datetime'])) {
            array_push($whereConditions, ['demand_infos.receive_datetime', '>=', $data['from_receive_datetime']]);
        }
        if (!empty($data['to_receive_datetime'])) {
            array_push($whereConditions, ['demand_infos.receive_datetime', '<=', $data['to_receive_datetime']]);
        }
        if (!empty($data['from_follow_date'])) {
            array_push($whereConditions, ['demand_infos.follow_date', '>=', $data['from_follow_date']]);
        }
        if (!empty($data['to_follow_date'])) {
            array_push($whereConditions, ['demand_infos.follow_date', '<=', $data['to_follow_date']]);
        }
        $queryDemand->leftjoin('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->leftjoin('m_categories', 'demand_infos.category_id', '=', 'm_categories.id')
            ->where('demand_infos.del_flg', '=', '0')
            ->where($whereConditions)->orwhere($orwhereConditions)
            ->addSelect(
                'demand_infos.*',
                'm_sites.site_name',
                'm_categories.category_name',
                DB::raw('(CASE WHEN "demand_infos"."is_contact_time_range_flg" = 1 THEN "demand_infos"."contact_desired_time_from" ELSE "demand_infos"."contact_desired_time" END) AS "detect_contact_desired_time"')
            );
        if (isset($data['b_check']) && $data['b_check'] && isset($data['corp_id'])) {
            $results = $this->commissionRepo->getDemandIdByCorp($data['corp_id']);
            $demandIds = [];
            if ($results) {
                foreach ($results as $result) {
                    $demandIds[] = $result->demand_id;
                }
            }
            $demandIdIn = implode(',', $demandIds);
            if (!empty($demandIdIn)) {
                $queryDemand->leftjoin(DB::raw('(SELECT rank() over (partition by demand_id order by demand_id desc, id asc) as rnk, demand_id, corp_id FROM "public"."commission_infos" AS "RankTable" WHERE "demand_id" IN ('.$demandIdIn.') AND "commit_flg" = 0 AND "del_flg" = 0 AND "lost_flg" = 0) AS "RankTable"'), function ($join) use ($data) {
                    $join->on('demand_infos.id', '=', 'RankTable.demand_id');
                    $join->on('RankTable.corp_id', '=', DB::raw($data['corp_id']));
                });
            } else {
                $queryDemand->leftjoin(DB::raw('(SELECT rank() over (partition by demand_id order by demand_id desc, id asc) as rnk, demand_id, corp_id FROM "public"."commission_infos" AS "RankTable" WHERE "commit_flg" = 0 AND "del_flg" = 0 AND "lost_flg" = 0) AS "RankTable"'), function ($join) use ($data) {
                    $join->on('demand_infos.id', '=', 'RankTable.demand_id');
                    $join->on('RankTable.corp_id', '=', DB::raw($data['corp_id']));
                });
            }
            $queryDemand->addSelect('RankTable.rnk AS DemandInfo__CommissionRank');
            $queryDemand->orderBy('RankTable.rnk', 'asc');
            if (empty($data['id'])) {
                $queryDemand->whereIn('demand_infos.id', $demandIds);
            }
        }
        if (isset($data['sort']) && isset($data['direction'])) {
            if ($data['sort'] == 'demand_infos.contact_desired_time') {
                $queryDemand->orderBy(DB::raw('CASE WHEN demand_infos.is_contact_time_range_flg = 1
	                THEN demand_infos.contact_desired_time_from
                    ELSE demand_infos.contact_desired_time END'), $data['direction']);
            } else {
                $queryDemand->orderBy($data['sort'], $data['direction']);
            }
        } else {
            $queryDemand->orderBy('demand_infos.id', 'desc');
        }
        return $queryDemand;
    }

    /**
     * @param $dataList
     * @return mixed
     */
    public function convertDataCsv($dataList)
    {
        $demandStatusList = getDropList(trans('demandlist.demand_status'));
        $orderFailReasonList = getDropList(trans('demandlist.order_fail_reason'));
        $siteList = $this->mSite->getList();
        $genreList = $this->mGenre->getList();
        $categoryList = $this->mCategory->getList();
        $petTombstoneDemandList = getDropList(trans('demandlist.pet_tombstone_demand'));
        $smsDemandList = getDropList(trans('demandlist.sms_demand'));
        $jbrWorkContentsList = getDropList(trans('demandlist.jbr_work_contents'));
        $jbrCategoryList = getDropList(trans('demandlist.jbr_category'));
        $userList = $this->mUser->dropDownUser();
        $jbrEstimateStatusList = getDropList(trans('demandlist.jbr_estimate_status'));
        $jbrReceiptStatusList = getDropList(trans('demandlist.jbr_receipt_status'));
        $sendMailFaxList = ['' => '', '0' => '', '1' => '送信済み'];
        $acceptanceStatusList = getDropList(trans('demandlist.acceptance_status'));
        $commissionStatusList = getDropList(trans('demandlist.commission_status'));
        $commissionOrderFailReasonList = getDropList(trans('demandlist.commission_order_fail_reason'));
        $selectionSystemList = getDivList('datacustom.selection_type', 'demandlist');
        $data = $dataList;

        $changeArray = [
            0 => 'mail_demand',
            1 => 'nighttime_takeover',
            2 => 'low_accuracy',
            3 => 'remand',
            4 => 'immediately',
            5 => 'corp_change',
            6 => 'sms_reorder'
        ];

        foreach ($dataList as $key => $val) {
            foreach ($changeArray as $v) {
                if ($val->$v == 0) {
                    $data[$key]->$v = trans('demandlist.batu');
                } else {
                    $data[$key]->$v = trans('demandlist.maru');
                }
            }
            $data[$key]->demand_status = !empty($val->demand_status) ? $demandStatusList[$val->demand_status] : '';
            $data[$key]->order_fail_reason = !empty($val->order_fail_reason) ? $orderFailReasonList[$val->order_fail_reason] : '';
            $data[$key]->site_name = !empty($val->site_name) ? $val->site_name : '';
            $data[$key]->genre_name = !empty($val->genre_name) ? $val->genre_name : '';
            $data[$key]->category_name = !empty($val->category_name) ? $val->category_name : '';
            $data[$key]->cross_sell_source_site = !empty($val->cross_sell_source_site) ? $siteList[$val->cross_sell_source_site] : '';
            $data[$key]->cross_sell_source_genre = !empty($val->cross_sell_source_genre) ? $genreList[$val->cross_sell_source_genre] : '';
            $data[$key]->cross_sell_source_category = !empty($val->cross_sell_source_category) ? $categoryList[$val->cross_sell_source_category] : '';
            $data[$key]->pet_tombstone_demand = !empty($val->pet_tombstone_demand) ? $petTombstoneDemandList[$val->pet_tombstone_demand] : '';
            $data[$key]->sms_demand = !empty($val->sms_demand) ? $smsDemandList[$val->sms_demand] : '';
            $data[$key]->receptionist = !empty($val->receptionist) ? $userList[$val->receptionist] : '';
            $data[$key]->address1 = !empty($val->address1) ? $val->address1 : '';
            $data[$key]->jbr_work_contents = !empty($val->jbr_work_contents) ? $jbrWorkContentsList[$val->jbr_work_contents] : '';
            $data[$key]->jbr_category = !empty($val->jbr_category) ? $jbrCategoryList[$val->jbr_category] : '';
            $data[$key]->jbr_estimate_status = !empty($val->jbr_estimate_status) ? $jbrEstimateStatusList[$val->jbr_estimate_status] : '';
            $data[$key]->jbr_receipt_status = !empty($val->jbr_receipt_status) ? $jbrReceiptStatusList[$val->jbr_receipt_status] : '';
            $data[$key]->contact_desired_time = !empty($val->contact_desired_time) ? $val->contact_desired_time : '';
            $data[$key]->acceptance_status = !empty($val->acceptance_status) ? $acceptanceStatusList[$val->acceptance_status] : '';
            $data[$key]->nitoryu_flg = !empty($val->nitoryu_flg) ? trans('demandlist.maru') : trans('demandlist.batu');
            $data[$key]->send_mail_fax = !empty($val->send_mail_fax) ? $sendMailFaxList[$val->send_mail_fax] : '';
            $data[$key]->commit_flg = !empty($val->commit_flg) ? trans('demandlist.maru') : trans('demandlist.batu');
            $data[$key]->commission_type = !empty($val->commission_type) ? trans('demandlist.bulk_quote') : trans('demandlist.normal_commission');
            $data[$key]->appointers = !empty($val->appointers) ? $userList[$val->appointers] : '';
            $data[$key]->first_commission = !empty($val->first_commission) ? trans('demandlist.maru') : trans('demandlist.batu');
            $data[$key]->tel_commission_person = !empty($val->tel_commission_person) ? $userList[$val->tel_commission_person] : '';
            $data[$key]->commission_note_sender = !empty($val->commission_note_sender) ? $userList[$val->commission_note_sender] : '';
            $data[$key]->commission_status = !empty($val->commission_status) ? $commissionStatusList[$val->commission_status] : '';
            $data[$key]->commission_order_fail_reason = !empty($val->commission_order_fail_reason) ? $commissionOrderFailReasonList[$val->commission_order_fail_reason] : '';
            $data[$key]->selection_system = !is_null($val->selection_system) ? $selectionSystemList[$val->selection_system] : '';
        }
        return json_decode(json_encode($data), true);
    }



    /**
     * Counting demand_info by genres
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  integer          $genreId
     * @param  integer          $auctioned
     * @param  integer          $year
     * @param  integer          $month
     * @param  array systemType
     * in database
     * @return Collection
     */
    public function getDemandbyGenreId($genreId, $auctioned = null, $year = null, $month = null, $systemType = [])
    {
        if (!is_array($genreId)) {
            $genreId = [$genreId];
        }
        $builder = $this->model->whereIn('genre_id', $genreId)->whereYear('created', '=', $year);

        // if counting by month
        if ($month) {
            $builder->whereMonth('created', '=', $month);
            // counting by selection_system field
            if (!empty($systemType)) {
                $builder->whereIn('selection_system', $systemType);
            }
        }

        if ($auctioned) {
            $builder->where('auction', '=', $auctioned);
        }
        // counting by address1
        return $builder->select('address1', DB::raw('count(*) as auction_count'))
            ->groupBy('address1')
            ->where('del_flg', 0)
            ->get();
    }

    /**
     * @param integer $demandId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getDemandById($demandId)
    {
        $result = $this->model->findOrFail($demandId);
        return $result;
    }

    /**
     * @param string $sort
     * @param string $direction
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDemandForReport($sort = null, $direction = null)
    {
        $query = $this->model->select('demand_infos.*', 'm_sites.site_name', 'm_categories.category_name')
            ->leftJoin('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->leftJoin('m_categories', 'demand_infos.category_id', '=', 'm_categories.id')
            ->where('m_sites.jbr_flg', 0)
            ->where('demand_infos.auction', 1)
            ->where('demand_infos.del_flg', 0)
            ->where('demand_infos.demand_status', getDivValue('demand_status', 'no_selection'));
        if (!empty($sort) && !empty($direction)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('demand_infos.contact_desired_time', 'asc');
        }
        return $query->paginate(\Config::get('datacustom.report_number_row'));
    }

    /**
     * get jbr commission report
     * @param array $orders
     * @return mixed
     */
    public function getJbrCommissionReport($orders = [])
    {
        return $this->queryForGetJbrCommissionReport($orders)->simplePaginate(config('rits.report_list_limit'));
    }

    /**
     * get total record commission report
     * @param array $orders
     * @return mixed
     */
    public function totalRecordCommissionReport($orders = [])
    {
        return $this->queryForGetJbrCommissionReport($orders)->count();
    }
    /**
     * @end Dung.PhamVan@nashtechglobal.com
     */

    /**
     * get jbr ongoing
     *
     * @param  array $data
     * @return object
     */
    public function getJbrOngoing($data)
    {
        $commissionStatus = [
            getDivValue('construction_status', 'construction'),
            getDivValue('construction_status', 'order_fail')
        ];
        $demandStatus = getDivValue('demand_status', 'information_sent');
        $fiedls = [
            'demand_infos.*',
            'commission_infos.id as commission_infos_id',
            'm_corps.id as m_corps_id',
            'm_corps.corp_name',
            'm_item1.item_name as item_name1',
            'm_item2.item_name as item_name2',
            'm_item3.item_name as item_name3'
        ];
        return $this->model
            ->select($fiedls)
            ->join('m_sites', 'm_sites.id', '=', 'demand_infos.site_id')
            ->join(
                'commission_infos AS commission_infos',
                function ($join) {
                    $join->on('commission_infos.demand_id', '=', 'demand_infos.id')
                        ->where('commission_infos.commit_flg', 1);
                }
            )
            ->join('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id')
            ->join(
                'm_items AS m_item1',
                function ($join) {
                    $join->on(DB::raw('cast(m_item1.item_id as varchar)'), '=', 'demand_infos.jbr_work_contents')
                        ->where('m_item1.item_category', '[JBR様]作業内容');
                }
            )
            ->join(
                'm_items AS m_item2',
                function ($join) {
                    $join->on('m_item2.item_id', '=', 'demand_infos.jbr_estimate_status')
                        ->where('m_item2.item_category', '[JBR様]見積書状況');
                }
            )
            ->join(
                'm_items AS m_item3',
                function ($join) {
                    $join->on('m_item3.item_id', '=', 'demand_infos.jbr_receipt_status')
                        ->where('m_item3.item_category', '[JBR様]領収書状況');
                }
            )
            ->where(
                function ($query) {
                    $query->orWhere('m_sites.jbr_flg', 1);
                    $query->orWhere('m_sites.id', 1314);
                }
            )
            ->where(DB::raw("coalesce(demand_infos.jbr_work_contents, '0')"), '!=', '5')
            ->whereNotIn(DB::raw('coalesce(commission_infos.commission_status, 0)'), $commissionStatus)
            ->where('demand_infos.demand_status', $demandStatus)
            ->where('demand_infos.del_flg', 0)
            ->orderBy($data['orderBy'], $data['sort'])
            ->paginate(config('rits.report_list_limit'));
    }

    /**
     * get auction setting follow
     *
     * @param  array   $data
     * @param  integer $spareTime
     * @return mixed
     */
    public function getAuctionSettingFollow($data, $spareTime)
    {
        $commissionStatus = [
            getDivValue('construction_status', 'construction'),
            getDivValue('construction_status', 'order_fail'),
        ];
        $selectionType = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection'),
        ];
        return $this->model
            ->select(
                'demand_infos.id',
                'demand_infos.customer_name',
                'demand_infos.auction_start_time as follow_tel_date',
                'demand_infos.auction_start_time',
                'demand_infos.site_id',
                'demand_infos.category_id',
                'demand_infos.tel1',
                'demand_infos.address1',
                'm_sites.site_name',
                'm_categories.category_name',
                'm_corps.id as m_corps_id',
                'm_corps.corp_name as m_corps_name',
                'visit_time_view_sort.id as visit_time_view_sort_id',
                'visit_time_view_sort.demand_id',
                'visit_time_view_sort.visit_time',
                'visit_time_view_sort.is_visit_time_range_flg',
                'visit_time_view_sort.visit_time_to',
                'visit_time_view_sort.visit_adjust_time'
            )
            ->join(
                'auction_infos',
                function ($join) {
                    $join->on('demand_infos.id', '=', 'auction_infos.demand_id');
                    $join->whereNotNull('auction_infos.responders');
                }
            )
            ->join('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->leftJoin('m_categories', 'demand_infos.category_id', '=', 'm_categories.id')
            ->leftJoin(
                'commission_infos',
                function ($join) {
                    $join->on('commission_infos.demand_id', '=', 'demand_infos.id');
                    $join->where('commission_infos.commit_flg', 1);
                }
            )
            ->leftJoin('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id')
            ->leftJoin(
                DB::raw(
                    '
                    (select visit_times.id, visit_times.demand_id, (case when COALESCE(is_visit_time_range_flg, 0) = 0 then visit_time else visit_time_from end) as visit_time, visit_times.modified_user_id, visit_times.modified, visit_times.created_user_id, visit_times.created, visit_times.is_visit_time_range_flg, visit_times.visit_time_to, visit_times.visit_adjust_time from visit_times) as visit_time_view_sort'
                ),
                'commission_infos.commission_visit_time_id',
                '=',
                'visit_time_view_sort.id'
            )
            ->whereExists(
                function ($query) use ($commissionStatus) {
                    $query->select('demand_id')
                        ->from('commission_infos')
                        ->join('demand_infos', 'commission_infos.demand_id', '=', 'demand_infos.id')
                        ->where('commission_infos.commit_flg', 1)
                        ->whereNotIn('commission_infos.commission_status', $commissionStatus);
                }
            )
            ->whereIn('demand_infos.selection_system', $selectionType)
            ->where('demand_infos.follow', '!=', 1)
            ->where(
                DB::raw('visit_time_view_sort.visit_time - demand_infos.auction_start_time'),
                '>',
                $spareTime . ' hours'
            )
            ->where('demand_infos.del_flg', 0)
            ->orderBy($data['orderBy'], $data['sort'])
            ->paginate(config('rits.report_list_limit'));
    }

    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @param integer $id
     * @return mixed
     */
    public function getDemandByIdWithRelations($id)
    {
        return $this->model->with('commissionInfos')
            ->with('demandAttachedFiles')
            ->with('mGenres')
            ->with('auctionInfo')
            ->with('demandCorresponds')
            ->with('visitTimes')
            ->with('demandNotification')
            ->whereId($id)->where('del_flg', 0)
            ->first();
    }

    /**
     * Query join for report corp commission
     *
     * @param  $subQueryForHearNum
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function joinQueryGetReportCorpCommission($subQueryForHearNum)
    {
        $query = $this->model->join('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->leftJoin(
                DB::raw("({$subQueryForHearNum->toSql()}) AS demand_commission_info"),
                function ($join) {
                    $join->on('demand_infos.id', '=', 'demand_commission_info.demand_id');
                }
            )
            ->addBinding($subQueryForHearNum->getBindings(), 'join')
            ->leftJoin('commission_infos', 'commission_infos.id', '=', 'demand_commission_info.id')
            ->leftJoin('m_corps', 'm_corps.id', '=', 'commission_infos.corp_id')
            ->leftJoin('affiliation_infos', 'affiliation_infos.corp_id', '=', 'm_corps.id')
            ->leftJoin('m_users', 'm_users.user_id', '=', 'commission_infos.modified_user_id')
            ->leftJoin(
                'm_genres',
                function ($join) {
                    $join->on('m_genres.id', '=', 'demand_infos.genre_id')
                        ->where('m_genres.valid_flg', 1);
                }
            )
            ->leftJoin('m_address1', 'm_address1.address1_cd', '=', DB::raw("lpad(demand_infos.address1, 2, '0')"))
            ->leftJoin('m_users as lock_user', 'lock_user.id', '=', 'demand_infos.lock_user_id')
            ->leftJoin('visit_time_view', 'visit_time_view.demand_id', '=', 'demand_infos.id');

        return $query;
    }

    /**
     * Query join for report corp selection
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function joinQueryGetReportCorpSelection()
    {
        $query = $this->model->leftJoin('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'demand_infos.category_id')
            ->leftJoin(
                'm_genres',
                function ($join) {
                    $join->on('m_genres.id', '=', 'demand_infos.genre_id')
                        ->where('m_genres.valid_flg', 1);
                }
            )
            ->leftJoin('visit_time_view_sort', 'visit_time_view_sort.demand_id', '=', 'demand_infos.id');

        return $query;
    }

    /**
     * @return array
     */
    public function getAllFields()
    {
        return DB::select("select psat.relname as table_name, pa.attname as column_name, pd.description as column_comment, " . " format_type(pa.atttypid, pa.atttypmod) as column_type from pg_stat_all_tables psat, pg_description pd, pg_attribute pa " . "where psat.relname = '" . $this->model->getTable() . "' and psat.relid = pd.objoid and pd.objsubid <> 0 and pd.objoid = pa.attrelid and pd.objsubid = pa.attnum " . "order by pd.objsubid");
    }

    /**
     * Acquire case status for real-time report
     *
     * @param  $subQueryForDemandStatus
     * @return array
     */
    public function findReportDemandStatus($subQueryForDemandStatus)
    {
        // Field settings
        $uncounted = '"demand_infos"."demand_status" IN(1,2,3) ';
        $bidding = '"demand_infos"."demand_status" IN(1,2,3) AND "demand_infos"."selection_system" IN(2,3) ';
        $missingMail = '"demand_infos"."demand_status" = 2 ';
        $followDate = '"demand_infos"."demand_status" IN(2,3) AND ("demand_infos"."follow_date" is not null AND "demand_infos"."follow_date" <> \'\') ';
        $selectionWaiting = '"demand_infos"."demand_status" = 1 AND ("demand_infos"."follow_date" is not null AND "demand_infos"."follow_date" <> \'\') ';
        $todayCommissionNum = '"today_commission_info"."today_commission_flg" = 1 ';
        $todayDemandNum = '"demand_infos"."demand_status" <> 9 AND date_trunc(\'day\', "demand_infos"."created") = current_date ';

        // Table connection
        $query = $this->model->addBinding($subQueryForDemandStatus->getBindings(), 'join')->leftJoin(
            DB::raw("({$subQueryForDemandStatus->toSql()}) as today_commission_info"),
            function ($join) {
                $join->on('today_commission_info.demand_id', '=', 'demand_infos.id')
                    ->where('demand_infos.demand_status', '<>', 9);
            }
        )
            ->where('demand_infos.del_flg', 0)
            ->select(
                // Total aggregate on the day
                DB::raw('SUM(CASE WHEN ' . $todayCommissionNum . ' THEN 1 ELSE 0 END) AS "today_commission_num"'),
                // Registered on the day
                DB::raw('SUM(CASE WHEN ' . $todayDemandNum . ' THEN 1 ELSE 0 END) AS "today_demand_num"'),
                // Uncounted total number
                DB::raw('SUM(CASE WHEN ' . $uncounted . ' THEN 1 ELSE 0 END) AS uncounted'),
                // Bidding in progress
                DB::raw('SUM(CASE WHEN ' . $bidding . ' THEN 1 ELSE 0 END) AS bidding'),
                // Missing e-mail
                DB::raw('SUM(CASE WHEN ' . $missingMail . ' THEN 1 ELSE 0 END) AS missing_mail'),
                // Post-chase day case
                DB::raw('SUM(CASE WHEN ' . $followDate . ' THEN 1 ELSE 0 END) AS follow_date'),
                // Selection waiting for selection
                DB::raw('SUM(CASE WHEN ' . $selectionWaiting . ' THEN 1 ELSE 0 END) AS selection_waiting'),
                // Possible calling cases
                DB::raw('SUM(CASE WHEN ' . $uncounted . ' AND NOT(' . $bidding . ')  AND NOT(' . $missingMail . ')AND NOT(' . $followDate . ') AND NOT(' . $selectionWaiting . ') THEN 1 ELSE 0 END) AS possible_call')
            )
            ->first()
            ->toArray();

        return $query;
    }


    /**
     * Acquire real-time report (inside callable number / required number of hears · number of refusals)
     * ※ Make the same query as the trader's list
     *
     * @param integer $subQueryForHearNum
     * @return array
     */
    public function getRealTimeReportHearLossNum1($subQueryForHearNum)
    {
        $query = $this->model->join('m_sites', 'm_sites.id', '=', 'demand_infos.site_id')
            ->leftJoin(
                DB::raw("({$subQueryForHearNum->toSql()}) demand_commission_info"),
                function ($join) {
                    $join->on('demand_commission_info.demand_id', '=', 'demand_infos.id');
                }
            )
            ->mergeBindings($subQueryForHearNum->getQuery())
            ->leftJoin('commission_infos', 'commission_infos.id', '=', 'demand_commission_info.id')
            ->where('m_sites.jbr_flg', 0)
            ->where(
                function ($where) {
                    $where->where('demand_infos.demand_status', 7)
                        ->orWhere('demand_infos.demand_status', 8)
                        ->orWhere(
                            function ($subWhere) {
                                $subWhere->where(
                                    function ($subWhere1) {
                                        $subWhere1->where('demand_infos.demand_status', 3)
                                            ->whereNotIn('demand_infos.selection_system', [2, 3]);
                                    }
                                )->orWhere(
                                    function ($subWhere2) {
                                        $subWhere2->where('demand_infos.demand_status', 3)
                                            ->whereNull('demand_infos.selection_system');
                                    }
                                );
                            }
                        );
                }
            )
            ->whereIn('commission_infos.corp_id', [1755, 3539])
            ->where('demand_infos.del_flg', 0)
            ->groupBy('demand_infos.id')
            ->select(
                DB::raw('SUM(CASE WHEN "commission_infos"."corp_id" = 1755 THEN 1 ELSE 0 END) AS "CallHearNum"'),
                DB::raw('SUM(CASE WHEN "commission_infos"."corp_id" = 3539 THEN 1 ELSE 0 END) AS "CallLossNum"')
            )->get();

        $data = [
            'CallHearNum1' => $query->sum('CallHearNum'),
            'CallLossNum1' => $query->sum('CallLossNum')
        ];

        return $data;
    }

    /**
     * Get real-time report ((JBR) callable inside / required number of hires / number of refusals)
     * ※ (Mr. JBR case) Make the same query as the previous list
     *
     * @param integer $subQueryForHearNum
     * @return array
     */
    public function getRealTimeReportHearLossNum2($subQueryForHearNum)
    {
        $query = $this->model->join(
            'm_sites',
            function ($join) {
                $join->on('m_sites.id', '=', 'demand_infos.site_id')
                    ->where(
                        function ($where) {
                            $where->where('m_sites.jbr_flg', 1)
                                ->orWhere('m_sites.id', 1314);
                        }
                    );
            }
        )
            ->leftJoin(
                DB::raw("({$subQueryForHearNum->toSql()}) demand_commission_info"),
                function ($join) {
                    $join->on('demand_commission_info.demand_id', '=', 'demand_infos.id');
                }
            )
            ->mergeBindings($subQueryForHearNum->getQuery())
            ->leftJoin('commission_infos', 'commission_infos.id', '=', 'demand_commission_info.id')
            ->leftJoin('m_corps', 'commission_infos.corp_id', '=', 'm_corps.id')
            ->whereIn('demand_infos.demand_status', [1, 2, 3, 4])
            ->where('demand_infos.selection_system', '!=', 2)
            ->where('demand_infos.selection_system', '!=', 3)
            ->whereIn('commission_infos.corp_id', [1755, 3539])
            ->where('demand_infos.del_flg', 0)
            ->groupBy('demand_infos.id')
            ->select(
                DB::raw('SUM(CASE WHEN "commission_infos"."corp_id" = 1755 THEN 1 ELSE 0 END) AS "CallHearNum"'),
                DB::raw('SUM(CASE WHEN "commission_infos"."corp_id" = 3539 THEN 1 ELSE 0 END) AS "CallLossNum"')
            )
            ->get();

        $data = [
            'CallHearNum2' => $query->sum('CallHearNum'),
            'CallLossNum2' => $query->sum('CallLossNum')
        ];

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateOrCreate($data)
    {
        $fields = $this->model->demandFields;
        foreach ($data as $key => $value) {
            if (in_array($key, array_keys($fields)) && $value !== "") {
                $fields[$key] = $value;
            }
        }
        $fields['modified'] = date('Y-m-d H:i:s');
        if ($fields['id'] > 0) {
            unset($fields['created_user_id']);
            unset($fields['created']);
            $this->model->where('id', $fields['id'])->update($fields);
            return $fields;
        }
        $fields['created'] = date('Y-m-d H:i:s');
        return $this->model->create($fields)->toArray();
    }


    /**
     * @param integer $demandId
     * @return int
     */
    public function getLimitoverTime($demandId)
    {
        $demand = $this->model->with('mGenres')->find($demandId);
        if (!in_array($demand->demand_status, [1, 2, 3])) {
            return 0;
        }

        if (is_null($demand->mGenres->commission_limit_time)) {
            return 0;
        }

        $cNow = Carbon::now();
        $cNow2 = Carbon::now();
        $cNow->addMinutes($demand->mGenres->commission_limit_time);
        if ($cNow->lt($cNow2)) {
            $minutes = $cNow2->diffInMinutes($cNow);
            return $minutes;
        }

        return 0;
    }

    /**
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function updateDemandData($id, $data)
    {
        unset($data['commission_type_div']);
        unset($data['contact_desired_time_before']);
        unset($data['priority_before']);
        unset($data['follow_tel_date']);
        unset($data['do_auto_selection']);
        unset($data['do_auction']);
        unset($data['selection_system_before']);
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $data[$key] = (int)$value;
            }
            if ($value == "") {
                $data[$key] = null;
            }
        }
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @return mixed
     */
    public function getMaxIdInsert()
    {
        return DB::table('demand_infos')->max('id');
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return Schema::getColumnListing($this->getTableName());
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->model->getTable();
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return new $this->model;
    }

    /**
     * @param integer $demandId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getMailData($demandId)
    {
        return $this->model->whereHas('mSite')->whereHas('mCategory')->whereHas('mUser')
            ->whereHas('commissionInfoMail')
            ->with('demandAttachedFiles')
            ->where('id', $demandId)
            ->first();
    }

    /**
     * Save data
     * @param mixed $data
     * @return mixed
     */
    public function save($data)
    {
        $demandInfo = $this->model->find($data['id']);
        $demandInfo->id = $data['id'];

        if (isset($data['upload_estimate_file_name']) || isset($data['upload_receipt_file_name'])) {
            $demandInfo->upload_estimate_file_name = isset($data['upload_estimate_file_name']) ? $data['upload_estimate_file_name'] : $demandInfo->upload_estimate_file_name;
            $demandInfo->upload_receipt_file_name = isset($data['upload_receipt_file_name']) ? $data['upload_receipt_file_name'] : $demandInfo->upload_receipt_file_name;
        } else {
            $demandInfo->order_date = $data['order_date'];

            if (isset($data['riro_kureka'])) {
                $demandInfo->riro_kureka = $data['riro_kureka'];
            }

            $demandInfo->jbr_estimate_status = isset($data['jbr_estimate_status']) ? $data['jbr_estimate_status'] : null;
            $demandInfo->jbr_receipt_status = isset($data['jbr_receipt_status']) ? $data['jbr_receipt_status'] : null;
            $demandInfo->jbr_receipt_price = isset($data['jbr_receipt_price']) ? $data['jbr_receipt_price'] : null;
        }

        $userLoginId = \Auth::user()->user_id;

        if (empty($demandInfo->id)) {
            $demandInfo->created = date(config('constant.FullDateTimeFormat'), time());
            $demandInfo->created_user_id = $userLoginId;
        }

        $demandInfo->modified = date(config('constant.FullDateTimeFormat'), time());
        $demandInfo->modified_user_id = $userLoginId;

        $demandInfo->save();

        return $demandInfo;
    }

    /**
     * @param $demandId
     * @return mixed
     */
    public function getGenreCategoryNameByDemand($demandId)
    {
        $query = $this->model->select(
            'm_genres.genre_name as genre_name',
            'm_categories.category_name as category_name',
            'demand_infos.contents as contents'
        )
            ->join('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id')
            ->join('m_categories', 'demand_infos.category_id', '=', 'm_categories.id')
            ->where('demand_infos.id', '=', $demandId)
            ->get()->toArray();
        return $query;
    }

    /**
     * @param $customerTel
     * @return mixed
     */
    public function getCustomerTel($customerTel)
    {
        return $this->model->select('id')
            ->where(function($q) use ($customerTel) {
                $q->orWhere('customer_tel', $customerTel)
                ->orWhere('tel1', $customerTel)
                ->orWhere('tel2', $customerTel);
            })->get();
    }
}
