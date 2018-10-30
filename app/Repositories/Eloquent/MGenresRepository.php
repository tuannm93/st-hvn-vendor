<?php

namespace App\Repositories\Eloquent;

use App\Models\MGenre;
use App\Repositories\MGenresRepositoryInterface;
use DB;
use Illuminate\Database\Query\JoinClause;
use Auth;

class MGenresRepository extends SingleKeyModelRepository implements MGenresRepositoryInterface
{
    /**
     * @var MGenre
     */
    protected $model;

    /**
     * MGenresRepository constructor.
     *
     * @param MGenre $model
     */
    public function __construct(MGenre $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MGenre|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new  MGenre();
    }


    /**
     * get list data for addition form
     * @param array $whereCondition
     * @return mixed
     */
    public function getListForAdditionForm($whereCondition)
    {
        return $this->model->where($whereCondition)->orderBy('genre_kana', 'asc');
    }

    /**
     * get list genres function
     *
     * @param  boolean $validFlg
     * @param  boolean $useExclusionFlg
     * @return array
     */
    public function getList($validFlg = false, $useExclusionFlg = false)
    {
        $conditions = [];
        if ($validFlg == true) {
            array_push($conditions, ['valid_flg', '=', 1]);
        }

        if ($useExclusionFlg == true) {
            array_push($conditions, ['exclusion_flg', '=', '0']);
        }
        $list = MGenre::where($conditions)->orderBy('genre_kana', 'asc')->get()->toarray();
        $results = [];
        foreach ($list as $val) {
            $results[$val['id']] = $val['genre_name'];
        }
        return $results;
    }

    /*
     * get list genres
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  array $condition
     * @return collection   list genres odered by genre_kana
     */
    /**
     * @param array $condition
     * @return \Illuminate\Support\Collection
     */
    public function getListSelectBox($condition)
    {
        return $this->model->where($condition)
            ->orderBy('genre_kana', 'ASC')
            ->pluck('genre_name', 'id');
    }

    /**
     * Get list genres
     * @author thaihv Thai.HoangVan@nashtechglobal.com
     * @param  array $ids
     * @return \Illuminate\Support\Collection
     */
    public function getGenresByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->select('id', 'genre_name')->orderBy('genre_name', 'ASC')->get();
    }

    /**
     * edit genres
     * @param array $data
     * @return boolean
     */
    public function editGenre($data)
    {
        $delIdArray = [];
        $saveData = [];
        $options = ['fields' => ['id', 'credit_unit_price', 'auction_fee', 'auto_call_flag', 'registration_mediation']];
        $chkId = $data['chk_id'];
        if (strlen($chkId) > 0) {
            $delIdArray = explode('-', $chkId);
        }
        $allGenre = $this->getAll($options);
        foreach ($data ['data']['MGenre'] as $v) {
            if (!isset($v['id']) || !isset($v['credit_unit_price'])) {
                continue;
            }
            foreach ($delIdArray as $value) {
                if ($value == $v['id']) {
                    if (!isset($v['registration_mediation'])) {
                        $v['registration_mediation'] = 0;
                    }
                    break;
                }
            }
            $this->getDataSave($saveData, $allGenre, $v);
        }
        return $this->saveData($saveData);
    }

    /**
     * get data save
     * @param  array $saveData
     * @param  array $allGenre
     * @param  array $delItem
     * @return void
     */
    public function getDataSave(&$saveData, $allGenre, $delItem)
    {
        foreach ($allGenre as $value) {
            if ($value['id'] == $delItem['id']) {
                if ($value['credit_unit_price'] != $delItem['credit_unit_price']
                    || $value['auto_call_flag'] != $delItem['auto_call_flag']
                    || (isset($delItem['registration_mediation']) && $delItem['registration_mediation'] != $value['registration_mediation'])
                ) {
                    $saveData[] = [
                        'id' => $delItem['id'],
                        'credit_unit_price' => $delItem['credit_unit_price'],
                        'auto_call_flag' => $delItem['auto_call_flag'],
                        'modified' => date('Y-m-d H:i:s'),
                        'modified_user_id' => Auth::user()->user_id,
                        'registration_mediation' => isset($delItem['registration_mediation']) ? $delItem['registration_mediation'] : $value['registration_mediation']
                    ];
                }
            }
        }
    }

    /**
     * save data
     * @param  array $saveData
     * @return boolean
     */
    public function saveData($saveData)
    {
        foreach ($saveData as $data) {
            try {
                DB::beginTransaction();
                DB::table('m_genres')->where('id', $data['id'])->update($data);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    /**
     * get all genres order
     *
     * @param  array $options
     * @return array
     */
    public function getAll($options = [])
    {
        $result = $this->model;
        if (isset($options['fields'])) {
            $result = $result->select($options['fields']);
        }
        $result = $result->orderBy('genre_group', 'ASC')->orderBy('genre_name', 'ASC')
            ->orderBy('id', 'ASC')->get()->toarray();
        return $result;
    }

    /**
     * get m_genre.id, m_genre.name in m_genres and select_genres where select_genres.select_type in [2,3,4] and
     * m_genres.valid_flg = 1
     *
     * @author ducnguyent3 Duc.NguyenTai@nashtechglobal.com
     *
     * @return mixed
     */
    public function getListBySelectType($selectType = [2,3,4])
    {
        $list = $this->model->select(
            'm_genres.genre_name as name',
            'm_genres.id as id',
            'select_genres.select_type as select_type',
            'm_genres.valid_flg as valid_flg'
        )
            ->join('select_genres', function ($join) use ($selectType) {
                /** @var JoinClause $join */
                $join->on('m_genres.id', '=', 'select_genres.genre_id')
                    ->whereIn('select_genres.select_type', $selectType);
            })
            ->where('m_genres.valid_flg', '=', 1)
            ->distinct()->orderBy('m_genres.genre_name')->get()->toArray();
        return $list;
    }


    /**
     * @param array $arrGenreId
     * @param array $arrCategoryId
     * @return MGenre|MGenresRepository
     */
    public function queryListGenreRelated($arrGenreId = [], $arrCategoryId = [])
    {
        $query = $this->model->join('m_categories', 'm_categories.genre_id', '=', 'm_genres.id')
            ->leftJoin('select_genres', 'select_genres.genre_id', '=', 'm_genres.id')
            ->where('m_genres.valid_flg', 1)
            ->where('m_categories.disable_flg', false)
            ->orderBy('m_genres.genre_kana', 'ASC')
            ->orderBy('m_categories.id', 'ASC');

        if (!empty($arrGenreId)) {
            $query = $query->whereIn('m_genres.id', $arrGenreId);
        }
        if (!empty($arrCategoryId)) {
            $query = $query->whereIn('m_categories.id', $arrCategoryId);
        }

        return $query;
    }

    /**
     * get name by id
     * @param  integer|null $genreId
     * @return string|array
     */
    public function getNameById($genreId = null)
    {
        $result = $this->model->select('genre_name')->where('id', '=', $genreId)->get()->toarray();
        if (empty($result)) {
            $result = '';
        } else {
            $result = $result[0]['genre_name'];
        }
        return $result;
    }

    /**
     * get mediation genre list
     * @param integer $registrationMediation
     * @return array|mixed
     */
    public function getListByMediation($registrationMediation = 1)
    {
        return $this->model->select('*')
            ->where('registration_mediation', '=', $registrationMediation)->get()->toArray();
    }

    /**
     * get corp_categories data by corpId and genreId
     * @param integer $corpId
     * @param integer $genreId
     * @return array|mixed
     */
    public function getListByCorpIdAndGenreId($corpId, $genreId)
    {
        return $this->model->select(
            'm_genres.id AS MGenres__id',
            'MCorpCategory.id AS MCorpCategory__id',
            'MCorpCategory.genre_id AS MCorpCategory__genre_id',
            'MCorpCategory.category_id AS MCorpCategory__category_id'
        )
            ->leftJoin('m_corp_categories AS MCorpCategory', function ($join) use ($corpId) {
                $join->on('MCorpCategory.genre_id', '=', 'm_genres.id');
                $join->where('MCorpCategory.corp_id', '=', $corpId);
            })
            ->where('m_genres.id', $genreId)
            ->groupBy(
                'm_genres.id',
                'MCorpCategory.id'
            )->get()->toArray();
    }

    /**
     * find all data for affiliation
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllForAffiliation()
    {
        $result = $this->model
            ->leftJoin('m_categories', 'm_genres.id', '=', 'm_categories.genre_id')
            ->where('m_categories.hide_flg', 0)
            ->where('m_genres.valid_flg', 1)
            ->orderBy('m_genres.id', 'asc')->orderBy('m_categories.id', 'asc')->get();
        return $result;
    }

    /**
     * get commission unit price
     * @param array $ids
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getCommissionUnitPrice($ids)
    {
        return $this->model->whereIn('id', $ids)
            ->select('targer_commission_unit_price')
            ->get();
    }
    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @param integer $siteId
     * @return boolean|\Illuminate\Config\Repository|mixed
     */
    public function getListForDropDown($siteId)
    {
        return config('constant.defaultOption') + $this->model->where('m_site_genres.site_id', $siteId)
            ->join(
                'm_site_genres',
                function ($join) use ($siteId) {
                        $join->on('m_site_genres.genre_id', '=', 'm_genres.id')->where(
                            function ($where) use ($siteId) {
                                $where->where('m_genres.st_hide_flg', 0);
                            }
                        );
                }
            )->orderBy('id', 'asc')->pluck('m_genres.genre_name', 'm_genres.id')->toArray();
    }

    /**
     * find first data
     * @param integer $id
     * @return mixed|static
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * get selection genres data
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getSelectionGenre()
    {
        $query = $this->model
            ->leftJoin('select_genres', 'select_genres.genre_id', '=', 'm_genres.id')
            ->select(
                'select_genres.id',
                'select_genres.select_type',
                'm_genres.genre_name',
                'm_genres.id as genre_id'
            )
            ->where('m_genres.valid_flg', 1)
            ->orderBy('m_genres.genre_name')
            ->get();

        return $query;
    }

    /**
     * get genres data
     * @param array $condition
     * @param array $orderBy
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getGenreWithConditions($condition = [], $orderBy = [])
    {
        $query = $this->model;
        if (count($condition) > 0) {
            foreach ($condition as $key => $value) {
                $query = $query->where($key, $value);
            }
        }

        if (count($orderBy) > 0) {
            foreach ($orderBy as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        $results = $query->get();
        return $results;
    }

    /**
     * update exclusion flag
     * @param integer $id
     * @param bool $flag
     * @return mixed|static
     */
    public function updateExclusionFlg($id, $flag)
    {
        $update = $this->model->find($id);
        $update->exclusion_flg = $flag;
        $update->modified_user_id = Auth::user()->user_id;
        $update->modified = date('Y-m-d H:i:s');
        $update->save();

        return $update;
    }

    /**
     * get genre data
     * @param integer $corpId
     * @param string $developmentGroup
     * @return array|mixed
     */
    public function getMGenreByCorpIdAnDevelopmentGroup($corpId, $developmentGroup)
    {
        return $this->model->select('m_genres.id AS MGenre__id')
            ->join(
                'm_corp_categories AS MCorpCategory',
                function ($joins) use ($corpId, $developmentGroup) {
                    $joins->on('MCorpCategory.genre_id', '=', 'm_genres.id');
                    $joins->where('m_genres.development_group', '=', $developmentGroup);
                    $joins->where('MCorpCategory.corp_id', '=', $corpId);
                }
            )->get()->toArray();
    }

    /**
     * get name by id
     *
     * @param  integer $id
     * @return string
     */
    public function getGenreNameById($id)
    {
        $item = $this->model
            ->where('id', $id)
            ->orderBy('genre_name', 'desc')
            ->first();
        return $item ? $item->genre_name : '';
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @return string
     */
    public function getListText($id)
    {
        $results = $this->model->where("id", $id)->orderBy("genre_name", "asc")->get();
        $name = "";
        foreach ($results as $result) {
            if ($result->id == $id) {
                $name = $result->genre_name;
            }
        }
        return $name;
    }
}
