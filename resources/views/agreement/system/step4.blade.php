@extends('layouts.app')

@section('content')
    <section class="agreement-system step-3">
        @include('agreement.system.progress')
        <form id="agreementSystemStep4" method="post" action="{{route('agreementSystem.postStep4')}}">
            {{ csrf_field() }}
            <h2>@lang('agreement_system.confirm_genre')</h2>
            <div id="main">
                <div class="row">
                    <div class="col-12">
                        <label class="sub-label mt-3 border_left_orange">
                            <strong>{{__('agreement_system.applicable_genre_list')}}</strong>
                        </label>
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
                    <button class="btn btn--gradient-green btn-lg text-white" type="submit">
                        {{__('agreement_system.btn_agree')}}
                    </button>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script>
        var urlGetCategoryDialog = '{{route('get.category_dialog')}}';
        var urlBackStep4 = '{{route('agreementSystem.getStep3')}}';
        var urlGetAreaDialog = '{{route('get.area_dialog')}}';
        var urlPostAreaDialog = '{{route('post.area_dialog')}}';
        var urlViewAreaDialog = '{{route('view_area_dialog')}}';
    </script>
    <script>
        jQuery(document).ready(function () {
            $('#back_button').on('click', function () {
                window.location.href = urlBackStep4;
            });
        });
    </script>
@endsection
