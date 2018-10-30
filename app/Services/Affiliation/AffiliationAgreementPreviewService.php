<?php

namespace App\Services\Affiliation;

use App\Models\MCorp;

use App\Repositories\CorpAgreementTempLinkRepositoryInterface;

use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpCategoryRepositoryInterface;


use App\Repositories\MPostRepositoryInterface;
use App\Services\BaseService;

class AffiliationAgreementPreviewService extends BaseService
{
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepo;
    /**
     * @var CorpAgreementTempLinkRepositoryInterface
     */
    protected $corpAgreementTempLinkRepo;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoryTempRepo;
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepo;

    /**
     * AffiliationAgreementPreviewService constructor.
     *
     * @param MPostRepositoryInterface $mPostRepo
     * @param CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoryTempRepo
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepo
     */
    public function __construct(
        MPostRepositoryInterface $mPostRepo,
        CorpAgreementTempLinkRepositoryInterface $corpAgreementTempLinkRepo,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoryTempRepo,
        MCorpCategoryRepositoryInterface $mCorpCategoryRepo
    ) {
        $this->mPostRepo = $mPostRepo;
        $this->corpAgreementTempLinkRepo = $corpAgreementTempLinkRepo;
        $this->mCorpCategoryTempRepo = $mCorpCategoryTempRepo;
        $this->mCorpCategoryRepo = $mCorpCategoryRepo;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getCorpArea($corpId)
    {
        $corpAreas = [];
        foreach (\Config::get('datacustom.prefecture_div') as $key => $value) {
            if ($key === 99) {
                continue;
            }
            $pref = [
                'id' => $key,
                'name' => $value,
                'rank' => 0
            ];
            $corpCount = $this->mPostRepo->getCorpPrefAreaCount($corpId, $value);
            if ($corpCount > 0) {
                $areaCount = $this->mPostRepo->getPrefAreaCount($value);
                if ($corpCount >= $areaCount) {
                    $pref['rank'] = 2;
                } else {
                    $pref['rank'] = 1;
                }
                $corpAreas[] = $pref;
            }
        }
        return $corpAreas;
    }

    /**
     * @param integer $corpId
     * @param integer $corpAgreement
     * @return array
     */
    public function getCategories($corpId, $corpAgreement)
    {
        $tempLink = $this->corpAgreementTempLinkRepo->getByCorpIdAndCorpAgreementId($corpId, $corpAgreement);
        $tempId = !empty($tempLink) ? $tempLink['id'] : null;
        $latestTempLink = $this->corpAgreementTempLinkRepo->getFirstByCorpId($corpId, $tempId);
        $categories = $this->mCorpCategoryTempRepo->findCategoryTempCopy($corpId, $tempId, $latestTempLink, $this->mCorpCategoryRepo, true);
        $categories = array_filter(
            $categories->toArray(),
            function ($category) {
                return $category['delete_flag'] == false;
            }
        );
        return $categories;
    }

    /**
     * @param string $listedKind
     * @return mixed
     */
    public function checkListedKind($listedKind)
    {
        if (!empty($listedKind)) {
            switch ($listedKind) {
                case MCorp::LISTED:
                    return MCorp::LISTED_KIND[MCorp::LISTED];
                    break;
                case MCorp::UNLISTED:
                    return MCorp::LISTED_KIND[MCorp::UNLISTED];
                    break;
                default:
                    return MCorp::LISTED_KIND[''];
            }
        } else {
            return MCorp::LISTED_KIND[''];
        }
    }
}
