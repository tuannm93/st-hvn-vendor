@if($isRoleAffiliation)
    <div class="caption_clearfix">
        <div class="row mt-3">
            <div class="col-md-2">
                <div class="caption_button float-right float-sm-none">
                        <a href="{{route('addition.index')}}"
                        class="btn_orange btn-orange btn btn--gradient-orange">@lang('commissioninfos.lbl.addition')</a>
                </div>
            </div>
            @if(!$isMobile)
                <div class="col-md-10">
                    <div class="caption_block">
                        @lang('commissioninfos.lbl.addition_notice')
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="caption_clearfix">
        <div class="row mt-3">
            <div class="col-md-2">
                <div class="caption_button float-right float-sm-none">
                        <a href="{{route('report.addition')}}"
                           class="btn_orange btn-orange btn btn--gradient-orange">@lang('commissioninfos.lbl.addition_report')</a>
                </div>
            </div>
        </div>
    </div>
@endif
