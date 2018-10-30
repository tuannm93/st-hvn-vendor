<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportDevelopmentSearchRequest;
use App\Models\Approval;
use App\Repositories\ApprovalRepositoryInterface;
use App\Repositories\Eloquent\MItemRepository;
use App\Repositories\MGenresRepositoryInterface;
use App\Services\ExportService;
use App\Services\Report\ReportCorpCommissionService;
use App\Services\Report\ReportDevSearchService;
use App\Services\Report\ReportRealTimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReportDevelopmentController extends Controller
{
    /**
     * @var ReportCorpCommissionService
     */
    protected $reportCorpCommissionService;
    /**
     * @var ApprovalRepositoryInterface
     */
    protected $approvalRepo;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $genreRepo;
    /**
     * @var ReportDevSearchService
     */
    protected $reportDevSearchService;
    /**
     * @var ExportService
     */
    protected $exportService;
    /**
     * @var ReportRealTimeService
     */
    protected $reportRealTimeService;

    /**
     * ReportDevelopmentController constructor.
     * @param ReportCorpCommissionService $reportCorpCommissionService
     * @param ApprovalRepositoryInterface $approvalRepository
     * @param MGenresRepositoryInterface $mGenresRepository
     * @param ReportDevSearchService $devSearchService
     * @param ReportRealTimeService $reportRealTimeService
     * @param ExportService $exportService
     */
    public function __construct(
        ReportCorpCommissionService $reportCorpCommissionService,
        ApprovalRepositoryInterface $approvalRepository,
        MGenresRepositoryInterface $mGenresRepository,
        ReportDevSearchService $devSearchService,
        ReportRealTimeService $reportRealTimeService,
        ExportService $exportService
    ) {
        parent::__construct();
        $this->reportCorpCommissionService = $reportCorpCommissionService;
        $this->approvalRepo = $approvalRepository;
        $this->genreRepo = $mGenresRepository;
        $this->reportDevSearchService = $devSearchService;
        $this->reportRealTimeService = $reportRealTimeService;
        $this->exportService = $exportService;
    }

    /**
     * Corp Commission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexCorpCommission()
    {
        $isSession = session()->has(self::$sessionReportCorpCommisison);
        if ($isSession) {
            $session = session()->get(self::$sessionReportCorpCommisison)[0];
        } else {
            $session = [];
        }

        $sortOptions = config('report.sortOptions');

        $strGetParam = [
            'demand_follow_date' => '',
            'detect_contact_desired_time' => '',
            'commission_rank' => '',
            'site_id' => '',
            'corp_name' => '',
            'business_day' => '',
            'first_commission' => '',
            'user_name' => '',
            'modified' => '',
            'auction' => '',
            'cross_sell_implement' => '',
        ];

        $filterOptions = config('report.filterOptions');
        $contactRequest = config('report.contactRequest');
        $genreRank = config('report.genreRank');
        $dayOfTheWeek = config('report.dayOfTheWeek');
        $historyUpdate = config('report.historyUpdate');

        $defaultOrder = [
            'order' => [
                1 => 'corp_name',
                2 => 'contact_desired_time',
                3 => 'commission_rank',
            ],
            'direction' => [
                1 => 'asc',
                2 => 'asc',
                3 => 'asc',
            ],
        ];

        return view('report.corp_commission.corp_commission', [
            'sortOptions' => $sortOptions,
            'strGetParam' => $strGetParam,
            'filterOptions' => $filterOptions,
            'contactRequest' => $contactRequest,
            'genreRank' => $genreRank,
            'dayOfTheWeek' => $dayOfTheWeek,
            'historyUpdate' => $historyUpdate,
            'defaultOrder' => json_encode($defaultOrder),
            'session' => $session,
            'results' => null,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function searchCorpCommission(Request $request)
    {
        $buildFilter = [];
        $order = [];
        for ($i = 1; $i < 5; $i++) {
            if ($request->get('order' . $i) && $request->get('direction' . $i)) {
                if ($request->get('order' . $i) === 'address1') {
                    $key = 'demand_infos.address1';
                } elseif ($request->get('order' . $i) === 'item_id') {
                    $key = 'demand_infos.id';
                } elseif ($request->get('order' . $i) === 'modified') {
                    $key = 'commission_infos.modified';
                } else {
                    $key = $request->get('order' . $i);
                }
                $order[$key] = ($request->get('direction' . $i)) ? $request->get('direction' . $i) : 'asc';
            }
        }

        $dataFillter = [
            'filter_demand_follow_date' => 'demand_infos.follow_date',
            'filter_detect_contact_desired_time' => 'demand_infos.contact_desired_time',
            'filter_commission_rank' => 'm_genres.commission_rank',
            'filter_site_name' => 'm_sites.site_name',
            'filter_corp_name' => 'm_corps.corp_name',
            'filter_holiday' => 'demand_infos.holiday',
            'filter_user_name' => 'm_users.user_name',
            'filter_modified' => 'commission_infos.modified',
            'filter_auction' => 'demand_infos.auction',
            'filter_cross_sell_implement' => 'demand_infos.cross_sell_implement',
            'filter_first_commission' => 'commission_infos.first_commission'
        ];

        foreach ($dataFillter as $key => $value) {
            if ($request->get($key)) {
                $buildFilter[$value] = $request->get($key);
            }
        }

        if (session()->has(self::$sessionReportCorpCommisison)) {
            session()->forget(self::$sessionReportCorpCommisison);
        }
        session()->push(self::$sessionReportCorpCommisison, $request->all());

        $data = $this->reportCorpCommissionService->getCorpCommissionPaginationCondition($buildFilter, $order);

        return response()->json(\view('report.corp_commission.show_report', [
            'results' => $data,
        ])->render());
    }

    /**
     * Display page report/application_admin
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function applicationAdmin()
    {
        $results = $this->approvalRepo->getApprovalForReport();
        $ir = getDropList(MItemRepository::IRREGULAR_REASON);

        return view('report.application_admin', [
            "results" => $results,
            "ir" => $ir,
        ]);
    }

    /**
     * Show page report/development
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function development(Request $request)
    {
        $genres = $this->genreRepo->getListSelectBox([['valid_flg', 1]]);
        $prefecture = getDivList('rits.prefecture_div', 'rits_config');
        $genreId = $request->session()->get("genre_id", null);
        $address = $request->session()->get("address", null);

        return view('report.development', [
            "genres" => $genres,
            "prefecture" => $prefecture,
            "genreId" => $genreId,
            "address" => $address,
        ]);
    }

    /**
     * Display table report, get params from session
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDevelopmentSearch(Request $request)
    {
        if (!$request->hasSession() || $request->session()->get("genre_id", null) == null) {
            $request->session()->flash('error', trans('report_development_search.genre_id_required'));

            return redirect()->route("report.development");
        }

        $genreId = $request->session()->get("genre_id");
        $address = $request->session()->get("address");
        $noAttackList = $this->reportDevSearchService->getMCropUnattended($genreId);
        $advanceList = $this->reportDevSearchService->getMCropAdvance($genreId);
        if ($address == null || empty($address)) {
            $genres = $this->genreRepo->getListSelectBox([['valid_flg', 1]]);
            $prefecture = getDivList('rits.prefecture_div', 'rits_config');

            return view('report.development_search', [
                "genres" => $genres,
                "prefecture" => $prefecture,
                "noAttackList" => $noAttackList,
                "advanceList" => $advanceList,
                "genreId" => $genreId,
                "flag" => false,
            ]);
        } else {
            return view('report.development_search', [
                "genreId" => $genreId,
                "address" => $address,
                "advanceList" => $advanceList,
                "noAttackList" => $noAttackList,
                "flag" => true,
            ]);
        }
    }

    /**
     * Display table report, get params from url
     *
     * @param \Illuminate\Http\Request $request
     * @param $status
     * @param $address
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDevelopmentSearchByParams(Request $request, $status, $address)
    {
        $genreId = Session::get("genre_id");
        if (empty($genreId)) {
            $request->session()->flash('error', trans('report_development_search.genre_id_required'));
            return redirect()->route("report.development");
        }
        $noAttackList = [];
        $advanceList = [];
        if ($status == 1) {
            $noAttackList = $this->reportDevSearchService->getMCropUnattended($genreId);
        }
        if ($status == 2) {
            $advanceList = $this->reportDevSearchService->getMCropAdvance($genreId);
        }

        return view('report.development_search', [
            "genreId" => $genreId,
            "address" => $address,
            "advanceList" => $advanceList,
            "noAttackList" => $noAttackList,
            "status" => $status,
            "flag" => true,
        ]);
    }

    /**
     * Get data for datatable
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getDevelopmentSearchData(Request $request)
    {
        $genreId = $request->session()->get("genre_id");
        $address = $request->query("address", null);
        if ($address == null) {
            $address = $request->session()->get("address");
        }
        $status = $request->query("status", null);

        return $this->reportDevSearchService->getListForDataTableByGenreIdAndAddressAndStatus(
            $genreId,
            $address,
            $status
        );
    }

    /**
     * Bind genre_id and address to session
     *
     * @param \App\Http\Requests\ReportDevelopmentSearchRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDevelopmentSearch(ReportDevelopmentSearchRequest $request)
    {
        $request->session()->put("genre_id", $request->input("genre_id"));
        if ($request->has("address")) {
            $request->session()->put("address", $request->input("address"));
        }

        return redirect()->route('report.development.search');
    }

    /**
     * Corp selection
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexCorpSelection(Request $request)
    {
        $page = config('datacustom.report_number_row');
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $pageLink = ($request->get('page')) ? '&page=' . $request->get('page') : '';
        $results = $this->reportCorpCommissionService->getCorpSelectionPaginationCondition($page, $sort, $direction);

        return view('report.corp_selection', ['results' => $results, 'pageLink' => $pageLink]);
    }

    /**
     * applicationAnswer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function applicationAnswer()
    {
        return view('report.application_answer');
    }

    /**
     * Application Answer Ajax
     *
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function applicationAnswerAjax()
    {
        $results = $this->approvalRepo->getApplicationAnswer();
        if (count($results) == 0) {
            return response()->json(view('report.components.error')->render());
        }
        return view('report.components.application_answer_table', [
            "results" => $results,
            'application' => MItemRepository::APPLICATION,
            'irregularReason' => MItemRepository::IRREGULAR_REASON,
        ]);
    }

    /**
     * get app answer csv
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function applicationAnswerCsv()
    {
        $dataList = $this->approvalRepo->getApplicationAnswerCsv();
        $fileName = trans('application_answer.application_answer') . '_' . Auth::user()->user_id;
        $fieldList = Approval::csvFormat();
        return $this->exportService->exportCsv($fileName, $fieldList['default'], $dataList);
    }

    /**
     * realTimeReport
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function realTimeReport()
    {
        $results = $this->reportRealTimeService->getDataRealTime();

        return view('report.real_time_report', ['results' => $results]);
    }

    public function deleteSesssionSearch()
    {
        $isSession = session()->has(self::$sessionReportCorpCommisison);
        if ($isSession) {
            session()->forget(self::$sessionReportCorpCommisison);
        }
    }
}
