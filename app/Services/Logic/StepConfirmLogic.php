<?php


namespace App\Services\Logic;

use App\Repositories\CorpAgreementRepositoryInterface;
use App\Repositories\MAddress1RepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MUserRepositoryInterface;
use App\Services\Affiliation\AffiliationCorpService;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class StepConfirmLogic
{

    /**
     * @var AgreementSystemLogic
     */
    protected $agreementSystemLogic;
    /**
     * @var AffiliationCorpService
     */
    protected $affiliationCorpService;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepository;
    /**
     * @var CorpAgreementRepositoryInterface
     */
    protected $corpAgreementRepository;
    /**
     * @var Step2Logic
     */
    protected $step2Logic;
    /**
     * @var Step3Logic
     */
    protected $step3Logic;
    /**
     * @var StepConfirmBusiness
     */
    protected $stepConfirmBusiness;

    /**
     * @var MAddress1RepositoryInterface
     */
    protected $mAddress1Repo;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepo;

    /**
     * @var MUserRepositoryInterface
     */
    protected $mUserRepo;

    /**
     * @var Step1Logic
     */
    protected $step1Logic;

    /**
     * StepConfirmLogic constructor.
     * @param AffiliationCorpService $affiliationCorpService
     * @param AgreementSystemLogic $agreementSystemLogic
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository
     * @param CorpAgreementRepositoryInterface $corpAgreementRepository
     * @param Step1Logic $step1Logic
     * @param Step2Logic $step2Logic
     * @param Step3Logic $step3Logic
     * @param StepConfirmBusiness $stepConfirmBusiness
     * @param MAddress1RepositoryInterface $mAddress1Repo
     * @param MCorpRepositoryInterface $mCorpRepo
     * @param MUserRepositoryInterface $mUserRepo
     */
    public function __construct(
        AffiliationCorpService $affiliationCorpService,
        AgreementSystemLogic $agreementSystemLogic,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepository,
        CorpAgreementRepositoryInterface $corpAgreementRepository,
        Step1Logic $step1Logic,
        Step2Logic $step2Logic,
        Step3Logic $step3Logic,
        StepConfirmBusiness $stepConfirmBusiness,
        MAddress1RepositoryInterface $mAddress1Repo,
        MCorpRepositoryInterface $mCorpRepo,
        MUserRepositoryInterface $mUserRepo
    ) {
        $this->affiliationCorpService = $affiliationCorpService;
        $this->agreementSystemLogic = $agreementSystemLogic;
        $this->mCorpCategoriesTempRepository = $mCorpCategoriesTempRepository;
        $this->corpAgreementRepository = $corpAgreementRepository;
        $this->step1Logic = $step1Logic;
        $this->step2Logic = $step2Logic;
        $this->step3Logic = $step3Logic;
        $this->stepConfirmBusiness = $stepConfirmBusiness;
        $this->mAddress1Repo = $mAddress1Repo;
        $this->mCorpRepo = $mCorpRepo;
        $this->mUserRepo = $mUserRepo;
    }

    /**
     * @param object $mCorp
     * @return array
     */
    public function getConfirm($mCorp)
    {
        $corpId = $mCorp->id;

        $arrayProvision = $this->agreementSystemLogic->findCustomizedAgreementByCorpId($corpId);
        $affiliationInfo = $this->affiliationCorpService->getAffiliationInfo($corpId);
        $data = $this->step2Logic->getDataStep2($mCorp->responsibility);
        $mCorpSubs = $this->affiliationCorpService->getMCorpSubByMCorpId($mCorp->id);
        $corpHolidays = $mCorpSubs['holiday'];
        $prefList = $this->step3Logic->getPrefList($corpId);
        $corpCategoryList = $this->step3Logic->getStep3($corpId);
        $viewData = [
            'arrayProvision' => $arrayProvision,
            'affiliationInfo' => $affiliationInfo,
            'data' => $data,
            'corpHolidays' => $corpHolidays, 'prefList' => $prefList,
            'corpCategoryList' => $corpCategoryList];
        return $viewData;
    }

    /**
     * @param object $user
     * @return mixed
     */
    public function postConfirm($user)
    {
        $corpAgreement = $this->corpAgreementRepository->getFirstByCorpIdAndAgreementId($user->affiliation_id, null, true);
        $this->stepConfirmBusiness->stepConfirmProcess($user);
        return $corpAgreement->id;
    }

    /**
     * @param $corpId
     * @param $corpAgreementId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    public function downloadAgreementReport($corpId, $corpAgreementId)
    {
        $corpAgreement = $this->getCorpAgreement($corpId, $corpAgreementId);

        $arrayProvision = $this->getAgreementList($corpAgreement);

        $corpCategoryList = $this->step3Logic->getCorpCategoryListForReport($corpId, $corpAgreementId);

        $result = $this->getCommonInfo($corpId, $corpAgreement);
        $result['arrayProvision'] = $arrayProvision;
        $result['corpCategoryList'] = $corpCategoryList;
        $result['corpId'] = $corpId;

        set_time_limit(0);
        ini_set("pcre.backtrack_limit", "5000000");
        $file = $this->outputPDF($result);
        return $file;
    }

    /**
     * @param $corpId
     * @param $corpAgreement
     * @return array
     */
    private function getCommonInfo($corpId, $corpAgreement)
    {
        $ownInfoStr = $this->getOwnInfo();
        $affiliationInfo = $this->getAffiliationInfo($corpId);

        $hansxyaDate = "";
        $completeDate = "";
        if (!is_null($corpAgreement)) {
            $hansxyaDate = date_time_format($corpAgreement->hansha_check_date, 'Y年m月d日');
            $completeDate = date_time_format($corpAgreement->acceptation_date, 'Y年m月d日');
        }

        $hansxyaAppName = $this->getUserName($corpAgreement->hansha_check_user_id);
        $completeAppName = $this->getUserName($corpAgreement->acceptation_user_id);

        $result = ['ownInfoStr' => $ownInfoStr,
            'hansxyaDate' => $hansxyaDate, 'completeDate' => $completeDate,
            'hansxyaAppName' => $hansxyaAppName, 'completeAppName' => $completeAppName];

        return array_merge($result, $affiliationInfo);
    }

    /**
     * @param $userId
     * @return string
     */
    private function getUserName($userId)
    {
        $userName = "";
        if (!checkIsNullOrEmptyStr($userId)) {
            $mUser = $this->mUserRepo->getUserByUserId($userId);
            if (!is_null($mUser)) {
                $userName = $mUser->user_name;
            }
        }
        return $userName;
    }

    /**
     * @param $corpId
     * @param $corpAgreementId
     * @return mixed
     */
    private function getCorpAgreement($corpId, $corpAgreementId)
    {
        if ($corpAgreementId == null) {
            return $this->corpAgreementRepository->findByCorpId($corpId);
        } else {
            return $this->corpAgreementRepository->findById($corpAgreementId);
        }
    }

    /**
     * @return string
     */
    private function getOwnInfo()
    {
        $ownInfoStr = "";
        $ownInfoStr .= "愛知県名古屋市中区丸の内3-23-20KHF 桜通ビルディング2F \r\n";
        $ownInfoStr .= "シェアリングテクノロジー株式会社 \r\n";
        $ownInfoStr .= "代表取締役　引字 圭祐 ";
        return $ownInfoStr;
    }

    /**
     * @param $corpId
     * @return array
     */
    private function getAffiliationInfo($corpId)
    {
        $mCorps = $this->mCorpRepo->getFirstById($corpId);
        $address1 = $this->mAddress1Repo->findByAddressCd($mCorps->address1);

        $affiliationInfoStr = [
            "address" => $address1->address1 . $mCorps->address2 . $mCorps->address3 . $mCorps->address4,
            "officialCorpName" => $mCorps->official_corp_name,
            "responsibility" => $mCorps->responsibility
        ];
        return $affiliationInfoStr;
    }

    /**
     * @return bool
     */
    public function checkUserNotAffiliation()
    {
        $result = true;
        if (strtolower(Auth::user()->auth) != 'affiliation') {
            $result = false;
        }
        return $result;
    }

    /**
     * @param $result
     * @return string
     * @throws \Mpdf\MpdfException
     * @throws \Throwable
     */
    private function outputPDF($result)
    {
        // setting config for mpdf
        $config = [
            'mode' => '+aCJK',
            'format' => 'A4',
            'default_font_size' => 8,
            'default_font' => 'sjis',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 10,
            'orientation' => 'P',
        ];
        $pdf = new Mpdf($config);
        $pdf->mirrorMargins = 0;

        $corpId = $result['corpId'];
        $pageNumber = '{PAGENO}';
        $address = $result['address'];
        $officialCorpName = $result['officialCorpName'];
        $responsibility = $result['responsibility'];
        $hanszxyaDate = $result['hansxyaDate'];
        $completeDate = $result['completeDate'];
        $hanszxyaAppName = $result['hansxyaAppName'];
        $completeName = $result['completeAppName'];
        $arrayProvision = $result['arrayProvision'];
        $corpCategoryList = $result['corpCategoryList'];

        $report1 = view('affiliation.pdf.agreement_report_1')
            ->with(
                compact(
                    "corpId",
                    "pageNumber",
                    "address",
                    "officialCorpName",
                    "responsibility",
                    "hanszxyaDate",
                    "completeDate",
                    "hanszxyaAppName",
                    "completeName",
                    "arrayProvision"
                )
            )->render();
        $category = "A";
        $report2 = view('affiliation.pdf.agreement_report_2')
            ->with(
                compact(
                    "corpId",
                    "pageNumber",
                    "category",
                    "corpCategoryList"
                )
            )->render();
        $category = "B";
        $report3 = view('affiliation.pdf.agreement_report_2')
            ->with(
                compact(
                    "corpId",
                    "pageNumber",
                    "category",
                    "corpCategoryList"
                )
            )->render();

        $pdf->WriteHTML($report1);
        $pdf->AddPage();
        $pdf->WriteHTML($report2);
        $pdf->AddPage();
        $pdf->WriteHTML($report3);

        return $pdf->Output("download.pdf", "I");
    }

    /**
     * @param $corpAgreement
     * @return array
     */
    private function getAgreementList($corpAgreement)
    {
        $agreementContent = $corpAgreement->original_agreement;
        if (!checkIsNullOrEmptyStr($corpAgreement->customize_agreement)) {
            $agreementContent = $corpAgreement->customize_agreement;
        }
        $agreementContent = str_replace("\r", "\n", str_replace("\r\n", "\n", $agreementContent));
        $agreementArray = explode("\n", $agreementContent);
        return $agreementArray;
    }
}
