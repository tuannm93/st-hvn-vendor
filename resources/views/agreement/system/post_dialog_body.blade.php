<div>
    <label class="form-category__label mb-4 d-none d-sm-block">
        [{{$address1}}] 市町村リスト
    </label>
    @if($viewMode)
        <div class="text-center">
            <button class="btn btn--gradient-orange"
                    name="all_check"
                    id="all_check">
                @lang('agreement_system.select_all_area')
            </button>&nbsp;
            <button class="btn btn--gradient-orange"
                    name="all_release"
                    id="all_release">
                @lang('agreement_system.cancel_all_area')
            </button>
        </div>
    @endif
    <br>
    <br>
    <div id="area_info">
        <form id="postDialogFormId" method="post" action="{{route('post.post_dialog')}}">
            {{ csrf_field() }}
            <input hidden name="addressCd" value="{{$addressCd}}"/>
            <div class="form-group row">
                @foreach($postList as $index => $post)
                    {{--@if($index % 6 == 0)--}}
                    <div class="col-6 col-lg-2 form-check">
                        {{--@endif--}}
                        {{--<td>--}}
                        <input type="checkbox"
                               class="postCheck check_group"
                               name="jisCd_{{$post['jis_cd']}}"
                               id="jisCd_{{$post['jis_cd']}}"
                               @if(!checkIsNullOrEmpty($post['corp_id']))  {{'checked'}} @endif
                               value="{{$post['jis_cd']}}">
                        <label for="jisCd_{{$post['jis_cd']}}">{{$post['address2']}}</label>
                        {{--</td>--}}
                        {{--@if(($index + 1) % 6 == 0)--}}
                    </div>
                    {{--@endif--}}
                @endforeach
            </div>
            @if($viewMode)
                <div class="row justify-content-center">
                    <button id="registerAreaId" class="btn btn--gradient-green w-30 text-white"
                            type="submit">@lang('agreement_system.btn_register')</button>
                </div>
            @endif
        </form>
    </div>
</div>