<h3><strong>{{$currentStepName}}</strong></h3>
<div class="progress_step border p-3">
    <ul class="d-flex flex-column flex-sm-row justify-content-between list-unstyled pl-4 progressbar progressbar--lg">
        <li class="w-sm-30 mb-1 @if($currentStep == 1 || $currentStep == 2
        || $currentStep == 3 || $currentStep == 4 || $currentStep== 5) active @else past @endif">
            <strong>@lang('progress.content_agreement')</strong></li>
        <li class="w-sm-30 mb-1 @if($currentStep == 7) active @elseif($currentStep > 7) past @endif">
            <strong>@lang('progress.confirm_contract_contents')</strong></li>
        <li class="w-sm-30 mb-1 @if($currentStep == 8) active @endif">
            <strong>@lang('progress.conclusion_of_contract_completed')</strong></li>
    </ul>
    @if($currentStep == 1 || $currentStep == 2 || $currentStep == 3 ||  $currentStep == 4 ||$currentStep == 5)
        <ul class="d-flex flex-column flex-sm-row list-unstyled mb-0 pl-4 progressbar progressbar--lg">
            <li class="w-sm-15 custom-height-li py-3 mb-1 @if($currentStep == 1) active @elseif($currentStep > 1) past @endif">
                <strong>@lang('progress.agreement_of_terms_conditions')</strong></li>
            <li class="w-sm-15 custom-height-li py-3 mb-1 @if($currentStep == 2) active @elseif($currentStep > 2) past @endif">
                <strong>@lang('progress.basic_information')</strong></li>
            <li class="w-sm-15 custom-height-li py-3 mb-1 @if($currentStep == 3 || $currentStep == 4) active @elseif($currentStep > 4) past @endif">
                <strong>@lang('progress.genre_area_setting')</strong></li>
            <li class="w-sm-15 custom-height-li py-3 mb-1 @if($currentStep == 5) active @elseif($currentStep > 5) past @endif">
                <strong>@lang('progress.required_documents')</strong></li>
        </ul>
    @endif
</div>

@if($currentStep == 2)
    <h3 class="my-3">@lang('agreement_system.contract_contents')</h3>
@endif
<div class="row header-field my-4">
    <div class="col-sm-6 col-md-4">
        <strong>@lang('agreement_system.name_company') {{$officialCorpName}}</strong>
    </div>
    <div class="col-sm-6 col-lg-3">
        <strong>
            {{ __('affiliation.business_id') }}: {{$corpId}}
        </strong>
    </div>
</div>
