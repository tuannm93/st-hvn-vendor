<?php


namespace App\Services\Logic;

use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\Eloquent\AgreementRepository;
use App\Repositories\Eloquent\MCorpCategoriesTempRepository;

class Step0Logic
{

    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var AgreementRepository
     */
    protected $agreementRepository;
    /**
     * @var MCorpCategoriesTempRepository
     */
    protected $mCorpCategoriesTempRepository;

    /**
     * Step0Logic constructor.
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param AgreementRepository $agreementRepository
     * @param MCorpCategoriesTempRepository $mCorpCategoriesTempRepository
     */
    public function __construct(
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        AgreementSystemLogic $agreementSystemLogic,
        AgreementRepository $agreementRepository,
        MCorpCategoriesTempRepository $mCorpCategoriesTempRepository
    ) {
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->agreementRepository = $agreementRepository;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
    }

    /**
     * @param integer t$corpId
     * @param object $user
     * @return object
     * @throws \Exception
     */
    public function getStep0($corpId, $user)
    {
        $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId($corpId, null, true);
        if (is_null($corpAgreement)) {
            try {
                $this->agreementSystemLogic->initDataCorpAgreement($user);
            } catch (\Exception $ex) {
                throw $ex;
            }
        }
        $corpAgreementList = $this->corpAgreementRepository->getAllByCorpId($corpId, 'asc');
        return $corpAgreementList;
    }

    /**
     * @param object $user
     * @throws \Exception
     */
    public function step0Process($user)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        $agreement = $this->agreementRepository->findCurrentVersion();
        if (is_null($corpAgreement)) {
            $corpAgreement = $this->agreementSystemLogic->initDataCorpAgreement($user);
        } else {
            if ($corpAgreement->agreement_id == null || $corpAgreement->agreement_id == 0) {
                $corpAgreement->agreement_id = $agreement->agreement_id;
                $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, null, $user);
            }
        }

        $corpAgreementTempLink = $this->agreementSystemLogic->initCorpAgreementTempLink($corpAgreement, $user);

        $mCorpCategoriesTempList = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag($corpAgreement->corp_id, $corpAgreementTempLink->id, null, null);

        if (checkIsNullOrEmptyCollection($mCorpCategoriesTempList)) {
            $this->agreementSystemLogic->initCorpCategoryTemp($user, $user->affiliation_id, $corpAgreementTempLink->id);
        }
    }
}
