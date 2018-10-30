@extends('layouts.app')
@php
    // Get some old check value
    $oldStopCategory = old('data.stop_category');
    $oldHoliday = old('data.m_corp_subs.holiday');
    $oldSupport24hour = old('data.m_corps.support24hour');
    $oldAvailableTimeOther = old('data.m_corps.available_time_other');
    $oldContactSupport24hour = old('data.m_corps.contactable_support24hour');
    $oldContactTimeOther = old('data.m_corps.contactable_time_other');
    $oldSupportLanguageEn = old('data.m_corps.support_language_en');
    $oldSupportLanguageZh = old('data.m_corps.support_language_zh');
    $oldDevelopmentResponse = old('data.m_corp_subs.development_response');

    $oldFollowPerson = old('data.m_corps.follow_person');
    $oldRitsPerson = old('data.m_corps.rits_person');
    $oldResponders = old('data.affiliation_correspond.responders');
    $correspondingContents = old('data.affiliation_correspond.corresponding_contens');

    // Set old check value
    $stopCategory = [];
    if(isset($oldStopCategory)){
        foreach($oldStopCategory as $key => $value) {
            $stopCategory[$value] = $stopCategoryList[$value];
            unset($stopCategoryList[$value]);
        }
    }
    $holidayChecked = isset($oldHoliday) ? $oldHoliday : [];
    $developmentResponseChecked = isset($oldDevelopmentResponse) ? $oldDevelopmentResponse : [];
    $support24hourChecked = isset($oldSupport24hour) ? 'checked' : '';
    $availableTimeOtherChecked = isset($oldAvailableTimeOther) ? 'checked' : '';
    $contactSupport24hourChecked = isset($oldContactSupport24hour) ? 'checked' : '';
    $contactTimeOtherChecked = isset($oldContactTimeOther) ? 'checked' : '';
    $supportLanguageEnChecked = isset($oldSupportLanguageEn) ? 'checked' : '';
    $supportLanguageZhChecked = isset($oldSupportLanguageZh) ? 'checked' : '';
@endphp
@section('content')
    <section class="content container affiliation my-4">
        {{-- Button link view --}}
        @include('affiliation.components.create.button_link')
        {{-- Error placement--}}
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(session($msg))
                <p class="alert alert-{{ $msg }}">
                    {{ session($msg) }}
                    {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
                </p>
            @endif
        @endforeach
        @if ($errors->any())
            <p class="alert alert-danger">@lang('affiliation.input_invalid')</p>
        @endif

        <form id="formAffiliation" method="post" action="{{ route('affiliation.detail.post') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{-- Mcorp view --}}
            @include('affiliation.components.create.m_corps')

            {{-- Affiliation correspond view --}}
            @include('affiliation.components.create.affiliation_correspond')

            {{-- Affiliation info view --}}
            @include('affiliation.components.create.affiliation_infos')
        </form>
    </section>
    <div id="page-data" data-url-search-address="{{ route('ajax.searchAddressByZip') }}"></div>
@endsection

@section('script')
    <script>
        var msg = "@lang('affiliation_detail.delete_affiliation_mess')";
        var confirm = "@lang('support.confirm')";
        var cancel = "@lang('support.cancel')";
    </script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/affiliation.detail.js') }}"></script>
    <script>
        $(document).ready(function () {
            FormUtil.validate('#formAffiliation');
            AffiliationDetail.init();
            Datetime.initForDatepicker();
            Datetime.initForDateTimepicker();
            Datetime.initForTimepicker();
        });
    </script>
@endsection
