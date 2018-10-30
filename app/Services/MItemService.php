<?php

namespace App\Services;

use App\Repositories\MItemRepositoryInterface;

class MItemService
{
    /**
     * @var MItemRepositoryInterface
     */
    protected $mItemRepository;

    /**
     * MItemService constructor.
     *
     * @param MItemRepositoryInterface $mItemRepository
     */
    public function __construct(
        MItemRepositoryInterface $mItemRepository
    ) {
        $this->mItemRepository = $mItemRepository;
    }

    /**
     * prepare list
     *
     * @param  array $list
     * @return array
     */
    public function prepareDataList($list)
    {
        $temp = [];
        foreach ($list as $arr) {
            $temp[$arr['id']] = $arr['category_name'];
        }
        return $temp;
    }

    /**
     * @param $categories
     * @return array
     */
    public function getMultiList($categories)
    {
        $rows = $this->mItemRepository->getByCategory($categories);
        $results = [];

        foreach ($rows as $row) {
            $results[$row['MItem__item_category']][$row['MItem__item_id']] = $row['MItem__item_name'];
        }

        return $results;
    }
}
