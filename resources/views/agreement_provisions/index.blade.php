@extends('layouts.app')

@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}"
          rel="stylesheet">
@endsection

@section('content')
    <div class="row agreement-customize agreement-provision">
        <div class="col-12">
            @if(isset($agreementProvisions))
                <div class="text-center header-bar py-3 mb-3">
                    @lang('agreement_admin.agreement_provisions_management')
                </div>

                <div class="text-center mb-4">
                    <button id="open-popup-create-agreement-provision-button" class="fa fa-plus btn btn-secondary btn-lg">
                        @lang('agreement_admin.btn_add_provision')
                    </button>
                    <button id="open-popup-create-agreement-provision-item-button" class="fa fa-plus btn btn-secondary btn-lg">
                        @lang('agreement_admin.btn_add_item')
                    </button>
                    <button id="version-up-button" class="fa fa-refresh btn btn-primary btn-lg">
                        @lang('agreement_admin.edit')
                    </button>
                    <a class="fa fa-search btn btn-secondary btn-lg text--no-decoration" href="{{ route('contract.terms.revision.history') }}">
                        @lang('agreement_admin.edit_history')
                    </a>
                    <hr>
                </div>
                <div class="alert alert-success d-none" id="success-alert" role="alert">
                </div>
                <div id="agreementProvisionDataId">
                    @include('agreement_provisions.agreement_provision_data')
                </div>
            @endif
        </div>
        @include('agreement_provisions.create_agreement_provision')
        @include('agreement_provisions.create_agreement_provision_item')
        @include('agreement_provisions.update_agreement_provision')
        @include('agreement_provisions.update_agreement_provision_item')
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script>
        var urlCreateAgreementProvision = '{{route('post.agreement.provision')}}';
        var urlAgreementProvision = '{{route('agreement.provisions')}}';
        var urlCreateAgreementProvisionItem = '{{route('post.agreement.provision.item')}}';
        var urlGetAgreementProvisionData = '{{route('agreement.provisions.data')}}';
        var urlVersionUp = '{{route('agreement.provisions.versionUp')}}';
        var urlProvisionData = '{{route('agreement.provisions.provision-data')}}';

        const YES = '{{trans('agreement_admin.btn_yes')}}';
        const NO = '{{trans('agreement_admin.btn_no')}}';
        const WOULD_YOU_PLEASE_REVISE_THE_CONTRACT = '{{trans('agreement_admin.would_you_please_revise_the_contract')}}';
        const CONTENT_CONFIRM_DELETE = '{{trans('agreement_admin.content_confirm_delete')}}';

    </script>
    <script src="{{ mix('js/pages/agreement.admin.agreement_provision.js') }}"></script>
@endsection
