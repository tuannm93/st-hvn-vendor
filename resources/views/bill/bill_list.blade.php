@extends('layouts.app')
@section('content')
    <div class="container" id="bill-search">
        @foreach (['error', 'success'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="box__mess alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
        @endforeach
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::open(['url' => route('bill.postBillSave', $id), 'class' => 'form-horizontal fieldset-custom','id' => 'bill_search']) !!}
        <fieldset>
            <legend>@lang('bill_list.search_condition')</legend>
            <div class="form-search">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                   for="corp_name">@lang('bill_list.franchise')</label>
                            <div class="col-md-9 pl-0 pr-0 my-auto">
                                <a class="highlight-link text--underline" href="{{ route('affiliation.detail.edit', $id) }}">{{ isset($bill) ? $bill->MCorp__official_corp_name : '' }}</a>
                                <input type="hidden" value="{{isset($billList[0]) ?  $billList[0]['official_corp_name'] :''}}" name="official_corp_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label my-auto" for="corp_id">@lang('bill_list.bill_status')</label>
                            <div class="col-md-9 pl-0 pr-0">
                                <select name="bill_status" class="form-control">
                                    @if(isset($billingStatus))
                                        @foreach($billingStatus as $billStatus)
                                            <option @if($billSession[0]['bill_status'] == $billStatus['id']) {{ 'selected' }} @endif value="{{ $billStatus['id'] }}">{{ $billStatus['category_name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-md-3">@lang('bill_list.commission_date')</label>
                            <div class="col-md-4 pl-0 pr-0">
                                {!! Form::text('from_fee_billing_date', '', ['class' => 'form-control datepicker','id' => 'from_fee_billing_date', 'data-error-container' => '#from_fee_billing_date_feedback']) !!}
                            </div>
                            <div class="col-md-1 text-center my-auto">～</div>
                            <div class="col-md-4 pl-0 pr-0">
                                {!! Form::text('to_fee_billing_date','', ['class' => 'form-control datepicker','id' => 'to_fee_billing_date', 'data-error-container' => '#to_fee_billing_date_feedback']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-4 offset-3 pr-0" id="from_fee_billing_date_feedback"></div>
                            <div class="col-md-4 offset-1 pl-0" id="to_fee_billing_date_feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <a href="javascript:void(0)" id="back-mcorp-list" onclick="checkSessionBill();" data-href="{{ route('bill.mCorpList') }}" class="btn btn--gradient-orange col-md-3">{{ trans('mcorp_list.back') }}</a>
                        {!! Form::button(trans('mcorp_list.search'), ['id' => 'search', 'class' => 'btn btn--gradient-orange col-md-3 mt-2 mt-md-0']); !!}
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="searchResult">
            @include('bill.component.bill_search')
        </div>
        {!!  Form::close() !!}
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/bill.search.js') }}"></script>
    <script>
        var url_mcorp_search = '{{route('bill.postBillSearch', $id)}}';
        var url_money_correspond = '{{route('bill.moneyCorrespond',$id)}}';
        var url_bill_download = '{{ route('bill.postBillDownload', $id) }}';
        var url_bill_save = '{{ route('bill.postBillSave', $id) }}';
        var  errorsMesseg = '@lang('bill_list.errors-messenger')';
        var controlEl = {
            searchEl: '#search',
            sort: [],
            formId: '#bill_search',
            resultArea: '.searchResult',
            nextPage: '.next',
            prevPage: '.previous'
        };
        FormUtil.validate(controlEl.formId);
        Datetime.initForDatepicker();

        function checkSessionBill() {
            var href = $('#back-mcorp-list').data('href');
            var urlSession = '{{ route('bill.check.session') }}';
            $.ajax({
                type: "POST",
                data: true,
                url: urlSession,
                cache: false
            }).done(function (data) {
                window.location.href = href;
            });
        }
    </script>
    <script>
        ajaxCommon.search(url_mcorp_search, controlEl);
        OnClick.init(url_money_correspond, url_bill_download, url_bill_save);
    </script>
@endsection
