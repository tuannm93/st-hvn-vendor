@extends('layouts.app')
@section('content')
    <?php $areaSort = ['急', '多', '中', '小'] ?>
    <div class="notice-info-near">
        <div class="notice-info-badge my-3">
            <label class="font-weight-bold mb-0 ml-2 py-2 fs-15">{{trans('notice_info.commission_title')}}</label>
        </div>
            @if(Session::has('message'))
                <div class="box__mess box--{{Session::get('class')}}">{{Session::get('message')}}</div>
            @endif
        <div class="p-2">
            <p class="mb-0">{{trans('notice_info.near_about_1')}}</p>
            <p class="mb-0">{{trans('notice_info.near_about_2')}}</p>
        </div>
        <div class="p-2">
            <p class="mb-0">{{trans('notice_info.number_deny_text')}}</p>
            <p class="mb-0 font-weight-bold not_correspond_count_3">{{trans('notice_info.small')}}・・・{{trans('notice_info.years')}} {{$settings['midium_lower_limit']}} {{trans('notice_info.pieces_below')}}</p>
            <p class="mb-0 font-weight-bold text-orange not_correspond_count_2">{{trans('notice_info.during')}}・・・{{trans('notice_info.years')}} {{$settings['midium_lower_limit']}} {{trans('notice_info.item')}}{{ trans('common.wavy_seal') }}{{$settings['large_lower_limit']}} {{trans('notice_info.item')}}</p>
            <p class="mb-0 font-weight-bold text-red not_correspond_count_1">{{trans('notice_info.many')}}・・・{{trans('notice_info.years')}} {{$settings['large_lower_limit']}} {{trans('notice_info.more_than')}}</p>
            <p class="mb-0 font-weight-bold text-red not_correspond_count_0">{{trans('notice_info.anxious')}}・・・{{trans('notice_info.nearly_location')}}</p>
        </div>
        @if(!empty($results))
            <?php $area_count = 0; ?>
            <form method="POST" action="{{route('notice_info.save.near')}}">
                {{csrf_field()}}
                <div class="custom-scroll-x">
                    <table class="table custom-border">
                        <thead>
                            <tr class="text-center bg-yellow-light">
                                <th class="p-1 align-middle w-30">{{trans('notice_info.genre')}}</th>
                                <th class="p-1 align-middle w-70">{{trans('notice_info.area')}}</th>
                            </tr> 
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                <tr class="group_{{$result['NotCorrespond']['development_group']}} {{$result['NotCorrespond']['registed'] ? 'bg-green-light' : ''}} open-collapse" data-toggle="collapse" href=".group_{{$result['NotCorrespond']['development_group']}}_genre"  role="button" aria-expanded="false">
                                    <td colspan="2" class="p-1 align-middle">
                                        <div class="row mx-0">
                                            <div class="col-lg-8 px-0">{{$result['NotCorrespond']['development_group_name']}} ▼</div>
                                            @if($result['NotCorrespond']['registed'])
                                                <div class="col-lg-4 px-0 text-lg-right">{{trans('notice_info.registed_genre')}}</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @foreach($result['MGenre'] as $genre)
                                    <tr class="group_{{$result['NotCorrespond']['development_group']}}_genre {{$result['NotCorrespond']['registed'] ? 'bg-green-light' : ''}} collapse  multi-collapse">
                                        <td class="p-1 align-middle w-30">
                                            <b>{{$genre['genre_name']}}</b><br>
                                            {{trans('notice_info.average_construction')}}: {{!empty($genre['unit_price']) ? yenFormat2($genre['unit_price']) : '-'}}
                                        </td>
                                        <td class="p-1 align-middle w-70">
                                            <?php $i = 0; ?>
                                            @foreach($genre['Correspond'] as $pref)
                                                @if($i%3 == 0) {!! '<div class="d-flex light-border">' !!} @endif
                                                <table class="custom-border">
                                                    <thead>
                                                        <tr class="text-center bg-yellow-light">
                                                            <th class="p-1 align-middle fix-w-150"><?php echo $pref['address1']; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($pref['Area'] as $area)
                                                        <tr>
                                                            <td class="not_correspond_label_{{$area['sort']}} p-1 align-middle fix-w-150">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="id[{{$area_count}}]" class="{{$genre['registed'] ? 'g_registed' : 'g_resigning'}} custom-control-input" name="id[{{$area_count}}]" value="{{$genre['genre_id'].'-'.$area['jis_cd']}}">
                                                                    <label class="custom-control-label" for="id[{{$area_count}}]">{{$area['address2']}}
                                                                        @if($area['new_flg'])
                                                                            <span class="font-weight-bold">new</span>
                                                                        @endif</label>
                                                                        <label class="not_correspond_count_{{$area['sort']}} pull-right">
                                                                        {{!empty($areaSort[$area['sort']]) ? $areaSort[$area['sort']] : '-'}}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $area_count++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @if($i%3 == 2) {!! "</div>" !!} @endif
                                            <?php $i++; ?>
                                            @endforeach
                                            @if($i%3 != 2) {!! "</div>" !!} @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <input type="submit" name="regist-area" class="btn btn--gradient-green col-sm-4 col-lg-2 mb-2 mb-sm-0 remove-effect-btn" value="@lang('notice_info.register_area')"/>
                    <input type="button" name="regist-resigning" class="btn btn--gradient-orange col-sm-4 col-lg-2 remove-effect-btn" value="@lang('notice_info.register_resign')" data-link="/auth_infos/agreement_link" />
                </div>
            </form>
            <div class="pseudo-scroll-bar" data-display="false" check-click="false">
                <div class="scroll-bar"></div>
            </div>
        @else
            <div class="box--success box__mess">{{trans('notice_info.right_block')}}</div>
        @endif
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/pages/notice_info.near.js') }}"></script>
    <script>
        NoticeInfoNear.init();
    </script>
@endsection
