<?php

namespace App\Services;

use Exception;
use App\Repositories\ProgDemandInfoTmpRepositoryInterface;
use App\Repositories\ProgAddDemandInfoTmpRepositoryInterface;
use App\Repositories\ProgDemandInfoOtherTmpRepositoryInterface;

class PMDeleteTempService
{
    /**
     * @var ProgDemandInfoTmpRepositoryInterface
     */
    protected $progDemandInfoTmpRepo;
    /**
     * @var ProgAddDemandInfoTmpRepositoryInterface
     */
    protected $progAddDemandInfoTmpRepo;
    /**
     * @var ProgDemandInfoOtherTmpRepositoryInterface
     */
    protected $progDemandInfoOtherTmpRepo;

    /**
     * PMDeleteTempService constructor.
     *
     * @param ProgDemandInfoTmpRepositoryInterface      $progDemandInfoTmpRepo
     * @param ProgDemandInfoOtherTmpRepositoryInterface $progDemandInfoOtherTmpRepo
     * @param ProgAddDemandInfoTmpRepositoryInterface   $progAddDemandInfoTmpRepo
     */
    public function __construct(
        ProgDemandInfoTmpRepositoryInterface $progDemandInfoTmpRepo,
        ProgDemandInfoOtherTmpRepositoryInterface $progDemandInfoOtherTmpRepo,
        ProgAddDemandInfoTmpRepositoryInterface $progAddDemandInfoTmpRepo
    ) {
        $this->progDemandInfoTmpRepo = $progDemandInfoTmpRepo;
        $this->progAddDemandInfoTmpRepo = $progAddDemandInfoTmpRepo;
        $this->progDemandInfoOtherTmpRepo = $progDemandInfoOtherTmpRepo;
    }

    /**
     * delete tmp
     *
     * @param  object $progCorp
     * @return boolean
     */
    public function deleteTmp($progCorp)
    {
        try {
            if (empty($progCorp->id)) {
                return false;
            }

            $this->progDemandInfoTmpRepo->deleteByProgCorpId($progCorp->id);
            $this->progAddDemandInfoTmpRepo->deleteByProgCorpId($progCorp->id);
            $this->progDemandInfoOtherTmpRepo->deleteByProgCorpId($progCorp->id);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
