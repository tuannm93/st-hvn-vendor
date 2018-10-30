<?php

namespace App\Services\Ajax;

use App\Repositories\AutoCommissionCorpRepositoryInterface;
use App\Repositories\MAnswerRepositoryInterface;
use App\Repositories\MGeneralSearchRepositoryInterface;
use App\Repositories\MInquiryRepositoryInterface;
use App\Repositories\MSiteCategoryRepositoryInterface;
use App\Repositories\MSiteGenresRepositoryInterface;
use App\Repositories\SelectGenrePrefectureRepositoryInterface;

class InitializeAjaxService
{
    /**
     * @var MGeneralSearchRepositoryInterface
     */
    public $mGeneralSearchRepo;
    /**
     * @var SelectGenrePrefectureRepositoryInterface
     */
    public $selectGenrePrefectureRepo;
    /**
     * @var MSiteGenresRepositoryInterface
     */
    public $mSiteGenresRepo;
    /**
     * @var MSiteCategoryRepositoryInterface
     */
    public $mSiteCategoryRepo;
    /**
     * @var MInquiryRepositoryInterface
     */
    public $mInquiryRepo;
    /**
     * @var MAnswerRepositoryInterface
     */
    public $mAnswerRepo;
    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    public $autoCommissionCorpRepo;

    /**
     * InitializeAjaxService constructor.
     * @param MGeneralSearchRepositoryInterface $mGeneralSearchRepo
     * @param SelectGenrePrefectureRepositoryInterface $selectGenrePrefectureRepo
     * @param MSiteGenresRepositoryInterface $mSiteGenresRepo
     * @param MSiteCategoryRepositoryInterface $mSiteCategoryRepo
     * @param MInquiryRepositoryInterface $mInquiryRepo
     * @param MAnswerRepositoryInterface $mAnswerRepo
     * @param AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepo
     */
    public function __construct(
        MGeneralSearchRepositoryInterface $mGeneralSearchRepo,
        SelectGenrePrefectureRepositoryInterface $selectGenrePrefectureRepo,
        MSiteGenresRepositoryInterface $mSiteGenresRepo,
        MSiteCategoryRepositoryInterface $mSiteCategoryRepo,
        MInquiryRepositoryInterface $mInquiryRepo,
        MAnswerRepositoryInterface $mAnswerRepo,
        AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepo
    ) {
        $this->mGeneralSearchRepo = $mGeneralSearchRepo;
        $this->selectGenrePrefectureRepo = $selectGenrePrefectureRepo;
        $this->mSiteGenresRepo = $mSiteGenresRepo;
        $this->mSiteCategoryRepo = $mSiteCategoryRepo;
        $this->mInquiryRepo = $mInquiryRepo;
        $this->mAnswerRepo = $mAnswerRepo;
        $this->autoCommissionCorpRepo = $autoCommissionCorpRepo;
    }
}
