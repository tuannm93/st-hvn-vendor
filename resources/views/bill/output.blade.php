@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <div id="search">
        <div class="fieldset-custom bill" id="contents">
            <div class="bill_output" id="main">
                @if($bHasError)
                    <div class="alert alert-danger">
                        {{\Session::get(__('bill.KEY_SESSION_ERROR_DOWNLOAD_BILL_CSV'))}}
                    </div>
                @endif
                <fieldset>
                    <legend>{{__('bill.output_label_condition')}}</legend>
                    {{ Form::open(['action' => 'Bill\BillController@output', 'method' => 'post', 'id' => 'output_bill']) }}
                    <div class="bg-content boder-content">
                        <div class="row m-3">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-2 pt-2 pl-0">{{__('bill.output_label_date_excute')}}</div>
                                    <div class="col-md-3 p-0">
                                        <input class="datepicker form-control" name="from_complete_date" title=""
                                               value="{{$fromDate}}">
                                    </div>
                                    <div class="col-md-1 p-0 text-center mt-md-2"> ～</div>
                                    <div class="col-md-3 p-0">
                                        <input class="datepicker d-inline-flex form-control" name="to_complete_date"
                                               title="" value="{{$toDate}}">
                                    </div>
                                    <div class="col-md-3 d-flex mt-md-1 px-0 mt-2">
                                        <div class="custom-control custom-checkbox d-inline ml-md-3">
                                            <input type="checkbox" name="fee_billing_date" id="choice-fee"
                                                   class="custom-control-input ignore" title="">
                                            <label class="custom-control-label custome-label"
                                                   for="lost_flg_filter"></label>
                                        </div>
                                        <label for="choice-fee">{{__('bill.output_checkbox_choice_fee')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mx-3 mb-3">
                                <input title="" type="submit" value="{{__('bill.output_button_export')}}" id="search"
                                       class="btn btn--gradient-gray col-md-5">
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </fieldset>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.ui.datepicker-ja.min.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/bill.output.js') }}"></script>
    <script>
        FormUtil.validate('#output_bill');
    </script>
@endsection
