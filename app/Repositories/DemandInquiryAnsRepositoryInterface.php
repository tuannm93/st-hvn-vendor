<?php

namespace App\Repositories;

interface DemandInquiryAnsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $demandId
     * @return mixed
     */
    public function getDemandInquiryWithMInquiryByDemand($demandId);

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param integer $demandId
     * @param integer $inquiryId
     * @return mixed
     */
    public function getDemandAnswerByDemandIdAndInquiryId($demandId, $inquiryId);
}
