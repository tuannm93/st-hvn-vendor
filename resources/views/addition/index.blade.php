@extends('layouts.app')
@section('content')
    @php
        $isError = $errors->any();
    @endphp
    <div id="addition-index">
        @if ($isError)
            <div class="alert alert-danger">
                {{ trans('addition.validate_fail') }}
            </div>
        @endif
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
        <div class="form-category">
            <label class="form-category__label">{{trans('addition.list_title')}}
                <a class="form-category__label__collapse float-right" id="dropdownMenuButton" href="#collapseTable"
                   aria-expanded="false"
                   data-toggle="collapse" aria-controls="collapseTable">
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </a>
            </label>
            <div class="collapse show" id="collapseTable">
                <div class="form-category__body table-responsive">
                    <table class="table table-list table-bordered" id="tblAddition">
                        <thead>
                        <tr>
                            <th>{{trans('addition.demand_id')}}</th>
                            <th>{{trans('addition.customer_name')}}</th>
                            <th>{{trans('addition.genre_name')}}</th>
                            <th>{{trans('addition.construction_price_tax_exclude')}}</th>
                            <th>{{trans('addition.complete_date')}}</th>
                            <th>{{trans('addition.demand_type_update')}}</th>
                            <th>{{trans('addition.note')}}</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $addition)
                            <tr>
                                <td data-label="{{trans('addition.demand_id')}}">{{$addition->demand_id}}</td>
                                <td data-label="{{trans('addition.customer_name')}}">{{$addition->customer_name}}
                                    <div></div> {{-- use for mobile screen --}}
                                </td>
                                <td data-label="{{trans('addition.genre_name')}}">@isset($addition->genres->genre_name) {{$addition->genres->genre_name}}@endif
                                    <div></div> {{-- use for mobile screen --}}
                                </td>
                                <td class="table__text-align text-sm-right"
                                    data-label="{{trans('addition.construction_price_tax_exclude')}}">{{ formatMoney($addition->construction_price_tax_exclude) }} {{trans('common.yen')}}
                                    <div></div> {{-- use for mobile screen --}}
                                </td>
                                <td class="table__text-align text-sm-center"
                                    data-label="{{trans('addition.complete_date')}}">{{$addition->complete_date}}
                                    <div></div> {{-- use for mobile screen --}}
                                </td>
                                <td class="d-none d-lg-table-cell" data-label="{{trans('addition.demand_type_update')}}">@if(!empty($addition->demand_type_update)){{$demandType[$addition->demand_type_update]}}@endif
                                    <div></div> {{-- use for mobile screen --}}
                                </td>
                                <td data-label="{{trans('addition.note')}}" class="table__text-align">
                                    {{mb_strimwidth($addition->note, 0, 24, '…', 'UTF-8')}}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn--gradient-gray btn-del" data-toggle="modal"
                                            data-target="#dialogConfirmDelete"
                                            data-id="{{$addition->id}}">{{trans('addition.delete')}}</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($isMobile)
                    {{$results->links('pagination.default')}}
                @endif
            </div>
        </div>
        <div class="hidden" id="delURL" data="{{route('addition.delete')}}"></div>
        <div class="form-category">
            <label class="form-category__label">{{trans('addition.form_title')}}</label>
            <div class="form-category__body pl-2 pt-2">
                <form action="{{route('addition.regist')}}" method="post" novalidate id="form-addition">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                    <div class="form-group row mb-2">
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.demand_id')}}</label>
                        <div class="col-12 col-sm-12 col-md-2">
                            <input type="text" maxlength="50" name="demand_id" class="form-control"
                                   value="{{ $demandInfos ? $demandInfos->id : '' }}" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row mb-2 {{ $errors->has('customer_name') ? 'has-error' : '' }}">
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.customer_name')}}
                            <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                        </label>
                        <div class="col-12 col-sm-12 col-md-3">
                            <input type="text" name="customer_name" data-rule-required="true" maxlength="50"
                                   class="form-control" value="{{ $demandInfos ? $demandInfos->customer_name : '' }}" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row mb-2 {{ $errors->has('genre_id') ? 'has-error' : '' }}">
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.genre_name')}}
                            <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                        </label>
                        <div class="col-12 col-sm-12 col-md-4">
                            <select class="custom-select" name="genre_id" data-rule-required="true">
                                <option value="" selected>{{trans('common.none')}}</option>
                                @foreach($genreList as $genre)
                                    <option @if(old('genre_id') == $genre->id) selected='selected'
                                            @endif value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2 {{ $errors->has('number') ? 'has-error' : '' }}">
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.construction_price_tax_exclude')}}
                            <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                        </label>
                        <div class="col-10 col-sm-12 col-md-2">
                            <input type="text" data-rule-number="true" data-rule-required="true" maxlength="10"
                                   class="form-control" name="construction_price_tax_exclude"
                                   value="{{ old('construction_price_tax_exclude') }}" placeholder="">
                        </div>
                        <label class="col-2 col-form-label yen-label">{{trans('common.yen')}}</label>
                    </div>
                    <div class="form-group row mb-2 {{ $errors->has('complete_date') ? 'has-error' : '' }}">
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.complete_date')}}
                            <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                        </label>
                        <div class="col-12 col-sm-12 col-md-2">
                            <input data-rule-required="true" type="text" id="complete_date"
                                   name="complete_date" class="form-control datepicker_limit"
                                   value="{{ old('complete_date') }}" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row mb-2 {{ $errors->has('demand_type_update') ? 'has-error' : '' }}" {{ $isMobile ? 'hidden' : '' }}>
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.demand_type_update')}}
                            <span class="badge badge-warning float-right">{{trans('addition.required')}}</span>
                        </label>
                        <div class="col-12 col-sm-12 col-md-6" data-group-required="{{ $isMobile ? 'false' : 'true' }}">
                            @foreach(Config::get('constant.demand_type_update') as $key => $demandType)
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" name="demand_type_update" type="radio"
                                           @if(old('demand_type_update') == $key) checked='checked' @endif
                                           id="exampleRadios{{$key}}" value="{{$key}}">
                                    <label class="custom-control-label" for="exampleRadios{{$key}}">
                                        {{$demandType}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-12 col-sm-12 col-md-2 col-form-label">{{trans('addition.note_column')}}</label>
                        <div class="col-12 col-sm-12 col-md-8">
                            <textarea maxlength="1000" class="form-control fixed" name="note"
                                      rows="8">{{ old('note') }}</textarea>
                        </div>
                    </div>
                    <input type="hidden" name="falsity_flg" value="1">
                    <div class="form-group row justify-content-center pt-4">
                        <button type="submit"
                                class="btn btn--gradient-green col-12 col-sm-12 col-md-2">{{trans('addition.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--  dialog confirm  --}}
    <div class="modal fade" id="dialogConfirmDelete" tabindex="-;1" role="dialog" aria-labelledby="dialogConfirmDelete"
         aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{route('addition.delete')}}" method="post">
                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{trans('addition.confirm_popup_title')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{trans('addition.confirm_popup_content')}}
                    </div>
                    <input type="hidden" name="addition_id" id="addition_id" value=""/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--gradient-gray fix-w-92"
                                data-dismiss="modal">{{trans('addition.btn_cancel')}}</button>
                        <button type="submit" class="btn btn--gradient-gray fix-w-92"
                                id="btn_confirm">{{trans('addition.btn_confirm')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/addition.js') }}"></script>
@endsection
