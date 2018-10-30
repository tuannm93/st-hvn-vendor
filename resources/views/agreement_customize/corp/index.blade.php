@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <div id="agreement-customize-corp" class="row agreement-customize"
         data-corp-id="{{$corpId}}"
         data-provisions-url="{{route('agreement.customize.with.corp.provisions', ['corpId' => $corpId])}}"
         data-update-data-url="{{route('agreement.customize.with.corp.data', ['corpId' => $corpId])}}">
        <div class="col-12">
            <div class="text-center header-bar py-3 mb-3">
                @lang('agreement_customize.header')
            </div>
            <div class="text-center">
                <h3>{{$officialCorpName}}</h3>
            </div>
            <div class="text-center mb-4">
                <button id="show-create-customize-provison-popup" class="fa fa-plus btn btn-secondary btn-lg">
                    @lang('agreement_admin.btn_add_provision')
                </button>
                <button id="show-create-customize-provison-item-popup" class="fa fa-plus btn btn-secondary btn-lg">
                    @lang('agreement_admin.btn_add_item')
                </button>
            </div>

            <div class="alert alert-success d-none" id="success-alert" role="alert">
            </div>

            <hr>

            <div id="agreement-customize-data-div">
                @include('agreement_customize.corp.data')
            </div>
        </div>
    </div>

    @include('agreement_provisions.create_agreement_provision')
    @include('agreement_provisions.create_agreement_provision_item')
    @include('agreement_provisions.update_agreement_provision')
    @include('agreement_provisions.update_agreement_provision_item')
@endsection

@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/agreement.admin.customize.corp.js') }}"></script>
    <script>
        const UPDATE_PROVISION_URL = '{{route('agreement.customize.with.corp.update-provision')}}';
        const UPDATE_ITEM_URL = '{{route('agreement.customize.with.corp.update-item')}}';
        const DELETE_PROVISION_URL = '{{route('agreement.customize.with.corp.delete-provision')}}';
        const DELETE_ITEM_URL = '{{route('agreement.customize.with.corp.delete-item')}}';

        const ADD = '{{\App\Models\AgreementCustomize::ADD}}';
        const UPDATE = '{{\App\Models\AgreementCustomize::UPDATE}}';

        const CONFIRM_DELETE_CONTENT = '{{trans('agreement_admin.content_confirm_delete')}}';
        const ARE_YOU_SURE_YOU_WANT_TO_REGISTER = '{{trans('agreement_admin.are_you_sure_you_want_to_register')}}';

        const YES = '{{trans('agreement_admin.btn_yes')}}';
        const NO = '{{trans('agreement_admin.btn_no')}}';

    </script>
@endsection
