<?php

namespace App\Repositories\Eloquent;

use App\Models\CommissionInfo;
use App\Models\DemandInfo;
use App\Repositories\CommissionInfoRepositoryInterface;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CommissionInfoRepository extends BaseCommissionInfoRepository implements CommissionInfoRepositoryInterface
{
    /**
     * CommissionInfoRepository constructor.
     *
     * @param CommissionInfo $model
     */
    public function __construct(CommissionInfo $model)
    {
        parent::__construct($model);
    }

    /**
     * check credit sum price
     * @param null $corpId
     * @return mixed
     */
    public function checkCreditSumPrice($corpId = null)
    {
        $query = $this->model->select(
            [
                'corp_id',
                DB::raw('SUM(CASE WHEN commission_infos.commission_type = 0 THEN m_genres.credit_unit_price ' . 'WHEN commission_infos.commission_type = 1 AND commission_infos.introduction_not = 0 THEN m_genres.credit_unit_price ' . 'END)as sum_credit'),
            ]
        )->join(
            'm_corps',
            function ($join) {
                /**
                 * @var JoinClause $join
                 */
                $join->on('commission_infos.corp_id', '=', 'm_corps.id')->where('m_corps.del_flg', '=', 0);
            }
        )->join(
            'demand_infos',
            function ($join) {
                /**
                 * @var JoinClause $join
                 */
                $join->on('commission_infos.demand_id', '=', 'demand_infos.id')
                    ->where('commission_infos.del_flg', '=', 0)
                    ->where('demand_infos.site_id', '<>', Config::get('rits.CREDIT_EXCLUSION_SITE_ID'));
            }
        )->join('m_genres', 'm_genres.id', '=', 'demand_infos.genre_id')
            ->where('commission_infos.corp_id', '=', $corpId)
            ->where('commission_infos.del_flg', '=', 0)
            ->where('commission_infos.lost_flg', '=', 0)
            ->where('commission_infos.commission_status', '<>', 2)
            ->where(DB::raw('TO_CHAR(commission_infos.commission_note_send_datetime, \'YYYY/MM\')'), '=', date('Y/m'))
            ->groupBy('commission_infos.corp_id');

        $commissionInfo = $query->get()->toArray();

        return $commissionInfo;
    }

    /**
     * get commission info by demand id
     * @param integer $demandId
     * @param boolean $isCorpFields
     * @param integer $commitFlg
     * @return array
     */
    public function getListByDemandId($demandId, $isCorpFields = false, $commitFlg = 1)
    {
        $model = $this->model->where('demand_id', $demandId)
            ->where('commit_flg', $commitFlg);

        if ($isCorpFields) {
            return $model->with('mCorp')->get();
        }
        return $model->pluck('corp_id')->toArray();
    }

    /**
     * [getCommissionInfoWithRelationById description]
     *
     * @author thaihv <[<email address>]>
     * @param  integer $id commission info id
     * @return mixed     model commission info
     */
    public function getWithRelationById($id)
    {
        return $this->model->with(
            [
                'mCorp' => function ($q) {
                    $q->where('del_flg', 0)->select(
                        'id',
                        'corp_name',
                        'official_corp_name',
                        'fax',
                        'prog_send_method',
                        'prog_send_mail_address',
                        'prog_send_fax',
                        'prog_irregular',
                        'commission_dial',
                        'mailaddress_pc',
                        'bill_send_method'
                    );
                },
                'mCorp.affiliationInfos',
                'demandInfo' => function ($q) {
                    $q->where('del_flg', '!=', 1)
                        ->select(
                            'id',
                            'category_id',
                            'genre_id',
                            'receive_datetime',
                            'customer_name',
                            'customer_tel',
                            'customer_mailaddress',
                            'tel1'
                        );
                },
                'demandInfo.mCategory',
                'demandInfo.mGenres',
                'billInfos' => function ($q) {
                    $q->join('demand_infos', 'bill_infos.demand_id', '=', 'demand_infos.id')->whereNull('auction_id');
                },
                'mItem' => function ($q) {
                    $q->where('item_category', '取次状況');
                },
            ]
        )->select(
            'id',
            'corp_id',
            'commission_status',
            'demand_id',
            'commission_status',
            'commission_order_fail_reason',
            'complete_date',
            'order_fail_date',
            'construction_price_tax_exclude',
            'construction_price_tax_include',
            'report_note',
            'order_fee_unit',
            'irregular_fee',
            'commission_fee_rate',
            'irregular_fee_rate',
            'corp_fee'
        )->find($id);
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $demandId
     * @param array $commInfoCols
     * @param array $corpCols
     * @return mixed
     */
    public function getCommInfoWithCorpByDemandId($demandId, $commInfoCols = ["*"], $corpCols = ["*"])
    {
        return $this->model->with([
            'mCorp' => function ($q) use ($corpCols) {
                $q->where('del_flg', 0)->select($corpCols);
            },
        ])->where('demand_id', $demandId)->select($commInfoCols)->get();
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getCommInfoForExportWordById($id)
    {
        return $this->model->with(
            [
                'demandInfo' => function ($q) {
                    $q->with(['mSite', 'mUser']);
                },
                'mCorp',
            ]
        )->find($id);
    }

    /**
     * @param \App\Models\Base $data
     * @return \App\Models\Base|CommissionInfo|bool|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function save($data)
    {
        if (isset($data['id'])) {
            $commissionInfo = $this->model->where('id', $data['id'])->first();
        } else {
            $commissionInfo = $this->getBlankModel();
        }

        $support = isset($data['support']) ? $data['support'] : false;
        unset($data['support']);

        foreach ($data as $field => $value) {
            $value = $this->convertNullToEmpty($field, $value);
            $commissionInfo->$field = $value;
        }

        if (empty($commissionInfo->ac_commission_exclusion_flg)) {
            $commissionInfo->ac_commission_exclusion_flg = false;
        }

        $this->beforeCreate($commissionInfo, $support);
        $commissionInfo->save();

        return $commissionInfo;
    }

    /**
     * Return model of repository
     *
     * @return \App\Models\Base|CommissionInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new CommissionInfo();
    }

    /**
     * @param array $ids
     * @param null $corpIds
     * @return mixed
     */
    public function getListByIds($ids, $corpIds = null)
    {
        $query = $this->model->select(
            'commission_infos.id as commission_infos_id',
            'demand_infos.id as demand_infos_id',
            'commission_infos.commission_status as commission_status',
            'demand_infos.receive_datetime as receive_datetime',
            'demand_infos.customer_name as customer_name',
            'm_corps.id as corp_id',
            'm_corps.corp_name as corp_name',
            'm_corps.official_corp_name as official_corp_name',
            'affiliation_infos.construction_unit_price',
            'demand_infos.customer_tel',
            'demand_infos.customer_mailaddress',
            'm_categories.category_name',
            'commission_infos.commission_status',
            'commission_infos.commission_order_fail_reason',
            'commission_infos.complete_date',
            'commission_infos.order_fail_date',
            'commission_infos.construction_price_tax_exclude',
            'commission_infos.construction_price_tax_include',
            'm_corps.fax',
            'm_corps.prog_send_method',
            'm_corps.prog_send_mail_address',
            'm_corps.prog_send_fax',
            'm_corps.prog_irregular',
            'm_corps.commission_dial',
            'commission_infos.report_note',
            'm_corps.mailaddress_pc',
            'm_corps.bill_send_method',
            'bill_infos.fee_billing_date',
            'commission_infos.order_fee_unit',
            'commission_infos.irregular_fee',
            'commission_infos.irregular_fee_rate',
            'commission_infos.commission_fee_rate',
            'commission_infos.corp_fee',
            'bill_infos.fee_target_price',
            'm_genres.genre_name',
            'demand_infos.tel1'
        )
            ->join('m_corps', function ($join) {
                $join->on('commission_infos.corp_id', '=', 'm_corps.id');
                $join->where('m_corps.del_flg', 0);
            })
            ->join('demand_infos', function ($join) {
                $join->on('demand_infos.id', '=', 'commission_infos.demand_id');
                $join->where('demand_infos.del_flg', '!=', 1);
            })
            ->leftJoin('m_categories', 'm_categories.id', '=', 'demand_infos.category_id')
            ->leftJoin('m_items', function ($join) {
                $join->on('m_items.item_id', '=', 'commission_infos.commission_status');
                $join->where('m_items.item_category', '=', MItemRepository::COMMISSION_STATUS);
            })
            ->leftJoin('affiliation_infos', 'affiliation_infos.corp_id', '=', 'm_corps.id')
            ->leftJoin('bill_infos', function ($join) {
                $join->on('bill_infos.demand_id', '=', 'demand_infos.id');
                $join->on('bill_infos.commission_id', '=', 'commission_infos.id');
                $join->whereNull('bill_infos.auction_id');
            })
            ->leftJoin('m_corp_categories', function ($join) {
                $join->on('m_corp_categories.corp_id', '=', 'commission_infos.corp_id');
                $join->on('m_corp_categories.category_id', '=', 'demand_infos.category_id');
            })
            ->leftJoin('m_genres', 'm_genres.id', '=', 'demand_infos.genre_id');
        if (isset($corpIds)) {
            $query->whereIn('commission_infos.corp_id', $corpIds)->whereIn('commission_infos.id', $ids);
        } else {
            $query->whereIn('commission_infos.id', $ids);
        }
        $result = $query->get();
        return $result;
    }

    /**
     * get list jbr receipt follow
     * @param null $followDateFrom
     * @param null $followDateTo
     * @param bool $isGetAll
     * @return mixed
     */
    public function getListJbrReceiptFollow($followDateFrom = null, $followDateTo = null, $isGetAll = true)
    {
        $conditionJbrStatus = [2, 3];
        $query = $this->model->join(
            'demand_infos',
            function ($join) {
                $join->on('commission_infos.demand_id', '=', 'demand_infos.id');
            }
        )->join(
            'm_corps',
            function ($join) {
                $join->on('m_corps.id', '=', 'commission_infos.corp_id');
                $join->where('m_corps.del_flg', '=', 0);
            }
        )->join(
            'm_sites',
            function ($join) {
                $join->on('m_sites.id', '=', 'demand_infos.site_id');
            }
        )->join(
            'm_genres',
            function ($join) {
                $join->on('m_genres.id', '=', 'demand_infos.genre_id');
            }
        )->leftjoin(
            'm_items as MItem',
            function ($join) {
                $join->on('MItem.item_id', '=', 'demand_infos.jbr_estimate_status');
                $join->where('MItem.item_category', '=', trans('report_jbr.JBR_ESTIMATE_STATUS'));
            }
        )->leftjoin(
            'm_items as MItem2',
            function ($join) {
                $join->on('MItem2.item_id', '=', 'demand_infos.jbr_receipt_status');
                $join->where('MItem2.item_category', '=', trans('report_jbr.JBR_RECEIPT_STATUS'));
            }
        )
            ->where(function ($query) {
                $query->where('m_sites.jbr_flg', 1)
                    ->orWhere('m_sites.id', 1314);
            })
            ->where('demand_infos.del_flg', 0)
            ->where('demand_infos.genre_id', '!=', 679)
            ->where('commission_infos.commission_status', 3)
            ->where(function ($sqlQuery) use ($conditionJbrStatus) {
                $sqlQuery->where(function ($query1) use ($conditionJbrStatus) {
                    $query1->where('demand_infos.genre_id', 676)
                        ->whereNotIn(DB::raw('COALESCE(demand_infos.jbr_estimate_status, 0)'), $conditionJbrStatus);
                })->orWhere(function ($query1) use ($conditionJbrStatus) {
                    $query1->where('demand_infos.genre_id', 676)
                        ->where('demand_infos.jbr_estimate_status', 2)
                        ->whereNotIn(DB::raw('COALESCE(demand_infos.jbr_receipt_status, 0)'), $conditionJbrStatus);
                })->orWhere(function ($query1) use ($conditionJbrStatus) {
                    $query1->where('demand_infos.genre_id', 676)
                        ->where('demand_infos.jbr_estimate_status', 3)
                        ->whereNotIn(DB::raw('COALESCE(demand_infos.jbr_receipt_status, 0)'), $conditionJbrStatus);
                })->orWhere(function ($query1) use ($conditionJbrStatus) {
                    $query1->where('demand_infos.genre_id', '!=', 676)
                        ->whereNotIn(DB::raw('COALESCE(demand_infos.jbr_receipt_status, 0)'), $conditionJbrStatus);
                });
            });

        if ($followDateFrom) {
            $query->where('commission_infos.follow_date', '>=', $followDateFrom);
        }

        if ($followDateTo) {
            $query->where('commission_infos.follow_date', '<=', $followDateTo);
        }
        if ($isGetAll) {
            $result = $query->select(
                'demand_infos.id as demand_id',
                'commission_infos.id as commission_id',
                'MItem.item_name as MItem_item_name',
                'MItem2.item_name as MItem2_item_name',
                'm_corps.id as m_corps_id',
                'm_corps.official_corp_name as official_corp_name',
                'm_genres.id as m_genres_id',
                'm_genres.genre_name',
                'demand_infos.jbr_order_no',
                'demand_infos.customer_name',
                'demand_infos.genre_id',
                'demand_infos.jbr_estimate_status',
                'demand_infos.jbr_receipt_status',
                'commission_infos.complete_date',
                'commission_infos.construction_price_tax_include'
            );
        } else {
            $result = $query->select('m_corps.id');
        }
        return $result;
    }

    /**
     * @param integer $id
     * @return array|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getCommissionInfoById($id)
    {
        $query = $this->model->from('commission_infos AS CommissionInfo')
            ->join(
                'm_corps AS MCorp',
                function ($join) {
                    $join->on('MCorp.id', '=', 'CommissionInfo.corp_id')
                        ->where('MCorp.del_flg', '=', 0);
                }
            )
            ->join(
                'demand_infos AS DemandInfo',
                function ($join) {
                    $join->on('DemandInfo.id', '=', 'CommissionInfo.demand_id');
                }
            )
            ->leftjoin(
                'auction_infos AS AuctionInfo',
                function ($join) {
                    $join->on('AuctionInfo.demand_id', '=', 'DemandInfo.id')
                        ->whereRaw('"AuctionInfo"."corp_id" = "CommissionInfo"."corp_id"');
                }
            )
            ->join(
                'm_genres AS MGenre',
                function ($join) {
                    $join->on('MGenre.id', '=', 'DemandInfo.genre_id');
                }
            )
            ->leftjoin(
                'bill_infos AS BillInfo',
                function ($join) {
                    $join->on('BillInfo.demand_id', '=', 'CommissionInfo.demand_id')
                        ->whereRaw('"BillInfo"."commission_id" = "CommissionInfo"."id"')
                        ->whereNull('BillInfo.auction_id');
                }
            )
            ->leftjoin(
                'm_corp_categories AS MCorpCategory',
                function ($join) {
                    $join->on('MCorpCategory.corp_id', '=', 'MCorp.id')
                        ->whereRaw('"MCorpCategory"."category_id" = "DemandInfo"."category_id"');
                }
            )
            ->join(
                'affiliation_infos AS AffiliationInfo',
                function ($join) {
                    $join->on('AffiliationInfo.corp_id', '=', 'CommissionInfo.corp_id');
                }
            )
            ->where('CommissionInfo.id', $id);

        if (Auth::user()->auth == 'affiliation') {
            $query->where('CommissionInfo.corp_id', '=', Auth::user()->affiliation_id);
        }

        $commissionInfoFields = $this->getAllTableFieldsByAlias('commission_infos', 'CommissionInfo');
        $demandInfoFields = $this->getAllTableFieldsByAlias('demand_infos', 'DemandInfo');

        $query->select($commissionInfoFields)
            ->addSelect($demandInfoFields)
            ->addSelect(
                'BillInfo.id AS BillInfo__id',
                'BillInfo.irregular_fee_rate AS BillInfo__irregular_fee_rate',
                'BillInfo.irregular_fee AS BillInfo__irregular_fee',
                'BillInfo.bill_status AS BillInfo__bill_status',
                'BillInfo.fee_target_price AS BillInfo__fee_target_price',
                'BillInfo.fee_tax_exclude AS BillInfo__fee_tax_exclude',
                'BillInfo.fee_billing_date AS BillInfo__fee_billing_date',
                'BillInfo.total_bill_price AS BillInfo__total_bill_price',
                'BillInfo.tax AS BillInfo__tax',
                'BillInfo.insurance_price AS BillInfo__insurance_price',
                'MCorp.id AS MCorp__id',
                'MCorp.corp_name AS MCorp__corp_name',
                'MCorp.official_corp_name AS MCorp__official_corp_name',
                'MCorp.auction_masking AS MCorp__auction_masking',
                'MCorp.commission_dial AS MCorp__commission_dial',
                'MCorp.progress_check_tel AS MCorp__progress_check_tel',
                'MCorpCategory.order_fee AS MCorpCategory__order_fee',
                'MCorpCategory.order_fee_unit AS MCorpCategory__order_fee_unit',
                'MCorpCategory.introduce_fee AS MCorpCategory__introduce_fee',
                'MCorpCategory.corp_commission_type AS MCorpCategory__corp_commission_type',
                'MCorpCategory.note AS MCorpCategory__note',
                'AffiliationInfo.liability_insurance AS AffiliationInfo__liability_insurance',
                'MGenre.insurant_flg AS MGenre__insurant_flg',
                'MGenre.genre_name AS MGenre__genre_name',
                'AuctionInfo.id AS AuctionInfo__id',
                'AuctionInfo.visit_time_id AS AuctionInfo__visit_time_id',
                'AuctionInfo.responders AS AuctionInfo__responders'
            );

        $result = $query->first();

        if ($result) {
            $result = $result->toArray();
        }

        return $result;
    }

    /**
     * @param integer $demandId
     * @param integer $id
     * @return array
     */
    public function getByDemandId($demandId, $id)
    {
        return $this->model->from('commission_infos AS CommissionInfo')
            ->where('CommissionInfo.demand_id', $demandId)
            ->where('CommissionInfo.id', '!=', $id)
            ->get()->toArray();
    }

    /**
     * @return array
     */
    public function getAllFields()
    {
        return DB::select(
            "select psat.relname as table_name, pa.attname as column_name, pd.description as column_comment, "
            . " format_type(pa.atttypid, pa.atttypmod) as column_type from pg_stat_all_tables psat, pg_description pd, pg_attribute pa "
            . "where psat.relname = '" . $this->model->getTable() . "' and psat.relid = pd.objoid and pd.objsubid <> 0 and pd.objoid = pa.attrelid and pd.objsubid = pa.attnum "
            . "order by pd.objsubid"
        );
    }

    /**
     * @return mixed
     */
    public function subQueryForDemandStatus()
    {
        $query = $this->model->select('demand_id', DB::raw("1 as \"today_commission_flg\""))
            ->where(
                'commission_note_send_datetime',
                '>=',
                DB::raw("to_timestamp(to_char(current_date, 'yyyy-mm-dd') || ' 09:00:00', 'yyyy-mm-dd hh24:mi:ss')")
            )
            ->where(
                'commission_note_send_datetime',
                '<',
                DB::raw("to_timestamp(to_char(current_date, 'yyyy-mm-dd') || ' 22:00:00', 'yyyy-mm-dd hh24:mi:ss')")
            )
            ->where('del_flg', 0)
            ->groupBy('demand_id');

        return $query;
    }

    /**
     * @return mixed
     */
    public function subQueryForHearNum()
    {
        $query = $this->model->select('demand_id', DB::raw('min(id) as id'))
            ->where('lost_flg', 0)
            ->where('del_flg', 0)
            ->where('introduction_not', 0)
            ->groupBy('demand_id');

        return $query;
    }

    /**
     * @param $commissionId
     * @return mixed|static
     */
    public function findById($commissionId)
    {
        return $this->model->find($commissionId);
    }

    /**
     * @param \App\Models\Base $id
     * @param array $data
     * @return \App\Models\Base|bool
     */
    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param integer $id
     * @return array|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findCommissionInfo($id)
    {
        $fields = $this->getAllTableFieldsByAlias('commission_infos', 'CommissionInfo');
        $moreFields = [
            'DemandInfo.id AS DemandInfo__id',
            'DemandInfo.category_id AS DemandInfo__category_id',
            'MGenre.insurant_flg AS MGenre__insurant_flg',
            'BillInfo.id AS BillInfo__id',
            'BillInfo.irregular_fee_rate AS BillInfo__irregular_fee_rate',
            'BillInfo.irregular_fee AS BillInfo__irregular_fee',
            'BillInfo.fee_target_price AS BillInfo__fee_target_price',
            'BillInfo.fee_tax_exclude AS BillInfo__fee_tax_exclude',
            'BillInfo.total_bill_price AS BillInfo__total_bill_price',
            'BillInfo.tax AS BillInfo__tax',
            'BillInfo.insurance_price AS BillInfo__insurance_price',
            'MCorpCategory.id AS MCorpCategory__id',
            'MCorpCategory.order_fee AS MCorpCategory__order_fee',
            'MCorpCategory.order_fee_unit AS MCorpCategory__order_fee_unit',
            'AffiliationInfo.liability_insurance AS AffiliationInfo__liability_insurance'
        ];

        $result = $this->model->from('commission_infos AS CommissionInfo')
            ->join(
                'demand_infos as DemandInfo',
                function ($join) {
                    $join->on('DemandInfo.id', '=', 'CommissionInfo.demand_id');
                }
            )
            ->join(
                'm_genres as MGenre',
                function ($join) {
                    $join->on('MGenre.id', '=', 'DemandInfo.genre_id');
                }
            )
            ->join(
                'm_corps AS MCorp',
                function ($join) {
                    $join->on('CommissionInfo.corp_id', '=', 'MCorp.id');
                }
            )
            ->leftjoin(
                'bill_infos as BillInfo',
                function ($join) {
                    $join->on('BillInfo.demand_id', '=', 'CommissionInfo.demand_id')
                        ->whereRaw('"BillInfo"."commission_id" = "CommissionInfo"."id"')
                        ->whereNull('BillInfo.auction_id');
                }
            )
            ->leftjoin(
                'm_corp_categories as MCorpCategory',
                function ($join) {
                    $join->on('MCorpCategory.corp_id', '=', 'MCorp.id')
                        ->whereRaw('"MCorpCategory"."category_id" = "DemandInfo"."category_id"');
                }
            )
            ->join(
                'affiliation_infos as AffiliationInfo',
                function ($join) {
                    $join->on('AffiliationInfo.corp_id', '=', 'CommissionInfo.corp_id');
                }
            )
            ->where('CommissionInfo.id', $id)
            ->select($fields)
            ->addSelect($moreFields)
            ->first();

        if ($result) {
            $result = $result->toArray();
        }

        return $result;
    }


    /**
     * Get list commission info by conditions with orderBy and limit.
     *
     * @param array $conditions
     * @param array $orderBy
     * @param integer $limit
     * @return mixed
     */
    public function searchCommissionInfo($conditions, $orderBy, $limit)
    {
        $whereConditions = $conditions['where'];
        $whereInConditions = $conditions['whereIn'];
        $whereRawConditions = $conditions['whereRaw'];
        $whereOrConditions = $conditions['whereOr'];
        $searchFields = $this->getColumnInCommissionSearch();
        $query = $this->model
            ->select($searchFields)
            ->join(
                'm_corps',
                function ($joins) {
                    $joins->on('m_corps.id', '=', 'commission_infos.corp_id');
                    $joins->where('m_corps.del_flg', '=', 0);
                }
            )
            ->join(
                'demand_infos',
                function ($joins) {
                    $joins->on('demand_infos.id', '=', 'commission_infos.demand_id');
                    $joins->where('demand_infos.del_flg', '!=', 1);
                }
            )
            ->leftJoin('m_sites', 'm_sites.id', '=', 'demand_infos.site_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'demand_infos.category_id')
            ->leftJoin(
                'm_items',
                function ($joins) {
                    $joins->on('m_items.item_id', '=', 'commission_infos.commission_status');
                    $joins->where('m_items.item_category', '=', __('demandlist.commission_status'));
                }
            )
            ->leftJoin(
                DB::raw("(select distinct demand_id from demand_attached_files) AS demand_attached_files"),
                'demand_attached_files.demand_id',
                '=',
                'demand_infos.id'
            )
            ->leftJoin('visit_time_view', 'visit_time_view.id', '=', 'commission_infos.commission_visit_time_id')
            ->where($whereConditions)
            ->where(
                function ($query) use ($whereInConditions) {
                    foreach ($whereInConditions as $condition) {
                        $query->whereIn($condition[0], $condition[2]);
                    }
                }
            )
            ->where(
                function ($query) use ($whereRawConditions) {
                    foreach ($whereRawConditions as $condition) {
                        $query->whereRaw($condition[0], $condition[1]);
                    }
                }
            )
            ->where(
                function ($query) use ($whereOrConditions) {
                    foreach ($whereOrConditions as $key => $conditions) {
                        $query->where(
                            function ($query) use ($conditions) {
                                foreach ($conditions as $key2 => $condition) {
                                    if ($key2 == 0) {
                                        $query->where($condition[0], $condition[1], $condition[2]);
                                    } else {
                                        $query->orWhere($condition[0], $condition[1], $condition[2]);
                                    }
                                }
                            }
                        );
                    }
                }
            );

        if (count($orderBy) > 0) {
            foreach ($orderBy as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }
        return $query->paginate($limit);
    }

    /**
     * @return array
     */
    public function getColumnInCommissionSearch()
    {
        $fields = $this->getAllTableFields('commission_infos');
        $addColumn = [
            'demand_attached_files.demand_id',
            'demand_infos.id',
            'demand_infos.customer_name',
            'demand_infos.customer_corp_name',
            'demand_infos.category_id',
            'demand_infos.site_id',
            'demand_infos.address1',
            'demand_infos.tel1',
            'demand_infos.tel2',
            'demand_infos.selection_system',
            'demand_infos.contact_desired_time',
            'demand_infos.contact_desired_time_from',
            'demand_infos.contact_desired_time_to',
            'demand_infos.selection_system',
            'demand_infos.receive_datetime',
            'demand_infos.priority',
            'demand_infos.is_contact_time_range_flg',
            'm_sites.site_name',
            'm_sites.site_url',
            'm_categories.category_name',
            'm_items.item_name',
            'm_corps.corp_name',
            'm_corps.auction_masking',
            'visit_time_view.id',
            'visit_time_view.visit_time',
            'visit_time_view.visit_time_to',
            'visit_time_view.visit_adjust_time',
            'visit_time_view.is_visit_time_range_flg'
        ];
        $virtualFields = [
            DB::raw('(case when (commission_infos.tel_support + commission_infos.visit_support + commission_infos.order_support > 0) and commission_infos.commission_status in (1,2) then 1 else 0 end) as status'),
        ];

        foreach ($addColumn as $column) {
            $field = explode(".", $column);
            $table = $field[0];
            $columnName = $field[1];
            $fields[] = "$table." . $columnName . " AS $table" . '_' . $columnName;
        }
        foreach ($virtualFields as $field) {
            $fields[] = $field;
        }
        return $fields;
    }

    /**
     * Get list data to export csv by conditions
     *
     * @param array $conditions
     * @return mixed
     */
    public function getListCommissionExportCSV($conditions)
    {
        $whereConditions = $conditions['where'];
        $whereInConditions = $conditions['whereIn'];
        $whereOrConditions = $conditions['whereOr'];
        $whereRawConditions = $conditions['whereRaw'];
        $orderBy = [
            'demand_infos.id' => 'desc',
        ];
        $csvFields = $this->getColumnInCsvExport();
        $query = DemandInfo::select($csvFields)
            ->leftJoin('m_sites', 'm_sites.id', '=', 'demand_infos.site_id')
            ->leftJoin('m_genres', 'm_genres.id', '=', 'demand_infos.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'demand_infos.category_id')
            ->leftJoin(
                'commission_infos',
                function ($joins) {
                    $joins->on('demand_infos.id', '=', 'commission_infos.demand_id');
                    $joins->where(
                        function ($query) {
                            $query->where('commission_infos.commit_flg', '=', 1);
                            $query->orWhere('commission_infos.commission_type', '=', 1);
                        }
                    );
                }
            )
            ->leftJoin('m_corps', 'm_corps.id', '=', 'commission_infos.corp_id')
            ->where($whereConditions)
            ->where(
                function ($query) use ($whereInConditions) {
                    foreach ($whereInConditions as $condition) {
                        $query->whereIn($condition[0], $condition[2]);
                    }
                }
            )
            ->where(
                function ($query) use ($whereRawConditions) {
                    foreach ($whereRawConditions as $condition) {
                        $query->whereRaw($condition[0], $condition[1]);
                    }
                }
            )
            ->where(
                function ($query) use ($whereOrConditions) {
                    foreach ($whereOrConditions as $key => $conditions) {
                        $query->where(
                            function ($query) use ($conditions) {
                                foreach ($conditions as $key2 => $condition) {
                                    if ($key2 == 0) {
                                        $query->where($condition[0], $condition[1], $condition[2]);
                                    } else {
                                        $query->orWhere($condition[0], $condition[1], $condition[2]);
                                    }
                                }
                            }
                        );
                    }
                }
            );
        foreach ($orderBy as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query->get();
    }

    /**
     * @return array
     */
    public function getColumnInCsvExport()
    {
        $fields = $this->model::csvFieldList();
        $virtualFields = [
            DB::raw('(CASE WHEN demand_infos.is_contact_time_range_flg = 1 THEN demand_infos.contact_desired_time_from ELSE demand_infos.contact_desired_time END) as demand_infos_detect_contact_desired_time'),
        ];
        foreach ($virtualFields as $field) {
            $fields[] = $field;
        }
        return $fields;
    }

    /**
     * @param array $params
     * @param array $sortPrarams
     * @return array
     */
    public function getSalesSupport($params = [], $sortPrarams = [])
    {
        $query = $this->model->from('commission_infos AS CommissionInfo')
            ->join(
                'm_corps AS MCorp',
                function ($join) {
                    $join->on('CommissionInfo.corp_id', '=', 'MCorp.id')
                        ->where('MCorp.del_flg', 0);
                }
            )
            ->leftjoin(
                'demand_infos AS DemandInfo',
                function ($join) {
                    $join->on('CommissionInfo.demand_id', '=', 'DemandInfo.id')
                        ->where('DemandInfo.del_flg', 0);
                }
            )
            ->join(
                'm_genres AS MGenre',
                function ($join) {
                    $join->on('DemandInfo.genre_id', '=', 'MGenre.id');
                }
            )
            ->join(
                'rits_commission_supports AS CommissionSupport',
                function ($join) {
                    $join->on('CommissionInfo.id', '=', 'CommissionSupport.commission_id')
                        ->where(
                            function ($orWhere) {
                                $orWhere->orWhere(
                                    function ($andWhere) {
                                        $andWhere->where('CommissionSupport.support_kind', 'tel')
                                            ->whereIn('CommissionSupport.correspond_status', [3, 4, 7, 8, 9, 10]);
                                    }
                                )
                                    ->orWhere(
                                        function ($andWhere) {
                                            $andWhere->where('CommissionSupport.support_kind', 'visit')
                                                ->whereIn('CommissionSupport.correspond_status', [3, 4, 7, 8, 9, 10]);
                                        }
                                    )
                                    ->orWhere(
                                        function ($andWhere) {
                                            $andWhere->where('CommissionSupport.support_kind', 'order')
                                                ->whereIn('CommissionSupport.correspond_status', [4, 5]);
                                        }
                                    );
                            }
                        );
                }
            )
            ->leftjoin(
                'm_items AS MItem',
                function ($join) {
                    $join->on('MItem.item_id', '=', 'CommissionInfo.commission_status')
                        ->where('MItem.item_category', __('report_sales_support.commission_status'));
                }
            )
            ->whereIn('CommissionInfo.commission_status', [1, 2, 4])
            ->where('CommissionInfo.del_flg', 0)
            ->where('CommissionInfo.lost_flg', 0)
            ->where('CommissionInfo.re_commission_exclusion_status', 0)
            ->where('MGenre.exclusion_flg', 0);

        if (isset($params['genre_id']) && is_array($params['genre_id'])) {
            $query->whereIn('DemandInfo.genre_id', $params['genre_id']);
        }

        if (isset($params['support_kind']) && $params['support_kind'] != 'none') {
            $query->where('CommissionSupport.support_kind', $params['support_kind']);
        }

        if (isset($params['last_step_status']) && is_array($params['last_step_status'])) {
            $query->where(
                function ($where) use ($params) {
                    $this->buildWhereCondition($params['last_step_status'], $where);
                }
            );
        }

        if (count($sortPrarams) > 0) {
            $query->orderBy($sortPrarams['sort'], $sortPrarams['direction']);
        } else {
            $query->orderBy('CommissionInfo.id', 'asc');
        }

        $query = $this->selectFields($query);

        $result = $query->get()->toArray();

        return $result;
    }

    /**
     * Get commission_info with relationship by id
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  integer $id
     * @return array|mixed
     */
    public function getCommissionInfoByIdForApproval($id)
    {
        $result = $this->model->with(
            [
                'mCorp' => function ($q) {
                    $q->where('del_flg', 0);
                },
                'demandInfo' => function ($q) {
                    $q->with(
                        [
                            'auctionInfo',
                            'mGenres',
                        ]
                    );
                },
                'billInfo' => function ($q) {
                    $q->whereNull('auction_id');
                },
                'affiliationInfo'
            ]
        )->find($id)->toArray();
        $result['m_corp_category'] = (array)DB::table('m_corp_categories')
            ->where('corp_id', '=', $result['m_corp']['id'])
            ->where('category_id', '=', $result['demand_info']['category_id'])
            ->first();

        return $result;
    }

    /**
     * get first by demand_id and corp_id
     *
     * @param integer $demandId
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstByDemandIdAndCorpId($demandId, $corpId)
    {
        $query = $this->model->where('corp_id', $corpId);
        if ($demandId) {
            $query->where('demand_id', $demandId);
        }

        return $query->first();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function multipleUpdate($data)
    {
        $updated = false;
        foreach ($data as &$value) {
            $idStaff = null;
            if (isset($value['position'])) {
                $temp = $value['position'];
                unset($value['position']);
            }
            if (isset($value['id_staff'])) {
                $idStaff = $value['id_staff'];
                unset($value['id_staff']);
            }
            $updated = $this->model->where('id', $value['id'])->update($value);
            if (!empty($temp)) {
                $value['position'] = $temp;
            }
            if (!empty($idStaff)) {
                $value['id_staff'] = $idStaff;
            }
        }
        return $updated;
    }

    /**
     * @param integer $demandId
     * @param integer $corpId
     * @param integer $cType
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findByDemandIdCorpAndType($demandId, $corpId, $cType)
    {
        return $this->model->where('demand_id', $demandId)->where('corp_id', $corpId)
            ->where('commission_type', $cType)->first();
    }


    /**
     * @param array $ids
     */
    public function updateAppPushFlg($ids)
    {
        $this->model->whereIn('id', $ids)->update(['app_push_flg' => 1]);
    }

    /**
     * @param integer $demandId
     * @return \Illuminate\Support\Collection
     */
    public function getAllCommissionByDemandId($demandId)
    {
        return $this->model->where('demand_id', $demandId)->get();
    }

    /**
     * @param $corpId
     * @return mixed
     */
    public function getDemandIdByCorp($corpId)
    {
        return $this->model->where('corp_id', $corpId)
            ->where('commit_flg', 0)
            ->where('del_flg', 0)
            ->where('lost_flg', 0)
            ->select('demand_id')
            ->get();
    }

    /**
     * @param $data
     * @return boolean
     */
    public function updateWorkStatus($data)
    {
        $commission = $this->model->find($data['id']);
        if ($commission) {
            $commission->work_status = 0;
            return $commission->save();
        }
        return false;
    }

    /**
     * @param string $field
     * @param string $val
     * @return string
     */
    private function convertNullToEmpty($field, $val)
    {
        $arr = [
            'attention', 'commission_dial', 'tel_commission_person', 'report_note', 'progress_report_datetime',
            're_commission_exclusion_user_id', 'commission_note_sender'
        ];

        $val = in_array($field, $arr) && is_null($val) ? '' : $val;
        $result = ($field == 'commission_type') ? (int) $val : $val;

        return $result;
    }

    /**
     * @param $commissionId
     * @param \Monolog\Logger $logger
     * @return mixed|void
     */
    public function updateWorkStatusAfterDeleteSchedule($commissionId, $logger)
    {
        $commission = $this->model->where(['id' => $commissionId])->first();
        if (!empty($commission)) {
            $commission->work_status = null;
            if (!$commission->save()) {
                $logger->error(__FILE__ . ' >>> ' . __LINE__ . ' >>> UPDATE FAIL AT COMMISSION ID: ' . $commissionId);
            }
        } else {
            $logger->warning(__FILE__ . ' >>> ' . __LINE__ . ' >>> NO COMMISSION AT: ' . $commissionId);
        }
    }
}
