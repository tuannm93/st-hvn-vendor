<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BaseMCorpRepository extends SingleKeyModelRepository
{
    /**
     * @var MCorp
     */
    public $model;

    const ITEM_CATEGORY_HOLIDAY = '休業日';
    /**
     * @param array $result
     * @param integer $flag
     * @return mixed
     */
    public function orderResult(&$result, $flag)
    {
        if ($flag) {
            return $result->orderByRaw('affiliation_area_stats.commission_unit_price_category is null asc')->orderByRaw('affiliation_area_stats.commission_unit_price_category desc')->orderByRaw('affiliation_area_stats.commission_count_category desc')->get()->toarray();
        }

        return $result->orderByRaw('affiliation_infos.commission_unit_price IS NULL')->orderByRaw('affiliation_infos.commission_unit_price DESC')->orderByRaw('affiliation_infos.commission_count DESC')->get()->toarray();
    }

    /**
     * getListFieldPopup
     * @return array
     */
    public function getListFieldPopup()
    {
        $holiday = " '".self::ITEM_CATEGORY_HOLIDAY."' ";
        $fieldsHoliday = ' (SELECT ARRAY_TO_STRING(ARRAY( SELECT item_name FROM m_items INNER JOIN m_corp_subs ON m_corp_subs.item_category = ';
        $fieldsHoliday .= ' m_items.item_category AND m_corp_subs.item_id = m_items.item_id WHERE m_corp_subs.item_category = ';

        $fieldsHoliday .= $holiday.' AND m_corp_subs.corp_id = m_corps.id ORDER BY m_items.sort_order ASC ),\'｜\') as "holiday" ) ';
        $fieldsCommissionUnitPrice = ' (SELECT m_genres.targer_commission_unit_price FROM m_genres WHERE m_genres.id = m_corp_categories.genre_id) AS "targer_commission_unit_price" ';

        $fields = [
            'm_corps.id as corp_id', 'm_corps.corp_name', 'm_corps.commission_dial', 'm_corps.coordination_method', 'm_corps.mailaddress_pc',
            'm_corps.fax', 'm_corps.note', 'm_corps.support24hour',
            'm_corps.available_time_from', 'm_corps.available_time_to', 'm_corps.available_time',
            'm_corps.contactable_support24hour', 'm_corps.contactable_time_from', 'm_corps.contactable_time_to',
            'm_corps.contactable_time', 'affiliation_infos.fee', 'affiliation_infos.commission_unit_price',
            'affiliation_infos.attention', 'm_corp_categories.order_fee', 'm_corp_categories.order_fee_unit',
            'm_corp_categories.note as note_mcorp_cate', 'affiliation_stats.commission_unit_price_category as commission_unit_price_category_as',
            'affiliation_infos.commission_count', 'affiliation_infos.sf_construction_count', 'm_items.item_name', 'm_corp_categories.select_list',
            'm_corp_categories.introduce_fee', 'm_corp_categories.corp_commission_type',
            DB::raw($fieldsHoliday),
            DB::raw($fieldsCommissionUnitPrice),
            'm_corps.address1', 'm_corps.address2', 'm_corps.address3', 'affiliation_infos.attention',
            'affiliation_stats.commission_count_category as commission_count_category_as',
            'affiliation_stats.orders_count_category',
            DB::raw('(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "m_corps".id AND commission_status = 1 ) AS in_progress'),
            DB::raw('(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "m_corps".id AND commission_status = 2 ) AS in_order'),
            DB::raw('(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "m_corps".id AND commission_status = 3 ) AS complete'),
            DB::raw('(SELECT COUNT(0) FROM commission_infos WHERE corp_id = "m_corps".id AND commission_status = 4 ) AS failed'),
            'm_corp_new_years.id as new_year_id',
            'm_corp_new_years.corp_id as new_year_corp_id',
            'm_corp_new_years.label_01', 'm_corp_new_years.label_02',
            'm_corp_new_years.label_03','m_corp_new_years.label_04',
            'm_corp_new_years.label_05', 'm_corp_new_years.label_06',
            'm_corp_new_years.label_07', 'm_corp_new_years.label_08',
            'm_corp_new_years.label_09','m_corp_new_years.label_10',
            'm_corp_new_years.status_01', 'm_corp_new_years.status_02', 'm_corp_new_years.status_03',
            'm_corp_new_years.status_04', 'm_corp_new_years.status_05', 'm_corp_new_years.status_06',
            'm_corp_new_years.status_07', 'm_corp_new_years.status_08', 'm_corp_new_years.status_09',
            'm_corp_new_years.status_10', 'm_corp_new_years.note as note_new_year', 'm_corp_new_years.created',
            'm_corp_new_years.modified'
        ];
        return $fields;
    }

    /**
     * @param object $query
     * @param bool $flag
     * @param bool $isNew
     */
    public function buildQueryWherePopup(&$query, $flag, $isNew)
    {
        if ($flag && $isNew) {
            $query->where('affiliation_area_stats.commission_count_category', '<', 5);
        }

        if ($flag && ! $isNew) {
            $query->where('affiliation_area_stats.commission_count_category', '>=', 5);
        }

        if (! $flag && $isNew) {
            $query->where('affiliation_infos.commission_count', '<', 5);
        }

        if (! $flag && ! $isNew) {
            $query->where('affiliation_infos.commission_count', '>=', 5);
        }

        $query->whereRaw(' coalesce(m_corps.corp_commission_status, 0) not in (1, 2, 4, 5) ');
        $query->whereNull('affiliation_subs.affiliation_id');
        $query->whereNull('affiliation_subs.item_id');
    }

    /**
     * @param object $query
     * @param array $data
     */
    public function buildQueryWhereExcludeCorp(&$query, $data)
    {
        if(empty($data['corp_name']) && isset($data['exclude_corp_id'])){
            $excludeCorpArray = explode(",", $data['exclude_corp_id']);
            $excludeCorpId = [];
            //[1755, 3539]

            for ($i = 0; $i < count($excludeCorpArray); $i++) {
                if ($excludeCorpArray[$i] != null) {
                    $excludeCorpId[] = $excludeCorpArray[$i];
                }
            }

            if (count($excludeCorpId) >= 2) {
                $query->whereNotIn('m_corps.id', $excludeCorpId);
            } elseif (count($excludeCorpId) == 1) {
                $query->where('m_corps.id', '<>', intval($excludeCorpId[0]));
            }

        }
    }

    /**
     * Get limit popup
     *
     * @param bool $isNew
     * @param integer $count
     * @return int
     */
    public function getLimitPopup($isNew, $count)
    {
        if ($isNew) {
            $limit = 2000 - $count;
        } else {
            $limit = 1500;
        }

        return $limit;
    }

    /**
     * @return mixed
     */
    public function querySelectForSearch()
    {
        $query = $this->model->select('m_items.item_name', DB::raw(/**
            * @lang PostgreSQL text
            */
            "(SELECT ARRAY_TO_STRING(ARRAY( SELECT category_name FROM m_categories
                INNER JOIN m_corp_categories ON m_corp_categories.category_id = m_categories.id
                AND m_corp_categories.corp_id = m_corps.id ),',') AS list_category_name)"
        ));
        $query->addSelect($this->getAllTableFields('m_corps'));

        return $query;
    }

    /**
     * @param object $allCondition
     * @param Builder $query
     * @return Builder
     */
    public function querySelectJoinParam($allCondition, Builder $query)
    {
        $subQuery = $this->querySelectJoinParamSubQuery($allCondition);
        $query->leftJoin('affiliation_infos', 'm_corps.id', '=', 'affiliation_infos.corp_id')->leftJoin('m_items', function (
            $join
        ) {
            /**
             * @var JoinClause $join
             */
            $join->on('m_items.item_id', '=', 'm_corps.corp_status')->where('m_items.item_category', '=', MItemRepository::CORP_STATUS);
        })->leftJoin(DB::raw('(SELECT corp_id, min(acceptation_date) as acceptation_date
                        FROM corp_agreement
                        WHERE status = \'Complete\'
                        GROUP BY corp_id ) AS CorpAgreement'), function ($join) {
            /**
             * @var JoinClause $join
             */
            $join->on('m_corps.id', '=', DB::raw('CorpAgreement.corp_id'));
        });
        if (strlen(trim($subQuery)) > 0) {
            $query->join(DB::raw($subQuery), function ($join) use ($subQuery) {
                /**
                 * @var JoinClause $join
                 */
                if (strlen(trim($subQuery)) > 0) {
                    $join->on('m_corps.id', '=', DB::raw('MCC.corp_id'));
                }
            });
        }

        return $query;
    }

    /**
     * @param object $allCondition
     * @return string
     */
    private function querySelectJoinParamSubQuery($allCondition)
    {
        $subQuery = '';
        if (! is_null($allCondition->list_genre) && count($allCondition->list_genre) > 0) {
            $subQuery = /**
             * @lang PostgreSQL text
             */
                '(SELECT corp_id FROM m_corp_categories WHERE genre_id in ('.implode(",", $allCondition->list_genre).')';
            if (! is_null($allCondition->list_avail_pref) && count($allCondition->list_avail_pref) > 0) {
                $childSubQuery = /**
                 * @lang PostgreSQL text
                 */
                    'SELECT corp_category_id FROM m_target_areas WHERE SUBSTRING(jis_cd, 1, 2) = \''.sprintf("%02d", $allCondition->list_avail_pref[0]).'\'';
                if (count($allCondition->list_avail_pref) > 1) {
                    for ($i = 1; $i < count($allCondition->list_avail_pref); $i++) {
                        $childSubQuery .= ' OR SUBSTRING(jis_cd, 1, 2) = \''.sprintf("%02d", $allCondition->list_avail_pref[$i]).'\'';
                    }
                }
                $subQuery .= ' AND id IN ('.$childSubQuery.')';
            }
            $subQuery .= ' GROUP BY corp_id ) AS MCC';
        }

        return $subQuery;
    }

    /**
     * @param object $allCondition
     * @param Builder $query
     * @return Builder
     */
    public function querySelectWhereParam($allCondition, Builder $query)
    {
        $query = $this->queryByCheckCropId($allCondition, $query);
        $query = $this->queryByCheckCorpName($allCondition, $query);
        $query = $this->queryByCheckListPref($allCondition, $query);
        $query = $this->queryByCheckCorpPhone($allCondition, $query);
        $query = $this->queryByCheckListFreeText($allCondition, $query);
        $query = $this->queryByCheckListMedia($allCondition, $query);
        $query = $this->queryByCheckCorpFax($allCondition, $query);
        $query = $this->queryByCheckCorpPcMail($allCondition, $query);
        $query = $this->queryByCheckCorpMobileMail($allCondition, $query);
        $query = $this->queryByCheckListStatus($allCondition, $query);
        $query = $this->queryByCheckCorpRadio($allCondition, $query);
        $query = $this->queryByCheckFollowUpDate($allCondition, $query);
        $query = $this->queryByCheckList($allCondition, $query);
        $query = $this->queryByCheckSupport($allCondition, $query);
        $query->where('m_corps.del_flg', '=', 0);

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCropId($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_id) && strlen(trim($allCondition->corp_id)) > 0) {
                $where->where('m_corps.id', '=', chgSearchValue($allCondition->corp_id));
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCorpName($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_name) && strlen(trim($allCondition->corp_name)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.corp_name)'), 'like', '%' . $this->changeSearchValue($allCondition->corp_name) . '%')->orWhere(DB::raw('z2h_kana(m_corps.official_corp_name)'), 'like', '%' . $this->changeSearchValue($allCondition->corp_name) . '%');
            }
        })->where(function ($where) use ($allCondition) {
            /**
             * @var Builder $where
             */
            if (! is_null($allCondition->corp_name_kana) && strlen(trim($allCondition->corp_name_kana)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.corp_name_kana)'), 'like', '%' . $this->changeSearchValue($allCondition->corp_name_kana) . '%');
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckListPref($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->list_pref) && count($allCondition->list_pref) > 0) {
                $where->whereIn('m_corps.address1', $allCondition->list_pref);
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCorpPhone($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_phone) && strlen(trim($allCondition->corp_phone)) > 0) {
                $where->where('m_corps.commission_dial', '=', chgSearchValue($allCondition->corp_phone))->orWhere('m_corps.tel1', '=', chgSearchValue($allCondition->corp_phone))->orWhere('m_corps.tel2', '=', chgSearchValue($allCondition->corp_phone))->orWhere('m_corps.mobile_tel', '=', chgSearchValue($allCondition->corp_phone));
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckListFreeText($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->free_text_search) && strlen(trim($allCondition->free_text_search)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.note)'), 'like', '%'.chgSearchValue($allCondition->free_text_search).'%')->orWhere(DB::raw('z2h_kana(affiliation_infos.attention)'), 'like', '%'.chgSearchValue($allCondition->free_text_search).'%')->orWhere(DB::raw('z2h_kana(affiliation_infos.attention)'), 'like', '%'.chgSearchValue($allCondition->free_text_search).'%');
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckListMedia($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->listed_media) && strlen(trim($allCondition->listed_media)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.listed_media)'), 'like', '%'.chgSearchValue($allCondition->listed_media).'%');
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCorpFax($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_fax) && strlen(trim($allCondition->corp_fax)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.fax)'), '=', chgSearchValue($allCondition->corp_fax));
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCorpPcMail($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_pc_mail) && strlen(trim($allCondition->corp_pc_mail)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.mailaddress_pc)'), 'like', '%'.chgSearchValue($allCondition->corp_pc_mail).'%');
            }
        });

        return $query;
    }

    /**
     * @param $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCorpMobileMail($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_mobile_mail) && strlen(trim($allCondition->corp_mobile_mail)) > 0) {
                $where->where(DB::raw('z2h_kana(m_corps.mailaddress_mobile)'), 'like', '%'.chgSearchValue($allCondition->corp_mobile_mail).'%');
            }
        });

        return $query;
    }

    /**
     * @param $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckListStatus($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->list_status) && count($allCondition->list_status) > 0) {
                $where->whereIn('m_corps.corp_status', $allCondition->list_status);
            }
        });

        return $query;
    }

    /**
     * @param $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckCorpRadio($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->corp_radio_ju) && strlen(trim($allCondition->corp_radio_ju)) > 0) {
                $where->where('m_corps.affiliation_status', '=', $allCondition->corp_radio_ju);
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckFollowUpDate($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->from_followup_date) && strlen(trim($allCondition->from_followup_date)) > 0) {
                $where->where('m_corps.follow_date', '>=', $allCondition->from_followup_date);
            }
        })->where(function ($where) use ($allCondition) {
            /**
             * @var Builder $where
             */
            if (! is_null($allCondition->to_followup_date) && strlen(trim($allCondition->to_followup_date)) > 0) {
                $where->where('m_corps.follow_date', '<=', $allCondition->to_followup_date);
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckList($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->list_rits_person) && count($allCondition->list_rits_person) > 0) {
                $where->whereIn('m_corps.rits_person', $allCondition->list_rits_person);
            }
        })->where(function ($where) use ($allCondition) {
            /**
             * @var Builder $where
             */
            if (! is_null($allCondition->list_contract_status) && count($allCondition->list_contract_status) > 0) {
                $where->whereIn('m_corps.corp_commission_status', $allCondition->list_contract_status);
            }
        });

        return $query;
    }

    /**
     * @param object $allCondition
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryByCheckSupport($allCondition, Builder $query)
    {
        $query->where(function ($where) use ($allCondition) {
            /** @var Builder $where */
            if (! is_null($allCondition->support_24h) && strlen(trim($allCondition->support_24h)) > 0) {
                $where->where('m_corps.support24hour', '=', $allCondition->support_24h);
            }
        });

        return $query;
    }

    /**
     * @return mixed
     */
    public function querySelectForDownloadCsv()
    {
        $query = $this->model->select(DB::raw('CorpAgreement.acceptation_date'), DB::raw(/**
            * @lang PostgreSQL text
            */
            '(SELECT ARRAY_TO_STRING(ARRAY( SELECT item_name FROM m_items
                INNER JOIN m_corp_subs ON m_corp_subs.item_category = m_items.item_category
                AND m_corp_subs.item_id = m_items.item_id
                WHERE m_corp_subs.item_category = \''.__('affiliation.key_word_holiday').'\'
                AND m_corp_subs.corp_id = m_corps.id ORDER BY m_items.sort_order ASC ),\'｜\') as corp_holiday )'
        ), DB::raw('(SELECT ARRAY_TO_STRING(ARRAY( SELECT item_name FROM m_items
                INNER JOIN m_corp_subs ON m_corp_subs.item_category = m_items.item_category
                AND m_corp_subs.item_id = m_items.item_id
                WHERE m_corp_subs.item_category = \''.__('affiliation.key_word_dev_reaction').'\'
                AND m_corp_subs.corp_id = m_corps.id ORDER BY m_items.sort_order ASC ),\'｜\') as corp_dev_response )'), DB::raw('(SELECT ARRAY_TO_STRING(ARRAY( SELECT category_name FROM m_categories
                INNER JOIN affiliation_subs ON affiliation_subs.item_id = m_categories.id
                AND affiliation_subs.item_category = \''.__('affiliation.key_word_stop_category').'\'
                AND affiliation_subs.affiliation_id = "affiliation_infos"."id" ),\'｜\') as stop_category_name )'), DB::raw('(SELECT ARRAY_TO_STRING(ARRAY( SELECT category_name FROM m_categories
                INNER JOIN m_corp_categories ON m_corp_categories.category_id = m_categories.id
                AND m_corp_categories.corp_id = m_corps.id ),\'｜\') as list_category_name )'), DB::raw('(( SELECT modified as corp_mcc_modified FROM m_corp_categories
                WHERE corp_id = m_corps.id ORDER BY m_corp_categories.modified desc LIMIT 1))'), DB::raw('(( SELECT modified as corp_mct_modified FROM m_corp_target_areas
                WHERE corp_id = m_corps.id ORDER BY m_corp_target_areas.modified desc LIMIT 1))'));
        $query->addSelect($this->getAllTableFields('m_corps'));
        $query->addSelect($this->getAllTableFields('affiliation_infos'));

        return $query;
    }

    /**
     * buildQueryByExcludeCorpId
     * @param array $data
     * @param mixed $queryOrm
     * @return mixed
     */
    public function buildQueryByExcludeCorpId($data, $queryOrm)
    {
        if (isset($data['exclude_corp_id'])) {
            $excludeCorps = explode(",", $data['exclude_corp_id']);
            $excludeCorpIds = [];
            for ($i = 0; $i < count($excludeCorps); $i++) {
                if ($excludeCorps[$i] != null && $i != $data['no']) {
                    $excludeCorpIds[] = $excludeCorps[$i];
                }
            }

            $queryOrm = $queryOrm->whereNotIn('m_corps.id', $excludeCorpIds);
        }
        return $queryOrm;
    }

    /**
     * buildQueryByTargetCheckFlag
     *
     * @param boolean $targetCheckFlg
     * @param mixed $queryOrm
     * @param mixed $jisCd
     * @param mixed $prefecture
     * @return mixed
     */
    public function buildQueryByTargetCheckFlag($targetCheckFlg, $queryOrm, $jisCd, $prefecture)
    {
        if ($targetCheckFlg) {
            $queryOrm = $queryOrm->with([
                'mCorpCategory.mTargetAreas' => function ($q) use ($jisCd) {
                    $q->where('jis_cd', $jisCd);
                },
                'affiliationAreaStats' => function ($q) use ($prefecture) {
                    $q->join('m_corp_categories', 'affiliation_area_stats.genre_id', '=', 'm_corp_categories.genre_id');
                    $q->where('prefecture', $prefecture);
                },
            ]);
        }
        return $queryOrm;
    }

    /**
     * @param array $data
     * @param bool $builder
     * @return \Illuminate\Database\Eloquent\Collection|Collection|mixed|static[]
     */
    public function demandCorpData($data, $builder = false)
    {
        $db = $this->model->select('m_corps.*')->with('affiliationInfos.affiliationSubs');
        if ($builder) {
            $db = DB::table('m_corps')->select('m_corps.id', 'm_corps.auto_call_flag', 'm_corps.auction_status', 'affiliation_area_stats.commission_unit_price_category', 'affiliation_area_stats.commission_count_category', 'affiliation_area_stats.commission_unit_price_rank', 'm_corp_categories.order_fee', 'm_corp_categories.order_fee_unit', 'm_corp_categories.auction_status AS m_cate_auction_status', 'm_corp_categories.introduce_fee', 'm_corp_categories.corp_commission_type');
        }

        $result = $db->where('m_corps.del_flg', 0)->join('m_corp_categories', function ($join) use ($data) {
            $join->on('m_corps.id', '=', 'm_corp_categories.corp_id');
            $join->where('m_corp_categories.genre_id', '=', $data['genre_id']);
            $join->where('m_corp_categories.category_id', '=', $data['category_id']);
        })->join('m_target_areas', function ($join) use ($data) {
            $join->on('m_corp_categories.id', '=', 'm_target_areas.corp_category_id');
            $join->where('m_target_areas.jis_cd', '=', $data['jis_cd']);
        })->join('affiliation_infos', function ($join) {
            $join->on('affiliation_infos.corp_id', '=', 'm_corps.id');
        })->join('affiliation_area_stats', function ($join) use ($data) {
            $join->on('m_corps.id', '=', 'affiliation_area_stats.corp_id');
            $join->on('affiliation_area_stats.genre_id', '=', 'm_corp_categories.genre_id');
            $join->where('affiliation_area_stats.prefecture', '=', $data['address1']);
        })->leftJoin('affiliation_subs', function ($join) use ($data) {
            $join->on('affiliation_subs.affiliation_id', '=', 'affiliation_infos.id');
            $join->where('affiliation_subs.item_id', '=', $data['category_id']);
        })->where('affiliation_status', '=', 1)->whereRaw('coalesce(corp_commission_status, 0) not in (1, 2, 4, 5)')->whereNull('affiliation_subs.affiliation_id')->whereNull('affiliation_subs.item_id');
        if ($data['site_id'] == 585) {
            // In case of living ambulance cases, if JBR compliance status is "not compliant", make it out of auction subjects
            $result = $result->where('jbr_available_status', '=', 2);
        }

        return $result->get();
    }

    /**
     * Change search value
     * @param string $val
     * @return string
     */
    private function changeSearchValue($val)
    {
        $rows = DB::select('select z2h_kana(:val)', ['val' => $val]);
        $result = $rows[0]->z2h_kana;

        return $result;
    }
}
