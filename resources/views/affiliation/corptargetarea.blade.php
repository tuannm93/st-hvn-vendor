@extends('layouts.app')
@section('content')
    <div class="affiliation-corptargetarea mt-3 pt-2">
        @if(Session::has('Update'))
            <p class="box__mess box--success">{{ Session::get('Update') }}</p>
        @endif
        @if(Session::has('InputError'))
            <p class="box__mess box--error">{{ Session::get('InputError') }}</p>
        @endif
        <div class="form-category">
            <label class="form-category__label d-none d-sm-block">{{ trans('corptargetarea.setting_area') }}</label>
            <label class="form-category__label collapse-label-mobi d-block d-sm-none" data-toggle="collapse" data-target="#setting-area" aria-expanded="false" aria-controls="setting-area">{{ trans('corptargetarea.setting_area') }} <i class="fa fa-chevron-down float-right" aria-hidden="true"></i></label>
        </div>
        <div id="setting-area" class="corp_table pb-3 mb-0 mb-sm-5 collapse show">
            <p><strong>{{ trans('corptargetarea.can_set_prefecture') }}</strong></p>
            <div class="row">
                @foreach($pref_list as $key => $val)
                    <div class="col-md-6 col-xl-3 my-2 pref_block">
                        <div class="form-inline pref_sub">
                            @if($val['rank'] == 2)
                                <div class="w-25 text-center font-weight-bold py-2 pref_sub1" id="taiou_'.{{$val['id']}}.'">
                                    {{ trans('aff_corptargetarea.all_regions') }}<br />{{ trans('aff_corptargetarea.compatible') }}
                                </div>
                            @elseif($val['rank'] == 1)
                                <div class="w-25 text-center font-weight-bold py-2 pref_sub1_part" id="taiou_'.{{$val['id']}}.'">
                                    {{ trans('aff_corptargetarea.some_areas') }}<br />{{ trans('aff_corptargetarea.compatible') }}
                                </div>
                            @else
                                <div class="w-25 text-center font-weight-bold py-2 pref_sub1_non" id="taiou_'.{{$val['id']}}.'">
                                    {{ trans('aff_corptargetarea.correspondence') }}<br />{{ trans('aff_corptargetarea.impossible') }}
                                </div>
                            @endif
                            <div class="w-50 font-weight-bold pl-3 pref_sub2">
                                {{$val['name']}}
                            </div>
                            <div class="w-25 font-weight-bold pref_sub3">
                                <a class="p-2 highlight-link val_check_button" href="javascript:void(0);" data-name="{{ $val['name'] }}" data-url="{{ route('ajax.searchCorpTargetArea', ['id' => $id, 'name' => $val['name']]) }}">{{ trans('aff_corptargetarea.configuration') }}≫</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-category">
            <label class="form-category__label d-none d-sm-block">{{ trans('corptargetarea.setting_genre') }}</label>
            <label class="form-category__label collapse-label-mobi d-block d-sm-none" data-toggle="collapse" data-target="#genre-area" aria-expanded="false" aria-controls="genre-area">{{ trans('corptargetarea.setting_genre') }} <i class="d-inline d-sm-none fa fa-chevron-down float-right" aria-hidden="true"></i></label>
        </div>
        <div id="genre-area" class="collapse show">
        @if(count($genre_custom_area_list) > 0 || count($genre_normal_area_list) > 0)
            <p>{{ trans('corptargetarea.register_here') }}<br> {{ trans('corptargetarea.put_checkbox') }}</p>
            @if(count($genre_normal_area_list) > 0)
                <h6 class="border-left-orange font-weight-bold pl-2 mt-2">{{trans('corptargetarea.genre_area')}}</h6>
                <div class="genre_contents mt-3 p-2">
                    <div class="row">
                    @foreach($genre_normal_area_list as $key => $val)
                        <div class="col-md-6 col-xl-3 my-2 ">
                            <div class="form-inline select_genre_sub">
                                <div class="w-45 pr-2 text-sm-right font-weight-bold select_genre_sub1">{{$val['genre_name']}}</div>
                                <div class="w-45 mr-2 text-center font-weight-bold select_genre_sub2">
                                    <div class="select_genre_sub2_btn"><a class="highlight-link p-1" href="{{ route('affiliation.targetarea', ['id' => $val['id']]) }}" target="_blank">{{ trans('aff_corptargetarea.by_genre') }}<br />{{ trans('aff_corptargetarea.corresponding_area_editing') }} ≫</a></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            @else
                <h6 class="border_left_orange font-weight-bold pl-2 mt-2">{{trans('corptargetarea.basic_correspondence')}}</h6>
                <p class="orange_right_block p-2 mt-3">{{trans('corptargetarea.no_corresponding_genre')}}</p>
            @endif
            @if (count($genre_custom_area_list) > 0)
                {{Form::open(['enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['affiliation.corptargetarea', $id] , 'accept-charset'=>"UTF-8"])}}
                <h6 class="border_left_orange font-weight-bold pl-2 mt-2">{{trans('corptargetarea.individually_changed')}}</h6>
                <div class="genre_contents mt-3 p-2">
                    <div class="row">
                    @foreach ($genre_custom_area_list as $key => $val)
                        <div class="col-md-6 col-xl-3 my-2 ">
                            <div class="form-inline select_genre_sub">
                                <div class="w-45 pr-2 text-sm-right font-weight-bold select_genre_sub1">
                                    <p>{{$val['genre_name']}}</p>
                                </div>
                                <div class="w-45 mr-2 text-center font-weight-bold select_genre_sub2">
                                    <div class="select_genre_sub2_btn"><a class="highlight-link p-1" href="{{ route('affiliation.targetarea', ['id' => $val['id']]) }}" target="_blank">{{ trans('aff_corptargetarea.by_genre') }}<br />{{ trans('aff_corptargetarea.corresponding_area_editing') }} ≫</a></div>
                                </div>
                                <div class="select_genre_sub3">
                                    <input type="checkbox" name="data[genre_id][{{$key}}]" id="genre_id{{$key}}" value="{{$val['genre_id']}}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
                <div class="text-center mt-5">
                    <input name="regist-base-update" id="regist-base-update" class="btn--gradient-green py-1 px-3" type="submit" value="{{ trans('aff_corptargetarea.apply_basic') }}">
                </div>
                {{Form::close()}}
            @else
                <h6 class="border_left_orange font-weight-bold pl-2 mt-2">{{trans('corptargetarea.individually_changed')}}</h6>
                <p class="orange_right_block p-2 mt-3">{{trans('corptargetarea.no_corresponding_genre')}}</p>
            @endif
        @else
            <p class="orange_right_block p-2 mt-3">{{ trans('corptargetarea.no_registration') }}</p>
        @endif
        @if(isset($last_modified['modified']))
            <p class="mt-3 mb-5">{{ trans('corptargetarea.last_updated') }}：{{substr($last_modified['modified'], 0, 19)}}</p>
        @endif
        @if (isset($init_pref))
            <p id="init_pref" data-name="{{ $init_pref }}" data-url="{{ route('ajax.searchCorpTargetArea', ['id' => $id, 'name' => $init_pref ]) }}"></p>
        @endif
        </div>
    </div>
    <div class="modal affiliation-corptargetarea-modal" tabindex="-1" role="dialog" id="area_check">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header p-1">
                    <button type="button" class="close fs-11" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="d-flex flex-column flex-sm-row justify-content-center mt-3 text-center">
                    {{Form::input('button', 'all_check', trans('aff_corptargetarea.select_all_areas'), ['div'=>false, 'label'=>false , 'id'=>'all_check' ,'class'=>'btn btn--gradient-orange remove-effect-btn mx-2 mr-sm-2 mb-2 mb-sm-0'])}}
                    {{Form::input('button', 'all_release', trans('aff_corptargetarea.all_release'), ['div'=>false, 'label'=>false , 'id'=>'all_release' ,'class'=>'btn btn--gradient-orange remove-effect-btn mx-2'])}}
                </div>
                {{Form::open(['enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['affiliation.corptargetarea', $id] , 'accept-charset'=>"UTF-8" ])}}
                {{Form::token()}}
                <div class="modal-body my-3 py-0 px-sm-5" id="display_modal_area">
                </div>
                <div class="text-center mb-3">
                    {{Form::input('submit', 'regist', trans('aff_corptargetarea.regist'), ['id'=>'regist' ,'class'=>'btn btn--gradient-green col-3 mb-3'])}}
                </div>
                {{Form::input('hidden', 'address1_text', trans('aff_corptargetarea.address1_text'), ['id'=>'address1_text'])}}
                <input type="hidden" value="">
                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/aff_corptargetarea.js') }}"></script>
@endsection
