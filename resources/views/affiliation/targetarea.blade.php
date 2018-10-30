@extends('layouts.app')
@section('content')
    <div class="affiliation-targetarea">
        @if(Session::has('Update'))
            <p class="box__mess box--success">{{ Session::get('Update') }}</p>
        @endif
        @if(Session::has('InputError'))
            <p class="box__mess box--error">{{ Session::get('InputError') }}</p>
        @endif
        <div class="row header-field mt-3">
            <div class="col-sm-6 col-lg-auto">
                <strong>{{ trans('targetarea.genre') }}:
                @if(isset($dataGenreAndCategory['genre_name']))
                    {{ $dataGenreAndCategory['genre_name'].' ' }}
                @endif</strong>
            </div>
            <div class="col-sm-6 col-lg-auto">
                <strong>{{ trans('targetarea.category') }}:
                @if(isset($dataGenreAndCategory['category_name']))
                    {{ $dataGenreAndCategory['category_name'] }}
                @endif</strong>
            </div>
        </div>
        <div class="form-category mt-4">
            <label class="form-category__label">{{ trans('targetarea.setting_corresponding_area_by_category') }}</label>
        </div>
        <p><strong>{{ trans('targetarea.you_can_set_the_corresponding') }}</strong></p>
        <div class="corp_table pb-3">
            <div class="row">
                @foreach($prefList as $key => $val)
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
                            <div class="w-50 font-weight-bold pl-3 fs-16 pref_sub2">
                                {{$val['name']}}
                            </div>
                            <div class="w-25 font-weight-bold pref_sub3">
                                <a class="p-2 highlight-link val_check_button" href="javascript:void(0);" data-name="{{ $val['name'] }}" data-url="{{ route('ajax.searchTargetArea', ['id' => $corpId, 'name' => $val['name']]) }}">{{ trans('aff_corptargetarea.configuration') }}≫</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if(isset($lastModified['modified']))
            <p class="mt-3 mb-5">{{ trans('corptargetarea.last_updated') }}：{{substr($lastModified['modified'], 0, 19)}}</p>
        @endif
    </div>
    <div class="modal affiliation-targetarea-modal" tabindex="-1" role="dialog" id="area_check">
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
                {{Form::open(['enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['affiliation.targetarea', $corpId] , 'accept-charset'=>"UTF-8" ])}}
                <div class="modal-body my-3 py-0 px-sm-5" id="display_modal_area">
                </div>
                <div class="text-center mb-3">
                    {{Form::input('submit', 'regist', trans('aff_corptargetarea.regist'), ['onclick'=>'javascript:regist_double_click_stop()', 'id'=>'regist' ,'class'=>'btn btn--gradient-green col-3 mb-3'])}}
                </div>
                {{ Form::input('hidden', 'address1_text', trans('aff_corptargetarea.address1_text'), ['id'=>'address1_text']) }}
                {{ Form::input('hidden', 'corp_category_id', $corpId, ['id'=>'corp_category_id']) }}
                {{ Form::input('hidden', 'corp_id', isset($dataGenreAndCategory['corp_id']) ? $dataGenreAndCategory['corp_id'] : '', ['id'=>'corp_id']) }}
                {{ Form::input('hidden', 'genre_id', isset($dataGenreAndCategory['genre_id']) ? $dataGenreAndCategory['genre_id'] : '', ['id'=>'genre_id']) }}
                <input type="hidden" value="">
                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/aff_targetarea.js') }}"></script>
@endsection
