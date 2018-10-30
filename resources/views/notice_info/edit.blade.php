@extends('layouts.app')
@section('content')
    @include($isReadOnly ? 'notice_info.components.edit_with_readonly' : 'notice_info.components.form', [
        'noticeInfo'    => $noticeInfo,
        'listCorps'     => $listCorps,
        'listAnswers'   => $listAnswers
    ])
    <div id="page-data"
        data-loading-ajax-text="{{ __('notice_info_update.loading_ajax_text') }}"
        data-loading-ajax-fail-text="{{ __('notice_info_update.loading_ajax_fail_text') }}"
        data-loading-ajax-success="{{ __('notice_info_update.ajax_message_search_aff') }}"
        data-alert-choose-corps="{{ __('notice_info_update.alert_choose_corp') }}"
        data-confirm-del="{{ __('notice_info_update.confirm_del') }}"
        data-route-notice-index="{{ route('notice_info.index') }}"
        data-route-notice-edit="{{ route('notice_info.edit', ['noticeId' => $noticeInfo->exists ? $noticeInfo->id : '']) }}"
        data-route-get-list-aff="{{ route('notice_info.affiliation_list') }}"
        data-route-remove-notice="{{ route('notice_info.remove', ['noticeId' => $noticeInfo->exists ? $noticeInfo->id : '']) }}"
        data-route-download-csv="{{ route('notice_info.download_csv_answer', ['noticeId' => $noticeInfo->exists ? $noticeInfo->id : '']) }}"
    ></div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/notice_info_edit.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script>
        FormUtil.validate('#form-notice');
    </script>
@endsection
