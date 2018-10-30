<?php

namespace App\Repositories;

interface MItemRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $category
     * @return mixed
     */
    public function getListByCategoryItem($category);

    /**
     * @param integer $category
     * @param integer     $itemId
     * @return mixed
     */
    public function getFirstOldList($category, $itemId = null);

    /**
     * @param string $query
     * @return mixed
     */
    public function scopeGetList($query);

    /**
     * @param string $category
     * @param null     $itemId
     * @return mixed
     */
    public function getList($category, $itemId = null);

    /**
     * @param integer $category
     * @param string $value
     * @return mixed
     */
    public function getListText($category, $value);

    /**
     * @param array $categories
     * @return mixed
     */
    public function getByCategory($categories);

    /**
     * @param string $categoryName
     * @return mixed
     */
    public function getMItemListByItemCategory($categoryName);

    /**
     * @param string $categoryName
     * @param string $date
     * @return mixed
     */
    public function getMItemList($categoryName, $date);

    /**
     * @return mixed
     */
    public function getByLongHoliday();

    /**
     * @return mixed
     */
    public function deleteByLongHoliday();
}
