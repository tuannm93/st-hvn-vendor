<label class="mb-0 mt-2">
    @if(isset($dataResult) && count($dataResult) > 0)
        {{ trans('report_corp_agreement_category.total_number').$dataResult->total().trans('report_corp_agreement_category.matter') }}
    @else
        {{ trans('report_corp_agreement_category.total_number').'0'.trans('report_corp_agreement_category.matter') }}
    @endif
</label>
<table class="table custom-border">
    <thead class="text-center bg-yellow-light">
        <tr>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.history_id') }}</th>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.contract_id') }}</th>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.company_id') }}</th>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.company_name') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.genre_id') }}</th>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.genre_name') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.category_id') }}</th>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.category_name') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.order_receiving_fee') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.order_commission_unit') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.referral_fee') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.expertise') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.order_form') }}</th>
            <th class="p-1 fix-w-100 align-middle text-wrap">{{ trans('report_corp_agreement_category.update_date_and_time') }}</th>
            <th class="p-1 fix-w-50 align-middle text-wrap">{{ trans('report_corp_agreement_category.update_type') }}</th>
        </tr>
        <tr>
            <th colspan="9" class="p-1">{{ trans('report_corp_agreement_category.remarks') }}</th>
            <th colspan="6" class="p-1">{{ trans('report_corp_agreement_category.update_contents') }}</th>
        </tr>
    </thead>
    <tbody>
    @if(isset($dataResult) && count($dataResult) > 0)
        @foreach($dataResult as $result)
            <tr>
                <td class="p-1 fix-w-100 align-middle text-wrap text-center">{{ $result->id }}</td>
                <td class="p-1 fix-w-100 align-middle text-wrap text-center">{{ $result->corp_agreement_id }}</td>
                <td class="p-1 fix-w-100 align-middle text-wrap text-center">{{ $result->m_corps_id }}</td>
                <td class="p-1 fix-w-100 align-middle text-wrap">{{ $result->official_corp_name }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-center">{{ $result->m_genres_id }}</td>
                <td class="p-1 fix-w-100 align-middle text-wrap">{{ $result->genre_name }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-center">{{ $result->m_categories_id }}</td>
                <td class="p-1 fix-w-100 align-middle text-wrap">{{ $result->category_name }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-right">{{ $result->order_fee }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-center">{{ $result->custom_order_fee_unit }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-right">{{ $result->introduce_fee }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-center">{{ $result->select_list }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-center">{{ $result->custom_corp_commission_type }}</td>
                <td class="p-1 fix-w-100 align-middle text-wrap text-center">{{ $result->modified }}</td>
                <td class="p-1 fix-w-50 align-middle text-wrap text-center
                                    @if(empty($result->custom_action_type))
                {{ 'bg-default' }}
                @elseif($result->custom_action_type == trans('report_corp_agreement_category.add_to'))
                {{ 'bg-added' }}
                @elseif($result->custom_action_type == trans('report_corp_agreement_category.delete'))
                {{ 'bg-deleted' }}
                @elseif($result->custom_action_type == trans('report_corp_agreement_category.change'))
                {{ 'bg-changed' }}
                @endif
                        " >
                    {{ $result->custom_action_type }}
                </td>
            </tr>
            <tr>
                <td colspan="9" class="p-1">
                    @if(!empty($result->note))
                        {{ $result->note }}
                    @else
                        {{ "&nbsp;" }}
                    @endif
                </td>
                <td colspan="6" class="p-1">
                    @if(!empty($result->custom_action))
                        {{ $result->custom_action }}
                    @else
                        {{ "&nbsp;" }}
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
@if(!empty($dataResult))
    {{ $dataResult->links('pagination.nextprevajax') }}
@endif