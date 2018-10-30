@extends('layouts.app')
@section('content')
    <div class="auction-exclusion">
        @component('auction_setting.components.tabs') @endcomponent
        <div class="pt-2 ">
            @php $isDisabled = Auth::user()->auth == 'accounting_admin' ? 'disabled' : '' @endphp
            @php
                $isError = $errors->any()
            @endphp

            @if ($isError)
                <div class="alert alert-danger">{{ __('mtime.message_failure') }}</div>
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success">{{ __('mtime.message_successfully') }}</div>
            @endif
            <div class="form-category">
                <form action="{{ route('auction_setting.postExclusion') }}" method="post" id="form-auction-exclusion" novalidate>
                    <input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
                    <label class="form-category__label">{{trans('exclusion.holidays_setting') }}</label>
                    <div class="container-fluid pl-5 pr-5">
                    <div id="setting-vacation" data-group-fill-required="{{ count($getPublicHoliday) == 0 ? 'true' : 'false' }}" data-error-container="{{ count($getPublicHoliday) == 0 ? '#error-required-feedback' : '' }}" data-msg-group-fill-required="{{ trans('exclusion.required_at_less') }}">
                            <?php
                            $forCount = ceil(count($getPublicHoliday) / 5);
                            if (count($getPublicHoliday) == 0) {
                                $forCount = 1;
                            }
                            ?>
                            @for ($j = 0; $j < $forCount; $j++)
                                <div class="form-group form-inline mb-2">
                                    @for ($i = 0; $i < 5; $i++)
                                        <div class="w-20 pl-1 pr-1 mb-auto">
                                            <input type="hidden" name="holiday_id[]"
                                                   @php if (isset($getPublicHoliday[$j * 5 + $i]['id']))
                                               echo "value=" . $getPublicHoliday[$j * 5 + $i]['id'] @endphp
                                                   id="PublicHoliday{{$j * 5 + $i}}Id">
                                            <input maxlength="10" size="10" name="holiday_date[{{$j * 5 + $i}}]" {{ $isDisabled }} id="holiday_date{{ $j * 5 + $i }}" class="form-control datepicker w-100" type="text" @php if (isset($getPublicHoliday[$j * 5 + $i]['id'])) echo "value=" . $getPublicHoliday[$j * 5 + $i]['holiday_date'] @endphp >
                                        </div>
                                    @endfor
                                </div>
                            @endfor
                        </div>
                        <div class="pl-1" id="error-required-feedback"></div>
                        <div class="form-group pl-1 pt-2">
                            <button id="addVacation" class="btn btn--gradient-orange" type="button">{{ trans('exclusion.submit') }}
                            </button>
                        </div>
                    </div>
                    <label class="form-category__label">{{trans('exclusion.exclusion_time_setting') }}</label>
                    <div class="container-fluid pl-5 pr-5">
                        @for ($i = 0; $i < 10; $i++)
                            <div class="form-inline form-group mb-2">
                                <label class="template-title justify-content-start mb-auto">{{trans('exclusion.pattern') }}{{$i + 1}}</label>
                                <div class="template-content">
                                    <div class="form-inline">
                                        <label class="pr-3">{{trans('exclusion.exclusion_time') }}</label>
                                        <input type="hidden" name="exclusion_id[]" value="{{ !empty($time[$i]['id']) ? $time[$i]['id'] : '' }}" id="ExclusionTime{{$i}}Id">
                                        <input type="hidden" name="pattern[]" value="{{ !empty($time[$i]['pattern']) ? $time[$i]['pattern'] : '' }}" id="ExclusionTime{{$i}}Pattern">

                                        <input {{ $isDisabled }} name="exclusion_time_from[{{$i}}]" id="exclusion_time_from{{$i}}" class="timepicker form-control" maxlength="5" size="5" type="text" value="{{ !empty($time[$i]['exclusion_time_from']) ? $time[$i]['exclusion_time_from'] : ''}}" data-error-container="#exclusion_time_from_feedback{{$i}}" data-rule-requiredPairTo="#exclusion_time_to{{$i}}">
                                        <label class="pl-1 pr-2">{{ trans('common.wavy_seal') }}</label>
                                        <input {{ $isDisabled }} name="exclusion_time_to[{{$i}}]" maxlength="5" size="5" id="exclusion_time_to{{$i}}" class="timepicker form-control" type="text" value="{{ !empty($time[$i]['exclusion_time_to']) ? $time[$i]['exclusion_time_to'] : ''}}" data-error-container="#exclusion_time_to_feedback{{$i}}" data-rule-requiredPairFrom="#exclusion_time_from{{$i}}">
                                    </div>
                                    @php
                                        $checked = [];
                                        if (!empty($time[$i]['exclusion_day'])) {
                                            $checked = App\Services\ExclusionService::setChecked($time[$i]['exclusion_day']);
                                        }
                                    @endphp
                                    <div class="form-inline">
                                        <label class="pr-3">{{trans('exclusion.setting_exclusion_date') }}</label>
                                        <div class="custom-control custom-checkbox pr-2">
                                            <input {{ $isDisabled }} type="checkbox" class="custom-control-input ignore"
                                                   name="exclusion_day[{{$i}}][]" id="customCheck{{$i}}-1"
                                                   @if(isset($checked['checked1'])) {{ $checked['checked1'] }} @endif value="1">
                                            <label class="custom-control-label"
                                                   for="customCheck{{$i}}-1">{{trans('exclusion.saturday') }}</label>
                                        </div>
                                        <div class="custom-control custom-checkbox pr-2">
                                            <input {{ $isDisabled }} type="checkbox" class="custom-control-input ignore"
                                                   id="customCheck{{$i}}-2" name="exclusion_day[{{$i}}][]"
                                                   @if(isset($checked['checked2'])) {{ $checked['checked2'] }} @endif value="2">
                                            <label class="custom-control-label"
                                                   for="customCheck{{$i}}-2">{{trans('exclusion.sunday') }}</label>
                                        </div>
                                        <div class="custom-control custom-checkbox pr-2">
                                            <input {{ $isDisabled }} type="checkbox" name="exclusion_day[{{$i}}][]"
                                                   class="custom-control-input ignore" id="customCheck{{$i}}-3"
                                                   @if(isset($checked['checked3'])) {{ $checked['checked3'] }} @endif value="4">
                                            <label class="custom-control-label"
                                                   for="customCheck{{$i}}-3">{{trans('exclusion.public_holiday') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-inline" id="exclusion_time_from_feedback{{$i}}"></div>
                                    <div class="form-inline" id="exclusion_time_to_feedback{{$i}}"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="form-group pt-4 pb-3 text-center">
                        <input name="regist" id="regist" class="btn btn--gradient-green function-button" type="submit" {{ $isDisabled }}
                               value="{{trans('exclusion.registration') }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- code block used when click button --}}
    <div class="d-none" id="group-vacation">
        <div class="form-group form-inline mb-2">
            @for ($i = 0; $i < 5; $i++)
                <div class="w-20 pl-1 pr-1 mb-auto">
                    <input type="hidden" name="holiday_id[]">
                    <input {{ $isDisabled }} type="text" maxlength="10" size="10" name="holiday_date[]" class="form-control datepicker w-100">
                </div>
            @endfor
        </div>
    </div>
@endsection
@section('script')
<script>
    var forCount = {{$forCount * 5}};
</script>
<script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
<script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
<script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
<script src="{{ mix('js/utilities/form.validate.js') }}"></script>
<script src="{{ mix('js/pages/auction.exclusion.js') }}"></script>
@endsection
