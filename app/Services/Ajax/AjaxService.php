<?php

namespace App\Services\Ajax;

use App\Repositories\ExclusionTimeRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Repositories\SelectGenreRepositoryInterface;

class AjaxService
{
    /**
     * @var MUserRepositoryInterface
     */
    public $mUserRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    public $mCategoryRepo;
    /**
     * @var MItemRepositoryInterface
     */
    public $mItemRepo;
    /**
     * @var MSiteRepositoryInterface
     */
    public $mSiteRepo;
    /**
     * @var MCorpRepositoryInterface
     */
    public $mCorpRepo;
    /**
     * @var MPostRepositoryInterface
     */
    public $mPostRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    public $mGenreRepo;
    /**
     * @var ExclusionTimeRepositoryInterface
     */
    public $exclusionTimeRepo;
    /**
     * @var SelectGenreRepositoryInterface
     */
    public $selectGenreRepo;

    /**
     * AjaxService constructor.
     * @param MUserRepositoryInterface $mUserRepo
     * @param MCategoryRepositoryInterface $mCategoryRepo
     * @param MItemRepositoryInterface $mItemRepo
     * @param MSiteRepositoryInterface $mSiteRepo
     * @param MCorpRepositoryInterface $mCorpRepo
     * @param MPostRepositoryInterface $mPostRepo
     * @param MGenresRepositoryInterface $mGenreRepo
     * @param ExclusionTimeRepositoryInterface $exclusionTimeRepo
     * @param SelectGenreRepositoryInterface $selectGenreRepo
     */
    public function __construct(
        MUserRepositoryInterface $mUserRepo,
        MCategoryRepositoryInterface $mCategoryRepo,
        MItemRepositoryInterface $mItemRepo,
        MSiteRepositoryInterface $mSiteRepo,
        MCorpRepositoryInterface $mCorpRepo,
        MPostRepositoryInterface $mPostRepo,
        MGenresRepositoryInterface $mGenreRepo,
        ExclusionTimeRepositoryInterface $exclusionTimeRepo,
        SelectGenreRepositoryInterface $selectGenreRepo
    ) {
        $this->mUserRepo = $mUserRepo;
        $this->mCategoryRepo = $mCategoryRepo;
        $this->mItemRepo = $mItemRepo;
        $this->mSiteRepo = $mSiteRepo;
        $this->mCorpRepo = $mCorpRepo;
        $this->mPostRepo = $mPostRepo;
        $this->mGenreRepo = $mGenreRepo;
        $this->exclusionTimeRepo = $exclusionTimeRepo;
        $this->selectGenreRepo = $selectGenreRepo;
    }
    /**
     * @param string $address1
     * @param string $address2
     * @return array
     */
    public function parseData($address1 = null, $address2 = null)
    {
        $data = [];
        if ($address1) {
            $data['address1'] = $this->getPrefectureJp($address1);
        }
        if ($address2) {
            $data['repAddress2'] = str_replace('ﾂ', 'ツ', str_replace('ﾉ', 'ノ', str_replace('ヶ', 'ケ', $address1)));
            $data['repAddress22'] = str_replace('ツ', 'ﾂ', str_replace('ノ', 'ﾉ', str_replace('ケ', 'ヶ', $data['repAddress2'])));
        }

        return $data;
    }

    /**
     * @param string $address
     * @return string
     */
    private function getPrefectureJp($address)
    {
        return config('datacustom.prefecture_div.' . $address);
    }

    /**
     * get ProcessType in AutoCommissionCorp
     * @param array $commissionCorps
     * @param object $targetCommissionCorp
     * @param object $value
     * @return mixed
     */
    public function checkTargetCommissionCorp($commissionCorps, $targetCommissionCorp, $value)
    {
        if ($targetCommissionCorp == null) {
            foreach ($commissionCorps as $commissionCorp) {
                if ($commissionCorp['corp_id'] == $value->m_corps_id) {
                    $targetCommissionCorp = $commissionCorp;
                    break;
                }
            }
        }
        return $targetCommissionCorp;
    }
}
