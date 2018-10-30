<?php

namespace App\Http\Middleware;

use App\Http\Controllers\NoticeInfo\NoticeInfoController;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\NoticeInfoRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AlertNoticeInfo
{
    /**
     * @var NoticeInfoRepositoryInterface
     */
    private $noticeInfoRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * AlertNoticeInfo constructor.
     *
     * @param NoticeInfoRepositoryInterface $noticeInfoRepository
     * @param MCorpRepositoryInterface      $mCorpRepository
     */
    public function __construct(
        NoticeInfoRepositoryInterface $noticeInfoRepository,
        MCorpRepositoryInterface $mCorpRepository
    ) {
        $this->noticeInfoRepository = $noticeInfoRepository;
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (Auth::user()->auth != 'affiliation' || $request->is(['agreement-system/*'])) {
            return $next($request);
        }
        $affiliationId = Auth::user()->affiliation_id;
        $mCorp = $this->mCorpRepository->find($affiliationId, ['id', 'corp_commission_type', 'created']);

        if (!Session::has('NUMBER_NOTICEINFO_UNREAD')) {
            $numUnreadForOneTime = $this->noticeInfoRepository->countUnreadByCorpIdAndCreatedDate($affiliationId, $mCorp->corp_commission_type, $mCorp->created);
            if ($numUnreadForOneTime) {
                Session::put('NUMBER_NOTICEINFO_UNREAD', $numUnreadForOneTime);
                view()->share('firstViewNumberNoticeinfoUnread', true);
            }
        } else {
            view()->share('firstViewNumberNoticeinfoUnread', false);
        }


        $numberUnreadNoticeInfo = $this->noticeInfoRepository->countUnreadNoticeInfoByCorpId($affiliationId, $mCorp->corp_commission_type);
        view()->share('numberUnreadNoticeInfo', $numberUnreadNoticeInfo);
        if (!$request->route()->getController() instanceof NoticeInfoController) {
            $unAnswerCount = $this->noticeInfoRepository->countUnansweredByCorpId($affiliationId, $mCorp->created, $mCorp->corp_commission_type);
        } else {
            $unAnswerCount = 0;
        }
        view()->share('unAnswerCount', $unAnswerCount);

        $isForceGoToNoticePage = false;
        $noticeInfoStatuses = $this->noticeInfoRepository->getNoticeInfoStatusByCorpId($affiliationId);

        if (!empty($noticeInfoStatuses)) {
            foreach ($noticeInfoStatuses as $noticeInfoStatus) {
                if (($noticeInfoStatus->status == "2" || $noticeInfoStatus->status == 3) &&
                    in_array($noticeInfoStatus->id, config('rits.notice_info_important_ids')) &&
                    !$request->route()->getController() instanceof NoticeInfoController
                ) {
                    $isForceGoToNoticePage = true;
                    view()->share('firstViewNumberNoticeinfoUnread', true);
                }
            }
        }
        view()->share('isForceGoToNoticePage', $isForceGoToNoticePage);

        return $next($request);
    }
}
