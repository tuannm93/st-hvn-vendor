@extends('layouts.app')
@section('content')
    <div class="autocommission-corp-add">
        <a class="link-orange" href="{{route('autoCommissionCorp.index')}}">@lang('corp_add.auto_transfer')</a>
        <label class="form-category__label mt-2">@lang('corp_add.auto_agent')</label>
        {{Form ::open(['url' => route('autoCommissionCorp.postCorpAdd'), 'id' => 'corp_form'])}}
        <div class="row mx-0 border-bold border-bottom-0">
            <div class="col-12 text-sm-center font-weight-bold py-2 bg-yellow-light">@lang('corp_add.description')</div>
        </div>
        <div class="row mx-0 border-bold border-bottom-0">
            <div class="col-sm-6 text-sm-center font-weight-bold py-2 bg-yellow-light border-right-bold">@lang('corp_add.prefectures')</div>
            <div class="col-sm-6 py-2">{{ $prefecture }}</div>
            {{ Form::hidden('pref_cd', !empty($data['pref_cd'])? $data['pref_cd'] : '', ['id' => 'pref_cd']) }}
        </div>
        <div class="row mx-0 border-bold border-bottom-0">
            <div class="col-sm-6 text-sm-center font-weight-bold py-2 bg-yellow-light border-right-bold">@lang('corp_add.genre')</div>
            <div class="col-sm-6 py-2">{{ $getGenreName }}</div>
            {{ Form::hidden('genre_id', !empty($data['genre_id'])? $data['genre_id'] : '', ['id' => 'genre_id']) }}
        </div>
        <div class="row mx-0 border-bold border-bottom-0">
            <div class="col-sm-6 text-sm-center font-weight-bold py-2 bg-yellow-light border-right-bold">@lang('corp_add.category')</div>
            <div class="col-sm-6 py-2">{{ $getCategoryName }}</div>
            {{ Form::hidden('category_id', !empty($data['category_id'])? $data['category_id'] : '', ['id' => 'category_id']) }}
        </div>
        <div id="selection-1" class="row mx-0 p-2 border-bold border-bottom-0">
            <div class="col-xl-6 py-1 header">
                <label class="col-sm-3 col-xl-5 col-form-label font-weight-bold">@lang('corp_add.auto_transaction')</label>
                {!! Form::button(trans('corp_add.target_commission_to_target_selection'), ['class' => 'btn btn--gradient-default px-4 py-1', 'data' => 'target_commission_to_target_selection']) !!}
                {!! Form::button(trans('corp_add.target_commission_to_corp_id'), ['class' => 'btn btn--gradient-default px-4 py-1', 'data' => 'target_commission_to_corp_id']) !!}
            </div>
            <div class="col-xl-6 py-1 header">
                <label class="col-md-3 col-form-label">@lang('corp_add.priority') :</label>
                {!! Form::button('<i class="triangle-up mr-1" aria-hidden="true"></i>'. trans('corp_add.upward'), ['class' => 'btn btn--gradient-default px-3 py-1', 'data' => 'commission_item_up']) !!}
                {!! Form::button('<i class="triangle-down mr-1" aria-hidden="true"></i>'. trans('corp_add.updown'), ['class' => 'btn btn--gradient-default px-3 py-1', 'data' => 'commission_item_down']) !!}
            </div>
            <div class="col-12 px-0">
                {!!
             Form::select('commission_corp_id[]', $corpCommissionList, null,['size' => 10 , 'id' => 'target_commission_corp_id','class' => 'form-control body','multiple' => true]);
             !!}
            </div>
        </div>
        <div id="selection-2" class="row mx-0 p-2 border-bold border-bottom-0">
            <div class="col-xl-6 py-1 header">
                <label class="col-sm-3 col-xl-5 col-form-label font-weight-bold">@lang('corp_add.auto_select')</label>
                {!! Form::button(trans('corp_add.target_selection_to_target_commission'), ['class' => 'btn btn--gradient-default px-4 py-1', 'data' => 'target_selection_to_target_commission']) !!}
                {!! Form::button(trans('corp_add.target_commission_to_corp_id'), ['class' => 'btn btn--gradient-default px-4 py-1', 'data' => 'target_selection_to_corp_id']) !!}
            </div>
            <div class="col-xl-6 py-1 header">
                <label class="col-md-3 col-form-label">@lang('corp_add.priority') :</label>
                {!! Form::button('<i class="triangle-up mr-1" aria-hidden="true"></i>'. trans('corp_add.upward'), ['class' => 'btn btn--gradient-default px-3 py-1', 'data' => 'selection_item_up']) !!}
                {!! Form::button('<i class="triangle-down mr-1" aria-hidden="true"></i>'. trans('corp_add.updown'), ['class' => 'btn btn--gradient-default px-3 py-1', 'data' => 'selection_item_down']) !!}
            </div>
            <div class="col-12 px-0">
                {!!
                Form::select('selection_corp_id[]', $corpSelectionList, null,['id' => 'target_selection_corp_id','class' => 'form-control body','multiple' => true, 'size' => 10]);
                !!}
            </div>
        </div>
        <div id="selection-3" class="row mx-0 p-2 border-bold border-bottom-0">
            <div class="col-xl-6 py-1 header">
                <label class="col-sm-3 col-xl-5 col-form-label font-weight-bold">@lang('corp_add.unselect')</label>
                {!! Form::button(trans('corp_add.target_selection_to_target_commission'), ['class' => 'btn btn--gradient-default px-4 py-1', 'data' => 'corp_id_to_target_commission']) !!}
                {!! Form::button(trans('corp_add.target_commission_to_target_selection'), ['class' => 'btn btn--gradient-default px-4 py-1', 'data' => 'corp_id_to_target_selection']) !!}
            </div>
            <div class="col-xl-6 py-1 header"></div>
            <div class="col-12 px-0">
                {!!
               Form::select('corp_list[]', [], true,['id' => 'corp_id','class' => 'form-control','multiple' => true, 'size' => 10]);
               !!}
            </div>
            <div class="col-sm-4 col-lg-2 px-0 py-1">
                {{ Form::select('search_key',['search_by_name' => '加盟店名で検索','search_by_id' => '加盟店IDで検索'],true,['class' => 'custom-select', 'id' => 'search_key']) }}
            </div>
            <div class="offset-lg-10 offset-sm-9"></div>
            <div class="col-xl-8 px-0 py-1">
                {{ Form::text('search_by_name', null, ['id' => 'search_by_name', 'class' => 'form-control', 'aria-label' => 'placeholder', 'placeholder' => trans('corp_add.search_name')]) }}
                {{ Form::textarea('search_by_id', null, ['id' => 'search_by_id', 'class' => 'form-control', 'aria-label' => 'placeholder', 'placeholder' => trans('corp_add.search_id'), 'rows' => 1,'display' => 'none']) }}
            </div>
            <div class="offset-xl-4"></div>
            <div class="col-12 px-0">
                {!! Form::button(trans('corp_add.get_corp'), ['class' => 'btn btn--gradient-orange px-3 py-1 text--white', 'id' => 'get_corp']) !!}
            </div>
            <div class="col-12 px-0">
                <span id="ajax_messages"></span>
            </div>
        </div>
        <div class="row mx-0 py-2 text-center border-bold">
            <div class="col-12 p-2">
                {!! Form::button(trans('corp_add.back'), ['class' => 'btn btn--gradient-default col-sm-3 col-xl-2 mb-2 mb-sm-0 mr-sm-2', 'id' => 'corp_add', 'name' => 'corp_add']) !!}
                {!! Form::submit(trans('corp_add.save'), ['class' => 'btn btn--gradient-green col-sm-3 col-xl-2', 'id' => 'corp_select','name' => 'corp_save']) !!}
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/auto.commission.corp.corp.add.js') }}"></script>
    <script>
        var url_get_corp_add_list = '{{route('autoCommissionCorp.getCorpAddList')}}';
        var url_auto_commission_corp_index = '{{route('autoCommissionCorp.index')}}';
        var loading_message = '@lang('corp_add.loading_message')';
        AutoCommissionCorpCorpAdd.init();
    </script>
@endsection()