@extends('layouts.app')
@section('style')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="top">
                    <div id="contents">
                        <div class="auction_setting_prefecture_detail all_area">
                            @if(Session::has('Update'))
                                <p class="box__mess box--success">{{ Session::get('Update') }}</p>
                            @endif
                                @if ($errors->any())
                                    <div class="box__mess box--error">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            {{--add tab element width link--}}
                            @component('auction_setting.components.tabs', ['path' => Route::current()->getName()])
                            @endcomponent
                            <div>
                                {{Form::open(['enctype' => 'multipart/form-data', 'id'=> 'prefecture-detail', 'type'=>'post', 'route'=>['auction_setting.prefecture.detail', $genreId, $prefCd] , 'accept-charset'=>"UTF-8", 'novalidate'=>'true'])}}
                                {{Form::input('hidden', 'id', isset($results->id) ? $results->id : '', ['id' => 'AuctionGenreId', 'class' => 'form-control'])}}
                                {{Form::input('hidden', 'genre_id', $genreId, ['id' => 'genre_id', 'class' => 'form-control'])}}
                                {{Form::input('hidden', 'prefecture_cd', $prefCd, ['id' => 'prefecture_cd', 'class' => 'form-control'])}}


                                <p class="mt-3 fs-13">{{ trans('prefecture_detail.genre') }} :{{ $genreName }} </p>
                                <p class="fs-13">{{ trans('prefecture_detail.pref_cd') }}：{{ $prefectureCd }}</p>
                                <div class="d-flex flex-column flex-md-row align-items-md-baseline fs-13">
                                    <div>{{ trans('prefecture_detail.exclusion_time') }}：</div>
                                    <div>
                                        {{ Form::select('exclusion_pattern', $exclusionTimeList, isset($results->exclusion_pattern)? $results->exclusion_pattern : null , ['disabled'=>$acaAccount, 'class' => 'field form-control w-100', 'empty'=>trans('common.none'), 'id'=>'exclusion_pattern', 'required'=>'required']) }}　
                                    </div>
                                    <div>
                                        <span id = 'exclusion_pattern_dis' data-url = '{{ route('ajax.exclusion.pattern') }}'></span>
                                    </div>
                                </div>
                                <h3 class="title-head">{{ trans('prefecture_detail.bidding_deadline_setting') }}</h3>
                                <div id="kihon_info" class="ml-md-5">
                                    <div class="content flex-column flex-md-row">
                                        <div>
                                            <p class="content-title">≪{{ trans('prefecture_detail.regular_case') }}≫&nbsp;&nbsp;{{ trans('prefecture_detail.arithmetic_priority_order_available') }}</p>
                                            <div class="d-flex align-items-center">
                                                <div> ① [Y] ‐ [X] ≦ </div>
                                                <div class="ml-1 mr-1">
                                                    {{Form::input('text', 'limit_asap', isset($results->limit_asap) ? $results->limit_asap : '', ['disabled'=>$acaAccount, 'id' => 'limit_asap', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '10', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-error-container' => '#limit_asap_feedback'])}}
                                                </div>
                                                <div>
                                                    {{ trans('prefecture_detail.minutes_great_urgent') }}
                                                </div>
                                            </div>
                                            <div id="limit_asap_feedback"></div>
                                            <p>&nbsp;&nbsp;↓&nbsp;&nbsp;</p>
                                            <div class="d-flex align-items-center">
                                                <div>② [Y] ‐ [X] ≦ </div>
                                                <div class="ml-1 mr-1">
                                                    {{Form::input('text', 'limit_immediately', isset($results->limit_immediately) ? $results->limit_immediately : '', ['disabled'=>$acaAccount, 'id' => 'limit_immediately', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '10', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-error-container' => '#limit_immediately_feedback'])}}
                                                </div>
                                                <div>
                                                    {{ trans('prefecture_detail.minutes_urgent') }}
                                                </div>
                                            </div>
                                            <div id="limit_immediately_feedback"></div>
                                            <p>&nbsp;&nbsp;↓&nbsp;&nbsp;</p>
                                            <p>③ {{ trans('prefecture_detail.to_ordinary_case') }}</p>
                                        </div>
                                        <div class="detail mt-2 mt-md-0">
                                            <small class="small-color fs-12">
                                                ・{{ trans('prefecture_detail.when_creating_a_case') }}・・・・[X]<br>
                                                ・{{ trans('prefecture_detail.date_and_time_of_visit') }}・・・・[Y]<br>
                                                ・{{ trans('prefecture_detail.bid_deadline_date_and_time') }}・・・・[Z]<br>
                                                ・{{ trans('prefecture_detail.time_to_bid') }}・・・・［W］<br>
                                                &nbsp;&nbsp;{{ trans('prefecture_detail.is_the_time_excluding') }}<br>
                                                ・{{ trans('prefecture_detail.exclusion_time_set_pattern') }}<br>
                                                &nbsp;&nbsp;{{ trans('prefecture_detail.analog_selection') }}
                                            </small>
                                        </div>
                                    </div>

                                    <p class="content-title">≪{{ trans('prefecture_detail.urgent_case') }}≫</p>

                                    <div class="d-flex align-items-center">
                                        <div>[X] ＋</div>
                                        <div class="ml-1 mr-1">{{Form::input('text', 'asap', isset($results->asap) ? $results->asap : '', ['disabled'=>$acaAccount, 'id' => 'asap', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '10', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-error-container' => '#asap_feedback'])}}</div>
                                        <div>
                                            {{ trans('prefecture_detail.minute_z') }}
                                        </div>
                                    </div>
                                    <div id="asap_feedback"></div>
                                    </p>
                                    <p>{{ trans('prefecture_detail.however_when') }}</p>
                                    <p class="content-fotter">{{ trans('prefecture_detail.if_the_visit_date') }}</p><br/>

                                    <p class="content-title">≪{{ trans('prefecture_detail.urgent_case_n') }}≫</p>
                                    <div class="d-flex align-items-center">
                                        <div>[X] ＋</div>
                                        <div class="ml-1 mr-1">{{Form::input('text', 'immediately', isset($results->immediately) ? $results->immediately : '', ['disabled'=>$acaAccount, 'id' => 'immediately', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '10', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-error-container' => '#immediately_feedback'])}}</div>
                                        <div>{{ trans('prefecture_detail.minute_z') }}</div>
                                    </div>
                                    <div id="immediately_feedback"></div>
                                    <p>{{ trans('prefecture_detail.however_when') }}</p>
                                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                                        <div class="d-flex align-items-center">
                                            <div>{{ trans('prefecture_detail.however') }}</div>
                                            <div class="ml-1 mr-1">
                                                {{Form::input('text', 'immediately_small', isset($results->immediately_small) ? $results->immediately_small : '', ['disabled'=>$acaAccount, 'id' => 'immediately_small', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '10', 'data-rule-number' => 'true'])}}
                                            </div>
                                        </div>
                                        <div>{{ trans('prefecture_detail.minute') }} <span class='immediately_small_dis'></span>{{ trans('prefecture_detail.min') }}</div>
                                    </div>

                                    <p class="content-fotter">{{ trans('prefecture_detail.if_the_reception_time') }}</p><br/>
                                    <p class="content-title">≪{{ trans('prefecture_detail.regular_case') }}≫</p>
                                    <div class="d-flex align-items-center">
                                        <div>[X] ＋</div>
                                        <div class="ml-1 mr-1">{{Form::input('text', 'normal3', isset($results->normal3) ? $results->normal3 : '', ['disabled'=>$acaAccount, 'id' => 'normal3', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '10', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-error-container' => '#normal3_feedback'])}}</div>
                                        <div>{{ trans('prefecture_detail.minute_z') }}</div>
                                    </div>
                                    <div id="normal3_feedback"></div>
                                </div>
                                <br>
                                <h3 class="title-head">{{ trans('prefecture_detail.open_rank_setting') }}</h3>
                                <div id="kihon_info" class="ml-md-5">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                                        <div class="fix-nowarp">{{ trans('prefecture_detail.correspondence') }}</div>
                                        <div class="d-flex align-items-center ml-md-2">
                                            <div class="mr-2">a</div>
                                            <div class="w-100">{{Form::input('text', 'open_rank_a', isset($results->open_rank_a) ? $results->open_rank_a : '', ['disabled'=>$acaAccount, 'id' => 'open_rank_a', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#open_rank_setting_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>

                                        <div class="d-none d-md-block ml-1 mr-1">→</div>
                                        <div class="d-block d-md-none mx-auto">↓</div>

                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">b</div>
                                            <div class="w-100">{{Form::input('text', 'open_rank_b', isset($results->open_rank_b) ? $results->open_rank_b : '', ['disabled'=>$acaAccount, 'id' => 'open_rank_b', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#open_rank_setting_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>

                                        <div class="d-none d-md-block ml-1 mr-1">→</div>
                                        <div class="d-block d-md-none mx-auto">↓</div>

                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">c</div>
                                            <div class="w-100">{{Form::input('text', 'open_rank_c', isset($results->open_rank_c) ? $results->open_rank_c : '', ['disabled'=>$acaAccount, 'id' => 'open_rank_c', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#open_rank_setting_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>

                                        <div class="d-none d-md-block ml-1 mr-1">→</div>
                                        <div class="d-block d-md-none mx-auto">↓</div>

                                       <div class="d-flex align-items-center">
                                            <div class="mr-2">d</div>
                                            <div class="w-100">{{Form::input('text', 'open_rank_d', isset($results->open_rank_d) ? $results->open_rank_d : '', ['disabled'=>$acaAccount, 'id' => 'open_rank_d', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#open_rank_setting_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                       </div>
                                        <div class="ml-md-3 mt-2 mt-md-0 mr-2 fix-nowarp">{{ trans('prefecture_detail.first_interaction') }}</div>
                                        <div class="d-flex align-items-center">
                                            <div class="w-100">{{Form::input('text', 'open_rank_z', isset($results->open_rank_z) ? $results->open_rank_z : '', ['disabled'=>$acaAccount, 'id' => 'open_rank_z', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#open_rank_setting_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>
                                    </div>
                                    <div id="open_rank_setting_feedback"></div>
                                    <p>{{ trans('prefecture_detail.also_linked_with') }}</p>
                                    <p class="content-fotter">{{ trans('prefecture_detail.for_example') }}</p>
                                </div>
                                <br>
                                <h3 class="title-head">{{ trans('prefecture_detail.telephone_desired_case') }}</h3>
                                <div id="kihon_info" class="ml-md-5">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">a</div>
                                            <div class="w-100">{{Form::input('text', 'tel_hope_a', isset($results->tel_hope_a) ? $results->tel_hope_a : '', ['disabled'=>$acaAccount, 'id' => 'tel_hope_a', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#telephone_desired_case_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>

                                        <div class="d-none d-md-block ml-1 mr-1">→</div>
                                        <div class="d-block d-md-none mx-auto">↓</div>

                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">b</div>
                                            <div class="w-100">{{Form::input('text', 'tel_hope_b', isset($results->tel_hope_b) ? $results->tel_hope_b : '', ['disabled'=>$acaAccount, 'id' => 'tel_hope_b', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#telephone_desired_case_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>

                                        <div class="d-none d-md-block ml-1 mr-1">→</div>
                                        <div class="d-block d-md-none mx-auto">↓</div>

                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">c</div>
                                            <div class="w-100">{{Form::input('text', 'tel_hope_c', isset($results->tel_hope_c) ? $results->tel_hope_c : '', ['disabled'=>$acaAccount, 'id' => 'tel_hope_c', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#telephone_desired_case_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>

                                        <div class="d-none d-md-block ml-1 mr-1">→</div>
                                        <div class="d-block d-md-none mx-auto">↓</div>

                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">d</div>
                                            <div class="w-100">{{Form::input('text', 'tel_hope_d', isset($results->tel_hope_d) ? $results->tel_hope_d : '', ['disabled'=>$acaAccount, 'id' => 'tel_hope_d', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#telephone_desired_case_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                        </div>
                                        <div class="ml-md-3 mt-2 mt-md-0 mr-2 fix-nowarp">{{ trans('prefecture_detail.first_interaction') }}</div>
                                        <div class="d-flex align-items-center">
                                            <div class="w-100">{{Form::input('text', 'tel_hope_z', isset($results->tel_hope_z) ? $results->tel_hope_z : '', ['disabled'=>$acaAccount, 'id' => 'tel_hope_z', 'class' => 'form-control', 'size'=>'10', 'maxlength' => '3', 'data-rule-required' => 'true', 'data-rule-number' => 'true', 'data-rule-max' => '100', 'data-rule-min' => '0', 'data-error-container' => '#telephone_desired_case_feedback'])}}</div>
                                            <div class="ml-1">%</div>
                                       </div>
                                    </div>
                                    <div id="telephone_desired_case_feedback"></div>
                                    <p class="content-fotter">{{ trans('prefecture_detail.since_the_probability') }}</p>
                                </div>
                            </div>
                            <div class="text-center">
                            <a class="btn btn--gradient-gray fix-button-w-120" href="{{ route('auction_setting.prefecture', ['id' => $genreId]) }}">{{ trans('prefecture_detail.return') }}</a>
                            {{Form::submit(trans('prefecture_detail.save'),['disabled'=>$acaAccount, 'id'=>'regist' ,'class'=>'btn btn--gradient-green fix-button-w-120 function-button'])}}
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/prefecture_detail.js') }}"></script>
    <script>
        FormUtil.validate('#prefecture-detail');
    </script>
@endsection
