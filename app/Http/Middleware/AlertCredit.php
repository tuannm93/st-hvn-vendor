<?php

namespace App\Http\Middleware;

use App\Repositories\AffiliationInfoRepositoryInterface;
use App\Services\CommissionInfoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AlertCredit
{
    /**
     * @var AffiliationInfoRepositoryInterface
     */
    private $affiliationInfoRepository;
    /**
     * @var CommissionInfoService
     */
    private $commissionInfoService;

    /**
     * AlertCredit constructor.
     * @param AffiliationInfoRepositoryInterface $affiliationInfoRepository
     * @param CommissionInfoService $commissionInfoService
     */
    public function __construct(
        AffiliationInfoRepositoryInterface $affiliationInfoRepository,
        CommissionInfoService $commissionInfoService
    ) {
        $this->affiliationInfoRepository = $affiliationInfoRepository;
        $this->commissionInfoService = $commissionInfoService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (Auth::user()->auth != 'affiliation') {
            return $next($request);
        }
        $affiliationId = Auth::user()->affiliation_id;
        $affiliationInfo = $this->affiliationInfoRepository->findAffiliationInfoByCorpId($affiliationId);

        if (!Session::has('CREDIT_ALERT_SHOWED')) {
            Session::put('CREDIT_ALERT_SHOWED', true);
            view()->share('firstViewCreditAlert', true);
        } else {
            view()->share('firstViewCreditAlert', false);
        }

        switch ($affiliationInfo['credit_mail_send_flg']) {
            case 1:
                view()->share('creditWarningData', [
                    'use' => $this->commissionInfoService->checkCreditSumPrice($affiliationId, null, true),
                    'limit' => (int)$affiliationInfo['credit_limit'] + (int)$affiliationInfo['add_month_credit']
                ]);
                break;
            case 2:
                view()->share(
                    'creditDangerData',
                    (int)$affiliationInfo['credit_limit'] + (int)$affiliationInfo['add_month_credit']
                );
                break;
            default:
                view()->share('firstViewCreditAlert', null); // Don't show modal box when  credit_mail_send_flg != 1 & 2
                break;
        }

        return $next($request);
    }
}
