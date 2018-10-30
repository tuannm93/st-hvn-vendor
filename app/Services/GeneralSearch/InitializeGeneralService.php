<?php

namespace App\Services\GeneralSearch;

use App\Repositories\MAddress1RepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;

class InitializeGeneralService extends BaseGeneralSearchService
{
    /**
     * @var MSiteRepositoryInterface
     */
    public $siteRepo;
    /**
     * @var MAddress1RepositoryInterface
     */
    public $addressRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    public $genreRepo;
    /**
     * @var MCategoryRepositoryInterface
     */
    public $categoryRepo;
    /**
     * @var MUserRepositoryInterface
     */
    public $userRepo;

    /**
     * @var mixed
     */
    public $siteList;
    /**
     * @var mixed
     */
    public $address1List;
    /**
     * @var mixed
     */
    public $genreList;
    /**
     * @var mixed
     */
    public $categoryList;
    /**
     * @var mixed
     */
    public $userList;
    /**
     * @var mixed
     */
    public $userList2;


    /**
     * InitializeGeneralService constructor.
     * @param MSiteRepositoryInterface $siteRepo
     * @param MAddress1RepositoryInterface $addressRepo
     * @param MGenresRepositoryInterface $genreRepo
     * @param MCategoryRepositoryInterface $categoryRepo
     * @param MUserRepositoryInterface $userRepo
     */
    public function __construct(
        MSiteRepositoryInterface $siteRepo,
        MAddress1RepositoryInterface $addressRepo,
        MGenresRepositoryInterface $genreRepo,
        MCategoryRepositoryInterface $categoryRepo,
        MUserRepositoryInterface $userRepo
    ) {
        $this->siteRepo = $siteRepo;
        $this->addressRepo = $addressRepo;
        $this->genreRepo = $genreRepo;
        $this->categoryRepo = $categoryRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * init data
    */
    public function initData()
    {
        $this->siteList = $this->siteRepo->getList();
        $this->address1List = $this->addressRepo->getList();
        $this->genreList = $this->genreRepo->getList();
        $this->categoryList = $this->categoryRepo->getList();
        $this->userList = $this->userRepo->dropDownUser()->toarray();
        $this->userList2 = $this->userRepo->dropDownUserList();
    }
}
