@php
    $disabledNext = ($data['pageNumber'] == $data['curPage']) ? true : false;
    $disabledPrevious = $data['curPage'] == 1 ? true : false;
@endphp
@if($data['total'] > 0)
    <div class="custom-scroll-x mt-2">
        <table class="table custom-border add-pseudo-scroll-bar">
            <thead>
            <tr class="text-center bg-yellow-light">
                <th class="p-1 fix-w-100 align-center">{{__('report_reputation_follow.corp_id')}}</th>
                <th class="p-1 fix-w-200 align-center">{{__('report_reputation_follow.corp_name')}}</th>
                <th class="p-1 fix-w-200 align-center">{{__('report_reputation_follow.last_update')}}</th>
                <th class="p-1 fix-w-50 align-center">{{__('report_reputation_follow.schedule_month')}}</th>
                <th class="p-1 fix-w-100 align-center">{{__('report_reputation_follow.process_dial')}}</th>
                @if($data['bAllowShowUpdate'])
                    <th class="p-1 align-center">{{__('report_reputation_follow.confirm')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if(!empty($data['listCorp']))
                @foreach($data['listCorp'] as $obj)
                    <tr class="bg-hover-row">
                        <td class="p-1 text-center align-center">
                            <a href="{{route('affiliation.detail.edit', $obj['id'])}}" target="_blank"
                               class="highlight-link">{{$obj['id']}}</a>
                        </td>
                        <td class="p-1 align-center">{{$obj['official_corp_name']}}</td>
                        <td class="p-1 align-center">{{$obj['last_reputation_date']}}</td>
                        <td class="p-1 text-center align-center">{{$obj['schedule_month']}}</td>
                        <td class="p-1 text-center align-center">
                            <a href="{{checkDevice().$obj['commission_dial']}}"
                               class="highlight-link">{{$obj['commission_dial']}}</a>
                        </td>
                        @if($data['bAllowShowUpdate'])
                            <td class="p-1 text-center align-center">
                                <input type="hidden" class="idCorp" value="{{$obj['id']}}"/>
                                <input type="checkbox" class="idCorpCheck" name="checkUpdate" id="" title="">
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div data-cur="{{$data['curPage']}}" data-total="{{$data['pageNumber']}}" id="dataPagInfo"></div>
    <div class="pseudo-scroll-bar" data-display="false">
        <div class="scroll-bar"></div>
    </div>
    @if($data['pageNumber'] > 1)
        <div class="pagination">
        <span id="btnPrevious" class="btn-pagination mr-5 {{ !$disabledPrevious ? "item-pagination active": ""}}"
            {{$disabledPrevious ? "disabled": ""}}>{{__('report_reputation_follow.btn_previous')}}
        </span>
            <span id="btnNext" class="btn-pagination {{!$disabledNext ? "item-pagination active": ""}}"
                {{$disabledNext ? "disabled": ""}}>{{__('report_reputation_follow.btn_next')}}
        </span>
        </div>
    @endif
@else
    <div class="text-center">{{__('report_reputation_follow.message_no_result_search')}}</div>
@endif
