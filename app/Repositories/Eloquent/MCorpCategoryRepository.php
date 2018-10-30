<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorpCategory;
use App\Repositories\MCorpCategoryRepositoryInterface;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class MCorpCategoryRepository extends SingleKeyModelRepository implements MCorpCategoryRepositoryInterface
{
    /**
     * @var MCorpCategory
     */
    protected $model;

    /**
     * MCorpCategoryRepository constructor.
     *
     * @param MCorpCategory $model
     */
    public function __construct(MCorpCategory $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MCorpCategory|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorpCategory();
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
     * count area corp list by corp id
     *
     * @param  integer $corpId
     * @return integer
     */
    public function getCountAreaCorpListByCorpId($corpId)
    {
        $result = $this->model
            ->select('m_corp_categories.corp_id', 'm_corp_categories.category_id', 'auto_commission_corp.pref')
            ->where('m_corp_categories.corp_id', $corpId)
            ->join('m_target_areas', 'm_corp_categories.id', '=', 'm_target_areas.corp_category_id')
            ->join(
                DB::raw('(SELECT corp_id, category_id, SUBSTRING(jis_cd, 1, 2) as pref FROM auto_commission_corp) AS "auto_commission_corp"'),
                function ($join) {
                    $join->on('m_corp_categories.corp_id', '=', 'auto_commission_corp.corp_id');
                    $join->on('m_corp_categories.category_id', '=', 'auto_commission_corp.category_id');
                }
            )
            ->distinct()
            ->get();
        return count($result);
    }

    /**
     * get list id by corp id
     *
     * @param  integer $corpId
     * @return array
     */
    public function getListIdByCorpId($corpId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->pluck('id')
            ->toArray();
    }

    /**
     * update many item with array
     *
     * @param  array $arrayData
     * @return boolean
     */
    public function updateManyItemWithArray($arrayData)
    {
        foreach ($arrayData as $value) {
            if ($value['id'] != '') {
                $id = $value['id'];
                unset($value['id']);
                $status = $this->model
                    ->where('id', $id)
                    ->update($value);
            } else {
                unset($value['id']);
                $status = $this->model->insert($value);
            }
            if (!$status) {
                return false;
            }
        }
        return true;
    }

    /**
     * get mcorp category id list by corp id
     *
     * @param  integer $corpId
     * @return array object
     */
    public function getCorpCategoryIdListByCorpId($corpId)
    {
        return $this->model
            ->select('m_corp_categories.id', 'm_corp_categories.genre_id', 'm_target_areas.corp_category_id')
            ->leftJoin('m_target_areas', 'm_target_areas.corp_category_id', '=', 'm_corp_categories.id')
            ->where('corp_id', $corpId)
            ->get();
    }

    /**
     * get all mcorp category by corp id and gener id
     *
     * @param  integer $corpId
     * @param  integer $genreId
     * @return array object
     */
    public function getAllByCorpIdAndGenreId($corpId, $genreId)
    {
        return $this->model
            ->select('id', 'target_area_type')
            ->where('corp_id', $corpId)
            ->where('genre_id', $genreId)
            ->get();
    }

    /**
     * update corp category target area type
     *
     * @param  integer $corpId
     * @param  integer $type
     * @return void
     */
    public function updateCorpCategoryTargetAreaType($corpId, $type)
    {
        $this->model
            ->find($corpId)
            ->update(['target_area_type' => $type]);
    }

    /**
     * get all by affiliation status and id
     *
     * @param  integer $corpId
     * @return array object
     */
    public function getListByCorpIdAndAffiliationStatus($corpId, $affiliationStatus = 1)
    {
        return $this->model
            ->select('m_corps.id', 'm_corp_categories.genre_id')
            ->join('m_corps', 'm_corps.id', '=', 'm_corp_categories.corp_id')
            ->where('m_corps.affiliation_status', $affiliationStatus)
            ->where('m_corps.id', $corpId)
            ->get();
    }

    /**
     * get array field category
     *
     * @return array
     */
    public function getArrayFieldCategory()
    {
        return $this->model->getArrayFieldCategory();
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
     * @param integer $corpId
     * @param boolean $toArray
     * @return mixed
     */
    public function getListByCorpId($corpId = null, $toArray = true)
    {
        $list = $this->model->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories.category_id')
            ->select(
                'm_corp_categories.*',
                'm_genres.id AS m_genres_id',
                'm_genres.genre_name',
                'm_genres.commission_type',
                'm_categories.category_name',
                'm_categories.hide_flg',
                'm_categories.disable_flg',
                DB::raw(
                    '(select m_sites.commission_type from m_site_genres INNER JOIN m_sites on
						m_site_genres.site_id = m_sites.id where m_site_genres.genre_id = m_genres.id
						 order by m_sites.id limit 1) AS m_sites_commission_type'
                )
            )
            ->where('m_corp_categories.corp_id', '=', $corpId)
            ->orderBy('m_categories.category_name', 'asc')
            ->get();
        if ($toArray) {
            $list = $list->toArray();
        }
        return $list;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getByCorpId($corpId)
    {
        return $this->model->where('corp_id', $corpId)
            ->select('id')
            ->get();
    }

    /**
     * @param array $ids
     * @return boolean
     * @throws \Exception
     */
    public function deleteById($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
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
     * @param integer $corpId
     * @return array
     */
    public function getListForIdByCorpId($corpId = null)
    {
        return $this->model->where('corp_id', $corpId)->select('id', 'genre_id')->get()->toArray();
    }

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @return array
     */
    public function getListForIdByCorpIdAndGenreId($corpId = null, $genreId = null)
    {
        return $this->model->where(
            [
                ['corp_id', $corpId],
                ['genre_id', $genreId]
            ]
        )->select('id', 'target_area_type')->get()->toArray();
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function getListByIdAndAffiliationStatus($id, $affiliationStatus = 1)
    {
        return $this->model->where(
            [
                ['id', $id],
                ['affiliation_status', $affiliationStatus]
            ]
        )->select('id', 'genre_id')->get()->toArray();
    }

    /**
     * get list corp category by id function
     *
     * @param  integer $id
     * @return array
     */
    public function getCorpSelectGenreList($id = null)
    {
        $result = MCorpCategory::select('m_genres.id', 'm_genres.genre_name')
            ->leftjoin('m_genres', 'm_genres.id', '=', 'm_corp_categories.genre_id')
            ->where('m_corp_categories.corp_id', '=', $id)
            ->groupBy('m_corp_categories.genre_id', 'm_genres.id')
            ->orderBy('m_genres.genre_group', 'asc')->orderBy('m_genres.genre_name', 'asc')
            ->get()->toarray();
        return $result;
    }

    /**
     * get first id by id function
     *
     * @param  integer $corpId
     * @param  integer $genreId
     * @return array
     */
    public function getIdByCorpIdAndGenreId($corpId = null, $genreId = null)
    {
        $result = MCorpCategory::select('id')
            ->where('corp_id', '=', $corpId)
            ->where('genre_id', '=', $genreId)->first()->toarray();
        return $result;
    }

    /**
     * get save corp category function
     *
     * @param  array $data
     * @return void
     * @throws \Exception
     */
    public function saveCorpCategory($data)
    {
        DB::beginTransaction();
        try {
            MCorpCategory::where('id', $data['id'])->update(
                ['target_area_type' => $data['target_area_type']]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getGenresByCorpId($corpId)
    {
        return $this->model->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories.genre_id')
            ->where('m_corp_categories.corp_id', $corpId)
            ->select('m_genres.genre_name', 'm_genres.id as m_genres_id')
            ->distinct('m_genres_id')
            ->orderBy('m_genres.id', 'asc')->get();
    }

    /**
     * @param integer $genreId
     * @param integer $corpId
     * @return mixed
     */
    public function getCategoriesByGenreIdCorpId($genreId, $corpId)
    {
        return $this->model->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories.category_id')
            ->where(
                [
                    ['m_corp_categories.corp_id', $corpId],
                    ['m_corp_categories.genre_id', $genreId]
                ]
            )
            ->select('m_categories.category_name', 'm_categories.id as m_category_id')
            ->distinct('m_categories_id')
            ->orderBy('m_categories.id', 'asc')->get();
    }

    /**
     * count by corp id and genre id
     *
     * @param  integer $corpId
     * @param  array $genreIds
     * @return integer
     */
    public function countByCorpIdAndGenreId($corpId, $genreIds)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->whereIn('genre_id', $genreIds)
            ->count();
    }

    /**
     * @param integer $corpId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllByCorpId($corpId)
    {
        $list = $this->model->select(
            'm_corp_categories.id as m_corp_category_id',
            'm_corp_categories.note as m_corp_categories_temp_note',
            'm_corp_categories.*',
            'm_genres.*',
            'm_categories.*'
        )
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm_corp_categories.genre_id')
            ->leftJoin('m_categories', 'm_categories.id', '=', 'm_corp_categories.category_id')
            ->where(
                [
                    ['m_corp_categories.corp_id', '=', $corpId],
                ]
            )
            ->orderBy('m_categories.category_name', 'asc')
            ->get();
        return $list;
    }

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @param null $categoryId
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findByCorpIdAndGenreIdAndCategoryId($corpId, $genreId, $categoryId = null)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('genre_id', $genreId)
            ->where('category_id', $categoryId)
            ->first();
    }

    /**
     * @param integer $corpId
     * @param integer $genreId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllByCorpIdAndGenreId($corpId, $genreId)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->where('genre_id', $genreId)
            ->get();
    }

    /**
     * Get last update category information of mCorp by corpId
     *
     * @param integer $corpId
     * @param  array $columns
     * @param  array $order
     * @return mixed
     */
    public function getLastByCorpId($corpId, $columns = ['*'], $order = ['column' => 'id', 'dir' => 'desc'])
    {
        return $this->model->join('m_corps', 'm_corps.id', '=', 'm_corp_categories.corp_id')
            ->where('m_corp_categories.corp_id', $corpId)
            ->where('m_corps.del_flg', 0)
            ->orderBy($order['column'], $order['dir'])->first($columns);
    }

    /**
     * Count total category of mCorp
     *
     * @param integer $corpId
     * @return mixed
     */
    public function getCountByCorpId($corpId)
    {
        return $this->model->where('corp_id', $corpId)->count('id');
    }

    /**
     * @param integer $corpId
     * @return array|mixed|void
     */
    public function getListForGenreAndCategoryByCorpId($corpId)
    {
        $result = $this->model->select(
            'm_corp_categories.corp_id',
            'm_corp_categories.genre_id',
            'm_genres.genre_name',
            'm_categories.category_name'
        )
            ->leftjoin('m_genres', 'm_genres.id', '=', 'm_corp_categories.genre_id')
            ->leftjoin('m_categories', 'm_categories.id', '=', 'm_corp_categories.category_id')
            ->where('m_corp_categories.id', '=', $corpId)->first();

        return $result ? $result->toarray() : [];
    }

    /**
     * @param integer $corpId
     * @param integer $type
     * @return bool|mixed
     */
    public function editCorpCategoryTargetAreaType($corpId, $type)
    {
        try {
            DB::beginTransaction();
            $this->model->where('id', $corpId)->update(
                ['target_area_type' => $type]
            );
            DB::table('m_target_areas')->where('corp_category_id', $corpId)->update(
                ['modified' => date('Y-m-d H:i:s')]
            );
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param integer $affiliationId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findByAffiliationId($affiliationId)
    {
        $fields = $this->getAllTableFieldsByAlias('m_corp_categories', 'MCorpCategory');
        $mCorpFields = $this->getAllTableFieldsByAlias('m_corps', 'MCorp');
        $result = $this->model->from('m_corp_categories AS MCorpCategory')
            ->join(
                'm_corps AS MCorp',
                function ($join) {
                    $join->on('MCorpCategory.corp_id', '=', 'MCorp.id')
                        ->where('MCorp.del_flg', 0);
                }
            )
            ->where('MCorpCategory.corp_id', $affiliationId)
            ->select($fields)
            ->addSelect($mCorpFields)
            ->get();

        return $result;
    }

    /**
     * Get list category_name an address for page target_area/{$corpId}
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @return mixed
     */
    public function getByCorpIdForTargetArea($corpId)
    {
        return $this->model->join("m_corps", function ($join) {
            /** @var JoinClause $join */
            $join->on("m_corp_categories.corp_id", "=", "m_corps.id");
            $join->where("m_corps.del_flg", 0);
        })
            ->join("m_target_areas", "m_target_areas.corp_category_id", "=", "m_corp_categories.id")
            ->join(
                DB::raw("(select jis_cd, max(address1 || address2) as address from m_posts group by jis_cd) as \"MPost\""),
                "MPost.jis_cd",
                "=",
                "m_target_areas.jis_cd"
            )
            ->join("m_categories", "m_categories.id", "=", "m_corp_categories.category_id")
            ->where("m_corp_categories.corp_id", $corpId)
            ->groupBy("m_categories.category_name")
            ->select("m_categories.category_name", DB::raw("string_agg(\"MPost\".\"address\", ',') as address"))
            ->get();
    }

    /**
     * @param integer $corpId
     * @param integer $idCategory
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function findLastIdByCorpIdAndCategoryId($corpId, $idCategory)
    {
        $query = $this->model->select('id')
            ->where('corp_id', '=', $corpId)
            ->where('category_id', '=', $idCategory)
            ->first();
        return $query;
    }

    /**
     * @param integer $corpId
     * @param integer $categoryId
     * @param integer $defaultFee
     * @return int
     */
    public function getIntroduceFee($corpId, $categoryId, $defaultFee)
    {

        // Search by enterprise-specific category master with company ID, category ID
        $result = $this->model->where('corp_id', $corpId)->where(
            'category_id',
            $categoryId
        )->select('introduce_fee')->first();

        // 2016.3.29 sasaki@tobila.com MOD start ORANGE-1336
        if ($result && !is_null($result->introduce_fee)) {
            return intval($result->introduce_fee);
        } else {
            if (!is_null($defaultFee['category_default_fee'])
                && $defaultFee['category_default_fee_unit'] == 0
            ) {
                return intval($defaultFee['category_default_fee']);
            }
            // If the unit of the fee is 1: commission rate, it returns zero.
            return 0;
        }
    }

    /**
     * Get list m_corp_categories join m_corps
     * Use in AffiliationStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array $columns
     * @return mixed
     */
    public function getListByCorpDelFlagAndAffiliationStatus($columns = ["*"], $delFlg = 0, $affiliationStatus = 1)
    {
        return $this->model->join("m_corps", function ($query) use ($delFlg, $affiliationStatus) {
            /** @var JoinClause $query */
            $query->on("m_corp_categories.corp_id", "=", "m_corps.id");
            $query->where("m_corps.del_flg", $delFlg);
        })->where("m_corps.affiliation_status", $affiliationStatus)->select($columns)->get();
    }

    /**
     * Get list m_corp_categories join m_corps group by m_corps.id and genre_id
     * Use in AffiliationAreaStatService
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array $columns
     * @param int $delFlag
     * @param int $affiliationStatus
     * @return mixed
     */
    public function getListAndGroupByCorpDelFlagAndAffiliationStatus(
        $columns = ["*"],
        $delFlag = 0,
        $affiliationStatus = 1
    ) {
        return $this->model->join("m_corps", function ($query) use ($delFlag, $affiliationStatus) {
            /** @var JoinClause $query */
            $query->on("m_corp_categories.corp_id", "=", "m_corps.id");
            $query->where("m_corps.del_flg", $delFlag);
        })->where("m_corps.affiliation_status", $affiliationStatus)->groupBy("m_corps.id", "m_corp_categories.genre_id")
            ->select($columns)->get();
    }

    /**
     * @param $corpId
     * @param $genreId
     * @param $categoryId
     * @return mixed
     */
    public function getOrderFeeCorp($corpId, $genreId, $categoryId)
    {
        $query = $this->model->select('order_fee', 'order_fee_unit', 'introduce_fee')
            ->where('corp_id', '=', $corpId)
            ->where('genre_id', '=', $genreId)
            ->where('category_id', '=', $categoryId)
            ->get()->toArray();
        return $query;
    }

    /**
     * get item by corp id
     * @param integer $corpId
     * @return mixed
     */
    public function getItemByCorpId($corpId = null)
    {
        return $this->model
            ->where('corp_id', $corpId)
            ->select('id', 'genre_id')
            ->get();
    }
}
