<?php

namespace App\Services;

use App\Repositories\DemandAttachedFileRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MItemRepositoryInterface;
use App\Repositories\MSiteGenresRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;

class DemandListDetailService
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
     * @var MSiteGenresRepositoryInterface
     */
    public $mSiteGenresRepo;
    /**
     * @var DemandAttachedFileRepositoryInterface
     */
    public $demandAttachedFileRepo;
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
     * DemandListDetailService constructor.
     * @param MUserRepositoryInterface $mUserRepo
     * @param MCategoryRepositoryInterface $mCategoryRepo
     * @param MSiteGenresRepositoryInterface $mSiteGenresRepo
     * @param DemandAttachedFileRepositoryInterface $demandAttachedFileRepo
     * @param MItemRepositoryInterface $mItemRepo
     * @param MSiteRepositoryInterface $mSiteRepo
     * @param MCorpRepositoryInterface $mCorpRepo
     */
    public function __construct(
        MUserRepositoryInterface $mUserRepo,
        MCategoryRepositoryInterface $mCategoryRepo,
        MSiteGenresRepositoryInterface $mSiteGenresRepo,
        DemandAttachedFileRepositoryInterface $demandAttachedFileRepo,
        MItemRepositoryInterface $mItemRepo,
        MSiteRepositoryInterface $mSiteRepo,
        MCorpRepositoryInterface $mCorpRepo
    ) {
        $this->mUserRepo = $mUserRepo;
        $this->mCategoryRepo = $mCategoryRepo;
        $this->mSiteGenresRepo = $mSiteGenresRepo;
        $this->demandAttachedFileRepo = $demandAttachedFileRepo;
        $this->mItemRepo = $mItemRepo;
        $this->mSiteRepo = $mSiteRepo;
        $this->mCorpRepo = $mCorpRepo;
    }
}
