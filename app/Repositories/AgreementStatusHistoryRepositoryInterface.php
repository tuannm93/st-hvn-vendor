<?php

namespace App\Repositories;

interface AgreementStatusHistoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getFirstAgreementStatusHistory();
}
