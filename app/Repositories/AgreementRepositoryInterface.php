<?php

namespace App\Repositories;

interface AgreementRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getFirstAgreement();

    /**
     * @return mixed
     */
    public function findCurrentVersion();

    /**
     * @param integer $id
     * @param array $data
     * @return \App\Models\Base
     */
    public function update($id, $data);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);
}
