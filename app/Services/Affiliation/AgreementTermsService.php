<?php

namespace App\Services\Affiliation;

use App\Repositories\AgreementCustomizeRepositoryInterface;
use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Services\BaseService;
use Mpdf\Mpdf;

class AgreementTermsService extends BaseService
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var AgreementCustomizeRepositoryInterface
     */
    protected $agreementCustomizeRepository;

    /**
     * AgreementTermsService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param AgreementCustomizeRepositoryInterface $agreementCustomizeRepository
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        AgreementCustomizeRepositoryInterface $agreementCustomizeRepository
    ) {
        $this->mCorpRepository = $mCorpRepository;
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->agreementCustomizeRepository = $agreementCustomizeRepository;
    }

    /**
     * output PDF
     *
     * @param integer $corpId
     * @param integer $agreementId
     * @return file
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function outputPDF($corpId, $agreementId)
    {
        $corpData = $this->mCorpRepository->findMcorp($corpId);
        $corpAgreement = $this->getCorpAgreement($corpId, $agreementId);
        $agreementId = $corpAgreement->id;
        $corpName = !empty($corpData) ? $corpData->official_corp_name : '';
        $orgTerms = empty($corpAgreement->original_agreement) ?
            "" : str_replace(["\n", "\r\n"], "<br>", $corpAgreement->original_agreement);
        $cstTerms = empty($corpAgreement->customize_agreement) ?
            "" : str_replace(["\n", "\r\n"], "<br>", $corpAgreement->customize_agreement);
        $tmpDate = isset($corpAgreement->acceptation_date) ?
            str_replace('-', '/', $corpAgreement->acceptation_date) : "";
        $tmpArray = explode(':', $tmpDate, -1);
        if (count($tmpArray)) {
            $agreementDate = $tmpArray[0] . ':' . $tmpArray[1];
        } else {
            $agreementDate = null;
        }
        $diffProv = $this->getDataForPDF($corpData, $corpAgreement);
        $termsTable = view(
            'affiliation.agreement_temp_outline',
            compact(
                'orgTerms',
                'cstTerms',
                'corpId',
                'corpName',
                'agreementId',
                'agreementDate'
            )
        )
            ->render();
        $diffTable = view('affiliation.agreement_diff_table', compact('diffProv'))->render();
        $fileNameTmpl = trans('agreement_terms.file_name_term');
        $fileName = "【" . $corpName . "】" . $fileNameTmpl . ".pdf";
        $config = $this->getConfigPdf();
        $pdf = new Mpdf($config);
        $pdf->SetTitle($fileName);
        $pdf->writeHTML($termsTable);
        $pdf->AddPage('LANDSCAPE', 'A3');
        $pdf->writeHTML($diffTable);
        return $pdf->Output($fileName, "I");
    }

    /**
     * get corp agreement
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return mixed
     */
    private function getCorpAgreement($corpId, $agreementId)
    {
        if (!empty($agreementId)) {
            $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId(
                $corpId,
                $agreementId
            );
        } else {
            $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId($corpId, null, true);
        }
        return $corpAgreement;
    }

    /**
     * get data for pdf
     * @param  array $corpData
     * @param  array $corpAgreement
     * @return array
     */
    private function getDataForPDF($corpData, $corpAgreement)
    {
        if (!$corpData || !$corpAgreement) {
            return [];
        }
        $corpId = $corpData->id;
        $agreementId = $corpAgreement->id;
        $customProvisions = $this->agreementCustomizeRepository->getByCorpIdAndCorpAgreementIdAndTableKind(
            $corpId,
            $agreementId
        );
        $titleList = []; // List of changed terms
        $cstList = []; //List of added provisions
        $this->getDataTermAndProvisions($customProvisions, $titleList, $cstList);
        $modItemProvisions = $this->agreementCustomizeRepository->findDataTerms($corpId, $agreementId);
        $this->customTitleList($modItemProvisions, $titleList);
        $modArray = [];
        $sortMax = 0;
        $addedArray = [];
        $this->sortTitleList($titleList, $modArray, $sortMax);
        $this->sortCstList($cstList, $modArray, $addedArray);
        ksort($modArray);
        return array_merge($addedArray, $modArray);
    }

    /**
     * get data terms and provisions
     * @param  array $customProvisions
     * @param  array $titleList
     * @param  array $cstList
     * @return void
     */
    private function getDataTermAndProvisions($customProvisions, &$titleList, &$cstList)
    {
        foreach ($customProvisions as $item) {
            $originalProvisionsId = $item['original_provisions_id'];
            $customizeProvisionsId = $item['customize_provisions_id'];
            $corpAgreementId = $item['corp_agreement_id'];
            if ($originalProvisionsId == 0) {
                if (!isset($cstList[$customizeProvisionsId])
                    || $corpAgreementId >= $cstList[$customizeProvisionsId]['corp_agreement_id']
                ) {
                    if ($item['edit_kind'] != 'Delete') {
                        $cstList[$customizeProvisionsId] = $item;
                    } else {
                        unset($cstList[$customizeProvisionsId]);
                    }
                }
            } elseif (!isset($titleList[$originalProvisionsId])
                || $corpAgreementId >= $titleList[$originalProvisionsId]['corp_agreement_id']
            ) {
                if ($item['edit_kind'] != 'Delete') {
                    $titleList[$originalProvisionsId] = $item;
                } else {
                    unset($titleList[$originalProvisionsId]);
                }
            }
        }
    }

    /**
     * custom title list
     * @param  array $modItemProvisions
     * @param  array $titleList
     * @return void
     */
    private function customTitleList($modItemProvisions, &$titleList)
    {
        foreach ($modItemProvisions as $item) {
            $agreementProvisions = $item->agreementProvision;
            $item = $item->toArray();
            $originalProvisionsId = $item['original_provisions_id'];
            if (!isset($titleList[$originalProvisionsId])) {
                $item['sort_no'] = $agreementProvisions->sort_no;
                $item['content'] = $agreementProvisions->provisions;
                $titleList[$originalProvisionsId] = $item;
            }
        }
    }

    /**
     * sort title list
     * @param  array $titleList
     * @param  array $modArray
     * @param  integer $sortMax
     * @return void
     */
    private function sortTitleList(&$titleList, &$modArray, &$sortMax)
    {
        foreach ($titleList as $item) {
            $sortNo = $item['sort_no'];
            $content = $item['content'];
            $modArray[$sortNo] = $content;

            if ($sortMax <= $sortNo) {
                $sortMax = $sortNo;
            }
        }
    }

    /**
     * sort cts list
     * @param  array $cstList
     * @param  array $modArray
     * @param  array $addedArray
     * @return void
     */
    private function sortCstList($cstList, &$modArray, &$addedArray)
    {
        foreach ($cstList as $item) {
            $sortNo = $item['sort_no'];
            $content = $item['content'];

            if ($sortNo == 0) {
                $addedArray[] = $content;
            } else {
                $modArray[$sortNo] = $content;
            }
        }
    }

    /**
     * get config pdf
     * @return array
     */
    private function getConfigPdf()
    {
        return [
            'mode' => '+aCJK',
            'format' => 'A3-L',
            'default_font' => 'sjis',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 8,
            'margin_header' => 8,
            'margin_footer' => 8,
            'orientation' => 'P',
        ];
    }
}
