@if((isset($firstViewNumberNoticeinfoUnread) && $firstViewNumberNoticeinfoUnread) || !empty($unAnswerCount))
    @php
        if(!$isForceGoToNoticePage) {
            $preventCloseModalBox = '';
        } else {
            $preventCloseModalBox = 'data-backdrop=static data-keyboard=false';
        }
    @endphp
    <div class="modal modal-global" id="globalUnreadNoticeModal" tabindex="-1" role="dialog" {{ $preventCloseModalBox }}>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('global_modal.global_unread_notice_title') }}</h5>
                    @if(!$isForceGoToNoticePage)
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    @endif
                </div>
                <div class="modal-body p-4">
                    <p>
                        {{ __('global_modal.global_unread_notice_body') }}<br>
                        @if($isForceGoToNoticePage)
                        <span class="text-danger">{{ __('global_modal.global_unread_notice_body_important') }}</span>
                        @endif
                    </p>
                </div>
                <div class="modal-footer justify-content-center p-4">
                    @if(!$isForceGoToNoticePage)
                        <button type="button" class="btn btn--gradient-default border w-45" data-dismiss="modal">
                            {{ __('global_modal.global_modal_later_btn') }}
                        </button>
                        <a class="btn btn--gradient-green w-45 w-45 text-white"
                           href="{{ action('NoticeInfo\NoticeInfoController@index') }}">
                            {{ __('global_modal.global_unread_notice_check_btn') }}
                        </a>
                    @else
                        <a class="btn btn--gradient-green w-75 text-white"
                           href="{{ action('NoticeInfo\NoticeInfoController@index') }}">
                            {{ __('global_modal.global_unread_notice_check_btn') }}
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($isAgreementDialogShow) && $isAgreementDialogShow)
    <div class="modal modal-global" id="globalAgreementConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('global_modal.global_agreement_confirm_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p>
                        {!!  trans('global_modal.global_agreement_confirm_body')  !!}
                    </p>
                </div>
                <div class="modal-footer justify-content-center p-4">
                    @if($haveAgreementCancel)
                        <button type="button" class="btn btn--gradient-default border w-45" data-dismiss="modal">
                            {{ __('global_modal.global_modal_later_btn') }}
                        </button>
                        <a class="btn btn--gradient-green w-45 text-white" href="{{ action('Auth\AuthInfosController@agreementLink') }}">
                            {{ __('global_modal.global_agreement_confirm_next_btn') }}
                        </a>
                    @else
                        <a class="btn btn--gradient-green w-50 text-white" href="{{ action('Auth\AuthInfosController@agreementLink') }}">
                            {{ __('global_modal.global_agreement_confirm_next_btn') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($isProgDialogShow) && $isProgDialogShow && !Request::is('progress_management/*'))
    <div class="modal modal-global" id="globalProgDialogModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('global_modal.global_prog_dialog_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p>
                        {!!  trans('global_modal.global_prog_dialog_body')  !!}
                    </p>
                </div>
                <div class="modal-footer justify-content-center p-4">
                    <button type="button" class="btn btn--gradient-default border w-45" data-dismiss="modal">
                        {{ __('global_modal.global_modal_later_btn') }}
                    </button>
                    <a class="btn btn--gradient-green w-45 text-white"
                       href="{{ route('get.progress_management.demand_detail', ['progImportFileId' => $importFileId]) }}">
                        {{ __('global_modal.global_agreement_confirm_next_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@if(!empty($antisocialFollowId))
    <div class="modal modal-global" id="globalAntisocialFollowDialogModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('global_modal.global_antisocial_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    {!! trans('global_modal.global_antisocial_body') !!}
                </div>
                <div class="modal-footer justify-content-center p-4">
                    <button type="button" class="btn btn--gradient-default border w-45" data-dismiss="modal">
                        {{ __('global_modal.global_antisocial_close_btn') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(isset($firstViewCreditAlert) && $firstViewCreditAlert)
    <div class="modal modal-global" id="globalCreditAlertDialogModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if(isset($creditWarningData))
                            {!!  __('global_modal.global_credit_warning_title', ['credit' => number_format($creditWarningData['limit'] - $creditWarningData['use'])])  !!}
                        @elseif(isset($creditDangerData))
                            {!!  __('global_modal.global_credit_danger_title') !!}
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    @if(isset($creditWarningData))
                        {!!  __('global_modal.global_credit_warning_body', ['credit' => number_format($creditWarningData['limit'] - $creditWarningData['use'])])  !!}
                    @elseif(isset($creditDangerData))
                        {!!  __('global_modal.global_credit_danger_body') !!}
                    @endif
                </div>
                <div class="modal-footer justify-content-center p-4">
                    <button type="button" class="btn btn--gradient-default border w-45" data-dismiss="modal">
                        {{ __('global_modal.global_credit_close_btn') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
