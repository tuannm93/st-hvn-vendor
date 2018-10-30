<?php

namespace App\Repositories;

interface CorpCategoryGroupApplicationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function searchCorpCategoryGroupApplication($params);

    /**
     * @param array $params
     * @return mixed
     */
    public function getDataExportCorpCateGroupApp($params);
}
