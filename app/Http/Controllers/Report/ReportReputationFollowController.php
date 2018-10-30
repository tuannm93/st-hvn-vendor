<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportReputationFollowService;
use Illuminate\Http\Request;

class ReportReputationFollowController extends Controller
{
    /**
     * @var ReportReputationFollowService
     */
    protected $reputationFollowService;

    /**
     * ReportReputationFollowController constructor.
     *
     * @param ReportReputationFollowService $reportReputationFollowService
     */
    public function __construct(
        ReportReputationFollowService $reportReputationFollowService
    ) {
        parent::__construct();
        $this->reputationFollowService = $reportReputationFollowService;
    }

    /**
     * prepare data to render view reputation_follow
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reportReputationFollow()
    {
        $dataForView = $this->reputationFollowService->prepareListDataForView();
        return \view('report.reputation_follow', ['data' => $dataForView]);
    }

    /**
     * show data when next or previous button click
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reportReputationFollowPaging(Request $request)
    {
        $page = $request->get('page');
        $dataForView = $this->reputationFollowService->getDataForPagination($page);
        return \view('report.reputation_follow.reputation_follow_table', ['data' => $dataForView]);
    }

    /**
     * download CSV
     *
     * @return mixed
     */
    public function downloadReputationFollow()
    {
        return $this->reputationFollowService->getDataForDownloadCsv();
    }

    /**
     * Update date_time by corp_id in reputation_check
     *
     * @param  Request $request
     * @return array
     */
    public function updateReputationFollow(Request $request)
    {
        $bAllowUpdate = false;
        $role = \Auth::user()->auth;
        if (in_array($role, ['system', 'admin', 'accounting_admin'])) {
            $bAllowUpdate = true;
        }
        if ($bAllowUpdate) {
            $listIdUpdate = $request->get('listId');
            if (!empty($listIdUpdate)) {
                $result = $this->reputationFollowService->updateReputationFollowTime($listIdUpdate);
                if ($result) {
                    \Session::flash(
                        __('report_reputation_follow.KEY_REPORT_REPUTATION_FOLLOW_MESSAGE_UPDATE'),
                        [__('report_reputation_follow.message_update_success'), false]
                    );
                } else {
                    \Session::flash(
                        __('report_reputation_follow.KEY_REPORT_REPUTATION_FOLLOW_MESSAGE_UPDATE'),
                        [__('report_reputation_follow.message_update_fail'), true]
                    );
                }
            } else {
                \Session::flash(
                    __('report_reputation_follow.KEY_REPORT_REPUTATION_FOLLOW_MESSAGE_UPDATE'),
                    [__('report_reputation_follow.message_no_data_update'), true]
                );
            }
        } else {
            \Session::flash(
                __('report_reputation_follow.KEY_REPORT_REPUTATION_FOLLOW_MESSAGE_UPDATE'),
                [__('report_reputation_follow.message_no_permission'), true]
            );
        }
        return ['code' => 'SUCCESS'];
    }
}
