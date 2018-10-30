<?php

namespace App\Services\Affiliation;

use App\Repositories\MPostRepositoryInterface;
use Illuminate\Support\Facades\Config;

class AffiliationTargetService
{

    /**
     * @var MPostRepositoryInterface
     */
    private $mPostRepository;

    /**
     * AffiliationTargetService constructor.
     *
     * @param MPostRepositoryInterface $mPostRepository
     */
    public function __construct(MPostRepositoryInterface $mPostRepository)
    {
        $this->mPostRepository = $mPostRepository;
    }

    /**
     * @param integer $corpId
     * @return array
     */
    public function getPrefList($corpId)
    {
        // Prefecture list (All region correspondence -
        // Partial region correspondence - No correspondence available setting)
        $prefList = [];

        foreach (Config::get('rits.prefecture_div') as $prefectureDivKey => $prefectureDivValue) {
            // 99 skipped reading
            if ($prefectureDivKey == 99) {
                continue;
            }
            $obj = [];
            $obj['id'] = $prefectureDivKey;
            $translatedPrefectureDivValue = __("rits_config.$prefectureDivValue");
            $obj['name'] = $translatedPrefectureDivValue;
            // Number of areas set by franchisees of designated prefectures
            $corpCount = $this->mPostRepository->getCorpCategoryAreaCount($corpId, $translatedPrefectureDivValue);
            if ($corpCount > 0) {
                // Number of areas in the specified prefecture
                $areaCount = $this->mPostRepository->getPrefAreaCount($translatedPrefectureDivValue);
                if ($corpCount >= $areaCount) {
                    // All regions correspondence
                    $obj['rank'] = 2;
                } else {
                    // For some areas
                    $obj['rank'] = 1;
                }
            } else {
                $obj['rank'] = 0;
            }
            $prefList[] = $obj;
        }

        return $prefList;
    }
}
