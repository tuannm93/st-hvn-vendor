<?php


namespace App\Services\Logic;

use App\Models\CorpAgreement;
use App\Repositories\CorpAgreementTempLinkRepositoryInterface;
use App\Repositories\MAddress1RepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;

class Step3Logic
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
     * @var MAddress1RepositoryInterface
     */
    protected $mAddress1Repository;
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;

    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepository;

    /**
     * Step3Logic constructor.
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreeTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param MAddress1RepositoryInterface $mAddress1Repository
     * @param MPostRepositoryInterface $mPostRepository
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     */
    public function __construct(
        CorpAgreementTempLinkRepositoryInterface $corpAgreeTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        AgreementSystemLogic $agreementSystemLogic,
        MAddress1RepositoryInterface $mAddress1Repository,
        MPostRepositoryInterface $mPostRepository,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepository
    ) {
        $this->corpAgreeTempLinkRepo = $corpAgreeTempLinkRepo;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->mAddress1Repository = $mAddress1Repository;
        $this->mPostRepository = $mPostRepository;
        $this->mCorpCategoryRepository = $mCorpCategoryRepository;
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getStep3($corpId)
    {
        $corpAgreementTempLink = $this->corpAgreeTempLinkRepo->findLatestByCorpId($corpId);
        $corpCategoryList = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag(
            $corpId,
            $corpAgreementTempLink->id,
            false,
            false
        )->toArray();
        return $corpCategoryList;
    }

    /**
     * @param $corpId
     * @param $corpAgreementId
     * @return null
     */
    public function getCorpCategoryListForReport($corpId, $corpAgreementId)
    {
        $corpAgreementTempLink = $this->corpAgreeTempLinkRepo->getByCorpIdAndCorpAgreementId($corpId, $corpAgreementId);
        if (is_null($corpAgreementTempLink)) {
            $corpAgreementTempLink = $this->corpAgreeTempLinkRepo->findLatestByCorpId($corpId, 'asc');
        }
        $corpCategoryList = null;
        if (!is_null($corpAgreementTempLink)) {
            $corpCategoryList = $this->mCorpCategoriesTempRepository->findAllByCorpIdAndTempIdWithFlag(
                $corpId,
                $corpAgreementTempLink->id,
                false,
                false
            )->toArray();
        } else {
            $corpCategoryList = $this->mCorpCategoryRepository->findAllByCorpId($corpId)->toArray();
        }
        return $corpCategoryList;
    }

    /**
     * @param object $user
     */
    public function step3Process($user)
    {
        $corpAgreement = $this->agreementSystemLogic->checkFirstCorpAgreementNotComplete($user);
        if (!is_null($corpAgreement)) {
            if ($corpAgreement->status == CorpAgreement::STEP2) {
                $this->agreementSystemLogic->updateCorpAgreement($corpAgreement, CorpAgreement::STEP3, $user);
            }
        }
    }

    /**
     * @param integer $corpId
     * @return mixed
     */
    public function getPrefList($corpId)
    {
        $prefList = $this->mAddress1Repository->findByCorpIdAndPrefecturalCode($corpId);
        foreach ($prefList as $address) {
            $dataPostCount = 0;
            $registPostCount = 0;
            $postList = $this->mPostRepository->findByCorpIdAndPrefecturalCode($corpId, $address->address1_cd);
            foreach ($postList as $post) {
                $dataPostCount++;
                if ($post['corp_id'] != null) {
                    $registPostCount++;
                }
            }
            $status = 2;
            if ($registPostCount == 0) {
                $status = 1;
            } elseif ($dataPostCount == $registPostCount) {
                $status = 3;
            }
            $address->status = $status;
        }
        return $prefList;
    }
}
