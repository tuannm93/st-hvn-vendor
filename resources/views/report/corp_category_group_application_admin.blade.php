@extends('layouts.app')

@section('style')
@endsection

@section('content')
    <div class="container">
        <h5 class="font-weight-bold mt-3 mb-4">@lang('report_corp_cate_group_app_admin.title')</h5>

        @if (Session::has('error'))
            <p class="box__mess box--error">
                {{ Session::get('error') }}
            </p>
        @endif
        @if ($errors->any())
            <div class="box__mess box--error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div>@lang('report_corp_cate_group_app_admin.total') {{ $results->total() }} @lang('common.item')</div>
        <div class="table-responsive demand-list">
            @if($results->count() > 0)
                <table class="table custom-border">
                    <thead>
                        <tr class="bg-primary-lighter">
                            <th class="text-center fix-w-50">@lang("report_corp_cate_group_app_admin.col1")</th>
                            <th class="text-center">@lang("report_corp_cate_group_app_admin.col2")</th>
                            <th class="text-center fix-w-200">@lang("report_corp_cate_group_app_admin.col3")</th>
                            <th class="text-center">@lang("report_corp_cate_group_app_admin.col4")</th>
                            <th class="text-center">@lang("report_corp_cate_group_app_admin.col5")</th>
                            <th class="text-center">@lang("report_corp_cate_group_app_admin.col6")</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($results as $key => $result)
                        <tr>
                            <td class="text-right">{{ $result->id }}</td>
                            <td class="text-center">@lang('report_corp_cate_group_app_admin.default_value_col2')</td>
                            <td class="text-center"><a class="highlight-link" target="_blank" href="{{ route('affiliation.detail.edit', $result->m_corps_id) }}">{{ $result->official_corp_name }}</a></td>
                            <td class="text-center">{{ $result->created_user_id }}</td>
                            <td class="text-center">{{ $result->created }}</td>
                            <td class="text-center"><a class="highlight-link" target="_blank" href="{{ route('report.getCorpCategoryAppAdmin', $result->id) }}">{{ $result->application_count }}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if ($results->hasPages())
                    <div class="dataTables_paginate">
                        @if ($results->onFirstPage())
                            <a class="paginate_button disabled" rel="prev">&lt; @lang('common.prev_page')</a>
                        @else
                            <a class="paginate_button previous active" href="{{ $results->url($results->currentPage()-1) }}" rel="prev">&lt; {{ __('common.prev_page') }}</a>
                        @endif
                        <span class="pl-3 pr-3"></span>
                        @if ($results->hasMorePages())
                            <a class="paginate_button next active" href="{{ $results->url($results->currentPage()+1) }}" rel="next">{{ __('common.next_page') }} &gt;</a>
                        @else
                            <a class="paginate_button disabled" rel="next">@lang('common.next_page') &gt;</a>
                        @endif
                    </div>
                @endif
            @else
                <p>@lang("report_corp_cate_group_app_admin.no_record")</p>
            @endif
        </div>
    </div>
@endsection