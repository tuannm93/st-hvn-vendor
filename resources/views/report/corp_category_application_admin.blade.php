@extends('layouts.app')

@section('style')
@endsection

@section('content')
    <div class="container corp_category_app">
        <h4 class="text-title">@lang("report_corp_cate_app_admin.title")</h4>

        @if (Session::has('error'))
            <p class="box__mess box--error">
                {{ Session::get('error') }}
            </p>
        @endif
        @if (Session::has('success'))
            <p class="box__mess box--success">
                {{ Session::get('success') }}
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
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="text-center p-1 bg-yellow w-15">@lang("report_corp_cate_app_admin.official_corp_name")</td>
                            <td colspan=3 class="text-center p-1">
                                <a href="{{route("affiliation.detail.edit", ["id" => $results[0]->corp_id])}}"
                                   target="_blank">{{$results[0]->official_corp_name}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center p-1 bg-yellow w-15">@lang("report_corp_cate_app_admin.application_user")</td>
                            <td class="text-center p-1 w-30">{{$results[0]->application_user_id}}</td>
                            <td class="text-center p-1 bg-yellow w-25">@lang("report_corp_cate_app_admin.application_datetime")</td>
                            <td class="text-center p-1 w-30">{{$results[0]->application_datetime}}</td>
                        </tr>
                    </tbody>
                </table>
                {{ Form::open(["url" => route("report.postCorpCategoryAppAdmin", ["groupId" => $groupId]), "method" => "post"]) }}
                    <button class="btn btn--gradient-default pull-right checkAll btn-w200" type="button" data-mode="0">@lang("report_corp_cate_app_admin.check_all.check")</button>
                    <table class="table table-bordered">
                        <thead class="bg-yellow">
                            <tr>
                                <th class="text-center p-1 w-7">@lang("report_corp_cate_app_admin.col1")</th>
                                <th class="text-center p-1 w-15">@lang("report_corp_cate_app_admin.col2")</th>
                                <th class="text-center p-1 w-15">@lang("report_corp_cate_app_admin.col3")</th>
                                <th class="text-center p-1 w-10">@lang("report_corp_cate_app_admin.col4")</th>
                                <th class="text-center p-1 w-15">@lang("report_corp_cate_app_admin.col5")</th>
                                <th rowspan="2" class="text-center p-1 align-middle w-33">@lang("report_corp_cate_app_admin.col6")</th>
                                <th rowspan="2" class="text-center p-1 align-middle w-5">@lang("report_corp_cate_app_admin.col7")</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-center p-1">@lang("report_corp_cate_app_admin.col8")</th>
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
                                        if(!empty($result->order_fee)) {
                                            if(isset($result->order_fee_unit)) {
                                                if ($result->order_fee_unit == 1) {
                                                    $result->order_fee .= trans("report_corp_cate_app_admin.percent");
                                                } elseif ($result->order_fee_unit == 0) {
                                                    $result->order_fee .= trans("report_corp_cate_app_admin.money");
                                                };
                                            }
                                            echo $result->order_fee;
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center p-1">{{!empty($result->introduce_fee) ? $result->introduce_fee . "円" : ""}}</td>
                                    <td rowspan="2" class="text-center p-1 align-middle">{{!empty($result->application_reason) ? $result->application_reason : ""}}</td>
                                    <td rowspan="2" class="text-center p-1 align-middle">
                                        <input type="checkbox" name="check[]" value="{{$result->id}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-left p-1 align-middle">
                                        <?php
                                        $text = '';
                                        if($result->corp_commission_type) {
                                            $text = trans("report_corp_cate_app_admin.corp_commission_type.text");
                                            if(!empty($result->corp_commission_type)) {
                                                if($result->corp_commission_type == 1) {
                                                    $text .= trans("report_corp_cate_app_admin.corp_commission_type.type1");
                                                } elseif($result->corp_commission_type == 2) {
                                                    $text .= trans("report_corp_cate_app_admin.corp_commission_type.type2");
                                                }
                                            } else {
                                                $text .= trans("report_corp_cate_app_admin.corp_commission_type.type2");
                                            }
                                        }
                                        if($result->note) {
                                            $text .= trans("report_corp_cate_app_admin.note.text");
                                            if(!empty($result->note)) {
                                                $text .= $result->note . ', ';
                                            } else {
                                                $text .= trans("report_corp_cate_app_admin.note.empty");
                                            }
                                        }

                                        if(!empty($text)) {
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
                    @if($results[0]->application_user_id == $user->user_id)
                        <div class="text-danger text-center font-weight-bold">@lang("report_corp_cate_app_admin.not_allow")</div>
                    @else
                        <div class="text-center mt-5">
                            <button class="btn btn--gradient-green btn-w200" type="submit" name="submit" value="approval">@lang("report_corp_cate_app_admin.approval")</button>
                            <button class="btn btn--gradient-orange btn-w200" type="submit" name="submit" value="rejection">@lang("report_corp_cate_app_admin.rejection")</button>
                        </div>
                    @endif
                {{ Form::close() }}
            @else
                <p>@lang("report_corp_cate_app_admin.no_record")</p>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        const BTN_CHECK_ALL_TEXTS = ['{{trans("report_corp_cate_app_admin.check_all.check")}}', '{{trans("report_corp_cate_app_admin.check_all.uncheck")}}'];
    </script>
    <script type="text/javascript" src="{{ mix('js/pages/report.corp.category.application.admin.js') }}"></script>
@endsection
