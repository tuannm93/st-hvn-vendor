@extends('layouts.app')
@php
    $bHasError = Session::has(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN'));
    $content = Session::get(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN'));
    $bSuccess = Session::has(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN_SUCCESS'));
    $contentSuccessful = Session::get(__('affiliation_resign.KEY_SESSION_MESSAGE_RESIGN_SUCCESS'));
    $bHasLastUpdate = isset($data['lastModified']) && strlen(trim($data['lastModified'])) > 0 ? true : false;
@endphp
@section('content')
<form action="" id="affiliation-resigning">
    <div class="affiliation-resigning">
        <input type="hidden" value="{{csrf_token()}}" id="csrf-token" name="token"/>
        <input type="hidden" value="{{$data['infoCorp']['id']}}" id="corp_id"/>
        <input type="hidden" value="{{$data['tempId']}}" id="temp_id"/>
        <input type="hidden" value="{{isset($data['infoCorp']['corp_commission_type']) ? $data['infoCorp']['corp_commission_type'] : "0"}}"
                id="corp_commission_type">
        <div class="row header-field mt-3">
            <div class="col-sm-6 col-lg-auto">
                <strong>{{__('affiliation_resign.business_name')}}：{{$data['infoCorp']['official_corp_name']}}</strong>
            </div>
            <div class="col-sm-6 col-lg-auto">
                <strong>{{__('affiliation_resign.business_id')}}：{{$data['infoCorp']['id']}}</strong>
            </div>
        </div>
        @if($bHasError)
            <div class="box__mess box--error">
                {{$content}}
            </div>
        @endif
        @if($bSuccess)
            <div class="box__mess box--success">
                {{$contentSuccessful}}
            </div>
        @endif
        <div class="form-category mt-4">
            <label class="form-category__label font-weight-normal">{{__('affiliation_resign.title_block_1')}}</label>
            <div class="row mx-0">
                <div class="col-auto pl-0">
                    <a class="btn btn--gradient-orange remove-effect-btn fs-sm-15 mb-2 mb-sm-0" href="{{route('affiliation.genre.resign.index', [$data['infoCorp']['id']])}}">
                    {{__('affiliation_resign.btn_to_genre_resign')}}</a>
                </div>
                <div class="col-xl-8 d-flex align-items-center caption"><span>{{__('affiliation_resign.notice_btn_genre_resign')}}</span></div>
            </div>
        </div>
        <div class="border-left-orange my-3">
            <strong class="ml-2">{{__('affiliation_resign.title_category_list')}}</strong>
        </div>
        <p class="mb-0">{{__('affiliation_resign.content_category_list_1')}}
            <span class="text-danger">{{__('affiliation_resign.content_category_list_2')}}</span>
        </p>
        <p class="mb-0">{{__('affiliation_resign.content_category_list_3')}}</p>
        <div class="text-sm-right">
            <button id="btnCheckAll" class="btn btn--gradient-orange remove-effect-btn col-sm-3 col-xl-2">
                {{__('affiliation_resign.text_btn_check_all')}}
            </button>
        </div>
        <br>
        <div class="border-left-orange my-3">
            <strong class="ml-2">{{__('affiliation_resign.title_business_category_A')}}</strong>
        </div>
        @component('affiliation.components.resign.resign_block_category', [
            'data' => $data['listCateA'],
            'isConclusionBase' => true,
            'listFeeUnit' => $data['listFeeUnit'],
            'listCorpCommisionType' => $data['listCorpCommisionType'],
            'listType' => 'group_1'
        ])
        @endcomponent
        <div class="border-left-orange my-3">
            <strong class="ml-2">{{__('affiliation_resign.title_business_category_B')}}</strong>
        </div>
        @component('affiliation.components.resign.resign_block_category', [
            'data' => $data['listCateB'],
            'isConclusionBase' => false,
            'listFeeUnit' => $data['listFeeUnit'],
            'listCorpCommisionType' => $data['listCorpCommisionType'],
            'listType' => 'group_2'
        ])
        @endcomponent
        @if($bHasLastUpdate)
            <div class="mt-2 mt-sm-0 pb-4 border-bottom">
                {{__('affiliation_resign.date_modified')}}<span class="text-primary">{{$data['lastModified']}}</span>
            </div>
        @endif
            <div class="text-center mt-2">
                <button class="btn btn--gradient-green remove-effect-btn col-sm-6 col-lg-2 resign fs-sm-15" id="btnUpdateResign">{{__('affiliation_resign.btn_approval')}}</button>
            </div>
            @if($data['bAllowShowReconfirm'])
            <div class="row mx-0 mt-2">
                <div class="col-sm-6 text-center text-sm-right px-0 pr-sm-1 mb-2 mb-sm-0">
                    <button class="btn btn--gradient-orange remove-effect-btn col-lg-3 reconfirm fs-sm-15" id="btnReconfirmResign">{{__('affiliation_resign.btn_reconfirm')}}</button>
                </div>
                <div class="col-sm-6 text-center text-sm-left px-0 pl-sm-1">
                    <button class="btn btn--gradient-orange remove-effect-btn col-lg-3 fax fs-sm-15" id="btnReconfirmFaxResign">{{__('affiliation_resign.btn_reconfirm_fax')}}</button>
                </div>
            </div>
            @endif
    </div>
    <div id="pageData"
            data-btn-ok="{{__('affiliation_resign.btn_yes')}}"
            data-btn-no="{{__('affiliation_resign.btn_no')}}"
            data-title="{{__('affiliation_genre_resign.content_dialog_reconfirm')}}"
            data-text-check="{{__('affiliation_resign.text_btn_check_all')}}"
            data-text-uncheck="{{__('affiliation_resign.text_btn_uncheck_all')}}"
            data-url-resign="{{route('affiliation.resign.update')}}"
            data-url-reconfirm="{{route('affiliation.resign.reconfirm')}}">
    </div>
</form>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{mix('js/utilities/st.common.js')}}"></script>
    <script src="{{mix('js/pages/affiliation.resign.js')}}"></script>
    <script>
        $(document).ready(function () {
            AffiliationResign.init();
            FormUtil.validate('#affiliation-resigning');
        });
    </script>
@endsection