<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Class Approval
 * @package App\Models
 */
class Approval extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approvals';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var boolean
     */
    public $timestamps = false;

    //define default format csv

    /**
     * @return array
     */
    public static function csvFormat()
    {
        return [
            'default' => [
                'id' => trans('application_answer.application_number'),
                'custom__application_section' => trans('application_answer.application_classification'),
                'commission_id' => trans('application_answer.agency_id'),
                'demand_id' => trans('application_answer.proposal_id'),
                'corp_id' => trans('application_answer.company_id'),
                'official_corp_name' => trans('application_answer.eligible_merchant'),
                'application_user_id' => trans('application_answer.applicant'),
                'application_datetime' => trans('application_answer.application_date_and_time'),
                'application_reason' => trans('application_answer.application_reason'),
                'deduction_tax_include' => trans('application_answer.deductible_amount'),
                'irregular_fee_rate' => trans('application_answer.irregular_commission_rate'),
                'irregular_fee' => trans('application_answer.irregular_fee_amount'),
                'custom__irregular_reason' => trans('application_answer.irregular_reason'),
                'custom__introduction_free' => trans('application_answer.introduction_free'),
                'custom__ac_commission_exclusion_flg' => trans('application_answer.bidding_fee'),
                'custom__status' => trans('application_answer.possibility'),
                'approval_user_id' => trans('application_answer.approver'),
                'approval_datetime' => trans('application_answer.approval_date_and_time'),
            ]
        ];
    }
}
