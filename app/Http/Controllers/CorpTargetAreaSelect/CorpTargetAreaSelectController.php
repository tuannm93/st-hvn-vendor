<?php

namespace App\Http\Controllers\CorpTargetAreaSelect;

use App\Http\Controllers\Controller;
use App\Repositories\MPostRepositoryInterface;
use App\Repositories\MCorpTargetAreaRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class CorpTargetAreaSelectController extends Controller
{

    /**
     * @var MPostRepositoryInterface
     */
    private $mPostRepo;
    /**
     * @var MCorpTargetAreaRepositoryInterface
     */
    private $mCorpTargetAreaRepo;

    /**
     * construct function
     *
     * @param MPostRepositoryInterface           $mPostRepo
     * @param MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepo
     */
    public function __construct(
        MPostRepositoryInterface $mPostRepo,
        MCorpTargetAreaRepositoryInterface $mCorpTargetAreaRepo
    ) {
        parent::__construct();
        $this->mPostRepo = $mPostRepo;
        $this->mCorpTargetAreaRepo = $mCorpTargetAreaRepo;
    }

    /**
     * Show list prefecture_div
     *
     * @param  string $corpId
     * @return view
     */
    public function index($corpId = null)
    {
        if ($corpId == null) {
            abort('404');
        }
        return view(
            'corp_target_area_select.index',
            [
            'corpId' => $corpId,
            'prefList' => $this->getPrefList($corpId)
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function updateAllData(Request $request)
    {
        $corpId = $request->input('corp-id');
        $data = $request->all();
        $result = $this->mPostRepo->allRegistTargetArea($corpId, $data['data']['address1_text']);
        if ($result == true) {
            $textResult = trans('aff_corptargetarea.update');
        } else {
            $textResult = trans('aff_corptargetarea.input_error');
        }
        return response()->json(
            [
            'session' => 'success',
            'result' => $textResult,
            'view' => view(
                'corp_target_area_select.delete_regist_all',
                [
                'corpId' => $corpId,
                'prefList' => $this->getPrefList($corpId)
                ]
            )->render()
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function deleteAllData(Request $request)
    {
        $corpId = $request->input('corp-id');
        $data = $request->all();
        $result = $this->mCorpTargetAreaRepo->removeByCorpId($corpId, $data['data']['address1_text']);
        if ($result == true) {
            $textResult = trans('aff_corptargetarea.update');
        } else {
            $textResult = trans('aff_corptargetarea.input_error');
        }
        return response()->json([
            'session' => 'success',
            'result' => $textResult,
            'view' => view(
                'corp_target_area_select.delete_regist_all',
                [
                'corpId' => $corpId,
                'prefList' => $this->getPrefList($corpId)
                ]
            )->render()
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function registData(Request $request)
    {
        $corpId = $request->input('corp-id');
        $data = $request->all();
        $result = $this->mPostRepo->editTargetArea($corpId, $data['data']);
        $listTargetArea = $this->mPostRepo->searchCorpTargetArea($corpId, $data['data']['address1_text']);
        if ($result == true) {
            return response()->json(
                [
                'session' => 'success',
                'result' => trans('aff_corptargetarea.update'),
                'view' => view(
                    'corp_target_area_select.list_target_areas_ajax',
                    [
                    'listTargetArea' => $listTargetArea,
                    'corpId' => $corpId
                    ]
                )->render()
                ]
            );
        } else {
            return response()->json(
                [
                'session' => 'error',
                'result' => trans('aff_corptargetarea.input_error')
                ]
            );
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function registAddressData(Request $request)
    {
        $corpId = $request->input('corp-id');
        $data = $request->all();
        if (isset($data['data']['nocheckaddress1']) && !empty(array_filter($data['data']['nocheckaddress1']))) {
            $this->mPostRepo->removeTargetAreaAddress($corpId, array_filter($data['data']['nocheckaddress1']));
        }
        if (!isset($data['data']['address1'])) {
            return response()->json(
                [
                'session' => 'success',
                'result' => trans('aff_corptargetarea.update'),
                'view' => view(
                    'corp_target_area_select.delete_regist_all',
                    [
                    'corpId' => $corpId,
                    'prefList' => $this->getPrefList($corpId)
                    ]
                )->render()
                ]
            );
        }
        $result = $this->mPostRepo->registTargetAreaAddress($corpId, $data['data']['address1']);
        if ($result == true) {
            $textResult = trans('aff_corptargetarea.update');
        } else {
            $textResult = trans('aff_corptargetarea.input_error');
        }
        return response()->json(
            [
            'session' => 'success',
            'result' => $textResult,
            'view' => view(
                'corp_target_area_select.delete_regist_all',
                [
                'corpId' => $corpId,
                'prefList' => $this->getPrefList($corpId)
                ]
            )->render()
            ]
        );
    }
    /**
     * @param $corpId
     * @return array
     */
    public function getPrefList($corpId)
    {
        // Prefecture list (All region correspondence -
        // Partial region correspondence - No correspondence available setting)
        $prefList = [];

        foreach (Config::get('rits.prefecture_div') as $prefectureDivKey => $prefectureDivValue) {
            $obj = [];
            $obj['id'] = $prefectureDivKey;
            $translatedPrefectureDivValue = __("rits_config.$prefectureDivValue");
            $obj['name'] = $translatedPrefectureDivValue;
            // Number of areas set by franchisees of designated prefectures
            $corpCount = $this->mPostRepo->getCorpPrefAreaCount($corpId, $translatedPrefectureDivValue);
            if ($corpCount > 0) {
                // Number of areas in the specified prefecture
                $areaCount = $this->mPostRepo->getPrefAreaCount($translatedPrefectureDivValue);
                if ($corpCount >= $areaCount) {
                    // All regions correspondence
                    $obj['rank'] = 2;
                } else {
                    // For some areas
                    $obj['rank'] = 1;
                }
            } else {
                $obj['rank'] = 0;
            }
            $prefList[] = $obj;
        }

        return $prefList;
    }
}
