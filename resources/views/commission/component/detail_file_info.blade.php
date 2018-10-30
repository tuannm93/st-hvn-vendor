<div class="form-category__body clearfix">
    <h6 class="border-left-orange font-weight-bold mt-0 mb-0 mb-md-2 pl-2">{{ trans('commission_detail.attachment') }}</h6>
    @if (count($demand_attached_files) > 0)
    <table border="1" class="table table-bordered">
        <thead class="bg-primary-lighter">
        <tr>
            <th class="text-center">{{ trans('commission_detail.file') }}</th>
            <th class="text-center">{{ trans('commission_detail.upload_datetime') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($demand_attached_files as $file)
        <tr>
            <td align="center">
                @if ($results['CommissionInfo__commit_flg'] == 1)
                    <a class="link-primary text--underline" href="{{ route('commission.commission_file_download', ['id' => $file['id']]) }}" target="_blank">
                        {{ $file['name'] }}
                    </a>
                @else
                    <span class="link-primary text--underline">{{ $file['name'] }}</span>
                @endif
            </td>
            <td align="center">{{ $file['create_date'] }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
      <p>{{ trans('commission_detail.not_uploaded') }}</p>
    @endif
    @if ($results['DemandInfo__selection_system'] == $div_value['auction_selection']
            || $results['DemandInfo__selection_system'] == $div_value['automatic_auction_selection'])
        <p class="text--orange-light border border-thick border-note p-2 bg-note">
            {{ trans('commission_detail.visit_datetime_text') }}
            @if (isset($address_disclosure[$results['DemandInfo__priority']]['item_hour_date']))
                {{ $address_disclosure[$results['DemandInfo__priority']]['item_hour_date'] }}{{ trans('commission_detail.hour') }}
            @endif
            @if (isset($address_disclosure[$results['DemandInfo__priority']]['item_minute_date']))
                {{ $address_disclosure[$results['DemandInfo__priority']]['item_minute_date'] }}{{ trans('commission_detail.minute') }}
            @endif
                {!! trans('commission_detail.disclose_text') !!}
            @if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_hour_date']))
                {{ $tel_disclosure[$results['DemandInfo__priority']]['item_hour_date'] }}{{ trans('commission_detail.hour') }}
            @endif
            @if (isset($tel_disclosure[$results['DemandInfo__priority']]['item_minute_date']))
                {{$tel_disclosure[$results['DemandInfo__priority']]['item_minute_date']  }}{{ trans('commission_detail.minute') }}
            @endif
            {{ trans('commission_detail.disclose_before_text') }}<br>
        </p>
    @endif

    <div class="text-center p-2">
        <button type="button" class="btn btn-lg btn--gradient-orange d-md-none site_launch_details_open w-100" id="site_launch_details_open_mobile">サイト打ち出し詳細 ≫</button>
    </div>
</div>
