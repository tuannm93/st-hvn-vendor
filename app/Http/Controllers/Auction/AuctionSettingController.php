<?php

namespace App\Http\Controllers\Auction;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoMTimeFormRequest;
use App\Http\Requests\ExclusionRequest;
use App\Repositories\AuctionGenreRepositoryInterface;
use App\Repositories\ExclusionTimeRepositoryInterface;
use App\Http\Requests\PrefectureDetailRequest;
use App\Repositories\AuctionGenreAreaRepositoryInterface;
use App\Repositories\MTimeRepositoryInterface;
use App\Services\AuctionSettingService;
use App\Services\ExclusionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\RedirectResponse;
use App\Repositories\PublicHolidayRepositoryInterface;
use App\Http\Requests\AuctionGenreRequest;
use App\Services\UserService;

class AuctionSettingController extends Controller
{
    /**
     * @var MTimeRepositoryInterface
     */
    protected $mTimeRepository;

    /**
     * @var AuctionGenreRepositoryInterface
     */
    public $auctionGenreRepo;

    /**
     * @var ExclusionTimeRepositoryInterface
     */
    public $exclusionTimeRepo;

    /**
     * @var AuctionSettingService
     */
    private $service;

    /**
     * @var AuctionGenreAreaRepositoryInterface
     */
    protected $auctionGenreAreaRepo;

    /**
     * @var PublicHolidayRepositoryInterface
     */
    public $publicHolidayRepository;

    /**
     * @var exclusionService
     */
    protected $exclusionService;

    /**
     * AuctionSettingController constructor.
     *
     * @param MTimeRepositoryInterface            $mTimeRepository
     * @param AuctionGenreRepositoryInterface     $auctionGenreRepo
     * @param ExclusionTimeRepositoryInterface    $exclusionTimeRepo
     * @param AuctionSettingService               $auctionSettingService
     * @param AuctionGenreAreaRepositoryInterface $auctionGenreAreaRepository
     * @param PublicHolidayRepositoryInterface    $publicHolidayRepository
     * @param ExclusionService                    $exclusionService
     */
    public function __construct(
        MTimeRepositoryInterface $mTimeRepository,
        AuctionGenreRepositoryInterface $auctionGenreRepo,
        ExclusionTimeRepositoryInterface $exclusionTimeRepo,
        AuctionSettingService $auctionSettingService,
        AuctionGenreAreaRepositoryInterface $auctionGenreAreaRepository,
        PublicHolidayRepositoryInterface $publicHolidayRepository,
        ExclusionService $exclusionService
    ) {
        parent::__construct();
        $this->mTimeRepository = $mTimeRepository;
        $this->auctionGenreRepo = $auctionGenreRepo;
        $this->exclusionTimeRepo = $exclusionTimeRepo;
        $this->service = $auctionSettingService;
        $this->auctionGenreAreaRepo = $auctionGenreAreaRepository;
        $this->publicHolidayRepository = $publicHolidayRepository;
        $this->exclusionService = $exclusionService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->mTimeRepository->get();
        $hourList = range(1, 99);
        $minuteList = range(0, 59);

        return view(
            'auction_setting.index_auction',
            [
            'data' => $data,
            'hourList' => $hourList,
            'minuteList' => $minuteList,
            ]
        );
    }

    /**
     * @param AutoMTimeFormRequest $request
     * @return RedirectResponse
     */
    public function update(AutoMTimeFormRequest $request)
    {
        if ($request->isMethod('post')) {
            $listId = $this->mTimeRepository->count();
            $list = range(1, $listId);
            $data = [];
            foreach ($list as $key) {
                $id = $request->input('id' . $key);
                $data['item_id'] = $request->get('item_id' . $key);
                $data['item_detail'] = $request->get('item_detail' . $key);
                $data['item_category'] = $request->get('item_category' . $key);
                $data['item_hour_date'] = $request->get('item_hour_date' .$key);
                $data['item_minute_date'] = $request->get('item_minute_date' .$key);
                $data['item_type'] = $request->get('item_type' . $key);
                $data['modified'] = date('Y-m-d H:i:s');
                $data['modified_user_id'] = Auth::user()['user_id'];
                $this->mTimeRepository->updateData($id, $data);
            }
            $request->session()->flash('success', Lang::get('mtime.message_successfully'));
        }

        return redirect()->action('Auction\AuctionSettingController@index');
    }

    /**
     * show detail genre
     *
     * @param  integer $genreId
     * @return object
     */
    public function genreDetail($genreId = null)
    {
        if (empty($genreId)) {
            return redirect('/auction_setting/genre');
        }
        $results = $this->auctionGenreRepo->getFirstByGenreId($genreId);
        $exclusionTimeList = $this->exclusionTimeRepo->getList();
        $genreName = $this->service->getMGenreNameById($genreId);
        $acaAccount = false;
        if (UserService::checkRole('accounting_admin')) {
            $acaAccount = true;
        }
        return view(
            'auction_setting.genre_detail',
            [
                "results" => $results,
                "exclusionTimeList" => $exclusionTimeList,
                'genreName' => $genreName,
                'genreId' => $genreId,
                'acaAccount' => $acaAccount
            ]
        );
    }

    /**
     * save data genre
     *
     * @param  AuctionGenreRequest $request
     * @return object
     */
    public function genreDetailRegist(AuctionGenreRequest $request)
    {
        $data = $request->all();
        if (isset($data['regist'])) {
            $result = $this->auctionGenreRepo->saveAuctionGenre($data['data']['AuctionGenre']);
            if ($result == true) {
                $request->session()->flash('Update', trans('auction.message_success'));
            } else {
                $request->session()->flash('InputError', trans('auction.message_failure'));
            }
        }

        return back();
    }

    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @return $this
     */
    public function getFlowing()
    {
        $disabled = '';
        if (auth()->user()->auth == 'accounting_admin') {
            $disabled = 'disabled';
        }
        $years = $this->service->getListYear();
        $months = $this->service->getListMonth();
        $genres = $this->service->getDropListGenre(true);

        return view('auction_setting.flowing')->with(compact('years', 'months', 'genres', 'disabled'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function postFlowing(Request $request)
    {

        set_time_limit(0);
        //recieved input
        $genresIds = !empty($request->get('genreIds')) ? $request->get('genreIds') : [];
        $year = $request->get('year');
        $month = $request->get('month');
        $disabled = '';
        if (auth()->user()->auth == 'accounting_admin') {
            $disabled = 'disabled';
        }
        // building data raking, ratio
        $rankData = $this->service->buildDemandInfoRatingData($genresIds, $year, $month);
        //export excel
        if ($request->has('csv')) {
            $file = $this->service->exportExcel($rankData);

            return $file->download('csv');
        }

        // get list from database
        $years = $this->service->getListYear();
        $months = $this->service->getListMonth();
        $genres = $this->service->getDropListGenre(true);

        return view('auction_setting.flowing')->with(compact('years', 'months', 'genres', 'rankData', 'disabled'));
    }

    /**
     * @author ducnguyent3 Duc.NguyenTai@nashtechglobal.com
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSelectedGenres(Request $request)
    {

        $path = $request->path();
        $listSelectedGenres = $this->service->getSelectedGenresData();

        return view(
            'auction_setting.genre',
            [
            'listGenre' => $listSelectedGenres,
            'path' => $path,
            ]
        );
    }

    /**
     * @param null $genreId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrefecture($genreId = null)
    {
        $genreName = null;
        if (!empty($genreId)) {
            $genreName = $this->service->getMGenreNameById($genreId);
        }
        $prefectureDiv = \Config::get('datacustom.prefecture_div');

        return view(
            'auction_setting.prefecture',
            [
            'genreId' => $genreId,
            'genreName' => $genreName,
            'prefectureDiv' => $prefectureDiv,
            ]
        );
    }

    /**
     * show page auction setting follow
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function follow(Request $request)
    {
        $data = $request->all();
        $followData = $this->mTimeRepository->getByItemCategoryFollowTel();
        $spareTime = $this->service->getSpareTimeFromFollowData($followData);
        $detailSort = $this->service->fomartDetailSortFollow($data);
        $results = $this->service->getAuctionSettingFollow($detailSort, $spareTime, $followData);
        $arrayListItemSort = $this->service->getArrayListItemSortFollow();

        return view('auction_setting.follow', compact('results', 'detailSort', 'arrayListItemSort'));
    }

    /**
     * ajax auction setting follow
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxFollow(Request $request)
    {
        $data = $request->all();
        $followData = $this->mTimeRepository->getByItemCategoryFollowTel();
        $spareTime = $this->service->getSpareTimeFromFollowData($followData);
        $detailSort = $this->service->fomartDetailSortFollow($data);
        $results = $this->service->getAuctionSettingFollow($detailSort, $spareTime, $followData);
        $arrayListItemSort = $this->service->getArrayListItemSortFollow();

        return view('auction_setting.components.follow', compact('results', 'detailSort', 'arrayListItemSort'));
    }

    /**
     * get auction_genres_area
     *
     * @param  null $genreId
     * @param  null $prefCd
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrefectureDetail($genreId = null, $prefCd = null)
    {
        $results = $this->auctionGenreAreaRepo->getFirstByGenreIdAndPrefCd($genreId, $prefCd);
        $exclusionTimeList = $this->exclusionTimeRepo->getList();
        $genreName = $this->service->getMGenreNameById($genreId);
        $prefectureCd = getDivTextJP('prefecture_div', $prefCd);
        $acaAccount = false;
        if (UserService::checkRole('accounting_admin')) {
            $acaAccount = true;
        }
        return view('auction_setting.prefecture_detail', compact('results', 'exclusionTimeList', 'genreName', 'genreId', 'prefCd', 'prefectureCd', 'acaAccount'));
    }

    /**
     * insert or update auction_genre_areas
     *
     * @param  PrefectureDetailRequest $request
     * @return RedirectResponse|string
     */
    public function postPrefectureDetail(PrefectureDetailRequest $request)
    {
        try {
            $dataAuctionGenreArea = $request->all();
            if ($this->auctionGenreAreaRepo->saveData($dataAuctionGenreArea)) {
                $request->session()->flash('Update', trans('auction_settings.insert'));
            } else {
                $request->session()->flash('InputError', trans('auction_settings.insert_error'));
            }

            return back();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * get public_holidays and exclusion_times table data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getExclusion()
    {
        $getPublicHoliday = $this->publicHolidayRepository->getPublicHolidayExclusion()->toArray();
        $time = $this->exclusionTimeRepo->getExclusionTime()->toArray();
        return view('auction_setting.exclusion', ['getPublicHoliday' => $getPublicHoliday, 'time' => $time]);
    }

    /**
     * update exclusion_times and public_holidays table data
     * @param ExclusionRequest $request
     * @return RedirectResponse
     */
    public function postExclusion(ExclusionRequest $request)
    {
        if ($this->exclusionService->postExclusion($request->all())) {
            $request->session()->flash('success', trans('exclusion.message_successfully'));
            $getPublicHoliday = $this->publicHolidayRepository->getPublicHolidayExclusionOld()->toArray();
            $time = $this->exclusionTimeRepo->getExclusionTime()->toArray();
            return view('auction_setting.exclusion', ['getPublicHoliday' => $getPublicHoliday, 'time' => $time]);
        } else {
            $request->session()->flash('error', trans('exclusion.message_failure'));
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function ranking(Request $request)
    {
        $dataRequest['aggregate_date'] = date("Y/m/d");
        $dataRequest['aggregate_period'] = 'day';

        if ($request->isMethod('post')) {
            $dataRequest['aggregate_date'] = !empty($request->input('aggregate_date')) ? $request->input('aggregate_date') : date("Y/m/d");
            $dataRequest['aggregate_period'] = $request->input('aggregate_period');
            $request->session()->put('dataRequest', $dataRequest);
        } elseif ($request->get('page')) {
            $dataRequest = $request->session()->has('dataRequest') ? $request->session()->get('dataRequest') : $dataRequest;
        }

        if ($request->input('submit') == \Config::get('constant.DOWNLOAD_CSV')) {
            return $this->service->downloadCSVAuctionRanking($dataRequest);
        } else {
            $results = $this->service->getSearchAuctionRanking($dataRequest);
            $countSearchAuctionRanking = $this->service->getCountSearchAuctionRanking($dataRequest);
        }

        return view('auction_setting.ranking', compact('results', 'countSearchAuctionRanking', 'dataRequest'));
    }
}
