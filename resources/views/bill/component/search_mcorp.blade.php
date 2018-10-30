@if(!empty($results))
    <div class="row table-result-search">
        <span class="count-total">@lang('mcorp_list.total') {{ $results->total() }}@lang('mcorp_list.matter')</span>
        <div class="table-responsive">
            <table class="table table-bordered" id="tbBillSearch">
                <thead>
                <tr>
                    <th class="text-center w-15">@lang('mcorp_list.id_link')</th>
                    <th class="text-center w-85">@lang('mcorp_list.link')</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr>
                            <td class="text-center w-15">{{ $result['id'] }}</td>
                            <td class="text-center w-85">
                                <a href="#" onclick="saveSessionBill(this);" data-href="{{route('bill.getBillList',$result['id'])}}">{{ $result['official_corp_name'] }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if(!empty($results))
                {{ $results->links('bill.component.mcorp_pagination') }}
            @endif
        </div>
    </div>
@endif
