<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorpTargetArea;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use App\Repositories\MTargetAreaRepositoryInterface;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MCorpTargetAreaRepository extends SingleKeyModelRepository implements MCorpTargetAreaRepositoryInterface
{
    /**
     * @var MCorpTargetArea
     */
    protected $model;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategory;
    /**
     * @var MTargetAreaRepositoryInterface
     */
    protected $mTargetArea;

    /**
     * MCorpTargetAreaRepository constructor.
     *
     * @param MCorpTargetArea                  $model
     * @param MTargetAreaRepositoryInterface   $mTargetArea
     * @param MCorpCategoryRepositoryInterface $mCorpCategory
     */
    public function __construct(
        MCorpTargetArea $model,
        MTargetAreaRepositoryInterface $mTargetArea,
        MCorpCategoryRepositoryInterface $mCorpCategory
    ) {
        $this->model = $model;
        $this->mCorpCategory = $mCorpCategory;
        $this->mTargetArea = $mTargetArea;
    }

    /**
     * @return \App\Models\Base|MCorpTargetArea|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorpTargetArea();
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
     * get list by corp_id
     *
     * @param integer $corpId
     * @param boolean $toArray
     * @return array|mixed
     */
    public function getListByCorpId($corpId, $toArray = false)
    {
        return $this->findByCorpId($corpId, $toArray);
    }

    /**
     * find data by corp_id
     * @param integer $corpId
     * @param boolean $toArray
     * @return mixed | array
    */
    private function findByCorpId($corpId, $toArray = false)
    {
        $query = $this->model->where('corp_id', $corpId);

        if ($toArray) {
            $result = $query->get();
            return !empty($result) ? $result->toArray() : [];
        }

        return $query->get();
    }

    /**
     * get count by corp_id
     * @param integer $corpId
     * @return integer
     */
    public function countByCorpId($corpId = null)
    {
        return MCorpTargetArea::select('id')
            ->where('corp_id', '=', $corpId)->count();
    }

    /**
     * get last corp by id function
     * @param integer $corpId
     * @return array
     */
    public function getLastModifiedByCorpId($corpId = null)
    {
        $result = MCorpTargetArea::select('modified')
            ->where('corp_id', '=', $corpId)
            ->orderBy('modified', 'desc')
            ->first();

        return empty($result) ? [] : $result->toarray();
    }

    /**
     * edit corp by id function
     *
     * @param  integer $id
     * @return boolean
     */
    public function editTargetAreaToGenre($id)
    {
        $saveData = [];
        $corpAreas = MCorpTargetArea::where('corp_id', '=', $id)->get()->toarray();
        $idList = $this->mCorpCategory->getListForIdByCorpId($id);
        $userId = Auth::user()->user_id;
        $date = Carbon::now();
        foreach ($idList as $val) {
            $areaCount = $this->mTargetArea->getCorpCategoryTargetAreaCount($val['id']);
            if ($areaCount > 0) {
                continue;
            }
            foreach ($corpAreas as $area) {
                $setData = [];
                $setData['corp_category_id'] = $val['id'];
                $setData['jis_cd'] = $area['jis_cd'];
                $setData['modified_user_id'] = $userId;
                $setData['modified'] = $date;
                $setData['created_user_id'] = $userId;
                $setData['created'] = $date;
                $saveData[] = $setData;
            }
        }
        if (!empty($saveData)) {
            try {
                DB::beginTransaction();
                DB::table('m_target_areas')->insert($saveData);
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    /**
     * @param null $corpId
     * @param null $address1
     * @return bool|mixed
     */
    public function removeByCorpId($corpId = null, $address1 = null)
    {
        try {
            if (empty($address1)) {
                $this->model->where('corp_id', '=', $corpId)->delete();
            } else {
                $jisCds = DB::table('m_posts')->select('jis_cd')
                    ->where('m_posts.address1', $address1)->groupBy('m_posts.jis_cd')->pluck('jis_cd')->toarray();
                $this->model->where('corp_id', '=', $corpId)
                    ->whereIn('jis_cd', $jisCds)
                    ->delete();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * find corp by id,genre_id function
     *
     * @param  integer $id
     * @param  integer $genreId
     * @return boolean
     */
    public function editTargetAreaToCategory($id = null, $genreId = null)
    {
        $saveData = [];
        $corpAreas = MCorpTargetArea::where('corp_id', '=', $id)->get()->toarray();
        $idList = $this->mCorpCategory->getListForIdByCorpIdAndGenreId($id, $genreId);
        $userId = Auth::user()->user_id;
        $date = date('Y-m-d H:i:s');
        foreach ($idList as $val) {
            $areaCount = $this->mTargetArea->getCorpCategoryTargetAreaCount($val['id']);
            if ($areaCount > 0) {
                DB::table('m_target_areas')->where('corp_category_id', '=', $val['id'])->delete();
            }
            foreach ($corpAreas as $area) {
                $setData = [];
                $setData['corp_category_id'] = $val['id'];
                $setData['jis_cd'] = $area['jis_cd'];
                $setData['modified_user_id'] = $userId;
                $setData['modified'] = $date;
                $setData['created_user_id'] = $userId;
                $setData['created'] = $date;
                $saveData[] = $setData;
            }
        }
        if (!empty($saveData)) {
            try {
                DB::beginTransaction();
                DB::table('m_target_areas')->insert($saveData);
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        return true;
    }

    /**
     * @param integer $corpId
     * @param string $addressCode
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getListByCorpIdAndAddressCode($corpId, $addressCode)
    {
        return $this->model->where('corp_id', $corpId)
            ->where(DB::raw('SUBSTR(m_corp_target_areas.jis_cd, 1, 2)'), $addressCode)->get();
    }

    /**
     * @param array $ids
     * @return boolean
     * @throws \Exception
     */
    public function deleteByListId($ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Get last update category information of mCorp by corpId
     *
     * @param integer $corpId
     * @param  array  $columns
     * @param  array  $order
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     */
    public function getLastByMCorp($corpId, $columns = ['*'], $order = ['column' => 'id', 'dir' => 'desc'])
    {
        return $this->model->join('m_corps', 'm_corps.id', '=', 'm_corp_target_areas.corp_id')
            ->where('m_corp_target_areas.corp_id', $corpId)
            ->where('m_corps.del_flg', 0)
            ->orderBy($order['column'], $order['dir'])
            ->first($columns);
    }

    /**
     * @param integer $corpId
     * @return array|mixed
     */
    public function getJscByCorpId($corpId)
    {
        return $this->model->where('corp_id', $corpId)
            ->get()->pluck('jis_cd')->toarray();
    }
}
