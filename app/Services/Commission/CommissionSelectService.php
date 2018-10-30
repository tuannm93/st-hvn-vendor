<?php

namespace App\Services\Commission;

use App\Repositories\MPostRepositoryInterface;
use App\Services\BaseService;

class CommissionSelectService extends BaseService
{
    /**
     * @var MPostRepositoryInterface
     */
    protected $mPostRepository;

    /**
     * CommissionSelectService constructor.
     *
     * @param \App\Repositories\MPostRepositoryInterface $mPostRepository
     */
    public function __construct(MPostRepositoryInterface $mPostRepository)
    {
        $this->mPostRepository = $mPostRepository;
    }

    /**
     * Check value of key address2 and add key jis_ci
     * Use in CommissionSelectController
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array $data
     * @return array
     */
    public function validateDataAndAddParams($data)
    {
        if (isset($data['address2'])) {
            $dateAddress2 = $data['address2'];
            $dateAddress2 = preg_replace('/^[ 　]+/u', '', $dateAddress2);
            $dateAddress2 = preg_replace('/[ 　]+$/u', '', $dateAddress2);
            $data['address2'] = $dateAddress2;
        }

        if (!isset($data['search'])) {
            $data['jis_cd'] = '';
            if (!empty($data['address1']) && $data['address1'] != '99') {
                $data['jis_cd'] = $this->mPostRepository->getTargetArea($data);
            }
        }

        return $data;
    }

    /**
     * Update value of key commission_unit_price_rank_
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array   $obj
     */
    private function changeCommissionUnitPrice1(&$obj)
    {
        if (empty($obj['targer_commission_unit_price'])) {
            $obj['commission_unit_price_rank_1'] = 'a';
        } elseif (empty($obj['commission_unit_price_category'])) {
            $obj['commission_unit_price_rank_1'] = 'd';
        } else {
            $rankF = $obj['commission_unit_price_category'] / $obj['targer_commission_unit_price'] * 100;
            if ($rankF >= 100) {
                $obj['commission_unit_price_rank_1'] = 'a';
            } elseif ($rankF >= 80) {
                $obj['commission_unit_price_rank_1'] = 'b';
            } elseif ($rankF >= 65) {
                $obj['commission_unit_price_rank_1'] = 'c';
            } else {
                $obj['commission_unit_price_rank_1'] = 'd';
            }
        }
    }

    /**
     * Update value of key commission_unit_price_rank_
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array   $obj
     */
    private function changeCommissionUnitPrice2(&$obj)
    {
        if (empty($obj['targer_commission_unit_price'])) {
            $obj['commission_unit_price_rank_2'] = 'a';
        } elseif (empty($obj['commission_unit_price'])) {
            $obj['commission_unit_price_rank_2'] = 'd';
        } else {
            $rankF = $obj['commission_unit_price'] / $obj['targer_commission_unit_price'] * 100;
            if ($rankF >= 100) {
                $obj['commission_unit_price_rank_2'] = 'a';
            } elseif ($rankF >= 80) {
                $obj['commission_unit_price_rank_2'] = 'b';
            } elseif ($rankF >= 65) {
                $obj['commission_unit_price_rank_2'] = 'c';
            } else {
                $obj['commission_unit_price_rank_2'] = 'd';
            }
        }
    }

    /**
     * Return new corp by update value of key commission_unit_price_rank_
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  array $corpList
     * @return array
     */
    public function getNewCorpList($corpList)
    {
        $temp = [];
        for ($i = 0; $i < count($corpList); $i++) {
            $obj = $corpList[$i];
            $this->changeCommissionUnitPrice1($obj);
            $this->changeCommissionUnitPrice2($obj);
            $temp[] = $obj;
        }
        return $temp;
    }
}
