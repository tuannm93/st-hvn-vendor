@extends('layouts.app')
@section('content')
    <div class="commission-index">
        @component('commission.component.index.alert')
        @endcomponent
        {{--Block 1: Notice--}}
        @if(!$isMobile)
            @component('commission.component.index.notice', [
            'isRoleAffiliation' => $isRoleAffiliation,
            'notEnough' => $notEnough,
            'corpLastUpdateProfile' => $corpLastUpdateProfile,
            'corpLastUpdateCategories' => $corpLastUpdateCategories,
            'corpLastUpdateArea' => $corpLastUpdateArea
       ])
            @endcomponent
        @endif
        {{--Block 2: Search Form--}}
        <h2 class="commission_title"> @lang("commissioninfos.lbl.search_box_title")</h2>
        <div class="border-left-black d-none d-sm-block">@lang("commissioninfos.lbl.search_box_title2")</div>
        <a class="headline d-sm-none d-block p-3" data-toggle="collapse" href="#searchBlockCollapse" role="button"
           aria-expanded="false" aria-controls="searchBlockCollapse">
            @lang("commissioninfos.lbl.search_box_title2")
            <span class="fa fa-caret-down float-right"></span>
        </a>
        @php
            $searchComponent = ($isMobile) ? 'commission.component.index.m_search_form' : 'commission.component.index.search_form';
        @endphp
        @component($searchComponent, [
           'params' => ($affiliationId !== null) ? ['affiliationId' => $affiliationId] : [],
           'lastSearchData' => $lastSearchData,
           'genres' => $genres,
           'siteList' => $siteList,
           'display' => $display
       ])
        @endcomponent
        {{--Block 3 Addition--}}
        @component('commission.component.index.addition', [
            'isRoleAffiliation' => $isRoleAffiliation,
            'isMobile' => $isMobile
        ])
        @endcomponent

        {{--Block 4 Calendar--}}
        @component('commission.component.index.calendar', [
            'isRoleAffiliation' => $isRoleAffiliation,
            'isMobile' => $isMobile
        ])
        @endcomponent

        {{--Block 5: Table--}}
        <div id="commissionTableSearch" data-url="{{ route('commission.postSearch', ['affiliationId' => $affiliationId]) }}">
        @if(count($results)>0)
            <div class="headline p-3 d-sm-none">
                <strong>@lang('affiliation_detail.commission_link')</strong>
            </div>
            <div class="d-block d-sm-none p-2">
                @lang('commissioninfos.lbl.info_table_1') <span
                        id="totalItem">{{$results->total()}}</span> @lang('commissioninfos.lbl.info_table_2')
                @if($isMobile)
                    @lang('commissioninfos.lbl.info_table_mobile')
                    <br>
                @endif
                <span>@lang('commissioninfos.lbl.info_table_3') <span
                            id="totalItemNotRead">{{$totalItemNotRead}}</span>@lang('commissioninfos.lbl.info_table_4') </span>
            </div>
            <div class="border-left-black info-table d-none d-sm-block">@lang('commissioninfos.lbl.info_table_1') <span
                        id="totalItem">{{$results->total()}}</span> @lang('commissioninfos.lbl.info_table_2')
                @if($isMobile)
                    @lang('commissioninfos.lbl.info_table_mobile')
                    <br>
                @endif
                <span>@lang('commissioninfos.lbl.info_table_3') <span
                            id="totalItemNotRead">{{$totalItemNotRead}}</span>@lang('commissioninfos.lbl.info_table_4') </span>
            </div>

            <div class="headline text-dark p-1 d-sm-none">
                <strong>@lang('commissioninfos.lbl.demand_attachment_file')</strong>
            </div>
            <div class="display_file_wrap p-2">
                <img src="{{asset('/img/file_ico.jpg')}}" alt="@lang('commissioninfos.lbl.display_file')"
                     title="@lang('commissioninfos.lbl.display_file')"
                     width="15">：@lang('commissioninfos.lbl.display_file')
            </div>

            <div class="headline text-dark p-1 d-sm-none">
                <strong>@lang('commissioninfos.lbl.filter_header')</strong>
            </div>
            @if($isMobile)
                @include('commission.component.index.m_table')
            @else
                @include('commission.component.index.table')
            @endif
        @endif
        @component('commission.component.index.modal', [])@endcomponent
        </div>
    </div>
@endsection
@section('script')
    <script>
                @if($isMobile)
        var CALS_ID = ["cell-cal"];
        var IS_MOBILE = true;
                @else
        var CALS_ID = ["cell-cal1", "cell-cal2", "cell-cal3"];
        var IS_MOBILE = false;
                @endif
        var LBL_DEMAND_ID = '@lang("commissioninfos.lbl.demand_id")';
        var LBL_CUSTOMER_NAME = '@lang("commissioninfos.lbl.customer_name")';
        var LBL_SITE_ID = '@lang("commissioninfos.lbl.site_id")';
        var COMMISSION_DETAIL_URL = '{{route('commission.detail',['id' => 'commission_id'])}}';
        var AJAX_CALENDAR_URL = '{{route('ajax.get.calender.view')}}';
        var currentDate = new Date();
        var eventDate = '{!! !isset($calenderEventData)  ? '[]' : $calenderEventData !!}';
        var un_select = '@lang('auto_commission_corp.none')';
        var check_all = '@lang('auto_commission_corp.check_all')';
        var un_check_all = '@lang('auto_commission_corp.un_check_all')';
    </script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/commission-ajax.js') }}"></script>
    <script src="{{ mix('js/pages/commission/commission.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script>
        FormUtil.validate('#searchForm');
    </script>
@endsection
