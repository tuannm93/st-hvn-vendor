<?php

namespace App\Repositories;

interface MPostRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Base
     */
    public function save($data);

    /**
     * @param integer $id
     * @param integer $address1
     * @return mixed
     */
    public function getCorpPrefAreaCount($id = null, $address1 = null);

    /**
     * @param integer $address1
     * @return mixed
     */
    public function getPrefAreaCount($address1 = null);

    /**
     * @param integer $id
     * @param integer $address1
     * @return mixed
     */
    public function searchCorpTargetArea($id = null, $address1 = null);

    /**
     * @param integer $id
     * @param array $data
     * @return mixed
     */
    public function editTargetArea2($id, $data);

    /**
     * @param null $id
     * @param null $address1
     * @return mixed
     */
    public function allRegistTargetArea($id = null, $address1 = null);

    /**
     * @param $address1
     * @return mixed
     */
    public function findByAddress1($address1);

    /**
     * @param $zipCode
     * @return mixed
     */
    public function searchAddressByZip($zipCode);

    /**
     * @param array $data
     * @return mixed
     */
    public function getTargetArea($data = []);

    /**
     * @param array $prefName
     * @return mixed
     */
    public function getJiscdByPrefName($prefName = []);

    /**
     * @param null $id
     * @param null $address1Cd
     * @return mixed
     */
    public function findByCorpIdAndPrefecturalCode($id = null, $address1Cd = null);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function editTargetArea($id, $data);

    /**
     * @param $address1
     * @param $lAddress2
     * @param $uAddress2
     * @return mixed
     */
    public function getJiscdByAddress($address1, $lAddress2, $uAddress2);

    /**
     * @param $corpId
     * @param $address1
     * @return mixed
     */
    public function findByAddress1AndCorpId($corpId, $address1);

    /**
     * @param null $corpId
     * @param null $address
     * @return mixed
     */
    public function getCorpCategoryAreaCount($corpId = null, $address = null);

    /**
     * @param $corpId
     * @param $address1
     * @return mixed
     */
    public function searchTargetArea($corpId, $address1);

    /**
     * @param $corpId
     * @param $dataRequest
     * @return mixed
     */
    public function registTargetArea($corpId, $dataRequest);

    /**
     * @param $corpId
     * @param $address
     * @return mixed
     */
    public function registTargetAreaAddress($corpId = null, $address = null);
    /**
     * @param $corpId
     * @param $address
     * @return mixed
     */
    public function removeTargetAreaAddress($corpId = null, $address = null);
}
