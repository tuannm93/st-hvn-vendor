<?php

namespace App\Repositories\Eloquent;

use App\Models\MCategoryCopyRule;
use App\Repositories\MCategoryCopyRuleRepositoryInterface;

class MCategoryCopyRuleRepository extends SingleKeyModelRepository implements MCategoryCopyRuleRepositoryInterface
{

    /**
     * @var MCategoryCopyRule
     */
    protected $model;

    /**
     * MCategoryCopyRuleRepository constructor.
     *
     * @param MCategoryCopyRule $model
     */
    public function __construct(MCategoryCopyRule $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer $orgCategoryId
     * @return \Illuminate\Support\Collection|mixed
     */
    public function findAllByOrgCategoryId($orgCategoryId)
    {
        return $this->model->leftJoin('m_categories as m1', 'm1.id', '=', 'm_category_copyrules.org_category_id')
            ->leftJoin('m_categories as m2', 'm2.id', '=', 'm_category_copyrules.copy_category_id')
            ->leftJoin('m_genres', 'm_genres.id', '=', 'm2.genre_id')
            ->where('m_category_copyrules.org_category_id', $orgCategoryId)->get();
    }

    /**
     * @param integer $listOriginCateId
     * @return array|mixed
     */
    public function findAllByListOriginCategoryId($listOriginCateId)
    {
        $query = $this->model->select('id')
            ->whereIn('org_category_id', $listOriginCateId)
            ->pluck('id')->toArray();
        return $query;
    }

    /**
     * @param integer $listOriginCateId
     * @return array|mixed
     */
    public function findCorpCateByOrgCateId($listOriginCateId)
    {
        $query = $this->model->select('copy_category_id')
            ->whereIn('org_category_id', $listOriginCateId)
            ->pluck('copy_category_id')->toArray();
        return $query;
    }
}
