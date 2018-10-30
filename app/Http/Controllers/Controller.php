<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var string
     */
    protected static $sessionKeyForDemandSearch = 'datas@DemandSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForCommissionSearch = 'datas@CommissionSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForAffiliationSearch = 'datas@AffiliationSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForReport = 'datas@Report';

    /**
     * @var string
     */
    protected static $sessionKeyForBillMcorpSearch = 'datas@BillMcopSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForSearchAfterEdit = 'datas@BillMcopSearchAfterEdit';

    /**
     * @var string
     */
    protected static $sessionKeyForCheckBillSearch = 'datas@BillMcopCheckBillSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForBillSearch = 'datas@BillSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForAuctionSearch = 'datas@AuctionSearch';

    /**
     * @var string
     */
    protected static $sessionKeyForAuctionSearchForKameiten = 'datas@AuctionSearchForKameiten';

    /**
     * @var string
     */
    protected static $sessionKeyForDemandParameter = 'datas@DemandParameter';

    /**
     * @var string
     */
    protected static $sessionReportCorpCommisison = 'datas@ReportCorpCommisison';

    /**
     * @var integer
     */
    protected $pageNumber = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verify.force.agreement');
        $this->middleware('alert.noticeInfo');
        $this->middleware('alert.credit');
        $this->middleware('demand.parameter');
    }

    /**
     * @return \App\Models\MUser|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return Auth::user();
    }

    /**
     * @param $content
     * @return mixed
     */
    public function getMessageResponseSuccess($content)
    {
        $message['content'] = $content;
        $message['type'] = 'SUCCESS';
        return $message;
    }
}
