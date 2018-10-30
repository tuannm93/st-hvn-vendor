<div class="auction-detail">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <h3>入札状況</h3>
                    <table class="table table-bordered list_tbl_ext table-list" border="1">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:15%;">{{ trans('auction_detail.company_name') }}</th>
                            <th class="text-center"
                                style="width:15%;">{{ trans('auction_detail.proposal_confirmation_date_and_time') }}</th>
                            <th class="text-center" style="width:15%;">{{ trans('auction_detail.respondent') }}</th>
                            <th class="text-center"
                                style="width:15%;">{{ trans('auction_detail.scheduled_visit_date') }}</th>
                            <th class="text-center" style="width:15%;">{{ trans('auction_detail.time_to_send') }}</th>
                            <th class="text-center" style="width:7%;">{{ trans('auction_detail.sent') }}</th>
                            <th class="text-center" style="width:18%;">{{ trans('auction_detail.decline_reason') }}</th>
                        </tr>
                        </thead>
                        @if(isset($results) && count($results) > 0)
                            @foreach($results as $result)
                                <tr>
                                    <td class="text-left">{{ $result->corp_name }}</td>
                                    <td class="text-center">{{ empty($result->first_display_time) ? '' : dateTimeFormat($result->first_display_time) }}</td>
                                    <td>{{ $result->responders }}</td>
                                    <td class="text-center">{{ empty($result->visit_time) ? '' : dateTimeFormat($result->visit_time) }}</td>
                                    <td class="text-center">{{ empty($result->push_time) ? '' : dateTimeFormat($result->push_time) }}</td>
                                    <td class="text-left">{{ ($result->push_flg == 1) ? trans('auction_detail.done') : trans('auction_detail.not_yet') }}</td>
                                    <td>
                                        @if(!empty($result->refusal_flg))
                                            {{ trans('auction_detail.decline') }}<br>
                                            @if(!empty($result->corresponds_time1) || !empty($result->corresponds_time2) || !empty($result->corresponds_time3))
                                                {{ trans('auction_detail.correspondence_time_is_incompatible') }}<br>
                                                {!! empty($result->corresponds_time1) ? '' : dateTimeFormat($result->corresponds_time1).'<br>' !!}
                                                {!! empty($result->corresponds_time2) ? '' : dateTimeFormat($result->corresponds_time2).'<br>' !!}
                                                {!! empty($result->corresponds_time3) ? '' : dateTimeFormat($result->corresponds_time3).'<br>' !!}
                                            @endif
                                            @if(!empty($result->cost_from) || !empty($result->cost_to))
                                                {{ trans('auction_detail.price_does_not_match') }}<br>
                                                {{ yenFormat2($result->cost_from) }}{{trans('common.wavy_seal')}}
                                                {{ yenFormat2($result->cost_to) }}<br>
                                            @endif
                                            @if(!empty($result->not_available_flg))
                                                {{ trans('auction_detail.unacceptable_item') }}<br>
                                            @endif
                                            @if(!empty($result->estimable_time_from))
                                                {{ trans('auction_detail.estimation_schedule_is_not_supported') }}<br>
                                                {{ dateTimeFormat($result->estimable_time_from) }} {{trans('common.wavy_seal')}} <br>
                                            @endif
                                            @if(!empty($result->contactable_time_from))
                                                {{ trans('auction_detail.contact_time_is_not_available') }}<br>
                                                {{ dateTimeFormat($result->contactable_time_from) }} {{trans('common.wavy_seal')}} <br>
                                            @endif
                                            @if(!empty($result->other_contens))
                                                {{ trans('auction_detail.other_unavailable_reasons') }}<br>
                                                {{ nl2br($result->other_contens) }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

