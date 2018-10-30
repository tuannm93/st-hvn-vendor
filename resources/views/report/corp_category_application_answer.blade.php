@extends('layouts.app')

@section('style')
@endsection

@section('content')
    <div class="container corp_category_app">
        <h4 class="text-title">@lang("report_corp_cate_app_answer.title")</h4>

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

        <div class="table-responsive">
            @if(count($results) > 0)
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="text-center p-1 bg-yellow w-15">@lang("report_corp_cate_app_answer.official_corp_name")</td>
                        <td colspan=3 class="text-center p-1">
                            <a href="{{route("affiliation.detail.edit", ["id" => $results[0]->corp_id])}}"
                               target="_blank">{{$results[0]->official_corp_name}}</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-1 bg-yellow w-15">@lang("report_corp_cate_app_answer.application_user")</td>
                        <td class="text-center p-1 w-30">{{$results[0]->application_user_id}}</td>
                        <td class="text-center p-1 bg-yellow w-25">@lang("report_corp_cate_app_answer.application_datetime")</td>
                        <td class="text-center p-1 w-30">{{$results[0]->application_datetime}}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="table-responsive">
                    <table class="table table-bordered table-list-report">
                        <thead>
                            <tr>
                                <th class="text-center p-1">@lang("report_corp_cate_app_answer.col1")</th>
                                <th class="text-center p-1">@lang("report_corp_cate_app_answer.col2")</th>
                                <th class="text-center p-1">@lang("report_corp_cate_app_answer.col3")</th>
                                <th class="text-center p-1">@lang("report_corp_cate_app_answer.col4")</th>
                                <th class="text-center p-1">@lang("report_corp_cate_app_answer.col5")</th>
                                <th rowspan="2" class="text-center p-1 align-middle">@lang("report_corp_cate_app_answer.col6")</th>
                                <th rowspan="2" class="text-center p-1 align-middle">@lang("report_corp_cate_app_answer.col7")</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-center p-1">@lang("report_corp_cate_app_answer.col8")</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $key => $result)
                            <tr>
                                <td class="text-center p-1">{{$result->id}}</td>
                                <td class="text-center p-1">{{!empty($result->genre_name) ? $result->genre_name : ""}}</td>
                                <td class="text-center p-1">{{!empty($result->category_name) ? $result->category_name : ""}}</td>
                                <td class="text-center p-1">
                                    <?php
                                    if (!empty($result->order_fee)) {
                                        if (isset($result->order_fee_unit)) {
                                            if ($result->order_fee_unit == 1) {
                                                $result->order_fee .= trans("report_corp_cate_app_answer.percent");
                                            } elseif ($result->order_fee_unit == 0) {
                                                $result->order_fee .= trans("report_corp_cate_app_answer.money");
                                            };
                                        }
                                        echo $result->order_fee;
                                    }
                                    $cssStatus = "";
                                    if ($result->status == -2) {
                                        $cssStatus = "bg-yellow";
                                    } else if ($result->status == -1) {
                                        $cssStatus = "bg-yellow";
                                    } else if ($result->status == 1) {
                                        $cssStatus = "bg-green";
                                    } else if ($result->status == 2) {
                                        $cssStatus = "bg-red";
                                    }
                                    ?>
                                </td>
                                <td class="text-center p-1">{{!empty($result->introduce_fee) ? $result->introduce_fee . "円" : ""}}</td>
                                <td rowspan="2"
                                    class="text-center p-1 align-middle">{{!empty($result->application_reason) ? $result->application_reason : ""}}</td>
                                <td rowspan="2" class="text-center p-1 align-middle {{$cssStatus}}">
                                    {{$mItems[$result->status]}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left p-1 align-middle">
                                    <?php
                                    $text = trans("report_corp_cate_app_answer.corp_commission_type.text");
                                    if ($result->corp_commission_type) {
                                        $text .= '';
                                        if (!empty($result->corp_commission_type)) {
                                            if ($result->corp_commission_type == 1) {
                                                $text .= trans("report_corp_cate_app_answer.corp_commission_type.type1");
                                            } elseif ($result->corp_commission_type == 2) {
                                                $text .= trans("report_corp_cate_app_answer.corp_commission_type.type2");
                                            }
                                        } else {
                                            $text .= trans("report_corp_cate_app_answer.corp_commission_type.type");
                                        }
                                    }
                                    if ($result->note) {
                                        $text .= trans("report_corp_cate_app_answer.note.text");
                                        if (!empty($result->note)) {
                                            $text .= $result->note.', ';
                                        } else {
                                            $text .= trans("report_corp_cate_app_answer.note.empty");
                                        }
                                    }

                                    if (!empty($text)) {
                                        echo substr($text, 0, -2);
                                    } else {
                                        echo "&nbsp;";
                                    }
                                    ?>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($results->total() > $results->perPage())
                    <div class="dataTables_paginate">
                        @if($results->previousPageUrl())
                            <a class="paginate_button previous active" href="{{ $results->previousPageUrl() }}">@lang('report_corp_cate_app_answer.prev_page')</a>
                        @else
                            <a class="paginate_button disabled">@lang('report_corp_cate_app_answer.prev_page')</a>
                        @endif
                        <span class="pl-3 pr-3"></span>
                        @if($results->nextPageUrl())
                            <a class="paginate_button next active" href="{{ $results->nextPageUrl() }}">@lang('report_corp_cate_app_answer.next_page')</a>
                        @else
                            <a class="paginate_button disabled">@lang('report_corp_cate_app_answer.next_page')</a>
                        @endif
                    </div>
                @endif
            @else
                <p>@lang("report_corp_cate_app_answer.no_record")</p>
            @endif
        </div>
    </div>
@endsection

@section('script')
@endsection
