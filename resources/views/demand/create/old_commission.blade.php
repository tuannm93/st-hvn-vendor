<div class="form-table mb-4 commission-table old-commission">
    <div class="row mx-0 form-table-cell">
        <div class="col-12 row m-0 bg-primary-lighter p-0">
            <div class="col-12 col-lg-3 px-0">
                <div class="form__label form__label--primary p-3 h-100">
                    <label class="m-0">

                        <strong>@lang('demand_detail.supplier')<span class="max-index">{{ $key + 1 }}</span></strong>
                    </label>
                    <button data-url_data="{{ route('ajax.demand.load_m_corp', [$commissionInfo['corp_id']]) }}"
                            data-toggle="modal" type="button"
                            class="btn btn-sm btn--gradient-default m-corps-detail">
                        @lang('demand_detail.information_reference')
                    </button>
                </div>
            </div>
            <div class="col-12 col-lg-6 py-3">
                <div class="form-group w-100 mb-lg-0">
                    {!! Form::text('commissionInfo['. $key .'][mCorp][corp_name]', $commissionInfo['mCorp']['corp_name'], ['class' => 'form-control', 'id' => 'corp_name' . $key]) !!}
                </div>
            </div>
            {{--<div class="col-12 col-lg-3 py-3 text-right"><a target='_blank' class="text--orange" href="{{ route('commission.detail', ['id' => $commissionInfo->id ?? '']) }}">詳細</a></div>--}}
        </div>

    </div>
    <div class="row mx-0 form-table-cell">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">@lang('demand_detail.procedure-dial')</label>
            <div class="col-lg-6 d-flex align-items-center py-2">
                @if($commissionInfo['mCorp']['commission_dial'])
                    <a href="{{ checkDevice().$commissionInfo['mCorp']['commission_dial'] }}" class="text--orange ml-2 w-50 text--underline">
                        {{ $commissionInfo['mCorp']['commission_dial'] }}
                    </a>
                @endif
                <div class="text-right w-50">
                    <a href="{{ route('demandlist.search', ['id' => $commissionInfo['corp_id'], 'bCheck' => 1]) }}" target="_blank" class="btn btn--gradient-gray">
                        @lang('demand_detail.b_check')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">
              <label for='del_flg{{ $key }}'>@lang('demand_detail.delete')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3">
                <!-- {!! Form::hidden('commissionInfo['. $key .'][del_flg]', 0) !!} -->
                {!! Form::checkbox('commissionInfo['. $key .'][del_flg]', 1, false, ['class' => 'custom-control-input', 'id' => 'del_flg'.$key]) !!}
                <label class="custom-control-label custome-label" for='del_flg{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0 form-table-cell">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">@lang('demand_detail.appointers')</label>
            <div class="col-lg-6 py-2 d-flex align-items-center">
                {!! Form::select('commissionInfo['. $key .'][appointers]', $userDropDownList, $commissionInfo['appointers'], ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0" >
              <label for='first_commission{{ $key }}'>@lang('demand_detail.initial-check')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3">
                <!-- {!! Form::hidden('commissionInfo['. $key .'][first_commission]', 0) !!} -->
                {!! Form::checkbox('commissionInfo['. $key .'][first_commission]', 1, false, ['class' => 'custom-control-input', 'id' => 'first_commission' . $key]) !!}
                <label class="custom-control-label custome-label" for='first_commission{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0 form-table-cell">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">@lang('demand_detail.mail-order-sender')</label>
            <div class="col-lg-6 py-2 d-flex align-items-center">
                {!! Form::select('commissionInfo['. $key .'][commission_note_sender]', $userDropDownList, $commissionInfo['commission_note_sender'], ['class' => 'form-control now_date commission_note_sender', 'id' => 'commission_note_sender'.$key, 'data-key' => $key]) !!}
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0" >
              <label for='unit_price_calc_exclude{{ $key }}'>@lang('demand_detail.not-covered-by-contract-price')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3">
                {!! Form::hidden('commissionInfo[' . $key . '][corp_id]', $commissionInfo['corp_id'], ['class' => 'corp_id', 'id' => 'CommissionInfo' . $key . 'CorpId']) !!}
                <!-- {!! Form::hidden('commissionInfo['. $key .'][unit_price_calc_exclude]', 0) !!} -->
                {!! Form::checkbox('commissionInfo['. $key .'][unit_price_calc_exclude]', 1, false, ['class' => 'custom-control-input', 'id' => 'unit_price_calc_exclude' . $key]) !!}
                <label class="custom-control-label custome-label" for='unit_price_calc_exclude{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0 form-table-cell">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <div class="col-12 col-lg-6 px-0 form__label form__label--white-light d-flex align-items-center">
                <label class="m-0 pl-3">
                    <strong>@lang('demand_detail.date-time-of-agency-sent')</strong>
                </label>
            </div>
            <div class="col-12 col-lg-6 py-2 d-flex align-items-center">
                <div class="form-group d-flex justify-content-around mb-lg-0 w-100">

                    {!! Form::text('commissionInfo['. $key .'][commission_note_send_datetime]', $commissionInfo['commission_note_send_datetime_format'] ?? '', ['class' => 'form-control datetimepicker date commission_note_send_datetime'.$key, 'id' => 'commission_note_send_datetime'.$key]) !!}
                </div>
                @if(session('commission_errors') && isset(session('commission_errors')[$key]['commission_note_send_datetime']))
                <label class="invalid-feedback d-block">
                    {{ session('commission_errors')[$key]['commission_note_send_datetime'] }}
                </label>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <div class="col-12 col-lg-6 px-0 form__label form__label--white-light d-flex align-items-center">
                <label class="m-0 pl-3" >
                    <label for='commit_flg{{ $key }}'>@lang('demand_detail.confirm')</label>
                </label>
            </div>
            <div class="col-12 col-lg-6 py-2 d-flex align-items-center justify-content-between">
                <div class="d-inline-block custom-control custom-checkbox">
                    <!-- {!! Form::hidden('commissionInfo['. $key .'][commit_flg]', 0) !!} -->
                    {!! Form::checkbox('commissionInfo['. $key .'][commit_flg]', 1, false, ['class' => 'commission_commit_flg custom-control-input chk-commit-flg', 'id' => 'commit_flg'. $key]) !!}
                    <label class="custom-control-label" for='commissionInfo["commit_flg]'>&nbsp;</label>
                </div>
                <div class="d-inline-block custom-control custom-checkbox demand-detail-custom-checkbox">
                    <span>(</span>
                    {!! Form::hidden('demandInfo[calendar_flg]', 0, ['id' => 'calendar_flg_' . $key . '_']) !!}
                    {!! Form::checkbox('demandInfo[calendar_flg]', 1, old('demandInfo')['calendar_flg'] == 1,
                    ['class' => 'custom-control-input calendar_check', 'id' => 'calendar_flg_' . $key]) !!}
                    <label class="custom-control-label demand-detail-label-custom" for="calendar_flg_{{ $key }}">@lang('demand_detail.calendar_flg_use'):</label>
                    <span class="demand-detail-close">)</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0 form-table-cell">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">@lang('demand_detail.no_mail')@lang('demand_detail.fax-transmission')</label>
            <div class="col-lg-6 py-2 d-flex align-items-center">
                <p class="mb-0">
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax]', $commissionInfo['send_mail_fax']) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax_datetime]', $commissionInfo['send_mail_fax_datetime'] ?? null) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax_sender]', $commissionInfo['send_mail_fax_sender'] ?? null) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax_othersend]', $commissionInfo['send_mail_fax_othersend'] ?? null) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][demand_id]', $demand['id'] ?? '') !!}
                    @if($commissionInfo['send_mail_fax'] == 1)

                        @lang('demand_detail.送信済み')　{{ $commissionInfo['send_mail_fax_datetime'] ?? '' }}
                        @if(isset($commissionInfo['send_mail_fax_sender']) && isset($userDropDownList[$commissionInfo['send_mail_fax_sender']]))
                            {{ $userDropDownList[$commissionInfo['send_mail_fax_sender']] }}
                        @endif
                    @endif
                </p>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0" >
              <label for="lost_flg{{ $key }}">@lang('demand_detail.prior-to-ordering')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3">
                <!-- {!! Form::hidden('commissionInfo['. $key .'][lost_flg]', 0) !!} -->
                {!! Form::checkbox('commissionInfo['. $key .'][lost_flg]', 1, false, ['class' => 'custom-control-input', 'id' => 'lost_flg' . $key]) !!}
                <label class="custom-control-label custome-label" for='lost_flg{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0 form-table-cell">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">
                {!! Form::hidden('commissionInfo['. $key .'][order_fee_unit]', $commissionInfo['order_fee_unit'] ?? null) !!}
                {!! Form::hidden('commissionInfo['. $key .'][complete_date]', $commissionIn['complete_date'] ?? '') !!}
                {!! Form::hidden('commissionInfo['. $key .'][commission_status]', $commissionInfo['commission_status'] ?? '') !!}
                {!! Form::hidden('commissionInfo['. $key .'][commission_string]', $commissionInfo['commission_string'] ?? '') !!}
                {{ $commissionInfo['commission_string'] ?? '' }}
            </label>
            <div class="col-lg-6 d-flex align-items-center py-2">
                {!! Form::hidden('commissionInfo['. $key .'][commission_fee_rate]', $commissionInfo['commission_fee_rate'] ?? '') !!}
                {!! Form::hidden('commissionInfo['. $key .'][commission_type]', $commissionInfo['commission_type']) !!}
                {!! Form::hidden('commissionInfo['. $key .'][corp_commission_type_disp]', $commissionInfo['corp_commission_type_disp'] ?? '') !!}
                {!! Form::hidden('commissionInfo['. $key .'][id]', $commissionInfo['id'] ?? '') !!}
                <p class="get-fee-data mb-0">{{ $commissionInfo['corp_commission_type_disp'] ?? '' }} {{ $commissionInfo['commission_fee_dis'] ?? '' }}</p>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0" >
              <label for='corp_claim_flg{{ $key }}'>@lang('demand_detail.merchant-claim')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3">
                <!-- {!! Form::hidden('commissionInfo['. $key .'][corp_claim_flg]', 0) !!} -->
                {!! Form::checkbox('commissionInfo['. $key .'][corp_claim_flg]', 1, false, ['class' => 'custom-control-input', 'id' => 'corp_claim_flg'. $key]) !!}
                <label class="custom-control-label custome-label" for='corp_claim_flg{{ $key }}'></label>
            </div>
        </div>
    </div>

    @if (isset($commissionInfo['staff_name']) && !empty($commissionInfo['staff_name']))
    <div class="row mx-0 form-table-cell">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <div class="col-12 col-lg-6 px-0">
                <div class="form__label form__label--white-light p-3 h-100">
                    <label class="m-0">
                        <strong>@lang('demand_detail.name_staff')</strong>
                    </label>
                </div>
            </div>
            <div class="col-12 col-lg-6 py-2">
                <div class="form-group d-flex mb-lg-0 p-3 h-100">
                    <input type="hidden" class="staff_id" name="commissionInfo[{{ $key }}][id_staff]" value="{{ $commissionInfo['id_staff'] ?? '' }}" />
                    <input type="hidden" name="commissionInfo[{{ $key }}][staff_name]" value="{{ $commissionInfo['staff_name'] ?? '' }}" />
                    <p class="m-0">{{ $commissionInfo['staff_name'] ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">@lang('demand_detail.phone_staff')</label>
            <div class="col-lg-6 d-flex align-items-center py-2">
                <input class="custom-control-input" id="commissionInfo[{{ $key }}]"
                        name="commissionInfo[{{ $key }}][phone_staff]" type="hidden"
                        value={{ $commissionInfo['phone_staff'] ?? '' }} >
                <a class="text--orange" for="" href="{{checkDevice().($commissionInfo['phone_staff'] ?? '')}}">{{ $commissionInfo['phone_staff'] ?? '' }}</a>
            </div>
        </div>
    </div>
    @endif


    <div class="row mx-0 form-table-cell">
        <div class="col-12 col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0">
                <strong>@lang('demand_detail.unit-price-rank') </strong>
            </label>
            <div class="col-12 col-lg-6 py-2">
                <div class="form-group d-flex  mb-lg-0 pt-3 pb-3 pr-3 pl-0 h-100">
                    {!! Form::hidden('commissionInfo['. $key .'][select_commission_unit_price_rank]', $commissionInfo['select_commission_unit_price_rank']) !!}
                    <p class="m-0">{{ $commissionInfo['select_commission_unit_price_rank'] ?? '' }}@lang('demand_detail.unit-price-per-contract')   {{ yenFormat2($commissionInfo['select_commission_unit_price'] ?? '') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 row m-0 p-0">
            <div class="col-12 col-lg-6 px-0 form__label form__label--white-light d-flex align-items-center">
                <label class="m-0 pl-3">
                    <strong></strong>
                </label>
            </div>
            <div class="col-12 col-lg-6 py-2">

            </div>
        </div>
    </div>

    <div class="row mx-0 form-table-cell" style="display: {{ empty($commissionInfo['commission_type']) ? 'none' : 'display' }};">
        <div class="col-lg-6 row m-0 p-0">&nbsp;</div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">
              <label for='introduction_not{{$key}}'>紹介不可</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                {!! Form::checkbox('commissionInfo['. $key .'][introduction_not]', 1, false, ['class' => 'custom-control-input', 'id' => 'introduction_not' . $key, 'disabled' => true]) !!}
                <label class="custom-control-label custome-label" for="introduction_not{{$key}}"></label>
            </div>
        </div>

    </div>


    <div class="row mx-0 form-table-cell">
        <div class="col-12 row m-0 p-0">
            <div class="col-12 col-lg-3 px-0 form__label form__label--white-light d-flex align-items-center">
                    <label class="m-0 pl-3">
                        <strong>@lang('demand_detail.notes')</strong>
                    </label>
            </div>
            <div class="col-12 col-lg-9 py-2">
                <div class="form-group d-flex mb-lg-0 pt-3 pb-3 pr-3 pl-0 h-100">
                    {!! nl2br($commissionInfo['attention'] ?? '')  !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-0 form-table-cell">
        <div class="col-12 row m-0 p-0">
            <div class="col-12 col-lg-3 px-0 form__label form__label--white-light d-flex align-items-center">
                <label class="m-0 pl-3">
                    <strong>@lang('demand_detail.long-term-holidays-situation')</strong>
                </label>
            </div>
            <div class="col-12 col-lg-9 py-2">
                <table class="table table-bordered table-list">
                    <thead>
                        @if(isset($commissionInfo['mCorpNewYear']))
                            <tr>
                                <?php $count = 1; ?>
                                @foreach($commissionInfo['mCorpNewYear'] as $k => $label)
                                    @if(strpos($k, 'label') !== false)
                                        {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][' . $k . ']', empty($label) ? old('vacations')[$count] : $label) !!}
                                        <th class="text-center">{{ empty($label) ? old('vacations')[$count] : $label }}</th>
                                    @endif
                                    <?php $count++; ?>
                                @endforeach
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(isset($commissionInfo['mCorpNewYear']))
                            <tr>
                                @foreach($commissionInfo['mCorpNewYear'] as $index => $status)
                                    @if(strpos($index, 'status') !== false)
                                        <td class="text-center" >
                                            {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][' . $index . ']', $status) !!}
                                            {{ $status }}
                                            @if(!$status)
                                                <span class="opacity-0">@lang('demand_detail.remarks')</span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                            <tr>
                                <td class="text-center">@lang('demand_detail.remarks')</td>
                                <td class="text-center" colspan="{{ count($commissionInfo['mCorpNewYear']) }}">
                                    {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][note]', $commissionInfo['mCorpNewYear']['note'] ?? '') !!}
                                    {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][long_vacation_note]', $commissionInfo['mCorpNewYear']['long_vacation_note'] ?? '') !!}
                                    {{ $commissionInfo['mCorpNewYear']['note'] ?? ''}}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @if(isset($commissionInfo['corp_fee']))
        {!! Form::hidden('commissionInfo['. $key .'][corp_fee]', $commissionInfo['corp_fee'] ?? 0) !!}
    @endif

    {!! Form::hidden('commissionInfo['. $key .'][select_commission_unit_price]', $commissionInfo['select_commission_unit_price'] ?? '') !!}

    {!! Form::hidden('commissionInfo['. $key .'][mCorp][fax]', $commissionInfo['mCorp']['fax'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][mailaddress_pc]', $commissionInfo['mCorp']['mailaddress_pc'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][coordination_method]', $commissionInfo['mCorp']['coordination_method'] ?? 0) !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][contactable_time]', $commissionInfo['mCorp']['contactable_time'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][holiday]', $commissionInfo['mCorp']['holiday'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][commission_dial]', $commissionInfo['mCorp']['commission_dial'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][demand_id]', $commissionInfo['demand_id'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][id]', $commissionInfo['id'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][position]', $commissionInfo['position'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][affiliationInfo ][attention]', isset($commissionInfo['affiliationInfo']) ? $commissionInfo['affiliationInfo']['attention'] : '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][attention]', $commissionInfo['attention'] ?? '') !!}

    {!! Form::hidden('commissionInfo['. $key .'][mCorpCategory][order_fee]', $commissionInfo['mCorpCategory']['order_fee'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorpCategory][order_fee_unit]', $commissionInfo['mCorpCategory']['order_fee_unit'] ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorpCategory][note]', $commissionInfo['mCorpCategory']['note'] ?? '') !!}
</div>
