@extends('layouts.app')
@section('content')
    <div class="container" id="bill-search">
        @if(session()->has('alert-success'))
            <p class="box__mess alert-success">{{ session()->get('alert-success') }}</p>
        @endif

        {!! Form::open(['url' => route('bill.mCorpSearch'), 'class' => 'form-horizontal fieldset-custom','id' => 'corp_search']) !!}
        <fieldset>
            <legend>@lang('mcorp_list.search_condition')</legend>
            <div class="form-search">
                <div class="row">
                    <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                        <label class="col-12 col-sm-3 col-md-3"
                               for="corp_name">@lang('mcorp_list.name_of_member_store')</label>
                        {!! Form::text('corp_name', isset($oldValue->corp_name) ? $oldValue->corp_name : '', ['class' => 'form-control col-12 col-sm-9 col-md-9','id' => 'corp_name']) !!}
                    </div>
                    <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                        <label class="col-12 col-sm-3 col-md-3" for="corp_id">@lang('mcorp_list.merchant_id')</label>
                        {!! Form::text('corp_id', isset($oldValue->corp_id) ? $oldValue->corp_id : '', ['class' => 'form-control col-12 col-sm-9 col-md-9','id' => 'corp_id', 'data-rule-number' => 'true']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                        <label class="col-12 col-sm-3 col-md-3"
                               for="bill_status">@lang('mcorp_list.billing_status')</label>
                        <select name="bill_status" id="bill_status" class="form-control col-12 col-sm-9 col-md-9">
                            @foreach( $billingStatus as $value)
                                <option
                                    @if(isset($searchData[0]['bill_status']) && $searchData[0]['bill_status'] == $value['id'])
                                    {{ 'selected' }}
                                    @endif
                                    value="{{$value['id']}}">
                                    {{$value['category_name']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                        <label class="col-12 col-sm-3 col-md-3" for="bill_id">@lang('mcorp_list.billing_id')</label>
                        {!! Form::text('bill_id', isset($searchData[0]['bill_id']) ? $searchData[0]['bill_id'] : '', ['class' => 'form-control col-12 col-sm-9 col-md-9','id' => 'bill_id', 'data-rule-number' => 'true']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                        <label class="col-12 col-sm-3 col-md-3">@lang('mcorp_list.commission_charge_date')</label>
                        <div class="col-12 col-sm-9 px-0 d-flex justify-content-between">
                            <div class="d-sm-inline-flex flex-column">
                                {!! Form::text('from_fee_billing_date', isset($searchData[0]['from_fee_billing_date']) ? $searchData[0]['from_fee_billing_date'] : '', ['class' => 'form-control datepicker w-100','id' => 'from_fee_billing_date', 'data-rule-lessThanTime' => '#to_fee_billing_date']) !!}
                            </div>
                            <div class="d-sm-inline-block p-2">{{ trans('common.wavy_seal') }}</div>
                            <div class="d-sm-inline-flex flex-column">
                                {!! Form::text('to_fee_billing_date', isset($searchData[0]['to_fee_billing_date']) ? $searchData[0]['to_fee_billing_date'] : '', ['class' => 'form-control datepicker w-100','id' => 'to_fee_billing_date']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                        <label class="col-12 col-sm-3 col-md-3">@lang('mcorp_list.nominee')</label>
                        {!! Form::text('nominee', isset($searchData[0]['nominee']) ? $searchData[0]['nominee'] : '', ['class' => 'form-control col-12 col-sm-9 col-md-9','id' => 'nominee']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-sm-12 col-md-6">
                        {!! Form::button(trans('mcorp_list.search'), ['id' => 'search', 'class' => 'btn btn--gradient-orange col-4 col-sm-3 col-md-3']); !!}
                    </div>
                </div>
            </div>
        </fieldset>
        {!!  Form::close() !!}
        <div class="searchResult">
            @include('bill.component.search_mcorp')
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var url_mcorp_search = '{{route('bill.mCorpSearch')}}';
            var isInitSearch = true;
            if(window.history.state && window.history.state.dataBack) {
                $('.searchResult').html(window.history.state.dataBack);
                isInitSearch = false;
            }
            @if (!empty($afterEditSession))
                @foreach($afterEditSession as $key => $value)
                    $("#{{ $key }}").val("{{ $value }}");
                @endforeach
                var controlElFull = {
                    isInitSearch: isInitSearch,
                    searchEl: '#search',
                    sort: [],
                    formId: '#corp_search',
                    resultArea: '.searchResult',
                    nextPage: '.next',
                    prevPage: '.previous'
                };
                ajaxCommon.search(url_mcorp_search, controlElFull);
            @else
                var controlEl = {
                    searchEl: '#search',
                    sort: [],
                    formId: '#corp_search',
                    resultArea: '.searchResult',
                    nextPage: '.next',
                    prevPage: '.previous'
                };
                ajaxCommon.search(url_mcorp_search, controlEl);
            @endif
            Datetime.initForDatepicker();
            FormUtil.validate('#corp_search');
        });

        function saveSessionBill(identifier) {
            var href = $(identifier).data('href');
            var formData = $('#corp_search').serializeArray();
            var urlSession = '{{ route('bill.save.session') }}';
            $.ajax({
                type: "POST",
                data: formData,
                url: urlSession,
                cache: false
            }).done(function (data) {
                window.location.href = href;
            });
        }
    </script>
@endsection
