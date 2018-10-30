@extends('layouts.app')

@section('content')
<div class="progress-management-corp-index">
    <div class="table-responsive mt-3">
        <table class="table custom-border">
            <thead>
                <tr class="text-center bg-yellow-light">
                    <th class="align-middle p-1">@lang('progress_management.delete_commission_infos.file_id')</th>
                    <th class="align-middle p-1">@lang('progress_management.delete_commission_infos.file_name')</th>
                    <th class="align-middle p-1">@lang('progress_management.delete_commission_infos.file_upload_date')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                	<td class="align-middle p-1 text-center">{{ $importFile->id }}</td>
					<td class="align-middle p-1">{{ $importFile->original_file_name }}</td>
					<td class="align-middle p-1 text-center">{{ $importFile->import_date }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <form class="fieldset-custom" method="GET" id="frmCorpSearch">
        @php
            $limit = isset($searchQuery['limit']) ? $searchQuery['limit'] : 30;
        @endphp
        <fieldset class="form-group">
            <legend class="col-form-label">@lang('progress_management.search_condition')</legend>
            <div class="box--bg-gray box--border-gray p-2">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <label class="col-form-label">@lang('progress_management.company_id')</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control param" data-rule-number="true"
                                       value="{{ isset($searchQuery['corp_id']) ? $searchQuery['corp_id'] : '' }}"
                                id="corp_id" name="corp_id">
                                <input type="hidden" name="limit" value="{{ $limit }}" id="limit" />
                            </div>
                        </div>
                    </div>
                    <div class="offset-xl-1 col-xl-5">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label class="col-form-label">@lang('progress_management.progress')</label>
                            </div>
                            <div class="col-sm-6">
                                @php
                                    $flg = 0;
                                    if (isset($searchQuery['progress_flag'])) {
                                        $flg = $searchQuery['progress_flag'];
                                    }
                                @endphp
                                <select class="form-control param" id="progress_flag" name="progress_flag">
                                    <option value="0"></option>
                                    @foreach($progressFlagList as $key => $val)
                                    <option value="{{ $key }}" {{ $flg == $key ? ' selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <label class="col-form-label">@lang('progress_management.company_name')</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control param"
                                value="{{ isset($searchQuery['corp_name']) ? $searchQuery['corp_name'] : '' }}"
                                id="corp_name" name="corp_name">
                            </div>
                        </div>
                    </div>
                    <div class="offset-xl-1 col-xl-5">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label class="col-form-label">@lang('progress_management.sending_method')</label>
                            </div>
                            <div class="col-sm-6">
                                @php
                                    $ctype = 0;
                                    if (isset($searchQuery['contact_type'])) {
                                        $ctype = $searchQuery['contact_type'];
                                    }
                                @endphp
                                <select class="form-control param" id="contact_type" name="contact_type">
                                    <option value="0"></option>
                                    @foreach($contactTypeList as $key => $val)
                                    <option value="{{ $key }}" {{ $key == $ctype ? ' selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <label class="col-form-label">@lang('progress_management.unit_price')</label>
                            </div>
                            <div class="col-sm-4">
                                @php
                                    $uCost = 0;
                                    if (isset($searchQuery['unit_cost'])) {
                                        $uCost = $searchQuery['unit_cost'];
                                    }
                                @endphp
                                <select class="form-control param" id="unit_cost" name="unit_cost">
                                    <option value="0"></option>
                                    <option value="1" {{ $uCost == "1" ? ' selected' : '' }}>@lang('progress_management.order_asc')</option>
                                    <option value="2" {{ $uCost == "2" ? ' selected' : '' }}>@lang('progress_management.order_desc')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="offset-xl-1 col-xl-5">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label class="col-form-label">@lang('progress_management.call_back_phone_flag')</label>
                            </div>
                            <div class="col-sm-6">
                                @php
                                    $cFlg = 0;
                                    if (isset($searchQuery['call_back_phone_flag'])) {
                                        $cFlg = $searchQuery['call_back_phone_flag'];
                                    }
                                @endphp
                                <select class="form-control param" id="call_back_phone_flag" name="call_back_phone_flag">
                                    <option value="0"></option>
                                    @foreach($callBackPhoneFlag as $key => $val)
                                    <option value="{{ $key }}" {{ $key == $cFlg ? ' selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <label class="col-form-label">@lang('progress_management.collection_date')</label>
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control mb-1 mb-sm-0 remove-effect-readonly datetimepicker param " name="collection_date_from" type="text" value="{{ isset($searchQuery['collection_date_from']) ? $searchQuery['collection_date_from'] : '' }}" id="collection_date">
                            </div>
                            <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x2053;</div>
                            <div class="col-sm-4">
                                <input class="form-control datetimepicker remove-effect-readonly param "  name="collection_date_to" type="text" value="{{ isset($searchQuery['collection_date_to']) ? $searchQuery['collection_date_to'] : '' }}" id="collection_date_to">
                            </div>
                        </div>
                    </div>
                    <div class="offset-xl-1 col-xl-5">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label class="col-form-label">@lang('progress_management.follow_up_history')</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control param" value="{{ isset($searchQuery['note']) ? $searchQuery['note'] : '' }}" id="note" name="note">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <label class="col-form-label">@lang('progress_management.after_tel_date')</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" data-rule-date="true" data-rule-pattern="\d{4}/\d{1,2}/\d{1,2}" data-msg-pattern="@lang('validation.invalid_date')" class="form-control mb-1 mb-sm-0 remove-effect-readonly datepicker param " value="{{ isset($searchQuery['after_tel_date_from']) ? $searchQuery['after_tel_date_from'] : '' }}" id="after_tel_date_from" name="after_tel_date_from">
                            </div>
                            <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x2053;</div>
                            <div class="col-sm-4">
                                <input type="text" data-rule-date="true" data-rule-pattern="\d{4}/\d{1,2}/\d{1,2}" data-msg-pattern="@lang('validation.invalid_date')" class="form-control datepicker remove-effect-readonly param " value="{{ isset($searchQuery['after_tel_date_to']) ? $searchQuery['after_tel_date_to'] : '' }}" id="after_tel_date_to" name="after_tel_date_to">
                            </div>
                        </div>
                    </div>
                    <div class="offset-xl-1 col-xl-5">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <label class="col-form-label">@lang('progress_management.post_history')</label>
                            </div>
                            <div class="col-sm-6">
                                @php
                                    $postHis = 0;
                                    if (isset($searchQuery['post_history'])) {
                                        $postHis = $searchQuery['post_history'];
                                    }
                                @endphp
                                <select class="form-control param"  id="post_history" name="post_history">
                                    <option value="0"></option>
                                    <option value="1" {{ $postHis == 1 ? ' selected' : '' }}>@lang('progress_management.listed')</option>
                                    <option value="2" {{ $postHis == 2 ? ' selected' : '' }}>@lang('progress_management.not_listed')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <label class="col-form-label">@lang('progress_management.follow_up_tel')</label>
                            </div>
                            <div class="col-sm-4">
                                @php
                                    $fut = 0;
                                    if (isset($searchQuery['call_back_phone_date'])) {
                                        $fut = $searchQuery['call_back_phone_date'];
                                    }
                                @endphp
                                <select class="form-control param" id="call_back_phone_date" name="call_back_phone_date">
                                    <option value="0"></option>
                                    <option value="1" {{ $fut ==1 ? ' selected' : '' }}>@lang('progress_management.none')</option>
                                    <option value="2" {{ $fut ==2 ? ' selected' : '' }}>@lang('progress_management.yes')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn--gradient-orange remove-effect-btn col-sm-6 col-lg-3 fix-w-100"  id="searchForm">@lang('progress_management.submit')</button>
                        <button type="button" class="btn btn--gradient-default remove-effect-btn col-sm-6 col-lg-3 fix-w-100" id="resetButton">@lang('progress_management.reset')</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    @if(session('message'))
        <div class="box__mess box--success mb-4">
          {!! session('message') !!}
        </div>
    @endif
    @if($progCorps->isEmpty())
        <p class="text-center">@lang('progress_management.search_empty')</p>
    @else
    <div class="corp-table-form">
        <div class="corp-pagination">
            <p class="text-sm-right">@lang('progress_management.guide_message')</p>
            <div class="input-group mb-3 col-sm-4 col-md-3 col-xl-2 px-0">
                <div class="input-group-prepend">
                    <label class="input-group-text">@lang('progress_management.display_result')</label>
                </div>
                <select class="form-control"  id="display_result" name="display_result">
                    <option value="30" {{ $limit == 30 ? ' selected' : '' }}>30</option>
                    <option value="50" {{ $limit == 50 ? ' selected' : '' }}>50</option>
                    <option value="100" {{ $limit == 100 ? ' selected' : '' }}>100</option>
                    <option value="200" {{ $limit == 200 ? ' selected' : '' }}>200</option>
                </select>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    @php
                        $to =  $progCorps->perPage() * ($progCorps->currentPage() - 1) + count($progCorps->items());
                        $from = $progCorps->perPage() * $progCorps->currentPage() - $progCorps->perPage() + 1;
                    @endphp
                	<span>
                		@lang('progress_management.page_info', [ 'total_row' => $progCorps->total(), 'from' => $from, 'to' => $to, 'total_page' => $progCorps->lastPage(), 'current_page' => $progCorps->currentPage() ])
                	</span>
                </div>
                <div class="col-sm-6 fs-20">
                        {{ $progCorps->appends($searchQuery)->links('pagination.prog_demand') }}
                </div>
            </div>
        </div>
        <div class="row mx-0 bg-primary-lighter">
            <div class="align-items-center custom-border custom-checkbox d-flex justify-content-center w-10 w-sm-5">
                <input type="checkbox" class="custom-control-input" id="selectAllCheck">
                <label class="custom-control-label custome-label" for="selectAllCheck"><span
                                                class="d-none">a</span></label>
                <input class="d-none" tabindex="-1">
            </div>
            <div class="w-90 w-sm-95 px-0 custom-border border-left"></div>
        </div>
        @foreach($progCorps as $pCorp)
        <form class="frmPCorpUpdate" id="frmPCorp{{ $pCorp->id }}" name="frmPCorp{{ $pCorp->id }}" method="POST" action="{{ route('progress.corpIndex.update.pcorp', ['fileId'=>$importFile->id, 'pCorpId' => $pCorp->id]) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT" />
            <div class="row mx-0">
                <div class="align-items-center border-top custom-border custom-checkbox d-flex justify-content-center w-10 w-sm-5">
                    <input id="progCheck_{{ $pCorp->id }}" class="custom-control-input progCheck"  pCorpId="{{ $pCorp->id }}" type="checkbox">
                    <label class="custom-control-label custome-label" for="progCheck_{{ $pCorp->id }}"><span
                                                class="d-none">a</span></label>
                    <input class="d-none" tabindex="-1">
                </div>
                <div class="w-65 w-sm-85 px-0 custom-border border-left border-top">
                    <div class="row mx-0">
                        <div class="col-xl-6 px-1 py-2">
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">
                                    @lang('progress_management.company_name')
                                </label>
                                <div class="col-sm-9 col-form-label mb-1">
                                    <input type="hidden" id="corp_name{{ $pCorp->id }}" name="official_corp_name" value="{{ isset($pCorp->mCorp) ? $pCorp->mCorp->official_corp_name : '' }}">
                                    <a target="_blank" href="{{ route('get.progress.management.admin_demand_detail', $pCorp->id) }}" class="highlight-link">
                                        {{ isset($pCorp->mCorp) ? $pCorp->mCorp->official_corp_name : '' }}
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">TEL</label>
                                <div class="col-sm-9 col-form-label mb-1">
                                    <a href="{{ isset($pCorp->mCorp) ? checkDevice().$pCorp->mCorp->commission_dial : '' }}" class="highlight-link">{{ isset($pCorp->mCorp) ? $pCorp->mCorp->commission_dial : '' }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.holiday')</label>
                                <div class="col-sm-9 col-form-label mb-1">
                                    <span>{{ $pCorp->holidays }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.unit_price')</label>
                                <div class="col-sm-9 col-form-label mb-1">
                                    <span>{{ $pCorp->unit_cost }}@lang('progress_management.yen')</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-form-label font-weight-bold">@lang('progress_management.mail')</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <label class="col-3 col-sm-2 col-form-label">{{ $pCorp->mail_count }}@lang('progress_management.times')</label>
                                        <input class="form-control col-8 col-sm-5 mb-1 update" name="mail_address" value="{{ $pCorp->mail_address }}" id="mail_address{{ $pCorp->id }}" pCorpId="{{ $pCorp->id }}">
                                        <div class="col-sm-5 text-center">
                                            <input type="hidden" value="{{ route('progress.corpIndex.progcorp.email', ['pCorpId' => $pCorp->id, 'fileId' => $fileId]) }}" id="indexMailUrl{{ $pCorp->id }}"/>
                                            <button  pCorpId="{{ $pCorp->id }}" class="btn btn--gradient-orange remove-effect-btn col-12 indexMailButton">@lang('progress_management.mail')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-form-label font-weight-bold">FAX</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <label class="col-3 col-sm-2 col-form-label">{{ $pCorp->fax_count }}@lang('progress_management.times')</label>
                                        <input  pCorpId="{{ $pCorp->id }}" class="form-control col-8 col-sm-5 mb-1 update inputFax" name="fax" id="fax{{ $pCorp->id }}" value="{{ $pCorp->fax }}">
                                        <div class="col-sm-5 text-center">
                                            <input type="hidden" value="{{ route('progress.corpIndex.progcorp.fax', [ 'pCorpId' =>$pCorp->id, 'fileId' => $fileId ]) }}" id="indexFaxButton{{ $pCorp->id }}" />
                                            <button  pCorpId="{{ $pCorp->id }}" class="btn btn--gradient-orange remove-effect-btn col-12 indexFaxButton">FAX</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <label class="col-form-label font-weight-bold">@lang('progress_management.email_lastest_sent')</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <label class="col-form-label col-7">
                                            {{ $pCorp->mail_last_send_date }}
                                        </label>
                                        <div class="col-sm-5 text-center">
                                            <input type="hidden" value="{{ route('progress.corpIndex.progcorp.fax-mail', ['pCorpId' => $pCorp->id, 'fileId' => $fileId]) }}" id="indexMailFaxButton{{ $pCorp->id }}">
                                            <button  pCorpId="{{ $pCorp->id }}"  class="btn btn--gradient-orange remove-effect-btn col-12 indexMailFaxButton">@lang('progress_management.mail') + FAX</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <label class="col-form-label font-weight-bold">@lang('progress_management.fax_lastest_sent')</label>
                                </div>
                                <div class="col-sm-9">
                                    <label class="col-form-label">{{ empty($pCorp->fax_last_send_date) ? '' : $pCorp->fax_last_send_date }}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <label class="col-form-label font-weight-bold">@lang('progress_management.after_16')</label>
                                </div>
                                <div class="col-sm-9">
                                    <label class="col-form-label">
                                        {{
                                            empty($pCorp->rev_mail_count) ? __('progress_management.none') :
                                            $pCorp->rev_mail_count . __('progress_management.times')
                                        }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 pr-0">
                                    <label class="col-form-label font-weight-bold">@lang('progress_management.irgular_content')</label>
                                </div>
                                <div class="col-sm-9">
                                    <label class="col-form-label">{{ isset($pCorp->mCorp) ? $pCorp->mCorp->prog_irregular : '' }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 px-1 py-2">
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.progress_flg')</label>
                                <div class="col-sm-9">
                                    <select class="form-control update" id="progress_flag{{ $pCorp->id }}" name="progress_flag"  pCorpId="{{ $pCorp->id }}">
                                        <option value="0">--@lang('progress_management.none')--</option>
                                        @foreach($progressFlagList as $key => $val)
                                        <option value="{{ $key }}" {{ $key == $pCorp->progress_flag ? ' selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.collection_date')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datetimepicker update" name="collect_date" pCorpId="{{ $pCorp->id }}"
                                    value="{{ !empty($pCorp->collect_date) ? date('Y/m/d H:i', strtotime($pCorp->collect_date)) : '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.result_return')</label>
                                <div class="col-sm-9">
                                    <select class="form-control update" pCorpId="{{ $pCorp->id }}" name="not_replay_flag" id="not_replay_flag{{ $pCorp->id }}">
                                        <option value="0">--@lang('progress_management.none')--</option>
                                        @foreach($notReplyFlagList as $key => $val)
                                        <option value="{{ $key }}" {{ $key == $pCorp->not_replay_flag ? ' selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.sending_method')</label>
                                <div class="col-sm-9">
                                    <select class="form-control update" pCorpId="{{ $pCorp->id }}" name="contact_type" id="contact_type{{ $pCorp->id }}">
                                        <option value="0">--@lang('progress_management.none')--</option>
                                        @foreach($contactTypeList as $key => $val)
                                        <option value="{{ $key }}" {{ $key == $pCorp->contact_type ? ' selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.follow_up_tel')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control datetimepicker update" name="call_back_phone_date" id="call_back_phone_date{{ $pCorp->id }}" pCorpId="{{ $pCorp->id }}"
                                    value="{{ !empty($pCorp->call_back_phone_date) ? date('Y/m/d H:i', strtotime($pCorp->call_back_phone_date)) : '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.call_back_phone_flag')</label>
                                <div class="col-sm-9">
                                    <select pCorpId="{{ $pCorp->id }}" class="form-control update" name="call_back_phone_flag" id="call_back_phone_flag{{ $pCorp->id }}">
                                        <option value="0">--@lang('progress_management.none')--</option>
                                        @foreach($callBackPhoneFlag as $key => $val)
                                        <option value="{{ $key }}" {{ $key == $pCorp->call_back_phone_flag ? ' selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label font-weight-bold mb-1">@lang('progress_management.follow_up_history')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control update" pCorpId="{{ $pCorp->id }}" value="{{ $pCorp->note }}" name="note" rows="5" id="note{{ $pCorp->id }}">{{ $pCorp->note }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-25 w-sm-10 px-0 custom-border border-left border-top d-flex align-items-center">
                    <div class="text-center">
                        <button  pCorpId="{{ $pCorp->id }}" id="btn_update{{ $pCorp->id }}" class="btn btn--gradient-orange remove-effect-btn mb-2 px-3 indexUpdateButton">@lang('progress_management.update')
                        </button>
                        <input type="hidden" value="{{ route('progress.corpIndex.progcorp.outcsv', $pCorp->id) }}" id="hidOutcsvUrl{{ $pCorp->id }}">
                        <button pCorpId="{{ $pCorp->id }}" class="btn btn--gradient-orange remove-effect-btn mb-2 px-3 outcsv" id="btn_csv{{ $pCorp->id }}">CSV
                        </button>
                        <input type="hidden" value="{{ route('progress.corpIndex.progcorp.outpdf', $pCorp->id) }}" id="hidOutPdfUrl{{ $pCorp->id }}" />
                        <button pCorpId="{{ $pCorp->id }}" id="{{ $pCorp->id }}" class="btn btn--gradient-orange remove-effect-btn mb-2 px-3 outpdf">PDF
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @endforeach
        <div class="corp-pagination">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center">
                	<span>
                        @lang('progress_management.page_info', [ 'total_row' => $progCorps->total(), 'from' => $from, 'to' => $to, 'total_page' => $progCorps->lastPage(), 'current_page' => $progCorps->currentPage() ])
                	</span>
                </div>
                <div class="col-sm-6 fs-20">
                        {{ $progCorps->appends($searchQuery)->links('pagination.prog_demand') }}
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row my-3">
        <div class="col-lg-4 text-center">
            <input type="hidden" value="{{ route('progress.corpIndex.progcorp.multi-mail', $fileId) }}" id="hidIndexAllUrl"/>
            <button id="indexAllMailButton" class="btn btn--gradient-orange remove-effect-btn col-sm-6 col-lg-12 py-3 mb-2" {{ $progCorps->isEmpty() ? 'disabled' : '' }}>@lang('progress_management.mail_check')
            </button>
        </div>
        <div class="col-lg-4 text-center">
            <input type="hidden" value="{{ route('progress.corpIndex.progcorp.multi-fax') }}" id="hidIndexAllFaxUrl">
            <button id="indexAllFaxButton" class="btn btn--gradient-orange remove-effect-btn col-sm-6 col-lg-12 py-3 mb-2" {{ $progCorps->isEmpty() ? 'disabled' : '' }}>@lang('progress_management.fax_check')
            </button>
        </div>
        <div class="col-lg-4 text-center">
            <input type="hidden" value="{{ route('progress.corpIndex.progcorp.multi-mail-fax', $fileId) }}" id="hidIndexAllMailFaxUrl" />
            <button id="indexAllMailFaxButton" class="btn btn--gradient-orange remove-effect-btn col-sm-6 col-lg-12 py-3 mb-2" {{ $progCorps->isEmpty() ? 'disabled' : '' }}>@lang('progress_management.mail_fax_check')
            </button>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
<script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
<script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
<script src="{{ mix('js/utilities/form.validate.js') }}"></script>
<script>
    var confirm_update_msg = "{{ $confirmUpdateMsg }}",
        mailEmptyMsg = "{{ $mailEmptyMsg }}",
        willSendEmailMsg = "{{ $willSendEmailMsg }}",
        willSendFaxMsg = "{{ $willSendFaxMsg }}",
        faxEmptyMsg = "{{ $faxEmptyMsg }}",
        faxNumberMsg = "{{ $faxNumberMsg }}",
        mailNumberMsg = "{{ $mailNumberMsg }}",
        willSendFaxMail = "{{ $willSendFaxMail }}",
        baseUrl = "{{ $baseUrl }}",
        sendBulkMail = "{{ $sendBulkMail }}",
        sendBulkFax = "{{ $sendBulkFax }}",
        noChecked = "{{ $noChecked }}",
        emailNotEnter = "{{ $emailNotEnter }}",
        faxNotEnter = "{{ $faxNotEnter }}",
        bulkMailFax = "{{ $bulkMailFax }}",
        corpIdNull = "{{ $corpIdNull }}";
        fileId = "{{ $fileId }}";

    FormUtil.validate('#frmCorpSearch');
    FormUtil.validate('.frmPCorpUpdate');
    var validateEmail = "{{ trans("progress_management.validate_email") }}";
</script>
<script type="text/javascript" src="{{ mix('js/utilities/st.common.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/pages/corp_index.js') }}"></script>
@endsection
