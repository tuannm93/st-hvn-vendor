<?php

namespace App\Repositories;

interface SingleKeyModelRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return array
     */
    public function getPrimaryKey();

    /**
     * @param integer $id
     *
     * @param array   $columns
     * @return \App\Models\Base|null
     */
    public function find($id, $columns = ['*']);

    /**
     * @param array       $ids
     * @param string|null $order
     * @param string|null $direction
     * @param boolean     $reorder
     *
     * @return \App\Models\Base[]
     */
    public function allByIds($ids, $order = null, $direction = null, $reorder = false);

    /**
     * @param array $ids
     *
     * @return integer
     */
    public function countByIds($ids);

    /**
     * @param array        $ids
     * @param string|null  $order
     * @param string|null  $direction
     * @param integer|null $offset
     * @param integer|null $limit
     *
     * @return \App\Models\Base[]
     */
    public function getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);


    /**
     * @param int $pageNumber
     * @param null $whereCondition
     * @return \App\Models\Base[]
     */
    public function paginate($pageNumber = 5, $whereCondition = null);

    /**
     * @param array $input
     *
     * @return \App\Models\Base
     */
    public function create($input);

    /**
     * @param \App\Models\Base $model
     * @param array            $input
     *
     * @return \App\Models\Base
     */
    public function update($model, $input);

    /**
     * @param \App\Models\Base|object $model
     *
     * @return \App\Models\Base
     */
    public function save($model);

    /**
     * @param \App\Models\Base $model
     *
     * @return boolean
     */
    public function delete($model);

    /**
     * delete list item by array id
     *
     * @param  array $arrayId
     * @return boolean
     */
    public function deleteListItemByArrayId($arrayId);

    /**
     * insert or update item
     *
     * @param  array  $input
     * @param  object $item
     * @return array
     */
    public function insertOrUpdateItem($input, $item = null);

    /**
     * delete item by id
     *
     * @param  integer $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * find last item
     *
     * @return object
     */
    public function findLastItem();

    /**
     * insert item or many item
     *
     * @param  array $data
     * @return boolean
     */
    public function insert($data);

    /**
     * @param array $condition
     * @return mixed
     */
    public function deleteByCondition($condition);

    /**
     * @param array $data
     * @return mixed
     */
    public function insertGetId($data);

    /**
     * Insert or update multi data
     * @param array $data
     * @return mixed
     */
    public function insertOrUpdateMultiData($data);
}
