@inject('service', 'App\Services\NoticeService')

@php
    $sort = $detailSort['sort'];
    $order = $detailSort['orderByDisplay'];
@endphp
@if (!$isRoleAffiliation)
    <button class="btn btn--gradient-orange mt-4" id="regist" data-url="{{ route('notice_info.edit') }}">@lang('notice_info.regist_new')</button>
@endif
<div class="row mx-0 mt-2">
    <div class="col-lg-3 px-0">
        @lang('notice_info.total') {{ $results->total() }}@lang('notice_info.item') @lang('notice_info.current') {{ $results->firstItem() }} @lang('notice_info.from_to_mapping') {{ $results->lastItem() }} @lang('notice_info.display')
    </div>
    <div class="col-lg-9 px-0 text-lg-right">
        @if ($isRoleAffiliation)
            <img src="{{ asset('img/important_icon.jpg') }}" class="img-animation"> <strong class="fs-15">@lang('notice_info.important_information')</strong>
        @endif
    </div>
</div>
@if ($linkDisplay)
<div class="items-in-page text-center">
    <a href="{{ route('trader.index') }}">@lang('notice_info.suggest_today')</a>
</div>
@endif
<div class="custom-scroll-x">
    <table class="table custom-border add-pseudo-scroll-bar">
        <thead>
            <tr class="text-center bg-yellow-light">
                @foreach($arrayListItemSort as $item)
                <th class="p-1 align-middle">
                    <a href="" class="sort-item text-dark text--underline">
                        {{ $item['text'] }}
                        @php
                            $sortInfor = $service->getInforOrderSort($order, $sort, $item['value']);
                        @endphp
                        @if ($sortInfor['is_active'] && empty($isGet))
                            @if ($sortInfor['is_asc'])
                            <span data-sort="{{ $item['value'] }}-desc">{{ trans('common.asc') }}</span>
                            @else
                            <span data-sort="{{ $item['value'] }}-asc">{{ trans('common.desc') }}</span>
                            @endif
                        @else
                            <span data-sort="{{ $item['value'] }}-desc"></span>
                        @endif
                    </a>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
            <tr class="text-center">
                <td class="p-1 align-middle fix-w-50">{{ $item->id }}</td>
                <td class="p-1 align-middle text-left">
                    @if ($isRoleAffiliation && in_array($item->id, config('rits.notice_info_important_ids')))
                        <img src="{{ asset('img/important_icon.jpg') }}" class="img-animation">
                    @endif
                    @if ($isRoleAffiliation)
                        <a href="{{ route('notice_info.detail', ['id' => $item->id]) }}" class="highlight-link text--underline">{{ $item->info_title }}</a>
                    @else
                        <a href="{{ route('notice_info.edit', ['id' => $item->id]) }}" class="highlight-link text--underline">{{ $item->info_title }}</a>
                    @endif
                </td>
                <td class="p-1 align-middle fix-w-50 text-left">
                    @if ($isRoleAffiliation)
                        @switch($item->status)
                            @case(1)
                                <span class="text-green font-weight-bold">@lang('notice_info.read')</span>
                                @break
                            @case(2)
                                <span class="text-danger font-weight-bold">@lang('notice_info.unread')</span>
                                @break
                            @case(3)
                                <span class="text-danger font-weight-bold">@lang('notice_info.no_answer')</span>
                                @break
                            @default
                                <span class="text-green font-weight-bold">@lang('notice_info.read')</span>
                                @break
                        @endswitch
                    @else
                        @if ($item->is_target_selected)
                            <span>@lang('notice_info.for_merchant')</span>
                        @else
                            @if ($item->corp_commission_type == null)
                                <span>@lang('notice_info.corporate_agency_form:all')</span>
                            @else
                                <span>@lang('notice_info.corporate_agency_form'){{ $dropListItem[$item->corp_commission_type] }}</span>
                            @endif
                        @endif
                    @endif
                </td>
                <td class="p-1 align-middle">{{ $service->dateTimeWeek($item->created, '%Y/%m/%d(%a) %H:%M') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if ($results->count())
    {{ $results->links('notice_info.components.index_pagination') }}
    @endif
</div>
<div class="pseudo-scroll-bar" data-display="false">
    <div class="scroll-bar"></div>
</div>
