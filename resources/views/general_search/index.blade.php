@extends('layouts.app')

@section('content')
    <div class="general-search-index">
        @if (Session::has('compMessage'))
            <div class="box__mess box--success">{{ Session::get('compMessage') }}</div>
        @endif
        <div class="box__mess box--error"></div>
        {{ Form::open(['enctype' => 'multipart/form-data', 'accept-charset'=>"UTF-8", 'id'=>'general_search_form', 'class'=>'fieldset-custom']) }}
        <fieldset class="form-group">
            {{ Form::input('hidden', 'data[MGeneralSearch][id]', isset($dataResults['MGeneralSearch']['id']) ? $dataResults['MGeneralSearch']['id'] : null, []) }}
            <legend class="col-form-label fs-13">{{ trans('general_search.setting_reading_saving') }}</legend>
            <div class="box--bg-gray box--border-gray row mx-0 p-2">
                <div class="col-md-8 col-xl-6 px-0 mb-2 mb-md-0">
                    <div class="row">
                        <div class="col-sm-3 col-lg-2 mb-2 mb-md-0">
                            <button id="search" type="button" class="btn btn--gradient-gray border--btn-gray" data-url="{{ route('ajax.searchmgeneralsearch') }}">{{ trans('general_search.setting_name') }}</button>
                        </div>
                        <div class="col-sm-9">
                            {{ Form::input('text', 'data[MGeneralSearch][definition_name]', isset($dataResults['MGeneralSearch']['definition_name']) ? $dataResults['MGeneralSearch']['definition_name'] : null, ['id' => 'definition_name', 'class'=>'form-control', 'data-rule-required'=>'true', 'maxlength' => '50']) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-6 px-0">
                    @if($permissionSaveDel)
                        <button type="button" id="regist-confirm" name="regist" formaction="{{ route('general_search.regist') }}" class="btn btn--gradient-green mx-md-2">{{ trans('general_search.save') }}</button>
                        <button type="button" id="delete-confirm" name="delete" formaction="{{ route('general_search.delete') }}" class="btn btn--gradient-gray border--btn-gray">{{ trans('general_search.delete') }}</button>
                    @endif
                </div>
                @if($permissionSaveDel)
                    <div class="col-md-2 col-xl-1 px-0">
                        <label class="col-form-label mb-0">{{ trans('general_search.scope_of_disclosure') }}</label>
                    </div>
                    <div class="col-md-10 col-xl-11 d-flex flex-column flex-sm-row px-0 align-items-sm-center">
                        <div class="custom-checkbox custom-control mr-3">
                            {{ Form::input('checkbox', 'data[MGeneralSearch][auth_popular]', 1, ['id' => 'auth_popular', 'class'=>'custom-control-input ignore', (isset($dataResults['MGeneralSearch']['auth_popular']) && !empty($dataResults) && $dataResults['MGeneralSearch']['auth_popular'] == 1) ? 'checked' : '']) }}
                            <label class="custom-control-label" for="auth_popular">{{ trans('general_search.general') }}</label>
                        </div>
                        <div class="custom-checkbox custom-control mr-3">
                            {{ Form::input('checkbox', 'data[MGeneralSearch][auth_admin]', 1, ['id' => 'auth_admin', 'class'=>'custom-control-input ignore', (isset($dataResults['MGeneralSearch']['auth_admin']) && !empty($dataResults) && $dataResults['MGeneralSearch']['auth_admin'] == 1) ? 'checked' : '']) }}
                            <label class="custom-control-label" for="auth_admin">{{ trans('general_search.administrator') }}</label>
                        </div>
                        <div class="custom-checkbox custom-control mr-3">
                            {{ Form::input('checkbox', 'data[MGeneralSearch][auth_accounting_admin]', 1, ['id' => 'auth_accounting_admin', 'class'=>'custom-control-input ignore', (isset($dataResults['MGeneralSearch']['auth_accounting_admin']) && !empty($dataResults) && $dataResults['MGeneralSearch']['auth_accounting_admin'] == 1) ? 'checked' : '']) }}
                            <label class="custom-control-label" for="auth_accounting_admin">{{ trans('general_search.accounting_management') }}</label>
                        </div>
                        <div class="custom-checkbox custom-control mr-3">
                            {{ Form::input('checkbox', 'data[MGeneralSearch][auth_accounting]', 1, ['id' => 'auth_accounting', 'class'=>'custom-control-input ignore', (isset($dataResults['MGeneralSearch']['auth_accounting']) && !empty($dataResults) && $dataResults['MGeneralSearch']['auth_accounting'] == 1) ? 'checked' : '']) }}
                            <label class="custom-control-label" for="auth_accounting">{{ trans('general_search.accounting_general') }}</label>
                        </div>
                    </div>
                @endif
            </div>
        </fieldset>
        <fieldset class="form-group">
            <legend class="col-form-label fs-13">{{ trans('general_search.output_setting') }}</legend>
            <div class="mt-4 d-flex">
                <label id="tab-search-column" class="switch-button border-bottom-0 border-box bg-box border-right-0 col-sm-3 col-xl-1 mb-0 py-1 text-center">{{ trans('general_search.extraction_item') }}</label>
                <label id="tab-search-condition" class="switch-button border-bottom-0 border-box col-sm-3 col-xl-1 mb-0 py-1 text-center">{{ trans('general_search.output_condition') }}</label>
            </div>
            <div id="content-search-column" class="output-box bg-box border-box mx-0 p-2">
                <div class="row mx-0 mb-2">
                    <div class="col-sm-3 col-xl-2 px-0 px-sm-2">
                        <label class="col-form-label">{{ trans('general_search.function_name') }}</label>
                    </div>
                    <div class="col-sm-6 col-xl-3 px-0">
                        {{ Form::select('data[GeneralSearchItem][function_id]', $functionList, null, ['id' => 'GeneralSearchItemFunctionId', 'class'=>'form-control ignore p-1']) }}
                    </div>
                </div>
                <div class="row mx-0 mb-2">
                    <div class="col-sm-3 col-xl-2 px-0 px-sm-2">
                        <label class="col-form-label">{{ trans('general_search.item_narrowing_down') }}</label>
                    </div>
                    <div class="col-sm-6 col-xl-3 px-0">
                        <input type="text" id="column_suggest" class="form-control ignore p-1"/>
                    </div>
                </div>
                <div class="row mx-0 mb-2">
                    <div class="col-md-3 mb-2 mb-md-0 px-0 px-sm-2">
                        <select id="candidate_list" class="custom-select ignore" size="15" multiple></select>
                    </div>
                    <div class="col-md-1 px-md-0 px-0 px-sm-2">
                        <button id="move_right" class="btn btn--gradient-gray border--btn-gray col-12 mb-2 mb-md-0 p-0 top-md-35 fs-20">&#x226B;</button>
                        <button id="move_left" class="btn btn--gradient-gray border--btn-gray col-12 mb-2 mb-md-0 p-0 top-md-55 fs-20">&#x226A;</button>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0 px-0 px-sm-2">
                        <select id="select_list" class="custom-select ignore" size="15" multiple></select>
                    </div>
                    <div class="col-md-1 px-md-0 px-0 px-sm-2">
                        <button id="move_up" class="btn btn--gradient-gray border--btn-gray col-12 mb-2 mb-md-0 p-0 top-md-35 fs-20">&#x2227;</button>
                        <button id="move_down" class="btn btn--gradient-gray border--btn-gray col-12 mb-2 mb-md-0 p-0 top-md-55 fs-20">&#x2228;</button>
                    </div>
                </div>
            </div>
            <div id="content-search-condition" class="output-box bg-box border-box p-2">
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.company_name') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][1][m_corps-corp_name]', isset($dataResults['GeneralSearchCondition'][1]['m_corps-corp_name']) ? $dataResults['GeneralSearchCondition'][1]['m_corps-corp_name'] : null, ['id' => 'GeneralSearchCondition1MCorps-corpName', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.company_name_furigana') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][1][m_corps-corp_name_kana]', isset($dataResults['GeneralSearchCondition'][1]['m_corps-corp_name_kana']) ? $dataResults['GeneralSearchCondition'][1]['m_corps-corp_name_kana'] : null, ['id' => 'GeneralSearchCondition1MCorps-corpNameKana', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.customer_phone_number') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][9][demand_infos-customer_tel]', isset($dataResults['GeneralSearchCondition'][9]['demand_infos-customer_tel']) ? $dataResults['GeneralSearchCondition'][9]['demand_infos-customer_tel'] : null, ['id' => 'GeneralSearchCondition9DemandInfos-customerTel', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.customer_name') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][1][demand_infos-customer_name]', isset($dataResults['GeneralSearchCondition'][1]['demand_infos-customer_name']) ? $dataResults['GeneralSearchCondition'][1]['demand_infos-customer_name'] : null, ['id' => 'GeneralSearchCondition1DemandInfos-customerName', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.opportunity_id') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][0][demand_infos-id]', isset($dataResults['GeneralSearchCondition'][0]['demand_infos-id']) ? $dataResults['GeneralSearchCondition'][0]['demand_infos-id'] : null, ['id' => 'GeneralSearchCondition0DemandInfos-id', 'class'=>'form-control', 'data-rule-number'=>'true']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.proposal_status') }}</label>
                            </div>
                            <div class="col-sm-7 px-0 width-ui-state-default">
                                {{ Form::select('data[GeneralSearchCondition][3][demand_infos-demand_status][]',getDropList(\App\Repositories\Eloquent\MItemRepository::PROPOSAL_STATUS), isset($dataResults['GeneralSearchCondition'][3]['demand_infos-demand_status']) ? $dataResults['GeneralSearchCondition'][3]['demand_infos-demand_status'] : null,['id'=>'GeneralSearchCondition3DemandInfos-demandStatus', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.site_name') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][demand_infos-site_id][]',$siteLists, isset($dataResults['GeneralSearchCondition'][3]['demand_infos-site_id']) ? $dataResults['GeneralSearchCondition'][3]['demand_infos-site_id'] : null,['id'=>'site-id', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.jbr_reception_like_no') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][0][demand_infos-jbr_order_no]', isset($dataResults['GeneralSearchCondition'][0]['demand_infos-jbr_order_no']) ? $dataResults['GeneralSearchCondition'][0]['demand_infos-jbr_order_no'] : null, ['id' => 'jbr_order_no', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.genre_case') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][demand_infos-genre_id][]', $genreLists, isset($dataResults['GeneralSearchCondition'][3]['demand_infos-genre_id']) ? $dataResults['GeneralSearchCondition'][3]['demand_infos-genre_id'] : null,['id'=>'demand-genre-id', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.introduction_free') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0 d-flex align-items-center">
                                <div class="custom-control custom-checkbox">
                                    {{ Form::input('hidden', 'data[GeneralSearchCondition][0][commission_infos-introduction_free]', 0) }}
                                    {{ Form::input('checkbox', 'data[GeneralSearchCondition][0][commission_infos-introduction_free]', 1, ['id'=>'introduction_free', 'class'=>'custom-control-input', (isset($dataResults['GeneralSearchCondition'][0]['commission_infos-introduction_free'])&&($dataResults['GeneralSearchCondition'][0]['commission_infos-introduction_free'] == 1)) ? 'checked' : '']) }}
                                    <label class="custom-control-label" for="introduction_free"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.contact_deadline_date_and_time') }}</label>
                            </div>
                            <div class="col-sm-8 px-0 pr-sm-3">
                                <div class="row mx-0">
                                    <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][demand_infos-contact_desired_time][0]', isset($dataResults['GeneralSearchCondition'][2]['demand_infos-contact_desired_time'][0]) ? $dataResults['GeneralSearchCondition'][2]['demand_infos-contact_desired_time'][0] : null, ['id' => 'GeneralSearchCondition2DemandInfos-contactDesiredTime0', 'class' => 'datetimepicker form-control', 'data-rule-lessThanTime'=>'#GeneralSearchCondition2DemandInfos-contactDesiredTime1']) }}
                                    </div>
                                    <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                    <div class="col-sm-5 px-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][demand_infos-contact_desired_time][1]', isset($dataResults['GeneralSearchCondition'][2]['demand_infos-contact_desired_time'][1]) ? $dataResults['GeneralSearchCondition'][2]['demand_infos-contact_desired_time'][1] : null, ['id' => 'GeneralSearchCondition2DemandInfos-contactDesiredTime1', 'class' => 'datetimepicker form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0"></div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.reception_date_and_time') }}</label>
                            </div>
                            <div class="col-sm-8 px-0 pr-sm-3">
                                <div class="row mx-0">
                                    <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][demand_infos-receive_datetime][0]', isset($dataResults['GeneralSearchCondition'][2]['demand_infos-receive_datetime'][0]) ? $dataResults['GeneralSearchCondition'][2]['demand_infos-receive_datetime'][0] : null, ['id' => 'GeneralSearchCondition2DemandInfos-receiveDatetime0', 'class' => 'datetimepicker form-control', 'data-rule-lessThanTime'=>'#GeneralSearchCondition2DemandInfos-receiveDatetime1']) }}
                                    </div>
                                    <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                    <div class="col-sm-5 px-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][demand_infos-receive_datetime][1]', isset($dataResults['GeneralSearchCondition'][2]['demand_infos-receive_datetime'][1]) ? $dataResults['GeneralSearchCondition'][2]['demand_infos-receive_datetime'][1] : null, ['id' => 'GeneralSearchCondition2DemandInfos-receiveDatetime1', 'class' => 'datetimepicker form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.date_and_time_of_agency_sent') }}</label>
                            </div>
                            <div class="col-sm-8 px-0 pr-sm-3">
                                <div class="row mx-0">
                                    <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][commission_infos-commission_note_send_datetime][0]', isset($dataResults['GeneralSearchCondition'][2]['commission_infos-commission_note_send_datetime'][0]) ? $dataResults['GeneralSearchCondition'][2]['commission_infos-commission_note_send_datetime'][0] : null, ['id' => 'GeneralSearchCondition2CommissionInfos-commissionNoteSendDatetime0', 'class' => 'datetimepicker form-control', 'data-rule-lessThanTime'=>'#GeneralSearchCondition2CommissionInfos-commissionNoteSendDatetime1']) }}
                                    </div>
                                    <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                    <div class="col-sm-5 px-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][commission_infos-commission_note_send_datetime][1]', isset($dataResults['GeneralSearchCondition'][2]['commission_infos-commission_note_send_datetime'][1]) ? $dataResults['GeneralSearchCondition'][2]['commission_infos-commission_note_send_datetime'][1] : null, ['id' => 'GeneralSearchCondition2CommissionInfos-commissionNoteSendDatetime1', 'class' => 'datetimepicker form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.company_id') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][0][m_corps-id]', isset($dataResults['GeneralSearchCondition'][0]['m_corps-id']) ? $dataResults['GeneralSearchCondition'][0]['m_corps-id'] : null, ['id' => 'GeneralSearchCondition0MCorps-id', 'class'=>'form-control', 'data-rule-number'=>'true']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.prefectures') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][4][m_corps-address1][]', \Config::get('datacustom.prefecture_div'), isset($dataResults['GeneralSearchCondition'][4]['m_corps-address1']) ? $dataResults['GeneralSearchCondition'][4]['m_corps-address1'] : null,['id'=>'GeneralSearchCondition4MCorps-address1', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.phone_number') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][9][m_corps-tel1]', isset($dataResults['GeneralSearchCondition'][9]['m_corps-tel1']) ? $dataResults['GeneralSearchCondition'][9]['m_corps-tel1'] : null, ['id' => 'GeneralSearchCondition9MCorps-tel1', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.fax_number') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][0][m_corps-fax]', isset($dataResults['GeneralSearchCondition'][0]['m_corps-fax']) ? $dataResults['GeneralSearchCondition'][0]['m_corps-fax'] : null, ['id' => 'GeneralSearchCondition0MCorps-fax', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.pc_mail') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][1][m_corps-mailaddress_pc]', isset($dataResults['GeneralSearchCondition'][1]['m_corps-mailaddress_pc']) ? $dataResults['GeneralSearchCondition'][1]['m_corps-mailaddress_pc'] : null, ['id' => 'GeneralSearchCondition1MCorps-mailaddressPc', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.mobile_mail') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][1][m_corps-mailaddress_mobile]', isset($dataResults['GeneralSearchCondition'][1]['m_corps-mailaddress_mobile']) ? $dataResults['GeneralSearchCondition'][1]['m_corps-mailaddress_mobile'] : null, ['id' => 'GeneralSearchCondition1MCorps-mailaddressMobile', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.development_situation') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][m_corps-corp_status][]',getDropList(\App\Repositories\Eloquent\MItemRepository::CORP_STATUS), isset($dataResults['GeneralSearchCondition'][3]['m_corps-corp_status']) ? $dataResults['GeneralSearchCondition'][3]['m_corps-corp_status'] : null,['id'=>'corp-status', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.accession_non_member') }}</label>
                            </div>
                            <div class="col-sm-8 px-0 pr-sm-3 d-flex flex-column flex-lg-row align-items-lg-center">
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('data[GeneralSearchCondition][0][m_corps-affiliation_status]', 0, (isset($dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status']) && $dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status'] == 0) ? true : false,  ['id'=>'GeneralSearchCondition0MCorps-affiliationStatus0', 'class'=>'custom-control-input ignore']) }}
                                    <label class="custom-control-label" for="GeneralSearchCondition0MCorps-affiliationStatus0">{{ trans('general_search.not_yet_joined') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('data[GeneralSearchCondition][0][m_corps-affiliation_status]', 1,((isset($dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status']) && $dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status'] == 1) || !(isset($dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status']))) ? true : false, ['id'=>'GeneralSearchCondition0MCorps-affiliationStatus1', 'class'=>'custom-control-input ignore']) }}
                                    <label class="custom-control-label" for="GeneralSearchCondition0MCorps-affiliationStatus1">{{ trans('general_search.accession') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('data[GeneralSearchCondition][0][m_corps-affiliation_status]', -1,(isset($dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status']) && $dataResults['GeneralSearchCondition'][0]['m_corps-affiliation_status'] == -1) ? true : false, ['id'=>'GeneralSearchCondition0MCorps-affiliationStatus2', 'class'=>'custom-control-input ignore']) }}
                                    <label class="custom-control-label" for="GeneralSearchCondition0MCorps-affiliationStatus2">{{ trans('general_search.cancellation') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.sharing_technology_personnel') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][m_corps-rits_person][]', $mUserlist, isset($dataResults['GeneralSearchCondition'][3]['m_corps-rits_person']) ? $dataResults['GeneralSearchCondition'][3]['m_corps-rits_person'] : null,['id'=>'follow-person', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label id="call_suggest_genre" class="col-form-label">{{ trans('general_search.genre_member_store_') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][m_corp_categories-genre_id][]', $genreLists, isset($dataResults['GeneralSearchCondition'][3]['m_corp_categories-genre_id']) ? $dataResults['GeneralSearchCondition'][3]['m_corp_categories-genre_id'] : null,['id'=>'genre-id', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{!! trans('general_search.applicable_area_prefecture') !!}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][9][m_target_areas-jis_cd][]', \Config::get('datacustom.prefecture_div'), isset($dataResults['GeneralSearchCondition'][9]['m_target_areas-jis_cd']) ? $dataResults['GeneralSearchCondition'][9]['m_target_areas-jis_cd'] : null,['id'=>'GeneralSearchCondition9MTargetAreas-jisCd', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0"></div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{!! trans('general_search.status_of_member_store_transactions') !!}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][m_corps-corp_commission_status][]',getDropList(\App\Repositories\Eloquent\MItemRepository::CONTRACT_STATUS), isset($dataResults['GeneralSearchCondition'][3]['m_corps-corp_commission_status']) ? $dataResults['GeneralSearchCondition'][3]['m_corps-corp_commission_status'] : null,['id'=>'corp-commission-status', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.fax_email_transmission') }}</label>
                            </div>
                            <div class="col-sm-8 d-flex flex-column flex-lg-row align-items-lg-center px-0">
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('data[GeneralSearchCondition][0][commission_infos-send_mail_fax]', 0, (isset($dataResults['GeneralSearchCondition'][0]['commission_infos-send_mail_fax']) && $dataResults['GeneralSearchCondition'][0]['commission_infos-send_mail_fax'] == 0) ? true : false, ['id' => 'send_mail_fax0', 'class'=>'custom-control-input ignore']) }}
                                    <label class="custom-control-label" for="send_mail_fax0">{{ trans('general_search.unsent') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('data[GeneralSearchCondition][0][commission_infos-send_mail_fax]', 1, (isset($dataResults['GeneralSearchCondition'][0]['commission_infos-send_mail_fax']) && $dataResults['GeneralSearchCondition'][0]['commission_infos-send_mail_fax'] == 1) ? true : false, ['id' => 'send_mail_fax1', 'class'=>'custom-control-input ignore']) }}
                                    <label class="custom-control-label" for="send_mail_fax1">{{ trans('general_search.send') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline pl-0 pl-sm-2">
                                    <button type="button" id="reset-send-mail-fax" class="btn btn--gradient-gray">{{ trans('general_search.release') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 mb-2">
                    <div class="col-12 px-0">
                        <div class="row mx-0">
                            <div class="col-sm-4 col-lg-2 px-0 d-flex align-items-center">
                                <label class="mb-0">{{ trans('general_search.free_text') }}</label>
                            </div>
                            <div class="col-sm-6 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][9][m_corps-free_text]', isset($dataResults['GeneralSearchCondition'][9]['m_corps-free_text']) ? $dataResults['GeneralSearchCondition'][9]['m_corps-free_text'] : null, ['id' => 'GeneralSearchCondition9MCorps-freeText', 'class'=>'form-control']) }}
                            </div>
                            <div class="offset-0 offset-sm-4 offset-sm-4 offset-lg-0 col-sm-8 col-lg-4 px-0 d-flex align-items-center">{{ trans('general_search.remarks_we_will_search_from_notes') }}</div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.list_source_medium') }}</label>
                            </div>
                            <div class="col-sm-6 col-md-5 px-0">
                                {{ Form::input('text', 'data[GeneralSearchCondition][1][m_corps-listed_media]', isset($dataResults['GeneralSearchCondition'][1]['m_corps-listed_media']) ? $dataResults['GeneralSearchCondition'][1]['m_corps-listed_media'] : null, ['id' => 'GeneralSearchCondition1MCorps-listedMedia', 'class'=>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0"></div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.contract_status') }}</label>
                            </div>
                            <div class="col-sm-7 px-0">
                                {{ Form::select('data[GeneralSearchCondition][3][commission_infos-commission_status][]',getDropList(\App\Repositories\Eloquent\MItemRepository::COMMISSION_STATUS), isset($dataResults['GeneralSearchCondition'][3]['commission_infos-commission_status']) ? $dataResults['GeneralSearchCondition'][3]['commission_infos-commission_status'] : 0,['id'=>'GeneralSearchCondition3CommissionInfos-commissionStatus', 'class'=>'form-control', 'multiple'=>'multiple']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 px-0">
                        <div class="row mx-0 mb-2">
                            <div class="col-sm-4 px-0">
                                <label class="col-form-label">{{ trans('general_search.construction_completion_date') }}</label>
                            </div>
                            <div class="col-sm-8 px-0 pr-sm-3">
                                <div class="row mx-0">
                                    <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][commission_infos-complete_date][0]', isset($dataResults['GeneralSearchCondition'][2]['commission_infos-complete_date'][0]) ? $dataResults['GeneralSearchCondition'][2]['commission_infos-complete_date'][0] : null, ['id' => 'GeneralSearchCondition2CommissionInfos-completeDate0', 'class' => 'datepicker form-control', 'data-rule-lessThanTime'=>'#GeneralSearchCondition2CommissionInfos-completeDate1']) }}
                                    </div>
                                    <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                    <div class="col-sm-5 px-0">
                                        {{ Form::input('text', 'data[GeneralSearchCondition][2][commission_infos-complete_date][1]', isset($dataResults['GeneralSearchCondition'][2]['commission_infos-complete_date'][1]) ? $dataResults['GeneralSearchCondition'][2]['commission_infos-complete_date'][1] : null, ['id' => 'GeneralSearchCondition2CommissionInfos-completeDate1', 'class' => 'datepicker form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0 mb-2">
                    <div class="col-sm-4 col-lg-2 px-0">
                        <label class="col-form-label">{{ trans('general_search.resabe_case') }}</label>
                    </div>
                    <div class="col-sm-8 col-lg-10 d-flex flex-column flex-lg-row align-items-lg-center px-0">
                        <div class="custom-control custom-radio custom-control-inline">
                            {{ Form::radio('data[GeneralSearchCondition][0][demand_infos-reservation_demand]', 0, (isset($dataResults['GeneralSearchCondition'][0]['demand_infos-reservation_demand']) && $dataResults['GeneralSearchCondition'][0]['demand_infos-reservation_demand'] == 0) ? true : false, ['id' => 'GeneralSearchCondition0DemandInfos-reservationDemand0', 'class'=>'custom-control-input ignore']) }}
                            <label class="custom-control-label" for="GeneralSearchCondition0DemandInfos-reservationDemand0">{{ trans('general_search.no_change') }}</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            {{ Form::radio('data[GeneralSearchCondition][0][demand_infos-reservation_demand]', 1, (isset($dataResults['GeneralSearchCondition'][0]['demand_infos-reservation_demand']) && $dataResults['GeneralSearchCondition'][0]['demand_infos-reservation_demand'] == 1) ? true : false, ['id' => 'GeneralSearchCondition0DemandInfos-reservationDemand1', 'class'=>'custom-control-input ignore']) }}
                            <label class="custom-control-label" for="GeneralSearchCondition0DemandInfos-reservationDemand1">{{ trans('general_search.put_on_hold_and_decide_the_return_time') }}</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            {{ Form::radio('data[GeneralSearchCondition][0][demand_infos-reservation_demand]', 2, (isset($dataResults['GeneralSearchCondition'][0]['demand_infos-reservation_demand']) && $dataResults['GeneralSearchCondition'][0]['demand_infos-reservation_demand'] == 2) ? true : false, ['id' => 'GeneralSearchCondition0DemandInfos-reservationDemand2', 'class'=>'custom-control-input ignore']) }}
                            <label class="custom-control-label" for="GeneralSearchCondition0DemandInfos-reservationDemand2">{{ trans('general_search.determination_of_visit_time_by_putting_on_hold') }}</label>
                        </div>
                    </div>
                </div>
                @if($permissionSaveDel)
                    <div class="row mx-0">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2">
                                <div class="col-sm-4 px-0">
                                    <label class="col-form-label">{{ trans('general_search.billing_status') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    @php
                                        $billInfosBillStatus = getDropList(\App\Repositories\Eloquent\MItemRepository::BILLING_STATUS);
                                        $billInfosBillStatus = [null => 'なし'] + $billInfosBillStatus;
                                    @endphp
                                    {{ Form::select('data[GeneralSearchCondition][0][bill_infos-bill_status]', $billInfosBillStatus, isset($dataResults['GeneralSearchCondition'][0]['bill_infos-bill_status']) ? $dataResults['GeneralSearchCondition'][0]['bill_infos-bill_status'] : 0,['id'=>'GeneralSearchCondition0BillInfos-billStatus', 'class'=>'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2">
                                <div class="col-sm-4 px-0">
                                    <label class="col-form-label">{{ trans('general_search.billing_id') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{ Form::input('text', 'data[GeneralSearchCondition][0][bill_infos-id]', isset($dataResults['GeneralSearchCondition'][0]['bill_infos-id']) ? $dataResults['GeneralSearchCondition'][0]['bill_infos-id'] : null, ['id' => 'GeneralSearchCondition0BillInfos-id', 'class'=>'form-control', 'data-rule-number'=>'true']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2">
                                <div class="col-sm-4 px-0">
                                    <label class="col-form-label">{{ trans('general_search.commission_charge_date') }}</label>
                                </div>
                                <div class="col-sm-8 px-0 pr-sm-3">
                                    <div class="row mx-0">
                                        <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                            {{ Form::input('text', 'data[GeneralSearchCondition][2][bill_infos-fee_billing_date][0]', isset($dataResults['GeneralSearchCondition'][2]['bill_infos-fee_billing_date'][0]) ? $dataResults['GeneralSearchCondition'][2]['bill_infos-fee_billing_date'][0] : null, ['id' => 'GeneralSearchCondition2BillInfos-feeBillingDate0', 'class' => 'datepicker form-control', 'data-rule-lessThanTime'=>'#GeneralSearchCondition2BillInfos-feeBillingDate1']) }}
                                        </div>
                                        <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                        <div class="col-sm-5 px-0">
                                            {{ Form::input('text', 'data[GeneralSearchCondition][2][bill_infos-fee_billing_date][1]', isset($dataResults['GeneralSearchCondition'][2]['bill_infos-fee_billing_date'][1]) ? $dataResults['GeneralSearchCondition'][2]['bill_infos-fee_billing_date'][1] : null, ['id' => 'GeneralSearchCondition2BillInfos-feeBillingDate1', 'class' => 'datepicker form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2">
                                <div class="col-sm-4 px-0">
                                    <label class="col-form-label">{{ trans('general_search.nominee') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{ Form::input('text', 'data[GeneralSearchCondition][9][money_corresponds-nominee]', isset($dataResults['GeneralSearchCondition'][9]['money_corresponds-nominee']) ? $dataResults['GeneralSearchCondition'][9]['money_corresponds-nominee'] : null, ['id' => 'GeneralSearchCondition9MonyCorresponds-nominee', 'class'=>'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0"></div>
                    </div>
                @endif
            </div>
            <div class="mt-3 text-sm-right">
                <a class="btn btn--gradient-gray col-lg-1 col-sm-3 mb-2 mb-sm-0" href="{{ route('general_search.index') }}">{{ trans('general_search.clear') }}</a>
                <button id="make-csv" name="csv" formaction="{{ route('general_search.csv') }}" class="btn btn--gradient-orange col-lg-1 col-sm-3 mb-2 mb-sm-0" type="submit">{{ trans('general_search.csv_output') }}</button>
                <button id="make-result" class="btn btn--gradient-orange col-lg-1 col-sm-3" data-url="{{ route('ajax.csvpreview') }}">{{ trans('general_search.search') }}</button>
            </div>
            <span data-url="{{ route('general_search.index') }}" class="url_genre"></span>
        </fieldset>

        {{ Form::close() }}

        <div class="modal fade general-search-index" id="savedList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="saved_list_title" class="modal-title w-100 fs-13" align="center">
                            <span style="color:#ccffcc">■</span>
                            <span style="color:#aaccaa">■</span>
                            <span style="color:#66aa66">■</span>
                            <span style="color:#008000">■</span>
                            <span style="color:#008000"
                                  class="font-weight-bold fs-14">{{ trans('general_search.save_list') }}</span>
                            <span style="color:#008000">■</span>
                            <span style="color:#66aa66">■</span>
                            <span style="color:#aaccaa">■</span>
                            <span style="color:#ccffcc">■</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-inline mb-3">
                            <label class="mr-2">{{ trans('general_search.search_keyword') }}</label>
                            <input type="text" id="list_search_keyword" class="form-control"/>
                        </div>
                        <div class="table-responsive fix-height-50vh-table-modal">
                            <table class="table custom-border">
                                <thead>
                                    <tr class="text-center bg-yellow-light">
                                        <th class="p-1 align-middle fix-w-100">
                                            {{ trans('general_search.overall_search_id') }}
                                        </th>
                                        <th class="p-1 align-middle fix-w-100">
                                            {{ trans('general_search.setting_name') }}
                                        </th>
                                        <th class="p-1 align-middle fix-w-100">
                                            {{ trans('general_search.scope_of_disclosure') }}
                                        </th>
                                        <th class="p-1 align-middle fix-w-100">
                                            {{ trans('general_search.creation_date') }}
                                        </th>
                                        <th class="p-1 align-middle fix-w-100">
                                            {{ trans('general_search.author') }}
                                        </th>
                                        <th class="p-1 align-middle fix-w-100">
                                            {{ trans('general_search.choice') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="save_list"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--gradient-gray" data-dismiss="modal">{{ trans('general_search.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade general-search-index" id="modal_genre_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="saved_list_title" class="modal-title w-100 fs-13" align="center">
                            <span style="color:#ccffcc">■</span>
                            <span style="color:#aaccaa">■</span>
                            <span style="color:#66aa66">■</span>
                            <span style="color:#008000">■</span>
                            <span style="color:#008000"
                                  class="font-weight-bold fs-14">{{ trans('general_search.genre_search') }}</span>
                            <span style="color:#008000">■</span>
                            <span style="color:#66aa66">■</span>
                            <span style="color:#aaccaa">■</span>
                            <span style="color:#ccffcc">■</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="my-2">
                            <input type="text" id="txt_search" class="form-control" onkeyup="searchGenre(this)">
                        </div>
                        <div>
                            <select id="genre_list" class="form-control" name="genre_list[]" size="20" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--gradient-gray" data-dismiss="modal" id="transfer-selected">{{ trans('general_search.confirm') }}
                        </button>
                        <button type="button" class="btn btn--gradient-gray" data-dismiss="modal">{{ trans('general_search.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade general-search-index" id="resultList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="saved_list_title" class="modal-title w-100 fs-13" align="center">
                            <span style="color:#ccffcc">■</span>
                            <span style="color:#aaccaa">■</span>
                            <span style="color:#66aa66">■</span>
                            <span style="color:#008000">■</span>
                            <span style="color:#008000"
                                  class="font-weight-bold fs-14">{{ trans('general_search.search_results') }}</span>
                            <span style="color:#008000">■</span>
                            <span style="color:#66aa66">■</span>
                            <span style="color:#aaccaa">■</span>
                            <span style="color:#ccffcc">■</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive fix-height-22vh-table-modal">
                            <table>
                                <tbody id="result_condition"></tbody>
                            </table>
                        </div>
                        <div class="table-responsive fix-height-50vh-table-modal">
                            <table class="table custom-border">
                                <thead id="result_header" class="text-center bg-yellow-light">
                                    <tr>
                                        <th class="p-1 align-middle fix-w-100">No</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}1</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}2</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}3</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}4</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}5</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}6</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}7</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}8</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}9</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}10</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}11</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}12</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}13</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}14</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}15</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}16</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}17</th>
                                        <th class="p-1 align-middle fix-w-100">{{ trans('general_search.item') }}18</th>
                                    </tr>
                                </thead>
                                <tbody id="result_list"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--gradient-gray" data-dismiss="modal">{{ trans('general_search.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var data_source = [
            [<?php echo $functionListCase; ?>],
            [<?php echo $functionAgencyManagement; ?>],
            [<?php echo $functionChargeManagement; ?>],
            [<?php echo $functionMemberManagement; ?>]
        ];
        var selected_item = [<?php echo $selectedItem; ?>];
        var un_select = '@lang('auto_commission_corp.none')';
        var check_all = '@lang('auto_commission_corp.check_all')';
        var un_check_all = '@lang('auto_commission_corp.un_check_all')';
        var time_text = '@lang('general_search.time_text')';
        var hour_text = '@lang('general_search.hour_text')';
        var min_text = '@lang('general_search.min_text')';
        var current_text = '@lang('general_search.current_text')';
        var close_text = '@lang('general_search.close_text')';
        var today_text = '@lang('general_search.today_text')';
    </script>
    <script type="text/javascript" src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/pages/general_search.index.js') }}"></script>
    <script>
        FormUtil.validate('#general_search_form');
        Datetime.initForDatepicker();
        Datetime.initForDateTimepicker();
    </script>
@endsection
