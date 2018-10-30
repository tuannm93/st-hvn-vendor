@inject('service', 'App\Services\PMUpdateConfirmService')
@extends('layouts.app')

@section('content')
<div class="edit-progressment">
    <div class="header-title">
            <h1>{{$officialCorpName}}@lang('pm_update_confirm.input_confirmation')</h1>
        </div>
    <div class="main-title">
        @lang('pm_update_confirm.number_of_cases')：{{ count($progDemandInfo) }}
        @lang('pm_update_confirm.cases')
    </div>
    {!! Form::open(['url' => route('progress_management.update_confirm', ['progImportFileId' => $progImportFileId])]) !!}
    <input type="hidden" name="ProgImportFile[file_id]" value="{{ !empty($progImportFile->prog_import_file_id) ? $progImportFile->prog_import_file_id : '' }}">
    <input type="hidden" name="ProgDemandInfoOther[add_flg]" value="{{ isset($progImportFile->add_flg) ? $progImportFile->add_flg : '' }}">
    <input type="hidden" name="ProgDemandInfoOther[agree_flag]" value="{{ !empty($progImportFile->agree_flag) ? $progImportFile->agree_flag : '' }}">
    <div class="table-responsive">
        <table class="table commonTbl">
            <tbody>
                <tr>
                    <th class="align-middle text-center">@lang('pm_update_confirm.received_date_and_time')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.proposal_number')<br>@lang('pm_update_confirm.customer_name')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.content')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.commission_rate')<br>@lang('pm_update_confirm.commission_amount')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.construction_completion_date')<br>@lang('pm_update_confirm.lost_day')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.construction_amount')<br>@lang('pm_update_confirm.commissionable_amount_of_money')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.our_company')<br>@lang('pm_update_confirm.management_status')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.in_our_management_situation')<br>@lang('pm_update_confirm.is_there_no_change')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.status_after_change')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.construction_completion_date')<br>@lang('pm_update_confirm.lost_day')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.construction_amount')<br>@lang('pm_update_confirm.construction_amount_tax_included')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.reason_for_lost')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.remarks_column')</th>
                </tr>
                @foreach($progDemandInfo as $key => $item)
                <tr class="formRow">
                    <td class="text-center">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][id]" value="{{ $item->prog_demand_info_id }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][agree_flag]" value="{{ !empty($progImportFile->agree_flag) ? $progImportFile->agree_flag : '' }}">

                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][demand_id]" value="{{ $item->demand_id }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][commission_id]" value="{{ $item->commission_id }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][receive_datetime]" value="{{ $item->receive_datetime }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][customer_name]" value="{{ $item->customer_name }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][category_name]" value="{{ $item->category_name }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][complete_date]" value="{{ $item->complete_date }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][construction_price_tax_exclude]" value="{{ $item->construction_price_tax_exclude }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][construction_price_tax_include]" value="{{ $item->construction_price_tax_include }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][fee_target_price]" value="{{ $item->fee_target_price }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][commission_status]" value="{{ $item->commission_status }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][diff_flg]" value="{{ $item->diff_flg }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][commission_status_update]" value="{{ $item->commission_status_update }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][complete_date_update]" value="{{ $item->complete_date_update }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][fee]" value="{{ $item->fee }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][fee_rate]" value="{{ $item->fee_rate }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][construction_price_tax_exclude_update]" value="{{ $item->construction_price_tax_exclude_update }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][construction_price_tax_include_update]" value="{{ (int)$item->construction_price_tax_exclude_update * 1.08 }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][commission_order_fail_reason_update]" value="{{ $item->commission_order_fail_reason_update }}">
                        <input type="hidden" name="ProgDemandInfo[{{ $key }}][comment_update]" value="{{ $item->comment_update }}">

                        {{ date_time_format($item->receive_datetime, 'Y/m/d') }}
                        <br>
                        {{ date_time_format($item->receive_datetime, 'h:i') }}
                    </td>
                    <td class="text-left">
                        {{ $item->demand_id }}<br>
                        {{ $item->customer_name }}
                    </td>
                    <td class="text-left">{{ $item->category_name }}</td>
                    <td class="text-right">
                        @if (!empty($item->fee))
                        {{ yenFormat2($item->fee) }}
                        @elseif (!empty($item->fee_rate))
                        {{ $item->fee_rate }} %
                        @endif
                    </td>
                    <td class="text-center">{{ !empty($item->complete_date) ? date_time_format($item->complete_date, 'Y/m/d') : '' }}</td>
                    <td class="text-right">
                        @if (!empty($item->construction_price_tax_exclude))
                            {{ yenFormat2($item->construction_price_tax_exclude) }}
                        @else
                            -@lang('pm_update_confirm.yen')
                        @endif
                        <br>
                        @if (!empty($item->construction_price_tax_include))
                            {{ yenFormat2($item->construction_price_tax_include) }}
                        @else
                            -@lang('pm_update_confirm.yen')
                        @endif
                        <br>
                        @if (!empty($item->fee_target_price))
                            {{ yenFormat2($item->fee_target_price) }}
                        @else
                            @lang('pm_update_confirm.yen')
                        @endif
                        <br>
                    </td>
                    <td class="text-left">{{ !empty($item->commission_status) ? $pmCommissionStatus[$item->commission_status] : '' }}</td>
                    <td class="text-left">{{ !empty($item->diff_flg) ? $diffFllags[$item->diff_flg] : '' }}</td>
                    <td class="text-left">{{ !empty($item->commission_status_update) ? $pmCommissionStatus[$item->commission_status_update] : '' }}</td>
                    <td class="text-center">{{ !empty($item->complete_date_update) ? date_time_format($item->complete_date_update, 'Y/m/d') : '' }}</td>
                    <td class="text-right">
                        @if (!empty($item->construction_price_tax_exclude_update))
                            {{ yenFormat2($item->construction_price_tax_exclude_update) }}
                        @else
                            -@lang('pm_update_confirm.yen')
                        @endif
                        <br>
                        @if (!empty($item->construction_price_tax_exclude_update))
                            {{ yenFormat2((int)$item->construction_price_tax_exclude_update * 1.08) }}
                        @else
                            -@lang('pm_update_confirm.yen')
                        @endif
                        <br>
                    </td>
                    <td class="text-left">{{ !empty($item->commission_order_fail_reason_update) ? $reasonList[$item->commission_order_fail_reason_update] : '' }}</td>
                    <td class="text-right">{{ !empty($item->comment_update) ? $item->comment_update : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@if (isset($progImportFile->add_flg) && $progImportFile->add_flg == 1) 
    <div class="sub-title">
        <p class="mt-2"> @lang('pm_update_confirm.confirm_additional_items')</p>
    </div>
    <div class="table-responsive">
        <table class="table common-table-second">
            <tbody>
                <tr class="sub-tr">
                    <th class="align-middle text-center">@lang('pm_update_confirm.proposal_number')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.customer_name')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.content')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.status')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.construction_completion_date')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.construction_amount_tax_not_included')<br>@lang('pm_update_confirm.construction_amount_tax_included')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.opportunity_attribute')</th>
                    <th class="align-middle text-center">@lang('pm_update_confirm.remarks_column')</th>
                </tr>
                @if (count($progAddDemandInfo))
                    @php
                        $index = 0;
                    @endphp
                    @foreach($progAddDemandInfo as $item)
                        @if ($service->addValidate($item->toArray()))
                        <tr class="formRow">
                            <td>
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][sequence]" value="{{ $item->sequence }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][agree_flag]" value="{{ !empty($progImportFile->agree_flag) ? $progImportFile->agree_flag : '' }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][id]" value="{{ $item->id }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][display]" value="{{ $item->display }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][demand_id_update]" value="{{ $item->demand_id_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][customer_name_update]" value="{{ $item->customer_name_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][category_name_update]" value="{{ $item->category_name_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][commission_status_update]" value="{{ $item->commission_status_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][complete_date_update]" value="{{ $item->complete_date_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][construction_price_tax_exclude_update]" value="{{ $item->construction_price_tax_exclude_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][comment_update]" value="{{ $item->comment_update }}">
                                <input type="hidden" name="ProgAddDemandInfo[{{ $index }}][demand_type_update]" value="{{ $item->demand_type_update }}">
                                {{ $item->demand_id_update }}
                                @php
                                    $index++;
                                @endphp
                            </td>
                            <td>{{ $item->customer_name_update }}</td>
                            <td>{{ $item->category_name_update }}</td>
                            <td>
                                {{ !empty($item->commission_status_update) ? $pmCommissionStatus[$item->commission_status_update] : '' }}
                            </td>

                            <td>
                                {{ !empty($item->complete_date_update) ? date_time_format($item->complete_date_update, 'Y/m/d') : ''}}
                            </td>
                            <td>
                                @if (!empty($item->construction_price_tax_exclude_update))
                                {{ yenFormat2($item->construction_price_tax_exclude_update) }}
                                <br>
                                {{ yenFormat2((int)$item->construction_price_tax_exclude_update * 1.08) }}
                                <br>
                                @endif
                            </td>
                            <td>{{ !empty($item->demand_type_update) ? $demandTypeList[$item->demand_type_update] : '' }}</td>
                            <td>{{ $item->comment_update }}</td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
 @endif 
    <div class="d-flex justify-content-center">
        <div class="border-box p-2 p-sm-5">
            <p class="d-flex justify-content-center notice">      
                @lang('pm_update_confirm.information_1')<br>     
                @lang('pm_update_confirm.information_2')<br>
                @lang('pm_update_confirm.information_3')
            </p>
            <div class="text-center">
                <button class="btn btn-sm btn--gradient-gray col-sm-4 mb-1" type="button" id="back" data-url="{{ route('get.progress_management.demand_detail', ['progImportFileId' => !empty($progImportFile->prog_import_file_id) ? $progImportFile->prog_import_file_id : '']) }}">&#60;&#60; @lang('pm_update_confirm.correct_item_information')</button>
                <button class="btn btn-sm btn--gradient-green col-sm-4 mb-1" type="submit">@lang('pm_update_confirm.send_item_information') &#62;&#62</button>
            </div>
        </div>
    </div>
    {!!Form::close() !!}
</div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/update_confirm.js') }}"></script>
@endsection
