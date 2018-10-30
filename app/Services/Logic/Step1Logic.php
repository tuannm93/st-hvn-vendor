<?php


namespace App\Services\Logic;

use App\Models\CorpAgreement;
use App\Repositories\CorpAgreementRepositoryInterface;

class Step1Logic
{
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;

    /**
     * Step1Logic constructor.
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     */
    public function __construct(
        AgreementSystemLogic $agreementSystemLogic,
        CorpAgreementRepositoryInterface $corpAgreementRepository
    ) {
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->corpAgreementRepository = $corpAgreementRepository;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getStep1($corpId)
    {
        $corpAgreement = $this->corpAgreementRepository->findByCorpId($corpId);
        $corpAgreementId = $corpAgreement->id;
        $arrayProvision = $this->agreementSystemLogic->findCustomizedAgreementByCorpId($corpId);
        return ['arrayProvision' => $arrayProvision, 'corpAgreementId' => $corpAgreementId];
    }

    /**
     * @param object $user
     */
    public function step1Process($user)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        if (!is_null($corpAgreement)) {
            if ($corpAgreement->status == CorpAgreement::STEP0
                || $corpAgreement->status == CorpAgreement::RECONFIRMATION
                || $corpAgreement->status == CorpAgreement::RESIGNING
            ) {
                $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, CorpAgreement::STEP1, $user);
            }
        }
    }
}
