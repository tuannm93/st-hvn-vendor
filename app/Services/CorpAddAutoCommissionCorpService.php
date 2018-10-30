<?php

namespace App\Services;

use App\Repositories\AutoCommissionCorpRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CorpAddAutoCommissionCorpService
{
    /**
     * @var AutoCommissionCorpRepositoryInterface
     */
    protected $autoCommissionCorpRepository;

    /**
     * CorpAddAutoCommissionCorpService constructor.
     *
     * @param AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepository
     */
    public function __construct(AutoCommissionCorpRepositoryInterface $autoCommissionCorpRepository)
    {
        $this->autoCommissionCorpRepository = $autoCommissionCorpRepository;
    }

    /**
     * @param $prefectureKey
     * @return mixed|string
     */
    public function getPrefecture($prefectureKey)
    {
        if (!empty(config('datacustom.state_list')[$prefectureKey])) {
            return config('datacustom.state_list')[$prefectureKey];
        } else {
            return '';
        }
    }

    /**
     * @param $key
     * @param $array
     * @return string
     */
    public function checkUndefinedOffsetKey($key, $array)
    {

        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return '';
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function getJiscd($data)
    {
        if (!empty($data['address1'])) {
            $dataAddress = $this->checkUndefinedOffsetKey($data['address1'], config('datacustom.state_list'));
        } elseif (!empty($data['pref_cd'])) {
            $dataAddress = $this->checkUndefinedOffsetKey($data['pref_cd'], config('datacustom.state_list'));
        } else {
            $dataAddress = '';
        }
        return $dataAddress;
    }

    /**
     * @param $corpListResult
     * @return boolean
     */
    public function deleteMultiRecord($corpListResult)
    {
        try {
            $listIdDelete = [];
            if (!empty($corpListResult)) {
                foreach ($corpListResult->toArray() as $value) {
                    $listIdDelete[] = $value['auto_commission_corp_id'];
                }
                $this->autoCommissionCorpRepository->deleteListItemByArrayId($listIdDelete);
            }
            return true;
        } catch (\Exception $exception) {
            $exception->getMessage();
            return false;
        }
    }

    /**
     * @param array       $data
     * @param $jiscdResult
     * @return array
     */
    public function setDataToSave($data = [], $jiscdResult = [])
    {
        $saveData = [];
        if (isset($data['commission_corp_id']) && count($jiscdResult)) {
            foreach ($data['commission_corp_id'] as $commissionSort => $commissionCorpId) {
                foreach ($jiscdResult as $value) {
                    $saveData[] = [
                        'corp_id' => $commissionCorpId,
                        'category_id' => $data['category_id'],
                        'sort' => $commissionSort,
                        'jis_cd' => $value['jis_cd'],
                        'created_user_id' => Auth::user()['user_id'],
                        'modified_user_id' => Auth::user()['user_id'],
                        'modified' => Carbon::now(),
                        'created' => Carbon::now(),
                        'process_type' => 2
                    ];
                }
            }
        }
        if (isset($data['selection_corp_id']) && count($jiscdResult)) {
            foreach ($data['selection_corp_id'] as $selectionSort => $selectionCorpId) {
                foreach ($jiscdResult as $value) {
                    $saveData[] = [
                        'corp_id' => $selectionCorpId,
                        'category_id' => $data['category_id'],
                        'sort' => $selectionSort,
                        'jis_cd' => $value['jis_cd'],
                        'created_user_id' => Auth::user()['user_id'],
                        'modified_user_id' => Auth::user()['user_id'],
                        'created' => Carbon::now(),
                        'modified' => Carbon::now(),
                        'process_type' => 1
                    ];
                }
            }
        }
        return $saveData;
    }
}
