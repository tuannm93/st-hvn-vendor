<?php

namespace App\Repositories\Eloquent;

use App\Models\MCategory;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class MCategoryRepository extends SingleKeyModelRepository implements MCategoryRepositoryInterface
{
    /**
     * @var MCategory
     */
    protected $model;

    /**
     * @var MSiteRepositoryInterface
     */
    protected $mSite;

    /**
     * MCategoryRepository constructor.
     *
     * @param MCategory                $model
     * @param MSiteRepositoryInterface $mSite
     */
    public function __construct(MCategory $model, MSiteRepositoryInterface $mSite)
    {
        $this->model = $model;
        $this->mSite = $mSite;
    }

    /**
     * @return \App\Models\Base|MCategory|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCategory();
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
     * count by category id and genre id
     *
     * @param  integer $categoryId
     * @param  integer $genreId
     * @return integer
     */
    public function countByCategoryIdAndGenreId($categoryId, $genreId)
    {
        return $this->model
            ->join('m_genres', 'm_genres.id', '=', 'm_categories.genre_id')
            ->where('m_categories.id', $categoryId)
            ->where('m_categories.genre_id', $genreId)
            ->count();
    }

    /**
     * @param integer $corpId
     * @return object
     */
    public function getFirstByCorpId($corpId)
    {
        return $this->model->where('id', $corpId)->first();
    }

    /**
     * @param integer $id
     * @param integer $genreId
     * @return mixed
     */
    public function getCount($id, $genreId)
    {
        return $this->model->join('m_genres', 'm_genres.id', '=', 'm_categories.genre_id')
            ->where([
                ['m_categories.id', $id],
                ['m_categories.genre_id', $genreId]
             ])
            ->count();
    }

    /**
     * get list function
     * @param null $genreId
     * @param bool $isAllCategory
     * @return array
     */
    public function getList($genreId = null, $isAllCategory = false)
    {
        $conditions = [];
        if (!empty($genreId)) {
            array_push($conditions, ['genre_id', '=', $genreId]);
        }

        if (!$isAllCategory) {
            array_push($conditions, ['disable_flg', '=', false]);
        }
        $list = MCategory::where($conditions)->orderBy('id', 'asc')->get()->toarray();
        $results = [];
        foreach ($list as $val) {
            $results[$val['id']] = $val['category_name'];
        }
        return $results;
    }

    /**
     * @param integer $genreId
     * @param bool $isAllCategory
     * @return mixed
     */
    public function getDropListCategory($genreId = null, $isAllCategory = false)
    {
        $result = $this->model->orderBy('id', 'asc');
        if (!empty($genreId)) {
            $result->where('genre_id', $genreId);
        }
        if (!$isAllCategory) {
            $result->where('disable_flg', false);
        }
        $results = $result->pluck('category_name', 'id');
        return $results;
    }

    /**
     * @param integer $id
     * @return mixed|string
     */
    public function getListText($id)
    {
        $list = $this->model->where('id', $id)
            ->orderBy('id', 'asc')
            ->get()->toarray();

        $name = '';
        foreach ($list as $val) {
            if ($id == $val['id']) {
                $name = $val['category_name'];
            }
        }

        return $name;
    }

    /**
     * get default fee function
     * @param integer $id
     * @return array|mixed
     */
    public function getDefaultFee($id)
    {
        $categoryDefaultFee = [];
        if ($id != '') {
            $mCategory = $this->model->where('id', $id)->first();
            if ($mCategory) {
                $mCategory = $mCategory->toArray();
            }
        }

        if (!empty($mCategory)) {
            $categoryDefaultFee['category_default_fee'] = $mCategory['category_default_fee'];
            $categoryDefaultFee['category_default_fee_unit'] = $mCategory['category_default_fee_unit'];
        }

        return $categoryDefaultFee;
    }

    /**
     * @param integer $id
     * @return mixed|string
     */
    public function getCommissionType($id)
    {
        $category = $this->model->join('m_genres', function ($join) {
            $join->on('m_genres.id', '=', 'm_categories.genre_id');
        })->where('m_categories.id', $id)->select('m_genres.commission_type')->first()->toArray();

        if (!empty($category)) {
            return $category['commission_type'];
        }

        return '';
    }


    /**
     * Acquisition of Contract STOP category
     *
     * @param integer $corpId
     * @return \Illuminate\Support\Collection
     */
    public function getStopCategoryList($corpId)
    {
        $stopText = $this->model::STOP_CATEGORY;

        $results = $this->model->leftJoin(
            'affiliation_infos',
            function ($join) use ($corpId) {
                $join->where('affiliation_infos.corp_id', $corpId);
            }
        )->leftJoin(
            'affiliation_subs',
            function ($join) use ($stopText) {
                    $join->on('affiliation_subs.affiliation_id', '=', 'affiliation_infos.id')
                        ->on('affiliation_subs.item_id', '=', 'm_categories.id')
                        ->where('affiliation_subs.item_category', '=', $stopText);
            }
        )
            ->whereNull('affiliation_subs.id')
            ->orderBy('m_categories.id', 'asc')
            ->pluck('m_categories.category_name', 'm_categories.id');

        return $results;
    }

    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getFeeData($id)
    {
        $result = $this->model->selectRaw('category_default_fee, category_default_fee_unit')
            ->where('id', $id)
            ->first();
        return $result;
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllForAffiliation()
    {
        $result = $this->model->select('m_categories.id as m_category_id', 'm_categories.*', 'm_genres.*')
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm_categories.genre_id')
            ->where('m_categories.hide_flg', 0)
            ->where('m_genres.valid_flg', 1)
            ->where('m_categories.disable_flg', false)->orderBy('m_categories.id', 'asc')->get();
        return $result;
    }


    /**
     * @author Dung.PhamVan@nashtechglobal.com
     * @param integer $genreId
     * @return mixed
     */
    public function getListCategoriesForDropDown($genreId = null)
    {
        $query = $this->model->where('disable_flg', false)->orderBy('id', 'asc');
        if ($genreId != null) {
            $query->where('genre_id', $genreId);
        }

        return config('constant.defaultOption') + $query->where('disable_flg', false)->orderBy('id', 'asc')
            ->pluck('category_name', 'id')->toArray();
    }

    /**
     * @param integer $genreId
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryByGenreId($genreId)
    {
        return $this->model->where('genre_id', $genreId)->pluck('category_name', 'id');
    }

    /**
     * @param integer $corpId
     * @param integer $tempId
     * @return array|mixed
     */
    public function getAllTempCategory($corpId, $tempId)
    {
        $allColumnTableCategory = $this->getAllTableFields('m_categories');
        $query = $this->model->select($allColumnTableCategory)
            ->addSelect(
                'm_genres.id as m_genres_id',
                'm_genres.genre_name as m_genres_genre_name',
                'm_genres.commission_type as m_genres_commission_type',
                'm_genres.genre_group as m_genres_genre_group',
                'm_genres.default_fee as m_genres_default_fee',
                'm_genres.default_fee_unit as m_genres_default_fee_unit',
                'm_genres.registration_mediation as m_genres_registration_mediation',
                'm_corp_categories_temp.id as m_corp_categories_temp_id',
                'm_corp_categories_temp.modified as m_corp_categories_temp_modified',
                'm_corp_categories_temp.select_list as m_corp_categories_temp_select_list',
                'm_corp_categories_temp.corp_commission_type as m_corp_categories_temp_corp_commission_type'
            )
            ->addSelect(
                DB::raw(
                    '(select m_sites.commission_type
                    from m_site_genres
                    inner join m_sites
                    on m_site_genres.site_id = m_sites.id
                    where m_site_genres.genre_id = m_genres.id
                    order by m_sites.id limit 1) AS "m_sites_commission_type"'
                )
            )
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm_categories.genre_id')
            ->leftJoin(
                'm_corp_categories_temp',
                function ($join) use ($corpId, $tempId) {
                    /**
                * @var JoinClause $join
                */
                    $join->on('m_corp_categories_temp.genre_id', '=', 'm_categories.genre_id')
                        ->on('m_corp_categories_temp.category_id', '=', 'm_categories.id')
                        ->where('m_corp_categories_temp.corp_id', '=', $corpId)
                        ->where('m_corp_categories_temp.temp_id', '=', $tempId)
                        ->where('m_corp_categories_temp.delete_flag', '=', false);
                }
            )
            ->where('m_genres.valid_flg', '=', 1)
            ->where('m_categories.hide_flg', '=', 0)
            ->where('m_categories.disable_flg', '=', false)
            ->orderBy('m_genres.genre_group', 'asc')
            ->orderBy('m_categories.display_order', 'asc')
            ->get()->toArray();
        return $query;
    }

    /**
     * @param integer $corpId
     * @return array|mixed
     */
    public function getAllCategoryByCorpId($corpId)
    {
        $allColumnTableCategory = $this->getAllTableFields('m_categories');
        $query = $this->model->select($allColumnTableCategory)
            ->addSelect(
                'm_genres.id as m_genres_id',
                'm_genres.genre_name as m_genres_genre_name',
                'm_genres.commission_type as m_genres_commission_type',
                'm_genres.genre_group as m_genres_genre_group',
                'm_genres.default_fee as m_genres_default_fee',
                'm_genres.default_fee_unit as m_genres_default_fee_unit',
                'm_genres.registration_mediation as m_genres_registration_mediation',
                'm_corp_categories.id as m_corp_categories_id',
                'm_corp_categories.modified as m_corp_categories_modified',
                'm_corp_categories.select_list as m_corp_categories_select_list'
            )
            ->addSelect(
                DB::raw(
                    '(select m_sites.commission_type
                        from m_site_genres
                        inner join m_sites on m_site_genres.site_id = m_sites.id
                        where m_site_genres.genre_id = m_genres.id
                        order by m_sites.id limit 1) AS "m_sites_commission_type"'
                )
            )
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm_categories.genre_id')
            ->leftJoin(
                'm_corp_categories',
                function ($join) use ($corpId) {
                    /**
                * @var JoinClause $join
                */
                    $join->on('m_corp_categories.genre_id', '=', 'm_categories.genre_id')
                        ->on('m_corp_categories.category_id', '=', 'm_categories.id')
                        ->where('m_corp_categories.corp_id', '=', $corpId);
                }
            )
            ->where('m_genres.valid_flg', '=', 1)
            ->where('m_categories.hide_flg', '=', 0)
            ->orderBy('m_genres.genre_group', 'asc')
            ->orderBy('m_categories.display_order', 'asc')
            ->get()->toArray();
        return $query;
    }

    /**
     * @param null $genreId
     * @param bool $isAllCategory
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getListStHide($genreId = null, $isAllCategory = false)
    {
        $query = $this->model->select('category_name', 'id')->orderBy('id', 'asc');
        if ($genreId) {
            $query->where('genre_id', $genreId);
        }
        if (!$isAllCategory) {
            $query->where('disable_flg', false);
        }

        return $query->where('st_hide_flg', 0)->pluck('category_name', 'id');
    }

    /**
     * @param integer $listCopyCategoriesId
     * @return array|mixed
     */
    public function findAllByCopyCategoryIds($listCopyCategoriesId)
    {
        $query = $this->model->select('*', 'm_genres.commission_type')
            ->leftJoin('m_genres', 'm_categories.genre_id', '=', 'm_genres.id')
            ->where(
                function ($where) use ($listCopyCategoriesId) {
                    /**
                * @var Builder $where
                */
                    if (!isset($listCopyCategoriesId) || count($listCopyCategoriesId) == 0) {
                        $where->whereNull('m_categories.id');
                    } else {
                        $where->whereIn('m_categories.id', $listCopyCategoriesId);
                    }
                }
            )->get()->toArray();
        return $query;
    }

    /**
     * @param integer $categoryId
     * @return mixed|static
     */
    public function getFeeDataCategories($categoryId)
    {
        return $this->model->select('category_default_fee_unit', 'category_default_fee')->find($categoryId);
    }

    /**
     * @auth dungpv
     * @param integer $siteId
     * @return \Illuminate\Support\Collection
     */
    public function getCategoriesBySite($siteId)
    {
        $query = $this->model->join('m_site_categories', 'm_site_categories.category_id', '=', 'm_categories.id');

        $mSite = $this->mSite->findById($siteId);
        if ($mSite && $mSite->cross_site_flg != 1) {
            $query->where('m_site_categories.site_id', $siteId);
        }

        return $query->orderBy('m_categories.id', 'asc')->pluck('m_categories.category_name', 'm_categories.id');
    }

    /**
     * @param $categoryId
     * @return array|mixed|string
     */
    public function getNameById($categoryId)
    {
        $result = $this->model->select('category_name')->where('id', '=', $categoryId)->get()->toarray();
        if (empty($result)) {
            $result = '';
        } else {
            $result = $result[0]['category_name'];
        }
        return $result;
    }
}
