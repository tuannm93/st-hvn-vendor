@php
use App\Services\AffiliationService;
@endphp
@extends('layouts.app')
@section('content')
<div class="affiliation-agreement mt-3 pt-3">
    @if(!isset($corpData))
        <p class="box__mess box--error mb-0">@lang('agreement.not_found_item')</p>
    @else
    <div class="row header-field">
        <div class="col-sm-6 col-lg-auto">
            <strong>@lang('agreement.trade_name')：</strong>
            <a href="{{ route('affiliation.detail.edit', ['id' => $corpData->id]) }}" class="text--orange" target="_blank">{{ isset($corpData->official_corp_name) ? $corpData->official_corp_name : '' }}</a>
        </div>
        <div class="col-sm-6 col-lg-auto">
            <strong id="corp-id" data-corp-id="{{ isset($corpData->id) ? $corpData->id : '' }}">@lang('agreement.company_id')：{{ isset($corpData->id) ? $corpData->id : '' }}</strong>
        </div>
    </div>
    @if ($errors->any())
        <div class="box__mess box--error mt-3">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif
    @foreach (['success', 'error'] as $msg)
        @if(Session::has('box--' . $msg))
            <p class="box__mess box--{{ $msg }} mb-0 mt-3">{{ Session::get('box--' . $msg) }}</p>
        @endif
    @endforeach
    @if(Session::has('error_file'))
        <p class="box__mess box--error mb-0 mt-3">{{ Session::get('error_file') }}</p>
    @endif
    <div class="form-category mt-5">
        <label class="form-category__label">@lang('agreement.contract_status')</label>
    </div>
    @if(!$isRoleAffiliation)
        {!! Form::open(['url' => route('affiliation.agreement.update', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : '']), 'id' => 'update-corp-agreement-form']) !!}
        {!! Form::hidden('ajax-flg', 1); !!}
    @endif
    @if($isRoleAffiliation)
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.web_convention')</label>
            <div class="col-sm-8">
                {!! Form::text('', (isset($corpAgreement->agreement_flag) && $corpAgreement->agreement_flag) ? __('agreement.contracted') : __('agreement.not_contracted'), ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.category')</label>
            <div class="col-sm-8">
                @php
                if(isset($corpData->corp_kind)) {
                    if($corpData->corp_kind == "Corp") {
                        $category = __('agreement.corporate');
                    }
                    if($corpData->corp_kind == "Person") {
                        $category = __('agreement.personal');
                    }
                }
                @endphp
                {!! Form::text('', isset($category) ? $category : '', ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.approved')</label>
            <div class="col-sm-8">
                @if(isset($corpAgreement->status) &&
                    ($corpAgreement->status == 'Application' ||
                    $corpAgreement->status == 'Complete'))
                    {!! Form::text('', !empty($corpAgreement->acceptation_date) ? __('agreement.approved') : __('agreement.unapproved'), ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
                @endif
            </div>
        </div>
    @else
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.contract_id')</label>
            <div class="col-sm-8">
                {!! Form::text('', isset($corpAgreement->id) ? $corpAgreement->id : '', ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.application_type')</label>
            <div class="col-sm-8">
                {!! Form::text('', isset($corpAgreement->kind) ? $corpAgreement->kind : '', ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.web_convention')</label>
            <div class="col-sm-8">
                <div class="form-check form-check-inline pt-3">
                    <label class="form-check-label">
                        {!! Form::radio("CorpAgreement[agreement_flag]", '0', ((isset($corpAgreement->agreement_flag) && !$corpAgreement->agreement_flag) || !isset($corpAgreement->agreement_flag)) ? true : false, ['class' => 'form-check-input']) !!}@lang('agreement.not_contracted')
                    </label>
                </div>
                <div class="form-check form-check-inline pt-3">
                    <label class="form-check-label">
                        {!! Form::radio('CorpAgreement[agreement_flag]', '1', (isset($corpAgreement->agreement_flag) && $corpAgreement->agreement_flag) ? true : false, ['class' => 'form-check-input']) !!}@lang('agreement.contracted')
                    </label>
                </div>
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.category')</label>
            <div class="col-sm-8">
                @php
                if(isset($corpData->corp_kind)) {
                    if($corpData->corp_kind == "Corp") {
                        $category = __('agreement.corporate');
                    }
                    if($corpData->corp_kind == "Person") {
                        $category = __('agreement.personal');
                    }
                }
                @endphp
                {!! Form::text('', isset($category) ? $category : '', ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
                {!! Form::hidden('CorpAgreement[corp_kind]', isset($corpData->corp_kind) ? $corpData->corp_kind : ''); !!}
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.approved')</label>
            <div class="col-sm-8">
                @if(isset($corpAgreement->status) &&
                    ($corpAgreement->status == 'Application' ||
                    $corpAgreement->status == 'Complete' ||
                    $corpAgreement->kind == 'FAX'))
                    <div class="form-check form-check-inline pt-3">
                        <label class="form-check-label">
                        {!! Form::checkbox('CorpAgreement[acceptation]', 1, isset($corpAgreement->acceptation_date) ? true : false, $disableFlg ? ['disabled' => 'disabled', 'class' => 'form-check-input'] : ['class' => 'form-check-input']); !!}@lang('agreement.approve')
                        </label>
                    </div>
                    <p>@lang('agreement.updated_date_and_time'): {{ isset($corpAgreement->acceptation_date) ? dateTimeFormat($corpAgreement->acceptation_date, 'Y/m/d H:i') : ''}}</p>
                    <p class="pb-3">@lang('agreement.updater'): {{ isset($corpAgreement->acceptation_user_id) ? $corpAgreement->acceptation_user_id : ''}}</p>
                @endif
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.contract_update_flag')</label>
            <div class="col-sm-8">
                @php
                    $arraySelect = [
                        0 => __('agreement.not_contracted'),
                        1 => __('agreement.contract_completion'),
                        2 => __('agreement.contract_un_renewal'),
                        3 => __('agreement.unupdated_STOP'),
                    ];
                    if ($disableFlg) {
                        $arrayOption = [
                            'class' => 'p-1 mt-3',
                            'disabled' => 'disabled'
                        ];
                    } else {
                        $arrayOption = [
                            'class' => 'p-1 mt-3'
                        ];
                    }
                @endphp
                {!! Form::select('MCorp[commission_accept_flg]', $arraySelect, $corpData->commission_accept_flg, $arrayOption); !!}
                <p>@lang('agreement.updated_date_and_time'): {{ isset($corpData->commission_accept_date) ? dateTimeFormat($corpData->commission_accept_date, 'Y/m/d H:i') : '' }}</p>
                <p class="pb-3">@lang('agreement.updater'): {{ isset($corpData->commission_accept_user_id) ? $corpData->commission_accept_user_id : '' }}</p>
            </div>
        </div>
        @if($corpData->last_antisocial_check != 'None')
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.anti_company_check')</label>
            <div class="col-sm-8">
                <p class="pt-3">@lang('agreement.latest_check_contents'): {{ $antisocialCheck[$corpData->last_antisocial_check] }}</p>
                <p>@lang('agreement.recent_updated_date_and_time'): {{ isset($lastAntisocialCheck->date) ? dateTimeFormat($lastAntisocialCheck->date, 'Y/m/d H:i') : '' }}</p>
                <p class="pb-3">@lang('agreement.recent_updater'): {{ isset($lastAntisocialCheck->created_user) ? $lastAntisocialCheck->created_user : '' }}</p>
            </div>
        </div>
        @endif
        @if(!empty($corpAgreement->status) && $corpAgreement->status == 'Application')
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.contract_redemption')</label>
            <div class="col-sm-8">
                <div class="form-check form-check-inline pt-3">
                    <label class="form-check-label">
                    {!! Form::checkbox('agreement_remand_flg', 1, false, ['class' => 'form-check-input']); !!}@lang('agreement.pass_back')
                    </label>
                </div>
            </div>
        </div>
        @endif
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.contract_state')</label>
            <div class="col-sm-8">
                {!! Form::text('', !empty($corpAgreement->status) ? $statusMsg[$corpAgreement->status] : '', ['class' => 'form-control-plaintext py-1 py-sm-3', 'readonly' => true]); !!}
            </div>
        </div>
        <div class="row border-bottom mb-0 mx-0">
            <label class="col-sm-2 col-form-label font-weight-bold bg-label py-1 py-sm-3">@lang('agreement.contract_agreement')</label>
            <div class="col-sm-8">
                <div class="form-check form-check-inline pt-3">
                    <label class="form-check-label">
                    {!! Form::checkbox('', '', !empty($corpAgreement->accept_check), ['disabled' => 'disabled', 'class' => 'form-check-input']); !!}
                    </label>
                </div>
            </div>
        </div>
    @endif
    @if(!$isRoleAffiliation)
    <div class="mt-4">
        <div class="row">
            <div class="col-sm-6 text-center text-sm-right mb-2">
                @php
                    $urlPreview = route('affiliation.agreement.preview', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : '']);
                @endphp
                {!! Form::button(__('agreement.application_confirmation'), ['class' => 'btn btn--gradient-orange remove-effect-btn col-sm-5', 'onclick' => "javascript:subWinPreview('$urlPreview')"]) !!}
            </div>
            <div class="col-sm-6 text-center text-sm-left mb-2">
                {!! Form::button(__('agreement.update'), ['id' => 'update-corp-agreement', 'class' => 'btn btn--gradient-green remove-effect-btn col-sm-5']); !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @endif
    @if(!$isRoleAffiliation)
    <div class="form-category mt-5">
        <label class="form-category__label">@lang('agreement.contract_history')</label>
    </div>
    <div class="custom-scroll-x custom-scroll-x-1">
        <table class="table custom-border add-pseudo-scroll-bar-1">
            <thead>
                <tr class="text-center bg-yellow-light">
                    <th class="fix-w-100 align-middle p-1">@lang('agreement.contract_id')</th>
                    <th class="fix-w-100 align-middle p-1">@lang('agreement.status')</th>
                    <th class="fix-w-100 align-middle p-1">@lang('agreement.approval_date')</th>
                    <th class="fix-w-100 align-middle p-1">@lang('agreement.approver')</th>
                    <th class="fix-w-50 align-middle p-1">@lang('agreement.details')</th>
                </tr>
            </thead>
            <tbody>
            @foreach($corpAgreementList as $item)
                <tr>
                    <td class="text-center align-middle p-1">{{ $item->id }}</td>
                    <td class="fix-w-100 align-middle p-1">{{ $statusMsg[$item->status] ? $statusMsg[$item->status] : '-' }}</td>
                    <td class="fix-w-100 align-middle text-center p-1">{{ $item->acceptation_date ? $item->acceptation_date : '-' }}</td>
                    <td class="fix-w-100 align-middle p-1">{{ $item->acceptation_user_id ? $item->acceptation_user_id : '-' }}</td>
                    <td class="fix-w-50 align-middle p-1">
                        <a href="{{ route('affiliation.agreement.index', ['corpId' => $corpData->id, 'agreementId' => $item->id]) }}" class="highlight-link">@lang('agreement.details')</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pseudo-scroll-bar-1" data-display="false">
        <div class="scroll-bar-1"></div>
    </div>
    @if($corpAgreement && (empty($corpAgreementCnt) || $corpAgreementCnt == 0))
    {!! Form::open(['url' => route('affiliation.agreement.update.reconfirmation', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : '']), 'id' => 'update-reconfirmation-form', 'class' => 'ml-4 form-1', 'onsubmit' => 'return confirm("' . __('agreement.contract_confirmation_popup') .'")']) !!}
        <div class="agreement-button text-center mt-4">
            {!! Form::submit(__('agreement.confirm_contract'), ['id' => 'update-reconfirmation', 'class' => 'btn btn--gradient-orange remove-effect-btn col-sm-3']); !!}
        </div>
    {!! Form::close() !!}
    @endif
    @endif
    {!! Form::open(['url' => route('affiliation.agreement.upload.file', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : '']), 'id' => 'upload-file-form', 'enctype' => 'multipart/form-data', 'class' => 'form-2']) !!}
    <div class="form-category mt-5">
        <label class="form-category__label">@lang('agreement.certificate_confirmation')</label>
    </div>
    <div class="special-title">
        <strong class="ml-1">@lang('agreement.required_documents')</strong>
    </div>
    @if(isset($agreementAttachedFileCert[0]['id']))
    <p class="mt-2 mb-4">@lang('agreement.copy_of_a_registry_certificate')</p>
    <div class="custom-scroll-x custom-scroll-x-2">
        <table class="table custom-border add-pseudo-scroll-bar-2">
            <thead>
                <tr class="text-center bg-yellow-light">
                    <th class="fix-w-100 align-middle p-1">@lang('agreement.file')</th>
                    <th class="fix-w-150 align-middle p-1">@lang('agreement.upload_date_and_time')</th>
                    @if ($isRoleSystem)
                    <th class="align-middle p-1">
                        @lang('agreement.delete')
                        {!! Form::hidden('file_id', '', ['id' => 'file-id']); !!}
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($agreementAttachedFileCert as $file)
                <tr>
                    <td class="fix-w-100 align-middle p-1 text-wrap">
                        @if (AffiliationService::checkFileExists($corpData->id, $file))
                        <a class="highlight-link highlight-hover" href="{{ route('affiliation.agreement.file.download', ['fileId' => $file->id]) }}">{{ $file->name }}</a>
                        @else
                        {{ $file->name }}
                        @endif
                    </td>
                    <td class="fix-w-150 align-middle text-center p-1 text-wrap">{{ $file->create_date }}</td>
                    @if($isRoleSystem)
                    <td class="align-middle text-center p-1 text-wrap">
                        {!! Form::submit(__('agreement.delete'), ['class' => 'btn btn--gradient-gray remove-effect-btn btn-delete-file', 'data-id' => $file->id]); !!}
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pseudo-scroll-bar-2" data-display="false">
        <div class="scroll-bar-2"></div>
    </div>
    @else
    <p class="mt-1">@lang('agreement.no_file_uploaded')</p>
    @endif
    <div class="upload-file mt-3">
        <p class="mb-2">{!! Form::file('upload_file_path[]', ['class' => 'upload_file_path', 'size' => 50, 'onchange' => 'enable_upload_btn();']); !!}</p>
        <p class="mb-2">{!! Form::file('upload_file_path[]', ['class' => 'upload_file_path', 'size' => 50, 'onchange' => 'enable_upload_btn();']); !!}</p>
        <p class="mb-2">{!! Form::file('upload_file_path[]', ['class' => 'upload_file_path', 'size' => 50, 'onchange' => 'enable_upload_btn();']); !!}</p>
        <p class="mb-2">{!! Form::file('upload_file_path[]', ['class' => 'upload_file_path', 'size' => 50, 'onchange' => 'enable_upload_btn();']); !!}</p>
        <p class="mb-2">{!! Form::file('upload_file_path[]', ['class' => 'upload_file_path', 'size' => 50, 'onchange' => 'enable_upload_btn();']); !!}</p>
        <p class="notice font-weight-bold">@lang('agreement.please_select_the_file_to_be_uploaded')</p>
    </div>
    <div class="guidance mt-5">
        <p>@lang('agreement.required_file_type')</p>
        <p>@lang('agreement.the_maximum_file_size_is_20_MB')</p>
    </div>
    <div class="agreement-button text-center mt-4">
        {!! Form::submit(__('agreement.upload'), ['id' => 'upload-file', 'class' => 'btn btn--gradient-orange remove-effect-btn col-sm-3 col-lg-2']); !!}
        <p class="notice font-weight-bold mt-1 mb-0">@lang('agreement.click_upload')</p>
    </div>
    {!! Form::close() !!}
    <div class="form-category mt-5">
        <label class="form-category__label">@lang('agreement.confirmation_of_document_of_clause')</label>
    </div>
    <div class="provisions">
        <div class="form-category pt-4">
            <label class="form-category__label pl-1">@lang('agreement.agreement_of_contract')</label>
        </div>
        <div class="agreement-provisions custome-scrollbar px-5">
        @if(!empty($agreementProvisions))
                {!! $agreementProvisions !!}
        @endif
        </div>
    </div>
    @if($isReportDownloadUrl)
    <div class="agreement-button row my-5">
        @if(!empty($corpAgreement->customize_agreement))
        <div class="col-sm-6 text-center text-sm-right mb-2">
            {!! Form::open(['url' => route('affiliation.agreement.report.download', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : '']), 'target' => '_blank']) !!}
            {!! Form::submit(__('agreement.contract_download'), ['class' => 'btn btn--gradient-green remove-effect-btn col-sm-5']); !!}
            {!! Form::close() !!}
        </div>
        <div class="col-sm-6 text-center text-sm-left">
            <a href="{{ route('affiliation.agreement.terms.download', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : ''])}}" class="btn btn--gradient-orange remove-effect-btn col-sm-5" target="_blank">@lang('agreement.terms_confirmation_PDF_download')</a>
        </div>
        @else
        <div class="col text-center">
            {!! Form::open(['url' => route('affiliation.agreement.report.download', ['corpId' => $corpData->id, 'agreementId' => isset($corpAgreement->id) ? $corpAgreement->id : '']), 'target' => '_blank']) !!}
            {!! Form::submit(__('agreement.contract_download'), ['class' => 'btn btn--gradient-green remove-effect-btn col-5']); !!}
            {!! Form::close() !!}
        </div>
        @endif
    </div>
    @endif
    @endif
    @include('affiliation.components.check_auto_commission_modal')
</div>

@endsection
@section('script')
    <script>
        var urlCheckAutoCommission = '{{ route('affiliation.agreement.check-auto-commission') }}';
    </script>
    <script src="{{ mix('js/pages/affiliation.agreement.index.js') }}"></script>
@endsection
