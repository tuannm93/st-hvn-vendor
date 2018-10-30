<?php

namespace App\Repositories;

interface AntisocialCheckRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * find history by corp id
     *
     * @param  integer $corpId
     * @param  string  $type
     * @return array object antisocial checks
     */
    public function findHistoryByCorpId($corpId, $type = 'first');

    /**
     * @return mixed
     */
    public function getAntisocialList();

    /**
     * @param string $auth
     * @return mixed
     */
    public function isUpdateAuthority($auth);

    /**
     * @return mixed
     */
    public function getDataCsv();

    /**
     * @param array $data
     * @param string $auth
     * @return mixed
     */
    public function updateDataAntisocialFollow($data, $auth);

    /**
     * @return mixed
     */
    public function getResultList();

    /**
     * @return mixed
     */
    public function getMonthList();

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getAntisocialFollow($corpId);
}
