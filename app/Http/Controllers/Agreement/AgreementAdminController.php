<?php

namespace App\Http\Controllers\Agreement;

use App\Models\AffiliationInfo;
use App\Models\CorpAgreement;
use App\Http\Controllers\Controller;
use App\Models\MCorp;
use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Services\AgreementAdminService;
use Illuminate\Http\Request;

class AgreementAdminController extends Controller
{
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    protected $repository;
    /**
     * @var AgreementAdminService
     */
    protected $service;

    /**
     * AgreementAdminController constructor.
     *
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     * @param AgreementAdminService              $agreementAdminService
     */
    public function __construct(
        AffiliationInfoRepositoryInterface $affiliationInfoRepository,
        AgreementAdminService $agreementAdminService
    ) {
        parent::__construct();
        $this->repository = $affiliationInfoRepository;
        $this->service = $agreementAdminService;
    }

    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $corpKindLabel = MCorp::CORP_KIND;
        $agreementStatusItemLabel = $this->service->getAgreementStatusItemLabel();
        $listedKindLabel = AffiliationInfo::LISTED_KIND;
        $hanshaCheckStatusLabel = CorpAgreement::HANSHA_CHECK_STATUS;
        return view(
            'agreement.admin.dashboard',
            compact('corpKindLabel', 'agreementStatusItemLabel', 'listedKindLabel', 'hanshaCheckStatusLabel')
        );
    }

    /**
     * Process data table ajax request.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataProcessing(Request $request)
    {
        try {
            return $this->service->getTableDataWithCondition($request);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            $message['content'] = $exception->getMessage();
            $message['type'] = 'ERROR';
            return $message;
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function exportCsv(Request $request)
    {
        try {
            $this->service->exportFile($request, config("datacustom.file_type.csv"));
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            $message['content'] = $exception->getMessage();
            $message['type'] = 'ERROR';
            return $message;
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function exportExcel(Request $request)
    {
        try {
            $this->service->exportFile($request, config("datacustom.file_type.excel"));
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            $message['content'] = $exception->getMessage();
            $message['type'] = 'ERROR';
            return $message;
        }
    }
}
