<?php


namespace App\Repositories;

interface MAddress1RepositoryInterface
{
    /**
     * @param string $address1
     * @return mixed
     */
    public function findByAddressName($address1);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findByCorpIdAndPrefecturalCode($corpId);

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function findAreaDataSetByCorpIdAndPrefecturalCode($corpId);

    /**
     * @return mixed
     */
    public function getList();

    /**
     * @param $addressCd
     * @return mixed
     */
    public function findByAddressCd($addressCd);
}
