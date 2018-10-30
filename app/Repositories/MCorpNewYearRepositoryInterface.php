<?php

namespace App\Repositories;

interface MCorpNewYearRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function deleteAll();

    /**
     * @param integer $corpId
     * @param array $data
     * @return mixed
     */
    public function updateNewYear($corpId, $data);

    /**
     * @param integer $mCorpId
     * @param array   $fields
     * @return mixed
     */
    public function getItemByMCorpId($mCorpId, $fields = ['*']);
}
