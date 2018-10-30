<?php

namespace App\Services;

use App\Repositories\Eloquent\MItemRepository;
use DateTime;
use Excel;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_NumberFormat;

class ExportExcelService
{

    /**
     * ExportExcelService constructor.
     */
    public function __construct()
    {
    }

    /**
     * export excel
     *
     * @param  array $billList
     * @param $mCorpData
     * @return void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportBillExcel($billList, $mCorpData)
    {
        $fileName = MItemRepository::BILLING_INFORMATION . $mCorpData['official_corp_name'];
        $reader = PHPExcel_IOFactory::createReader('Excel2007');
        $templatePath = resource_path(MItemRepository::EXCEL_URL . 'bill_template.xlsx');
        $officialCorpName = $mCorpData['official_corp_name'];
        $book = $reader->load($templatePath);
        $book->setActiveSheetIndex(0);
        $sheet = $book->getActiveSheet();
        $sheet->setTitle($officialCorpName);
        $createDate = PHPExcel_Shared_Date::PHPToExcel(new DateTime(date("Y/m/d")));
        $sheet->setCellValue("G1", $createDate);
        $sheet->setCellValue("A5", $officialCorpName);
        $sheet->setCellValue("A13", MItemRepository::AT_YOUR_COMPANY . date("n", strtotime('-1 month')) . MItemRepository::EXCEL_DESCRIPTION);
        $count = 0;
        $totalFeeTaxExclude = 0;
        $totalTax = 0;
        $totalInsurancePrice = 0;
        foreach ($billList as $val) {
            $totalFeeTaxExclude = $totalFeeTaxExclude + $val['fee_tax_exclude'];
            $totalTax = $totalTax + $val['tax'];
            $totalInsurancePrice = $totalInsurancePrice + $val['insurance_price'];
            $sheet = $this->setExcelData($sheet, $val);

            $count++;
        }
        $num = $count + 19;
        if (!empty($mCorpData['past_bill_price'])) {
            $sheet = $this->setExcelValueForMCorp($sheet, $num, $mCorpData);
            $count++;
        }
        $num = $count + 19;
        $sheet->setCellValue("F" . $num, $totalFeeTaxExclude);
        $sheet->setCellValue("G" . $num, $totalTax);
        $sheet->setCellValue("H" . $num, $totalInsurancePrice);
        $num = $count + 20;
        $allMoney = $mCorpData['past_bill_price'] + $totalFeeTaxExclude + $totalTax + $totalInsurancePrice;
        $sheet->setCellValue("G" . $num, $allMoney);
        $string = MItemRepository::DEPOSIT . date("n", strtotime('+1 month')) . MItemRepository::EXCEL2 . PHP_EOL . MItemRepository::EXCEL3;
        $num = $count + 23;
        $sheet->setCellValue("A" . $num, $string);
        $num = $count + 26;
        $sheet->setCellValue("A" . $num, MItemRepository::EXCEL4 . $mCorpData['mcorp_id']);
        $this->configToDownload($book, $fileName);
    }

    /**
     * set excel sheet
     *
     * @param \PHPExcel_Worksheet $sheet
     * @param array $val
     * @throws \PHPExcel_Exception
     * @return \PHPExcel_Worksheet
     */
    protected function setExcelData($sheet, $val)
    {
        $sheet->insertNewRowBefore(19, 1);
        $sheet->getRowDimension(19)->setRowHeight(21);
        $sheet->setCellValue("A19", $val['demand_id']);
        $sheet->setCellValue("B19", $val['complete_date']);
        $sheet->setCellValue("C19", $val['customer_name']);
        $sheet->setCellValue("D19", $val['fee_target_price']);
        $sheet->setCellValue("E19", $val['comfirmed_fee_rate']);
        $sheet->setCellValue("F19", $val['fee_tax_exclude']);
        $sheet->setCellValue("G19", $val['tax']);
        $sheet->setCellValue("H19", $val['insurance_price']);
        $sheet->getStyle('A19')->getFont()->setSize(11);
        $sheet->getStyle('B19')->getFont()->setSize(11);
        $sheet->getStyle('C19')->getFont()->setSize(11);
        $sheet->getStyle('D19')->getFont()->setSize(11);
        $sheet->getStyle('E19')->getFont()->setSize(11);
        $sheet->getStyle('F19')->getFont()->setSize(11);
        $sheet->getStyle('G19')->getFont()->setSize(11);
        $sheet->getStyle('H19')->getFont()->setSize(11);
        $sheet->getStyle('D19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
        $sheet->getStyle('F19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
        $sheet->getStyle('G19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
        $sheet->getStyle('H19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
        $sheet->getStyle('B19')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
        $sheet->getStyle('D19')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
        $sheet->getStyle('F19')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
        $sheet->getStyle('G19')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
        $sheet->getStyle('H19')->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
        $sheet->getStyle('A19')->getAlignment()->setShrinkToFit(true);
        $sheet->getStyle('B19')->getAlignment()->setShrinkToFit(true);
        $sheet->getStyle('C19')->getAlignment()->setShrinkToFit(true);
        return $sheet;
    }

    /**
     * set excel value if not empty m_corps data
     *
     * @param \PHPExcel $sheet
     * @param integer $num
     * @param array $mCorpData
     * @return \PHPExcel
     */
    protected function setExcelValueForMCorp($sheet, $num, $mCorpData)
    {
        $sheet->insertNewRowBefore($num, 1);
        $sheet->getRowDimension($num)->setRowHeight(21);
        $sheet->mergeCells('D' . $num . ':E' . $num);
        $sheet->mergeCells('F' . $num . ':H' . $num);
        $sheet->setCellValue("A" . $num, 'その他');
        $sheet->setCellValue("C" . $num, '前月繰越残高');
        $sheet->getStyle("A" . $num)->getFont()->setSize(11);
        $sheet->getStyle("C" . $num)->getFont()->setSize(11);
        $sheet->getStyle("A" . $num)->getAlignment()->setShrinkToFit(true);
        $sheet->getStyle("C" . $num)->getAlignment()->setShrinkToFit(true);
        $sheet->getStyle("F" . $num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);
        $sheet->getStyle("F" . $num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F" . $num)->getNumberFormat()->setFormatCode('#,##0;[Red]-#,##0');
        $sheet->setCellValue("F" . $num, $mCorpData['past_bill_price']);
        return $sheet;
    }

    /**
     * Setting header to download
     *
     * @param  $book
     * @param  $fileName
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    protected function configToDownload($book, $fileName)
    {
        $objWriter = PHPExcel_IOFactory::createWriter($book, 'Excel2007');
        $fileName = $fileName . '.xlsx';
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream name='" . $fileName . "'");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $fileName . "");
        header("Content-Transfer-Encoding: binary ");
        $objWriter->save('php://output');
        exit();
    }
}
