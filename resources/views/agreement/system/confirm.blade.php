@extends('layouts.app')

@section('content')
    <section class="agreement-system">
        @include('agreement.system.progress')
        <h2>@lang('agreement_system.contract_contents')</h2>
        <hr>
        <form id="confirmFormId" method="post" action="{{route('agreementSystem.postConfirm')}}">
            {{ csrf_field() }}
            @include('agreement.system.confirm_agreement')
            <br/><br/><br/><br/>
            @include('agreement.system.confirm_base_information')
            <br>
            @include('agreement.system.confirm_area')
            <br>
            @include('agreement.system.confirm_category')
            <br><br><br>
            @include('agreement.system.confirm_revise')
            <div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="acceptedCheck">
                    <label class="custom-control-label" for="acceptedCheck">@lang('agreement_system.i_agree_above_content')</label>
                </div>
            </div>
            <div align="center">
                <hr>
                <button id="back_button" class="btn btn--gradient-default btn-lg"
                        type="button">@lang('agreement_system.btn.return')</button>
                <button id="btnApplicationId" class="btn btn--gradient-default btn-lg"
                        type="button">@lang('agreement_system.btn_application')</button>
            </div>
        </form>
        @include('agreement.system.area_dialog')
    </section>
@endsection
@section('script')
    <script>
        var urlBackConfirm = '{{route('agreementSystem.back.getConfirm')}}';
        var alertConfirmAgreement = '@lang('agreement_system.alert_confirm_agreement')';
        var urlViewAreaDialog = '{{route('view_area_dialog')}}';
    </script>
    <script src="{{ mix('js/pages/step_confirm_agreement_system.js')}}"></script>
    <script src="{{ mix('js/pages/step3_agreement_system.js')}}"></script>
    <script>
        jQuery(document).ready(function () {
            StepConfirmAgreementSystem.init();
            Step3AgreementSystem.onViewConfigurationArea();
        });
    </script>
@endsection
