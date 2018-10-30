@extends('layouts.app')
@section('content')
    <div class="bill container">
        <div class="bill_detail fix-nowarp">
            <form method="POST" id="bill-detail" action="{{route('bill.bill_detail.update', $result['bill_infos_id'])}}">
                {{csrf_field()}}
                <div class="d-flex justify-content-end">
                    <input type="button" class="btn btn--gradient-gray fix-button-w-120 redirectToBillList" data-mcorpid="{{route('bill.getBillList', $result['MCorp__id'])}}" value="@lang('bill.back')" />
                </div>
                <div class="d-flex justify-content-end">
                    <span class="text-danger">*</span>
                    <span>(@lang('bill.require'))</span>
                </div>
                <div>
                    @if(Session::has('message'))
                        <p class="box__mess box--{{Session::get('class')}}" >{{Session::get('message')}}</p>
                    @endif
                    @if(Session::has('input_error'))
                        <p class="box__mess box--{{Session::get('class_error')}}" >{{Session::get('input_error')}}</p>
                    @endif
                </div>
                <h3 class="title-head">@lang('bill.bill_info_title')</h3>
                    <div class="row ml-md-3">
                        <div class="col-5 col-md-3">
                            <div class="pt-1 pb-1">@lang('bill.name_website')</div>
                            <div class="pt-1 pb-1">@lang('bill.genre')</div>
                            <div class="pt-1 pb-1">@lang('bill.customer_name')</div>
                            <div class="pt-1 pb-1">@lang('bill.prefecture')</div>
                            <div class="pt-1 pb-1">@lang('bill.municipality')</div>
                            <div class="pt-1 pb-1">@lang('bill.laster_address')</div>
                            <div class="pt-1 pb-1">@lang('bill.completion_date')</div>
                        </div>
                        <div class="col-6 col-md-5">
                            <div class="pt-1 pb-1">{{$result['MSite__site_name']}}</div>
                            <div class="pt-1 pb-1">{{$result['MGenre__genre_name']}}</div>
                            <div class="pt-1 pb-1">{{$result['DemandInfo__customer_name']}}</div>
                            <div class="pt-1 pb-1">{{ getDivTextJP('prefecture_div', $result['DemandInfo__address1']) }}</div>
                            <div class="pt-1 pb-1">{{$result['DemandInfo__address2']}}</div>
                            <div class="pt-1 pb-1">{{$result['DemandInfo__address3']}}</div>
                            <div class="pt-1 pb-1">{{$result['CommissionInfo__complete_date']}}</div>
                        </div>
                    </div>
                <h3 class="title-head">@lang('bill.billding_infomation')</h3>
                <div class="row ml-md-3">
                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.request_id')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{$data['bill_infos_id']}}
                        <input type="hidden" name="bill_infos_id" value="{{$data['bill_infos_id']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.case_id')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{$data['bill_infos_demand_id']}}
                        <input type="hidden" name="bill_infos_demand_id" value="{{$data['bill_infos_demand_id']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1 my-auto">@lang('bill.billding_status')</div>
                    <div class="col-7 col-md-2 pt-1 pb-1">
                        <select class="form-control" name="bill_infos_bill_status">
                            @foreach($mItem as $key => $item)
                                <option value="{{$key}}" {{$data['bill_infos_bill_status'] == $key ? 'selected' : ''}} >{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.commistion_rate')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_irregular_fee_rate'])  ? $data['bill_infos_irregular_fee_rate'] . '%' : ''}}
                        <input type="hidden" name="bill_infos_irregular_fee_rate" value="{{$data['bill_infos_irregular_fee_rate']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.irregular_fee')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{ yenFormat2($data['bill_infos_irregular_fee']) }}
                        <input type="hidden" name="bill_infos_irregular_fee" value="{{$data['bill_infos_irregular_fee']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.tax_include')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_deduction_tax_include']) ? yenFormat2($data['bill_infos_deduction_tax_include']) : '0円' }}
                        <input type="hidden" name="bill_infos_deduction_tax_include" value="{{$data['bill_infos_deduction_tax_include']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.tax_exclude')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_deduction_tax_exclude']) ? yenFormat2($data['bill_infos_deduction_tax_exclude']) : '0円' }}
                        <input type="hidden" name="bill_infos_deduction_tax_exclude" value="{{$data['bill_infos_deduction_tax_exclude']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.indivisual_billing')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        <input type="checkbox" value="1" name="bill_infos_indivisual_billing" {{($data['bill_infos_indivisual_billing'] == 1) ? 'checked' : ''}}>
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.comfirmed_fee_rate')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{$data['bill_infos_comfirmed_fee_rate']}}%
                        <input type="hidden" name="bill_infos_comfirmed_fee_rate" value="{{$data['bill_infos_comfirmed_fee_rate']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.fee_target_price')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_fee_target_price']) ? yenFormat2($data['bill_infos_fee_target_price']) : '0円'}}
                        <input type="hidden" name="bill_infos_fee_target_price" value="{{$data['bill_infos_fee_target_price']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.fee_tax_exclude')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_fee_tax_exclude']) ? yenFormat2($data['bill_infos_fee_tax_exclude']) : '0円'}}
                        <input type="hidden" name="bill_infos_fee_tax_exclude" value="{{$data['bill_infos_fee_tax_exclude']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.bill_infos_tax')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_tax']) ? yenFormat2($data['bill_infos_tax']) : '0円'}}
                        <input type="hidden" name="bill_infos_tax" value="{{$data['bill_infos_tax']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.insurance_price')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_insurance_price']) ? yenFormat2($data['bill_infos_insurance_price']) : '0円'}}
                        <input type="hidden" name="bill_infos_insurance_price" value="{{$data['bill_infos_insurance_price']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.total_bill_price')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        {{!empty($data['bill_infos_total_bill_price']) ? yenFormat2($data['bill_infos_total_bill_price']) : '0円'}}
                        <input type="hidden" name="bill_infos_total_bill_price"  id="total_bill_price" value="{{$data['bill_infos_total_bill_price']}}">
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.fee_billing_date')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        <input type="text" class="datepicker form-control fix-w-200" maxlength="40" name="bill_infos_fee_billing_date" value="{{!empty(old('bill_infos_fee_billing_date')) ? old('bill_infos_fee_billing_date') : $data['bill_infos_fee_billing_date']}}">
                        <p>{{$errors->first('bill_infos_fee_billing_date')}}</p>
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.fee_payment_date')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        <input type="text" class="datepicker form-control fix-w-200" maxlength="40" name="bill_infos_fee_payment_date" value="{{!empty(old('bill_infos_fee_payment_date')) ? old('bill_infos_fee_payment_date') : $data['bill_infos_fee_payment_date']}}">
                        <p>{{$errors->first('bill_infos_fee_payment_date')}}</p>
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.fee_payment_price')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        <div class="d-flex">
                            <div>
                                <input type="text" class="form-control fix-w-200 value-change"  id="fee_payment_price" name="bill_infos_fee_payment_price" value="{{!empty(old('bill_infos_fee_payment_price')) ? (int)old('bill_infos_fee_payment_price') : (isset($result['bill_infos_fee_payment_price']) ? $result['bill_infos_fee_payment_price'] : '0' ) }}" data-rule-number="true" maxlength="40" data-rule-min="0" >
                                <p>{{$errors->first('bill_infos_fee_payment_price')}}</p>
                            </div>
                            <div class="mt-2 ml-1">円</div>
                        </div>
                    </div>

                    <div class="col-5 col-md-3 pt-1 pb-1">@lang('bill.fee_payment_balance')</div>
                    <div class="col-7 col-md-9 pt-1 pb-1">
                        <span id="fee_payment_balance_display">{{yenFormat2((int)$data['bill_infos_total_bill_price'] - (int)$data['bill_infos_fee_payment_price'])}}</span>
                        <input type="hidden" name="bill_infos_fee_payment_balance" value="{{(int)$data['bill_infos_total_bill_price'] - (int)$data['bill_infos_fee_payment_price']}}" id="fee_payment_balance">
                    </div>

                    <div class="col-md-3 pt-1 pb-1 my-auto">@lang('bill.report_note')</div>
                    <div class="col-md-9 pt-1 pb-1">
                        <textarea class="form-control" rows="6" name="bill_infos_report_note">{{trim($data['bill_infos_report_note'], '\0')}}</textarea>
                    </div>
                    <input name="bill_infos_modified" value="{{$data['bill_infos_modified']}}" type="hidden" />
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-md-center mt-2">
                    <input type="button" class="btn btn--gradient-gray button-w-120 redirectToBillList" data-mcorpid="{{route('bill.getBillList', $result['MCorp__id'])}}" value="@lang('bill.back')" />
                    <input type="submit" class="btn btn--gradient-green button-w-120 ml-md-2 mt-2 mt-md-0"  value="@lang('bill.registration')" name="regist"/>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/bill.detail.js') }}"></script>
    <script>
        $(document).ready(function () {
            BillDetail.init();
            Datetime.initForDatepicker();
            FormUtil.validate('#bill-detail');
        });
    </script>
@endsection
