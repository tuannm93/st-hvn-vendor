<div class="d-sm-block collapse show" id="searchBlockCollapse">
    {!! Form::open(['url' => route('commission.postSearch',$params),'accept-charset' => 'UTF-8', 'id' => 'searchForm']) !!}
    <div class="search-block p-3">
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-4">
                        <label for="demand_id">
                            @lang("commissioninfos.lbl.demand_id")
                        </label>
                    </div>
                    <div class="col-md-8">
                        {{Form::text('demand_id',($lastSearchData !== null) ? (isset($lastSearchData['demand_id']) ? $lastSearchData['demand_id'] : null) : null,['class'=>'form-control w-100','data-rule-number' => 'true', 'id' => 'demand_id'])}}
                    </div>
                </div>
                @if($errors->has('demand_id'))
                    <small class="text-danger offset-md-4 col-md-8 ">{{$errors->first('demand_id')}}</small>
                @endif
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-4">
                        <label for="">
                            @lang("commissioninfos.lbl.genre_id")
                        </label>
                    </div>
                    <div class="col-md-8">
                        {{Form::select('genre_id[]', $genres, ($lastSearchData !== null) ? (isset($lastSearchData['genre_id']) ? $lastSearchData['genre_id'] : null) : null, ['id' => 'genre_id', 'multiple' => true, 'class' => ' form-control' ])}}
                    </div>

                </div>
                @if($errors->has('genre_id'))
                    <small class="text-danger offset-md-4 col-md-8 ">{{$errors->first('genre_id')}}</small>
                @endif
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-5">
                        <label for="">
                            @lang("commissioninfos.lbl.customer_tel")
                        </label>
                    </div>
                    <div class="col-md-7">
                        {{Form::text('customer_tel',($lastSearchData !== null) ? (isset($lastSearchData['customer_tel']) ? $lastSearchData['customer_tel'] : null) : null,['class'=>'form-control w-100'])}}
                    </div>

                </div>
                @if($errors->has('customer_tel'))
                    <small class="text-danger d-flex justify-content-end">{{$errors->first('customer_tel')}}</small>
                @endif
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-5">
                        <label for="">
                            @lang("commissioninfos.lbl.customer_name")
                        </label>
                    </div>
                    <div class="col-md-7">
                        {{Form::text('customer_name',($lastSearchData !== null) ? (isset($lastSearchData['customer_name']) ? $lastSearchData['customer_name'] : null) : null,['class'=>' form-control w-100'])}}
                    </div>

                </div>

                @if($errors->has('customer_name'))
                    <small class="text-danger d-flex justify-content-end">{{$errors->first('customer_name')}}</small>
                @endif
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-5">
                        <label for="" class="commission_date">
                            @lang("commissioninfos.lbl.commission_date")
                        </label>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex flex-column w-45">
                            {{Form::text('commission_date1',($lastSearchData !== null) ? (isset($lastSearchData['commission_date1']) ? $lastSearchData['commission_date1'] : null) : null,['class'=>'w-100 datepicker form-control','data-rule-lessThanTime'=>'#commission_date2'])}}
                        </div>
                        <div class="d-inline-block">
                            {{trans('common.wavy_seal')}}
                        </div>
                        <div class="d-inline-flex flex-column w-45">
                            {{Form::text('commission_date2',($lastSearchData !== null) ? (isset($lastSearchData['commission_date2']) ? $lastSearchData['commission_date2'] : null) : null,['class'=>'w-100 datepicker form-control','id' => 'commission_date2'])}}
                        </div>
                    </div>


                    <div class="col-md-3">

                    </div>

                </div>
                @if($errors->has('commission_date1') || $errors->has('commission_date2'))
                    @if($errors->has('commission_date1'))
                        <small class="text-danger offset-md-5 col-md-8">{{$errors->first('commission_date1')}}</small>
                    @else
                        <small class="text-danger offset-md-5 col-md-8">{{$errors->first('commission_date2')}}</small>
                    @endif
                @endif
                <div class="row d-flex">
                    <div id="asap-numeric-fail-2" class="offset-md-5 col-md-8">

                    </div>
                    <div id="asap-numeric-fail-3" class="offset-md-8 col-md-3">

                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-4">
                        <label>
                            @lang("commissioninfos.lbl.visit_desired_time")
                        </label>
                    </div>
                    <div class="col-md-8">
                        {{Form::text('visit_desired_time',($lastSearchData !== null) ? (isset($lastSearchData['visit_desired_time']) ? $lastSearchData['visit_desired_time'] : null) : null,['class'=>'datepicker form-control w-100'])}}
                    </div>


                </div>
                @if($errors->has('visit_desired_time'))
                    <small class="text-danger offset-md-4 col-md-8 ">{{$errors->first('visit_desired_time')}}</small>
                @endif
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-5">
                        <label for="">
                            @lang("commissioninfos.lbl.contact_desired_time")
                        </label>
                    </div>
                    <div class="col-md-7">
                        {{Form::text('contact_desired_time',($lastSearchData !== null) ? (isset($lastSearchData['contact_desired_time']) ? $lastSearchData['contact_desired_time'] : null) : null,['class'=>'datepicker form-control w-100'])}}
                    </div>

                </div>
                @if($errors->has('contact_desired_time'))
                    <small class="text-danger offset-md-5 col-md-8">{{$errors->first('contact_desired_time')}}</small>
                @endif
            </div>

            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-5">
                        <label for="">
                            @lang("commissioninfos.lbl.jbr_order_no")
                        </label>
                    </div>
                    <div class="col-md-7">
                        {{Form::text('jbr_order_no',($lastSearchData !== null) ? (isset($lastSearchData['jbr_order_no']) ? $lastSearchData['jbr_order_no'] : null) : null,['class'=>'form-control w-100'])}}
                    </div>

                </div>

                @if($errors->has('jbr_order_no'))
                    <small class="text-danger justify-content-end">{{$errors->first('jbr_order_no')}}</small>
                @endif
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-inline">
                    <div class="col-md-4">
                        <label for="">
                            @lang("commissioninfos.lbl.commission_status")
                        </label>
                    </div>

                    <div class="col-md-8">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            {{Form::checkbox('commission_status[]', 3, ($lastSearchData !== null) ? (isset($lastSearchData['commission_status']) ? (in_array(3, $lastSearchData['commission_status']) ? true : false) : false) : false, ['id' => 'commission_status1', 'class' => 'custom-control-input ignore'])}}
                            <label class="custom-control-label"
                                   for="commission_status1">@lang("commissioninfos.lbl.commission_status1")</label>
                        </div>

                        <div class="custom-control custom-checkbox custom-control-inline">
                            {{Form::checkbox('commission_status[]', 2, false, ['id' => 'commission_status2', 'class' => 'custom-control-input ignore'])}}
                            <label class="custom-control-label"
                                   for="commission_status2">@lang("commissioninfos.lbl.commission_status2")</label>
                        </div>

                        <div class="custom-control custom-checkbox custom-control-inline">
                            {{Form::checkbox('commission_status[]', 1, false, ['id' => 'commission_status3', 'class' => 'custom-control-input ignore'])}}
                            <label class="custom-control-label"
                                   for="commission_status3">@lang("commissioninfos.lbl.commission_status3")</label>
                        </div>

                        <div class="custom-control custom-checkbox custom-control-inline">
                            {{Form::checkbox('commission_status[]', 4, false, ['id' => 'commission_status4', 'class' => 'custom-control-input ignore'])}}
                            <label class="custom-control-label"
                                   for="commission_status4">@lang("commissioninfos.lbl.commission_status4")</label>
                        </div>

                        <div class="custom-control custom-checkbox custom-control-inline">
                            {{Form::checkbox('commission_status[]', 5, false, ['id' => 'commission_status5', 'class' => 'custom-control-input ignore'])}}
                            <label class="custom-control-label"
                                   for="commission_status5">@lang("commissioninfos.lbl.commission_status5")</label>
                        </div>
                    </div>
                    @if($errors->has('commission_status'))
                        <small class="text-danger offset-md-4 col-md-10 ">{{$errors->first('commission_status')}}</small>
                    @endif
                    @if(session('commission_errors.commission_status'))
                        <small class="text-danger offset-md-4 col-md-10 ">{{session('commission_errors.commission_status')}}</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="search_btn_block text-center">
            {{Form::button(trans('commissioninfos.lbl.btn_search'),['name'=>'search','class'=>'btn_orange_m btnOver btnSearch btn btn--gradient-orange mb-3'])}}
            @if((\Auth::user()->auth == 'system' || \Auth::user()->auth == 'admin'|| \Auth::user()->auth == 'accounting_admin') && (isset($display) && $display == true))
                {{ Form::submit(trans('commissioninfos.lbl.btn_csv'), ['class' => 'btn_orange_m btnOver btn btn--gradient-orange', 'name' => 'csv_out', 'id' => 'btnExport']) }}
            @endif
        </div>
    </div>
    {!! Form::close() !!}
</div>
