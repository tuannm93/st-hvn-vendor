<?php

namespace App\Repositories;

interface AccumulatedInformationsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $corpId
     * @param integer $demandId
     * @return mixed
     */
    public function getInfos($corpId, $demandId);

    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getAllInfos($demandId);

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($id, $data);

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param integer $corpId
     * @param integer $demandId
     * @return mixed
     */
    public function getInfoByFlag($corpId, $demandId);
}
