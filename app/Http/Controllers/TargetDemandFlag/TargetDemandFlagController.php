<?php

namespace App\Http\Controllers\TargetDemandFlag;

use App\Http\Controllers\Controller;
use App\Repositories\MGenresRepositoryInterface;
use App\Services\TargetDemandFlagService;
use Illuminate\Http\Request;

class TargetDemandFlagController extends Controller
{
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;
    /**
     * @var TargetDemandFlagService
     */
    protected $targetDemandFlagService;

    /**
     * TargetDemandFlagController constructor.
     *
     * @param MGenresRepositoryInterface $mGenresRepository
     * @param TargetDemandFlagService    $targetDemandFlagService
     */
    public function __construct(
        MGenresRepositoryInterface $mGenresRepository,
        TargetDemandFlagService $targetDemandFlagService
    ) {
        parent::__construct();
        $this->mGenresRepository = $mGenresRepository;
        $this->targetDemandFlagService = $targetDemandFlagService;
    }

    /**
     * Show list target demand flag
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $condition = ['valid_flg' => 1];
        $order = ['genre_name' => 'asc'];
        $allData = $this->mGenresRepository->getGenreWithConditions($condition, $order)->split(2);
        return view('target_demand_flag.index', ['allData' => $allData]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postTargetDemandFlag(Request $request)
    {
        $exclusionFlg = $request->all()['exclusion_flg'];
        $update = $this->targetDemandFlagService->updateDemandFlag($exclusionFlg);
        if ($update) {
            return redirect()->route('get.target.demand.flag')->with('success', __('target_demand_flag.update_success'));
        } else {
            return redirect()->route('get.target.demand.flag')->with('error', __('target_demand_flag.update_error'));
        }
    }
}
