<?php

namespace App\Services;

use App\Repositories\DemandInquiryAnsRepositoryInterface;
use Illuminate\Support\Facades\Log;

class DemandInquiryAnswerService
{

    // repository
    /**
     * @var DemandInquiryAnsRepositoryInterface
     */
    protected $demandInquiryAnswerRepo;

    /**
     * constructor
     *
     * @param DemandInquiryAnsRepositoryInterface $demandInquiryAnswerRepo
     */
    public function __construct(
        DemandInquiryAnsRepositoryInterface $demandInquiryAnswerRepo
    ) {

        $this->demandInquiryAnswerRepo = $demandInquiryAnswerRepo;
    }

    /**
     * @param $data
     * @return boolean
     */
    public function updateDemandInquiryAnswer($data)
    {
        Log::debug('___ Start insert demand inquiry ___');
        // If the item-specific hearing item is not entered, nothing is done
        if (!array_key_exists('demandInquiryAnswer', $data)) {
            Log::debug('___ empty demandInquiryAnswer ___');
            return true;
        }

        // Retrieve deal ID
        $demandId = (array_key_exists('id', $data['demandInfo'])) ? $data['demandInfo']['id'] : null;

        // Registration of destination information
        $saveData = [];
        foreach ($data['demandInquiryAnswer'] as $key => $val) {
            // Judge whether registration is necessary on the basis of presence or absence of a hearing item ID
            if (!empty($val['inquiry_id'])) {
                // Confirm existence of registered data with item ID and interview item ID
                $currentData = $this->demandInquiryAnswerRepo->getDemandAnswerByDemandIdAndInquiryId(
                    $demandId,
                    $val['inquiry_id']
                );
                // Update or registration judgment
                if ($currentData) {
                    // When updating
                    $saveData[$key]['id'] = $currentData['id'];
                    $saveData[$key]['answer_note'] = $val['answer_note'];
                } else {
                    // In case of new registration
                    $saveData[$key]['demand_id'] = $demandId;
                    $saveData[$key]['inquiry_id'] = $val['inquiry_id'];
                    $saveData[$key]['answer_note'] = $val['answer_note'];
                }
            }
        }

        if (!empty($saveData)) {
            Log::debug('___ process insert demandInquiryAnswerRepo ___');
            return $this->demandInquiryAnswerRepo->multipleUpdate($saveData);
        }
        Log::debug('___ empty data to insert demandInquiryAnswerRepo ___');
    }
}
