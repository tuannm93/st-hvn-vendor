@if($isRoleAffiliation)
    <div class="row">
        <div class="col-md-2">
            <div class="top-left {{$notEnough === true ? 'animate' : ''}}">
                <a href="{{route('affiliation.category',['id' => \Auth::user()->affiliation_id])}}"
                   target="_blank">
                    <button type="button"
                            class="green-button-s btn btn--gradient-green  ">@lang("commissioninfos.lbl.genre_btn")</button>
                </a>
            </div>
            <p class="update_info_block">
                @lang("commissioninfos.lbl.last_update_profile")：<br>{{$corpLastUpdateProfile}}<br>
            </p>
            <p class="update_info_block2">
                @lang("commissioninfos.lbl.last_update_category")：<br>{{$corpLastUpdateCategories}}<br>
            </p>
            <p class="update_info_block2">
                @lang("commissioninfos.lbl.last_update_area")：<br>{{substr($corpLastUpdateArea, 0, 19)}}<br>
            </p>
        </div>
        <div class="col-md-10 left-block">
            <p class="indicate-block mt-4 pt-3">
                @lang("commissioninfos.lbl.area_update_notice_line_1")<br>
                @lang("commissioninfos.lbl.area_update_notice_line_2")
            </p>
        </div>
    </div>
@endif

