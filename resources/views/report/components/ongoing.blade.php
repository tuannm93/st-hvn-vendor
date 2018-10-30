<label class="font-weight-bold fs-15 mt-2">@lang('report_jbr.after_the_visit')</label>
<p class="mb-0">@lang('report_jbr.number_of_pieces') {{ $results->total() }}@lang('report_jbr.pieces')</p>
<div class="table-responsive">
    <table class="table custom-border">
        <thead>
            <tr class="text-center bg-yellow-light">
                <th class="p-1 align-middle">@lang('report_jbr.case_id')
                    <i class="triangle-up sort" data-sort="demand_infos.id-asc"></i>
                    <i class="triangle-down sort" data-sort="demand_infos.id-desc"></i>
                </th>
                <th class="p-1 align-middle">@lang('report_jbr.make_sure_to_take_the_first')
                    <i class="triangle-up sort" data-sort="m_corps.corp_name-asc"></i>
                    <i class="triangle-down sort" data-sort="m_corps.corp_name-desc"></i>
                </th>
                <th class="p-1 align-middle">@lang('report_jbr.take_id')
                    <i class="triangle-up sort" data-sort="commission_infos.id-asc"></i>
                    <i class="triangle-down sort" data-sort="commission_infos.id-desc"></i>
                </th>
                <th class="p-1 align-middle">@lang('report_jbr.contents_of_homework')
                    <i class="triangle-up sort" data-sort="demand_infos.jbr_work_contents-asc"></i>
                    <i class="triangle-down sort" data-sort="demand_infos.jbr_work_contents-desc"></i>
                </th>
                <th class="p-1 align-middle">@lang('report_jbr.see_the_status_of_book')
                    <i class="triangle-up sort" data-sort="demand_infos.jbr_estimate_status-asc"></i>
                    <i class="triangle-down sort" data-sort="demand_infos.jbr_estimate_status-desc"></i>
                </th>
                <th class="p-1 align-middle">@lang('report_jbr.recevie_the_book_status')
                    <i class="triangle-up sort" data-sort="demand_infos.jbr_receipt_status-asc"></i>
                    <i class="triangle-down sort" data-sort="demand_infos.jbr_receipt_status-desc"></i>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $item)
            <tr>
                <td class="p-1 align-middle text-center"><a href="{{ route('demand.detail', ['id' => $item->id]) }}" class="highlight-link">{{ $item->id }}</a></td>
                <td class="p-1 align-middle"><a href="{{ route('affiliation.detail.edit', ['id' => $item->m_corps_id]) }}" class="highlight-link">{{ $item->corp_name }}</a></td>
                <td class="p-1 align-middle text-center"><a href="{{ route('commission.detail', ['id' => $item->commission_infos_id]) }}" class="highlight-link">{{ $item->commission_infos_id }}</a></td>
                <td class="p-1 align-middle">{{ $item->item_name1 }}</td>
                <td class="p-1 align-middle">{{ $item->item_name2 }}</td>
                <td class="p-1 align-middle">{{ $item->item_name3 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if ($results->count())
        {{ $results->links('report.components.ongoing_pagination') }}
    @endif
</div>