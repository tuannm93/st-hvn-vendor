<?php

namespace App\Http\Controllers\Commission;

use App\Http\Controllers\Controller;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Services\CommissionPrintService;

class CommissionPrintController extends Controller
{
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionInfoRepo;

    /**
     * @var CommissionPrintService
     */
    protected $commissionPrintService;

    /**
     * CommissionPrintController constructor.
     *
     * @param CommissionInfoRepositoryInterface $commissionInfoRepository
     * @param CommissionPrintService $commissionPrintService
     */
    public function __construct(
        CommissionInfoRepositoryInterface $commissionInfoRepository,
        CommissionPrintService $commissionPrintService
    ) {
        parent::__construct();
        $this->commissionInfoRepo = $commissionInfoRepository;
        $this->commissionPrintService = $commissionPrintService;
    }

    /**
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param $demandId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($demandId)
    {
        $results = $this->commissionInfoRepo->getCommInfoWithCorpByDemandId(
            $demandId,
            [
                "id",
                "corp_id",
            ],
            ["id", "official_corp_name"]
        );

        return view("commission_print.detail", ["results" => $results]);
    }

    /**
     * @param $commissionId
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function exportWord($commissionId)
    {
        $makeFile = "";
        $fileName = $this->commissionPrintService->exportWord($commissionId, $makeFile);
        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment; filename=" . $fileName);
        readfile($makeFile);
        exit;
    }
}
