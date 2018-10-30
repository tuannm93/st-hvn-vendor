@extends('layouts.app')
@php
    $bHasError = Session::has(__('affiliation_genre_resign.KEY_SESSION_ERROR_GENRE_RESIGN'));
    $bHasLastUpdate = isset($data['modifiedDate']) &&
        $data['modifiedDate'] != __('affiliation_genre_resign.time_default') ? true : false;
@endphp
@section('content')
    <div class="genre_resigning">
        <div id="top">
            <div id="contents">
                <div id="main">
                    <input type="hidden" value="{{csrf_token()}}" id="csrf-token" name="token"/>
                    <input type="hidden" value="{{$data['inforCorp']['id']}}" id="corp_id"/>
                    <input type="hidden"
                           value="{{isset($data['inforCorp']['corp_commission_type']) ? $data['inforCorp']['corp_commission_type'] : "0"}}"
                           id="corp_commission_type">
                    <div class="corp_title">
                    <span>
                        {{__('affiliation_genre_resign.business_name')}}：{{$data['inforCorp']['official_corp_name']}}
                    </span>
                        <span>
                        {{__('affiliation_genre_resign.business_id')}}：{{$data['inforCorp']['id']}}
                    </span>
                    </div>
                    @if($bHasError)
                        <div class="row justify-content-center box__mess box--error">
                            {{__('affiliation_genre_resign.content_error_genre_resign')}}
                        </div>
                    @endif
                    <h3 class="border_double_gray">{{__('affiliation_genre_resign.title_notice_genre_info')}}</h3>
                    <div id="category_info">
                        <div class="row mt-4">
                            <div class="col-md-7">
                                <p class="comment_space">
                                    <span><strong>{{__('affiliation_genre_resign.notice_1_dots')}}
                                            &nbsp;{{__('affiliation_genre_resign.notice_1_title')}}</strong></span>
                                    &nbsp;{{__('affiliation_genre_resign.notice_1_content_dots')}}
                                    &nbsp; {{__('affiliation_genre_resign.notice_1_content')}}<br>
                                    <span><strong>{{__('affiliation_genre_resign.notice_1_dots')}}
                                            &nbsp;{{__('affiliation_genre_resign.notice_2_title')}}</strong></span>
                                    &nbsp;{{__('affiliation_genre_resign.notice_1_content_dots')}}
                                    &nbsp;{{__('affiliation_genre_resign.notice_2_content')}}<br>
                                </p>
                            </div>
                            <div class="col-md-1"></div>
                            @if(count($data['mediationGenre']) > 0)
                                <div class="col-md-4">

                                    <p class="comment_space_sm">
                                        <strong>{{__('affiliation_genre_resign.notice_3_title')}}</strong>
                                        {!!trans('affiliation_genre_resign.notice_3_content')!!}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="orange_backcolor_info">
                                {{__('affiliation_genre_resign.notice_4')}}<br>
                                @foreach($data['mediationGenre'] as $obj)
                                    <span>・<a href="#genre_key_{{$obj['id']}}">{{$obj['genre_name']}}</a></span>
                                @endforeach
                            </p>
                        </div>
                        @component('affiliation.components.resign.genre_resign_btn', [ 'data' => $data])
                        @endcomponent
                        <form id="listCateGenre">
                            <div id="block_contracted_base_genre">
                                <input type="hidden" name="checked_categories_temp" id="categoriesSelected"
                                       value="{{$data['checkedCategoriesTempId']}}">
                                <h3 class="border_double_gray">{{__('affiliation_genre_resign.title_commission_type_1')}}</h3>
                                @component('affiliation.components.resign.genre_resign_table_data', [
                                    'data' => $data['listCommissionType1'],
                                    'bShowLastColumn' => true])
                                @endcomponent
                            </div>
                            <div id="block_introduction_base_genre">
                                <h3 class="border_double_gray">{{__('affiliation_genre_resign.title_commission_type_2')}}</h3>
                                @component('affiliation.components.resign.genre_resign_table_data', [
                                    'data' => $data['listCommissionType2'],
                                    'bShowLastColumn' => false])
                                @endcomponent
                            </div>
                            <div id="block_genre_not_in_corp">
                                <h3 class="border_double_gray">{{__('affiliation_genre_resign.title_commission_type_3')}}</h3>
                                @component('affiliation.components.resign.genre_resign_table_data', [
                                    'data' => $data['listCommissionType3'],
                                    'bShowLastColumn' => true])
                                @endcomponent
                            </div>
                        </form>
                        @component('affiliation.components.resign.genre_resign_btn', ['data' => $data])
                        @endcomponent
                        @if($bHasLastUpdate)
                            <div class="mt-2">
                                {{__('affiliation_genre_resign.time_last_update')}}：{{$data['modifiedDate']}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="pageData"
                 data-btn-ok="{{__('affiliation_genre_resign.btn_yes')}}"
                 data-btn-no="{{__('affiliation_genre_resign.btn_no')}}"
                 data-title="{{__('affiliation_genre_resign.content_dialog_reconfirm')}}"
                 data-url-back="{{route('affiliation.resign.index', $data['inforCorp']['id'])}}"
                 data-url-resign="{{route('affiliation.genre.resign.update')}}"
                 data-url-reconfirm="{{route('affiliation.genre.resign.reconfirm')}}">
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{mix('js/utilities/st.common.js')}}"></script>
    <script src="{{mix('js/pages/affiliation.genre.resign.js')}}"></script>
    <script>
        $(document).ready(function () {
            AffiliationGenreResign.init();
        });
    </script>
@endsection
