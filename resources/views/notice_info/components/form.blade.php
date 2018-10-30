@inject('service', 'App\Services\NoticeService')

<div class="notice-info-edit-readonly">
    <div class="text-sm-right">
        <button class="btn btn--gradient-gray remove-effect-btn col-5 col-sm-2 back-to-index" data-url="{{ route('notice_info.index') }}" type="button">{{ __('notice_info_update.btn_back') }}</button>
        @if($noticeInfo->exists)
        <button class="btn btn--gradient-gray remove-effect-btn col-5 col-sm-2" id="btn-remove-notice">{{ __('notice_info_update.btn_del') }}</button>
        @endif
    </div>
    {{ Form::model($noticeInfo, ['route' => ($noticeInfo->exists) ? ['notice_info.update'] : ['notice_info.create'], 'id' => 'form-notice']) }}
        <input type="hidden" name="del_flg" value="0" id="del_flg">
    @if(!empty(session('error_message')))
        <div class="box__mess box--error my-1">
            {{ session('error_message') }}
        </div>
    @endif
    @if($errors->any())
        <div class="box__mess box--error my-1">
            {{ __('notice_info_update.please_check_the_input_item') }}
        </div>
    @endif
    @if(!empty(session('success_message')))
        <div class="box__mess box--success my-1">
            {{ session('success_message') }}
        </div>
    @endif
    @if($noticeInfo->exists)
    <div class="row mx-0 notice-info-badge my-3">
        <div class="col-sm-6 col-lg-auto">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.notice_id_label') }}：{{ $noticeInfo->id }}</label>
        </div>
        <div class="col-sm-6 col-lg-auto">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.register_date_label') }}： {{ dateTimeWeek($noticeInfo->created) }}</label>
        </div>
    </div>
    @endif
    <label class="form-category__label font-weight-normal mt-2">{{ __('notice_info_update.notice_title_label') }}</label>
    <div class="text-right">
        <label class="col-form-label"><span class="text-danger">* </span>({{ __('notice_info_update.require_label') }})</label>
    </div>
    @if($noticeInfo->exists)
        <input type="hidden" name="notice_id" value="{{ $noticeInfo->id }}">
    @endif
    <div class="row mb-2">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.subject') }} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-10">
            {!! Form::text('info_title', null, ['class' => 'form-control', 'id' => 'title-post', 'maxlength' => '100', 'data-rule-required' => 'true']) !!}
            @if($errors->has('info_title'))
                <span class="text-danger">
                    {{ $errors->first('info_title') }}
                </span>
            @endif
        </div>
    </div>
    <div class="row mb-2">
        <div class="align-items-center col-sm-2 d-flex flex-sm-row-reverse">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.content') }} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-10">
            {!! Form::textarea('info_contents', null, ['class' => 'form-control', 'id' => 'content-post', 'maxlength' => '20000', 'data-rule-required' => 'true']) !!}
            @if($errors->has('info_contents'))
                <span class="text-danger">
                    {{ $errors->first('info_contents') }}
                </span>
            @endif
        </div>
    </div>
    <div class="row mb-2">
        <div class="offset-sm-2 col-sm-10">
            <span>{{ __('notice_info_update.note_url') }}</span>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.display_target') }}</label>
        </div>
        <div class="col-sm-10">
            <div class="custom-control custom-radio custom-control-inline">
                {{ Form::radio('target', '1', !$noticeInfo->is_target_selected, ['class' => 'custom-control-input ignore', 'id' => 'targetDisp1']) }}
                <label class="custom-control-label" for="targetDisp1">{{ __('notice_info_update.designated_in_corporate_transaction_form') }}</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                {{ Form::radio('target', '2', $noticeInfo->is_target_selected, ['class' => 'custom-control-input ignore', 'id' => 'targetDisp2']) }}
                <label class="custom-control-label" for="targetDisp2">{{ __('notice_info_update.select_a_merchant_store') }}</label>
            </div>
        </div>
    </div>
    <div id="tr-disp-1" class="row mb-2 tr-disp">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.corporate_brokerage_form') }}</label>
        </div>
        <div class="col-sm-4 col-lg-2">
            {{ Form::select('corp_commission_type', $service->formatCorpCommissionType($listItemNotice, '全て'),
                null,['class' => 'form-control']
            ) }}
        </div>
    </div>
    <div id="tr-disp-2" class="row mb-2 tr-disp">
        <div class="offset-sm-2 col-sm-10">
            {{ Form::select('target_corp_ids[]', isset($listCorps) ? $listCorps->mapWithKeys(function($item) { return ($item->corp_id && $item->corp_name) ? [$item->corp_id => $item->corp_name] : [] ;}) : [], null, ['class' => 'form-control', 'id'    => 'choose-affs', 'multiple'  => true, 'size'  => 10]
            ) }}
        </div>
        <div class="offset-sm-2 col-sm-10 my-2">
            <button id="btn-move-up" class="btn btn--gradient-orange remove-effect-btn" type="button">
                {{ __('notice_info_update.btn_move_up') }}
            </button>
            <button id="btn-move-down" class="btn btn--gradient-orange remove-effect-btn" type="button">
                {{ __('notice_info_update.btn_move_down') }}
            </button>
        </div>
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.franchise_store') }}</label>
        </div>
        <div class="col-sm-10 mb-2">
            {{ Form::select('corp_commission_type', [], null, ['class' => 'form-control', 'id' => 'list-choose-affs', 'multiple' => true, 'size' => 10,]) }}
        </div>
        <div class="offset-sm-2 col-sm-10 mb-2">
            <div class="row mx-0">
                <div class="col-sm-4 col-xl-2 mb-2 mb-xl-0 pl-0">
                    <select id="search_aff_type" class="form-control mb-2 mb-sm-0">
                        <option value="search_by_name">{{ __('notice_info_update.search_by_corp_name') }}</option>
                        <option value="search_by_id">{{ __('notice_info_update.search_by_corp_id') }}</option>
                    </select>
                </div>
                <div class="col-md-9 pl-0">
                    <input data-placeholder-search-aff="{{ __('notice_info_update.placeholder_search_aff_with_id') }}" id="search_aff" class="form-control mb-2" placeholder="{{ __('notice_info_update.placeholder_search_aff') }}">
                </div>
                <div class="col-md-1 px-0">
                    <button class="btn btn--gradient-orange remove-effect-btn" type="button" id="get_list_aff">{{ __('notice_info_update.btn_search_aff') }}</button>
                </div>
            </div>
            <div class="ajax-message">
                <span id="ajax-message"></span>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.approve_to_franchisees') }}</label>
        </div>
        <div class="col-sm-10">
            <div class="custom-control custom-radio custom-control-inline">
                {{ Form::radio('request-answer', '0', empty($noticeInfo->choices), ['class' => 'custom-control-input ignore', 'id' => 'require_answerAnswer0']) }}
                <label class="custom-control-label" for="require_answerAnswer0">{{ __('notice_info_update.do_not_request') }}</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                {{ Form::radio('request-answer', '1', !empty($noticeInfo->choices), ['class' => 'custom-control-input ignore', 'id' => 'require_answerAnswer1']) }}
                <label class="custom-control-label" for="require_answerAnswer1">{{ __('notice_info_update.to_request') }}</label>
            </div>
        </div>
    </div>
    <div id="input-request-answer" class="row mb-2">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.option_choice') }}</label>
        </div>
        <div class="col-sm-10">
            {!! Form::text('choices', empty($noticeInfo->choices) ? __('notice_info_update.default_option') : $noticeInfo->choices, ['class' => 'form-control', 'id' => 'choices-notice', 'maxlength' => 200]) !!}
            @if($errors->has('choices'))
                <span class="text-danger">
                    {{ $errors->first('choices') }}
                </span>
            @endif
        </div>
    </div>
    <div class="mb-2 text-center">
        <button class="btn btn--gradient-gray col-sm-3 col-xl-2 mb-2 mb-sm-0 mr-sm-2 remove-effect-btn back-to-index" data-url="{{ route('notice_info.index') }}" type="button">{{ __('notice_info_update.btn_back') }}</button>
        <button id="btn-show-confirm" class="btn btn--gradient-green remove-effect-btn col-sm-3 col-xl-2" type="button">{{ __('notice_info_update.btn_registration') }}</button>
    </div>
    {{ Form::close() }}
    <div>
        @if (!empty($noticeInfo->choices))
            @include('notice_info.components.table_answer', [
                'listAnswers'   => $listAnswers
            ])
        @endif
    </div>
</div>
<div class="modal modal-notice-info-detail" id="target_confirm" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-26 font-weight-bold">
                    {{ __('notice_info_update.below') }} 
                    <span id="target_confirm_count"></span>
                    {{ __('notice_info_update.postfix_title_modal') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body border-bottom">
                <ul id="target_confirm_list"></ul>
            </div>
            <div class="row mt-5 mx-3 py-3 border-top-custom">
                <div class="col-sm-6 text-center text-sm-right mb-1">
                    <button id="submit-form" type="button" class="btn btn--gradient-green remove-effect-btn">{{ __('notice_info_update.btn_submit_form') }}</button>
                </div>
                <div class="col-sm-6 text-center text-sm-left mb-1">
                    <button type="button" class="btn btn--gradient-gray remove-effect-btn" data-dismiss="modal">{{ __('notice_info_update.btn_do_not_regiter') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
