@inject('service', 'App\Services\AuctionSettingService')

@php
    $sort = $detailSort['sort'];
    $order = $detailSort['orderByDisplay'];
@endphp

<label class="font-weight-bold fs-15 mt-2">@lang('auction_settings.follow')</label>
<p>@lang('auction_settings.the_total_number_of_cases') {{ $results->total() }}@lang('auction_settings.matter')</p>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                @foreach($arrayListItemSort as $item)
                <td>
                    <a href="" class="sort-item">
                        {{ $item['text'] }}
                        @php
                            $sortInfor = $service->getInforOrderSort($order, $sort, $item['value']);
                        @endphp
                        @if ($sortInfor['is_active'])
                            @if ($sortInfor['is_asc'])
                            <span data-sort="{{ $item['value'] }}-desc">{{ trans('common.desc') }}</span>
                            @else
                            <span data-sort="{{ $item['value'] }}-asc">{{ trans('common.asc') }}</span>
                            @endif
                        @else
                            <span data-sort="{{ $item['value'] }}-asc"></span>
                        @endif
                    </a>
                </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
            <tr class="text-center">
                <td><a href="{{ route('demand.detail', ['id' => $item->id]) }}" class="high-light">{{ $item->id }}</a></td>
                <td>{{ $item->customer_name }}</td>
                <td>{{ $item->follow_tel_date }}</td>
                <td>{!! $item->visit_time !!}</td>
                <td>{{ $item->site_name }}</td>
                <td>{{ $item->category_name }}</td>
                <td><a href="{{ checkDevice().$item->tel1 }}">{{ $item->tel1 }}</a></td>
                <td><a href="{{ route('affiliation.detail.create', ['id' => $item->m_corps_id]) }}" class="high-light">{{ $item->m_corps_name }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if ($results->count())
    {{ $results->links('auction_setting.components.follow_pagination') }}
    @endif
</div>
