<?php

namespace App\Repositories;

interface AgreementEditHistoryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getFirstAgreementEditHistory();
}
