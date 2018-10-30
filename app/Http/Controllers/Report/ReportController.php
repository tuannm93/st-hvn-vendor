<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\AntisocialCheck;
use App\Models\CommissionInfo;
use App\Models\MCorpCategoriesTemp;
use App\Repositories\AntisocialCheckRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpCategoriesTempRepositoryInterface;
use App\Services\ExportService;
use App\Services\ReportJbrCommissionService;
use App\Services\ReportService;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ReportController extends Controller
{
    const CSVOUT = 'CSV出力';
    /**
     * @var AntisocialCheckRepositoryInterface
     */
    public $antisocialcheckRepository;
    /**
     * @var MCorpCategoriesTempRepositoryInterface
     */
    protected $mCorpCategoriesTempRepo;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;
    /**
     * @var ReportJbrCommissionService
     */
    protected $reportCommissionService;
    /**
     * @var ReportService
     */
    protected $service;
    /**
     * @var ExportService
     */
    protected $exportService;

    /**
     * ReportController constructor.
     *
     * @param AntisocialCheckRepositoryInterface $antisocialcheckRepository
     * @param ReportJbrCommissionService $reportCommissionService
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepo
     * @param ReportService $service
     * @param ExportService $exportService
     */
    public function __construct(
        AntisocialCheckRepositoryInterface $antisocialcheckRepository,
        ReportJbrCommissionService $reportCommissionService,
        DemandInfoRepositoryInterface $demandInfoRepository,
        MCorpCategoriesTempRepositoryInterface $mCorpCategoriesTempRepo,
        ReportService $service,
        ExportService $exportService
    ) {
        parent::__construct();
        $this->antisocialcheckRepository = $antisocialcheckRepository;
        $this->reportCommissionService = $reportCommissionService;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->mCorpCategoriesTempRepo = $mCorpCategoriesTempRepo;
        $this->service = $service;
        $this->exportService = $exportService;
    }

    /**
     * @return view
     */
    public function index()
    {
        return view('report.index');
    }

    /**
     * get data antisocial follow to view
     *
     * @param  Request $request
     * @return view
     * @throws \Throwable
     */
    public function antisocialFollow(Request $request)
    {
        $results = $this->antisocialcheckRepository->getAntisocialList();
        $isUpdateAuthority = $this->antisocialcheckRepository->isUpdateAuthority(Auth::user()->auth);
        if ($request->ajax()) {
            return view(
                'report.components.antisocial_follow_table',
                [
                        "results" => $results,
                        "isUpdateAuthority" => $isUpdateAuthority,
                    ]
            );
        }

        return view(
            'report.antisocial_follow',
            [
                "results" => $results,
                "isUpdateAuthority" => $isUpdateAuthority,
            ]
        );
    }

    /**
     * post data antisocial follow
     *
     * @param  Request $request
     * @return null
     */
    public function antisocialFollowUpdate(Request $request)
    {
        $dataParam = $request->all();
        if (isset($dataParam['csv_out'])) {
            $fileName = trans('antisocial_follow.antisocial_follow') . '_' . Auth::user()->user_id;
            $fieldList = AntisocialCheck::csvFormat();
            $dataList = $this->antisocialcheckRepository->getDataCsv();
            return $this->exportService->exportCsv($fileName, $fieldList, $dataList);
        }
        if (isset($dataParam['update'])) {
            $result = $this->antisocialcheckRepository->updateDataAntisocialFollow($dataParam, Auth::user()->auth);
            if ($result) {
                $request->session()->flash('Update', trans('aff_corptargetarea.update'));
            } else {
                $request->session()->flash('InputError', trans('bill.output_message_no_data'));
            }
        }

        return back();
    }

    /**
     * get list receipt follow
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function getListReceiptFollow(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $order = $request->get('order');
        $nameColumn = $request->get('nameColumn');

        $results = $this->service->orderList(
            $this->service->getListJbrReceiptFollow($fromDate, $toDate, true),
            $order,
            $nameColumn
        );

        $numberCorp = $this->service->getListJbrReceiptFollow($fromDate, $toDate, false)
            ->groupBy('m_corps.id')->limit(trans('report_jbr.limitRecord'))->get()->count();
        $numberCorp = trans('report_jbr.number_corp') . $numberCorp;

        if ($request->ajax()) {
            return view('report.components.component_jbr_receipt', compact('results', 'numberCorp'));
        }
        return view('report.jbr_receipt_follow', compact('results', 'numberCorp'));
    }

    /**
     * get csv list receipt follow
     * @param Request $request
     * @return string
     */
    public function getCsvListReceiptFollow(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        try {
            $fieldList = CommissionInfo::csvFormat();

            $fileName = trans('report_jbr.receipt_follow_csv') . '_' . date('YmdHis', time());

            $listJbr = $this->service->getListJbrReceiptFollow(
                $fromDate,
                $toDate,
                true
            )->limit(trans('report_jbr.limitRecord'))->orderBy(
                'commission_infos.follow_date',
                'asc'
            )->get()->toArray();
            $csvData = $this->reportCommissionService->setCsvData($listJbr, $fieldList);

            return Excel::create(
                $fileName,
                function ($excel) use ($csvData, $fileName) {
                    $excel->sheet(
                        $fileName,
                        function ($sheet) use ($csvData) {
                            $sheet->fromArray($csvData);
                        }
                    );
                }
            )->download('csv');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * get data for report corp agreement category
     * @return view
     */
    public function corpAgreementCategory()
    {
        $dataResult = $this->mCorpCategoriesTempRepo->getCorpAgreementCategory();

        return view(
            'report.corp_agreement_category',
            [
                'dataResult' => $dataResult,
            ]
        );
    }

    /**
     * get data for report corp agreement category by ajax
     * @param $request
     * @return view
     * @throws \Throwable
     */
    public function corpAgreementCategoryAjax(Request $request)
    {
        if ($request->ajax()) {
            $results = $this->mCorpCategoriesTempRepo->getCorpAgreementCategory();
            if (count($results) == 0) {
                return response()->json(view('report.components.error')->render());
            }
            try {
                return response()->json(view('report.components.corp_agreement_category_table', [
                    "dataResult" => $results
                ])->render());
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * create csv corp agreement category
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsvCorpAgreementCategory()
    {
        $dataList = $this->mCorpCategoriesTempRepo->getCsvCorpAgreementCategory();

        $fileName = trans('report_corp_agreement_category.name_csv') . '_' . Auth::user()->user_id;

        $fieldList = MCorpCategoriesTemp::csvFormat();
        $exportService = new ExportService();
        return $exportService->exportCsv($fileName, $fieldList, $dataList);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function auctionFall(Request $request)
    {
        $data['url'] = '/report/auction_fall';
        $data['named'] = $request->input('named');
        $request->session()->forget(self::$sessionKeyForReport);
        $request->session()->put(self::$sessionKeyForReport, $data);

        $dataResult = $this->demandInfoRepository->getDemandForReport(
            $request->input('sort'),
            $request->input('direction')
        );

        return view(
            'report.auction_fall',
            [
                "results" => $dataResult,
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function auctionFallTable(Request $request)
    {
        $dataResult = $this->demandInfoRepository->getDemandForReport(
            $request->input('sort'),
            $request->input('direction')
        );

        return view(
            'report.auction_fall_table',
            [
                "results" => $dataResult,
            ]
        );
    }

    /**
     * get jbr commission
     * @auth Dung.PhamVan@nashtechglobal.com
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJbrCommission(Request $request)
    {
        $orderRequest = $request->all();
        $sortOptions = config('report.commissionSortOption');
        $sortDefault = [
            ['name' => 'corp_name', 'sort' => 'asc'],
            ['name' => 'contact_desired_time', 'sort' => 'asc'],
            ['name' => 'commission_rank', 'sort' => 'asc'],
            ['name' => '', 'sort' => ''],
        ];

        $arrayCombine = [];
        if (isset($orderRequest['order_by']) && !is_array($orderRequest['order_by']) && !is_array($orderRequest['sort_by'])) {
            $arrayCombine[$orderRequest['order_by']] = $orderRequest['sort_by'];
        } else {
            $orderBy = $orderRequest['order_by'] ?? [];
            $sortBy = $orderRequest['sort_by'] ?? [];
            foreach ($orderBy as $key => $order) {
                $arrayCombine[$order] = array_key_exists($key, $sortBy) ? $sortBy[$key] : '';
            }
        }
        $reportData = $this->demandInfoRepository->getJbrCommissionReport($arrayCombine)->appends($orderRequest);
        $totalRecord = $this->demandInfoRepository->totalRecordCommissionReport($arrayCombine);

        return view('report.jbr_commission', compact('sortOptions', 'sortDefault', 'reportData', 'totalRecord'));
    }

    /**
     * show page jbr ongoing
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jbrOngoing(Request $request)
    {
        $data = $request->all();
        $detailSort = ReportService::formatDetailSortJbrOngoing($data);
        $results = $this->demandInfoRepository->getJbrOngoing($detailSort);
        if ($request->ajax()) {
            return view('report.components.ongoing', compact('results'));
        }

        return view('report.jbr_ongoing', compact('results'));
    }
}
