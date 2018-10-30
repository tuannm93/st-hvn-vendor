@if ($results['CommissionInfo__commission_type'] != $div_value['package_estimate'])
    <div class="form-category__body clearfix">
        <h6 class="form-note font-weight-bold mt-0 d-none d-md-block">{!! trans('commission_detail.support') !!}</h6>

        <div id="accordion">
            <div class="card">
                <div class="card-header card-header--bg-1" data-toggle="collapse" data-target="#collapseOne"
                     aria-expanded="true" aria-controls="collapseOne" id="headingOne">
                    <h6 class="mb-0 font-weight-bold">{!! trans('commission_detail.tel_support') !!}</h6>
                    <span class="fa fa-chevron-down card-header__icon"></span>
                </div>
                 <div id="collapseOne" class="collapse multi-collapse bg-body-card-1">
                    <div class="fs-sm-14 p-0 p-sm-4">
                        <div class="head-info row mx-0 my-2">
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.contact_desired_time') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                                        <span class="fs-sm-18 font-weight-bold" id="contact_respond_bar">
                                            {{ $contact_desired_time ? $contact_desired_time : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.correspond_status') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                            <span class="fs-sm-18 font-weight-bold">
                            {{ (!isset($results['CommissionTelSupport']['correspond_status'])) ? '-' : getDivText('tel_correspond_status', $results['CommissionTelSupport']['correspond_status']) }}
                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.display_time') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                            <span class="fs-sm-18">
                            {{ !isset($m_commission_alert_settings_tel['correspond_status']) ? '-' : $m_commission_alert_settings_tel['display_time'] }}
                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                @if ($results['DemandInfo__selection_system'] == $div_value['auction_selection']
                                        || $results['DemandInfo__selection_system'] == $div_value['automatic_auction_selection'])
                                    <span class="text--orange">{!! trans('commission_detail.contact_datetime_text') !!}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mx-0">
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.tel_correspond_datetime') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        <input name="tel_correspond_datetime" type="text"
                                               id="tel_correspond_datetime" class="form-control datetimepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label text-md-right">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.situation') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        {{ Form::select('tel_correspond_status', $drop_list['tel_correspond_status'], null, ['id' => 'tel_correspond_status', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label text-md-right">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.responders') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        <input type="text" class="form-control" id="tel_responders"
                                               name="tel_responders" maxlength="40">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.fail_reason') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        {{ Form::select('tel_order_fail_reason', ['' => trans('common.none')] + $drop_list['tel_order_fail_reason'], null, ['id' => 'tel_order_fail_reason', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <label class="col-7 col-lg-6 col-form-label mb-0">{!! trans('commission_detail.visit_time_of_hope') !!}</label>
                                    <label class="col-5 col-lg-6 col-form-label mb-0">
                                        {{ $visit_time_of_hope ? $visit_time_of_hope : '-' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-0">
                            <label class="col-sm-3 col-lg-auto col-form-label">{!! trans('commission_detail.corresponding_contens') !!}</label>
                            <div class="col-sm-7 col-lg-6">
                                <input type="text" class="form-control mb-1 mb-sm-0"
                                       id="tel_corresponding_contens" name="tel_corresponding_contens"
                                       maxlength="20">
                            </div>
                            <div class="col-sm-2 d-flex align-items-center">
                                <a href="javascript:void(0)" class="link-primary text--underline"
                                   id="tel_support_sample">{!! trans('commission_detail.support_sample') !!}</a>
                            </div>
                            <div class="col-12">
                                <p class="commission__notice my-2">{!! trans('commission_detail.support_text_1') !!}</p>
                                <p class="commission__notice my-2 pl-3">{!! trans('commission_detail.support_text_2') !!}</p>
                                @if ((($results['CommissionInfo__lock_status'] == 1) and ($auth == 'affiliation')) ||
                                (($results['CommissionInfo__commission_status'] == 3) and ($auth == 'affiliation')) ||
                                (($results['CommissionInfo__progress_reported'] == 1) and ($auth == 'affiliation')))
                                @else
                                    <div class="text-center">
                                        <button type="button" id="regist_tel_supports_button"
                                                class="btn btn--gradient-green remove-effect-btn col-sm-3 col-xl-2  my-3">{{ trans('commission_detail.btn_register') }}</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-history-commission-detail">
                            <div class="history">
                                <div class="mb-2">
                                    <span class="border-left-orange font-weight-bold pl-2 ml-3 ml-sm-0">{!! trans('commission_detail.support_his') !!}</span>
                                </div>
                                <div id="table-container">
                                    <table class="table-list table-bordered border-0 table-fixed" id="table-1">
                                        <thead class="d-block">
                                        <tr class=>
                                            <th>{!! trans('commission_detail.support_time') !!}</th>
                                            <th>{!! trans('commission_detail.situation') !!}</th>
                                            <th>{!! trans('commission_detail.responders') !!}</th>
                                            <th>{!! trans('commission_detail.create_time') !!}</th>
                                            <th>{!! trans('commission_detail.text_entry_field') !!}</th>
                                        </tr>
                                        </thead>
                                        <tbody id="TelSupportsList" class="d-block">
                                        </tbody>
                                    </table>
                                    <table id="header-fixed"></table>
                                    <div id="bottom_anchor"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end card 1 -->

            <div class="card">
                <div class="card-header card-header--bg-2" data-toggle="collapse"
                     data-target="#collapseTwo" aria-expanded="true"
                     aria-controls="collapseTwo">
                    <h6 class="mb-0 font-weight-bold">{!! trans('commission_detail.visit_support') !!}</h6>
                    <span class="fa fa-chevron-down card-header__icon"></span>
                </div>
                <div id="collapseTwo" class="collapse multi-collapse bg-body-card-2">
                    <div class="fs-sm-14 p-0 p-sm-4">
                        <div class="head-info row mx-0 my-2">
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.visit_datetime') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                                        <span class="fs-sm-18 font-weight-bold" id="visit_respond_bar">
                                        {{ $visit_time_display }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.correspond_status') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                            <span class="fs-sm-18 font-weight-bold">
                            {{ !isset($results['CommissionVisitSupport']['correspond_status']) ? '-' : getDivText('visit_correspond_status', $results['CommissionVisitSupport']['correspond_status']) }}
                            </span>
                                    </div>
                                </div>
                            </div>
                            @if ($auth != 'affiliation')
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2">
                                        <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                            <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.display_time') !!}</span>
                                        </div>
                                        <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                            <span class="fs-sm-18">
                            {{ !isset($m_commission_alert_settings_visit['correspond_status']) ? '-' : $m_commission_alert_settings_visit['display_time'] }}
                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-center">
                                    @if ($results['DemandInfo__selection_system'] == $div_value['auction_selection']
                                            || $results['DemandInfo__selection_system'] == $div_value['automatic_auction_selection'])
                                        <span class="text--orange">{!! trans('commission_detail.support_text_3') !!}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="row mx-0">
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.visit_response_datetime') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        <input name="visit_correspond_datetime" type="text"
                                               id="visit_correspond_datetime"
                                               class="form-control datetimepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label text-md-right">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.situation') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        {{ Form::select('visit_correspond_status', $drop_list['visit_correspond_status'], null, ['id' => 'visit_correspond_status', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label text-md-right">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.responders') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        <input type="text" class="form-control" id="visit_responders"
                                               name="visit_responders" maxlength="40">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.fail_reason') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        {{ Form::select('visit_order_fail_reason', ['' => trans('common.none')] + $drop_list['visit_order_fail_reason'], null, ['id' => 'visit_order_fail_reason', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-0">
                            <label class="col-sm-3 col-lg-auto col-form-label">{!! trans('commission_detail.corresponding_contens') !!}</label>
                            <div class="col-sm-7 col-lg-6">
                                <input type="text" class="form-control d-inline-block"
                                       id="visit_corresponding_contens" name="visit_corresponding_contens"
                                       maxlength="20">
                            </div>
                            <div class="col-sm-2 d-flex align-items-center">
                                <a href="javascript:void(0)" class="link-primary text--underline"
                                   id="visit_support_sample">{!! trans('commission_detail.support_sample') !!}</a>
                            </div>
                            <div class="col-12">
                                <p class="commission__notice my-2">{!! trans('commission_detail.support_text_4') !!}</p>
                                @if ((($results['CommissionInfo__lock_status'] == 1) and ($auth == 'affiliation')) ||
                                    (($results['CommissionInfo__commission_status'] == 3) and ($auth == 'affiliation')) ||
                                    (($results['CommissionInfo__progress_reported'] == 1) and ($auth == 'affiliation')))
                                @else
                                    <div class="text-center">
                                        <button type="button" id="regist_visit_supports_button"
                                                class="btn btn--gradient-green remove-effect-btn col-sm-3 col-xl-2 my-3">{{ trans('commission_detail.btn_register') }}</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="history">
                            <div class="mb-2">
                                <span class="border-left-orange font-weight-bold pl-2 ml-3 ml-sm-0">{!! trans('commission_detail.support_his') !!}</span>
                            </div>
                            <div>
                                <table class="table-list table-bordered border-0 table-fixed">
                                    <thead class="d-block">
                                    <tr class="text-center bg-yellow-light">
                                        <th>{!! trans('commission_detail.support_time') !!}</th>
                                        <th>{!! trans('commission_detail.situation') !!}</th>
                                        <th>{!! trans('commission_detail.responders') !!}</th>
                                        <th>{!! trans('commission_detail.create_time') !!}</th>
                                        <th>{!! trans('commission_detail.text_entry_field') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="VisitSupportsList" class="d-block">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end card 2 -->

            <div class="card">
                <div class="card-header card-header--bg-3" data-toggle="collapse"
                     data-target="#collapseThree" aria-expanded="true"
                     aria-controls="collapseThree">
                    <h6 class="mb-0 font-weight-bold">{!! trans('commission_detail.order_support') !!}</h6>
                    <span class="fa fa-chevron-down card-header__icon"></span>
                </div>
                <div id="collapseThree" class="collapse multi-collapse bg-body-card-3">
                    <div class="fs-sm-14 p-0 p-sm-4">
                        <div class="head-info row mx-0 my-2">
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.order_datetime') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                                        <span class="fs-sm-18 font-weight-bold" id="order_respond_bar">
                                        {{ strlen($results['CommissionInfo__order_respond_datetime']) == 0 ? '-' : $results['CommissionInfo__order_respond_datetime'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.correspond_status') !!}</span>
                                    </div>
                                    <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                            <span class="fs-sm-18 font-weight-bold">
                            {{ !isset($results['CommissionOrderSupport']['correspond_status']) ? '-' : getDivText('order_correspond_status', $results['CommissionOrderSupport']['correspond_status']) }}
                            </span>
                                    </div>
                                </div>
                            </div>
                            @if ($auth != 'affiliation')
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2">
                                        <div class="col-6 col-sm-4 col-lg-auto d-flex align-items-center pr-0 pr-md-3">
                                            <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.display_time') !!}</span>
                                        </div>
                                        <div class="col-6 col-sm-8 col-lg-8 d-flex align-items-center px-0 px-sm-3">
                                        <span class="fs-sm-18">
                                        {{ !isset($m_commission_alert_settings_order['correspond_status']) ? '-' : $m_commission_alert_settings_order['display_time'] }}
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row mx-0">
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.order_datetime_label') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        <input name="order_correspond_datetime" type="text"
                                               id="order_correspond_datetime"
                                               class="form-control datetimepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label text-md-right">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.situation') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        {{ Form::select('order_correspond_status', $drop_list['order_correspond_status'], null, ['id' => 'order_correspond_status', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label text-md-right">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.responders') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        <input type="text" class="form-control" id="order_responders"
                                               name="order_responders" maxlength="40">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 px-0">
                                <div class="row mx-0 mb-2">
                                    <div class="col-sm-4 col-lg-5 col-form-label">
                                        <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.fail_reason') !!}</span>
                                    </div>
                                    <div class="col-sm-8 col-lg-7">
                                        {{ Form::select('order_order_fail_reason', ['' => trans('common.none')] + $drop_list['order_order_fail_reason'], null,
                                            ['id' => 'order_order_fail_reason', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mx-0">
                            <label class="col-sm-4 col-lg-auto col-form-label">{!! trans('commission_detail.corresponding_contens') !!}</label>
                            <div class="col-sm-8 col-lg-6">
                                <input type="text" class="form-control d-inline-block"
                                       id="order_corresponding_contens" name="order_corresponding_contens"
                                       maxlength="20">
                            </div>
                            <div class="col-sm-2 offset-sm-4 offset-md-0 d-flex align-items-center">
                                <a href="javascript:void(0)" class="link-primary text--underline"
                                   id="order_support_sample">{!! trans('commission_detail.support_sample') !!}</a>
                            </div>
                            <div class="col-12">
                                <p class="commission__notice my-2">{!! trans('commission_detail.support_text_4') !!}</p>
                                @if ((($results['CommissionInfo__lock_status'] == 1) and ($auth == 'affiliation')) ||
                                    (($results['CommissionInfo__commission_status'] == 3) and ($auth == 'affiliation')) ||
                                    (($results['CommissionInfo__progress_reported'] == 1) and ($auth == 'affiliation')))
                                @else
                                    <div class="text-center">
                                        <button type="button" id="regist_order_supports_button"
                                                class="btn btn--gradient-green remove-effect-btn col-sm-3 col-xl-2 my-3">{{ trans('commission_detail.btn_register') }}</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="history">
                            <div class="mb-2">
                                <span class="border-left-orange font-weight-bold pl-2 ml-3 ml-sm-0">{!! trans('commission_detail.support_his') !!}</span>
                            </div>
                            <div>
                                <table class="table-list table-bordered border-0 table-fixed">
                                    <thead class="d-block">
                                    <tr>
                                        <th>{!! trans('commission_detail.support_time') !!}</th>
                                        <th>{!! trans('commission_detail.situation') !!}</th>
                                        <th>{!! trans('commission_detail.responders') !!}</th>
                                        <th>{!! trans('commission_detail.create_time') !!}</th>
                                        <th>{!! trans('commission_detail.text_entry_field') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="OrderSupportsList" class="d-block">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
