<?php

namespace App\Services;

use App\Repositories\CommissionInfoRepositoryInterface;
use App\Repositories\DemandInquiryAnsRepositoryInterface;
use App\Repositories\MSiteRepositoryInterface;
use Config;
use PhpOffice\PhpWord\TemplateProcessor;

class CommissionPrintService
{
    /**
     * @var MSiteRepositoryInterface
     */
    public $mSiteRepo;

    /**
     * @var CommissionInfoRepositoryInterface
     */
    public $commInfoRepo;

    /**
     * @var DemandInquiryAnsRepositoryInterface
     */
    public $demandInquiryAnswerRepo;

    /**
     * CommissionPrintService constructor.
     *
     * @param MSiteRepositoryInterface            $mSiteRepo
     * @param CommissionInfoRepositoryInterface   $commInfoRepo
     * @param DemandInquiryAnsRepositoryInterface $demandInquiryAnsRepository
     */
    public function __construct(
        MSiteRepositoryInterface $mSiteRepo,
        CommissionInfoRepositoryInterface $commInfoRepo,
        DemandInquiryAnsRepositoryInterface $demandInquiryAnsRepository
    ) {
        $this->mSiteRepo = $mSiteRepo;
        $this->commInfoRepo = $commInfoRepo;
        $this->demandInquiryAnswerRepo = $demandInquiryAnsRepository;
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param $commissionId
     * @param $makeFile
     * @return string|void
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function exportWord($commissionId, &$makeFile)
    {
        /* Get [commission_infos] with [demand_infos] (with [m_sites] and [m_users]) by commission id*/
        $data = $this->commInfoRepo->getCommInfoForExportWordById($commissionId)->toArray();
        $inquiryData = [];
        $data['inquiry_data'] = '';

        /* Get [demand_inquiry_answers] with [m_inquiries] by demand_infos.id */
        if (! empty($data['demand_info']['id'])) {
            $inquiryData = $this->demandInquiryAnswerRepo->getDemandInquiryWithMInquiryByDemand($data['demand_info']['id'])->toArray();
        }
        if (! empty($inquiryData)) {
            $count = count($inquiryData) - 1;
            foreach ($inquiryData as $key => $val) {
                $data['inquiry_data'] .= $val['m_inquiry']['inquiry_name'].'：'.$val['answer_note'];
                if ($count != $key) {
                    $data['inquiry_data'] .= ', ';
                }
            }
        }

        return $this->makeWordFile($data, $makeFile, false);
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param $data
     * @param $makeFile
     * @param boolean  $isMailFile
     * @return string|void
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function makeWordFile($data, &$makeFile, $isMailFile = true)
    {
        /* Get m_sites by demand_info.site_id*/
        $site = $this->mSiteRepo->find($data['demand_info']['site_id']);

        /* Get path template docx in config file */
        if ($site->jbr_flg == 1 && $data['demand_info']['jbr_work_contents'] == Config::get('datacustom.jbr_glass_category')) {
            $template = Config::get('datacustom.commission_template_jbrglass');
        } elseif ($site->jbr_flg == 1) {
            $template = Config::get('datacustom.commission_template_jbr');
        } else {
            if (isset($data['commission_type']) && $data['commission_type'] == 1) {
                $template = Config::get('datacustom.commission_template_introduce');
            } else {
                $template = Config::get('datacustom.commission_template');
            }
        }

        /* Load template */
        $document = new TemplateProcessor($template);

        /* Set value to replace */
        $org = ["“", "”", "−"];
        $new = ["\"", "\"", "-"];

        $document->setValue('corp_name', e($data['m_corp']['official_corp_name']));
        $document->setValue('confirmd_fee_rate', $data['commission_fee_rate']);
        $document->setValue('demand_id', $data['demand_info']['id']);
        $document->setValue('site_name', e($site->site_name));
        $document->setValue('note', e($site->note));
        $document->setValue('customer_name', e(str_replace($org, $new, $data['demand_info']['customer_name'])));

        $customerAddress = getDivTextJP('prefecture_div', $data['demand_info']['address1']).$data['demand_info']['address2'].$data['demand_info']['address3'].$data['demand_info']['address4'].$data['demand_info']['building'].$data['demand_info']['room'];
        $customerAddress = str_replace($org, $new, $customerAddress);
        $document->setValue('address', e($customerAddress));

        $document->setValue('construction_class', getDropText('建物種別', $data['demand_info']['construction_class']));
        $document->setValue('tel1', $data['demand_info']['tel1']);
        $document->setValue('tel2', $data['demand_info']['tel2']);
        $document->setValue('contents', str_replace("\n", "<w:br/>", e(str_replace($org, $new, $data['demand_info']['contents']))));
        $document->setValue('contents1', $data['inquiry_data']);
        $document->setValue('receptionist', e($data['demand_info']['m_user']['user_name']));
        $document->setValue('commission_id', $data['id']);
        $document->setValue('jbr_order_no', e($data['demand_info']['jbr_order_no']));
        $document->setValue('jbr_work_contents', e(getDropText('[JBR様]作業内容', empty($data['demand_info']['jbr_work_contents']) ? 0 : $data['demand_info']['jbr_work_contents'])));

        /* Check directory is exist, if not then create it */
        if (! \File::exists(Config::get('datacustom.commission_tmp_dir'))) {
            \File::makeDirectory(Config::get('datacustom.commission_tmp_dir'), 0777, true, true);
        }
        $makeFile = Config::get('datacustom.commission_tmp_dir').sprintf('commission_%s_%s.docx', $data['demand_info']['id'], $data['id']);
        $document->saveAs($makeFile);

        if ($isMailFile) {
            $fileName = mb_encode_mimeheader(mb_convert_encoding(sprintf('%s_%s_%s.docx', __('commission_print.commission_print_name'), $data['m_corp']['official_corp_name'], $data['demand_id']), 'ISO-2022-JP', 'UTF-8'));
        } else {
            $fileName = sprintf('%s_%s_%s.docx', __('commission_print.commission_print_name'), $data['m_corp']['official_corp_name'], $data['demand_id']);
        }

        return $fileName;
    }
}
