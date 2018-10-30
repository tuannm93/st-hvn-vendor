@extends('layouts.app')
@section('style')
@endsection
@php
    $bCheckUnjoin = false;
    $bCheckJoin = true;
    $bCheckDismis = false;
    switch ($data['corpJoinStatus']){
        case 0:
            $bCheckUnjoin = true;
            $bCheckJoin = false;
            $bCheckDismis = false;
            break;
        case -1:
            $bCheckUnjoin = false;
            $bCheckJoin = false;
            $bCheckDismis = true;
            break;
        default:
            $bCheckUnjoin = false;
            $bCheckJoin = true;
            $bCheckDismis = false;
            break;
    }

    $phoneNumber = isset($tel1) ? $tel1 : $data['phoneNumber'];
@endphp
@section('content')
    <div class="affiliation-index">
        <div id="error">
            <div id="error-inner"></div>
        </div>
        <br/>
        <input type="hidden" value="{{csrf_token()}}" id="csrf-token" name="token">
        <input type="hidden" id="instant_search" value="{{$instantSearch}}" name="iSearch">
        <div id="search">
            <div id="contents">
                <div id="main">
                    {{ Form::open(['action' => 'Affiliation\AffiliationController@downloadCSVAffiliation',
                    'method' => 'post', 'id' => 'formDataSearchAffiliation', 'class' => 'fieldset-custom']) }}
                    <fieldset class="form-group">
                        <legend class="col-form-label fs-13">{{__('affiliation.index_search_legend')}}</legend>
                        <div class="box--bg-gray box--border-gray p-2">
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.corp_name')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_name', null, ['id'=>'corp_name', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.corp_name_kana')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_name_kana', null, ['id'=>'corp_name_kana', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.business_id')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_id', null, ['id'=>'corp_id', 'class' => 'form-control', 'data-rule-numberAllSize'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.prefectures')}}</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            {{Form::select('list_pref[]', $data['listPref'], null, ['id' => 'list_pref', 'multiple'=>'multiple', 'class' => 'form-control', 'hidden'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.phone_number')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_phone', $phoneNumber, ['id'=>'corp_phone', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.fax_number')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_fax', null, ['id'=>'corp_fax', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.pc_mail')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_pc_mail', null, [ 'id'=>'corp_pc_mail', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.mobile_mail')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'corp_mobile_mail', null, ['id'=>'corp_mobile_mail', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.corp_status')}}</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            {{Form::select('list_status[]', $data['listStatus'], null, ['id' => 'list_status','multiple'=>'multiple','class' => 'form-control', 'hidden'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.corp_join_unjoin')}}</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {{ Form::radio('corp_radio_ju', 0, $bCheckUnjoin, ['id' => 'state_corp_unjoin', 'class' => 'custom-control-input ignore'])}}
                                                <label class="custom-control-label"
                                                       for="state_corp_unjoin">{{__('affiliation.corp_unjoin')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {{ Form::radio('corp_radio_ju', 1, $bCheckJoin, ['id' => 'state_corp_join', 'class' => 'custom-control-input ignore'])}}
                                                <label class="custom-control-label"
                                                       for="state_corp_join">{{__('affiliation.corp_join')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {{ Form::radio('corp_radio_ju', -1, $bCheckDismis, ['id' => 'state_corp_dismisal', 'class' => 'custom-control-input ignore'])}}
                                                <label class="custom-control-label"
                                                       for="state_corp_dismisal">{{__('affiliation.corp_dismisal')}}</label>
                                            </div>
                                            <button class="btn d-none d-lg-inline-block opacity-0" tabindex="-1">a</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.corp_followup_date')}}</label>
                                        </div>
                                        <div class="col-sm-8 px-0 pr-sm-3">
                                            <div class="row mx-0">
                                                <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                                    {{Form::input('text', 'from_followup_date', null, ['id'=>'from_followup_date', 'class' => 'datetimepicker form-control', 'data-rule-lessThanTime'=>'#to_followup_date'])}}
                                                </div>
                                                <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                                <div class="col-sm-5 px-0">
                                                    {{Form::input('text', 'to_followup_date', null, ['id'=>'to_followup_date', 'class' => 'datetimepicker form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0"></div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.sharing_tech_person')}}</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            {{Form::select('list_rits_person[]', $data['listRitsPerson'], null, ['id' => 'list_rits_person', 'multiple'=>'multiple', 'class' => 'form-control', 'hidden'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0" id="searchGenre">{{__('affiliation.genre')}}</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            {{Form::select('list_genre[]', $data['listGenre'], null, ['id' => 'list_genre', 'multiple'=>'multiple', 'class' => 'form-control', 'hidden'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0 mb-2 mb-sm-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.available_area')}}
                                                <br/>({{__('affiliation.prefectures')}})</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            {{Form::select('list_avail_pref[]', $data['listPref'], null, ['id' => 'list_avail_pref', 'multiple'=>'multiple', 'class' => 'form-control', 'hidden'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.24h_work')}}</label>
                                        </div>
                                        <div class="col-sm-8 px-0">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {{ Form::radio('support_24h', 0, null, ['id' => 'support_24h_no', 'class' => 'custom-control-input ignore'])}}
                                                <label class="custom-control-label"
                                                       for="support_24h_no">{{__('affiliation.no')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {{ Form::radio('support_24h', 1, null, ['id' => 'support_24h_yes', 'class' => 'custom-control-input ignore'])}}
                                                <label class="custom-control-label"
                                                       for="support_24h_yes">{{__('affiliation.yes')}}</label>
                                            </div>
                                            {{ Form::button(__('affiliation.release'), ['id' => 'btnResetCheckWork24h', 'class' => 'btn btn--gradient-gray remove-effect-btn'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.contract_status')}}</label>
                                        </div>
                                        <div class="col-sm-7 px-0">
                                            {{Form::select('list_contract_status[]', $data['contractStatus'], null, ['id' => 'list_contract_status', 'multiple'=>'multiple', 'class' => 'form-control', 'hidden'=>'true'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0"></div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-12 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 col-lg-2 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.free_text')}}</label>
                                        </div>
                                        <div class="col-sm-6 px-0">
                                            {{Form::input('text', 'free_text_search', null, ['id'=>'free_text_search', 'class' => 'form-control'])}}
                                        </div>
                                        <div class="offset-0 offset-sm-4 offset-sm-4 offset-lg-0 col-sm-8 col-lg-4 px-0 d-flex align-items-center">{{__('affiliation.note_free_text')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-0 mb-2">
                                <div class="col-lg-6 px-0">
                                    <div class="row mx-0">
                                        <div class="col-sm-4 px-0 d-flex align-items-center">
                                            <label class="mb-0">{{__('affiliation.listed_media')}}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-5 px-0">
                                            {{Form::input('text', 'listed_media', null, ['id'=>'listed_media', 'class' => 'form-control'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 px-0"></div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row">
                                {{Form::button(__('affiliation.sign_up'), ['id' => 'btnBackToSignUp', 'class' => 'btn btn--gradient-orange remove-effect-btn col-lg-1 col-sm-3 mb-1 mb-sm-0'])}}
                                {{Form::button(__('affiliation.search'), ['id' => 'btnSearch', 'class' => 'btn btn--gradient-orange remove-effect-btn col-lg-1 col-sm-3 mx-sm-2 mb-1 mb-sm-0'])}}
                                @if($data['allowShowDownloadCsv'])
                                    <input title="" type="submit" value="{{__('affiliation.export_CSV')}}"
                                           id="btnExportCsv"
                                           class="function-button btn btn--gradient-orange remove-effect-btn col-lg-1
                                            col-sm-3 ui-helper-hidden">
                                @endif
                            </div>
                        </div>
                    </fieldset>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div id="viewResult"></div>
        <div id="page-data" data-text-none-select="{{__('auto_commission_corp.none')}}"
             data-text-selectall="{{__('auto_commission_corp.select_all')}}"
             data-text-unselectall="{{__('auto_commission_corp.unselect_all')}}"
             data-url-back="{{route('affiliation.detail.create')}}"
             data-url-exportcsv="{{route('affiliation.download')}}"
             data-url-search="{{route('affiliation.search')}}">
        </div>
    </div>
    <div class="modal custom-modal-affiliation-index" id="genreSearchModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 fs-13" align="center">
                        <span style="color:#ccffcc">■</span>
                        <span style="color:#aaccaa">■</span>
                        <span style="color:#66aa66">■</span>
                        <span style="color:#008000">■</span>
                        <span style="color:#008000"
                              class="font-weight-bold fs-14">{{__('affiliation.genre_search')}}</span>
                        <span style="color:#008000">■</span>
                        <span style="color:#66aa66">■</span>
                        <span style="color:#aaccaa">■</span>
                        <span style="color:#ccffcc">■</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mx-auto w-50 mb-2">
                        <input id="txtSearch" type="text" class="form-control" title="">
                    </div>
                    <div class="mx-auto w-80 pt-2 border-top-bold">
                        <select id="genreListSearchModal" class="w-100" name="genre_list[]" size="20" multiple
                                title=""></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--gradient-green remove-effect-btn" id="btnModalDecide"
                            data-dismiss="modal">{{__('affiliation.decide')}}
                    </button>
                    <button type="button" class="btn btn--gradient-gray remove-effect-btn"
                            data-dismiss="modal">{{__('affiliation.cancel')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script>
        FormUtil.validate('#formDataSearchAffiliation');
    </script>
    <script src="{{ mix('js/pages/affiliation.search.js') }}"></script>
@endsection
