@extends('layouts.app')
@section('content')
    <div class="autocommission-add">
        <div id="get-error"></div>
        @if ($errors->any())
            <div class="box__mess box--error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        {!! Form::open(['url' => route('autoCommissionCorp.postAdd')]) !!}
        <div class="row mx-0 mb-2 mt-4">
            <div class='offset-sm-3 col-sm-8'>
                <div class="row mx-0">
                    {!! Form::select('search_key', ['search_by_name' => trans('auto_commission_corp.search_by_name'), 'search_by_id' => trans('auto_commission_corp.search_by_id')],false,['class' => 'form-control col-6 col-sm-4','id' => 'search_key']) !!}
                    <button type="button" id="get_corp" class="btn btn--gradient-orange remove-effect-btn col-3 col-sm-2 ml-1">@lang('auto_commission_corp.get_corp')</button>
                </div>
            </div>
        </div>
        <div class="row mx-0 mb-2">
            <div class='offset-sm-3 col-sm-8'>
                {!! Form::text('name','',['class' => 'form-control', 'id' => 'search_by_name', 'placeholder' => trans('auto_commission_corp.text_search_name')]) !!}
                {!! Form::textarea('id','',['class' => 'form-control','rows' => 1, 'id' => 'search_by_id', 'placeholder' => trans('auto_commission_corp.text_search_id')]) !!}
            </div>
        </div>
        <div class="row mx-0 mb-2">
            <label class="offset-sm-3 col-sm-8"><span id="ajax_response"></span></label>
        </div>
        <div class="row mx-0 mb-2">
            <label class="col-sm-3 col-form-label text-sm-center font-weight-bold">@lang('auto_commission_corp.franchise_store')</label>
            <div class='col-sm-8'>
                {!! Form::select('corp_id',[],false,['class'=>'form-control', 'id' => 'corp_id', 'size' => 10]) !!}
            </div>
        </div>
        <div class="row mx-0 mb-2">
            <label class="col-sm-3 col-form-label text-sm-center font-weight-bold">@lang('auto_commission_corp.genre')</label>
            <div class="col-sm-4">
                <select id="genre_id" name="genre_id">
                    <option>{{trans('auto_commission_corp.none')}}</option>
                </select>
                {{--{!! Form::select('genre_id',['' => trans('auto_commission_corp.none')], false,['id' => 'genre_id']) !!}--}}
            </div>
        </div>
        <div class="row mx-0 mb-2">
            <label class="col-sm-3 col-form-label text-sm-center font-weight-bold">@lang('auto_commission_corp.category')</label>
            <div class="col-sm-4">
                {!! Form::select('category_id[]',['' => trans('auto_commission_corp.none')], true,['id' => 'category_id', 'multiple' => true]) !!}
            </div>
        </div>
        <div class="row mx-0 mb-2">
            <label class="col-sm-3 col-form-label text-sm-center font-weight-bold">@lang('auto_commission_corp.prefectures')</label>
            <div class="col-sm-4">
                {!! Form::select('jis_cd[]',$jisCd, false,['id' => 'jis_cd', 'multiple' => true]) !!}
            </div>
        </div>
        <div class="row mx-0 mb-2">
            <label class="col-sm-3 col-form-label text-sm-center font-weight-bold">@lang('auto_commission_corp.automatic')</label>
            <div class="col-sm-8 d-flex align-items-center">
                <div class="custom-control custom-radio custom-control-inline">
                    {!! Form::radio('process_type', 2, false,['class' => 'custom-control-input', 'id' => 'process_type-2']) !!}
                    <label class="custom-control-label"
                           for="process_type-2">@lang('auto_commission_corp.process_type_2')</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    {!! Form::radio('process_type', 1,false,['class' => 'custom-control-input', 'id' => 'process_type-1']) !!}
                    <label class="custom-control-label"
                           for="process_type-1">@lang('auto_commission_corp.process_type_1')</label>
                </div>
            </div>
            <span class="offset-sm-3 col-sm-9 text-danger" id="required_process_type"></span>
        </div>
        <div class="row mx-0 text-center">
            <div class="col-12">
                {!! Form::button(trans('auto_commission_corp.return'), ['class' => 'btn btn--gradient-default remove-effect-btn col-sm-3 col-lg-2 mb-2', 'id' => 'back']) !!}
                {!! Form::submit(trans('auto_commission_corp.submit'), ['class' => 'btn btn--gradient-green remove-effect-btn col-sm-3 col-lg-2 mb-2', 'id' => 'save']) !!}
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
@section('script')
    <script>
        var url_get_corp_list = '{{route('autoCommissionCorp.getCorpList')}}';
        var url_get_genre_list_by_corp_id = '{{route('autoCommissionCorp.getGenreList')}}';
        var url_get_category_by_genre_id_corp_id = '{{route('autoCommissionCorp.getCategoryList')}}';
        var url_auto_commission_corp_index = '{{route('autoCommissionCorp.index')}}';
        var un_select = '@lang('auto_commission_corp.none')';
        var check_all = '@lang('auto_commission_corp.check_all')';
        var un_check_all = '@lang('auto_commission_corp.un_check_all')';
        var required_corp_id = '@lang('auto_commission_corp.required_corp_id')';
        var required_category_id = '@lang('auto_commission_corp.required_category_id')';
        var required_genre_id = '@lang('auto_commission_corp.required_genre_id')';
        var required_jicd = '@lang('auto_commission_corp.required_jicd')';
        var required_process_type = '@lang('auto_commission_corp.required_process_type')';
        var ajax_message_loading = '@lang('auto_commission_corp.ajax_message_loading')';
        var ajax_message_loading_kameiten_fail = '@lang('auto_commission_corp.ajax_message_loading_kameiten_fail')';
    </script>
    <script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
    <script src="{{ mix('js/pages/auto_commission_corp_add.js') }}"></script>
@endsection()
