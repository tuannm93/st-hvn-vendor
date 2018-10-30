<?php

namespace App\Http\Controllers\NoticeInfo;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNoticeInfoRequest;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\NoticeInfoRepositoryInterface;
use App\Repositories\MCorpsNoticeInfoRepositoryInterface;
use App\Repositories\NoticeInfoTargetRepositoryInterface;
use App\Services\ExportService;
use Auth;
use App\Models\NoticeInfo;
use DB;
use Illuminate\Http\Request;
use App\Services\NoticeService;
use App\Services\UserService;
use App\Repositories\Eloquent\MItemRepository;

class NoticeInfoController extends Controller
{
    /**
     * @var MCorpRepositoryInterface
     */
    protected $mCorpRepository;
    /**
     * @var NoticeInfoRepositoryInterface
     */
    protected $noticeInfoRepository;
    /**
     * @var MCorpsNoticeInfoRepositoryInterface
     */
    protected $mCorpsNoticeInfoRepository;
    /**
     * @var NoticeInfoTargetRepositoryInterface
     */
    protected $noticeInfoTargetRepository;
    /**
     * @var mixed
     */
    protected $noticeInfoService;
    /**
     * @var NoticeService
     */
    protected $services;
    /**
     * @var ExportService
     */
    protected $exportService;

    /**
     * NoticeInfoController constructor.
     * @param MCorpRepositoryInterface $mCorpRepository
     * @param NoticeInfoRepositoryInterface $noticeInfoRepository
     * @param MCorpsNoticeInfoRepositoryInterface $mCorpsNoticeInfoRepository
     * @param NoticeInfoTargetRepositoryInterface $noticeInfoTargetRepository
     * @param NoticeService $services
     * @param ExportService $exportService
     */
    public function __construct(
        MCorpRepositoryInterface $mCorpRepository,
        NoticeInfoRepositoryInterface $noticeInfoRepository,
        MCorpsNoticeInfoRepositoryInterface $mCorpsNoticeInfoRepository,
        NoticeInfoTargetRepositoryInterface $noticeInfoTargetRepository,
        NoticeService $services,
        ExportService $exportService
    ) {
        parent::__construct();
        $this->mCorpRepository = $mCorpRepository;
        $this->noticeInfoRepository = $noticeInfoRepository;
        $this->mCorpsNoticeInfoRepository = $mCorpsNoticeInfoRepository;
        $this->noticeInfoTargetRepository = $noticeInfoTargetRepository;
        $this->services = $services;
        $this->exportService = $exportService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function near()
    {
        $user = Auth::user();
        $corpId = $user->affiliation_id;
        $settings = $this->services->getFirstNotCorrespondItem();
        $results = $this->services->getDataNearNotice($corpId, $settings);
        return view('notice_info.near', compact('results', 'settings'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateArea(Request $request)
    {
        $user = Auth::user();
        $corpId = $user->affiliation_id;
        $data = $request->all();
        if (empty($data['id'])) {
            return redirect()->route('notice_info.near')->with(['message' => trans('notice_info.not_area_empty'), 'class' => 'error']);
        } else {
            if ($this->services->saveTargetAreas($corpId, $data)) {
                return redirect()->route('notice_info.near')->with(['message' => trans('notice_info.near_updated'), 'class' => 'success']);
            } else {
                return redirect()->route('notice_info.near')->with(['message' => trans('notice_info.not_registed_genre'), 'class' => 'error']);
            }
        }
    }

    /**
     * show page notice index
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $request->all();
        return $this->renderViewGetListNoticeInfo($data, 'notice_info.index');
    }

    /**
     * ajax get list notice info
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxGetListNoticeInfo(Request $request)
    {
        $data = $request->all();
        return $this->renderViewGetListNoticeInfo($data, 'notice_info.components.list_notice_infos');
    }

    /**
     * render view get list notice info
     *
     * @param  array  $data
     * @param  string $fileName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderViewGetListNoticeInfo($data, $fileName)
    {
        $user = Auth::user();
        $role = $user['auth'];
        $dropListItem = getDropList(MItemRepository::CORPORATE_BROKERAGE_FORM);
        $isRoleAffiliation = $this->services->isRole($role, ['affiliation']);
        $detailSort = $this->services->formatDataNoticeInfoSort($data);
        $results = $this->services->getListNoticeInfos($isRoleAffiliation, $detailSort);
        $linkDisplay = $this->services->checkDisplayTrader($isRoleAffiliation);
        $arrayListItemSort = $this->services->getArrayListItemSortFollow($isRoleAffiliation);
        return view($fileName, compact('results', 'linkDisplay', 'dropListItem', 'isRoleAffiliation', 'arrayListItemSort', 'detailSort'));
    }

    /**
     * @param null $noticeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function detail($noticeId = null)
    {
        if (empty($noticeId)) {
            return redirect()->route('notice_info.near');
        }

        $mCorp = $this->mCorpRepository->getFirstById(Auth::user()->affiliation_id);
        if (UserService::checkRole('affiliation')) {
            $noticeInfo = $this->noticeInfoRepository->getNoticeInfoByAffiliation($noticeId, $mCorp);
        } else {
            $noticeInfo = $this->noticeInfoRepository->getNoticeInfoByOtherRoles($noticeId);
        }

        if (empty($noticeInfo)) {
            return redirect()->route('notice_info.near');
        }

        $this->mCorpsNoticeInfoRepository->markReadNotice(Auth::user(), $noticeId, $mCorp);

        return view(
            'notice_info.detail',
            compact('noticeInfo')
        );
    }

    /**
     * @param $noticeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function answer($noticeId)
    {
        if (Auth::user()->isPoster()) {
            return redirect()->route('notice_info.detail', ['noticeId' => $noticeId])
                ->with('error_message', __('notice_info.error_not_affilication'));
        }

        if (!Auth::user()->isReader()) {
            abort(404);
        }
        $mCorp = $this->mCorpRepository->getFirstById(Auth::user()->affiliation_id);
        $noticeInfo = $this->mCorpsNoticeInfoRepository->markReadNotice(Auth::user(), $noticeId, $mCorp);

        if (!is_null($noticeInfo->answer_value)) {
            return redirect()->route('notice_info.detail', ['noticeId' => $noticeId])
                ->with('error_message', __('notice_info.error_notice_was_answered'));
        }

        $this->mCorpsNoticeInfoRepository->answerNotice($noticeInfo, Auth::user(), request()->input("answer"));

        return redirect()->route('notice_info.detail', ['noticeId' => $noticeId])
            ->with('success_message', __('notice_info.success_answer'));
    }

    /**
     * @param null $noticeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($noticeId = null)
    {
        $listItemNotice = getDropList(MItemRepository::CORPORATE_BROKERAGE_FORM);
        if (empty($noticeId)) {
            return view(
                'notice_info.new',
                [
                'noticeInfo' => new NoticeInfo(),
                'listItemNotice' => $listItemNotice,
                ]
            );
        }
        abort_if(!Auth::user()->isPoster(), 404);
        $noticeInfo = $this->noticeInfoRepository->findActiveById($noticeId);
        if (empty($noticeInfo)) {
            return redirect()->route('notice_info.index');
        }

        $listCorps = $this->noticeInfoTargetRepository->findCorpListByNoticeInfoId($noticeId);
        $listAnswers = $this->mCorpsNoticeInfoRepository->findAnswerListByNoticeInfoId($noticeId);
        $isReadOnly = $listAnswers->count() ? true : false;

        return view(
            'notice_info.edit',
            compact(
                'noticeInfo',
                'listCorps',
                'listAnswers',
                'isReadOnly',
                'listItemNotice'
            )
        );
    }

    /**
     * @param $noticeId
     * @return mixed
     */
    public function downloadAnswerCSV($noticeId)
    {
        abort_if(!Auth::user()->isPoster(), 404);
        $listAnswersCsv = $this->mCorpsNoticeInfoRepository->getListAnswerCSV($noticeId);
        $csvFormats = config('datacustom.csvFormats');
        $fileName = 'notice_infos' . '_' . $noticeId . '_' . Auth::user()->user_id;
        return $this->exportService->exportCsv($fileName, $csvFormats['list_answer'], $listAnswersCsv);
    }

    /**
     * @param CreateNoticeInfoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateNoticeInfoRequest $request)
    {
        try {
            DB::beginTransaction();
            $noticeInfo = $this->noticeInfoRepository->saveNoticeInfo([
                'info_title'           => $request->input('info_title'),
                'info_contents'        => $request->input('info_contents'),
                'choices'              => ($request->input('request-answer')) ? $request->input('choices') : null,
                'created'              => date('Y-m-d H:i:s'),
                'created_user_id'      => Auth::user()->user_id,
                'modified'             => date('Y-m-d H:i:s'),
                'modified_user_id'     => Auth::user()->user_id,
                'is_target_selected'   => $request->input('target') == 2,
                'corp_commission_type' => ($request->input('target') == 2) ? null : $request->input('corp_commission_type'),
                ]);

            if ($request->input('target') == 2) {
                $this->noticeInfoTargetRepository->updateCorpListOfNoticeInfo(
                    Auth::user(),
                    $noticeInfo->id,
                    $request->input('target_corp_ids')
                );
            }

            DB::commit();
            return redirect()->route('notice_info.edit', ['noticeId' => $noticeInfo->id])
                ->with('success_message', __('notice_info_update.complete_register'));
        } catch (\Exception $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * @param CreateNoticeInfoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateNoticeInfoRequest $request)
    {
        try {
            if ($this->mCorpsNoticeInfoRepository->isNoticeAnswered(request('notice_id'))) {
                return redirect()->route('notice_info.edit', ['noticeId' => request('notice_id')])
                    ->with('error_message', __('notice_info_update.was_answered'));
            }
            DB::beginTransaction();
            $effecRows = $this->noticeInfoRepository->saveNoticeInfo(
                [
                'info_title'           => $request->input('info_title'),
                'info_contents'        => $request->input('info_contents'),
                'choices'              => ($request->input('request-answer')) ? $request->input('choices') : null,
                'modified_user_id'     => Auth::user()->user_id,
                'modified'             => date('Y-m-d H:i:s'),
                'is_target_selected'   => $request->input('target') == 2,
                'corp_commission_type' => ($request->input('target') == 2) ? null : $request->input('corp_commission_type'),
                'del_flg'              => $request->input('del_flg'),
                ],
                $request->input('notice_id')
            );

            if ($effecRows > 0) {
                if ($request->input('target') == 2) {
                    $this->noticeInfoTargetRepository->updateCorpListOfNoticeInfo(
                        Auth::user(),
                        request('notice_id'),
                        $request->input('target_corp_ids')
                    );
                } else {
                    $this->noticeInfoTargetRepository->removeCorpListOfNoticeInfo($request->input('notice_id'));
                }
            } else {
                DB::rollback();
                abort(500);
            }
            DB::commit();
            if ($request->input('del_flg')) {
                return redirect()->route('notice_info.index')->with('success_message', __('notice_info_update.complete_update'));
            }
            return redirect()->route('notice_info.edit', ['noticeId' => request('notice_id')])
                ->with('success_message', __('notice_info_update.complete_update'));
        } catch (\Exception $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * @param $noticeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeNotice($noticeId)
    {
        $this->noticeInfoRepository->saveNoticeInfo(['del_flg' => 1], $noticeId);
        return response()->json();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListAffiliation()
    {
        try {
            return response()->json([
                'data'  => $this->mCorpRepository->getAdvanceSearchByIdOrName(
                    request('search_key'),
                    request('search_value'),
                    request('exlude_corp_ids')
                ),
                'count' => $this->mCorpRepository->getCountAdvanceSearchByIdOrName(
                    request('search_key'),
                    request('search_value'),
                    request('exlude_corp_ids')
                )
                ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                'message'   => $e->getMessage()
                ],
                500
            );
        }
    }
}
