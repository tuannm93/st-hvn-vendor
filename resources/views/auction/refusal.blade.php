<!-- Modal -->
<div class="modal fade" id="refusalModal" tabindex="-1" role="dialog" aria-labelledby="refusalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close p-3 close-refusalModal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="refusal-progress-block">
                    <div class="refusal-progress"></div>
                </div>
                <div class="auction-refusal pt-2">

                    <div class="support-limit d-none">
                        <div class="text-center">{{ trans('auction_refusal.text_support_limit_1') }}</div>
                        <div class="text-center">{{ trans('auction_refusal.text_support_limit_2') }}</div>
                        <div class="text-center">{{ trans('auction_refusal.text_support_limit_3') }}</div>
                        <div class="text-center pt-3">
                            <button type="button" data-dismiss="modal" class="btn btn--gradient-green col-5 col-sm-4 col-md-2">{{ trans('auction_refusal.close_button') }}</button>
                        </div>
                    </div>

                    <div class="deal-already d-none">
                        <div class="deal-already-not-support d-none">
                            <div class="text-center">{{ trans('auction_refusal.text_project_has_been_lost') }}</div>
                            <div class="text-center pt-3">
                                <button type="button" data-dismiss="modal" class="btn btn--gradient-green col-5 col-sm-4 col-md-2">{{ trans('auction_refusal.close_button') }}</button>
                            </div>
                        </div>

                        <div class="deal-already-support d-none">
                            <div class="deal-already__form col-12 mx-auto">
                                <form method="post" novalidate id="form-auction-refusal">
                                    {{ csrf_field() }}
                                    <div class="text-danger font-weight-bold">{{ trans('auction_refusal.text_reason_can_not_support') }}</div>
                                    <div>{{ trans('auction_refusal.label_calendar_is_not_available') }}</div>
                                    <div class="form-group pl-4 row">
                                        <small class="col-12"><span class="orange">{{ trans('common.square') }}</span>&nbsp;{{ trans('auction_refusal.label_date_and_time') }}</small>
                                        <input type="text" data-error-container="#estimable_time_from_feedback" class="ml-3 form-control datetimepicker col-10 col-sm-10 col-md-5" name="estimable_time_from">
                                        <label class="col-1 col-form-label">{{trans('common.wavy_seal')}}</label>
                                        <div id="estimable_time_from_feedback" class="col-12"></div>
                                    </div>
                                    <div>{{ trans('auction_refusal.text_time_request_is_not_available') }}</div>
                                    <div class="form-group pl-4 row">
                                        <small class="col-12"><span class="orange">{{ trans('common.square') }}</span> {{ trans('auction_refusal.label_date_and_time') }}</small>
                                        <input data-error-container="#contactable_time_from_feedback" type="text" class="ml-3 form-control datetimepicker col-10 col-sm-10 col-md-5" name="contactable_time_from">
                                        <label class="col-1 col-form-label">{{trans('common.wavy_seal')}}</label>
                                        <div id="contactable_time_from_feedback" class="col-12"></div>
                                        <small class="col-12 pt-1">{{trans('common.asterisk')}}{{ trans('auction_refusal.text_invite_notice') }}</small>
                                    </div>
                                    <div>{{ trans('auction_refusal.label_other_reason') }}</div>
                                    <div class="form-group pl-4">
                                        <textarea maxlength="1000" rows="7" class="form-control" name="other_contents"></textarea>
                                    </div>
                                    <input type="hidden" value="" id="refusal_modified" name="modified">
                                    <div class="text-center pt-3">
                                        <button type="submit" class="btn btn--gradient-green col-5 col-sm-4 col-md-3">{{ trans('auction_refusal.button_submit') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="support-already d-none">
                        <div class="text-center text-danger font-weight-bold">{{ trans('auction_refusal.text_support_already_1') }}</div>
                        <div class="text-center"><span id="commissionData"></span>{{ trans('auction_refusal.text_support_already_2') }}</div>
                        <div class="text-center red">{{ trans('auction_refusal.text_support_already_3') }}</div>
                        <div class="text-center pt-3">
                            <button type="button" data-dismiss="modal" class="btn btn--gradient-gray col-5 col-sm-4 col-md-2">{{ trans('auction_refusal.close_button') }}</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
