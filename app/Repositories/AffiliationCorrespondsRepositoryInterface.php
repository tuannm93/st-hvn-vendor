<?php

namespace App\Repositories;

interface AffiliationCorrespondsRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Acquisition of member store correspondence history
     *
     * @param  array $conditions
     * @return \Illuminate\Support\Collection
     */
    public function getAffiliationCorrespond($conditions = []);

    /**
     * Update affiliation correspond with id
     *
     * @param integer $id
     * @param array $data
     * @return mixed|static
     */
    public function updateAffiliationCorrespondWithId($id, $data);
}
