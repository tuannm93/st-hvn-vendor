<?php

namespace App\Repositories\Eloquent;

use App\Models\BillInfo;
use App\Repositories\BillRepositoryInterface;
use Auth;
use Config;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class BillRepository extends SingleKeyModelRepository implements BillRepositoryInterface
{
    /**
     * @var BillInfo
     */
    protected $model;

    /**
     * BillRepository constructor.
     *
     * @param BillInfo $billModel
     */
    public function __construct(BillInfo $billModel)
    {
        $this->model = $billModel;
    }

    /**
     * @return \App\Models\Base|BillInfo|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new BillInfo();
    }

    /**
     * get data for download
     *
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    public function getDataByCondition($fromDate, $toDate)
    {
        $result = $this->model->select(
            'BillInfo.id as BillInfo__id',
            'BillInfo.demand_id as BillInfo__demand_id',
            'BillInfo.bill_status as BillInfo__bill_status',
            'BillInfo.irregular_fee_rate as BillInfo__irregular_fee_rate',
            'BillInfo.irregular_fee as BillInfo__irregular_fee',
            'BillInfo.deduction_tax_include as BillInfo__deduction_tax_include',
            'BillInfo.deduction_tax_exclude as BillInfo__deduction_tax_exclude',
            'BillInfo.indivisual_billing as BillInfo__indivisual_billing',
            'BillInfo.comfirmed_fee_rate as BillInfo__comfirmed_fee_rate',
            'BillInfo.fee_target_price as BillInfo__fee_target_price',
            'BillInfo.fee_tax_exclude as BillInfo__fee_tax_exclude',
            'BillInfo.tax as BillInfo__tax',
            'BillInfo.insurance_price as BillInfo__insurance_price',
            'BillInfo.total_bill_price as BillInfo__total_bill_price',
            'BillInfo.fee_billing_date as BillInfo__fee_billing_date',
            'BillInfo.fee_payment_date as BillInfo__fee_payment_date',
            'BillInfo.fee_payment_price as BillInfo__fee_payment_price',
            'BillInfo.fee_payment_balance as BillInfo__fee_payment_balance',
            'BillInfo.report_note as BillInfo__report_note',
            'BillInfo.modified_user_id as BillInfo__modified_user_id',
            'BillInfo.modified as BillInfo__modified',
            'BillInfo.created_user_id as BillInfo__created_user_id',
            'BillInfo.created as BillInfo__created',
            'BillInfo.commission_id as BillInfo__commission_id',
            'BillInfo.business_trip_amount as BillInfo__business_trip_amount',
            'BillInfo.auction_id as BillInfo__auction_id',
            'DemandInfo.id as DemandInfo__id',
            'DemandInfo.riro_kureka as DemandInfo__riro_kureka',
            'DemandInfo.customer_name as DemandInfo__customer_name',
            'DemandInfo.category_id as DemandInfo__category_id',
            'MItem.item_name as MItem__item_name',
            'CommissionInfo.complete_date as CommissionInfo__complete_date',
            'CommissionInfo.tel_commission_datetime as CommissionInfo__tel_commission_datetime',
            'MCorp.id as MCorp__id',
            'MCorp.official_corp_name as MCorp__official_corp_name'
        )->from('public.bill_infos as BillInfo');
        $result = $this->setJoin($result);
        $result = $this->setWhere($result, $fromDate, $toDate);
        $result = $result->orderBy('BillInfo.id', 'DESC')->get()->toArray();

        return $result;
    }

    /**
     * set join condition
     *
     * @param object $query
     * @return mixed
     */
    private function setJoin($query)
    {
        /** @var Builder $query */
        $query->join('public.demand_infos as DemandInfo', function ($join) {
            /** @var JoinClause $join */
            $join->on(function ($joinCondition) {
                /** @var JoinClause $joinCondition */
                $joinCondition->on('DemandInfo.id', '=', 'BillInfo.demand_id')
                    ->where('DemandInfo.del_flg', '!=', 1)
                    ->where('DemandInfo.demand_status', '!=', 6)
                    ->where('DemandInfo.riro_kureka', '!=', 1);
            });
        })->join('public.m_sites as MSite', function ($join) {
            /** @var JoinClause $join */
            $join->on('MSite.id', '=', 'DemandInfo.site_id');
        })->join('public.m_items as MItem', function ($join) {
            /** @var JoinClause $join */
            $join->on(function ($joinCondition) {
                /** @var JoinClause $joinCondition */
                $joinCondition->on('MItem.item_id', '=', 'MSite.commission_type')
                    ->where('MItem.item_category', '=', '取次形態');
            });
        })->join('public.commission_infos as CommissionInfo', function ($join) {
            /** @var JoinClause $join */
            $join->on(function ($joinCondition) {
                /** @var JoinClause $joinCondition */
                $joinCondition->on('CommissionInfo.demand_id', '=', 'BillInfo.demand_id')
                    ->on('CommissionInfo.id', '=', 'BillInfo.commission_id')
                    ->where(function ($joinOr) {
                        /** @var JoinClause $joinOr */
                        $joinOr->where('CommissionInfo.complete_date', '!=', '')
                            ->orWhereNotNull('BillInfo.auction_id');
                    })
                    ->where('CommissionInfo.del_flg', '!=', 1)
                    ->where('CommissionInfo.introduction_not', '!=', 1)
                    ->where('CommissionInfo.introduction_free', '!=', 1);
            });
        })->join('public.m_corps as MCorp', function ($join) {
            /** @var JoinClause $join */
            $join->on(function ($joinCondition) {
                /** @var JoinClause $joinCondition */
                $joinCondition->on('MCorp.id', '=', 'CommissionInfo.corp_id')
                    ->where('MCorp.del_flg', '!=', 1);
            });
        });

        return $query;
    }

    /**
     * set where condtion
     *
     * @param object $query
     * @param string $fromDate
     * @param string $toDate
     * @return mixed
     */
    private function setWhere($query, $fromDate, $toDate)
    {
        /** @var Builder $query */
        $query->where('BillInfo.bill_status', '!=', $this->getStatusConfig('rits.bill_status', 'payment'))
            ->where(function ($where) {
                /** @var Builder $where */
                $where->where(function ($andWhereOne) {
                    /** @var Builder $andWhereOne */
                    $andWhereOne->where(function ($where) {
                        /** @var Builder $where */
                        $where->where(
                            'CommissionInfo.commission_status',
                            '=',
                            $this->getStatusConfig('rits.construction_status', 'construction')
                        )->whereNull('BillInfo.auction_id');
                    })->orWhere(function ($andWhereTwo) {
                        /**@var Builder $andWhereTwo */
                        $andWhereTwo->where(
                            'CommissionInfo.commission_status',
                            '=',
                            $this->getStatusConfig('rits.construction_status', 'introduction')
                        )->whereNull('BillInfo.auction_id');
                    })->orWhere(function ($andWhereThree) {
                        /** @var Builder $andWhereThree */
                        $andWhereThree->where('CommissionInfo.ac_commission_exclusion_flg', '=', false)
                            ->whereNotNull('BillInfo.auction_id');
                    });
                });
            })
            ->where(function ($where) use ($fromDate) {
                /** @var Builder $where */
                if (!empty($fromDate)) {
                    $where->where('CommissionInfo.complete_date', '>=', $fromDate);
                }
            })
            ->where(function ($where) use ($toDate) {
                /** @var Builder $where */
                if (!empty($toDate)) {
                    $where->where('CommissionInfo.complete_date', '<=', $toDate);
                }
            });

        return $query;
    }

    /**
     * @param string $code see: file config/rits.php
     * @param string $text
     * @return mixed
     */
    private function getStatusConfig($code, $text)
    {
        $listStatus = Config::get($code);
        $listDiv = array_flip($listStatus);

        return $listDiv[$text];
    }

    /**
     * create bill_infos
     *
     * @param array $data
     * @return BillInfo
     */
    public function insertData($data)
    {
        $billInfo = $this->model;

        if (isset($data['id'])) {
            $billInfo = $this->model->where('id', $data['id'])->first();
        }

        $billInfo->commission_id = $data['commission_id'];
        $billInfo->demand_id = $data['demand_id'];

        if (isset($data['bill_status'])) {
            $billInfo->bill_status = $data['bill_status'];
        }

        $billInfo->comfirmed_fee_rate = $data['comfirmed_fee_rate'];
        $billInfo->fee_target_price = $data['fee_target_price'];
        $billInfo->fee_tax_exclude = $data['fee_tax_exclude'];
        $billInfo->tax = $data['tax'];
        $billInfo->total_bill_price = $data['total_bill_price'];
        $billInfo->fee_payment_price = $data['fee_payment_price'];
        $billInfo->fee_payment_balance = $data['fee_payment_balance'];
        $billInfo->auction_id = isset($data['auction_id']) ? $data['auction_id'] : null;
        $billInfo->deduction_tax_include = isset($data['deduction_tax_include']) ? $data['deduction_tax_include'] : 0;
        $billInfo->deduction_tax_exclude = isset($data['deduction_tax_exclude']) ? $data['deduction_tax_exclude'] : 0;
        $billInfo->insurance_price = isset($data['insurance_price']) ? $data['insurance_price'] : 0;
        $this->beforeCreate($billInfo);

        $billInfo->save();

        return $billInfo;
    }

    /**
     * set created,modified data
     *
     * @param object $billInfo
     * @return  void
     */
    private function beforeCreate($billInfo)
    {
        $userLoginId = Auth::user()->user_id;
        $billInfo->modified = date(config('constant.FullDateTimeFormat'), time());
        $billInfo->modified_user_id = $userLoginId;

        if (empty($billInfo->id)) {
            $billInfo->created = date(config('constant.FullDateTimeFormat'), time());
            $billInfo->created_user_id = $userLoginId;
        }
    }

    /**
     * @param integer $auctionId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findByAuctionId($auctionId)
    {
        $result = $this->model->from('bill_infos AS AuctionBillInfo')
            ->where('AuctionBillInfo.auction_id', $auctionId)
            ->select('AuctionBillInfo.total_bill_price AS AuctionBillInfo__total_bill_price')
            ->first();

        return $result;
    }

    /**
     * search bill list data by multicondtion
     *
     * @param array $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     */
    public function searchByConditions($data)
    {
        $query = $this->model->orderBy('bill_infos.id', 'desc');
        $query = self::setJoinCondition($query);
        $query = self::setCondition($query, $data);
        if ($data['bill_status'] == getDivValue('bill_status', 'payment')) {
            $page = getDivValue('list_limit', 'perPage');
            $query->select(
                'bill_infos.*',
                'demand_infos.customer_name',
                'demand_infos.category_id',
                'demand_infos.id as demand_info_id',
                'commission_infos.complete_date',
                'm_corps.id as m_corps_id',
                'm_corps.official_corp_name'
            );

            return $result = $query->paginate($page);
        } else {
            $query->select(
                'bill_infos.*',
                'demand_infos.id as demand_info_id',
                'demand_infos.riro_kureka',
                'demand_infos.customer_name',
                'demand_infos.category_id',
                'm_items.item_name',
                'commission_infos.complete_date',
                'commission_infos.tel_commission_datetime',
                'm_corps.id as m_corps_id',
                'm_corps.official_corp_name'
            );

            return $result = $query->get();
        }
    }

    /**
     * set join condition for bill search
     *
     * @param object $query
     * @return mixed
     */
    private function setJoinCondition($query)
    {
        /** @var Builder $query */
        $query->join('demand_infos', function ($joins) {
            /** @var JoinClause $joins */
            $joins->on('demand_infos.id', '=', 'bill_infos.demand_id');
            $joins->where([
                ['demand_infos.del_flg', '!=', 1],
                ['demand_infos.demand_status', '!=', 6],
                ['demand_infos.riro_kureka', '!=', 1],
            ]);
        });
        $query->join('m_sites', 'm_sites.id', '=', 'demand_infos.site_id');
        $query->join('m_items', function ($joins) {
            /** @var JoinClause $joins */
            $joins->on('m_items.item_id', '=', 'm_sites.commission_type');
            $joins->whereRaw("m_items.item_category = '" . MItemRepository::ITEM_CATEGORY_BILL_LIST . "'");
        });
        $query->join('commission_infos', function ($joins) {
            /** @var JoinClause $joins */
            $joins->on('commission_infos.demand_id', '=', 'bill_infos.demand_id');
            $joins->on('commission_infos.id', '=', 'bill_infos.commission_id');
            $joins->where(function ($where) {
                /** @var Builder $where */
                $where->whereRaw("commission_infos.complete_date != ''");
                $where->orWhereNotNull('bill_infos.auction_id');
            });
            $joins->where([
                ['commission_infos.del_flg', '!=', 1],
                ['commission_infos.introduction_not', '!=', 1],
                ['commission_infos.introduction_free', '!=', 1],
            ]);
        });
        $query->join('m_corps', function ($joins) {
            /** @var JoinClause $joins */
            $joins->on('m_corps.id', '=', 'commission_infos.corp_id');
            $joins->where('m_corps.del_flg', '!=', 1);
        });

        return $query;
    }

    /**
     * @param object $query
     * @param array $data
     * @return builder
     */
    private function setCondition($query, $data)
    {
        /** @var Builder $query */
        $query->where('m_corps.id', $data['corp_id']);
        $query->where(function ($wheres) {
            /** @var Builder $wheres */
            $wheres->where(function ($where) {
                /** @var Builder $where */
                $where->where('commission_infos.commission_status', getDivValue('construction_status', 'construction'));
                $where->whereNull('bill_infos.auction_id');
            });
            $wheres->orWhere(function ($where) {
                /** @var Builder $where */
                $where->where('commission_infos.commission_status', getDivValue('construction_status', 'introduction'));
                $where->whereNull('bill_infos.auction_id');
            });
            $wheres->orWhere(function ($where) {
                /** @var Builder $where */
                $where->whereRaw('commission_infos.ac_commission_exclusion_flg = false');
                $where->whereNotNull('bill_infos.auction_id');
            });
        })->where('bill_infos.bill_status', $data['bill_status']);
        if (!empty($data['from_fee_billing_date'])) {
            $query->where('bill_infos.fee_billing_date', '>=', $data['from_fee_billing_date']);
        }
        if (!empty($data['to_fee_billing_date'])) {
            $query->where('bill_infos.fee_billing_date', '<=', $data['to_fee_billing_date']);
        }

        return $query;
    }

    /**
     * Find modified column
     *
     * @param integer $id
     * @return mixed
     */
    public function findModified($id)
    {
        return $this->model->select('modified')->where('id', $id)->first();
    }

    /**
     * update bill info data
     *
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function updateRecord($id, $data)
    {
        try {
            DB::beginTransaction();
            $this->model->where('id', $id)->update($data);
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();

            return false;
        }
    }

    /**
     * get data bill_infos data
     *
     * @param array $ids
     * @return mixed
     */
    public function getDownloadList($ids)
    {
        $query = $this->model->select(
            'bill_infos.*',
            'demand_infos.id as demand_info_id',
            'demand_infos.riro_kureka',
            'demand_infos.customer_name',
            'demand_infos.category_id',
            'm_items.item_name',
            'commission_infos.complete_date',
            'commission_infos.tel_commission_datetime',
            'm_corps.id as m_corp_id',
            'm_corps.official_corp_name'
        );
        self::setJoinCondition($query);
        $query->whereIn('bill_infos.id', $ids)
            ->where('commission_infos.complete_date', '>=', check_months_ago())
            ->where('commission_infos.complete_date', '<', date('Y/m/01'))
            ->orderBy('bill_infos.id', 'DESC');
        $result = $query->get();

        return $result;
    }

    /**
     * get bill_info data pass condition
     *
     * @param array $ids
     * @param integer $mCorpId
     * @param integer $billStatus
     * @return int
     */
    public function getPastIssueList($ids, $mCorpId, $billStatus)
    {
        $query = $this->model->select(DB::raw('SUM((bill_infos.total_bill_price - bill_infos.fee_payment_price))AS "past_bill_price"'));
        if ($billStatus == getDivValue('bill_status', 'issue')) {
            $query->whereIn('bill_infos.id', $ids);
        };
        $query->where('bill_infos.bill_status', '!=', 3)
            ->where('m_corps.id', $mCorpId)
            ->where('commission_infos.complete_date', '<', check_months_ago());
        $query->join('demand_infos', 'demand_infos.id', '=', 'bill_infos.demand_id');
        $query->join('commission_infos', function ($join) {
            /**@var JoinClause $join */
            $join->on('commission_infos.demand_id', 'bill_infos.demand_id');
            $join->whereRaw("commission_infos.complete_date != ''");
        });
        $query->join('m_corps', 'm_corps.id', '=', 'commission_infos.corp_id');
        $result = $query->first();
        $pastBillPrice = 0;
        if (!empty($result['past_bill_price'])) {
            $pastBillPrice = $result['past_bill_price'];
        }

        return $pastBillPrice;
    }

    /**
     * get bill_info data
     *
     * @param integer $id
     * @return array
     */
    public function getData($id)
    {
        $feilds = $this->getAllTableFields('bill_infos');
        $addFields = [
            'MCorp.id as MCorp__id',
            'MCorp.official_corp_name as MCorp__official_corp_name',
            'MCorp.commission_dial as MCorp__commission_dial',
            'MCorp.tel1 as MCorp__tel1',
            'MCorp.tel2 as MCorp__tel2',
            'DemandInfo.customer_name as DemandInfo__customer_name',
            'DemandInfo.address1 as DemandInfo__address1',
            'DemandInfo.address2 as DemandInfo__address2',
            'DemandInfo.address3 as DemandInfo__address3',
            'DemandInfo.customer_tel as DemandInfo__customer_tel',
            'DemandInfo.tel1 as DemandInfo__tel1',
            'DemandInfo.tel2 as DemandInfo__tel2',
            'DemandInfo.postcode as DemandInfo__postcode',
            'CommissionInfo.complete_date as CommissionInfo__complete_date',
            'm_sites.site_name as MSite__site_name',
            'm_genres.genre_name as MGenre__genre_name',
        ];
        foreach ($addFields as $val) {
            array_push($feilds, $val);
        }

        return $this->model->select($feilds)->join('demand_infos AS DemandInfo', function ($join) {
            /**@var JoinClause $join */
            $join->on('DemandInfo.id', '=', 'bill_infos.demand_id');
        })->join('commission_infos as CommissionInfo', function ($join) {
            /**@var JoinClause $join */
            $join->on('CommissionInfo.demand_id', '=', 'bill_infos.demand_id');
            $join->where(function ($query) {
                /**@var Builder $query */
                $query->orWhere('CommissionInfo.complete_date', '!=', '');
                $query->orWhereNotNull('bill_infos.auction_id');
            });
        })->join('m_corps AS MCorp', function ($join) {
            /**@var JoinClause $join */
            $join->on('MCorp.id', '=', 'CommissionInfo.corp_id');
        })->leftJoin('m_sites', function ($join) {
            /**@var JoinClause $join */
            $join->on('m_sites.id', '=', 'DemandInfo.site_id');
        })->leftJoin('m_genres', function ($join) {
            /**@var JoinClause $join */
            $join->on('m_genres.id', '=', 'DemandInfo.genre_id');
        })->where('bill_infos.id', $id)->first()->toArray();
    }

    /**
     * update bill_infos data
     *
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function updateData($id, $data)
    {
        try {
            DB::beginTransaction();
            $billInfo = $this->model->find($id);
            $billInfo->bill_status = (int)$data['bill_infos_bill_status'];
            $billInfo->indivisual_billing = (int)$data['bill_infos_indivisual_billing'];
            $billInfo->fee_billing_date = $data['bill_infos_fee_billing_date'];
            $billInfo->fee_payment_date = $data['bill_infos_fee_payment_date'];
            $billInfo->fee_payment_price = (int)$data['bill_infos_fee_payment_price'];
            $billInfo->report_note = $data['bill_infos_report_note'];
            $this->beforeCreate($billInfo);
            if ($billInfo->save()) {
                DB::commit();

                return true;
            }
            DB::rollback();

            return false;
        } catch (\Exception $exception) {
            DB::rollback();

            return false;
        }
    }

    /**
     * get first data by demand id and auction id
     *
     * @param integer $demandId
     * @param null|integer $auctionId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getByDemandIdAuctionId($demandId, $auctionId = null)
    {
        return $this->model->where('demand_id', $demandId)->where('auction_id', $auctionId)->first();
    }

    /**
     * count data by demand id and commission id
     *
     * @param integer $demandId
     * @param integer $commissionId
     * @return int
     */
    public function countByDemandIdAndCommissionId($demandId, $commissionId)
    {
        return $this->model->where('commission_id', $commissionId)->where('demand_id', $demandId)->count();
    }

    /**
     * delete multi record
     *
     * @param integer $demandId
     * @param array $ids
     * @return bool|null
     */
    public function deleteByDemandIdAndIds($demandId, $ids)
    {
        try {
            $this->model->where('demand_id', $demandId)->whereIn('id', $ids)->delete();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
