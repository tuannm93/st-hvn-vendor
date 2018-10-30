<?php
namespace App\Repositories;

interface MoneyCorrespondRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $id
     * @return mixed
     */
    public function deleteMoneyRecord($id);

    /**
     * @param integer $corpId
     * @param string $nominee
     * @param string $orderBy
     * @return mixed
     */
    public function getMoneyCorrespondDataInitial($corpId, $nominee, $orderBy);
}
