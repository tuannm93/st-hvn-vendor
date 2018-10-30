<div class="form-table mb-4 commission-table">
    <div class="row m-0 bg-primary-lighter p-0">
        <div class="col-lg-3 px-0">
            <div class="form__label form__label--primary p-3 h-100 border-bottom">
                <label class="m-0">

                    <strong>@lang('demand_detail.supplier')<span class="max-index">{{ $key + 1 }}</span></strong>
                </label>
                <button
                        data-url_data="{{ route('ajax.demand.load_m_corp', [$commissionInfor->mCorpAndMCorpNewYear['id'], $demand->category_id]) }}" type="button" data-key="{{ $key + 1 }}"
                        class="btn btn-sm btn--gradient-default m-corps-detail">
                    @lang('demand_detail.information_reference')
                </button>
            </div>
        </div>
        <div class="col-lg-6 py-3">
            <div class="form-group w-100 mb-lg-0">
                {!! Form::text('commissionInfo['. $key .'][mCorp][corp_name]',
                    $commissionInfor->mCorpAndMCorpNewYear['corp_name'], ['class' => 'form-control', 'id' => 'corp_name' . $key]) !!}
            </div>
        </div>
        <div class="col-lg-3 py-3 text-right">
            @if (! empty($commissionInfor->id))
                @if ($commissionInfor->commission_type != 1 && !empty($commissionInfor->commit_flg) && $commissionInfor->commit_flg == 1)
                    <a target='_blank'
                        class="text--orange" href="{{ route('commission.detail', ['id' => $commissionInfor->id]) }}">詳細</a>
                @elseif($commissionInfor->commission_type == 1)
                    <a target='_blank'
                        class="text--orange" href="{{ route('commission.detail', ['id' => $commissionInfor->id]) }}">詳細</a>
                @endif
            @endif
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.procedure-dial')</label>
            <div class="col-lg-6 d-flex align-items-center px-3 py-2 form-table-cell">
                @if($commissionInfor->mCorpAndMCorpNewYear['commission_dial'])
                    <a href="{{ checkDevice().$commissionInfor->mCorpAndMCorpNewYear['commission_dial'] }}"
                        class="text--orange w-50 text--underline">
                        {{ $commissionInfor->mCorpAndMCorpNewYear['commission_dial'] }}
                    </a>
                @endif
                    <div class="text-right w-50">
                        <a href="{{ route('demandlist.search', ['id' => $commissionInfor->corp_id, 'bCheck' => 1]) }}"
                            target="_blank" class="btn btn--gradient-gray">
                            @lang('demand_detail.b_check')
                        </a>
                    </div>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" >
              <label for='del_flg{{ $key }}'>@lang('demand_detail.delete')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::checkbox('commissionInfo['. $key .'][del_flg]', 1, $commissionInfor->del_flg == 1, ['class' => 'custom-control-input', 'id' => 'del_flg'.$key]) !!}
                <label class="custom-control-label custome-label" for='del_flg{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">
              @lang('demand_detail.appointers')
            </label>
            <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                {!! Form::select('commissionInfo['. $key .'][appointers]', $userDropDownList, $commissionInfor->appointers, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" >
              <label for='first_commission{{ $key }}'>@lang('demand_detail.initial-check')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::checkbox('commissionInfo['. $key .'][first_commission]', 1, $commissionInfor->first_commission == 1, ['class' => 'custom-control-input', 'id' => 'first_commission' . $key]) !!}
                <label class="custom-control-label custome-label" for='first_commission{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.mail-order-sender')</label>
            <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                {!! Form::select('commissionInfo['. $key .'][commission_note_sender]', $userDropDownList, $commissionInfor->commission_note_sender, ['id' => 'commission_note_sender'.$key, 'class' => 'form-control now_date', 'data-url' => route('ajax.get.now.view'), 'data-key' => $key]) !!}
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" >
              <label for='unit_price_calc_exclude{{ $key }}'>@lang('demand_detail.not-covered-by-contract-price')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::hidden('commissionInfo[' . $key . '][corp_id]', $commissionInfor->corp_id, ['class' => 'corp_id', 'id' => 'CommissionInfo' . $key . 'CorpId']) !!}
                {!! Form::checkbox('commissionInfo['. $key .'][unit_price_calc_exclude]', 1, $commissionInfor->unit_price_calc_exclude == 1, ['class' => 'custom-control-input', 'id' => 'unit_price_calc_exclude'. $key]) !!}
                <label class="custom-control-label custome-label" for='unit_price_calc_exclude{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.date-time-of-agency-sent')</label>
            <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                {!! Form::text('commissionInfo['. $key .'][commission_note_send_datetime]', $commissionInfor->commission_note_send_datetime_format, ['id' => 'commission_note_send_datetime'.$key,'class' => 'form-control datetimepicker']) !!}
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" >
              <label for='commit_flg{{ $key }}'>@lang('demand_detail.confirm')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                    {!! Form::checkbox('commissionInfo['. $key .'][commit_flg]', 1, $commissionInfor->commit_flg == 1,
                    ['class' => 'commission_commit_flg custom-control-input chk-commit-flg', 'id' => 'commit_flg' . $key, 'data-id' => $key]) !!}
                    <label class="custom-control-label custome-label" for='commit_flg{{ $key }}'></label>
                <div class="demand-detail-custom-checkbox ml-auto">
                    <span>(</span>
                    {!! Form::hidden('demandInfo[calendar_flg]', 0, ['id' => 'calendar_flg_' . $key . '_']) !!}
                    {!! Form::checkbox('demandInfo[calendar_flg]', 1, $demand['calendar_flg'] == 1,
                    ['class' => 'custom-control-input calendar_check', 'id' => 'calendar_flg_' . $key]) !!}
                    <label class="custom-control-label demand-detail-label-custom" for="calendar_flg_{{ $key }}">@lang('demand_detail.calendar_flg_use'):</label>
                    <span class="demand-detail-close">)</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.no_mail')@lang('demand_detail.fax-transmission')</label>
            <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                <p class="mb-0">
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax]', $commissionInfor->send_mail_fax) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax_datetime]', $commissionInfor->send_mail_fax_datetime) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax_sender]', $commissionInfor->send_mail_fax_sender) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][send_mail_fax_othersend]', $commissionInfor->send_mail_fax_othersend) !!}
                    {!! Form::hidden('commissionInfo['. $key .'][demand_id]', $demand->id) !!}

                    @if($commissionInfor->send_mail_fax_othersend)
                        個別送信
                    @endif
                    @if($commissionInfor->send_mail_fax == 1)
                        送信済み　{{$commissionInfor->send_mail_fax_datetime}} <br>
                        {{ is_null($commissionInfor->send_mail_fax_sender) ? '' : $userDropDownList[$commissionInfor->send_mail_fax_sender] }}
                    @endif
                </p>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" >
              <label for='lost_flg{{ $key }}'>@lang('demand_detail.prior-to-ordering')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::checkbox('commissionInfo['. $key .'][lost_flg]', 1, $commissionInfor->lost_flg == 1, ['class' => 'custom-control-input', 'id' => 'lost_flg' . $key]) !!}
                <label class="custom-control-label custome-label" for='lost_flg{{ $key }}'></label>
            </div>
        </div>
    </div>
    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">
            {!! Form::hidden('commissionInfo['. $key .'][order_fee_unit]', $commissionInfor->order_fee_unit) !!}
            {!! Form::hidden('commissionInfo['. $key .'][complete_date]', $commissionInfor->complete_date) !!}
            {!! Form::hidden('commissionInfo['. $key .'][commission_status]', $commissionInfor->commission_status) !!}
            {{ $commissionInfor->order_fee_unit_label }}
            {!! Form::hidden('commissionInfo['. $key .'][commission_string]', $commissionInfor->order_fee_unit_label) !!}</label>
            <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                {!! Form::hidden('commissionInfo['. $key .'][commission_fee_rate]', $commissionInfor->commission_fee_rate) !!}
                {!! Form::hidden('commissionInfo['. $key .'][commission_type]', $commissionInfor->commission_type) !!}
                {!! Form::hidden('commissionInfo['. $key .'][id]', $commissionInfor->id) !!}
                <p class="get-fee-data mb-0">
                    {{ $commissionInfor->corp_commission_type_disp }}
                    {!! Form::hidden('commissionInfo['. $key .'][corp_commission_type_disp]', $commissionInfor->corp_commission_type_disp) !!}
                </p>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell" >
              <label for='corp_claim_flg{{ $key }}'>@lang('demand_detail.merchant-claim')</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 form-table-cell">
                {!! Form::checkbox('commissionInfo['. $key .'][corp_claim_flg]', 1, $commissionInfor->corp_claim_flg == 1, ['class' => 'custom-control-input', 'id' => 'corp_claim_flg' . $key]) !!}
                <label class="custom-control-label custome-label" for='corp_claim_flg{{ $key }}'></label>
            </div>
        </div>
    </div>

    @if (!empty($listStaff[$commissionInfor->id]))
        <div class="row mx-0">
            <div class="col-lg-6 row m-0 p-0">
                <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.name_staff')</label>
                <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                    <div class="form-group d-flex mb-lg-0">
                        <input type="hidden" name="commissionInfo[{{ $key }}][staff_name]"
                               value="{{ $listStaff[$commissionInfor->id]['user_name'] ?? '' }}"/>
                        <input type="hidden" class="staff_id" name="commissionInfo[{{ $key }}][id_staff]"
                               value="{{ $listStaff[$commissionInfor->id]['user_id'] ?? '' }}"/>
                        <input type="hidden" class="notification_status" name="commissionInfo[{{ $key }}][notification_status]"
                               value="{{$notificationStatus[$commissionInfor->id] ?? ''}}">
                        <p class="mb-0">{{ $listStaff[$commissionInfor->id]['user_name'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 row m-0 p-0">
                <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.phone_staff')</label>
                <div class="col-lg-6 d-flex align-items-center py-3 form-table-cell">
                    <div class="mr-sm-2">
                        <input class="custom-control-input" id="commissionInfo[{{ $key }}][staff_phone]"
                               name="commissionInfo[{{ $key }}][staff_phone]" type="hidden"
                               value={{  $listStaff[$commissionInfor->id]['staff_phone'] ?? '' }} >
                        <a href="{{ checkDevice().($listStaff[$commissionInfor->id]['staff_phone'] ?? '')}}"
                           class="text--orange phone"
                           for="">{{  $listStaff[$commissionInfor->id]['staff_phone'] ?? '' }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mx-0">
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.unit-price-rank')</label>
            <div class="col-lg-6 d-flex align-items-center py-2 form-table-cell">
                {!! Form::hidden('commissionInfo['. $key .'][select_commission_unit_price_rank]', $commissionInfor->select_commission_unit_price_rank) !!}
                <p class="mb-0">{{ $commissionInfor->select_commission_unit_price_rank }}@lang('demand_detail.unit-price-per-contract')   {{ yenFormat2($commissionInfor->select_commission_unit_price) }}</p>
            </div>
        </div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 form-table-cell"></label>
            <div class="col-lg-6 form-table-cell"></div>
        </div>
    </div>

    <div class="row mx-0 form-table-cell" style="display: {{ empty($commissionInfor->commission_type) ? 'none' : 'display' }};">
        <div class="col-lg-6 row m-0 p-0">&nbsp;</div>
        <div class="col-lg-6 row m-0 p-0">
            <label class="col-lg-6 d-flex align-items-center form__label--white-light font-weight-bold p-3 mb-0 ">
              <label for='introduction_not{{$key}}'>紹介不可</label>
            </label>
            <div class="col-lg-6 d-flex align-items-center custom-control custom-checkbox p-3 ">
                {!! Form::checkbox('commissionInfo['. $key .'][introduction_not]', 1, $commissionInfor->introduction_not == 1, ['class' => 'custom-control-input', 'id' => 'introduction_not' . $key, 'disabled' => true]) !!}
                <label class="custom-control-label custome-label" for="introduction_not{{$key}}"></label>
            </div>
        </div>

    </div>



    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.notes')</label>
            <div class="col-lg-9 d-flex align-items-center px-3 py-2 form-table-cell">
                {!! $commissionInfor->mCorpAndMCorpNewYear && $commissionInfor->mCorpAndMCorpNewYear->affiliationInfo ? nl2br($commissionInfor->mCorpAndMCorpNewYear->affiliationInfo->attention) : ''  !!}
            </div>
        </div>

    </div>

    <div class="row mx-0">
        <div class="col-12 row m-0 p-0">
            <div class="col-12 col-lg-3 px-0 form__label form__label--white-light d-flex align-items-center ">
                <label class="m-0 pl-3">
                    <strong>@lang('demand_detail.long-term-holidays-situation')</strong>
                </label>
            </div>
            <div class="col-12 col-lg-9 py-2 form-table-cell">
                <div class="table-responsive">
                    <table class="table table-bordered table-list mb-0">
                        <thead>
                        @if(!empty($vacationLabels))
                            <tr>
                                @foreach($vacationLabels as $k => $label)
                                    {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][label_0' . $k . ']', $label) !!}

                                    <th class="p-2 align-middle">{{ $label }}</th>

                                @endforeach
                            </tr>
                        @endif
                        </thead>
                        <tbody>
                        <tr>
                            @foreach($vacationLabels as $index => $label)
                                {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][status_0' . $index . ']', '') !!}
                                <td class="p-2 align-middle text-center">
                                    @if($commissionInfor->mCorpAndMCorpNewYear && $commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear)
                                        {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][status_0' . $index . ']',
                                            $commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear->{'status_0' . $index}) !!}

                                        {{ $commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear->{'status_0' . $index} }}
                                        @if(!$commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear->{'status_0' . $index})
                                            <span class="opacity-0">@lang('demand_detail.remarks')</span>
                                        @endif
                                    @else
                                        <span class="opacity-0">@lang('demand_detail.remarks')</span>
                                    @endif
                                </td>

                            @endforeach
                        </tr>
                        <tr>
                            <td class="p-2 align-middle text-center">@lang('demand_detail.remarks')</td>
                            <td colspan="{{ count($vacationLabels) }}" class="p-2 align-middle">
                                {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][note]', '') !!}
                                @if($commissionInfor->mCorpAndMCorpNewYear && $commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear)
                                    {!! Form::hidden('commissionInfo['. $key .'][mCorpNewYear][note]', $commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear->note) !!}
                                    {{ $commissionInfor->mCorpAndMCorpNewYear->mCorpNewYear->note }}
                                @endif
                            </td>

                        </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
    {!! Form::hidden('commissionInfo['. $key .'][id]', $commissionInfor->id, ['id' => 'CommissionInfo' . $key . 'Id']) !!}
    {!! Form::hidden('commissionInfo['. $key .'][select_commission_unit_price]', $commissionInfor->select_commission_unit_price) !!}
    {!! Form::hidden('commissionInfo['. $key .'][introduction_not]', $commissionInfor->introduction_not) !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][fax]', $commissionInfor->mCorpAndMCorpNewYear->fax ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][mailaddress_pc]', $commissionInfor->mCorpAndMCorpNewYear->mailaddress_pc ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][coordination_method]', $commissionInfor->mCorpAndMCorpNewYear->coordination_method ?? 0) !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][contactable_time]', $commissionInfor->mCorpAndMCorpNewYear->contactable_time ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][holiday]', $commissionInfor->mCorpAndMCorpNewYear->holiday1 ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorp][commission_dial]', $commissionInfor->mCorpAndMCorpNewYear->commission_dial ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][position]', $commissionInfor->position ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][auctionInfo][attention]', $demand->affiliationInfo ? $demand->affiliationInfo->attention : '') !!}

    {!! Form::hidden('commissionInfo['. $key .'][mCorpCategory][order_fee]', $commissionInfor->mCorpAndMCorpNewYear->m_corps_categories->order_fee ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorpCategory][order_fee_unit]', $commissionInfor->mCorpAndMCorpNewYear->m_corps_categories->order_fee_unit ?? '') !!}
    {!! Form::hidden('commissionInfo['. $key .'][mCorpCategory][note]', $commissionInfor->mCorpAndMCorpNewYear->m_corps_categories->note ?? '') !!}
</div>
