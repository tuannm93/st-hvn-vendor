<?php

namespace App\Repositories;

interface MCategoryCopyRuleRepositoryInterface
{
    /**
     * @param integer $orgCategoryId
     * @return mixed
     */
    public function findAllByOrgCategoryId($orgCategoryId);

    /**
     * @param array $listOriginCateId
     * @return mixed
     */
    public function findAllByListOriginCategoryId($listOriginCateId);

    /**
     * @param integer $listOriginCateId
     * @return mixed
     */
    public function findCorpCateByOrgCateId($listOriginCateId);
}
