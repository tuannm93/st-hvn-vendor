<?php

namespace App\Http\Controllers\Auction;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuctionRefusalRequest;
use App\Repositories\AuctionInfoRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;
use App\Repositories\VisitTimeRepositoryInterface;
use App\Services\Auction\BaseAuctionService;
use App\Services\Auction\AuctionService;
use App\Services\Auction\AuctionSupportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuctionController extends Controller
{
    /**
     * @var AuctionInfoRepositoryInterface
     */
    protected $auctionInfoRepository;

    /**
     * @var MTimeRepositoryInterface
     */
    protected $timeRepository;

    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfoRepository;

    /**
     * @var VisitTimeRepositoryInterface
     */
    protected $visitTimeRepository;

    /**
     * @var MCorpRepositoryInterface
     */
    protected $corpRepository;

    /**
     * @var AuctionService
     */
    protected $auctionService;

    /**
     * @var BaseAuctionService
     */
    protected $baseAuctionService;

    /**
     * @var AuctionSupportService
     */
    protected $auctionSupportService;

    /**
     * Instantiate a new controller instance.
     *
     * @param AuctionInfoRepositoryInterface $auctionInfoRepository
     * @param MTimeRepositoryInterface $timeRepository
     * @param DemandInfoRepositoryInterface $demandInfoRepository
     * @param VisitTimeRepositoryInterface $visitTimeRepository
     * @param MCorpRepositoryInterface $corpRepository
     * @param AuctionService $auctionService
     * @param BaseAuctionService $baseAuctionService
     * @param AuctionSupportService $auctionSupportService
     */
    public function __construct(
        AuctionInfoRepositoryInterface $auctionInfoRepository,
        MTimeRepositoryInterface $timeRepository,
        DemandInfoRepositoryInterface $demandInfoRepository,
        VisitTimeRepositoryInterface $visitTimeRepository,
        MCorpRepositoryInterface $corpRepository,
        AuctionService $auctionService,
        BaseAuctionService $baseAuctionService,
        AuctionSupportService $auctionSupportService
    ) {
        parent::__construct();
        $this->auctionInfoRepository = $auctionInfoRepository;
        $this->timeRepository = $timeRepository;
        $this->demandInfoRepository = $demandInfoRepository;
        $this->visitTimeRepository = $visitTimeRepository;
        $this->corpRepository = $corpRepository;
        $this->auctionService = $auctionService;
        $this->baseAuctionService = $baseAuctionService;
        $this->auctionSupportService = $auctionSupportService;
    }

    /**
     * Show page index
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dataSession = null;
        $dataSessionForKameiten = null;
        if (Session::has(self::$sessionKeyForAuctionSearch)) {
            $dataSession = Session::get(self::$sessionKeyForAuctionSearch)[0];
        }
        if (Session::has(self::$sessionKeyForAuctionSearchForKameiten)) {
            $dataSessionForKameiten = Session::get(self::$sessionKeyForAuctionSearchForKameiten)[0];
        }
        $compact = $this->baseAuctionService->indexAuction($user, $dataSession, $dataSessionForKameiten);

        return view('auction.index')->with($compact);
    }

    /**
     * Search auction
     *
     * @param  Request $request
     * @return Response|\Illuminate\Http\RedirectResponse
     */
    public function postSearch(Request $request)
    {
        try {
            $dataRequest = $request->all();
            Session::forget(self::$sessionKeyForAuctionSearch);
            Session::push(self::$sessionKeyForAuctionSearch, $dataRequest);
            $user = Auth::user();
            return $this->baseAuctionService->searchAuction($user, $dataRequest);
        } catch (\Exception $ex) {
            $results = [];
            return view('auction.search', compact('results'));
        }
    }

    /**
     * sort auction for kameiten
     *
     * @param  Request $request
     * @return Response|\Illuminate\Http\RedirectResponse
     */
    public function sortForKameiten(Request $request)
    {
        try {
            $dataRequest = $request->all();
            Session::forget(self::$sessionKeyForAuctionSearchForKameiten);
            Session::push(self::$sessionKeyForAuctionSearchForKameiten, $dataRequest);
            $user = Auth::user();
            return $this->baseAuctionService->sortForKameiten($user, $dataRequest);
        } catch (\Exception $ex) {
            $results = [];
            return view('auction.kameiten', compact('results'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAuction(Request $request)
    {
        try {
            $dataRequest = $request->all();
            if ($this->auctionInfoRepository->deleteItemByListId($dataRequest)) {
                $request->session()->flash('alert-success', trans('auction.message_success'));
            } else {
                $request->session()->flash('alert-danger', trans('auction.message_failure'));
            }

            return redirect()->back();
        } catch (\Exception $ex) {
            $request->session()->flash('alert-danger', trans('auction.message_failure'));

            return redirect()->back();
        }
    }

    /**
     * get view auction support
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function support($id = null)
    {
        try {
            $auctionId = (int)$id;

            $commissionData = $this->auctionSupportService->getCommissionDataSupport($auctionId);
            if (!empty($commissionData)) {
                return response()->json([
                    'canBid' => false,
                    'view' => view('auction.support',
                                    [
                                        'results' => $commissionData,
                                        'screen' => config('constant.refusal.deal_already'),
                                        'auctionId' => $auctionId
                                    ]
                            )->render()
                ]);
            }

            $data = $this->auctionInfoRepository->getAuctionInfoDemandInfo($auctionId);

            if (strtotime($data->auction_deadline_time) <= strtotime(date('Y-m-d H:i:s'))) {
                return response()->json([
                    'canBid' => false,
                    'view' => view('auction.support',
                                    [
                                        'screen' => config('constant.refusal.support_limit'),
                                        'auctionId' => $auctionId
                                    ])->render()
                ]);
            }

            $data = $data->toarray();
            $visitList = $this->visitTimeRepository->findAllByDemandId($data['demand_id']);

            $auctionFee = $this->auctionInfoRepository->getAuctionFee($auctionId);
            $auctionProvisions = $this->auctionSupportService->getItemAuctionAgreement();
            $auctionProvisions = formatTextLineDown($auctionProvisions);
            return response()->json([
                    'canBid' => true,
                    'view' => view(
                                'auction.support',
                                [
                                    'data'=> $data,
                                    'visit_list' => $visitList,
                                    'auction_fee' => $auctionFee,
                                    'auction_provision' => $auctionProvisions,
                                    'screen' => 5,
                                    'auctionId' => $auctionId
                                ]
                            )->render()
                ]);
        } catch (\Exception $exception) {
            $message = ['error' => trans('support.updateFail')];
            Log::error($exception);
            return redirect()->back()->with($message);
        }
    }

    /**
     * update support
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function updateSupport(Request $request)
    {
        $data = $this->auctionService->parsingData($request['data']);
        $demandData = $this->demandInfoRepository->getDemandById($data['demand_id'])->toarray();
        if (strtotime($demandData['auction_deadline_time']) <= strtotime(date('Y-m-d H:i:s'))) {
            return view('auction.support', ['screen' => config('constant.refusal.support_past_time')]);
        }

        $resultEdit = $this->auctionSupportService->editSupport($data);
        if ($resultEdit) {
            $addressDisclosure = $this->timeRepository->getByItemCategory('address_disclosure');
            $telDisclosure     = $this->timeRepository->getByItemCategory('tel_disclosure');
            $auctionFee        = $this->auctionInfoRepository->getAuctionFee($data['id']);
            $auctionProvisions = $this->auctionSupportService->getItemAuctionAgreement();
            $auctionProvisions = formatTextLineDown($auctionProvisions);
            $popupStopFlag     = $this->auctionSupportService->isPopupStopFlag(Auth::user()->affiliation_id);
            $this->auctionSupportService->updateAccumulatedInfoRegistDate($data['demand_id'], Auth::user()->affiliation_id);
            if (!$popupStopFlag) {
                return view(
                    'auction.component.modal-support',
                    ['address_disclosure'=> $addressDisclosure,
                        'demand_data' => $demandData, 'tel_disclosure' => $telDisclosure,
                        'auction_fee' => $auctionFee, 'auction_provisions'=> $auctionProvisions,
                        'screen'      => config('constant.refusal.support_already')
                    ]
                );
            }

            return;
        }
        //update fail
        return view('auction.support', ['screen' => config('constant.refusal.update_fail')]);
    }

    /**
     * update flag for m_corps
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request)
    {
        $popupStopFlg = $request->get('popup_stop_flg');
        try {
            if ($popupStopFlg) {
                $this->auctionService->updatePopupStopFlg(Auth::user()->affiliation_id);
            }

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Exception $exception) {
            Log::info($exception);

            return response()->json([
                'status' => 500,
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateJbrStatus(Request $request)
    {
        $corpId = $request->get('corpId');
        try {
            $data = [
                'jbr_available_status' => 2
            ];
            $this->auctionSupportService->updateJbrStatus($corpId, $data);
            return response()->json([
                'status' => 200,
                ]);
        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json(
                [
                'status' => 500,
                ]
            );
        }
    }

    /**
     * Function refusal for auction
     *
     * @param $auctionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refusal($auctionId)
    {
        $commissionData = $this->baseAuctionService->getCommissionData($auctionId);
        if (empty($commissionData)) {
            $data = $this->auctionInfoRepository->getAuctionInfoDemandInfo($auctionId);
            if (strtotime($data->auction_deadline_time) <= strtotime(date('Y-m-d H:i:s'))) {
                // Correspondence deadline has passed
                $demandStatus = null;
                $screen = config('constant.refusal.support_limit');
            } else {
                // If can deal with the case and no other trader exists
                $demandStatus = $data->demand_status;
                $screen = config('constant.refusal.deal_already');
            }
            $modified = $data->modified;
        } else {
            // When other companies have responded
            $modified = null;
            $demandStatus = null;
            $screen = config('constant.refusal.support_already');
            $commissionData = dateTimeFormat($commissionData->created, config('constant.datetime_format_jp'));
        }
        $data = [
            'modified' => $modified,
            'demandStatus' => $demandStatus,
            'auctionId' => $auctionId,
            'commissionData' => $commissionData,
            'screen' => $screen,
        ];

        return $data;
    }

    /**
     * @param AuctionRefusalRequest $request
     * @param $auctionId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postRefusal(AuctionRefusalRequest $request, $auctionId)
    {
        $allData = $request->all();
        $modified = $allData['modified'];
        unset($allData['modified']);
        $data['refusal'] = $allData;

        if ($data['refusal']['other_contents'] > 1000) {
            return redirect()->back()->withInput();
        } elseif (empty($data['refusal']['other_contents'])) {
            $data['refusal']['other_contents'] = '';
        }

        $data['auctionInfo'] = ['id' => $auctionId, 'refusal_flg' => 1];

        $auction = $this->auctionInfoRepository->find($auctionId);
        if (strtotime($modified) == strtotime($auction->modified)) {
            if ($auction->refusal_flg != 1) {
                if ($this->auctionService->editRefusal($data)) {
                    $this->auctionService->updateAccumulatedInfoRefusalDate($auction->demand_id, $auction->corp_id);
                }
            }
        }

        return redirect()->route('auction.index');
    }

    /**
     * show page proposal
     *
     * @param  integer $demandId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function proposal($demandId)
    {
        $user = Auth::user();
        if ($this->baseAuctionService->isRole($user->auth, ['affiliation'])) {
            $auctionInfo = $this->auctionInfoRepository->getFirstByDemandIdAndCorpId($demandId, $user->affiliation_id);
            if (empty($auctionInfo->first_display_time)) {
                $auctionInfo->first_display_time = date('Y-m-d H:i');
                $this->auctionInfoRepository->save($auctionInfo);
            }
        }
        $demandInfo = $this->demandInfoRepository->find($demandId);

        return view('auction.proposal', compact('demandInfo'));
    }

    /**
     * ajax proposal json
     *
     * @param  integer $demandId
     * @return \Illuminate\Http\JsonResponse
     */
    public function proposalJson($demandId)
    {
        $user = Auth::user();
        if ($this->auctionService->isRole($user->auth, ['affiliation'])) {
            $auctionInfo = $this->auctionInfoRepository->getFirstByDemandIdAndCorpId($demandId, $user->affiliation_id);
            if (empty($auctionInfo->first_display_time)) {
                $auctionInfo->first_display_time = date('Y-m-d H:i');
                $this->auctionInfoRepository->save($auctionInfo);
            }
        }
        $demandInfo = $this->demandInfoRepository->find($demandId);
        $result['id'] = $demandId;
        $result['contents'] = $demandInfo->contents;

        return response()->json($result);
    }
}
