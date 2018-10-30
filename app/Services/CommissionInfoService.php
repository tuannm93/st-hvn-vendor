<?php

namespace App\Services;

use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use Illuminate\Support\Facades\Config;

class CommissionInfoService extends BaseService
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    private $commissionInfoRepository;
    /**
     *
     * @var MGenresRepositoryInterface
     */
    private $mGenresRepository;
    /**
     *
     * @var AffiliationInfoRepositoryInterface
     */
    private $affiliationInfoRepository;
    /**
     * CommissionInfoService constructor.
     *
     * @param CommissionInfoRepositoryInterface  $commissionInfoRepository
     * @param MGenresRepositoryInterface         $mGenresRepository
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        MGenresRepositoryInterface $mGenresRepository,
        AffiliationInfoRepositoryInterface $affiliationInfoRepository
    ) {
        $this->commissionInfoRepository = $commissionInfoRepository;
        $this->mGenresRepository = $mGenresRepository;
        $this->affiliationInfoRepository = $affiliationInfoRepository;
    }

    /**
     *
     * @param integer|null $corpId
     * @param integer|null $genreId
     * @param boolean      $displayPrice
     * @return integer
     */
    public function checkCredit($corpId = null, $genreId = null, $displayPrice = false)
    {
        // In the case of the following company ID, do not check credit limit
        if ($corpId == 1751  // 【開拓依頼中】- 【Pioneering request】
            || $corpId == 1755  // 【要ヒアリングor連絡待ち】- 【Hearing required or waiting for contact】
            || $corpId == 3539
        ) { // 【SF用】取次ぎ前失注用(質問のみ等) - 【For SF】 For forfeiting before interruption (question only)
            if ($displayPrice) {
                return 0;
            } else {
                return Config::get('rits.CREDIT_NORMAL');
            }
        }
        $result = $this->checkCreditSumPrice($corpId, $genreId, $displayPrice);

        return $result;
    }

    /**
     *
     * @param integer|null $corpId
     * @param integer|null $genreId
     * @param boolean      $displayPrice
     * @return integer|mixed
     */
    public function checkCreditSumPrice($corpId = null, $genreId = null, $displayPrice = false)
    {
        $sumCredit = 0;
        $result = Config::get('rits.CREDIT_NORMAL');

        $commissionInfo = $this->commissionInfoRepository->checkCreditSumPrice($corpId);
        if (!empty($commissionInfo[0]['sum_credit'])) {
            $sumCredit = (int)$commissionInfo[0]['sum_credit'];
        }

        if (!empty($genreId)) {
            $genre = $this->mGenresRepository->find($genreId);
            $sumCredit += $genre->credit_unit_price;
        }
        $affiliationInfo = $this->affiliationInfoRepository->findAffiliationInfoByCorpId($corpId);
        if (is_null($affiliationInfo['credit_limit'])) {
            // As for no credit limit input (NULL), it is excluded from checking.
            $result = Config::get('rits.CREDIT_NORMAL');
        } elseif ($sumCredit >= (int)$affiliationInfo['credit_limit'] + $affiliationInfo['add_month_credit']) {
            $result = Config::get('rits.CREDIT_DANGER');
        } elseif ((((int)$affiliationInfo['credit_limit'] + (int)$affiliationInfo['add_month_credit']) * Config::get('rits.WARNING_CREDIT_RATE')) <= $sumCredit) {
            $result = Config::get('rits.CREDIT_WARNING');
        }

        // If there is a charge display flag, the charge is returned
        if ($displayPrice) {
            $result = $sumCredit;
        }

        return $result;
    }
}
