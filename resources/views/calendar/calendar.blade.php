<div class="mx-sm-1 mb-1 p-2 text-center custom-calendar" data-month="{{ $currentMonth }}"
     data-year="{{ $currentYear }}">
    <span class="my-2 font-weight-bold month_main">{{ $currentYear }} @lang('calendar.year') {{ $currentMonth }} @lang('calendar.month') </span>
    <table class="mx-auto">
        <thead class="bg-header">
        <tr>
            <th class="p-1">@lang('calendar.mon')</th>
            <th class="p-1">@lang('calendar.tue')</th>
            <th class="p-1">@lang('calendar.wed')</th>
            <th class="p-1">@lang('calendar.thu')</th>
            <th class="p-1">@lang('calendar.fri')</th>
            <th class="p-1">@lang('calendar.sat')</th>
            <th class="p-1 holiday">@lang('calendar.sun')</th>
        </tr>
        </thead>
        <tbody>
        @php
            $checkSkip = 1;
            do{
        @endphp
        <tr data-month="{{ $currentMonth }}" data-year="{{ $currentYear }}">
            {{-- loops through each week --}}
            @for($i=0; $i < 7; $i++)
                @php
                    $class = '';
                    if($tempDate->month != $currentMonth)
                    {
                        $class = 'out-date ';
                    }
                    if(in_array($tempDate->day, $arrHoliday)
                    || ($i == 6 && $tempDate->month == $currentMonth))
                    {
                        $class .= 'holiday';
                    }
                @endphp
                <td class="p-1 {{ $class }}"
                    data-date="{{$currentYear.'-'.$currentMonth.'-'.($tempDate->day < 10 ? '0'.$tempDate->day : $tempDate->day)}}">{{ $tempDate->day }}</td>
                @php
                    $tempDate->addDay();
                @endphp
            @endfor
        </tr>
        @php
            } while($tempDate->month == $currentMonth);
        @endphp
        </tbody>
    </table>
</div>
