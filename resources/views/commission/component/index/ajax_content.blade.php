<div id="temp_display" data-display="{{ $display }}"></div>
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
@else
    <br>
    <div id="message" style="text-align: center">
        @lang('commissioninfos.no_data')
    </div>
@endif
<div id="data-event-calendar" data-event-calendar="{{ !isset($calenderEventData) ? '[]' : $calenderEventData }}"></div>
@component('commission.component.index.modal', [])@endcomponent
