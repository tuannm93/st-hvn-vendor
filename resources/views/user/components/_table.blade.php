<div class="user-search custom-scroll-x">
    @if($results->total() == 0)
        <p class="text-center">@lang("user_search.no_record")</p>
    @else
        <div class="table-result-search mt-2">
            <span id="dataTotalRow" data-total="{{$results->total()}}">@lang("user_search.total_result", ["count" => $results->total()])</span>
            <table class="table table-bordered mt-1">
                <thead>
                <tr>
                    <th class="text-center p-1">@lang("user_search.col1")</th>
                    <th class="text-center p-1">@lang("user_search.col2")</th>
                    <th class="text-center p-1">@lang("user_search.col3")</th>
                    <th class="text-center p-1">@lang("user_search.col4")</th>
                </tr>
                </thead>
                <tbody>
                @foreach($results as $result)
                    <tr>
                        <td class="p-1">
                            @if(!$checkSysAdmin && $result->auth != "affiliation")
                                {{$result->user_name}}
                            @else
                                <a class="highlight-link" href="{{route("user.edit", ["id" => $result->id])}}">{{$result->user_name}}</a>
                            @endif
                        </td>
                        <td class="p-1">
                            <a class="highlight-link" href="{{route("affiliation.detail.edit", ["id" => $result->affiliation_id])}}">{{$result->official_corp_name}}</a>
                        </td>
                        <td class="p-1">
                            {{$authList[$result->auth]}}
                        </td>
                        <td class="p-1 text-center">
                            {{dateTimeFormat($result->last_login_date)}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div data-cur="{{$results->currentPage()}}" data-total="{{$results->lastPage()}}" id="dataPagInfo"></div>
            @if($results->total() > $results->perPage())
                <div>
                    @if($results->previousPageUrl())
                        <a class="highlight-link" href="javascript:void(0)" id="btnPreviousListUser">@lang('user_search.prev_page')</a>
                    @else
                        <span>@lang('user_search.prev_page')</span>
                    @endif
                    @if($results->nextPageUrl())
                        <a class="ml-3 highlight-link" href="javascript:void(0)" id="btnNextListUser">@lang('user_search.next_page')</a>
                    @else
                        <span>@lang('user_search.next_page')</span>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
