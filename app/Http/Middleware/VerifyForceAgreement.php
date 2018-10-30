<?php

namespace App\Http\Middleware;

use App\Repositories\AntisocialCheckRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\ProgImportFilesRepositoryInterface;
use App\Services\CorpAgreementService;
use App\Services\ProgCorpService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class VerifyForceAgreement
{
    /**
     * @var CorpAgreementService
     */
    private $corpAgreementService;
    /**
     * @var ProgCorpService
     */
    private $progCorpService;
    /**
     * @var ProgImportFilesRepositoryInterface
     */
    private $progImportFilesRepository;
    /**
     * @var AntisocialCheckRepositoryInterface
     */
    private $antisocialCheckRepository;
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * VerifyForceAgreement constructor.
     *
     * @param CorpAgreementService               $corpAgreementService
     * @param ProgCorpService                    $progCorpService
     * @param ProgImportFilesRepositoryInterface $progImportFilesRepository
     * @param AntisocialCheckRepositoryInterface $antisocialCheckRepository
     * @param MCorpRepositoryInterface           $mCorpRepository
     */
    public function __construct(
        CorpAgreementService $corpAgreementService,
        ProgCorpService $progCorpService,
        ProgImportFilesRepositoryInterface $progImportFilesRepository,
        AntisocialCheckRepositoryInterface $antisocialCheckRepository,
        MCorpRepositoryInterface $mCorpRepository
    ) {
        $this->corpAgreementService = $corpAgreementService;
        $this->progCorpService = $progCorpService;
        $this->progImportFilesRepository = $progImportFilesRepository;
        $this->antisocialCheckRepository = $antisocialCheckRepository;
        $this->mCorpRepository = $mCorpRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->isMethod('GET')
            || $request->ajax()
            || $request->is(['login', 'logout', 'agreement-system/*'])
            || Auth::user()->auth != 'affiliation'
        ) {
            return $next($request);
        }

        if ($this->corpAgreementService->isAgreementDialogShow(Auth::user()->affiliation_id)) {
            view()->share('isAgreementDialogShow', 'true');
            $haveAgreementCancel = time() < strtotime(Config::get('rits.agreement_grace_date'));
            view()->share('haveAgreementCancel', $haveAgreementCancel);
        }

        if ($this->progCorpService->isShowProg(Auth::user()->affiliation_id)) {
            view()->share('isProgDialogShow', 'true');
            $progImportFile = $this->progImportFilesRepository->getImportFileReleased();
            view()->share('importFileId', $progImportFile->id);
        }

        $antisocialFollow = $this->antisocialCheckRepository->getAntisocialFollow(Auth::user()->affiliation_id);
        if (!empty($antisocialFollow)) {
            view()->share('antisocialFollowId', $antisocialFollow->m_corps_id);
            // Antisocial modal box show only first time
            $mCorp = $this->mCorpRepository->find($antisocialFollow->m_corps_id);
            $mCorp->antisocial_display_flag = 0;
            $this->mCorpRepository->save($mCorp);
        }


        return $next($request);
    }
}
