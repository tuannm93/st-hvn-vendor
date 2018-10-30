{{-- Button link view --}}
<div class="form-category mb-4">
    <label class="form-category__label">{{ trans('affiliation_detail.federation_store_information_label') }}</label>
    <div class="form-category__body clearfix">
        <div class="row m-0 justify-content-end">
            <div class="col-12 col-md-4 col-lg-2 mb-2">
                <a href="javascript:void(0);" data-url="{{ route('affiliation.index') }}" data-session="{{ route('affiliation.set.session') }}" class="btn btn-default w-100 return-link">{{ trans('affiliation_detail.return_link') }}</a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 mb-2">
                <a href="{{ route('commission.index') }}" target="_blank" class="btn btn-default w-100">{{ trans('affiliation_detail.commission_link') }}</a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 mb-2 pr-lg-0">
                <a href="{{ route('demandlist.search') }}" target="_blank" class="btn btn-default w-100">{{ trans('affiliation_detail.demand_list_link') }}</a>
            </div>
        </div>
        <p class="float-right"><span class="text-danger">*</span> ({{ trans('affiliation_detail.required_input') }})</p>
    </div>
</div>
