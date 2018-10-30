
@if( $screen == __('demand_detail.status_screen.ok'))
    <label class="form-category__label mt-2">{{$pageTitle}}</label>
    <div class="introduction bg-gray-light border-gray p-2">
        <p class="mb-0">{{ $corpInfo->official_corp_name }}  @lang('demand_detail.dear')</p>
        @if(!empty($pi->up_text))
            {!! str_replace("\n", '<br/>', $pi->up_text) !!}
        @endif
    </div>
    <div class="mt-2">
        @if ( $dataPaginate->count())
            {{ $dataPaginate->links('progress_management.component.paginate_back') }}
        @endif
    </div>

    <div class="items-statistic border-bottom-bold mt-4">
        <label class="font-weight-bold mb-0">{{ $pageInfo  }}</label>
    </div>
    <form id="demandDetailForm" method="post" action="{{ route('get.progress_management.demand_detail', $id) }}"
          accept-charset="utf-8">
        {{ csrf_field() }}
        <input name="ProgImportFile[file_id]" type="hidden" value="{{ $id }}">
        <input name="prog_corp_id" type="hidden" value="{{ $prog_corp_id }}">

        @include('progress_management.demand_detail.table_aff_demand_detail')
        @if(!$dataPaginate->hasMorePages())
            @include('progress_management.demand_detail.form_aff_demand_detail')
        @endif

        @if(!$dataPaginate->hasMorePages())
            <div class="bg-yellow text-white border-gray p-2 mt-4">
                {!! __('demand_detail.pm_caution1') . $corpInfo->official_corp_name .
                    str_replace(__('demand_detail.down_line1'), __('demand_detail.down_line1') . '<br/>', __('demand_detail.pm_caution2')).
                    $corpInfo->official_corp_name .
                    str_replace(__('demand_detail.down_line2'), __('demand_detail.down_line2') . '<br/>', str_replace( __('demand_detail.down_line3'), __('demand_detail.down_line3') . '<br>', __('demand_detail.pm_caution3'))) !!}
            </div>
        @endif

        <div class="border-top mt-4">
            {{ __('demand_detail.notice') }}<br>
            @if(!empty($pi->down_text))
                {!! str_replace("\n", '<br />', $pi->down_text) !!}
            @endif

            <input type="hidden" name="mode" value="check">
            <input type="hidden" name="id" value="{{ $id }}">
            @if(!$dataPaginate->hasMorePages())
                <div class="text-center mt-4">
                    <div class="custom-control custom-checkbox d-inline-block">
                        <input id="ProgDemandInfoOther_agree_flag_lastCheck" class="custom-control-input" type="checkbox" value="1" name="ProgDemandInfoOther[agree_flag]">
                        <label class="custom-control-label" for="ProgDemandInfoOther_agree_flag_lastCheck">{{ __('demand_detail.check_box_message') }}</label>
                        <div class="err-last-checkbox text-left" hidden>@lang('demand_detail.required_mess')</div>
                    </div>
                </div>
            @endif
        </div>
        @if ( $dataPaginate->count())
            <div class="text-center my-3">
                {!! Session::put('demand_detail_id', $id) !!}
                {{ $dataPaginate->links('progress_management.component.paginate_back') }}
                {{ $dataPaginate->links('progress_management.component.paginate_next') }}
            </div>
        @endif
    </form>
    <div class="fixed-button text-center p-1 p-sm-3">
        <p class="text-white">{{ __('demand_detail.count_error') }} ： <span id="nokoriInput"
                                                                            class="fs-16 font-weight-bold">0</span> @lang('common.item')
        </p>
        <button type="submit" class="submitSession btn btn--gradient-green" id="submitSession" name="submitSession"
                value="{{ __('demand_detail.button_submit2') }}">{{ __('demand_detail.button_submit2') }}</button>
    </div>
@elseif($screen == __('demand_detail.status_screen.no_access_role'))
    <div>
        <div>
            @if(!empty($corpInfo)) {{ $corpInfo->official_corp_name }} @endif {{ __('demand_detail.send_completely') }}
        </div>

        <div class="box__mess box--error">
            {{ __('demand_detail.no_result') }}
        </div>
    </div>
@else
    <div>
        <div class="box__mess box--error">
            {{ __('pm_update_confirm.exception_2') }}
        </div>
    </div>
@endif
