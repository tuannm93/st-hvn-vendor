<?php

namespace App\Repositories\Eloquent;

use App\Models\CommissionInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseCommissionInfoRepository extends SingleKeyModelRepository
{
    /**
     * @var CommissionInfo
     */
    public $model;

    /**
     * CommissionInfoRepository constructor.
     *
     * @param CommissionInfo $model
     */
    public function __construct(CommissionInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $demandId
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getWordData($demandId, $corpId)
    {
        return $this->model->where('demand_id', $demandId)->where('corp_id', $corpId)
            ->with(
                [
                    'mCorp',
                    'demandInfo',
                    'demandInfo.mSite',
                    'demandInfo.mUser',
                    'demandInfo.inquiries',
                    'demandInfo.inquiries.mInquiry'
                ]
            )->first();
    }

    /**
     * Get sub commission info query for command CheckDeadlinePastAuction
     *
     * @return mixed
     */
    public function subCommissionInfo()
    {
        return $this->model->select('demand_id')->where('commit_flg', 1);
    }

    /**
     * Find commission info by demand id
     *
     * @param integer $demandId
     * @return mixed
     */
    public function findByDemandId($demandId)
    {
        return $this->model->where('demand_id', $demandId)->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function insertCommission($data)
    {
        $insertData = [];
        foreach ($data as $d) {
            $idStaff = null;
            if (isset($d['id_staff'])) {
                $idStaff = $d['id_staff'];
                unset($d['id_staff']);
            }
            $dTemp = $d['position'];
            unset($d['position']);
            $commission = array_merge($this->model->create($d)->toArray(), [
                'position' => $dTemp
            ]);
            if ($idStaff) {
                $commission['id_staff'] = $idStaff;
            }
            $insertData[] = $commission;
        }
        return $insertData;
    }

    /**
     * Get list commission_infos, m_corps, demand_infos, affiliation_stats
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithRelForGroupCategory()
    {
        return $this->model->join(
            "m_corps",
            function ($q) {
                $q->on("commission_infos.corp_id", "=", "m_corps.id");
                $q->where("m_corps.del_flg", 0);
            }
        )->join("demand_infos", "commission_infos.demand_id", "=", "demand_infos.id")
            ->leftJoin(
                "affiliation_stats",
                function ($q) {
                    $q->on("affiliation_stats.corp_id", "=", "commission_infos.corp_id");
                    $q->on("affiliation_stats.genre_id", "=", "demand_infos.genre_id");
                }
            )->where("commission_infos.lost_flg", 0)
            ->where("commission_infos.unit_price_calc_exclude", 0)
            ->groupBy("commission_infos.corp_id", "demand_infos.genre_id")
            ->select(
                "commission_infos.corp_id",
                "demand_infos.genre_id",
                DB::raw("count(*) as commission_count_category"),
                DB::raw("min(affiliation_stats.id) as affiliation_stat_id")
            )
            ->get();
    }

    /**
     * Get list commission_infos, m_corps, demand_infos, affiliation_stats by commission_status
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return mixed
     */
    public function getWithRelForGroupCategoryByComStatus($status)
    {
        return $this->model->join(
            "m_corps",
            function ($q) {
                $q->on("commission_infos.corp_id", "=", "m_corps.id");
                $q->where("m_corps.del_flg", 0);
            }
        )->join("demand_infos", "commission_infos.demand_id", "=", "demand_infos.id")
            ->leftJoin(
                "affiliation_stats",
                function ($q) {
                    $q->on("affiliation_stats.corp_id", "=", "commission_infos.corp_id");
                    $q->on("affiliation_stats.genre_id", "=", "demand_infos.genre_id");
                }
            )->where("commission_infos.commission_status", $status)
            ->where("commission_infos.lost_flg", 0)
            ->where("commission_infos.unit_price_calc_exclude", 0)
            ->groupBy("commission_infos.corp_id", "demand_infos.genre_id")
            ->select(
                "commission_infos.corp_id",
                "demand_infos.genre_id",
                DB::raw("count(*) as orders_count_category"),
                DB::raw("min(affiliation_stats.id) as affiliation_stat_id")
            )
            ->get();
    }

    /**
     * Get list commission_infos, m_corps and count total row
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  boolean $inWeek
     * @return mixed
     */
    public function getWithMCorpAndCountRow($inWeek = false)
    {
        return $this->model->join(
            "m_corps",
            function ($q) {
                $q->on("commission_infos.corp_id", "=", "m_corps.id");
                $q->where("m_corps.del_flg", 0);
            }
        )->where("commission_infos.lost_flg", 0)
            ->where("commission_infos.unit_price_calc_exclude", 0)
            ->where(
                function ($q) use ($inWeek) {
                    if ($inWeek) {
                        $q->where("commission_infos.created", ">=", date('Y/m/d', strtotime('-8 day')));
                        $q->whereRaw(
                            "to_char(commission_infos.created, 'yyyy/mm/dd') <= ?",
                            [date('Y/m/d', strtotime('-1 day'))]
                        );
                    }
                }
            )
            ->groupBy("commission_infos.corp_id")
            ->select(["commission_infos.corp_id", DB::raw("count(*) as total")])
            ->get();
    }

    /**
     * Get list corp_id, total row, avg construction_price_tax_exclude by commission_status
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $status
     * @return mixed
     */
    public function getWithAVGPriceTaxByStatus($status)
    {
        return $this->model->join(
            "m_corps",
            function ($q) {
                $q->on("commission_infos.corp_id", "=", "m_corps.id");
                $q->where("m_corps.del_flg", 0);
            }
        )->where("commission_infos.commission_status", $status)
            ->where("commission_infos.unit_price_calc_exclude", 0)
            ->where("commission_infos.lost_flg", 0)
            ->groupBy("commission_infos.corp_id")
            ->select(
                [
                    "commission_infos.corp_id",
                    DB::raw("count(*) as total"),
                    DB::raw("avg(construction_price_tax_exclude) as construction_price_tax_exclude")
                ]
            )->get();
    }

    /**
     * Get list corp_id, avg corp_fee
     * Use in AffiliationInfoService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithAvgCorpFee()
    {
        return $this->model->join(
            "m_corps",
            function ($q) {
                $q->on("commission_infos.corp_id", "=", "m_corps.id");
                $q->where("m_corps.del_flg", 0);
            }
        )->where("commission_infos.lost_flg", 0)
            ->where("commission_infos.unit_price_calc_exclude", 0)
            ->groupBy("commission_infos.corp_id")
            ->select(
                [
                    "commission_infos.corp_id",
                    DB::raw("avg(corp_fee) as corp_fee")
                ]
            )->get();
    }

    /**
     * Get list commission_infos, m_corps, demand_infos, affiliation_area_stats
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return mixed
     */
    public function getWithRelForGroupCategoryByPrefecture()
    {
        return $this->model->join(
            "m_corps",
            function ($q) {
                $q->on("commission_infos.corp_id", "=", "m_corps.id");
                $q->where("m_corps.del_flg", 0);
            }
        )->join("demand_infos", "commission_infos.demand_id", "=", "demand_infos.id")
            ->leftJoin(
                "affiliation_area_stats",
                function ($q) {
                    $q->on("affiliation_area_stats.corp_id", "=", "commission_infos.corp_id");
                    $q->on("affiliation_area_stats.genre_id", "=", "demand_infos.genre_id");
                    $q->on("affiliation_area_stats.prefecture", "=", "demand_infos.address1");
                }
            )->where("commission_infos.lost_flg", 0)
            ->where("commission_infos.unit_price_calc_exclude", 0)
            ->groupBy("commission_infos.corp_id", "demand_infos.genre_id", "demand_infos.address1")
            ->select(
                "commission_infos.corp_id",
                "demand_infos.genre_id",
                "demand_infos.address1",
                DB::raw("count(*) as commission_count_category"),
                DB::raw("min(affiliation_area_stats.id) as affiliation_area_stat_id")
            )
            ->get();
    }

    /**
     * Find commission registed by demand id, use for Batch 002
     * @param $demandId
     * @return mixed
     */
    public function findCommissionRegistedByDemandId($demandId)
    {
        $query = $this->model->select('commission_infos.*')
            ->join('m_corps', function ($join) {
                $join->on('commission_infos.corp_id', '=', 'm_corps.id')
                    ->where('m_corps.del_flg', 0);
            })->where('commission_infos.demand_id', $demandId)
            ->get();

        return $query;
    }


    /**
     * @param object $commissionInfo
     * @param boolean $support
     */
    public function beforeCreate($commissionInfo, $support = false)
    {
        $userLoginId = Auth::user()->user_id;

        if (empty($commissionInfo->id)) {
            $commissionInfo->created = date(config('constant.FullDateTimeFormat'), time());
            $commissionInfo->created_user_id = $userLoginId;
        }

        if (!$support) {
            $commissionInfo->modified_user_id = $userLoginId;
        }

        $commissionInfo->modified = date(config('constant.FullDateTimeFormat'), time());
    }

    /**
     * @param object $query
     */
    public function selectFields($query)
    {
        return $query->select(
            'DemandInfo.id AS DemandInfo__id',
            'DemandInfo.tel1 AS DemandInfo__tel1',
            'MCorp.official_corp_name AS MCorp__official_corp_name',
            'MCorp.commission_dial AS MCorp__commission_dial',
            'MGenre.genre_name AS MGenre__genre_name',
            'DemandInfo.customer_name AS DemandInfo__customer_name',
            'MItem.item_name AS MItem__item_name',
            'CommissionSupport.support_kind AS CommissionSupport__support_kind',
            'CommissionSupport.correspond_status AS CommissionSupport__correspond_status',
            'CommissionSupport.correspond_datetime AS CommissionSupport__correspond_datetime',
            'CommissionSupport.order_fail_reason AS CommissionSupport__order_fail_reason',
            'CommissionSupport.modified AS CommissionSupport__modified',
            'CommissionInfo.id AS CommissionInfo__id',
            'CommissionInfo.re_commission_exclusion_status AS CommissionInfo__re_commission_exclusion_status',
            'MGenre.exclusion_flg AS MGenre__exclusion_flg'
        );
    }

    /**
     * @param array $lastStepStatusList
     * @param object $where
     */
    public function buildWhereCondition($lastStepStatusList, &$where)
    {
        foreach ($lastStepStatusList as $status) {
            switch ($status) {
                case 8:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'tel')
                                ->where('CommissionSupport.correspond_status', 9);
                        }
                    );
                    break;
                case 9:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'tel')
                                ->where('CommissionSupport.correspond_status', 10);
                        }
                    );
                    break;
                case 3:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'tel')
                                ->where('CommissionSupport.correspond_status', 7);
                        }
                    );
                    break;
                case 10:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'visit')
                                ->where('CommissionSupport.correspond_status', 9);
                        }
                    );
                    break;
                case 11:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'visit')
                                ->where('CommissionSupport.correspond_status', 10);
                        }
                    );
                    break;
                case 6:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'visit')
                                ->where('CommissionSupport.correspond_status', 7);
                        }
                    );
                    break;
                case 7:
                    $where->orWhere(
                        function ($andWhere) {
                            $andWhere->where('CommissionSupport.support_kind', 'order')
                                ->where('CommissionSupport.correspond_status', 4);
                        }
                    );
                    break;
            }
        }
    }
}
