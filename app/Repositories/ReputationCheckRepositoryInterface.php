<?php

namespace App\Repositories;

interface ReputationCheckRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $corpId
     * @param string $type
     * @return mixed
     */
    public function findHistoryByCorpId($corpId, $type = 'first');

    /**
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function getListCorpReport($page = 1, $limit = 100);

    /**
     * @return mixed
     */
    public function getListCorpReportDownload();

    /**
     * @param integer $id
     * @return mixed
     */
    public function updateDateTime($id);
}
