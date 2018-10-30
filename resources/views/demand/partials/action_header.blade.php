@if(!isset($cross) && !isset($copy))
    @if($demand->over_limit_time && $demand->over_limit_time_format && !Session::has('disableLimit'))
        <h4 class="text-danger text-center">取次完了時間リミットを超えております。至急対応して下さい。（{{ $demand->over_limit_time_format }})</h4>
    @endif
@endif
<div class="row header-field mb-4">
    <div class="col-12">
        <strong class="pull-left mr-4">案件ID: {{ isset($demand->id) ? $demand->id : '' }}</strong>
        <strong class="pull-left mr-4">案件状況: {{ (isset($demand->status_name) && !isset($cross) && !isset($copy)) ? $demand->status_name : '' }}</strong>
        <input type="hidden" value="{{ $demand->status_id ?? '' }}" id="demand_status_id" />
        <strong class="pull-right">閲覧数 : <span class="total-current-views">0</span>件</strong>
    </div>
</div>
<div class="col-12 p-0">
    @if(!isset($copy) && !isset($cross))
    <div class="d-flex flex-column flex-sm-row justify-content-end mb-3">
         <a href="{{ route('demand.detail.cross', $demand->id) }}" target="_blank" class="btn btn--gradient-default col-sm-3 col-xl-1 mr-0 mr-sm-1">{{ __('demand.cross_label') }}</a>
         <a href="{{ route('demand.detail.copy', $demand->id) }}" id="btn-copy" class="btn btn--gradient-default col-sm-3 col-xl-1">{{ __('demand.copy_label') }}</a>
    </div>
    @endif
    <div class="d-flex flex-column flex-sm-row justify-content-end mb-3">
        <button id="top_regist" class="btn btn--gradient-green col-sm-3 col-xl-1">登録</button>
        @if(session()->has('again_enabled'))
            &nbsp;<a href="{{ route('demand.detail', $demand->id) }}" class="btn btn--gradient-default col-sm-3 col-xl-1">再表示</a>
        @endif
        @if(!isset($copy) && !isset($cross))
            @if(auth()->user()->auth != 'popular')
            <button class="btn btn--gradient-default col-sm-3 col-xl-1 ml-0 ml-sm-1" id="demand_delete" type="button">削除</button>
            @endif
            <button class="btn btn--gradient-default col-sm-3 col-xl-1 ml-0 ml-sm-1" id="commission_print" type="button">取次票印刷</button>
        @endif
        @if(isset($copy) || isset($cross))
            &nbsp;<a href="{{ route('demand.get.create') }}" class="btn btn--gradient-default col-sm-3 col-xl-1">クリア</a>
        @endif
        <input type="hidden" value="{{ isset($demand->id) ? $demand->id : '' }}" id="demand_id">
    </div>
    @php
        $checkStaffInCorp = Session::get('check_staff_in_corp');
    @endphp
    @if(Session::has('check_staff_in_corp'))
        <div class="row header-field my-4 border-2 border border-warning">
            <div class="col-12 text-left">
                <strong class="text-left text-danger" style="color: #f27b07"> {{ $checkStaffInCorp }} </strong>
            </div>
        </div>
    @elseif(Session::has('message'))
        @foreach( Session::get('message') as $message)
            <div class="row header-field my-4 border-2 border border-warning">
                <div class="col-12 text-left">
                    <strong class="text-left" style="color: #f27b07">{{ $message }}</strong>
                </div>
            </div>
        @endforeach
    @elseif(Session::has('error_msg_input'))
        <div class="row header-field my-4 border-2 border border-warning">
            <div class="col-12 text-left">
                <strong class="text-left text-danger" style="color: #f27b07"> {{ Session::get('error_msg_input') }} </strong>
            </div>
        </div>
    @elseif($errors->any() && !$errors->has('demand_attached_file.*'))
        <div class="row header-field my-4 border-2 border border-warning">
            <div class="col-12 text-left">
                <strong class="text-left text-danger" style="color: #f27b07">  @lang('demand.error_miss_input') </strong>
            </div>
        </div>
    @elseif($errors->has('demand_attached_file.*'))
        <div class="row header-field my-4 border-2 border border-warning">
            <div class="col-12 text-left">
                <strong class="text-left text-danger" style="color: #f27b07"> {{ $errors->first() }} </strong>
            </div>
        </div>
    @elseif(session('actionSuccess'))
            <div class="row header-field my-4 border-2 border border-warning">
                <div class="col-12 text-left">
                    <strong class="text-left" style="color: #f27b07">{{ session('actionSuccess') }}</strong>
                </div>
            </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="#commissioninfo" class="text--orange text--underline ml-4">▼取次先情報 </a>
        <a href="#jbrdemandinfo" class="text--orange text--underline ml-4">▼JBR様案件情報 </a>
        <a href="#demandstatus" class="text--orange text--underline ml-4">▼案件状況 </a>
        <a href="#introductioninfo" class="text--orange text--underline ml-4">▼紹介先情報 </a>
        <a href="#correspondsinfo" class="text--orange text--underline ml-4">▼対応履歴情報</a>
    </div>
</div>


<div class="modal modal-global" tabindex="-1" role="dialog" id="commission_print_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header p-1">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5" id="display_modal_area">

            </div>
            <div class="text-center">
                <button type="button" class="btn btn--gradient-gray btn--w-normal ml-4" id="commission_print_close">
                    閉じる
                </button>
                <p></p>
            </div>
        </div>
    </div>
</div>
