<?php

namespace App\Repositories\Eloquent;

use App\Models\AuctionInfo;
use App\Repositories\AuctionInfoRepositoryInterface;
use DB;
use Carbon\Carbon;

class AuctionInfoRepository extends SingleKeyModelRepository implements AuctionInfoRepositoryInterface
{
    /**
     * @var AuctionInfo
     */
    protected $model;

    /**
     * AuctionInfoRepository constructor.
     *
     * @param AuctionInfo $model
     */
    public function __construct(AuctionInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @return AuctionInfo|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AuctionInfo();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }

    /**
     * get auction already list
     * @param  array $orderBy
     * @param  integer $affiliationId
     * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAuctionAlreadyList($orderBy, $affiliationId)
    {
        $now = Carbon::now();
        $now->subWeek(1);
        $selectionSystem = [
            getDivValue('selection_type', 'auction_selection'),
            getDivValue('selection_type', 'automatic_auction_selection')
        ];
        $condition = $this->model
            ->select(
                'auction_infos.*',
                'visit_times.visit_time',
                'm_genres.genre_name',
                'demand_infos.customer_name',
                'demand_infos.customer_tel',
                'demand_infos.tel1',
                'demand_infos.address1',
                'demand_infos.address2',
                'demand_infos.address3',
                'demand_infos.contact_desired_time',
                'demand_infos.priority',
                'demand_infos.construction_class',
                'commission_infos.id as commission_infos_id',
                'commission_infos.demand_id as commission_infos_demand_id',
                'commission_infos.visit_desired_time',
                'commission_infos.order_respond_datetime',
                'm_sites.site_name',
                'm_corps.auction_masking',
                'demand_infos.is_contact_time_range_flg',
                'demand_infos.contact_desired_time_from',
                'demand_infos.contact_desired_time_to',
                'visit_times.is_visit_time_range_flg',
                'visit_times.visit_time_from',
                'visit_times.visit_time_to',
                'visit_times.visit_adjust_time',
                'demand_attached_files.demand_id as demand_attached_files_demand_id'
            )->join(
                'demand_infos',
                function ($join) use ($selectionSystem) {
                    $join->on('demand_infos.id', '=', 'auction_infos.demand_id');
                    $join->whereIn('demand_infos.selection_system', $selectionSystem);
                }
            )
            ->join('m_sites', 'demand_infos.site_id', '=', 'm_sites.id')
            ->join(
                'commission_infos',
                function ($join) use ($now) {
                    $join->on('demand_infos.id', '=', 'commission_infos.demand_id');
                    $join->on('auction_infos.corp_id', '=', 'commission_infos.corp_id');
                }
            )
            ->leftJoin('visit_times', 'auction_infos.visit_time_id', '=', 'visit_times.id')
            ->leftJoin('m_genres', 'demand_infos.genre_id', '=', 'm_genres.id')
            ->join('m_corps', 'auction_infos.corp_id', '=', 'm_corps.id')
            ->leftJoin(
                DB::raw('(select distinct demand_id from demand_attached_files) as demand_attached_files'),
                'demand_attached_files.demand_id',
                '=',
                'demand_infos.id'
            )
            ->where('auction_infos.corp_id', $affiliationId)
            ->where('auction_infos.display_flg', 0)
            ->where('auction_infos.refusal_flg', 0)
            ->whereDate('auction_infos.push_time', '>=', $now)
            ->where('commission_infos.commit_flg', 1);
        foreach ($orderBy as $key => $value) {
            $condition = $condition->orderBy($key, $value);
        }
        return $condition->paginate(config('rits.list_limit'));
    }

    /**
     * delete item
     * @param array $data
     * @return bool|mixed
     */
    public function deleteItemByListId($data)
    {
        if (!isset($data['id'])) {
            return false;
        }
        return $this->model
            ->whereIn('id', $data['id'])
            ->update(['display_flg' => 1]);
    }

    /**
     * @param integer $id
     * @return mixed|static
     */
    public function getById($id)
    {
        $result = $this->model->find($id);
        return $result;
    }

    /**
     * count record auction_infos
     * @param integer $id
     * @param integer $commitFlg
     * @return integer
     */
    public function countByIdAndCommissionCommitFlag($id, $commitFlg = 1)
    {
        $result = $this->findByIdAndCommission($id, $commitFlg);
        return $result->count();
    }

    /**
     * get data by id and commit flag
     * @param integer $id
     * @param integer $commitFlg
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getByIdAndCommissionCommitFlag($id, $commitFlg = 1)
    {
        $fields = 'commission_infos.created';
        $result = $this->findByIdAndCommission($id, $commitFlg, $fields, true);

        return $result->first();
    }

    /**
     * find by id and commission
     * @param  integer  $id
     * @param  integer  $commitFlag
     * @param  string  $fields
     * @param  boolean $isLastCreated
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    private function findByIdAndCommission($id, $commitFlag, $fields = null, $isLastCreated = false)
    {
        $results = $this->model
            ->join('commission_infos', function ($join) use ($commitFlag) {
                $join->on('commission_infos.demand_id', '=', 'auction_infos.demand_id');
                $join->where('commission_infos.commit_flg', '=', $commitFlag);
            })
            ->where('auction_infos.id', $id);

        if ($fields != null) {
            $results = $results->select($fields);
        }

        if ($fields != null & $isLastCreated) {
            $results = $results->orderBy($fields, 'desc');
        }

        return $results;
    }

    /**
     * get list auction info by auctionId
     * @param integer $auctionId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getAuctionInfoDemandInfo($auctionId)
    {
        $arrField = ['auction_infos.*', 'demand_infos.contact_desired_time', 'demand_infos.contact_desired_time_from', 'demand_infos.contact_desired_time_to',
            'demand_infos.auction_deadline_time', 'demand_infos.business_trip_amount', 'demand_infos.cost_from', 'demand_infos.cost_to',
            'demand_infos.demand_status', 'demand_infos.site_id', 'm_corps.jbr_available_status'];

        $result = $this->model->select($arrField)
            ->join(
                'demand_infos',
                function ($join) {
                    $join->on('demand_infos.id', '=', 'auction_infos.demand_id');
                }
            )
            ->join(
                'm_corps',
                function ($join) {
                    $join->on('m_corps.id', '=', 'auction_infos.corp_id');
                }
            )
            ->where('auction_infos.id', $auctionId)->first();

        return $result;
    }

    /**
     * get auction_fee from select_genre_prefectures by auctionId
     * @param integer $auctionId
     * @return integer
     */
    public function getAuctionFee($auctionId)
    {
        $result = $this->model->select('select_genre_prefectures.auction_fee')
            ->leftJoin(
                'demand_infos',
                function ($join) {
                    $join->on('demand_infos.id', '=', 'auction_infos.demand_id');
                }
            )
            ->leftJoin(
                'm_genres',
                function ($join) {
                    $join->on('m_genres.id', '=', 'demand_infos.genre_id');
                }
            )
            ->leftJoin(
                'select_genre_prefectures',
                function ($join) {
                    $join->on('select_genre_prefectures.genre_id', '=', 'demand_infos.genre_id');
                    $join->on('select_genre_prefectures.prefecture_cd', '=', 'demand_infos.address1');
                }
            )->where('auction_infos.id', $auctionId)->first()->toarray();

        if (!empty($result)) {
            return isset($result['auction_fee']) ? $result['auction_fee'] : 0;
        }

        return 0;
    }

    /**
     * update data
     * @param array $dataAuction
     * @return mixed
     */
    public function updateAuctionInfo($dataAuction)
    {
        $this->model->where('id', $dataAuction['id'])
            ->update([
                'demand_id' => $dataAuction['demand_id'],
                'corp_id' => $dataAuction['corp_id'],
                'responders' => $dataAuction['responders'],
                'modified' => date('Y-m-d H:i:s'),
                'modified_user_id' => auth()->user()->user_id
            ]);
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstById($id)
    {
        return $this->model->join('demand_infos', 'auction_infos.demand_id', '=', 'demand_infos.id')
            ->where('auction_infos.id', $id)
            ->select('demand_infos.site_id', 'demand_infos.selection_system')
            ->first();
    }

    /**
     * count by id
     * @param integer $auctionId
     * @return integer
     */
    public function countCurrentCommit($auctionId)
    {
        return $this->model->join(
            'commission_infos',
            function ($join) {
                $join->on('commission_infos.demand_id', '=', 'auction_infos.demand_id')
                    ->where('commission_infos.commit_flg', 1);
            }
        )
            ->where('auction_infos.id', $auctionId)
            ->count();
    }

    /**
     * find newest record in commission_infos by auctionId
     * @param integer $auctionId
     * @param null $field
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findLastCommission($auctionId, $field = null)
    {
        $commissionInfo = $this->model->join(
            'commission_infos',
            function ($join) {
                $join->on('commission_infos.demand_id', '=', 'auction_infos.demand_id')
                    ->where('commission_infos.commit_flg', 1);
            }
        )
            ->where('auction_infos.id', $auctionId)
            ->orderBy('commission_infos.created', 'desc')
            ->first();

        if ($field) {
            return $commissionInfo->$field;
        }

        return $commissionInfo;
    }

    /**
     * update flag auction
     * @param array $data
     * @param array $flags
     * @return bool
     */
    public function updateFlag($data, $flags = ['refusal_flg'])
    {
        try {
            $auction = $this->model->find($data['id']);
            if ($auction) {
                foreach ($flags as $flag) {
                    $auction->$flag = $data[$flag];
                }
                $auction->save();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * find by demand id and corp id
     * @param integer $demandId
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstByDemandIdAndCorpId($demandId, $corpId)
    {
        return $this->model
            ->where('demand_id', $demandId)
            ->where('corp_id', $corpId)
            ->first();
    }

    /**
     * @param null $demandId
     * @return \Illuminate\Support\Collection
     */
    public function getListByDemandId($demandId = null)
    {
        $results = $this->model
            ->select(
                'auction_infos.*',
                'm_corps.official_corp_name',
                'visit_times.visit_time',
                'refusals.corresponds_time1',
                'refusals.corresponds_time2',
                'refusals.corresponds_time3',
                'refusals.cost_from',
                'refusals.cost_to',
                'refusals.other_contens',
                'm_corps.corp_name',
                'refusals.not_available_flg',
                'refusals.estimable_time_from',
                'refusals.contactable_time_from'
            )
            ->join('m_corps', 'auction_infos.corp_id', '=', 'm_corps.id')
            ->leftjoin('refusals', 'auction_infos.id', '=', 'refusals.auction_id')
            ->leftjoin('visit_times', 'auction_infos.visit_time_id', '=', 'visit_times.id')
            ->where('auction_infos.demand_id', '=', $demandId)
            ->orderBy('auction_infos', 'desc')->get();
        return $results;
    }

    /**
     * create or update auction
     * @param integer $id
     * @param array $data
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function saveAuction($id, $data)
    {
        $data['modified_user_id'] = auth()->user()->user_id;
        $date = date('Y-m-d H:i:s');
        $data['modified'] = $date;
        if (is_null($id)) {
            $data['created_user_id'] = auth()->user()->user_id;
            $data['created'] = $date;
            return $this->model->create($data);
        } else {
            return $this->model->where('id', $id)->update($data);
        }
    }

    /**
     * create or update auction
     * @param array $data
     * @return mixed|void
     */
    public function saveAuctions($data)
    {
        $insert = [];
        $date = date('Y-m-d H:i:s');
        foreach ($data as $value) {
            if (empty($value)) {
                continue;
            }
            unset($value['rank']);
            $value['modified_user_id'] = auth()->user()->user_id;
            $value['modified'] = $date;
            if (empty($value['push_time'])) {
                $value['push_time'] = null;
            }
            if (isset($value['id']) && !empty($value['id'])) {
                $value['modified_user_id'] = auth()->user()->user_id;
                $this->model->where('id', $value['id'])->update($value);
            } else {
                unset($value['id']);
                $value['created_user_id'] = auth()->user()->user_id;
                $value['created'] = $date;
                $insert[] = $value;
            }
        }
        if (!empty($insert)) {
            return $this->model->insert($insert);
        }
        return true;
    }

    /**
     * get AuctionInfo By DemandId For Check Deadline
     * @param integer $demandId
     * @return mixed
     */
    public function getAuctionInfoByDemandIdForCheckDeadline($demandId)
    {
        $query = $this->model->join('affiliation_infos', 'affiliation_infos.corp_id', '=', 'auction_infos.corp_id')
            ->join('demand_infos', 'demand_infos.id', '=', 'auction_infos.demand_id')
            ->join('m_corp_categories', function ($join) {
                $join->on('m_corp_categories.corp_id', '=', 'auction_infos.corp_id')
                    ->on('m_corp_categories.category_id', '=', 'demand_infos.category_id');
            })
            ->join('affiliation_area_stats', function ($join) {
                $join->on('affiliation_area_stats.corp_id', '=', 'auction_infos.corp_id')
                    ->on('affiliation_area_stats.genre_id', '=', 'm_corp_categories.genre_id')
                    ->on('affiliation_area_stats.prefecture', '=', 'demand_infos.address1');
            })
            ->join('m_corps', 'm_corps.id', '=', 'auction_infos.corp_id')
            ->leftJoin('refusals', 'refusals.auction_id', '=', 'auction_infos.id')
            ->where('auction_infos.demand_id', $demandId)
            ->select(
                'auction_infos.*',
                'demand_infos.category_id',
                'demand_infos.business_trip_amount',
                'm_corp_categories.order_fee_unit',
                'm_corp_categories.order_fee',
                'm_corp_categories.introduce_fee',
                'm_corp_categories.corp_commission_type',
                'affiliation_area_stats.commission_unit_price_category',
                'affiliation_area_stats.commission_count_category',
                'affiliation_area_stats.commission_unit_price_rank',
                DB::raw('(SELECT m_genres.targer_commission_unit_price FROM m_genres WHERE m_genres.id = m_corp_categories.genre_id) AS targer_commission_unit_price'),
                'm_corps.official_corp_name',
                'm_corps.corp_name',
                'refusals.corresponds_time1',
                'refusals.corresponds_time2',
                'refusals.corresponds_time3',
                'refusals.cost_from',
                'refusals.cost_to',
                'refusals.other_contens',
                'refusals.estimable_time_from',
                'refusals.contactable_time_from'
            )
            ->orderBy('auction_infos.push_time')
            ->orderByRaw('affiliation_area_stats.commission_unit_price_category IS NULL')
            ->orderBy('affiliation_area_stats.commission_unit_price_category', 'desc')
            ->orderBy('affiliation_area_stats.commission_count_category', 'desc');

        $results = $query->get();
        return $results;
    }

    /**
     * get list auction info by demand_id
     * @param integer $demandId
     * @return mixed
     */
    public function findAllByDemandId($demandId)
    {
        $results = $this->model->where('demand_id', $demandId)->get();
        return $results;
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer|null $id
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($id, $data)
    {
        if (is_null($id)) {
            return $this->model->insert($data);
        } else {
            return $this->model->where('id', $id)->update($data);
        }
    }

    /**
     * update before push flag
     * @param  array $arrayId
     * @return boolean
     */
    public function updateBeforePushFlag($arrayId)
    {
        $dataUpdate = [
            'before_push_flg'  => 1,
            'modified_user_id' => null,
            'modified'         => date('Y-m-d H:i:s')
        ];
        return $this->model
            ->whereIn('id', $arrayId)
            ->update($dataUpdate);
    }

    /**
     * @return mixed|void
     */
    public function countRefusal($demandInfoId)
    {
        $result =  $this->model->select(
            DB::raw('((select count(0) from auction_infos where demand_id ='.$demandInfoId.') - (select count(0) from auction_infos where demand_id ='.$demandInfoId.' and refusal_flg = 1)) as r_count')
        )->first();
        return $result;
    }
}
