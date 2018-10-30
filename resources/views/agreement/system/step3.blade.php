@extends('layouts.app')

@section('content')
    <section class="agreement-system step-3">
        @include('agreement.system.progress')
        <form id="agreementSystemStep3" method="post" action="{{route('agreementSystem.postStep3')}}">
            {{ csrf_field() }}
            <h3>{{__('agreement_system.setting_genre/area')}}</h3>
            <div id="main">
                <div class="d-block mt-sm-5" id="area">
                    <div class="row mb-sm-5">
                        <div class="col-12">
                            <label
                                class="form-category__label mb-1 d-none d-sm-block">{{__('agreement_system.genre/area_setting_corresponding')}}</label>
                        </div>
                        <div class="col-8 col-sm-12 col-md-4 col-lg-3 pr-md-0">
                            <button id="linkPopupCategory" type="button"
                                    class="btn btn-lg btn--gradient-orange rounded-0 text-white w-100">
                                <strong>{{__('agreement_system.supported_genres_registered')}}</strong>
                            </button>
                        </div>

                        <div class="col-12 col-sm-12 col-md-8 col-lg-9 pl-md-0">
                            <div class="border border-thick border-note bg-note mt-1 mt-sm-0 ml-md-2 box-mess">
                                <p class="m-0 text--orange-light">
                                    {{__('agreement_system.supported_genres_registered_info')}}
                                </p>
                            </div>
                        </div>

                        <div class="col-12 p-2"></div>

                        <div class="col-8 col-sm-12 col-md-4 col-lg-3 pr-md-0">
                            <button id="linkPopupArea" type="button"
                                    class="btn btn-lg btn--gradient-orange rounded-0 text-white w-100">
                                <strong>{{__('agreement_system.register_available_areas')}}</strong>
                            </button>
                        </div>

                        <div class="col-12 col-sm-12 col-md-8 cols-lg-9 pl-md-0">
                            <div class="border border-thick border-note bg-note mt-1 mt-sm-0 ml-md-2 box-mess">
                                <p class="m-0 text--orange-light">
                                    {{__('agreement_system.register_available_areas_info')}}
                                </p>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <p class="p-3 bg-light">
                                <strong>{{__('agreement_system.specialty')}}</strong><br> {{__('agreement_system.specialty_info')}}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-block mt-sm-5" id="setting">
                    <div class="row">
                        <div class="col-12">
                            <label
                                class="form-category__label mb-1 d-none d-sm-block">{{__('agreement_system.setting_contents_list')}}</label>
                            <label class="sub-label mt-3 border_left_orange">
                                <strong>{{__('agreement_system.basic_compatible_area_list')}}</strong>
                            </label>
                        </div>
                        <div class="col-12">
                            <div class="select_area_contents mt-md-3">
                                <div class="area_line_group d-block d-md-table">
                                    @foreach($prefList as $pref)
                                        <div class="pref_block d-block d-md-table-cell">
                                            <div class="pref_sub d-table">
                                                <div
                                                    class="pref_sub1 @if($pref->status == 1) pref_sub1__none @elseif($pref->status == 2) pref_sub1__part @endif d-table-cell">
                                                    @if($pref->status == 1)
                                                        {!! trans('agreement_system.correspondence_impossible') !!}
                                                    @elseif($pref->status == 2)
                                                        {!! trans('agreement_system.partial_area_support_possible')!!}
                                                    @else
                                                        {!! trans('agreement_system.all_regions_available')!!}
                                                    @endif
                                                </div>
                                                <div class="pref_sub2 d-table-cell">
                                                    {{ $pref->address1 }}
                                                </div>
                                                <div class="pref_sub3 d-table-cell">
                                                    <a class="viewSelectArea" style="cursor:pointer;" target="_blank"
                                                       data-city="{{$pref->address1}}" href="javascript:void(0);"
                                                       data-address="{{$pref->address1_cd}}">
                                                        {{__('agreement_system.area')}}<span
                                                            class="d-none d-sm-inline">≫</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @if($loop->iteration % 3 == 0)
                                </div>
                                <div class="area_line_group d-block d-md-table">
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label class="sub-label mt-3 border_left_orange">
                            <strong>{{__('agreement_system.applicable_genre_list')}}</strong>
                        </label>
                        <p>
                            {{__('agreement_system.applicable_genre_list_info')}}
                        </p>
                    </div>

                    {{-- Category A --}}
                    <div class="col-12">
                        <label class="sub-label border_left_orange">
                            <strong>{{__('agreement_system.conclusion_base_business_category_A')}}</strong>
                        </label>
                    </div>
                    <div class="col-12">
                        <label class="sub-label border_left_orange">
                            {{__('agreement_system.genre_reflects_basic_correspondence_area')}}
                        </label>
                        @component('agreement.system.component.genre_table', [
                            'corpCategoryList' => $corpCategoryList,
                            'category' => 'A'
                        ])
                        @endcomponent
                    </div>

                    {{-- Category B --}}
                    <div class="col-12">
                        <label class="sub-label border_left_orange">
                            <strong>{{__('agreement_system.conclusion_base_business_category_B')}}</strong>
                        </label>
                    </div>
                    <div class="col-12">
                        <label class="sub-label border_left_orange">
                            {{__('agreement_system.genre_reflects_basic_correspondence_area')}}
                        </label>
                        @component('agreement.system.component.genre_table', [
                            'corpCategoryList' => $corpCategoryList,
                            'category' => 'B'
                        ])
                        @endcomponent
                    </div>
                </div>
                <p align="center" height="0"
                   style="margin: 5px 0 20px 0; padding-top: 10px; border-top: solid 1px #999999;">
                    <input id="commt_flg" name="commt_flg" type="hidden" value="">
                </p>

                <div class="agreement-system text-center regist">
                    <button class="btn btn--gradient-default btn-lg" type="button" id="back_button">
                        {{__('agreement_system.btn.return')}}
                    </button>
                    <button class="btn btn--gradient-green btn-lg" type="submit">
                        {{__('agreement_system.btn.go_to_confirmation_screen')}}
                    </button>
                </div>
            </div>
        </form>
        @include('agreement.system.category_dialog')
        @include('agreement.system.area_dialog')
    </section>
@endsection
@section('script')
    <script>
        var urlGetCategoryDialog = '{{route('get.category_dialog')}}';
        var urlBackStep3 = '{{route('agreementSystem.getStep2')}}';
        var urlGetAreaDialog = '{{route('get.area_dialog')}}';
        var urlPostAreaDialog = '{{route('post.area_dialog')}}';
        var urlViewAreaDialog = '{{route('view_area_dialog')}}';
    </script>
    <script src="{{mix('js/pages/step3_agreement_system.js')}}"></script>
    <script>
        jQuery(document).ready(function () {
            Step3AgreementSystem.init();
        });
    </script>
@endsection
