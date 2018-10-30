<?php

namespace App\Http\Controllers\TargetArea;

use App\Http\Controllers\Controller;
use App\Repositories\MCorpCategoryRepositoryInterface;

class TargetAreaController extends Controller
{
    /**
     * @var MCorpCategoryRepositoryInterface
     */
    protected $mCorpCategoryRepo;

    /**
     * TargetAreaController constructor.
     *
     * @param MCorpCategoryRepositoryInterface $mCorpCategoryRepository
     */
    public function __construct(MCorpCategoryRepositoryInterface $mCorpCategoryRepository)
    {
        parent::__construct();
        $this->mCorpCategoryRepo = $mCorpCategoryRepository;
    }

    /**
     * Show page /target_area/{$corpId}
     *
     * @author Nguyen.DoNhu <Nguyen.DoNhu@Nashtechglobal.com>
     * @param  $corpId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($corpId)
    {
        $results = $this->mCorpCategoryRepo->getByCorpIdForTargetArea($corpId);

        return view("target_area.detail", ["results" => $results]);
    }
}
