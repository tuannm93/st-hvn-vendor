<div id="dataTotalRow" data-total="{{ $result['total']}}"></div>
@if($result['total'] > 0)
    <div id="search_details" class="custom-scroll-x">
        {{Form::label('',__('affiliation.total_row') . " " . $result['total'] . __('affiliation.row'))}}
        <table class="table custom-border add-pseudo-scroll-bar">
            <thead>
            <tr class="text-center bg-yellow-light">
                <th class="p-1 align-middle fix-w-150">
                    {{__('affiliation.corp_name')}}
                    <span class="sortInner" data-col-sort="corp_name">
                            <i class="triangle-up mx-1 up-priority" aria-hidden="true" tabindex="0"></i>
                            <i class="triangle-down down-priority" aria-hidden="true" tabindex="0"></i>
                        </span>
                </th>
                <th class="p-1 align-middle fix-w-150">
                    {{__('affiliation.corp_name_kana')}}
                    <span class="sortInner" data-col-sort="corp_name_kana">
                            <i class="triangle-up mx-1 up-priority" aria-hidden="true" tabindex="0"></i>
                            <i class="triangle-down down-priority" aria-hidden="true" tabindex="0"></i>
                        </span>
                </th>
                <th class="p-1 align-middle fix-w-150">
                    {{__('affiliation.corp_address')}}
                    <span class="sortInner" data-col-sort="address1">
                            <i class="triangle-up mx-1 up-priority" aria-hidden="true" tabindex="0"></i>
                            <i class="triangle-down down-priority" aria-hidden="true" tabindex="0"></i>
                        </span>
                </th>
                <th class="p-1 align-middle fix-w-120">
                    {{__('affiliation.phone_number')}}
                    <span class="sortInner" data-col-sort="tel1">
                            <i class="triangle-up mx-1 up-priority" aria-hidden="true" tabindex="0"></i>
                            <i class="triangle-down down-priority" aria-hidden="true" tabindex="0"></i>
                        </span>
                </th>
                <th class="p-1 align-middle fix-w-150">
                    {{__('affiliation.corp_status')}}
                    <span class="sortInner" data-col-sort="corp_status">
                            <i class="triangle-up mx-1 up-priority" aria-hidden="true" tabindex="0"></i>
                            <i class="triangle-down down-priority" aria-hidden="true" tabindex="0"></i>
                        </span>
                </th>
                <th class="p-1 align-middle fix-w-120">
                    {{__('affiliation.corp_followup_date')}}
                    <span class="sortInner" data-col-sort="follow_date">
                            <i class="triangle-up mx-1 up-priority" aria-hidden="true" tabindex="0"></i>
                            <i class="triangle-down down-priority" aria-hidden="true" tabindex="0"></i>
                        </span>
                </th>
                <th class="p-1 align-middle fix-w-150">{{__('affiliation.corresponding_work')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($result))
                @foreach($result['data'] as $obj)
                    <tr>
                        <td class="p-1 align-middle ">
                            <a href="{{ route('affiliation.detail.edit', $obj['m_corps_id'])}}"
                               target="_blank" class="highlight-link">{{$obj['m_corps_corp_name'] }}</a>
                        </td>
                        <td class="p-1 align-middle ">{{$obj['m_corps_corp_name_kana']}}</td>
                        <td class="p-1 align-middle ">{{getDivTextJP('prefecture_div',$obj['m_corps_address1'])
                    .$obj['m_corps_address2'].$obj['m_corps_address3'].$obj['m_corps_address4']}}</td>
                        <td class="p-1 align-middle ">{{$obj['m_corps_tel1']}}</td>
                        <td class="p-1 align-middle ">{{$obj['item_name']}}</td>
                        <td class="p-1 align-middle text-center">{{dateTimeFormat($obj['m_corps_follow_date'])}}</td>
                        <td class="p-1 align-middle ">{{$obj['list_category_name']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div data-cur="{{$result['curPage']}}" data-total="{{$result['pageNumber']}}" id="dataPagInfo"></div>
    @if($result['pageNumber'] > 1)
        @component('affiliation.components.affiliation_paginator', ['paginator' => $result])
        @endcomponent
    @endif
    <div class="pseudo-scroll-bar" data-display="false">
        <div class="scroll-bar"></div>
    </div>
@else
    <div class="text-center">{{__('affiliation.message_no_result_search')}}</div>
@endif
