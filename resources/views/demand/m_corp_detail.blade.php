
<table class="table mb-0">
    <tbody>
        <tr>
            <td class="align-middle border-top-0 w-xl-150">@lang('demand_detail.location')</td>
            <td class="align-middle border-top-0 w-xl-450">
                {{ $mCorp->address1_jp  }}{{ $mCorp->address2 }}{{ $mCorp->address3 }}
{{--                        {{ $mCorp->address3 != '' && $mCorp->address4 != '' && strpos($mCorp->address3, $mCorp->address4) ? '' : $mCorp->address4 }}--}}
            </td>
        </tr>

        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.fax_num')</td>
            <td class="align-middle w-xl-450">
                {{ $mCorp->fax }}
            </td>
        </tr>
        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.pc_email')</td>
            <td class="align-middle w-xl-450">

                @foreach($mCorp->email_by_array as $mail)
                    <a href="{{ checkDevice().$mail }}" class="highlight-link text--underline">{{ $mail }}</a> <br/>
                @endforeach
            </td>
        </tr>

        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.custome_info')</td>
            <td class="align-middle w-xl-450">
                {{ $mCorp->text_coordination }}
            </td>
        </tr>
        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.available_time')</td>
            <td class="align-middle w-xl-450">
                @if($mCorp->contactable_support24hour)
                    @lang('demand_detail.24h')
                @else
                    {{ $mCorp->contactable_time_from . ' - ' . $mCorp->contactable_time_to }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.holiday')</td>
            <td class="align-middle w-xl-450">
                {{ $mCorp->holiday1 }}
            </td>
        </tr>
        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.order_fee')</td>
            <td class="align-middle w-xl-450">
                {!! $feeCommission !!}
            </td>
        </tr>

        <tr>
            <td class="align-middle fix-w-100 w-xl-150">@lang('demand_detail.order_note')</td>
            <td class="align-middle w-xl-450">
                {!! !empty($feeData->note) ? $feeData->note : ''  !!}
            </td>
        </tr>

    </tbody>
</table>
