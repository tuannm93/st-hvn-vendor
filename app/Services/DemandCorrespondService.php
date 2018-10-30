<?php
namespace App\Services;

use App\Repositories\DemandCorrespondsRepositoryInterface;
use App\Repositories\MCorpRepositoryInterface;
use Validator;
use Illuminate\Support\Facades\Log;

class DemandCorrespondService
{
    /**
     * @var DemandCorrespondsRepositoryInterface
     */
    public $correspondRepo;
    /**
     * @var MCorpRepositoryInterface
     */
    public $mCorpRepo;

    /**
     * DemandCorrespondService constructor.
     *
     * @param DemandCorrespondsRepositoryInterface $correspondRepo
     * @param MCorpRepositoryInterface             $mCorpRepo
     */
    public function __construct(
        DemandCorrespondsRepositoryInterface $correspondRepo,
        MCorpRepositoryInterface $mCorpRepo
    ) {
        $this->correspondRepo = $correspondRepo;
        $this->mCorpRepo = $mCorpRepo;
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function updateDemandCorrespond($data)
    {
        // If the case correspondence history is not entered, nothing is done
        Log::debug('___ start update demand coresspond ___');
        if (!array_key_exists('demandCorrespond', $data) || empty($data['demandCorrespond']['corresponding_contens'])) {
            Log::debug('___ empty demand corresspond ___');
            return true;
        }

        // Retrieve deal ID
        $demandId = (array_key_exists('id', $data['demandInfo'])) ? $data['demandInfo']['id'] : null;
        // Registration of introduction destination information
        $saveData = $data['demandCorrespond'];
        // In case of new registration
        $dateInsert = date('Y-m-d H:i:s');
        $saveData['demand_id'] = $demandId;
        $saveData['modified_user_id'] = auth()->user()->user_id;
        $saveData['modified'] = $dateInsert;
        $saveData['created_user_id'] = auth()->user()->user_id;
        $saveData['created'] = $dateInsert;
        // Update introduction destination information
        if (!$this->correspondRepo->create($saveData)) {
            throw new \Exception;
        } else {
            $this->createCorrespondingContents($data, $saveData);
            //It indicates that you designated a member shop by area × category
            $this->createOtherCorrespondingContent($data, $saveData);
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    public function validate($data, $frmAction)
    {
        $isValids = [
            $this->checkDatetime($data),
            $this->checkInputResponders($data),
            $this->maxLength20($data),
            $this->overMaxLength1000($data)
        ];
        $isValids[] = $this->checkInputContens($data);
        return !in_array(false, $isValids);
    }

    /**
     * @param $data
     * @return bool
     */
    public function checkInputResponders($data)
    {
        if (empty($data['responders'])) {
            if (!empty($data['corresponding_contens'])) {
                session()->flash('errors.responders', __('demand.validation_error.date_error'));
                return false;
            }
        }
        return true;
    }

    /**
     * Check max length 20
     *
     * @param  array $data
     * @return boolean
     */
    public function maxLength20($data)
    {
        if (strlen($data['responders']) > 20) {
            session()->flash('errors.correspond_datetime', __('demand.validation_error.max_20'));
            return false;
        }
        return true;
    }

    /**
     * Check date time
     *
     * @param $data
     * @return bool
     */
    public function checkDatetime($data)
    {
        if (!empty($data['correspond_datetime']) && (strtotime($data['correspond_datetime']) === false)) {
            session()->flash('errors.correspond_datetime', __('demand.validation_error.date_error'));
            return false;
        }
        return true;
    }

    /**
     * Check input contents
     *
     * @param  array $data
     * @return boolean
     */
    public function checkInputContens($data)
    {
        if (empty($data['corresponding_contens'])) {
            if (!empty($data['responders'])) {
                 session()->flash('errors.corresponding_contens', __('demand.validation_error.corresponding_contens'));
                return false;
            }
        }
        return true;
    }

    /**
     * Over max length 1000
     *
     * @param array $data
     * @return boolean
     */
    public function overMaxLength1000($data)
    {
        if (mb_strlen($data['corresponding_contens']) > 1000) {
            session()->flash('errors.correspond_datetime', __('demand.validation_error.max_1000'));
            return false;
        }
        return true;
    }

    /**
     * Create corresponding contents, with case have do_auto_selection
     *
     * @param $data
     * @param $saveData
     */
    private function createCorrespondingContents($data, $saveData)
    {
        if (!empty($data['demandInfo']['do_auto_selection'])) {
            $saveData['responders'] = '自動選定';
            $correspondingContens = '';
            // Acquire merchant name
            foreach ($data['commissionInfo'] as $commission) {
                if (!empty($commission['corp_id'])
                    && (!empty($commission['created_user_id']))
                    && $commission['created_user_id'] == 'AutomaticAuction') {
                    $corp = $this->mCorpRepo->getFirstById($commission['corp_id']);
                    $correspondingContens .= '自動選定 ['.$corp->corp_name . "]\n";
                }
            }
            if (empty($correspondingContens)) {
                $correspondingContens = '加盟店なし';
            }

            $saveData['corresponding_contens'] = $correspondingContens;
            $this->correspondRepo->create($saveData);
        }
    }

    /**
     * Create other corresponding contents, with case have do_auto_selection_category
     *
     * @param $data
     * @param $saveData
     */
    private function createOtherCorrespondingContent($data, $saveData)
    {
        if (isset($data['demandInfo']['do_auto_selection_category'])
            && $data['demandInfo']['do_auto_selection_category'] == 1 ) {
            //Opportunity ID
            $saveData2['demand_id'] = $data['demandInfo']['id'];

            //Corresponds to $ saveData
            $saveData2['correspond_datetime'] = $saveData['correspond_datetime'];

            //Person in charge
            $saveData2['responders'] = '地域・カテゴリ別自動選定';

            $correspondingContens = '';
            // Acquire merchant name
            foreach ($data['commissionInfo'] as $commission) {
                if (!empty($commission['corp_id'])
                    && (!empty($commission['created_user_id']))
                    && $commission['created_user_id'] == 'AutoCommissionCorp') {
                    $corp = $this->mCorpRepo->getFirstById($commission['corp_id']);
                    $correspondingContens .= '地域・カテゴリ別自動選定 ['.$corp->official_corp_name . "]\n";
                }
            }
            if (empty($correspondingContens)) {
                $correspondingContens = '自動選定加盟店なし';
            }

            $saveData2['corresponding_contens'] = $correspondingContens;
            $this->correspondRepo->create($saveData2);
        }
    }
}
