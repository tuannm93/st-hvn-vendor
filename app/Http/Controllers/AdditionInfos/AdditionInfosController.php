<?php

namespace App\Http\Controllers\AdditionInfos;

use App\Http\Controllers\Controller;
use Session;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AdditionInfoRepositoryInterface;
use App\Repositories\MGenresRepositoryInterface;
use App\Repositories\DemandInfoRepositoryInterface;
use App\Http\Requests\AddAdditionInfoRequest;
use Exception;

class AdditionInfosController extends Controller
{

    /**
     * @var AdditionInfoRepositoryInterface
     */
    protected $additionInfosRepository;
    /**
     * @var MGenresRepositoryInterface
     */
    protected $mGenresRepository;
    /**
     * @var DemandInfoRepositoryInterface
     */
    protected $demandInfo;

    /**
     * AdditionInfosController constructor.
     *
     * @param AdditionInfoRepositoryInterface $additionInfoRepository
     * @param MGenresRepositoryInterface      $mGenresRepository
     * @param DemandInfoRepositoryInterface   $demandInfo
     */
    public function __construct(
        AdditionInfoRepositoryInterface $additionInfoRepository,
        MGenresRepositoryInterface $mGenresRepository,
        DemandInfoRepositoryInterface $demandInfo
    ) {
        parent::__construct();
        $this->additionInfosRepository = $additionInfoRepository;
        $this->mGenresRepository = $mGenresRepository;
        $this->demandInfo = $demandInfo;
    }

    /**
     * Show the application dashboard.
     *
     * @param  Request $request
     * @param  null    $demandId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $demandId = null)
    {
        $authUser = Auth::user();
        $demandInfos = '';
        $demandType = Config::get('constant.demand_type');
        if (empty($demandId)) {
            $conditions = [
                'corp_id' => $authUser["affiliation_id"],
                'demand_flg' => 0,
                'del_flg' => 0
            ];
        } else {
            $conditions = [
                'corp_id' => $authUser["affiliation_id"],
                'demand_id' => $demandId,
                'demand_flg' => 0,
                'del_flg' => 0
            ];
            $demandInfos = $this->demandInfo->find($demandId);
        }
        $isMobile = utilIsMobile($request->header('user-agent'));
        $conditions['isMobile'] = $isMobile;
        $results = $this->additionInfosRepository->getAdditionList($conditions);


        $whereCondition = [
            ['valid_flg','=', 1],
            ['commission_type','<>', 2]
        ];
        $genreList = $this->mGenresRepository->getListForAdditionForm($whereCondition)->get();

        return view('addition.index', compact('genreList', 'results', 'demandInfos', 'demandType', 'isMobile'));
    }

    /**
     * @param AddAdditionInfoRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function regist(AddAdditionInfoRequest $request)
    {
        try {
            $authUser = Auth::user();
            $input = $request->only(
                [
                'demand_id',
                'customer_name',
                'genre_id',
                'construction_price_tax_exclude',
                'complete_date',
                'demand_type_update',
                'note',
                'falsity_flg'
                ]
            );
            $input['corp_id'] = $authUser["affiliation_id"] !== null ? $authUser["affiliation_id"] : null;
            $model = $this->additionInfosRepository->save($input);
            if (!$model) {
                return redirect()->back()->withErrors(trans('addition.save_fail'))->withInput();
            }
            Session::flash('message', trans('addition.create_success'));
            return redirect()->action('AdditionInfos\AdditionInfosController@index')->with('message-success', trans('addition.create_success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(trans('addition.save_fail'))->withInput();
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $id = $request->addition_id;
        $result = $this->additionInfosRepository->delete($id);
        if ($result) {
            Session::flash('message', trans('addition.delete_success'));
            return redirect()->action('AdditionInfos\AdditionInfosController@index')->with('message-success', trans('addition.delete_success'));
        } else {
            Session::flash('message', trans('addition.delete_fail'));
            return redirect()->back()->withErrors(trans('addition.delete_fail'));
        }
    }
}
