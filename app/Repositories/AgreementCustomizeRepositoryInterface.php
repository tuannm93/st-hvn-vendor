<?php

namespace App\Repositories;

interface AgreementCustomizeRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllAgreementCustomize();

    /**
     * @param integer $corpId
     * @param integer $deleteFlag
     * @return mixed
     */
    public function findAgreementCustomizeByCorpId($corpId, $deleteFlag);

    /**
     * @param integer $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function saveAgreementCustomize($data);

    /**
     * @param integer $fieldId
     * @param string $field
     * @param string $tableKind
     * @return mixed
     */
    public function findLastestCustomize($fieldId, $field, $tableKind);
}
