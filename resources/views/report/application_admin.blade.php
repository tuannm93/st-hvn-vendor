@extends('layouts.app')

@section('content')
    <div class="report-application-admin pt-2">
        <h5 class="font-weight-bold pb-1">{{trans('report_application_admin.title')}}</h5>
        @if (Session::has('success'))
            <div class="alert alert-success mb-0">{{ Session::get('success') }}</div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger mb-0">{{ Session::get('error') }}</div>
        @endif
        <div>
            <p class="pt-3 mb-0">@lang('report_application_admin.total_record', ["count" => $results->total()])</p>
            <div class="table-responsive">
                <table class="table table-bordered table-app-admin">
                    <thead>
                    <tr>
                        <th class="w-7">{{trans('report_application_admin.header_col1')}}</th>
                        <th class="w-7">{{trans('report_application_admin.header_col2')}}</th>
                        <th class="w-20">{{trans('report_application_admin.header_col3')}}</th>
                        <th class="w-10">{{trans('report_application_admin.header_col4')}}</th>
                        <th class="w-10">{{trans('report_application_admin.header_col5')}}</th>
                        <th rowspan="2" class="w-46">{{trans('report_application_admin.header_col6')}}</th>
                    </tr>
                    <tr>
                        <th colspan="5">{{trans('report_application_admin.header_col7')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($results as $key => $result)
                        <tr>
                            <td class="text-center">
                                @if($result->application_section == 'CommissionApplication')
                                    <a class="text--orange"
                                       href="{{route("commission.detail", $result->commission_id)}}"
                                       target="_blank">{{$result->id}}</a>
                                @endif
                            </td>
                            <td>
                                @if($result->application_section == 'CommissionApplication')
                                    {{trans('report_application_admin.body_col2')}}
                                @endif
                            </td>
                            <td>
                                <a class="text--orange" href="{{route("affiliation.detail.edit", $result->corp_id)}}"
                                   target="_blank">{{$result->official_corp_name}}</a>
                            </td>
                            <td>{{$result->application_user_id}}</td>
                            <td class="text-center">
                                <p class="mb-0">{{date("Y-m-d", strtotime($result->application_datetime))}}</p>
                                <p class="mb-0">{{date("H:i:s", strtotime($result->application_datetime))}}</p>
                            </td>
                            <td rowspan="2" class="text-center">
                                <label class="mb-1">{{$result->application_reason}}</label>
                                <div class="form-inline justify-content-between pl-3 pr-3 pb-1">
                                    <button href="javascript:void(0);" data-content="post_approval_{{$key}}"
                                            class="btn btn--gradient-green reload w-46">{{trans("report_application_admin.approval")}}</button>
                                    <button href="javascript:void(0);" data-content="post_rejected_{{$key}}"
                                            class="btn btn--gradient-orange reload w-46">{{trans("report_application_admin.rejected")}}</button>
                                </div>
                                <form action="{{route("commission.approval")}}" name="post_approval_{{$key}}"
                                      id="post_approval_{{$key}}" style="display:none;" method="post" target="_blank">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="approval_id" value="{{$result->id}}">
                                    <input type="hidden" name="action_name" value="approval">
                                </form>
                                <form action="{{route("commission.approval")}}" name="post_rejected_{{$key}}"
                                      id="post_rejected_{{$key}}" style="display:none;" method="post" target="_blank">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="approval_id" value="{{$result->id}}">
                                    <input type="hidden" name="action_name" value="rejected">
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <?php
                                $text = '';
                                if ($result->chg_deduction_tax_include) {
                                    $text .= trans("report_application_admin.deductible_amount");
                                    if (!empty($result->deduction_tax_include)) {
                                        $text .= $result->deduction_tax_include.trans("report_application_admin.money");
                                    } else {
                                        $text .= trans("report_application_admin.empty_text");
                                    }
                                }
                                if ($result->chg_irregular_fee_rate) {
                                    $text .= trans("report_application_admin.irregular_fee_rate");
                                    if (!empty($result->irregular_fee_rate)) {
                                        $text .= $result->irregular_fee_rate.'%, ';
                                    } else {
                                        $text .= trans("report_application_admin.empty_text");
                                    }
                                }
                                if ($result->chg_irregular_fee) {
                                    $text .= trans("report_application_admin.irregular_fee");
                                    if (!empty($result->irregular_fee)) {
                                        $text .= $result->irregular_fee.trans("report_application_admin.money");
                                    } else {
                                        $text .= trans("report_application_admin.empty_text");
                                    }
                                }
                                if ($result->chg_irregular_fee_rate || $result->chg_irregular_fee) {
                                    $text .= trans("report_application_admin.irregular_reason");

                                    if (isset($result->irregular_reason)) {
                                        if ($result->irregular_reason == 0) {
                                            $text .= trans("report_application_admin.empty_text");
                                        } else { $text .= $ir[$result->irregular_reason];
                                        }
                                    } else {
                                        $text .= trans("report_application_admin.empty_text");
                                    }

                                    $text .= ', ';
                                }
                                if ($result->chg_introduction_free) {
                                    if ($result->introduction_free == 0) {
                                        $text .= trans("report_application_admin.introduction_free_zero");
                                    } elseif ($result->introduction_free == 1) {
                                        $text .= trans("report_application_admin.introduction_free_one");
                                    }
                                }
                                if ($result->chg_ac_commission_exclusion_flg) {
                                    if ($result->ac_commission_exclusion_flg == 0) {
                                        $text .= trans("report_application_admin.commission_exclusion_zero");
                                    } elseif ($result->ac_commission_exclusion_flg == 1) {
                                        $text .= trans("report_application_admin.commission_exclusion_one");
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
            <div class="dataTables_paginate">
                @if($results->currentPage() != 1)
                    <a class="paginate_button previous active" rel="prev" href="{{ $results->previousPageUrl() }}"
                       aria-controls="tbBillSearch"
                       id="tbBillSearch_previous">{{ trans('report_application_admin.prev') }}</a>
                @else
                    <span class="paginate_button disabled" rel="prev" aria-controls="tbBillSearch"
                          id="tbBillSearch_previous">{{ trans('report_application_admin.prev') }}</span>
                @endif
                <span class="pl-3 pr-3"></span>
                @if($results->currentPage() != $results->lastPage())
                    <a class="paginate_button next active" href="{{ $results->nextPageUrl() }}" rel="next"
                       aria-controls="tbBillSearch"
                       id="tbBillSearch_next">{{ trans('report_application_admin.next') }}</a>
                @else
                    <span class="paginate_button disabled" rel="next" aria-controls="tbBillSearch"
                          id="tbBillSearch_next">{{ trans('report_application_admin.next') }}</span>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/pages/application.admin.js') }}"></script>
@endsection
