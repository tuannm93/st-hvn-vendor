<?php

namespace App\Repositories;

interface MInquiryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $category
     * @return mixed
     */
    public function getListInquiryByCategory($category = null);
}
