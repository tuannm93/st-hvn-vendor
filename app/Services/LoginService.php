<?php

namespace App\Services;

use App\Repositories\MUserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MCorpRepositoryInterface;

class LoginService
{
    /**
     * @var MCorpRepositoryInterface
     */
    private $mCorpRepository;

    /**
     * @var MUserRepositoryInterface
     */
    private $mUserRepository;

    /**
     * LoginService constructor.
     *
     * @param MCorpRepositoryInterface $mCorpRepository
     */
    public function __construct(MCorpRepositoryInterface $mCorpRepository, MUserRepositoryInterface $mUserRepository)
    {
        $this->mCorpRepository = $mCorpRepository;
        $this->mUserRepository = $mUserRepository;
    }

    /**
     * check permission for show or hide link
     *
     * @param  $dataRequest
     * @return boolean
     */
    public function checkGuideline($dataRequest)
    {
        if (Auth::user()->auth == 'affiliation') {
            $affiliation = $this->mCorpRepository->getFirstById(Auth::user()->affiliation_id);
            if (!isset($dataRequest['guideline'])) {
                if (empty($affiliation->guideline_check_date) || (!empty($affiliation->guideline_check_date)
                    && strtotime($affiliation->guideline_check_date) < strtotime(\Config::get('datacustom.GUIDELINE_DATE')))
                ) {
                    return false;
                } else {
                    return true;
                }
            }
            if (isset($dataRequest['guideline'])) {
                if (empty($affiliation->guideline_check_date)
                    || (!empty($affiliation->guideline_check_date)
                    && strtotime($affiliation->guideline_check_date) < strtotime(\Config::get('datacustom.GUIDELINE_DATE')))
                ) {
                    return $this->mCorpRepository->updateGuidelineCheckDate($affiliation->id);
                }
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function checkMcorpDelflg()
    {
        if (Auth::user()->auth == 'affiliation') {
            $affiliation = $this->mCorpRepository->getFirstByIdNotDelFlag(Auth::user()->affiliation_id);
            if (empty($affiliation->guideline_check_date) || strtotime($affiliation->guideline_check_date) < strtotime(\Config::get('datacustom.GUIDELINE_DATE')))
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function checkUserLogin($data)
    {
        return $this->mUserRepository->getUserByUserIdAndPassword($data);
    }
}
