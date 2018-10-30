<?php


namespace App\Services\Logic;

use App\Models\CorpAgreement;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;

class Step4Logic
{

    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreeTempLinkRepo;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;

    /**
     * Step4Logic constructor.
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreeTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param AgreementSystemLogic $agreementSystemLogic
     */
    public function __construct(
        CorpAgreementTempLinkRepositoryInterface $corpAgreeTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        AgreementSystemLogic $agreementSystemLogic
    ) {
        $this->corpAgreeTempLinkRepo = $corpAgreeTempLinkRepo;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->agreementSystemLogic = $agreementSystemLogic;
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getStep4($corpId)
    {
        $corpAgreementTempLink = $this->corpAgreeTempLinkRepo->findLatestByCorpId($corpId);
        $corpCategoryList = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag($corpId, $corpAgreementTempLink->id, false, false)->toArray();

        return $corpCategoryList;
    }

    /**
     * @param object $user
     */
    public function step4Process($user)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        if (!is_null($corpAgreement)) {
            if ($corpAgreement->status == CorpAgreement::STEP3) {
                $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, CorpAgreement::STEP4, $user);
            }
        }
    }
}
