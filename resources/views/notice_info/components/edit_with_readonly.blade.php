<div class="notice-info-edit">
    <div class="row mt-3">
    {{ Form::model($noticeInfo, ['route' => ($noticeInfo->exists) ? ['notice_info.update'] : ['notice_info.create'], 'id' => 'form-notice']) }}
    {{ Form::close() }}
        <div class="col-sm-6 offset-sm-6 text-center text-sm-right">
            <button class="btn btn--gradient-gray col-5 col-sm-4 back-to-index" data-url="{{ route('notice_info.index') }}">{{ __('notice_info_update.btn_back') }}</button>
            <button class="btn btn--gradient-gray col-5 col-sm-4" id="btn-remove-notice">{{ __('notice_info_update.btn_del') }}</button>
        </div>
    </div>
    @if($errors->any())
        <div class="box__mess box--error my-1">
            {{ __('notice_info_update.please_check_the_input_item') }}
        </div>
    @endif
    <div class="row mx-0 notice-info-badge my-3">
        <div class="col-sm-4 col-xl-2">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.notice_id_label') }}：{{ $noticeInfo->id }}</label>
        </div>
        <div class="col-sm-8 col-xl-4">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.register_date_label') }}： {{ dateTimeWeek($noticeInfo->created) }}</label>
        </div>
        <div class="col-xl-4">
            <label class="col-form-label font-weight-bold text-danger">{{ __('notice_info_update.readonly_label') }}</label>
        </div>
    </div>
    <label class="form-category__label font-weight-normal mt-2">{{ __('notice_info_update.notice_title_label') }}</label>
    <div class="text-right">
        <label class="col-form-label"><span class="text-danger">* </span>({{ __('notice_info_update.require_label') }})</label>
    </div>
    <div class="form-group row">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold">{{ __('notice_info_update.subject') }} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-10">
            <div class="col-form-label p-1 border-dash">{{ $noticeInfo->info_title }}</div>
        </div>
    </div>
    <div class="form-group row">
        <div class="align-items-center col-sm-2 d-flex flex-sm-row-reverse">
            <label class="font-weight-bold">{{ __('notice_info_update.content') }} <span class="text-danger">*</span></label>
        </div>
        <div class="col-sm-10">
            <div class="col-form-label p-1 border-dash">{!! nl2br($noticeInfo->info_contents) !!}</div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold py-1">{{ __('notice_info_update.display_target') }}</label>
        </div>
        <div class="col-sm-10">
            @if($noticeInfo->is_target_selected === false)
            <div class="custom-control custom-radio">
                <input type="radio" id="targetDisp1" class="custom-control-input" name="target" checked>
                <label class="custom-control-label" for="targetDisp1">{{ __('notice_info_update.designated_in_corporate_transaction_form') }}</label>
            </div>
            @else
            <div class="custom-control custom-radio">
                <input type="radio" id="targetDisp2" class="custom-control-input" name="target" checked>
                <label class="custom-control-label" for="targetDisp2">{{ __('notice_info_update.select_a_merchant_store') }}</label>
            </div>
            @endif
        </div>
    </div>
    @if($noticeInfo->is_target_selected === false)
    <div class="form-group row">
        <div class="align-items-center col-sm-2 d-flex flex-sm-row-reverse">
            <label class="font-weight-bold">{{ __('notice_info_update.corporate_brokerage_form') }}</label>
        </div>
        <div class="col-sm-10">
            <div class="col-form-label p-1 border-dash">
                    <div>全て</div>
                @foreach($listItemNotice as $item)
                    <div>{{ $item }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @if($noticeInfo->is_target_selected)
    <div class="form-group row">
        <div class="align-items-center col-sm-2 d-flex flex-sm-row-reverse">
            <label class="font-weight-bold">{{ __('notice_info_update.franchise_store') }}</label>
        </div>
        <div class="col-sm-10">
            <div class="col-form-label p-1 border-dash">
                @foreach($listCorps as $c)
                    <div>{{ $c->corp_name }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <div class="col-sm-2 text-sm-right">
            <label class="col-form-label font-weight-bold py-1">{{ __('notice_info_update.approve_to_franchisees') }}</label>
        </div>
        <div class="col-sm-10">
            <div class="custom-control custom-radio">
                @if($noticeInfo->choices)
                    <input type="radio" id="request" class="custom-control-input" checked/>
                    <label class="custom-control-label" for="request">{{ __('notice_info_update.to_request') }}</label>
                @else
                    <input type="radio" id="request" class="custom-control-input" checked/>
                    <label class="custom-control-label" for="request">{{ __('notice_info_update.do_not_request') }}</label>
                @endif
            </div>
        </div>
    </div>
    @if($noticeInfo->choices)
    <div class="form-group row">
        <div class="align-items-center col-sm-2 d-flex flex-sm-row-reverse">
            <label class="font-weight-bold">{{ __('notice_info_update.option_choice') }}</label>
        </div>
        <div class="col-sm-10">
            <div class="col-form-label p-1 border-dash">
                @empty(preg_replace('/(^\{)|(\}$)/', '', $noticeInfo->choices))
                    {{ __('notice_info_update.default_option') }}
                @else
                    {{ preg_replace('/(^\{)|(\}$)/', '', $noticeInfo->choices) }}
                @endempty
            </div>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10 text-center">
            <button class="btn btn--gradient-gray col-sm-3 back-to-index" data-url="{{ route('notice_info.index') }}">{{ __('notice_info_update.btn_back') }}</button>
        </div>
    </div>
    <div>
        @if (!empty($noticeInfo->choices))
            @include('notice_info.components.table_answer', [
                'listAnswers'   => $listAnswers
            ])
        @endif
    </div>
</div>
