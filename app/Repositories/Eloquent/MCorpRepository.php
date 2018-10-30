<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorp;
use App\Repositories\MCorpRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MCorpRepository extends BaseMCorpRepository implements MCorpRepositoryInterface
{
    /**
     * MCorpRepository constructor.
     *
     * @param MCorp $model
     */
    public function __construct(MCorp $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $id
     * @param bool $toArray
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getFirstById($id, $toArray = false)
    {
        if (!is_numeric($id)) {
            return $this->getBlankModel();
        }

        $result = $this->model->find($id);

        if ($toArray) {
            return $result ? $result->toarray() : [];
        }

        return $result;
    }

    /**
     * @return \App\Models\Base|MCorp|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorp();
    }

    /**
     * @param array $data
     * @param boolean $isNew
     * @param integer $count
     * @return mixed
     */
    public function searchCorpForPopup($data, $isNew, $count)
    {
        $flag = false;
        if (empty($data['target_check'])) {
            if (!empty($data['category_id']) && !empty($data['jis_cd'])) {
                $flag = true;
            } else {
                return false;
            }
        }
        $fields = $this->getListFieldPopup();
        $itemCat = "'" . config('constant.MITEM.COORDINATION_METHOD') . "'";
        $query = $this->model->join('affiliation_infos', function ($join) {
            $join->on('affiliation_infos.corp_id', '=', 'm_corps.id');
        })->join('m_items', function ($join) use ($itemCat) {
            $join->on('m_items.item_category', '=', DB::raw($itemCat));
            $join->on('m_items.item_id', '=', 'm_corps.coordination_method');
        });
        if ($flag) {
            $fields[] = 'affiliation_area_stats.commission_unit_price_category';
            $fields[] = 'affiliation_area_stats.commission_count_category';
            $fields[] = 'm_corp_categories.category_id';
            $fields[] = 'm_corp_categories.auction_status';

            $query->join('m_corp_categories', function ($join) use ($data) {
                $join->on('m_corp_categories.corp_id', '=', 'm_corps.id');
                $join->where('m_corp_categories.category_id', '=', (int)($data['category_id']));
            });
            $jisCd = $data['jis_cd'];
            $query->join('m_target_areas', function ($join) use ($jisCd) {
                $join->on('m_target_areas.corp_category_id', '=', 'm_corp_categories.id');
                $join->where('m_target_areas.jis_cd', '=', $jisCd);
            });
            $prefecture = substr($data['jis_cd'], 0, 2);

            $query->join('affiliation_area_stats', function ($join) use ($prefecture) {
                $join->on('affiliation_area_stats.corp_id', '=', 'm_corps.id');
                $join->on('affiliation_area_stats.genre_id', '=', 'm_corp_categories.genre_id');
                $join->where('affiliation_area_stats.prefecture', '=', (int)$prefecture);
            });
        } else {
            $query->leftjoin('m_corp_categories', function ($join) use ($data) {
                $join->on('m_corp_categories.corp_id', '=', 'm_corps.id');
                $join->where('m_corp_categories.category_id', '=', (int)($data['category_id']));
            });
        }
        $query->leftjoin('affiliation_stats', function ($join) {
            $join->on('affiliation_stats.corp_id', '=', 'm_corps.id');
            $join->on('affiliation_stats.genre_id', '=', 'm_corp_categories.genre_id');
        });
        $query->leftjoin('affiliation_subs', function ($join) use ($data) {
            $join->on('affiliation_subs.affiliation_id', '=', 'affiliation_infos.id');
            $join->where('affiliation_subs.item_id', '=', (int)($data['category_id']));
        });
        $query->leftjoin('m_corp_new_years', function ($join) use ($data) {
            $join->on('m_corps.id', '=', 'm_corp_new_years.corp_id');
        });
        $query->where('m_corps.affiliation_status', '=', 1);
        if (!empty($data['corp_name'])) {
            $query->where(
                DB::raw('z2h_kana(m_corps.corp_name)'),
                'like',
                '%' . chgSearchValue($data['corp_name']) . '%'
            );
        }
        if ($flag) {
            $query->where(function ($sqlQuery) {
                return $sqlQuery->whereNull('m_corps.auction_status')->orWhere([
                    ['m_corps.auction_status', '!=', 3],
                    ['m_corp_categories.auction_status', '!=', 3],
                ])->orWhere([
                    ['m_corps.auction_status', '=', 1],
                    ['m_corp_categories.auction_status', '!=', 3],
                ])->orWhere([
                    ['m_corps.auction_status', '=', 3],
                    ['m_corp_categories.auction_status', '=', 1],
                ])->orWhere([
                    ['m_corps.auction_status', '=', 3],
                    ['m_corp_categories.auction_status', '=', 2],
                ]);
            });
        }
        $this->buildQueryWherePopup($query, $flag, $isNew);
        $this->buildQueryWhereExcludeCorp($query, $data);
        $limit = $this->getLimitPopup($isNew, $count);
        $result = $query->whereNotIn('m_corps.commission_accept_flg', [
            0,
            3,
        ])->where('m_corps.del_flg', 0)->limit($limit)->select($fields);
        $resultOrder = $this->orderResult($result, $flag);
        return $resultOrder;
    }

    /**
     * get m_corps data function
     *
     * @param array $data
     * @param integer $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchCorpAndPaging($data, $page)
    {
        $query = $this->model->join('commission_infos', function ($join) {
            $join->on('commission_infos.corp_id', '=', 'm_corps.id');
            $join->where('commission_infos.del_flg', '!=', 1);
            $join->where('commission_infos.introduction_not', '!=', 1);
            $join->where(function ($query) {
                $query->orWhere(
                    'commission_infos.commission_status',
                    '=',
                    getDivValue('construction_status', 'construction')
                );
                $query->orWhere(
                    'commission_infos.commission_status',
                    '=',
                    getDivValue('construction_status', 'introduction')
                );
                $query->orWhere('commission_infos.introduction_free', '!=', 1);
            });
        })->join('bill_infos', function ($join) {
            $join->on(function ($joins) {
                $joins->on('bill_infos.commission_id', '=', 'commission_infos.id');
                $joins->where('bill_infos.auction_id', '=', null);
                $joins->whereRaw("commission_infos.complete_date != '' ");
            });
            $join->orOn(function ($joins) {
                $joins->on('bill_infos.commission_id', '=', 'commission_infos.id');
                $joins->where('bill_infos.auction_id', '!=', null);
            });
        })->join('demand_infos', function ($join) {
            $join->on('demand_infos.id', '=', 'commission_infos.demand_id')->where([
                ['demand_infos.del_flg', '!=', 1],
                ['demand_infos.demand_status', '!=', 6],
                ['demand_infos.riro_kureka', '!=', 1],
            ]);
        });
        if (!empty($data['nominee'])) {
            $query->join(
                DB::raw('( SELECT corp_id FROM money_corresponds WHERE nominee LIKE \'%' . $data['nominee'] . '%\' GROUP BY corp_id) as money_corresponds'),
                'money_corresponds.corp_id',
                '=',
                'm_corps.id'
            );
        };
        if (!empty($data['corp_name'])) {
            $query->whereRaw("z2h_kana(m_corps.corp_name) like '%" . chgSearchValue($data['corp_name']) . "%'");
        }
        if (!empty($data['corp_id'])) {
            $query->where('m_corps.id', '=', chgSearchValue($data['corp_id']));
        }
        if (!empty($data['bill_status'])) {
            $query->where('bill_infos.bill_status', '=', $data['bill_status']);
        }
        if (!empty($data['bill_id'])) {
            $query->where('bill_infos.id', '=', $data['bill_id']);
        }
        if (!empty($data['from_fee_billing_date'])) {
            $query->where('bill_infos.fee_billing_date', '>=', $data['from_fee_billing_date']);
        }
        if (!empty($data['to_fee_billing_date'])) {
            $query->where('bill_infos.fee_billing_date', '<=', $data['to_fee_billing_date']);
        };
        $query->where('m_corps.affiliation_status', '=', 1)
            ->where('m_corps.del_flg', 0)
            ->groupBy('m_corps.id', 'm_corps.official_corp_name')
            ->orderBy('m_corps.id', 'asc')
            ->select('m_corps.id', 'm_corps.official_corp_name');
        $result = $query->paginate($page);

        return $result;
    }

    /**
     * @param integer $id
     * @param boolean $includeMoreInformation
     * @return \Illuminate\Database\Eloquent\Model|Collection|null|object|static
     */
    public function findByIdForAffiliation($id, $includeMoreInformation = false)
    {
        $mCorpsColumns = $this->getAllTableFields('m_corps');
        $mCorpNewYearsColumns = $this->getAllTableFields('m_corp_new_years');
        $query = DB::table('m_corps')
            ->select($mCorpsColumns)
            ->addSelect($mCorpNewYearsColumns)
            ->leftJoin('m_corp_new_years', 'm_corps.id', '=', 'm_corp_new_years.corp_id')
            ->where('m_corps.id', '=', $id);
        if ($includeMoreInformation) {
            $affiliationInfosColumns = $this->getAllTableFields('affiliation_infos');
            $query->addSelect($affiliationInfosColumns);
            $query->leftJoin('affiliation_infos', 'm_corps.id', '=', 'affiliation_infos.corp_id');
            $query->leftJoin('affiliation_stats', 'm_corps.id', '=', 'affiliation_stats.corp_id');
            $query->leftJoin('corp_agreement', 'm_corps.id', '=', 'corp_agreement.corp_id');
        }
        $mCorp = $query->first();
        if (isset($mCorp->m_corps_responsibility)) {
            $responsibillity = explode(' ', $mCorp->m_corps_responsibility);
            if (!empty($responsibillity[0])) {
                $mCorp->m_corps_responsibility_sei = $responsibillity[0];
            }
            if (!empty($responsibillity[1])) {
                $mCorp->m_corps_responsibility_mei = $responsibillity[1];
            }
        }

        return $mCorp;
    }

    /*
     * find corp by id function
     * @param integer $id
     * @return array
     */
    /**
     * @param null $id
     * @return array|mixed
     */
    public function findById($id = null)
    {
        $result = $this->model->select('id', 'corp_name', 'corp_name_kana', 'popup_stop_flg')
            ->where('id', $id)->get()->toarray();

        return $result;
    }

    /**
     * find m_corp by id
     *
     * @param integer $corpId
     * @return \App\Models\Base|MCorp|\Illuminate\Database\Eloquent\Model|mixed|static
     */
    public function findMcorp($corpId)
    {
        if (!is_numeric($corpId)) {
            return $this->getBlankModel();
        }

        return $this->model->find($corpId);
    }

    /**
     * get list by customer_tel function
     *
     * @param integer $customerTel
     * @return array
     */
    public function searchAffiliationInfoAll($customerTel)
    {
        $results = $this->model->where('commission_dial', '=', $customerTel)
            ->orwhere('tel1', '=', $customerTel)
            ->orwhere('tel2', '=', $customerTel)
            ->orwhere('mobile_tel', '=', $customerTel)
            ->get()->toArray();

        return $results;
    }

    /**
     * @param integer $searchKey
     * @param string $searchValue
     * @param integer $limitSearch
     * @param integer $count
     * @return mixed
     */
    public function searchByCorpIdOrCorpName($searchKey, $searchValue, $limitSearch, $count)
    {
        $query = $this->model->select('id', 'corp_name')->where('del_flg', 0);
        if ($searchKey === 'search_by_id') {
            $patterns = preg_split('/[\s\n,]/', $searchValue, -1, PREG_SPLIT_NO_EMPTY);
            $conditions = array_values(array_filter($patterns, 'is_numeric'));
            $query->whereIn('id', $conditions);
        } else {
            $patterns = preg_split('/[\s]/', str_replace('　', ' ', $searchValue), -1, PREG_SPLIT_NO_EMPTY);
            array_walk($patterns, function ($conditions) use (&$query) {
                if (mb_substr($conditions, 0, 1) === '-') {
                    $query->where('corp_name', 'NOT LIKE', '%' . mb_substr($conditions, 1) . '%');
                } else {
                    $query->where('corp_name', 'LIKE', '%' . $conditions . '%');
                }
            });
        }
        $results = $query->orderBy('id', 'asc')->take($limitSearch);
        if ($count) {
            $data = $results->count();
        } else {
            $data = $results->get();
        }

        return $data;
    }

    /**
     * @param integer $categoryIds
     * @param array $address1
     * @return array
     */
    public function getListByCategoryIdsAndAddress1($categoryIds, $address1)
    {
        $data = $this->model->select(
            'MCorp.id as idCorp',
            'MCorp.corp_name as nameCorp',
            'AutoCommissionCorp.sort as sort',
            'AutoCommissionCorp.process_type as process_type'
        )
            ->from('public.auto_commission_corp as AutoCommissionCorp')
            ->join('public.m_corps as MCorp', function ($join) {
                /** @var JoinClause $join */
                $join->on('AutoCommissionCorp.corp_id', '=', 'MCorp.id');
            })->join('public.m_categories as MCategories', function ($join) {
                /**@var JoinClause $join */
                $join->on('AutoCommissionCorp.category_id', '=', 'MCategories.id');
            })->join('public.m_posts as MPost', function ($join) {
                /** @var JoinClause $join */
                $join->on('AutoCommissionCorp.jis_cd', '=', 'MPost.jis_cd');
            })->whereIn('AutoCommissionCorp.category_id', $categoryIds)
            ->whereIn('MPost.address1', $address1)
            ->where('MCorp.del_flg', '=', 0)
            ->orderBy('AutoCommissionCorp.process_type', 'ASC')
            ->orderBy('AutoCommissionCorp.sort', 'ASC')
            ->distinct()->get()->toArray();

        return $data;
    }

    /**
     * @param integer $categoryIds
     * @param array $listPref
     * @param integer $corpIds
     * @param bool $type
     * @param string $text
     * @return array|mixed
     */
    public function searchByCategoryPref($categoryIds, $listPref, $corpIds, $type, $text)
    {
        $result = $this->model->select('MCorp.id AS id', 'MCorp.corp_name AS name')
            ->from('public.m_corps AS MCorp')
            ->join('public.m_corp_categories AS MCorpCategory', function ($join) {
                /** @var JoinClause $join */
                $join->on('MCorpCategory.corp_id', '=', 'MCorp.id');
            })->join(DB::raw('(SELECT corp_category_id, SUBSTRING(jis_cd, 1, 2) FROM m_target_areas
                            WHERE SUBSTRING(jis_cd, 1, 2) IN (' . implode(",", $listPref) . ')
                            GROUP BY corp_category_id, SUBSTRING(jis_cd, 1,2)) AS "MTargetArea"'), function ($join) {
                /** @var JoinClause $join */
                $join->on('MTargetArea.corp_category_id', '=', 'MCorpCategory.id');
            })->where('MCorp.del_flg', '=', 0)
            ->where(function ($where) use ($type, $text) {
                /** @var Builder $where */
                if ($type == 0 && strlen(trim($text)) > 0) {
                    $where->where('MCorp.corp_name', 'like', '%' . $text . '&');
                }
                if ($type == 1 && strlen(trim($text)) > 0) {
                    $listCropId = explode(',', $text);
                    if (is_array($listCropId) && count($listCropId) > 0) {
                        $where->whereIn('MCorp.id', $listCropId);
                    }
                }
            })->whereIn('MCorpCategory.category_id', $categoryIds)
            ->whereNotIn('MCorp.id', $corpIds)->orderBy('MCorp.id', 'asc')
            ->distinct()->get()->toArray();

        return $result;
    }

    /**
     * @param array $data
     * @param string $limitSearch
     * @param integer $count
     * @return mixed
     */
    public function searchCorpAddList($data, $limitSearch, $count)
    {
        $results = [];
        if (!empty($data['category_id'] && !empty($data['pref_cd']))) {
            !empty($data['commission_corp_id']) ? $commisionArray = $data['commission_corp_id'] : $commisionArray = [];
            !empty($data['selection_corp_id']) ? $selectionArray = $data['selection_corp_id'] : $selectionArray = [];
            $checkNotInId = array_merge($commisionArray, $selectionArray);
            $query = $this->model->select('m_corps.id', 'm_corps.corp_name')
                ->where('m_corps.del_flg', 0)
                ->where('m_corp_categories.category_id', $data['category_id'])
                ->whereNotIn('m_corps.id', $checkNotInId);
            if ($data['search_key'] === 'search_by_id') {
                $patterns = preg_split('/[\s\n,]/', $data['search_value'], -1, PREG_SPLIT_NO_EMPTY);
                $conditions = array_values(array_filter($patterns, 'is_numeric'));
                $query->whereIn('m_corps.id', $conditions);
            } else {
                $patterns = preg_split('/[\s]/', str_replace('　', ' ', $data['search_value']), -1, PREG_SPLIT_NO_EMPTY);
                array_walk($patterns, function ($conditions) use (&$query) {
                    if (mb_substr($conditions, 0, 1) === '-') {
                        $query->where('corp_name', 'NOT LIKE', '%' . mb_substr($conditions, 1) . '%');
                    } else {
                        $query->where('corp_name', 'LIKE', '%' . $conditions . '%');
                    }
                });
            }
            $query->join('m_corp_categories', 'm_corp_categories.corp_id', '=', 'm_corps.id')
                ->join(
                    DB::raw("(SELECT m_target_areas.corp_category_id as corps_category_id, SUBSTRING(jis_cd, 1, 2)
                                        FROM m_target_areas
                                        WHERE SUBSTRING(jis_cd, 1, 2) = '" . $data['pref_cd'] . "'
                                        GROUP BY corp_category_id, SUBSTRING(jis_cd, 1, 2)) as m_target_area"),
                    'corps_category_id',
                    '=',
                    'm_corp_categories.id'
                );
            $query->orderBy('m_corps.id', 'asc')->take($limitSearch);
            if ($count) {
                $results = $query->count();
            } else {
                $results = $query->get();
            }
            return $results;
        }

        return $results;
    }

    /**
     * @param integer $searchKey
     * @param string $searchValue
     * @param integer $excludeCorpId
     * @param int $limit
     * @return mixed
     */
    public function getAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId, $limit = 50)
    {
        return $this->buildAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId)->limit($limit)->get();
    }

    /**
     * @param integer $searchKey
     * @param string $searchValue
     * @param integer $excludeCorpId
     * @return mixed
     */
    public function buildAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId)
    {
        return $this->model->where('del_flg', 0)
            ->where('affiliation_status', 1)->whereNotIn('id', [])
            ->when($searchKey == 'search_by_name', function ($query) use ($searchValue) {
                $listOfWords = preg_split('/[\s]/', str_replace('　', ' ', $searchValue), -1, PREG_SPLIT_NO_EMPTY);
                foreach ($listOfWords as $word) {
                    if (mb_substr($word, 0, 1) === '-') {
                        $query->where('corp_name', 'NOT LIKE', '%' . mb_substr($word, 1) . '%');
                    } else {
                        $query->where('corp_name', 'LIKE', "%$word%");
                    }
                }
            })
            ->when($searchKey == 'search_by_id', function ($query) use ($searchValue) {
                $listOfWords = preg_split('/[\s\n,]/', $searchValue, -1, PREG_SPLIT_NO_EMPTY);
                $query->whereIn('id', $listOfWords);
            })
            ->when(!empty($excludeCorpId), function ($query) use ($excludeCorpId) {
                $query->whereNotIn('id', $excludeCorpId);
            })->select('id', 'corp_name')->orderBy('id');
    }

    /**
     * Acquisition of company information by ID
     *
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getDataAffiliationById($id)
    {
        $results = $this->model->select(
            'm_corps.*',
            'affiliation_infos.id as affiliation_info_id',
            'affiliation_infos.employees',
            'affiliation_infos.max_commission',
            'affiliation_infos.collection_method',
            'affiliation_infos.collection_method_others',
            'affiliation_infos.liability_insurance',
            'affiliation_infos.reg_follow_date1',
            'affiliation_infos.reg_follow_date2',
            'affiliation_infos.reg_follow_date3',
            'affiliation_infos.waste_collect_oath',
            'affiliation_infos.transfer_name',
            'affiliation_infos.claim_history',
            'affiliation_infos.claim_count',
            'affiliation_infos.commission_count',
            'affiliation_infos.weekly_commission_count',
            'affiliation_infos.orders_count',
            'affiliation_infos.orders_rate',
            'affiliation_infos.construction_cost',
            'affiliation_infos.fee',
            'affiliation_infos.bill_price',
            'affiliation_infos.payment_price',
            'affiliation_infos.balance',
            'affiliation_infos.construction_unit_price',
            'affiliation_infos.commission_unit_price',
            'affiliation_infos.reg_info',
            'affiliation_infos.reg_pdf_path',
            'affiliation_infos.attention',
            'affiliation_infos.capital_stock',
            'affiliation_infos.listed_kind',
            'affiliation_infos.default_tax',
            'affiliation_infos.credit_limit',
            'affiliation_infos.add_month_credit',
            'affiliation_infos.virtual_account',
            'corp_agreement.id as corp_agreement_id',
            'affiliation_stats.commission_count_category',
            'affiliation_stats.orders_count_category',
            'affiliation_stats.commission_unit_price_category',
            'm_corp_new_years.id as m_corp_new_years_id',
            'm_corp_new_years.label_01',
            'm_corp_new_years.status_01',
            'm_corp_new_years.label_02',
            'm_corp_new_years.status_02',
            'm_corp_new_years.label_03',
            'm_corp_new_years.status_03',
            'm_corp_new_years.label_04',
            'm_corp_new_years.status_04',
            'm_corp_new_years.label_05',
            'm_corp_new_years.status_05',
            'm_corp_new_years.label_06',
            'm_corp_new_years.status_06',
            'm_corp_new_years.label_07',
            'm_corp_new_years.status_07',
            'm_corp_new_years.label_08',
            'm_corp_new_years.status_08',
            'm_corp_new_years.label_09',
            'm_corp_new_years.status_09',
            'm_corp_new_years.label_10',
            'm_corp_new_years.status_10',
            'm_corp_new_years.note as new_year_note'
        )
            ->leftJoin('affiliation_infos', 'affiliation_infos.corp_id', '=', 'm_corps.id')
            ->leftJoin('affiliation_stats', 'affiliation_stats.corp_id', '=', 'm_corps.id')
            ->leftJoin('m_corp_new_years', 'm_corp_new_years.corp_id', '=', 'm_corps.id')
            ->leftJoin('corp_agreement', 'corp_agreement.corp_id', '=', 'm_corps.id')
            ->where('m_corps.id', $id)
            ->where('m_corps.del_flg', 0)->first();

        if (isset($results->responsibility)) {
            $responsibillity = explode(' ', $results->responsibility);
            if (!empty($responsibillity[0])) {
                $results->responsibility_sei = $responsibillity[0];
            }
            if (!empty($responsibillity[1])) {
                $results->responsibility_mei = $responsibillity[1];
            }
        }

        return $results;
    }

    /**
     * Checking company information update date and time
     *
     * @param  integer $id
     * @param  string $modified
     * @return boolean
     */
    public function checkModifiedMcorp($id, $modified)
    {
        if (empty($id)) {
            return true;
        }

        $results = $this->find($id);

        if ($results) {
            if ($modified == $results->modified) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param integer $searchKey
     * @param string $searchValue
     * @param integer $excludeCorpId
     * @return mixed
     */
    public function getCountAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId)
    {
        return $this->buildAdvanceSearchByIdOrName($searchKey, $searchValue, $excludeCorpId)->count();
    }

    /**
     * @param integer $corpId
     * @return bool|mixed
     */
    public function updateGuidelineCheckDate($corpId)
    {
        try {
            $this->model->where('id', $corpId)->update([
                'guideline_check_date' => date('Y-m-d'),
                'modified' => date('Y-m-d H:i:s'),
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param integer $id
     * @param array $data
     * @return bool|mixed
     */
    public function updateCorp($id, $data)
    {
        $result = $this->model->where('id', $id)->update($data);

        return $result;
    }

    /**
     * @param integer $id
     * @return bool|mixed
     */
    public function deleteSoftById($id)
    {
        $mCorp = $this->model->find($id);
        if ($mCorp) {
            $mCorp->del_flg = true;
            $mCorp->save();

            return true;
        }

        return false;
    }

    /**
     * @param object $allCondition
     * @param string $orderBy
     * @param string $direction
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function getListCorpByConditionFromAffiliation(
        $allCondition,
        $orderBy = 'id',
        $direction = 'asc',
        $page = 1,
        $limit = 100
    ) {
        $query = $this->querySelectForSearch();
        $query = $this->querySelectJoinParam($allCondition, $query);
        $query = $this->querySelectWhereParam($allCondition, $query);
        $total = $query->count();
        $query->orderBy('m_corps_' . $orderBy, $direction)->take($limit)->skip(($page - 1) * $limit);
        $list = $query->get()->toArray();
        $numberPage = (int)($total / $limit);
        if ($total % $limit != 0) {
            $numberPage += 1;
        }

        $result = [
            'total' => $total,
            'data' => $list,
            'pageNumber' => $numberPage,
            'curPage' => $page,
        ];

        return $result;
    }

    /**
     * @param object $allCondition
     * @return array|mixed
     */
    public function createDataDownloadCsvAffiliation($allCondition)
    {
        $query = $this->querySelectForDownloadCsv();
        $query = $this->querySelectJoinParam($allCondition, $query);
        $query = $this->querySelectWhereParam($allCondition, $query);
        $result = $query->orderBy('m_corps_id', 'asc')->get()->toArray();

        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function updateById($data)
    {
        return $this->model->where('id', $data['id'])->update($data);
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getAllInformationById($id)
    {
        return $this->model->where('id', '=', $id)->orderBy('id')->first();
    }

    /**
     * get official_corp_name column
     *
     * @param integer $id
     * @return mixed
     */
    public function getOfficialName($id)
    {
        return $this->model->select('official_corp_name')->where('id', $id)->first();
    }

    /**
     * find corp by id
     *
     * @param integer $corpId
     * @return object
     */
    public function findByMcorpId($corpId)
    {
        return $this->model->select('id', 'corp_commission_type')->where('id', $corpId)->first();
    }

    /**
     * Get crop unattended for report development search
     *
     * @param integer $genreId
     * @return mixed
     */
    public function getUnattendedForReportDevByGenreId($genreId)
    {
        return $this->model->select("m_corps.address1", DB::raw('count(*) as total'))
            ->join("m_corp_categories", "m_corps.id", "=", "m_corp_categories.corp_id")
            ->where("m_corp_categories.genre_id", $genreId)
            ->where("m_corps.affiliation_status", 0)
            ->where("m_corps.corp_status", 1)
            ->where("m_corps.del_flg", 0)
            ->groupBY("m_corps.address1")
            ->orderBy("m_corps.address1", "asc")->get();
    }

    /**
     * Get crop advance for report development search
     *
     * @param integer $genreId
     * @return mixed
     */
    public function getAdvanceForReportDevByGenreId($genreId)
    {
        return $this->model->select("m_corps.address1", DB::raw('count(*) as total'))
            ->join("m_corp_categories", "m_corps.id", "=", "m_corp_categories.corp_id")
            ->where("m_corp_categories.genre_id", $genreId)
            ->where("m_corps.affiliation_status", 0)
            ->where("m_corps.corp_status", "!=", 1)
            ->where("m_corps.corp_status", "!=", 6)->where("m_corps.del_flg", 0)
            ->groupBY("m_corps.address1")
            ->orderBy("m_corps.address1", "asc")->get();
    }

    /**
     * @param integer $genreId
     * @param integer $address
     * @param null $status
     * @return $this|mixed
     */
    public function getListForDataTableByGenreIdAndAddressAndStatus($genreId, $address, $status = null)
    {
        return $this->model->select(
            "m_corps.address1",
            "m_users.user_name",
            "m_corps.id",
            "m_corps.official_corp_name",
            "m_items.item_name",
            "m_corps.note"
        )->join("m_corp_categories", "m_corps.id", "=", "m_corp_categories.corp_id")
            ->leftJoin("m_users", "m_users.id", "=", "m_corps.rits_person")
            ->leftJoin("m_items", function ($join) {
                $join->on("m_items.item_id", "=", "m_corps.corp_status");
                $join->where("m_items.item_category", "開拓状況");
            })
            ->where("m_corps.affiliation_status", 0)
            ->where("m_corps.address1", $address)
            ->where("m_corp_categories.genre_id", $genreId)
            ->where("m_corps.del_flg", 0)
            ->where(function ($query) use ($status) {
                if ($status == null) {
                    $query->where("m_corps.corp_status", "<>", 6);
                } elseif ($status == 1) {
                    $query->where("m_corps.corp_status", 1);
                } else {
                    $query->where("m_corps.corp_status", "<>", 1);
                    $query->where("m_corps.corp_status", "<>", 6);
                }
            })
            ->groupBy(
                "m_corps.address1",
                "m_corp_categories.genre_id",
                "m_users.user_name",
                "m_corps.official_corp_name",
                "m_items.item_name",
                "m_corps.id",
                "m_corps.note"
            );
    }

    /**
     * @param null $data
     * @return array|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getListForCommissionSelect($data = null)
    {
        return $this->model->select(['id', 'official_corp_name'])->where(function ($query) use ($data) {
            if ($data !== null && isset($data['corp_name'])) {
                $query->whereRaw("z2h_kana(official_corp_name) like '%" . chgSearchValue($data['corp_name']) . "%'");
            }
        })->orderBy('id', 'asc')->simplePaginate(config('rits.list_limit'));
    }

    /**
     * @param integer $affiliationId
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findByAffiliationId($affiliationId)
    {
        $fields = $this->getAllTableFieldsByAlias('m_corps', 'MCorp');
        $result = $this->model->from('m_corps AS MCorp')
            ->where('MCorp.id', $affiliationId)
            ->where('MCorp.del_flg', 0)->select($fields)->first();

        return $result;
    }

    /**
     * @param array $corpIds
     * @return Collection|mixed
     */
    public function getHolidayByCorpId($corpIds = [])
    {
        return DB::table('m_corps')
            ->join('m_corp_subs', 'm_corp_subs.corp_id', '=', 'm_corps.id')
            ->join('m_items', function ($join) {
                $join->on('m_items.item_category', '=', 'm_corp_subs.item_category')
                    ->on('m_items.id', '=', 'm_corp_subs.item_id')
                    ->where('m_corp_subs.item_category', 'LIKE', '%休業日%');
            })->whereIn('m_corps.id', $corpIds)
            ->selectRaw('m_corps.id corp_id, string_agg(m_items.item_name, \',\') holidays')
            ->groupBy('m_corps.id')->get();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null|static|static[]
     */
    public function findAllAttribute($corpId)
    {
        return $this->model->with('MCorpCategory')->find($corpId);
    }

    /**
     * @param array $data
     * @return mixed|string
     */
    public function getTargetJisCd($data)
    {
        $query = $this->model->select('jis_cd');
        if ($data['address1']) {
            $query->where('address1', $data['address1']);
        }
        if (isset($data['address2']) && !empty($data['address2'])) {
            $query->where('address2', $data['address2']);
        }
        $data = $query->groupBy('jis_cd')->first();

        return $data ? $data->jis_cd : '';
    }

    /**
     * @param array $data
     * @param bool $targetCheckFlg
     * @param bool $check
     * @param string $mCorpCateJoinType
     * @return mixed
     */
    public function getCorpsList($data, $targetCheckFlg, $check, $mCorpCateJoinType = 'left')
    {
        $categoryId = $data['category_id'];
        $jisCd = $data['jis_cd'];
        $prefecture = (int)substr($data ['jis_cd'], 0, 2);
        $limit = null;

        if ($mCorpCateJoinType != 'left') {
            $queryOrm = $this->model->whereHas([
                'mCorpCategory' => function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                }
            ]);
        } else {
            $queryOrm = $this->model->with([
                'mCorpCategory' => function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                },
            ]);
        }
        $queryOrm = $queryOrm->with([
            'mCorpNewYear',
            'affiliationInfo',
            'mItem' => function ($query) {
                $query->where('item_category', config('rits.coordination_method_category'));
            },
            'affiliationInfo.affiliationSubs' => function ($q) use ($categoryId) {
                $q->where('item_id', $categoryId);
            },
        ]);

        $queryOrm = $queryOrm->with([
            'affiliationStats' => function ($q) {
                $q->join('m_corp_categories', 'affiliation_stats.genre_id', '=', 'm_corp_categories.genre_id');
            },
        ]);

        $queryOrm = $this->buildQueryByTargetCheckFlag($targetCheckFlg, $queryOrm, $jisCd, $prefecture);

        /********************
         * Create condition
         ******************/

        $queryOrm = $this->buildQueryByExcludeCorpId($data, $queryOrm);

        $queryOrm = $queryOrm->where('m_corps.affiliation_status', MCorp::MEMBER_STATE_ACCESSION)
            ->whereNotIn('m_corps.commission_accept_flg', [0, 3,])
            ->whereRaw('coalesce(m_corps.corp_commission_status, 0) not in (1,2,3,4,5)');
        $queryOrm = $queryOrm->join('affiliation_infos', 'm_corps.id', '=', 'affiliation_infos.corp_id')
            ->leftJoin('affiliation_subs', 'affiliation_infos.id', '=', 'affiliation_subs.affiliation_id')
            ->whereNull('affiliation_subs.affiliation_id')->whereNull('affiliation_subs.item_id');

        if (!empty($data['corp_name'])) {
            $queryOrm = $queryOrm->whereRaw('z2h_kana(corp_name) LIKE %' . chgSearchValue($data['corp_name']) . '%');
        }

        if ($targetCheckFlg) {
            if (empty($check)) {
                $limit = 1500;
                $queryOrm = $queryOrm->join(
                    'affiliation_area_stats',
                    'm_corps.id',
                    '=',
                    'affiliation_area_stats.corp_id'
                )
                    ->where(
                        'affiliation_area_stats.commission_count_category',
                        '>=',
                        AffiliationAreaStat::AFF_TRANSACTION
                    );
            } else {
                $limit = 2000; //2000 - $this->controller->count
                $queryOrm = $queryOrm->join(
                    'affiliation_area_stats',
                    'm_corps.id',
                    '=',
                    'affiliation_area_stats.corp_id'
                )
                    ->where(
                        'affiliation_area_stats.commission_count_category',
                        '<',
                        AffiliationAreaStat::AFF_TRANSACTION
                    );
            }
            $queryOrm = $queryOrm->join('m_corp_categories', 'm_corps.id', '=', 'm_corp_categories.corp_id');
            $queryOrm = $queryOrm->orWhereNull('m_corps.auction_status')->orWhere(function ($wh) {
                $wh->where('m_corps.auction_status', '!=', MCorp::AUCTION_STATUS_THREE)
                    ->where('m_corp_categories.auction_status', '!=', MCorp::AUCTION_STATUS_THREE);
            })->orWhere(function ($wh) {
                $wh->where('m_corps.auction_status', MCorp::AUCTION_STATUS_ONE)
                    ->where('m_corp_categories.auction_status', '!=', MCorp::AUCTION_STATUS_THREE);
            })->orWhere(function ($wh) {
                $wh->where('m_corps.auction_status', MCorp::AUCTION_STATUS_THREE)
                    ->where('m_corp_categories.auction_status', MCorp::AUCTION_STATUS_ONE);
            })->orWhere(function ($wh) {
                $wh->where('m_corps.auction_status', MCorp::AUCTION_STATUS_THREE)
                    ->where('m_corp_categories.auction_status', MCorp::AUCTION_STATUS_TWO);
            });
        } else {
            $queryOrm = $queryOrm->join('affiliation_infos', 'm_corps.id', '=', 'affiliation_infos.corp_id');
            if (empty($check)) {
                $queryOrm = $queryOrm->where(
                    'affiliation_infos.commission_count',
                    '>=',
                    AffiliationAreaStat::AFF_TRANSACTION
                );
            } else {
                $queryOrm = $queryOrm->where(
                    'affiliation_infos.commission_count',
                    '<',
                    AffiliationAreaStat::AFF_TRANSACTION
                );
            }
        }
        if ($targetCheckFlg) {
            $queryOrm = $queryOrm->orderByRaw('affiliation_area_stats.commission_unit_price_category IS NULL')->orderByRaw('affiliation_area_stats.commission_unit_price_category DESC')->orderByRaw('affiliation_area_stats.commission_count_category DESC');
        } else {
            $queryOrm = $queryOrm->orderByRaw('affiliation_infos.commission_unit_price IS NULL')->orderByRaw('affiliation_infos.commission_unit_price DESC')->orderByRaw('affiliation_infos.commission_count DESC');
        }
        if ($limit) {
            return $queryOrm->paginate($limit);
        }

        return $queryOrm->get();
    }

    /**
     * @param integer $id
     * @return bool|mixed
     */
    public function isCommissionStop($id)
    {
        $mCorp = $this->model->select('commission_accept_flg')->where('id', $id)->first();

        if (isset($mCorp->commission_accept_flg) && $mCorp->commission_accept_flg != 0 && $mCorp->commission_accept_flg != 3) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getCorpData($corpId)
    {
        return $this->model->select(
            'm_corps.*',
            'affiliation_infos.listed_kind',
            'affiliation_infos.default_tax',
            'affiliation_infos.capital_stock',
            'affiliation_infos.employees'
        )
            ->leftJoin('affiliation_infos', 'affiliation_infos.corp_id', '=', 'm_corps.id')
            ->where('m_corps.id', $corpId)->first();
    }

    /**
     * Get data of m_corp by corpId
     *
     * @param integer $id
     * @param array $columns
     * @param array $order
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getDataById($id, $columns = ['*'], $order = ['column' => 'id', 'dir' => 'desc'])
    {
        return $this->model->where('id', $id)
            ->where('del_flg', 0)
            ->orderBy($order['column'], $order['dir'])
            ->first($columns);
    }

    /**
     * @param string $corpName
     * @return array|mixed
     */
    public function findByName($corpName)
    {
        $corpName = chgSearchValue($corpName);

        return $this->model->whereRaw('z2h_kana(m_corps.official_corp_name) = ?', $corpName)
            ->where('m_corps.del_flg', 0)->first()->toArray();
    }

    /**
     * @param integer $corpId
     * @return array|mixed
     */
    public function getHolidayListByCorpId($corpId)
    {
        $result = [];
        $affiliationInfo = new \stdClass();

        $data = DB::table('m_corps as MCorp')
            ->leftjoin('affiliation_infos as AffiliationInfo', 'MCorp.id', '=', 'AffiliationInfo.corp_id')
            ->leftjoin('m_corp_new_years as MCorpNewYear', 'MCorp.id', '=', 'MCorpNewYear.corp_id')
            ->where('MCorp.id', '=', $corpId)->select(
                [
                    'MCorpNewYear.label_01',
                    'MCorpNewYear.status_01',
                    'MCorpNewYear.label_02',
                    'MCorpNewYear.status_02',
                    'MCorpNewYear.label_03',
                    'MCorpNewYear.status_03',
                    'MCorpNewYear.label_04',
                    'MCorpNewYear.status_04',
                    'MCorpNewYear.label_05',
                    'MCorpNewYear.status_05',
                    'MCorpNewYear.label_06',
                    'MCorpNewYear.status_06',
                    'MCorpNewYear.label_07',
                    'MCorpNewYear.status_07',
                    'MCorpNewYear.label_08',
                    'MCorpNewYear.status_08',
                    'MCorpNewYear.label_09',
                    'MCorpNewYear.status_09',
                    'MCorpNewYear.label_10',
                    'MCorpNewYear.status_10',
                    'MCorpNewYear.note',
                    'AffiliationInfo.attention'
                ]
            )->first();
        if (!empty($data)) {
            $affiliationInfo->attention = $data->attention;
            // unset
            unset($data->attention);
            $mCorpNewYear = $data;

            $result['MCorpNewYear'] = collect($mCorpNewYear)->toArray();
            $result['AffiliationInfo'] = collect($affiliationInfo)->toArray();
        }

        return $result;
    }

    /**
     * @param integer $corpId
     * @param null $categoryId
     * @return array|mixed
     */
    public function getByCorpIdAndCategoryId($corpId, $categoryId = null)
    {

        $results = [];
        $data = DB::table('m_corps as MCorp')->leftjoin('m_corp_categories as MCorpCategory', function ($join) use (
            $categoryId
        ) {
            $join->on('MCorp.id', '=', 'MCorpCategory.corp_id');
            $join->where('MCorpCategory.category_id', $categoryId);
        })->where('MCorp.id', $corpId)->select([
            'MCorpCategory.order_fee',
            'MCorpCategory.order_fee_unit',
            'MCorpCategory.note',
            'MCorpCategory.introduce_fee',
            'MCorpCategory.corp_commission_type',
        ])->first();

        if (!empty($data)) {
            $results['MCorpCategory'] = collect($data)->toArray();
        }

        return $results;
    }

    /**
     * @param integer $num
     * @param integer $categoryId
     * @param integer $corpId
     * @return mixed|string
     */
    public function getCommissionChangeByCategoryIdAndCorpId($num, $categoryId = null, $corpId = null)
    {
        if (empty($categoryId)) {
            $categoryId = 0;
        }

        $results = DB::table('m_corps as MCorp')
            ->join('affiliation_infos as AffiliationInfo', 'AffiliationInfo.corp_id', '=', 'MCorp.id')
            ->leftjoin('m_corp_categories as MCorpCategory', function (
                $join
            ) use ($categoryId) {
                $join->on('MCorpCategory.corp_id', '=', 'MCorp.id');
                $join->where('MCorpCategory.category_id', $categoryId);
            })->leftjoin('affiliation_stats as AffiliationStat', function ($join) {
                $join->on('AffiliationStat.corp_id', '=', 'MCorp.id');
                $join->on('AffiliationStat.genre_id', '=', 'MCorpCategory.genre_id');
            })->where('MCorp.affiliation_status', 1);

        if (! empty($corpId)) {
            $results = $results->where('MCorp.id', $corpId);
        }

        $results = $results->select([
            'MCorpCategory.order_fee',
            'MCorpCategory.order_fee_unit',
            'MCorpCategory.note'
        ])->first();

        $results = collect($results)->toArray();
        return $num.",".$results['order_fee'].",".$results['order_fee_unit'].",".$results['note'];
    }

    /**
     * @param integer $corpId
     * @return array|mixed
     */
    public function getHolidays($corpId)
    {
        $query = "SELECT ARRAY_TO_STRING(ARRAY(
                    SELECT item_name FROM m_items INNER JOIN m_corp_subs
                    ON m_corp_subs.item_category = m_items.item_category
                    AND m_corp_subs.item_id = m_items.item_id
                    WHERE m_corp_subs.item_category = '" . self::ITEM_CATEGORY_HOLIDAY . "' ";
        $query .= "AND m_corp_subs.corp_id = " . $corpId . " ORDER BY m_items.sort_order ASC ),'｜')";

        return \DB::select($query);
    }

    /**
     * @param integer $jisCd
     * @param array $data
     * @return mixed
     */
    public function getDataCheckDeadlineCommand($jisCd, $data)
    {
        $query = $this->model->join('m_corp_categories', function ($join) use ($data) {
            $join->on('m_corp_categories.corp_id', '=', 'm_corps.id')
                ->where('m_corp_categories.genre_id', $data->genre_id)
                ->where('m_corp_categories.category_id', $data->category_id);
        })->join('m_target_areas', function ($join) use ($jisCd) {
            $join->on('m_target_areas.corp_category_id', '=', 'm_corp_categories.id')
                ->where('m_target_areas.jis_cd', $jisCd);
        })->join('affiliation_area_stats', function ($join) use ($data) {
            $join->on('affiliation_area_stats.corp_id', '=', 'm_corps.id')
                ->on('affiliation_area_stats.genre_id', '=', 'm_corp_categories.genre_id')
                ->where('affiliation_area_stats.prefecture', $data->address1);
        })->join('affiliation_infos', 'affiliation_infos.corp_id', '=', 'm_corps.id')
            ->join('affiliation_subs', function ($join) use ($data) {
                $join->on('affiliation_subs.affiliation_id', '=', 'affiliation_infos.id')
                    ->where('affiliation_subs.item_id', $data->category_id);
            })->where('m_corps.affiliation_status', 1)
            ->whereNull('affiliation_subs.affiliation_id')
            ->whereNull('affiliation_subs.item_id')
            ->whereRaw(' coalesce(m_corps.corp_commission_status, 0) not in (1, 2, 4, 5) ');

        // In case of living ambulance case, if JBR compliance status is "not compliant", make it out of auction subjects
        if ($data->site_id = 585) {
            $query = $query->where('m_corps.jbr_available_status', 2);
        }

        $results = $query->select(
            'm_corps.id',
            'm_corps.auction_status as m_corp_auction_status',
            'affiliation_area_stats.commission_unit_price_category',
            'affiliation_area_stats.commission_count_category',
            'affiliation_area_stats.commission_unit_price_rank',
            'm_corp_categories.order_fee',
            'm_corp_categories.order_fee_unit',
            'm_corp_categories.auction_status as m_corp_categories_auction_status',
            'm_corp_categories.introduce_fee',
            'm_corp_categories.corp_commission_type',
            'm_corps.auto_call_flag'
        )->get();

        return $results;
    }

    /**
     * Get fee data
     *
     * @param  integer $corpId
     * @param  integer $categoryId
     * @return mixed
     */
    public function getFeeData($corpId, $categoryId)
    {
        return $this->model->select(
            'm_corp_categories.order_fee',
            'm_corp_categories.order_fee_unit',
            'm_corp_categories.note',
            'm_corp_categories.introduce_fee'
        )->leftJoin('m_corp_categories', function ($join) use ($categoryId) {
            $join->on('m_corp_categories.corp_id', '=', 'm_corps.id');
            if ($categoryId) {
                $join->where('m_corp_categories.category_id', $categoryId);
            }
        })->find($corpId);
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getContactableTime($corpId)
    {
        $fields = [
            'm_corps.id as id',
            'm_corps.corp_name as corp_name',
            'm_corps.contactable_support24hour as contactable_support24hour',
            'm_corps.contactable_time_other as contactable_time_other',
            'm_corps.contactable_time_from as contactable_time_from',
            'm_corps.contactable_time_to as contactable_time_to',
        ];
        $query = $this->model->select($fields)
            ->leftJoin('affiliation_infos', 'm_corps.id', '=', 'affiliation_infos.corp_id')
            ->leftJoin('affiliation_stats', 'm_corps.id', '=', 'affiliation_stats.corp_id')
            ->leftJoin('m_corp_new_years', 'm_corps.id', '=', 'm_corp_new_years.corp_id')
            ->leftJoin('corp_agreement', 'm_corps.id', '=', 'corp_agreement.corp_id')
            ->where('m_corps.del_flg', '=', 0)
            ->where('m_corps.id', '=', $corpId);

        return $query->first();
    }

    /**
     * @param array $data
     * @param bool $isAntiSocial
     * @return mixed
     */
    public function updateAll($data, $isAntiSocial = false)
    {
        if ($isAntiSocial) {
            return $this->model->where('antisocial_display_flag', 0)->update($data);
        } else {
            return $this->model->where('license_display_flag', 0)->update($data);
        }
    }

    /**
     * @param $staffId
     * @return mixed
     */
    public function getMailByUserId($staffId)
    {
        $query = $this->model->select('mailaddress_pc')
            ->join('m_users', 'm_users.affiliation_id', '=', 'm_corps.id')
            ->where('m_users.user_id', '=', $staffId)
            ->get()->toArray();
        return $query;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getCorpNameAndStaffNameFromUserId($userId)
    {
        $query = $this->model->select(['m_users.user_name as user_name','m_corps.corp_name as corp_name'])
            ->join('m_users', 'm_users.affiliation_id', '=', 'm_corps.id')
            ->where('m_users.user_id', '=', $userId)->get()->toArray();
        return $query;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getFirstByIdNotDelFlag($id)
    {
        return $this->model->where('del_flg', '!=', 1)->find($id);
    }
}
