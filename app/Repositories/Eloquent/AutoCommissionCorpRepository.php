<?php

namespace App\Repositories\Eloquent;

use App\Models\AutoCommissionCorp;
use App\Repositories\AutoCommissionCorpRepositoryInterface;
use DB;

class AutoCommissionCorpRepository extends SingleKeyModelRepository implements AutoCommissionCorpRepositoryInterface
{
    /**
     * @var AutoCommissionCorp
     */
    protected $model;

    /**
     * AutoCommissionCorpRepository constructor.
     *
     * @param AutoCommissionCorp $model
     */
    public function __construct(AutoCommissionCorp $model)
    {
        $this->model = $model;
    }

    /**
     * @return AutoCommissionCorp|\App\Models\Base|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new AutoCommissionCorp();
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
     * count auto commission corp list by corpId
     *
     * @param  integer $corpId
     * @return integer
     */
    public function countByCorpId($corpId)
    {
        $result = $this->model
            ->select('corp_id', 'category_id', DB::raw('substring(jis_cd from 1 for 2)'))
            ->where('corp_id', $corpId)
            ->distinct()
            ->get();
        return count($result);
    }

    /**
     * @param integer $jisCd
     * @param integer $categoryId
     * @param integer $corpId
     * @return bool
     * @throws \Exception
     */
    public function deleteBy($jisCd, $categoryId, $corpId)
    {
        try {
            DB::beginTransaction();

            foreach ($jisCd as $jisValue){
                $this->model->whereIn('category_id', $categoryId)
                    ->where('corp_id', $corpId)
                    ->where('jis_cd', 'like', $jisValue . '%')->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * @param array $data
     * @param null    $type
     * @param boolean $checkRequest
     * @return mixed
     */
    public function getByCategoryGenreAndPrefCd($data, $type = null, $checkRequest = true, $datacustom = 'state_list')
    {
        $results = [];
        $result = $this->model->distinct()
            ->join('m_corps', 'm_corps.id', '=', 'auto_commission_corp.corp_id')
            ->join('m_categories', 'm_categories.id', '=', 'auto_commission_corp.category_id')
            ->join('m_posts', 'm_posts.jis_cd', '=', 'auto_commission_corp.jis_cd')
            ->where('m_corps.del_flg', 0);
        if (isset($data['pref_cd'])) {
            if (!empty(config('datacustom.' . $datacustom)[$data['pref_cd']])) {
                $getAddress1 = config('datacustom.' . $datacustom)[$data['pref_cd']];
                $result->where('m_posts.address1', $getAddress1);
            } else {
                $result->whereNull('m_posts.address1');
            }
        } else {
            $getAddress1 = config('datacustom.' . $datacustom);
            $result->whereIn('m_posts.address1', array_values($getAddress1));
        }
        if (isset($data['category_id'])) {
            $result->where('auto_commission_corp.category_id', '=', intval($data['category_id']));
        } else {
            $result->whereNull('auto_commission_corp.category_id');
        }

        if ($type === 'auto_corp') {
            $result->select(
                'auto_commission_corp.id as auto_commission_corp_id',
                'auto_commission_corp.sort as auto_commission_corp_sort'
            );
        } else {
            $result->select(
                'm_corps.id as m_corps_id',
                'm_corps.corp_name',
                'auto_commission_corp.sort',
                'auto_commission_corp.process_type'
            );
            $result->orderBy('auto_commission_corp.process_type', 'asc')
                ->orderBy('auto_commission_corp.sort', 'asc');
        }
        $result = $result->get();
        if ($checkRequest) {
            if (!empty($result->toArray())) {
                foreach ($result as $value) {
                    $results[$value['process_type']][$value['m_corps_id']] = $value['corp_name'];
                }
                return $results;
            } else {
                return $results = [];
            }
        } else {
            return $result;
        }
    }

    /**
     * @param integer $arrGenreId
     * @return mixed
     */
    public function findByGenreId($arrGenreId)
    {
        $query = $this->model->join('m_corps', 'auto_commission_corp.corp_id', '=', 'm_corps.id')
            ->join('m_categories', 'auto_commission_corp.category_id', '=', 'm_categories.id')
            ->select(
                'auto_commission_corp.corp_id',
                'auto_commission_corp.category_id',
                DB::raw("substr(auto_commission_corp.jis_cd, 1, 2) AS pref_cd"),
                'auto_commission_corp.sort',
                'auto_commission_corp.process_type',
                'm_corps.corp_name',
                'm_corps.official_corp_name',
                'm_categories.genre_id',
                'm_categories.category_name'
            )
            ->orderBy('pref_cd', 'asc')
            ->orderBy('m_categories.genre_id', 'asc')
            ->orderBy('auto_commission_corp.process_type', 'desc')
            ->orderBy('auto_commission_corp.sort', 'asc')
            ->distinct();
        if (!empty($arrGenreId)) {
            $query->whereIn('m_categories.genre_id', $arrGenreId)->where('m_corps.del_flg', 0);
        }
        return $query->get();
    }

    /**
     * @param array $listCateId
     * @param array $listPrefId
     * @return bool
     */
    public function deleteByCateAndPref($listCateId, $listPrefId)
    {
        $bSuccess = true;
        try {
            $this->model->whereIn('category_id', $listCateId)
                ->whereIn(DB::raw("substring(jis_cd,1,2)"), $listPrefId)
                ->delete();
        } catch (\Exception $ex) {
            $bSuccess = false;
        }
        return $bSuccess;
    }

    /**
     * @param array $listCorpCommission
     * @param array $listCorpSelect
     * @param array $listCate
     * @param integer $listJiscd
     * @return bool
     */
    public function addCorpInfor($listCorpCommission, $listCorpSelect, $listCate, $listJiscd)
    {
        $bSuccess = true;
        try {
            DB::beginTransaction();
            if (is_array($listCorpCommission) && count($listCorpCommission) > 0) {
                $this->insertCorpInfo(1, $listCorpCommission, $listJiscd, $listCate);
            }
            if (is_array($listCorpSelect) && count($listCorpSelect) > 0) {
                $this->insertCorpInfo(2, $listCorpSelect, $listJiscd, $listCate);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            $bSuccess = false;
        }
        return $bSuccess;
    }

    /**
     * @param $processType
     * @param array $listCorp
     * @param array $listJiscd
     * @param array $listCate
     */
    private function insertCorpInfo($processType, $listCorp = [], $listJiscd = [], $listCate = [])
    {
        $dateNow = date('Y-m-d H:i:s');
        $userId = \Auth::user()->user_id;
        foreach ($listCorp as $corp) {
            foreach ($listJiscd as $jiscd) {
                foreach ($listCate as $cate) {
                    $data = [
                        'corp_id' => $corp,
                        'category_id' => $cate,
                        'sort' => 0,
                        'jis_cd' => $jiscd['jis_cd'],
                        'created_user_id' => $userId,
                        'modified_user_id' => $userId,
                        'process_type' => $processType,
                        'modified' => $dateNow,
                        'created' => $dateNow
                    ];
                    $this->model->insert($data);
                }
            }
        }
    }

    /**
     * @param integer $prefCd
     * @param array $category
     * @param integer $corpId
     * @param bool $toArray
     * @return array|\Illuminate\Support\Collection
     */
    public function getAutoCommissionCorp($prefCd = null, $category = null, $corpId = null, $toArray = true)
    {
        if (empty($prefCd) || empty($category)) {
            return [];
        }
        $prefCd = sprintf('%02d', $prefCd);
        $query = \DB::table('auto_commission_corp')
            ->join('m_corps', 'auto_commission_corp.id', '=', 'm_corps.id')
            ->distinct('corp_id')
            ->select('corp_id', 'category_id', 'sort', 'process_type')
            ->addSelect(\DB::raw('(substring("auto_commission_corp"."jis_cd" from 1 for 2)) AS pref_cd'))
            ->where('m_corps.del_flg', 0)
            ->where('auto_commission_corp.category_id', $category)
            ->whereRaw('(substring("auto_commission_corp"."jis_cd" from 1 for 2))  = \'' . $prefCd . '\'')
            ->orderBy('process_type', 'DESC')
            ->orderBy('sort', 'ASC');
        if ($corpId) {
            $query->where('auto_commission_corp.corp_id', $corpId);
        }
        if ($toArray) {
            return $query->get()->toArray();
        }
        return $query->get();
    }
}
