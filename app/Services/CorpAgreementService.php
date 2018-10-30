<?php


namespace App\Services;

use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;

class CorpAgreementService extends BaseService
{
    /**
     * @var CorpAgreementRepositoryInterface
     */
    private $corpAgreementRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * CorpAgreementService constructor.
     *
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param MCorpRepositoryInterface         $mCorpRepository
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        MCorpRepositoryInterface $mCorpRepository
    ) {
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * @param $corpId
     * @return boolean
     */
    public function isAgreementDialogShow($corpId)
    {
        $corpAgreement = $this->corpAgreementRepository->findByCorpId($corpId);
        if (!empty($corpAgreement)
            && ($corpAgreement['status'] == "Complete" || $corpAgreement['status'] == "Application")
        ) {
            $mCorp = $this->mCorpRepository->find($corpId);
            if ($mCorp->commission_accept_flg != 2
                && $mCorp->commission_accept_flg != 3
            ) {
                return false;
            }
        }
        return true;
    }
}
