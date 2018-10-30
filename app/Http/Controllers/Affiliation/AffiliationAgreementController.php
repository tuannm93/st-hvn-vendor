<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\BaseController;
use App\Services\Affiliation\AgreementTermsService;

class AffiliationAgreementController extends BaseController
{
    /**
     * @var AgreementTermsService
     */
    protected $agreementTermsService;

    /**
     * AffiliationAgreementController constructor.
     *
     * @param AgreementTermsService $agreementTermsService
     */
    public function __construct(
        AgreementTermsService $agreementTermsService
    ) {
        parent::__construct();
        $this->agreementTermsService = $agreementTermsService;
    }

    /**
     * Download agreement terms
     *
     * @param  integer $corpId
     * @param  integer $agreementId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadAgreementTerms($corpId, $agreementId = null)
    {
        set_time_limit(0);
        ini_set("pcre.backtrack_limit", "5000000");
        $file = $this->agreementTermsService->outputPDF($corpId, $agreementId);
        if (!$file) {
            return view('errors.404');
        }
        return $file;
    }
}
