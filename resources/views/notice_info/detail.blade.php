@inject('service', 'App\Services\NoticeService')

@extends('layouts.app')
@section('content')
    <div class="notice-info-detail pt-3">
        @if(session()->has('error_message'))
            <p class="box__mess box--error">{{ session('error_message') }}</p>
        @endif
        @if(session()->has('success_message'))
            <p class="box__mess box--success">{{ session('success_message') }}</p>
        @endif

        <div class="row mx-0 notice-info-badge fs-15 mb-3">
            <div class="col-sm-3 col-lg-auto">
                <label class="col-form-label font-weight-bold">{{ __('notice_info.notice_id') }} : {{ $noticeInfo->notice_infos_id }}</label>
            </div>
            <div class="col-sm-6 col-lg-auto">
                <label class="col-form-label font-weight-bold">{{ __('notice_info.registed_date') }} : {{ formatDateWeek($noticeInfo->notice_infos_created) }}</label>
            </div>
        </div>
        <label class="form-category__label font-weight-normal mt-2 mb-0">{{ __('notice_info.notice_info_title') }}</label>
        <div class="row mx-0 border-bottom">
            <div class="col-md-2 col-sm-3 d-flex align-items-center bg-label">
                <label class="font-weight-bold mb-0 py-2">{{ __('notice_info.notice_title') }}</label>
            </div>
            <div class="col-sm-9 col-md-10">
                <p class="mb-0 py-3 text-wrap">{{ $noticeInfo->info_title }}</p>
            </div>
        </div>
        <div class="row mx-0 border-bottom">
            <div class="col-md-2 col-sm-3 d-flex align-items-center bg-label">
                <label class="font-weight-bold mb-0 py-2">{{ __('notice_info.notice_content') }}</label>
            </div>
            <div class="col-sm-9 col-md-10">
                <p class="mb-0 py-3 text-wrap">{!! nl2br($service->autoLink($noticeInfo->info_contents)) !!}</p>
            </div>
        </div>
        @if(!empty($noticeInfo->choices))
        <div class="row mx-0 border-bottom">
            <div class="col-md-2 col-sm-3 d-flex align-items-center bg-label">
                <label class="font-weight-bold mb-0 py-2">{{ __('notice_info.reply') }}</label>
            </div>
            <div class="col-sm-9 col-md-10">
                @if(!empty($noticeInfo->answer_value))
                    <p class="mb-0 py-3 text-wrap">
                        {{ __('notice_info.label_answered', ['answer' => $noticeInfo->answer_value]) }}
                        {{ __('notice_info.label_answer_date', ['answer_date' => $noticeInfo->answer_date]) }}
                    </p>
                @else
                    <label class="mb-0 py-3">{{ __('notice_info.please_select_an_answer') }}</label>
                    <input type="hidden" id="url-submit-answer">
                    <form id="submit-answer" method="post" action="{{ route('notice_info.answer', ['noticeId' => $noticeInfo->notice_infos_id]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="answer" id="answer">
                    </form>
                    <div class="d-flex flex-column flex-sm-row">
                        @foreach(explode(",", $noticeInfo->choices) as $c)
                            <button class="btn btn--gradient-orange button-answer mb-1 px-5 mr-2 text--white">{{ $c }}</button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif
        <div class="text-center mt-4">
            <button class="btn btn--gradient-gray col-3 col-md-2 back-to-index" data-url="{{ route('notice_info.index') }}">{{ __('notice_info.back') }}</button>
        </div>
    </div>

    <div id="confirm-answer" class="modal modal-notice-info-detail" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold fs-26">{{ __('notice_info.close_tag_L_left') }}<span id="confirm_answer_value"></span>{{ __('notice_info.close_tag_L_right') }}{{ __('notice_info.title_modal_confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body border-bottom">
                    {!! __('notice_info.message_confirm') !!}
                </div>
                <div class="row mt-3 mx-3 py-3 border-top-custom">
                    <div class="col-sm-6 text-center text-sm-right mb-1">
                        <button id="btn-confirm-answer" type="button" class="btn btn--gradient-green col-8">{{ __('notice_info.answer') }}</button>
                    </div>
                    <div class="col-sm-6 text-center text-sm-left mb-1">
                        <button type="button" class="btn btn--gradient-gray col-8" data-dismiss="modal">{{ __('notice_info.dont_answer') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/notice_info.detail.js') }}"></script>
    <script>
        NoticeInfoDetail.init();
    </script>
@endsection
