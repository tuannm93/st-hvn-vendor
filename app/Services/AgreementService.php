<?php


namespace App\Services;

use App\Repositories\AgreementCustomizeRepositoryInterface;
use App\Repositories\AgreementRepositoryInterface;
use App\Services\Logic\AgreementSystemLogic;

class AgreementService
{

    /**
     * @var AgreementCustomizeRepositoryInterface
     */
    protected $agreementCustomizeRepository;
    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var AgreementRepositoryInterface
     */
    protected $agreementRepository;

    /**
     * AgreementService constructor.
     *
     * @param AgreementCustomizeRepositoryInterface $agreementCustomizeRepository
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param AgreementRepositoryInterface $agreementRepository
     */
    public function __construct(
        AgreementCustomizeRepositoryInterface $agreementCustomizeRepository,
        AgreementSystemLogic $agreementSystemLogic,
        AgreementRepositoryInterface $agreementRepository
    ) {
        $this->agreementCustomizeRepository = $agreementCustomizeRepository;
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->agreementRepository = $agreementRepository;
    }

    /**
     * @return string
     */
    public function getOriginalAgreement()
    {
        $agreementVersion = $this->agreementRepository->findCurrentVersion();
        $agreement = $this->agreementRepository->find($agreementVersion->agreement_id);
        $content = '';
        $provisionList = $agreement->agreementProvision->load('agreementProvisionItem');
        foreach ($provisionList as $provision) {
            $content .= $provision->getContentAndAllItems() . " \n";
        }
        return $content;
    }

    /**
     * @param $corpId
     * @return mixed|string
     */
    public function getCustomizeAgreement($corpId)
    {
        $numberOfAgreementCustomize = $this->agreementCustomizeRepository->findAgreementCustomizeByCorpId(
            $corpId,
            false
        )->count();
        if ($numberOfAgreementCustomize > 0) {
            $arrayProvision = $this->agreementSystemLogic->findCustomizedAgreementByCorpId($corpId);
            return $this->formatAgreementCustomize($arrayProvision);
        }
        return null;
    }

    /**
     * @param $arrayProvision
     * @return mixed
     */
    private function formatAgreementCustomize($arrayProvision)
    {
        $content = "";
        foreach ($arrayProvision as $provision) {
            $content .= (checkIsNullOrEmptyStr($provision['provisions']) ? "null" : $provision['provisions']) . " \n";
            if (array_key_exists('agreement_provision_item', $provision)) {
                foreach ($provision['agreement_provision_item'] as $item) {
                    $content .= " " . (checkIsNullOrEmptyStr($item['item']) ? "null" : $item['item']) . " \n";
                }
            }
            $content .= " \n";
        }
        return $content;
    }
}
