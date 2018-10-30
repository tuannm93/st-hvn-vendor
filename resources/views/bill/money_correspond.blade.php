@extends('layouts.app')
@section('content')

<div class="bill-money-correspond container">
    <div class="row">
        <div class="group-header col-5 col-sm-4 col-md-2">
            <button class="btn btn--gradient-gray" onclick="javascript:window.close();">
                {{ __('money_correspond.close') }}
            </button>
        </div>
    </div>
    <input type="hidden" id="m_corp" value="{{ $corpId }}">
    {!! Form::open(['url' => route('bill.moneyCorrespond', ['corpId' => $corpId, 'sort' => request('sort')]), 'method' => 'get',
    'class'=>'form-horizontal fieldset-custom']) !!}
    <fieldset>
        <legend>{{ __('money_correspond.search_condition') }}</legend>
        <div class="form-search">
            <div class="row">
                <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                    <label class="col-form-label col-12 col-sm-12 col-md-3">
                        {{ __('money_correspond.nominee') }}
                    </label>
                    <input name="search_nominee" class="form-control col-12 col-sm-12 col-md-9" value="{{ request('search_nominee') }}" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-5 col-sm-4 col-md-3 form-inline">
                    <button class="btn btn--gradient-orange" type="submit">
                        {{ __('money_correspond.search_btn') }}
                    </button>
                </div>
            </div>
        </div>
    </fieldset>
    {!! Form::close() !!}
    <div class="form-category">
        <label class="form-category__label">{{ __('money_correspond.payment_history_information') }}</label>
        <div class="form-category__body">
            <div class="create-deposit">
                @if (Session::has('msg_create_deposit'))
                <div class="alert alert-success">{{ session('msg_create_deposit') }}</div>
                @endif
                @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->all()[0] }}</div>
                @endif
                {!! Form::open(['url' => route('bill.moneyAddDeposit'), 'id' => 'form-bill-money-correspond', 'novalidate']) !!}
                <div class="form-group row mb-2">
                    <label class="col-12 col-sm-12 col-md-3 col-form-label">{{ __('money_correspond.deposit_date') }}
                        <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                    </label>
                    <div class="col-12 col-sm-12 col-md-3">
                        <input type="text" data-rule-required="true" data-msg-required="{{ __('money_correspond.required') }}" name="payment_date" class="form-control datepicker input-insert" value="{{ $paymentDate }}" placeholder="" data-rule-date="true" data-rule-pattern="\d{4}/\d{1,2}/\d{1,2}" data-msg-pattern="{{ __('money_correspond.invalid_date') }}">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-12 col-sm-12 col-md-3 col-form-label">{{ __('money_correspond.nominee') }}
                        <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                    </label>
                    <div class="col-12 col-sm-12 col-md-3">
                        <input type="text" data-rule-required="true" data-msg-required="{{ __('money_correspond.required') }}" maxlength="200" id="nominee" name="nominee" class="form-control input-insert" value="{{ $nominee }}" placeholder="">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-12 col-sm-12 col-md-3 col-form-label">{{ __('money_correspond.deposit_amount') }}
                        <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                    </label>
                    <div class="col-10 col-sm-11 col-md-3">
                        <input type="text" data-rule-number="true" data-msg-number="{{ __('money_correspond.invalid_numeric') }}" maxlength="9" data-rule-required="true" data-msg-required="{{ __('money_correspond.required') }}" id="payment_amount" name="payment_amount" class="form-control input-insert" value="{{ $paymentAmount }}" placeholder="">
                    </div>
                    <label class="col-2 col-sm-1 col-form-label yen-label">{{trans('common.yen')}}</label>
                </div>
                <div class="form-group justify-content-left pt-4">
                    {{ Form::hidden( 'corp_id', $corpId ) }}
                    <button type="button" class="btn btn--gradient-gray" onclick="javascript:window.close();">{{ __('money_correspond.cancel') }}</button>
                    <button type="button" id="btn-insert" class="btn btn--gradient-green">{{ __('money_correspond.registration') }}</button>
                </div>
                {!! Form::close() !!}
                <div class="searchResult">
                    <div class="table-result-search">
                        <div class="table-responsive col-12 col-sm-12 col-md-8">
                            <table class="table table-bordered" id="main_table">
                                <thead>
                                    <tr>
                                        <th class="text-center w-16-7">
                                            {{ __('money_correspond.deposit_date') }}
                                            <a class="order-by order-asc" href="javascript:void(0)">{{ trans('common.asc') }}</a>
                                            <a class="order-by order-desc" href="javascript:void(0)">{{ trans('common.desc') }}</a>

                                        </th>
                                        <th class="text-center w-16-7">{{ __('money_correspond.nominee') }}</th>
                                        <th class="text-center w-20">{{ __('money_correspond.deposit_amount') }}</th>
                                        <th class="text-center w-5">{{ __('money_correspond.delete') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($listMoneyData))
                                    @foreach($listMoneyData as $money)
                                    <tr>
                                        <td class="text-center w-16-7">{{ $money->payment_date }}</td>
                                        <td class="text-left w-16-7">{{ $money->nominee }}</td>
                                        <td class="text-right w-20">{{ yenFormat2($money->payment_amount) }}</td>
                                        <td class="text-center w-5">
                                            <button class="btn btn-sm btn--gradient-gray btn-remove-deposit" data-id="{{$money->id}}">{{ __('money_correspond.delete') }}</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="dataTables_paginate">
                @if($listMoneyData->lastPage() != 1)
                    @if($listMoneyData->currentPage() != 1)
                        <a class="paginate_button previous active" href="{{ $listMoneyData->appends(['sort' => request('sort'), 'search_nominee' => request('search_nominee')])->previousPageUrl() }}" rel="prev" aria-controls="tbBillSearch" id="tbBillSearch_previous">{{ __('money_correspond.prev') }}</a>
                    @else
                        <a class="paginate_button previous disabled" rel="prev" aria-controls="tbBillSearch" id="tbBillSearch_previous">{{ __('money_correspond.prev') }}</a>
                    @endif
                    <span class="pl-3 pr-3"></span>
                    @if($listMoneyData->currentPage() != $listMoneyData->lastPage())
                        <a class="paginate_button next active" href="{{ $listMoneyData->appends(['sort' => request('sort'), 'search_nominee' => request('search_nominee')])->nextPageUrl() }}" rel="next" aria-controls="tbBillSearch" id="tbBillSearch_next">{{ __('money_correspond.next') }}</a>
                    @else
                        <a class="paginate_button next disabled" rel="next" aria-controls="tbBillSearch" id="tbBillSearch_next">{{ __('money_correspond.next') }}</a>
                    @endif
                @endif
            </div>

            <div class="form-group row justify-content-left pt-4">
                <button type="button" class="btn btn--gradient-gray" onclick="javascript:window.close();">{{ __('money_correspond.close') }}</button>
            </div>
        </div>
    </div>
</div>
<div id="message_comfirm" data-message-confirm="{{ __(" money_correspond.confirm_remove ") }}"></div>
<div id="route_remove" data-route="{{ route('bill.removeMoneyDeposit') }}"></div>

{{-- dialog confirm --}}
<div class="modal fade" id="dialogConfirmDelete" tabindex="-;1" role="dialog" aria-labelledby="dialogConfirmDelete" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('addition.confirm_popup_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('money_correspond.confirm_remove') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--gradient-gray" data-dismiss="modal">{{trans('addition.btn_cancel')}}</button>
                <button type="submit" class="btn btn--gradient-gray" id="btn_confirm">{{trans('addition.btn_confirm')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        var urlGetListMoneyData = '{{route('bill.orderMoneyCorrespond')}}';
    </script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/pages/bill.money_correspond.js') }}"></script>
@endsection
