@extends('layouts.app')
@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="demand-list">
        <form id="demand_info" class="form-horizontal fieldset-custom" method="POST"
              action="{{ route('demandlist.post.search') }}">
            {{ csrf_field() }}
            <fieldset>
                <legend class="fs-13">{{ trans('demandlist.search_condition') }}</legend>
                <div class="form-container box--bg-gray box--border-gray p-2">
                    <div class="row mx-0 mb-2">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2 mb-sm-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.company_name') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[corp_name]', isset($conditions['corp_name']) ? $conditions['corp_name'] : null, ['id' => 'corp_name', 'class' => 'form-control'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.company_name_furigana') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[corp_name_kana]', isset($conditions['corp_name_kana']) ? $conditions['corp_name_kana'] : null, ['id' => 'corp_name_kana', 'class' => 'form-control'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 mb-2">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2 mb-sm-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.customer_phone_number') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[customer_tel]', isset($conditions['customer_tel']) ? $conditions['customer_tel'] : null, ['id' => 'customer_tel', 'class' => 'form-control'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.customer_name') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[customer_name]', null, ['id' => 'customer_name', 'class' => 'form-control'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 mb-2">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2 mb-sm-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.opportunity_id') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[id]', null, ['id' => 'id', 'class' => 'form-control', 'data-rule-number' => 'true'])}}
                                    @if ($errors->has('data.id'))
                                        <p class="form-control-feedback text-danger my-2 has-danger">{{$errors->first('data.id')}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.proposal_status') }}</label>
                                </div>
                                <div class="col-sm-7 px-0">
                                    {{Form::select('data[demand_status][]',$itemLists, isset($conditions['demand_status']) ? $conditions['demand_status'] : null,['id'=>'demand_status', 'multiple'=>'multiple', 'class'=>'multiple_check_filter', 'style' => 'display:none'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 mb-2">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2 mb-sm-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.site_name') }}</label>
                                </div>
                                <div class="col-sm-7 px-0">
                                    {{Form::select('data[site_id][]',$siteLists, isset($conditions['site_id']) ? $conditions['site_id'] : null,['id'=>'site_id', 'multiple'=>'multiple', 'class'=>'multiple_check_filter w-100' , 'style' => 'display:none'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.JBR_reception_like_no') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[jbr_order_no]', null, ['id' => 'jbr_order_no', 'class' => 'form-control'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 mb-2">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2 mb-sm-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.contact_deadline_date_and_time') }}</label>
                                </div>
                                <div class="col-sm-8 px-0 pr-sm-3">
                                    <div class="row mx-0">
                                        <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                            <input name="data[from_contact_desired_time]"
                                                   class="form-control datetimepicker w-100"
                                                   type="text"
                                                   id="from_contact_desired_time"
                                                   value="{{ isset($conditions['from_contact_desired_time']) ? $conditions['from_contact_desired_time'] : '' }}"
                                                   data-rule-lessThanTime="#to_contact_desired_time">
                                            @if ($errors->has('data.from_contact_desired_time'))
                                                <p class="form-control-feedback text-danger my-2 has-danger">{{$errors->first('data.from_contact_desired_time')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                        <div class="col-sm-5 px-0">
                                            <input name="data[to_contact_desired_time]"
                                                   class="form-control datetimepicker w-100"
                                                   type="text"
                                                   id="to_contact_desired_time"
                                                   value="{{ isset($conditions['to_contact_desired_time']) ? $conditions['to_contact_desired_time'] : '' }}">
                                            @if ($errors->has('data.to_contact_desired_time'))
                                                <p class="form-control-feedback text-danger my-2 has-danger">{{$errors->first('data.to_contact_desired_time')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0" for="b_check">{{ trans('demandlist.b_check') }}</label>
                                </div>
                                <div class="col-sm-7 custom-checkbox custom-control d-flex align-items-center">
                                    {{Form::input('checkbox', 'data[b_check]', null, ['id' => 'b_check', 'class' => 'custom-control-input ignore', (isset($conditions['b_check']) && $conditions['b_check']=='on') ? 'checked' : ''])}}
                                    <label class="custom-control-label custome-label" for="b_check"><span
                                                class="d-none">a</span></label>
                                    <input class="d-none d-lg-inline-block opacity-0 form-control col-1" tabindex="-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 mb-2">
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0 mb-2 mb-sm-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.reception_date_and_time') }}</label>
                                </div>
                                <div class="col-sm-8 px-0 pr-sm-3">
                                    <div class="row mx-0">
                                        <div class="col-sm-5 px-0 mb-1 mb-sm-0">
                                            <input name="data[from_receive_datetime]"
                                                   class="form-control datetimepicker w-100"
                                                   type="text"
                                                   id="from_receive_datetime"
                                                   value="{{ isset($conditions['from_receive_datetime']) ? $conditions['from_receive_datetime'] : '' }}"
                                                   data-rule-lessThanTime="#to_receive_datetime">
                                            @if ($errors->has('data.from_receive_datetime'))
                                                <p class="form-control-feedback text-danger my-2 has-danger">{{$errors->first('data.from_receive_datetime')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-1 d-none d-sm-block px-0 fs-20 text-center">&#x223C;</div>
                                        <div class="col-sm-5 px-0">
                                            <input name="data[to_receive_datetime]"
                                                   class="form-control datetimepicker w-100"
                                                   type="text" id="to_receive_datetime"
                                                   value="{{ isset($conditions['to_receive_datetime']) ? $conditions['to_receive_datetime'] : '' }}">
                                            @if ($errors->has('data.to_receive_datetime'))
                                                <p class="form-control-feedback text-danger my-2 has-danger">{{$errors->first('data.to_receive_datetime')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 px-0">
                            <div class="row mx-0">
                                <div class="col-sm-4 px-0 d-flex align-items-center">
                                    <label class="mb-0">{{ trans('demandlist.company_id') }}</label>
                                </div>
                                <div class="col-sm-6 col-md-5 px-0">
                                    {{Form::input('text', 'data[corp_id]', isset($conditions['corp_id']) ? $conditions['corp_id'] : null, ['id' => 'corp_id', 'class' => 'form-control', 'data-rule-number' => 'true'])}}
                                </div>
                            </div>
                            <div class="row mx-0">
                                <div class="offset-sm-4 col-sm-7 px-0 text--info fs-11">{{ trans('demandlist.ignore_company_name') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 mb-2 d-flex flex-column flex-sm-row">
                        <input type="button"
                               class="btn btn--gradient-orange remove-effect-btn col-lg-2 col-sm-3 mb-1 mb-sm-0"
                               onclick="location.href='{{ route('demand.get.create') }}';"
                               value="{{ trans('demandlist.sign_up') }}">
                        <button name="submit-search" id="search"
                                class="btn btn--gradient-orange  remove-effect-btn col-lg-2 col-sm-3 mx-sm-2 mb-1 mb-sm-0"
                                type="button"
                                value="search">{{ trans('demandlist.search') }}</button>

                        @if($auth=='system' || $auth=='admin' || $auth=='accounting_admin')
                            <button name="submit-csv"
                                    class="btn btn--gradient-orange remove-effect-btn col-lg-2 col-sm-3 d-none"
                                    type="submit" id="demand_csv"
                                    value="csv">{{ trans('demandlist.CSV_output') }}</button>
                        @endif
                    </div>
                </div>
            </fieldset>
            <div class="custom-scroll-x demand_info_table" data-trigger="{{ isset($triggerSearch) ? $triggerSearch : '' }}" data-url="{{ route('demandlist.post.search') }}">
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/demand_ajax.js') }}"></script>
    <script src="{{ mix('js/lib/custom.js') }}"></script>
    <script src="{{ mix('js/pages/demandinfo.js') }}"></script>
    <script>
        FormUtil.validate('#demand_info');
    </script>
@endsection
