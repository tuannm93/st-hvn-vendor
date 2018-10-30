<?php

namespace App\Http\Controllers\Agreement;

use App\Http\Controllers\Controller;
use App\Repositories\AgreementAttachedFileRepositoryInterface;
use App\Repositories\CategoryLicenseLinkRepositoryInterface;
use App\Repositories\CorpLicenseLinkRepositoryInterface;
use App\Repositories\AgreementAdminLicenseRepositoryInterface;
use App\Services\AgreementAdminLicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class AgreementAdminLicenseController extends Controller
{
    /**
     * @var AgreementAdminLicenseRepositoryInterface
     */
    protected $agreementLicenseRepository;
    /**
     * @var AgreementAdminLicenseService
     */
    protected $agreementLicenseService;

    /**
     * @var CategoryLicenseLinkRepositoryInterface
     */
    protected $categoryLicenseLinkRepository;

    /**
     * @var AgreementAttachedFileRepositoryInterface
     */
    protected $agreementAttachedFileRepo;

    /**
     * @var CorpLicenseLinkRepositoryInterface
     */
    protected $corpLicenseLinkRepository;

    /**
     * AgreementAdminLicenseController constructor.
     * @param AgreementAdminLicenseRepositoryInterface $agreementLicenseRepository
     * @param AgreementAdminLicenseService $agreementLicenseService
     * @param CategoryLicenseLinkRepositoryInterface $categoryLicenseLinkRepository
     * @param AgreementAttachedFileRepositoryInterface $agreementAttachedFileRepo
     * @param CorpLicenseLinkRepositoryInterface $corpLicenseLinkRepository
     */
    public function __construct(
        AgreementAdminLicenseRepositoryInterface $agreementLicenseRepository,
        AgreementAdminLicenseService $agreementLicenseService,
        CategoryLicenseLinkRepositoryInterface $categoryLicenseLinkRepository,
        AgreementAttachedFileRepositoryInterface $agreementAttachedFileRepo,
        CorpLicenseLinkRepositoryInterface $corpLicenseLinkRepository
    ) {
        parent::__construct();
        $this->agreementLicenseRepository = $agreementLicenseRepository;
        $this->agreementLicenseService = $agreementLicenseService;
        $this->categoryLicenseLinkRepository = $categoryLicenseLinkRepository;
        $this->agreementAttachedFileRepo = $agreementAttachedFileRepo;
        $this->corpLicenseLinkRepository = $corpLicenseLinkRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLicensePage()
    {
        return view('agreement.admin.license.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getLicenseData(Request $request)
    {
        return $this->agreementLicenseService->getAgreementLicenseData($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addLicense(Request $request)
    {
        $this->agreementLicenseRepository->addLicense($request);
        $content = Lang::get('agreement_admin.registration_complete');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getLicenseDetail($id)
    {
        return $this->agreementLicenseRepository->getLicenseById($id);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateLicense(Request $request)
    {
        $this->agreementLicenseRepository->updateLicense($request);
        $content = Lang::get('agreement_admin.content_update_successfully');
        return $this->getMessageResponseSuccess($content);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteLicense($id)
    {
        try {
            $attachedFiles = $this->agreementAttachedFileRepo->findByLicenseId($id);
            $corps = $this->corpLicenseLinkRepository->findByLicenseId($id);
            if (! checkIsNullOrEmptyCollection($attachedFiles) || !checkIsNullOrEmptyCollection($corps)) {
                $message['type'] = 'ERROR';
                $message['content'] = 'This license has constraints in corp_license_link table or agreement_attached_file table';
            } else {
                $this->categoryLicenseLinkRepository->deleteByLicenseId($id);
                $this->agreementLicenseRepository->deleteLicenseById($id);
                $content = Lang::get('agreement_admin.content_delete_successfully');
                $message = $this->getMessageResponseSuccess($content);
            }
        } catch (\Exception $exception) {
            $message['type'] = 'ERROR';
            $message['content'] = 'Exception';
        }
        return $message;
    }
}
