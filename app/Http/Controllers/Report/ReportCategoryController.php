<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Report\ReportCorpCategoryGroupApplicationService;
use Illuminate\Support\Facades\Auth;
use App\Models\CorpCategoryGroupApplication;
use App\Repositories\Eloquent\MItemRepository;
use App\Services\ReportService;
use App\Repositories\MGenresRepositoryInterface;
use App\Services\MItemService;
use App\Repositories\CommissionInfoRepositoryInterface;
use App\Services\ExportService;
use Exception;

class ReportCategoryController extends Controller
{
    /**
     * @var ReportCorpCategoryGroupApplicationService
     */
    protected $reportCorpCateGroupAppService;
    /**
     * @var ReportService
     */
    protected $service;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $genreRepo;
    /**
     * @var MItemService
     */
    protected $mItemService;
    /**
     * @var CommissionInfoRepositoryInterface
     */
    protected $commissionRepository;
    /**
     * @var ExportService
     */
    protected $exportService;

    /**
     * ReportCategoryController constructor.
     * @param ReportCorpCategoryGroupApplicationService $reportCorpCateGroupAppService
     * @param ReportService $service
     * @param MGenresRepositoryInterface $mGenresRepository
     * @param MItemService $mItemService
     * @param CommissionInfoRepositoryInterface $commissionRepository
     * @param ExportService $exportService
     */
    public function __construct(
        ReportCorpCategoryGroupApplicationService $reportCorpCateGroupAppService,
        ReportService $service,
        MGenresRepositoryInterface $mGenresRepository,
        MItemService $mItemService,
        CommissionInfoRepositoryInterface $commissionRepository,
        ExportService $exportService
    ) {
        parent::__construct();
        $this->reportCorpCateGroupAppService = $reportCorpCateGroupAppService;
        $this->service = $service;
        $this->genreRepo = $mGenresRepository;
        $this->mItemService = $mItemService;
        $this->commissionRepository = $commissionRepository;
        $this->exportService = $exportService;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCorpCategoryGroupApplicationAnswer(Request $request)
    {
        try {
            $params['corp_id'] = $request->get('corp_id_hid');
            $params['corp_name'] = $request->get('corp_name_hid');
            $params['group_id'] = $request->get('group_id_hid');
            $params['application_date_from'] = $request->get('application_date_from_hid');
            $params['application_date_to'] = $request->get('application_date_to_hid');
            $dataList = $this->reportCorpCateGroupAppService->getDataExportCsvCorpCateGroupApp($params);
            $fileName = trans('report_corp_cate_group_app_answer.name_csv') . '_' . Auth::user()->user_id;
            $fieldList = CorpCategoryGroupApplication::csvFormat();
            $exportService = new ExportService();
            return $exportService->exportCsv($fileName, $fieldList, $dataList);
        } catch (Exception $e) {
            logger(__METHOD__ . '- Error - ' . $e->getMessage());
        }
    }

    /**
     * Show data report corp category group application answer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function corpCategoryGroupApplicationAnswer()
    {
        return view('report.corp_category_group_application_answer.corp_category_group_application_answer');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function searchCorpCategoryGroupApplicationAnswer(Request $request)
    {
        $params['corp_id'] = $request->get('corp_id');
        $params['corp_name'] = $request->get('corp_name');
        $params['group_id'] = $request->get('group_id');
        $params['application_date_from'] = $request->get('application_date_from');
        $params['application_date_to'] = $request->get('application_date_to');

        $propriety = getDropList(MItemRepository::APPLICATION) + [0 => MItemRepository::PARTIAL_APPROVAL_OR_REJECTION];

        $results = $this->reportCorpCateGroupAppService->searchCorpCategoryGroupApplication($params);

        return view('report.corp_category_group_application_answer.show_report', [
            'results' => $results,
            'propriety' => $propriety,
        ]);
    }

    /**
     * Show data report corp category group application answer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function corpCategoryGroupApplicationAdmin()
    {
        $results = $this->reportCorpCateGroupAppService->corpCategoryGroupApplicationAdmin();

        return view('report.corp_category_group_application_admin', ['results' => $results]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function unsentList(Request $request)
    {
        $requestData = $request->all();
        $responseData = $this->service->getUnSentList($requestData);
        return view('report.unsent_list', $responseData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function salesSupport(Request $request)
    {
        $params = $request->all();
        $sortParams = [];
        $flg = true;
        $results = [];

        if (!key_exists('last_step_status', $params)) {
            $params['last_step_status'] = [3, 6, 7];
            $flg = false;
        }

        if (isset($params['data'])) {
            $sortParams['sort'] = isset($params['data']['sort']) ? $params['data']['sort'] : '';
            $sortParams['direction'] = isset($params['data']['direction']) ? $params['data']['direction'] : '';
        }

        $mGenreList = $this->genreRepo->getList(true, true);
        $supportKindLabel = config('report.support_kind_label');

        $categories = [
            __('report_sales_support.tel_correspon'),
            __('report_sales_support.visit_correspon'),
            __('report_sales_support.order_correspon'),
            __('report_sales_support.tel_reason'),
            __('report_sales_support.visit_reason'),
            __('report_sales_support.order_reason'),
        ];

        $items = $this->mItemService->getMultiList($categories);

        try {
            $results = $this->commissionRepository->getSalesSupport($params, $sortParams);
        } catch (Exception $e) {
            abort('404');
        }

        return view('report.sales_support', [
            'm_genre_list' => $mGenreList,
            'support_kind_label' => $supportKindLabel,
            'items' => $items,
            'results' => $results,
            'flg' => $flg,
            'support_kind' => isset($params['support_kind']) ? $params['support_kind'] : ''
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSalesSupport(Request $request)
    {
        $exclusionStatus = $request->get('exclusion_status');
        $userId = Auth::user()->user_id;
        foreach ($exclusionStatus as $id => $status) {
            if ($status > 0) {
                $saveData = [
                    'id' => $id,
                    're_commission_exclusion_status' => $status,
                ];
                $saveData['re_commission_exclusion_user_id'] = $userId;
                $saveData['re_commission_exclusion_datetime'] = date('Y-m-d H:i:s');
                $this->commissionRepository->save($saveData);
            }
        }
        $request->session()->flash('success', __('report_sales_support.message_successfully'));

        return redirect()->route('report.sales.support');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function addition(Request $request)
    {
        $requestData = $request->all();
        $responseData = $this->service->getAdditionList($requestData);
        return view('report.addition', $responseData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function additionUpdate(Request $request)
    {
        $requestData = $request->all();
        $updateResult = $this->service->updateAdditionInfo($requestData);
        $request->session()->flash('alert-' . $updateResult['type'], $updateResult['message']);
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function additionExportCSV(Request $request)
    {
        $dataExport = $this->service->getDataCSV()->toArray();
        if (count($dataExport) > 0) {
            $fieldList = $this->service->getFieldListExportCSV();
            $fileName = trans('report_addition.csv_file_name') . '_' . Auth::user()->user_id;
            return $this->exportService->exportCsv($fileName, $fieldList, $dataExport);
        } else {
            $request->session()->flash('alert-warning', trans('report_addition.export_no_data'));
            return redirect()->back();
        }
    }
}
