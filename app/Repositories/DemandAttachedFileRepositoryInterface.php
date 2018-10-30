<?php

namespace App\Repositories;

interface DemandAttachedFileRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param integer $id
     * @return mixed
     */
    public function findByDemandId($id);

    /**
     * @param integer $attachedId
     * @return mixed
     */
    public function findId($attachedId);

    /**
     * Get file download
     * @param integer $id
     * @return mixed
     */
    public function getFileDownload($id);
}
