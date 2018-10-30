<?php

namespace App\Repositories\Eloquent;

use App\Models\MTargetArea;
use App\Repositories\MTargetAreaRepositoryInterface;

class MTargetAreaRepository extends SingleKeyModelRepository implements MTargetAreaRepositoryInterface
{
    /**
     * @var MTargetArea
     */
    protected $model;

    /**
     * MTargetAreaRepository constructor.
     *
     * @param MTargetArea $model
     */
    public function __construct(MTargetArea $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MTargetArea|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MTargetArea();
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
     * delete list item by array corp category id
     *
     * @param  array $arrayId
     * @return boolean
     * @throws \Exception
     */
    public function deleteListItemByArrayCorpCategoryId($arrayId)
    {
        return $this->model
            ->whereIn('corp_category_id', $arrayId)
            ->delete();
    }

    /**
     * count corp category target area
     *
     * @param  integer $corpCategoryId
     * @return integer
     */
    public function countCorpCategoryTargetArea($corpCategoryId)
    {
        return $this->model
            ->where('corp_category_id', $corpCategoryId)
            ->count();
    }

    /**
     * @param integer $corpCategoryId
     * @return boolean
     * @throws \Exception
     */
    public function deleteById($corpCategoryId)
    {
        return $this->model->whereIn('corp_category_id', $corpCategoryId)->delete();
    }

    /**
     * @param array $data
     */
    public function insertByCorpCategoryId($data)
    {
        $this->model->insert($data);
    }

    /**
     * @param integer $id
     * @param integer   $jisCD
     * @return integer
     */
    public function getCorpCategoryTargetAreaCount($id = null, $jisCD = null)
    {
        $query = $this->model->where('corp_category_id', $id)->select('id');
        if (isset($jisCD)) {
            $query->where('jis_cd', $jisCD);
            $count = $query->count();
        } else {
            $count = $query->count();
        }
        return $count;
    }

    /**
     * count corp by id,jis_cd  function
     *
     * @param  integer $id
     * @param  integer    $jisCd
     * @return integer
     */
    public function getCorpCategoryTargetAreaCount2($id = null, $jisCd = null)
    {
        $result = MTargetArea::select('id')
            ->where('corp_category_id', '=', $id)
            ->where('jis_cd', '=', $jisCd)->count();
        return $result;
    }

    /**
     * @param integer $id
     * @return integer
     */
    public function getCorpCategoryTargetAreaCount3($id = null)
    {
        return $this->model->select('id')->where('corp_category_id', $id)->count();
    }

    /**
     * @param integer $id
     * @param integer $jisCd
     * @return mixed
     */
    public function getCorpCategoryTargetAreaByJisCd($id = null, $jisCd = null)
    {
        return $this->model->select('id')->where('corp_category_id', $id)->where('jis_cd', $jisCd)->first();
    }

    /**
     * @param array $data
     * @return MTargetArea|\Illuminate\Database\Eloquent\Model
     */
    public function saveAll($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $corpCategoryId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllByCorpCategoryId($corpCategoryId)
    {
        return $this->model->where('corp_category_id', '=', $corpCategoryId)->get();
    }

    /**
     * @param integer $corpCategoryId
     * @param integer $jisCd
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllByCorpCategoryIdAndJisCd($corpCategoryId, $jisCd)
    {
        return $this->model->where('corp_category_id', '=', $corpCategoryId)
            ->where('jis_cd', '=', $jisCd)
            ->get();
    }

    /**
     * @param integer $corpId
     * @param array $defaultJisCds
     * @return array|mixed
     */
    public function countHasJisCdsOfCorpCategory($corpId = null, $defaultJisCds = null)
    {
        return $this->model->where('corp_category_id', '=', $corpId)
            ->whereIn('jis_cd', $defaultJisCds)
            ->get()->toarray();
    }

    /**
     * @param integer $corpId
     * @return array|mixed|void
     */
    public function getTargetAreaLastModified($corpId)
    {
        $result = $this->model->select('modified')
            ->where('corp_category_id', '=', $corpId)
            ->orderBy('modified', 'desc')
            ->first();
        if ($result) {
            return $result->toarray();
        }
        return;
    }

    /**
     * @param integer $corpCategoryId
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteByCorpCategoryId($corpCategoryId)
    {
        return $this->model->where('corp_category_id', '=', $corpCategoryId)->delete();
    }
}
