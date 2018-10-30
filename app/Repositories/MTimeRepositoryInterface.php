<?php

namespace App\Repositories;

interface MTimeRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * get by item category
     *
     * @param  string $itemCategory
     * @return array
     */
    public function getByItemCategory($itemCategory);

    /**
     * get support message time
     *
     * @return string
     */
    public function getSupportMessageTime();

    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * get item by item category follow tel
     *
     * @return array object
     */
    public function getByItemCategoryFollowTel();

    /**
     * find by item detail and item category
     *
     * @param  string $itemDetail
     * @param  string $itemCategory
     * @return object
     */
    public function findByItemDetailAndItemCategory($itemDetail, $itemCategory);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateData($id, $data);
}
