
<div class="col-12 p-0">
    <div class="row header-field mb-3">
        <div class="col-12">
            <strong class="pull-left mr-4"> @lang('demand_detail.demand_id'): </strong>
            <strong class="pull-left mr-4">@lang('demand_detail.proposal_status'): </strong>
            <strong class="pull-right">@lang('demand_detail.view_count')：0件</strong>
        </div>
    </div>
    <div class="d-flex justify-content-end mb-3">
        <button id="top_regist" class="btn btn--gradient-green btn--w-normal ml-4">@lang('demand_detail.registration')</button>
    </div>
    @if(Session::has('message'))
    <div class="row header-field my-4 border-2 border border-warning">
        <div class="col-12">
            <strong style="color: #f27b07"> {{ Session::get('message') }} </strong>
        </div>
    </div>
    @endif
    @if(session('error'))
            <p class="alert alert-danger my-2">{{ session('error') }}</p>
    @endif

    @if(Session::has('error_msg_input'))
            <p class="alert alert-danger my-2">{{ Session::get('error_msg_input') }}</p>
    @elseif ($errors->any() || Session::get('demand_errors'))
            <p class="alert alert-danger my-2">{{ __('demand.error_miss_input') }}</p>
    @endif
    <div class="d-flex justify-content-end mb-3">
        <a href="#commissioninfo" class="text--orange text--underline ml-4">▼@lang('demand_detail.partner_information') </a>
        <a href="#jbrdemandinfo" class="text--orange text--underline ml-4">▼@lang('demand_detail.jbr_info') </a>
        <a href="#demandstatus" class="text--orange text--underline ml-4">▼@lang('demand_detail.proposal_status') </a>
        <a href="#introductioninfo" class="text--orange text--underline ml-4">▼@lang('demand_detail.intro_info') </a>
        <a href="#correspondsinfo" class="text--orange text--underline ml-4">▼@lang('demand_detail.corresponding_history_information')</a>
    </div>
</div>
