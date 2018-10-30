@extends('layouts.app')
@section('style')
@endsection
@section('content')
<div class="container" id="admin-index">
    <div class="row">
        <div class="list-group col-md-3">
            <div class="group">
                <h5 class="list-group-item list-group-item-info">{{ trans('report_index.report_menu') }}</h5>
                <a class="list-group-item list-group-item-warning" id="report-corp-commisson-link" onclick="deleteReportCommisisonSession();" href="javascript:void(0)" data-href="{{url('/report/corp_commission')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.vendor_agency_list') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/corp_selection')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.vendor_selection_list') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{ route('report.unsent_list') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.MailorFAX_unsent_list') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/jbr_commission')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.JBR-like_case_pre-list') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/jbr_ongoing')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.JBR-like_case_ongoing_chasing_list') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/jbr_receipt_follow')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.post_JBR_receipt_report') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/addition')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.additional_construction_list') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/development')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.targeted_report') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{ route('report.sales.support') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.supported_project_report') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{ route('general_search.index') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.overall_search') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/corp_agreement_category')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.merchant_contract_category_history') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/antisocial_follow')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.report_after_checking') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{route(__('report.reputation.follow'))}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.follow_up_report') }}
                </a>
                <a class="list-group-item list-group-item-warning" href="{{url('/report/real_time_report')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.real_time_report') }}
                </a>
            </div>
            <div class="group">
                <h5 class="list-group-item list-group-item-info">{{trans('report_index.application_menu') }}</h5>
                @if((Auth::user()['auth'] == 'system' || Auth::user()['auth'] == 'admin'))
                <a class="list-group-item list-group-item-warning" href="{{url('/report/application_admin')}}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.procurement_application_administrator') }}
                </a>
                @endif
                <a class="list-group-item list-group-item-warning" href="{{ route('report.application_answer') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.application_for_appointment_answer') }}
                </a>
                @if((Auth::user()['auth'] == 'system' || Auth::user()['auth'] == 'admin'))
                <a class="list-group-item list-group-item-warning" href="{{ route('report.get.corp.category.group.application.admin') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.merchant_category_application_administrator') }}
                </a>
                @endif
                <a class="list-group-item list-group-item-warning" href="{{ route('report.get.corp.category.group.application.answer') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>{{trans('report_index.merchant_category_application_answer') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function deleteReportCommisisonSession() {
            var href = $('#report-corp-commisson-link').data('href');
            var urlSession = '{{ route('delete.session.corp.commission') }}';
            $.ajax({
                type: "POST",
                data: true,
                url: urlSession,
                cache: false
            }).done(function (data) {
                window.location.href = href;
            });
        }
    </script>
@endsection
