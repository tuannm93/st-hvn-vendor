<p class="pt-3 mb-0">
    @if(isset($results) && count($results) > 0)
        {{ trans('report_auction_fall.the_total_number_of_cases').$results->total().trans('report_auction_fall.matter') }}
    @else
        {{ trans('report_auction_fall.the_total_number_of_cases').'0'.trans('report_auction_fall.matter') }}
    @endif
</p>
<table class="table table-bordered table-app-answer">
    <thead>
    <tr>
        <th class="w-7">{{ trans('application_answer.application_number') }}</th>
        <th class="w-7">{{ trans('application_answer.application_classification') }}</th>
        <th class="w-20">{{ trans('application_answer.eligible_merchant') }}</th>
        <th class="w-10">{{ trans('application_answer.applicant') }}</th>
        <th class="w-20">{{ trans('application_answer.application_date_and_time') }}</th>
        <th rowspan="2" class="w-20">{{ trans('application_answer.application_reason') }}</th>
        <th rowspan="2" class="w-5">{{ trans('application_answer.possibility') }}</th>
    </tr>
    <tr>
        <th colspan="5">{{ trans('application_answer.application_content') }}</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($results) && count($results) > 0)
        @foreach($results as $result)
            <tr>
                <td class="text-center">
                    <a class="text--orange" href="{{ route('commission.detail', [$result->commission_id, '#app']) }}" target="_blank">{{ $result->id }}</a>
                </td>
                <td>
                    @if($result->application_section == 'CommissionApplication')
                        {{ trans('application_answer.manager_administration') }}
                    @endif
                </td>
                <td>
                    <a class="text--orange" href="{{ route('affiliation.detail.edit', $result->corp_id) }}" target="_blank">{{ $result->official_corp_name }}</a>
                </td>
                <td>{{ $result->application_user_id }}</td>
                <td class="text-center">
                    <p class="mb-0">{{ $result->application_datetime }}</p>
                </td>
                <td rowspan="2">
                    {{ $result->application_reason }}
                </td>
                <td rowspan="2" class="text-center
                                        @if($result->status == -1 || $result->status == -2)
                        apply
@endif
                @if($result->status == 1)
                        accep
@endif
                @if($result->status == 2)
                        dismissal
@endif
                        ">
                    @php
                        $status = getDropList($application);
                        if (isset($status[$result->status])){
                          echo $status[$result->status];
                        }
                    @endphp
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    @php
                        $text = '';
                        if($result->chg_deduction_tax_include){
                            $text .= trans('application_answer.deductible_amount').'：';
                            if(!empty($result->deduction_tax_include))$text .= yenFormat2($result->deduction_tax_include).', ';
                            else $text .= trans('application_answer.empty_string').', ';
                        }
                        if($result->chg_irregular_fee_rate){
                            $text .= trans('application_answer.irregular_commission_rate').'：';
                            if(!empty($result->irregular_fee_rate))$text .= $result->irregular_fee_rate.'%, ';
                            else $text .= trans('application_answer.empty_string').', ';
                        }
                        if($result->chg_irregular_fee){
                            $text .= trans('application_answer.irregular_fee_amount').'：';
                            if(!empty($result->irregular_fee))$text .= yenFormat2($result->irregular_fee).', ';
                            else $text .= trans('application_answer.empty_string').', ';
                        }
                        if($result->chg_irregular_fee_rate || $result->chg_irregular_fee){
                            $text .= trans('application_answer.irregular_reason').'：';

                            $ir =  getDropList($irregularReason);
                            if(isset($result->irregular_reason)){
                                if($result->irregular_reason == 0) $text .= trans('application_answer.empty_string');
                                else $text .= $ir[$result->irregular_reason];
                            }else $text .= trans('application_answer.empty_string');

                            $text .= ', ';
                        }
                        if($result->chg_introduction_free){
                            if($result->introduction_free == 0)
                                $text .= trans('application_answer.referral_free_invalid');
                            elseif($result->introduction_free == 1)
                                $text .= trans('application_answer.introduction_free_valid');
                        }
                        if($result->chg_ac_commission_exclusion_flg){
                            if($result->ac_commission_exclusion_flg == 0)
                                $text .= trans('application_answer.bidding_fee_do_not_exclude');
                            elseif($result->ac_commission_exclusion_flg == 1)
                                $text .= trans('application_answer.bidding_fee_exclude');
                        }
                        if(!empty($text))echo substr($text, 0, -2);
                        else echo "&nbsp;";
                    @endphp
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
{{ $results->links('pagination.nextprevajax') }}