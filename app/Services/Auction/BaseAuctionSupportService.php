<?php

namespace App\Services\Auction;

use App\Models\AuctionAgreementLink;
use App\Repositories\BillRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use App\Services\BaseService;

class BaseAuctionSupportService extends BaseService
{
    /**
     * @var MSiteRepositoryInterface
     */
    public $siteRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    public $categoryRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    public $corpRepository;
    /**
     * @var BillRepositoryInterface
     */
    public $billInfoRepository;
    /**
     * @var AuctionAgreementLink
     */
    public $auctionAgreementLink;

    /**
     * BaseAuctionSupportService constructor.
     *
     * @param \App\Repositories\MSiteRepositoryInterface $siteRepository
     * @param \App\Repositories\MCategoryRepositoryInterface $categoryRepository
     * @param \App\Repositories\MCorpRepositoryInterface $corpRepository
     * @param \App\Repositories\BillRepositoryInterface
     * @param \App\Models\AuctionAgreementLink
     */
    public function __construct(
        MSiteRepositoryInterface $siteRepository,
        MCategoryRepositoryInterface $categoryRepository,
        MCorpRepositoryInterface $corpRepository,
        BillRepositoryInterface $billInfoRepository,
        AuctionAgreementLink $auctionAgreementLink
    ) {
        $this->siteRepository = $siteRepository;
        $this->categoryRepository = $categoryRepository;
        $this->corpRepository = $corpRepository;
        $this->billInfoRepository = $billInfoRepository;
        $this->auctionAgreementLink = $auctionAgreementLink;
    }
}
