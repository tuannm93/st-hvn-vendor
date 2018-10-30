<?php

namespace App\Services\Commission;

use App\Services\BaseService;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\MCategoryRepositoryInterface;
use App\Repositories\MTaxRateRepositoryInterface;

class CalculatorService extends BaseService
{
    /**
     * @var MTaxRateRepositoryInterface
     */
    private $taxRateRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var MCategoryRepositoryInterface
     */
    private $commissionInfoRepository;

    /**
     * CalculatorService constructor.
     * @param MTaxRateRepositoryInterface $taxRateRepository
     * @param MCategoryRepositoryInterface $categoryRepository
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     */
    public function __construct(
        MTaxRateRepositoryInterface $taxRateRepository,
        MCategoryRepositoryInterface $categoryRepository,
        CommissionInfoRepositoryInterface $commissionInfoRepository
    ) {
        $this->taxRateRepository = $taxRateRepository;
        $this->categoryRepository = $categoryRepository;
        $this->commissionInfoRepository = $commissionInfoRepository;
    }

    /**
     * @param string $date
     * @return float|int|string
     */
    public function getTaxRates($date = null)
    {
        $result = '';

        if (!empty($date)) {
            $row = $this->taxRateRepository->findByDate($date);
            $result = $row->tax_rate * 100;
        }

        return $result;
    }

    /**
     * @param string $date
     * @return array
     */
    public function getMTaxRates($date = null)
    {
        $result = [
            'tax_rate_val' => '',
            'tax_rate' => ''

        ];

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        if (!empty($date)) {
            $row = $this->taxRateRepository->findByDate($date);

            if ($row) {
                $result['tax_rate_val'] = $row->tax_rate;
                $result['tax_rate'] = $row->tax_rate * 100;
            }
        }

        return $result;
    }

    /**
     * @param integer $commissionId
     * @param integer $commissionStatus
     * @param string $completeDate
     * @param float $constructionPriceTaxExclude
     * @return array
     */
    public function calcBillPrice($commissionId, $commissionStatus, $completeDate, $constructionPriceTaxExclude)
    {
        $data = $this->commissionInfoRepository->findCommissionInfo($commissionId);
        $data['CommissionInfo__commission_status'] = $commissionStatus;
        $data['CommissionInfo__complete_date'] = $completeDate;
        $data['CommissionInfo__construction_price_tax_exclude'] = $constructionPriceTaxExclude;

        $data = $this->calculateBillPrice($data);

        $result = [
            'MTaxRate' => ['tax_rate' => $data['MTaxRate__tax_rate']],
            'CommissionInfo' => [
                'construction_price_tax_exclude' => $data['CommissionInfo__construction_price_tax_exclude'],
                'construction_price_tax_include' => $data['CommissionInfo__construction_price_tax_include'],
                'corp_fee' => $data['CommissionInfo__corp_fee'],
                'deduction_tax_exclude' => $data['CommissionInfo__deduction_tax_exclude'],
                'deduction_tax_include' => $data['CommissionInfo__deduction_tax_include'],
                'confirmd_fee_rate' => $data['CommissionInfo__confirmd_fee_rate'],
            ],
            'BillInfo' => [
                'fee_target_price' => $data['BillInfo__fee_target_price'],
                'fee_tax_exclude' => $data['BillInfo__fee_tax_exclude'],
                'tax' => $data['BillInfo__tax'],
                'insurance_price' => $data['BillInfo__insurance_price'],
                'total_bill_price' => $data['BillInfo__total_bill_price']
            ],
        ];

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public function calculateBillPrice($data)
    {
        $taxRate = $this->getMTaxRates($data['CommissionInfo__complete_date']);
        $data['MTaxRate__tax_rate'] = $taxRate['tax_rate'];

        $data = $this->fillDataWithStatusNotIntroduction($data, $taxRate);

        $data = $this->fillOrderFeeUnit($data);

        $data = $this->fillFeeRateAndFeeTaxExclude($data);

        $data = $this->fillTax($data, $taxRate);

        return $data;
    }

    /**
     * @param $data
     * @param $taxRate
     * @return mixed
     */
    private function fillDataWithStatusNotIntroduction($data, $taxRate)
    {
        if ($data['CommissionInfo__commission_status'] != getDivValue('construction_status', 'introduction')) {
            $constructionPriceTaxExclude = $data['CommissionInfo__construction_price_tax_exclude'];

            if (empty($constructionPriceTaxExclude)) {
                $constructionPriceTaxExclude = 0;
            }

            if (empty($data['CommissionInfo__business_trip_amount'])) {
                $data['CommissionInfo__business_trip_amount'] = 0;
            }

            if (empty($data['CommissionInfo__deduction_tax_include'])) {
                $data['CommissionInfo__deduction_tax_include'] = 0;
            }

            $data = $this->fillTaxRate($data, $taxRate, $constructionPriceTaxExclude);

            $data = $this->fillFeeTargetPrice($data, $constructionPriceTaxExclude);

            $data = $this->fillInsurancePrice($data, $constructionPriceTaxExclude);
        }
        return $data;
    }

    /**
     * @param $data
     * @param $taxRate
     * @param $constructionPriceTaxExclude
     * @return mixed
     */
    private function fillTaxRate($data, $taxRate, $constructionPriceTaxExclude)
    {
        if ($taxRate['tax_rate_val'] != '') {
            if (!empty($data['CommissionInfo__construction_price_tax_exclude'])) {
                $data['CommissionInfo__construction_price_tax_include'] = round($constructionPriceTaxExclude * (1 + $taxRate['tax_rate_val']));
            } else {
                $data['CommissionInfo__construction_price_tax_include'] = $data['CommissionInfo__construction_price_tax_exclude'];
            }

            if (!empty($data['CommissionInfo__deduction_tax_include'])) {
                $data['CommissionInfo__deduction_tax_exclude'] = round($data['CommissionInfo__deduction_tax_include'] / (1 + $taxRate['tax_rate_val']));
            } else {
                $data['CommissionInfo__deduction_tax_exclude'] = 0;
            }
        } else {
            $data['CommissionInfo__construction_price_tax_include'] = $data['CommissionInfo__construction_price_tax_exclude'];
            $data['CommissionInfo__deduction_tax_exclude'] = $data['CommissionInfo__deduction_tax_include'];
        }

        if (empty($data['CommissionInfo__deduction_tax_exclude'])) {
            $data['CommissionInfo__deduction_tax_exclude'] = 0;
        }
        return $data;
    }

    /**
     * @param $data
     * @param $constructionPriceTaxExclude
     * @return mixed
     */
    private function fillInsurancePrice($data, $constructionPriceTaxExclude)
    {
        if ($data['MGenre__insurant_flg'] == 1 && $data['AffiliationInfo__liability_insurance'] == 2) {
            // 保険料 = 施工金額(税抜) × 0.01
            $data['BillInfo__insurance_price'] = round($constructionPriceTaxExclude * 0.01);
        } else {
            $data['BillInfo__insurance_price'] = 0;
        }
        return $data;
    }

    /**
     * @param $data
     * @param $constructionPriceTaxExclude
     * @return mixed
     */
    private function fillFeeTargetPrice($data, $constructionPriceTaxExclude)
    {
        if ($constructionPriceTaxExclude != 0) {
            $data['BillInfo__fee_target_price']
                = $constructionPriceTaxExclude - $data['CommissionInfo__deduction_tax_exclude'];
        } else {
            $data['BillInfo__fee_target_price'] = 0;
        }
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function fillDataFeedRateInNotConsAndProd($data)
    {
        if (!empty($data['CommissionInfo__irregular_fee_rate'])) {
            $data['CommissionInfo__confirmd_fee_rate'] = $data['CommissionInfo__irregular_fee_rate'];
        } else {
            if (empty($data['CommissionInfo__confirmd_fee_rate'])) {
                $data['CommissionInfo__confirmd_fee_rate'] = $data['CommissionInfo__commission_fee_rate'];
            }
        }

        if (!empty($data['CommissionInfo__irregular_fee'])) {
            $data['BillInfo__fee_tax_exclude'] = $data['CommissionInfo__irregular_fee'];
        } else {
            $data['BillInfo__fee_tax_exclude'] = round($data['BillInfo__fee_target_price'] * $data['CommissionInfo__confirmd_fee_rate'] * 0.01);
        }
        if (!empty($data['BillInfo__fee_tax_exclude'])) {
            $data['CommissionInfo__corp_fee'] = $data['BillInfo__fee_tax_exclude'];
        }
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function fillDataFeedRateInConsAndProd($data)
    {
        if (!empty($data['CommissionInfo__irregular_fee'])) {
            $data['BillInfo__fee_tax_exclude'] = $data['CommissionInfo__irregular_fee'];
        } else {
            $data['BillInfo__fee_tax_exclude'] = $data['CommissionInfo__corp_fee'];
        }

        if ($data['CommissionInfo__commission_status'] == getDivValue('construction_status', 'introduction')) {
            $data['BillInfo__fee_target_price'] = $data['BillInfo__fee_tax_exclude'];

            if ($data['CommissionInfo__introduction_free'] == 1) {
                $data['BillInfo__fee_tax_exclude'] = 0;
            }
        }
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function fillFeeRateAndFeeTaxExclude($data)
    {
        if ($data['CommissionInfo__order_fee_unit'] != 0 && $data['CommissionInfo__commission_status'] != getDivValue('construction_status', 'introduction')) {
            $data = $this->fillDataFeedRateInNotConsAndProd($data);
        } else {
            $data = $this->fillDataFeedRateInConsAndProd($data);
        }
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function fillOrderFeeUnit($data)
    {
        if (is_null($data['CommissionInfo__order_fee_unit'])) {
            if (is_null($data['MCorpCategory__order_fee_unit'])) {
                $defaultCategory = $this->categoryRepository->getDefaultFee($data['DemandInfo__category_id']);
                $data['CommissionInfo__order_fee_unit'] = $defaultCategory['category_default_fee_unit'];
            } else {
                $data['CommissionInfo__order_fee_unit'] = $data['MCorpCategory__order_fee_unit'];
            }
        }
        return $data;
    }

    /**
     * @param $data
     * @param $taxRate
     * @return mixed
     */
    private function fillTax($data, $taxRate)
    {
        if (!empty($taxRate['tax_rate_val'])) {
            $data['BillInfo__tax'] = round($data['BillInfo__fee_tax_exclude'] * $taxRate['tax_rate_val']);
        } else {
            $data['BillInfo__tax'] = 0;
        }

        $feeTaxExclude = !empty($data['BillInfo__fee_tax_exclude']) ? $data['BillInfo__fee_tax_exclude'] : 0;
        $data['BillInfo__total_bill_price'] = $feeTaxExclude + $data['BillInfo__tax'] + $data['BillInfo__insurance_price'];

        return $data;
    }
}
