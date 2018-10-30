<?php

namespace App\Repositories\Eloquent;

use App\Models\MCorpSub;
use App\Repositories\MCorpSubRepositoryInterface;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

class MCorpSubRepository extends SingleKeyModelRepository implements MCorpSubRepositoryInterface
{
    /**
     * @var MCorpSub
     */
    private $model;

    /**
     * MCorpSubRepository constructor.
     *
     * @param MCorpSub $model
     */
    public function __construct(MCorpSub $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Models\Base|MCorpSub|\Illuminate\Database\Eloquent\Model
     */
    public function getBlankModel()
    {
        return new MCorpSub();
    }

    /**
     * @param integer $corpId
     * @return Collection|mixed
     */
    public function findByCorpIdForAffiliation($corpId)
    {
        $query = $this->model->select(
            [
                'm_corp_subs.item_id',
                'm_corp_subs.item_category',
                'm_items.item_name'
            ]
        )->from('m_corp_subs')
            ->leftJoin(
                'm_items',
                function ($join) {
                    /**
                     * @var JoinClause $join
                     */
                    $join->on('m_corp_subs.item_category', '=', 'm_items.item_category')
                        ->on('m_corp_subs.item_id', '=', 'm_items.item_id');
                }
            )
            ->where('m_corp_subs.corp_id', '=', $corpId)
            ->orderBy('m_items.item_category', 'asc')
            ->orderBy('m_items.sort_order', 'asc');
        return $query->get();
    }

    /**
     * Acquisition of company master incidental information
     *
     * @param integer $id
     * @return array|Collection
     */
    public function getMCorpSubList($id)
    {
        $results = $this->model->join(
            'm_items',
            function ($join) {
                $join->on('m_corp_subs.item_category', '=', 'm_items.item_category')
                    ->on('m_corp_subs.item_id', '=', 'm_items.item_id');
            }
        )->where('m_corp_subs.corp_id', $id)
            ->orderBy('m_items.item_category', 'asc')
            ->orderBy('m_items.sort_order', 'asc')
            ->get();

        return $results;
    }

    /**
     * @return $this|mixed
     */
    public function holidayQuery()
    {
        $query = $this->model->select('m_items.item_name')
            ->join(
                'm_items',
                function ($join) {
                    $join->on('m_items.item_category', '=', 'm_corp_subs.item_category')
                        ->on('m_items.item_id', '=', 'm_corp_subs.item_id');
                }
            )
            ->whereColumn('m_corp_subs.corp_id', 'm_corps.id')
            ->where('m_corp_subs.item_category', '=', config('constant.holiday'));
        return $query;
    }

    /**
     * @param integer $corpId
     * @return Collection|mixed
     */
    public function getMCorpSubData($corpId)
    {
        return $this->model->select('m_corp_subs.*', 'm_items.item_name')
            ->leftJoin(
                'm_items',
                [
                    ['m_items.item_category', '=', 'm_corp_subs.item_category'],
                    ['m_items.item_id', '=', 'm_corp_subs.item_id']
                ]
            )
            ->where(
                [
                    ['m_corp_subs.corp_id', $corpId],
                    ['m_corp_subs.item_category', MCorpSub::HOLIDAY]
                ]
            )->get();
    }

    /**
     * @param integer $corpId
     * @param string $fields
     * @param array $orders
     * @return mixed
     */
    public function getItemByMCorpId($corpId, $fields, $orders)
    {
        $query = $this->model->select($fields)->where('corp_id', '=', $corpId);
        if (count($orders) > 0) {
            foreach ($orders as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }
        return $query->get();
    }

    /**
     * get m_corp_subs by corp_id and item_category
     * @param $corpId
     * @param $category
     * @return mixed
     */
    public function getItemByCorpIdAndCate($corpId, $category)
    {
        return $this->model->where('corp_id', '=', $corpId)->where('item_category', '=', $category)->get();
    }

    /**
     * delete row where corp_id, item_category, item_id
     * @param $conditions
     * @return mixed
     * @throws \Exception
     */
    public function deleteItemsNotExist($conditions)
    {
        $query = $this->model->where('corp_id', '=', $conditions['corp_id'])
            ->where('item_category', '=', $conditions['item_category']);
        $query->whereNotIn('item_id', $conditions['item_id']);
        $query->delete();
    }
}
