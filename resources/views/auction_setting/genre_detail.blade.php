@extends('layouts.app')
@section('content')
    <div class="genre-detail">
        @component('auction_setting.components.tabs')
        @endcomponent
        <div class="content_area pt-2">
            @if(Session::has('Update'))
                <div class="alert alert-success mb-3">{{ Session::get('Update') }}</div>
            @endif
            @if(Session::has('InputError'))
                <div class="alert alert-danger mb-3">{{ Session::get('InputError') }}</div>
            @endif
            {{Form::open(['enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['auction.setting.genre.detail'] , 'accept-charset'=>"UTF-8", 'novalidate'=>'true', 'id' => 'form-genre-detail'])}}
                {{Form::input('hidden', 'data[AuctionGenre][id]', isset($results->id) ? $results->id : '', ['id' => 'AuctionGenreId', 'class' => 'form-control'])}}
                {{Form::input('hidden', 'data[AuctionGenre][genre_id]', $genreId, ['id' => 'genre_id', 'class' => 'form-control'])}}
                <div>{{ trans('genre_detail.genre') }}：{{ $genreName }}</div>
                <div class="form-inline">{{ trans('genre_detail.exclusion_time') }}：
                    {{ Form::select('data[AuctionGenre][exclusion_pattern]', $exclusionTimeList, isset($results->exclusion_pattern) ? $results->exclusion_pattern : '', ['disabled'=>$acaAccount, 'class' => 'field form-control form-control-sm fix-w-100', 'empty'=>trans('common.none'), 'id'=>'exclusion_pattern', 'data-rule-required'=>'true']) }}　
                    <span id = 'exclusion_pattern_dis' data-url = '{{ route('ajax.exclusion.pattern') }}'></span>
                </div>
                <div class="form-category pt-3 pb-3">
                    <label class="form-category__label">{{ trans('genre_detail.bidding_deadline_setting') }} </label>
                    <div class="form-category__body">
                        <div class="pl-4 row pt-3">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-inline">
                                    <b class="pr-2">{{ trans('genre_detail.back_icon') }}{{ trans('genre_detail.regular_case') }}{{ trans('genre_detail.next_icon') }}</b><b>{{ trans('genre_detail.arithmetic_priority_order_available') }}</b>
                                </div>
                                <div class="form-inline">
                                    <label>{{ trans('genre_detail.1_x_y_icon') }}</label>
                                    {{Form::input('text', 'data[AuctionGenre][limit_asap]', isset($results->limit_asap) ? $results->limit_asap : '',
                                     ['disabled'=>$acaAccount, 'id' => 'limit_asap', 'class' => 'form-control form-control-sm mr-2 ml-2', 'data-rule-required'=>'true',
                                      'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'10', 'data-rule-number' => 'true',
                                       'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#limit_asap_feedback'])}}
                                    <label>{{ trans('genre_detail.minutes_great_urgent') }}</label>
                                    @if ($errors->has('data.AuctionGenre.limit_asap'))
                                        <p class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.limit_asap')}}</p>
                                    @endif
                                </div>
                                <div id="limit_asap_feedback"></div>
                                <div class="form-inline pl-2">{{ trans('genre_detail.down_icon') }}</div>
                                <div class="form-inline">
                                    <label>{{ trans('genre_detail.2_x_y_icon') }}</label>
                                    {{Form::input('text', 'data[AuctionGenre][limit_immediately]', isset($results->limit_immediately) ? $results->limit_immediately : '',
                                    ['disabled'=>$acaAccount, 'id' => 'limit_immediately', 'class' => 'form-control form-control-sm mr-2 ml-2',
                                    'data-rule-required'=>'true', 'data-msg-required' => trans('genre_detail.validate_required'),
                                     'maxlength'=>'10', 'data-rule-number' => 'true', 'data-msg-number' => trans('genre_detail.validate_number'),
                                      'data-error-container' => '#limit_immediately_feedback'])}}
                                    <label>{{ trans('genre_detail.minutes_urgent') }}</label>
                                    @if ($errors->has('data.AuctionGenre.limit_immediately'))
                                        <p class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.limit_immediately')}}</p>
                                    @endif
                                </div>
                                <div id="limit_immediately_feedback"></div>
                                <div class="form-inline pl-2">{{ trans('genre_detail.down_icon') }}</div>
                                <div class="form-inline">{{ trans('genre_detail.3_icon') }} {{ trans('genre_detail.to_ordinary_case') }}</div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 note">
                                <small class="form-inline">・{{ trans('genre_detail.when_creating_a_case') }}・・・・[X]</small>
                                <small class="form-inline">・{{ trans('genre_detail.date_and_time_of_visit') }}・・・・[Y]</small>
                                <small class="form-inline">・{{ trans('genre_detail.bid_deadline_date_and_time') }}・・・・[Z]</small>
                                <small class="form-inline">・{{ trans('genre_detail.time_to_bid') }}・・・・[W]</small>
                                <small class="form-inline pl-2">{{ trans('genre_detail.is_the_time_excluding') }}</small>
                                <small class="form-inline">・{{ trans('genre_detail.exclusion_time_set_pattern') }}</small>
                                <small class="form-inline pl-2">{{ trans('genre_detail.analog_selection') }}</small>
                            </div>
                        </div>
                        <div class="pl-4 pt-4">
                            <div class="form-inline"><b>{{ trans('genre_detail.back_icon') }}{{ trans('genre_detail.urgent_case') }}{{ trans('genre_detail.next_icon') }}</b></div>
                            <div class="form-inline">
                                <label>[X] ＋</label>
                                {{Form::input('text', 'data[AuctionGenre][asap]', isset($results->asap) ? $results->asap : '',
                                 ['disabled'=>$acaAccount, 'id' => 'asap', 'class' => 'form-control form-control-sm mr-2 ml-2',
                                 'data-rule-required'=>'true', 'data-msg-required' => trans('genre_detail.validate_required'),
                                  'maxlength'=>'10', 'data-rule-number' => 'true', 'data-msg-number' => trans('genre_detail.validate_number'),
                                   'data-error-container' => '#asap_feedback'])}}
                                <label>{{ trans('genre_detail.minute_z') }}</label>
                                @if ($errors->has('data.AuctionGenre.asap'))
                                    <p class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.asap')}}</p>
                                @endif
                            </div>
                            <div id="asap_feedback"></div>
                            <div class="form-inline">{{ trans('genre_detail.however_when') }}</div>
                            <div class="form-inline text-yellow font-weight-bold">{{ trans('genre_detail.if_the_visit_date') }}</div>
                        </div>
                        <div class="pl-4 pt-4">
                            <div class="form-inline"><b>{{ trans('genre_detail.back_icon') }}{{ trans('genre_detail.urgent_case_n') }}{{ trans('genre_detail.next_icon') }}</b></div>
                            <div class="form-inline">
                                <label>[X] ＋</label>
                                {{Form::input('text', 'data[AuctionGenre][immediately]',
                                 isset($results->immediately) ? $results->immediately : '',
                                  ['disabled'=>$acaAccount, 'id' => 'immediately', 'class' => 'form-control form-control-sm mr-2 ml-2',
                                   'data-rule-required'=>'true', 'data-msg-required' => trans('genre_detail.validate_required'),
                                    'maxlength'=>'10', 'data-rule-number' => 'true', 'data-msg-number' => trans('genre_detail.validate_number'),
                                     'data-error-container' => '#immediately_feedback'])}}
                                <label>{{ trans('genre_detail.minute_z') }}</label>
                                @if ($errors->has('data.AuctionGenre.immediately'))
                                    <p class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.immediately')}}</p>
                                @endif
                            </div>
                            <div id="immediately_feedback"></div>
                            <div class="form-inline">{{ trans('genre_detail.however_when') }}</div>
                            <div class="form-inline">
                                <label>{{ trans('genre_detail.however') }}</label>
                                {{Form::input('text', 'data[AuctionGenre][immediately_small]',
                                 isset($results->immediately_small) ? $results->immediately_small : '',
                                 ['disabled'=>$acaAccount, 'id' => 'immediately_small', 'class' => 'form-control form-control-sm mr-2 ml-2',
                                 'maxlength'=>'10', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'),
                                  'data-error-container' => '#immediately_small_feedback'])}}

                                <label>{{ trans('genre_detail.minute') }}</label> <span class='immediately_small_dis'></span><label>{{ trans('genre_detail.min') }}</label>
                                @if ($errors->has('data.AuctionGenre.immediately_small'))
                                    <p class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.immediately_small')}}</p>
                                @endif
                            </div>
                            <div id="immediately_small_feedback"></div>
                            <div class="form-inline text-yellow font-weight-bold">{{ trans('genre_detail.if_the_reception_time') }}</div>
                        </div>
                        <div class="pl-4 pt-4">
                            <div class="form-inline"><b>{{ trans('genre_detail.back_icon') }}{{ trans('genre_detail.regular_case') }}{{ trans('genre_detail.next_icon') }}</b></div>
                            <div class="form-inline">
                                <label>[X] ＋</label>
                                {{Form::input('text', 'data[AuctionGenre][normal3]',
                                isset($results->normal3) ? $results->normal3 : '',
                                 ['disabled'=>$acaAccount, 'id' => 'normal3', 'class' => 'form-control form-control-sm mr-2 ml-2',
                                  'data-rule-required'=>'true', 'data-msg-required' => trans('genre_detail.validate_required'),
                                  'maxlength'=>'10', 'data-rule-number' => 'true',
                                  'data-msg-number' => trans('genre_detail.validate_number'),
                                  'data-error-container' => '#normal3_feedback'])}}

                                <label>{{ trans('genre_detail.minute_z') }}</label>
                                @if ($errors->has('data.AuctionGenre.normal3'))
                                    <p class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.normal3')}}</p>
                                @endif
                            </div>
                            <div id="normal3_feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-category pt-4">
                    <label class="form-category__label">{{ trans('genre_detail.open_rank_setting') }}</label>
                    <div class="form-category__body">
                        <div class="pl-4">
                            <div class="form-inline">{{ trans('genre_detail.correspondence') }}
                                <span class="pl-3">a</span>
                                {{Form::input('text', 'data[AuctionGenre][open_rank_a]', isset($results->open_rank_a) ? $results->open_rank_a : '',
                                 ['disabled'=>$acaAccount, 'id' => 'open_rank_a', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                  'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                  'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#open_rank_setting_feedback'])}}
                                %<span class="pl-1 pr-1">→</span>b
                                {{Form::input('text', 'data[AuctionGenre][open_rank_b]', isset($results->open_rank_b) ? $results->open_rank_b : '',
                                 ['disabled'=>$acaAccount, 'id' => 'open_rank_b', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                  'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                   'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#open_rank_setting_feedback'])}}
                                %<span class="pl-1 pr-1">→</span>c
                                {{Form::input('text', 'data[AuctionGenre][open_rank_c]', isset($results->open_rank_c) ? $results->open_rank_c : '',
                                ['disabled'=>$acaAccount, 'id' => 'open_rank_c', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#open_rank_setting_feedback'])}}
                                %<span class="pl-1 pr-1">→</span>d
                                {{Form::input('text', 'data[AuctionGenre][open_rank_d]', isset($results->open_rank_d) ? $results->open_rank_d : '',
                                ['disabled'=>$acaAccount, 'id' => 'open_rank_d', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#open_rank_setting_feedback'])}}
                                <span class="pr-2">%</span>
                                {{ trans('genre_detail.first_interaction') }}
                                {{Form::input('text', 'data[AuctionGenre][open_rank_z]', isset($results->open_rank_z) ? $results->open_rank_z : '',
                                 ['disabled'=>$acaAccount, 'id' => 'open_rank_z', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                  'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                   'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#open_rank_setting_feedback'])}}%
                            </div>
                            <div id="open_rank_setting_feedback"></div>
                            @if ($errors->has('data.AuctionGenre.open_rank_a'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.open_rank_a')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.open_rank_b'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.open_rank_b')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.open_rank_c'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.open_rank_c')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.open_rank_d'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.open_rank_d')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.open_rank_z'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.open_rank_z')}}</div>
                            @endif
                            <small>{{ trans('genre_detail.also_linked_with') }}</small>
                            <div class="form-inline text-yellow font-weight-bold">{{ trans('genre_detail.for_example') }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-category">
                    <label class="form-category__label">{{ trans('genre_detail.telephone_desired_case') }}</label>
                    <div class="form-category__body">
                        <div class="pl-4">
                            <div class="form-inline">a
                                {{Form::input('text', 'data[AuctionGenre][tel_hope_a]', isset($results->tel_hope_a) ? $results->tel_hope_a : '',
                                 ['disabled'=>$acaAccount, 'id' => 'tel_hope_a', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                 'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#telephone_desired_case_feedback'])}}
                                %<span class="pl-1 pr-1">→</span>b
                                {{Form::input('text', 'data[AuctionGenre][tel_hope_b]', isset($results->tel_hope_b) ? $results->tel_hope_b : '',
                                ['disabled'=>$acaAccount, 'id' => 'tel_hope_b', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                 'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#telephone_desired_case_feedback'])}}
                                %<span class="pl-1 pr-1">→</span>c
                                {{Form::input('text', 'data[AuctionGenre][tel_hope_c]', isset($results->tel_hope_c) ? $results->tel_hope_c : '',
                                 ['disabled'=>$acaAccount, 'id' => 'tel_hope_c', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                 'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#telephone_desired_case_feedback'])}}
                                %<span class="pl-1 pr-1">→</span>d
                                {{Form::input('text', 'data[AuctionGenre][tel_hope_d]', isset($results->tel_hope_d) ? $results->tel_hope_d : '',
                                ['disabled'=>$acaAccount, 'id' => 'tel_hope_d', 'class' => 'form-control form-control-sm ml-2', 'data-rule-required'=>'true',
                                'data-msg-required' => trans('genre_detail.validate_required'), 'maxlength'=>'3', 'data-rule-number' => 'true',
                                 'data-msg-number' => trans('genre_detail.validate_number'), 'data-error-container' => '#telephone_desired_case_feedback'])}}
                                <span class="pr-2">%</span>
                                {{ trans('genre_detail.first_interaction') }}{{Form::input('text', 'data[AuctionGenre][tel_hope_z]',
                                isset($results->tel_hope_z) ? $results->tel_hope_z : '',
                                 ['disabled'=>$acaAccount, 'id' => 'tel_hope_z', 'class' => 'form-control form-control-sm ml-2',
                                 'data-rule-required'=>'true', 'data-msg-required' => trans('genre_detail.validate_required'),
                                  'maxlength'=>'3', 'data-rule-number' => 'true', 'data-msg-number' => trans('genre_detail.validate_number'),
                                  'data-error-container' => '#telephone_desired_case_feedback'])}}%
                            </div>
                            <div id="telephone_desired_case_feedback"></div>
                            @if ($errors->has('data.AuctionGenre.tel_hope_a'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.tel_hope_a')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.tel_hope_b'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.tel_hope_b')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.tel_hope_c'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.tel_hope_c')}}</div>
                            @endif
                            @if ($errors->has('data.AuctionGenre.tel_hope_d'))
                                <div class="form-control-feedback text-danger mt-1 has-danger font-weight-bold">{{$errors->first('data.AuctionGenre.tel_hope_d')}}</div>
                            @endif
                            <div class="form-inline text-yellow font-weight-bold">{{ trans('genre_detail.since_the_probability') }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-3">
                    <a class="btn btn-default font-weight-bold" href="{{ route('auction_setting.genre') }}">{{ trans('genre_detail.return') }}</a>
                    {{Form::input('submit', 'regist', trans('genre_detail.save'), ['disabled'=>$acaAccount, 'id'=>'regist', 'class'=>'btn btn--gradient-green font-weight-bold'])}}
                    <a class="btn btn--gradient-orange font-weight-bold" href="{{ route('auction_setting.prefecture', ['genreId' => $genreId]) }}">{{ trans('genre_detail.detailed_settings') }}</a>
                </div>
            {{Form::close()}}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/genre_detail.js') }}"></script>
    <script>
        FormUtil.validate('#form-genre-detail');
    </script>
@endsection
