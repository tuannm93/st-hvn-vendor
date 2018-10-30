@extends('layouts.app')
@section('content')
    @component('auction_setting.components.tabs')
    @endcomponent

    <div class="auction_setting_ranking fieldset-custom">
        <form method="post" id="auction-setting-ranking" action="{{route('auction_setting.ranking')}}" >
            {{csrf_field()}}
            <div class="main">
                <h3>@lang('auction_settings.title_header_ranking')</h3>
                    <fieldset>
                        <legend>@lang('auction_settings.search_con_title')</legend>
                        <div class="content-top-main">
                            <div class="form-group row p-3 mb-0">
                                <label for="staticEmail" class="col-md-1 col-form-label fix-nowarp">@lang('auction_settings.tallying_date')</label>
                                <div class="col-md-3">
                                    {{Form::text('aggregate_date', $dataRequest['aggregate_date'], ['class' => 'datepicker form-control w-100', 'data-rule-required'=>'true'])}}
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-5 my-md-auto mt-2">
                                    <div class="row mx-0">
                                        <div class="col-md-4 px-0 d-flex align-items-center">
                                            <label for="staticEmail" class="mb-0">@lang('auction_settings.aggregation_period')</label>
                                        </div>
                                        <div class="col-md-8 px-0">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="aggregate_period" value="day" id ="radio-day" class="custom-control-input ignore" {{ ($dataRequest['aggregate_period'] == 'day') ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="radio-day">@lang('auction_settings.one_day')</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="aggregate_period" value="week" id ="radio-week" class="custom-control-input ignore" {{ ($dataRequest['aggregate_period'] == 'week') ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="radio-week">@lang('auction_settings.seven_day')</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="aggregate_period" value="month" id ="radio-month" class="custom-control-input ignore" {{ ($dataRequest['aggregate_period'] == 'month') ? 'checked' : '' }} />
                                                <label class="custom-control-label" for="radio-month">@lang('auction_settings.one_month')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row p-3">
                                <input class="btn btn--gradient-orange" type="submit" value="@lang('auction_settings.btn_search_title')" name="submit" />
                                <input class="btn btn--gradient-orange ml-md-2 mt-2 mt-md-0" id="export_csv_ranking" value="@lang('auction_settings.csv_download')" name="submit" type="submit" />
                            </div>
                        </div>
                    </fieldset><!-- contents end -->
                <div class="ml-2 mt-3 mr-2">
                    @lang('auction_settings.the_total_number_of_cases') {{ !empty($countSearchAuctionRanking) ? $countSearchAuctionRanking : '0' }}@lang('auction_settings.matter')
                    <table class="table table-bordered table-list">
                        <thead class="bg-thead">
                        <tr>
                            <th>@lang('auction_settings.business_name')</th>
                            <th>@lang('auction_settings.business_id')</th>
                            <th>@lang('auction_settings.times')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($results))
                            @foreach($results as $result)
                                <tr>
                                    <td class="p-1 align-middle text-left">{{$result->MCorp__official_corp_name}}</td>
                                    <td class="p-1 align-middle text-center">{{$result->CommissionInfo_corp_id}}</td>
                                    <td class="p-1 align-middle text-center">{{$result->CommissionInfo__ranking}}</td>
                                <tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(isset($results))
                        {{$results->links()}}
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script>
        Datetime.initForDatepicker();
        FormUtil.validate('#auction-setting-ranking');
    </script>
@endsection
