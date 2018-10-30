<div class="modal modal-global" tabindex="-1" role="dialog" id="site_launch_details_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header p-1">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                <h4 class="modal-title text-center mb-2">{!! trans('commission_detail.site_launch_details_dialog') !!}</h4>
                <p>{!! $site_list['note'] !!}</p>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-gray mb-3 col-2" id="site_launch_details_close">{!! trans('commission_detail.btn_close') !!}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-global" tabindex="-1" role="dialog" id="tel_support_sample_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                {!! trans('commission_detail.tel_support_sample_dialog') !!}
            </div>
        </div>
    </div>
</div>
<div class="modal modal-global" tabindex="-1" role="dialog" id="visit_support_sample_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                {!! trans('commission_detail.visit_support_sample_dialog') !!}
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global" tabindex="-1" role="dialog" id="order_support_sample_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
               {!! trans('commission_detail.order_support_sample_dialog') !!}
            </div>
        </div>
    </div>
</div>
<div class="modal modal-global" tabindex="-1" role="dialog" id="error_check_dialog">
    <div class="modal-dialog modal-lg commission-detail-modal">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5 text-center fs-15 fs-sm-20" id="display_modal_area">
                <h4 class="modal-title text-danger">{!! trans('commission_detail.error_check_dialog') !!}</h4>
                <p id="error_message" class="text-danger"></p>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-gray remove-effect-btn fix-w-200  my-3" id="error_check_close">{!! trans('commission_detail.btn_close') !!}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-global" tabindex="-1" role="dialog" id="visit_hope_date_dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-0 pt-1 px-2">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0 px-5" id="display_modal_area">
                <p id="visit_hope_error_message" class="box__mess box--error mb-0 mt-2"></p>
                <p class="text-center my-2">{!! trans('commission_detail.visit_hope_date_dialog') !!}</p>
                <div class="row justify-content-center">
                    <div class="col-8 col-sm-5 col-md-5">
                        <div class="form-group mb-2">
                            <input name="visit_hope_datetime" id="visit_hope_datetime" type="text" class="form-control datetimepicker">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-green remove-effect-btn fix-w-200  my-3" id="visit_hope_date_ok">{{ trans('commission_detail.btn_register') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global" tabindex="-1" role="dialog" id="order_support_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                <p id="order_support_error_message" class="box__mess box--error"></p>
                <p>{!! trans('commission_detail.order_support_dialog') !!}</p>
                <div class="col-12 col-sm-3">
                    <div class="form-group mb-2">
                        <input name="order_support_datetime" id="order_support_datetime" type="text" class="form-control datetimepicker">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-green remove-effect-btn fix-w-200  my-3" id="order_support_ok">{{ trans('commission_detail.btn_register') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global" tabindex="-1" role="dialog" id="error_check_dialog_1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                <h4 class="modal-title">{!! trans('commission_detail.error_check_dialog_1') !!}</h4>
                <p id="error_message_1" class="box__mess box--error"></p>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-green remove-effect-btn fix-w-200  my-3" id="error_message_1_close_btn">{!! trans('commission_detail.btn_close') !!}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global" tabindex="-1" role="dialog" id="error_check_dialog_2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                <h4 class="modal-title"></h4>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-green remove-effect-btn fix-w-200  my-3">{!! trans('commission_detail.btn_close') !!}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-global" tabindex="-1" role="dialog" id="order_completion_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">&nbsp;</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">
                <h4 class="modal-title"></h4>
                <p id="order_completion_error_message" class="box__mess box--error"></p>
                <p class="text-center" >{!! trans('commission_detail.order_completion_dialog') !!}</p>
                @if ($auth != 'affiliation')
                    {{Form::input('hidden', 'order_first_commission', isset($results['CommissionInfo__first_commission']) ? $results['CommissionInfo__first_commission'] : '0', ['id' => 'first_commission'])}}
                    {{Form::input('hidden', 'order_unit_price_calc_exclude', isset($results['CommissionInfo__unit_price_calc_exclude']) ? $results['CommissionInfo__unit_price_calc_exclude'] : '0', ['id' => 'order_unit_price_calc_exclude'])}}
                @endif
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formGroupExampleInput">{!! trans('commission_detail.order_completion_dialog_1') !!}</label>
                                @php
                                    $complete_date_class = ($auth == 'accounting_admin' || $auth == 'accounting' || $auth == 'system') ? 'datepicker' : 'datepicker_limit';
                                    $complete_date_disabled = ($results['CommissionInfo__commission_type'] == $div_value['package_estimate'] || ($auth == 'affiliation' && $results['CommissionInfo__commission_status'] == $div_value['construction'])) ? 'disabled="disabled"' : '';
                                @endphp
                                <div>
                                    <div class="form-group mb-2">
                                        <input name="order_completion_datetime" id="order_completion_datetime" class="{{$complete_date_class}} form-control" type="text" {{ $complete_date_disabled }} >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formGroupExampleInput">{!! trans('commission_detail.construction_price_tax_exclude') !!}</label>
                                @if ($results['BillInfo__bill_status'] == $div_value['payment'])
                                    @if (($auth == 'admin') || ($auth == 'system'))
                                        <div>
                                            <div class="input-group mb-2">
                                                <input name="order_construction_price_tax_exclude" id="order_construction_price_tax_exclude" onchange="//exclude_tax_rate();" type="text" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">{!! trans('common.yen') !!}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span id='order_construction_price_tax_exclude'></span>
                                    @endif
                                @else
                                    @if ($auth == 'affiliation' && $results['CommissionInfo__commission_status'] == $div_value['construction'])
                                        <span id='order_construction_price_tax_exclude'></span>
                                    @else
                                        <div>
                                            <div class="input-group mb-2">
                                                <input name="order_construction_price_tax_exclude" id="order_construction_price_tax_exclude" onchange="//exclude_tax_rate();" type="text" class="form-control">
                                                <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2">{!! trans('common.yen') !!}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formGroupExampleInput" class="w-100">{!! trans('commission_detail.construction_price_tax_include') !!}</label>
                                @if ($auth == 'affiliation')
                                    <div class="mt-2">
                                        <span id='order_construction_price_tax_include_display'></span>
                                        <input name="order_construction_price_tax_include" id="order_construction_price_tax_include" type="hidden" />{!! trans('common.yen') !!}
                                    </div>
                                @else
                                    <div>
                                        <div class="input-group mb-2">
                                            <input name="order_construction_price_tax_include" id="order_construction_price_tax_include" type="text" class="form-control">
                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">{!! trans('common.yen') !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-green remove-effect-btn fix-w-200  my-3" id="order_completion_ok">{{ trans('commission_detail.btn_register') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-global" tabindex="-1" role="dialog" id="history_input_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">&nbsp;</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="display_history_input">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="progress_reported_check" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                @lang('commission_detail.confirm_mess')
            </div>
            <div class="modal-footer">
                <button id="progress_reported_check_cancel" type="button" class="btn btn--gradient-gray">@lang('common.cancel')</button>
                <button id="progress_reported_check_ok" type="button" class="btn btn--gradient-green" >@lang('common.ok')</button>
            </div>
        </div>
    </div>
</div>

