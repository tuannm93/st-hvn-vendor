@if ($paginator->hasPages())
    @if (!$paginator->onFirstPage())
        <button type="submit" class="previous btn btn--gradient-gray remove-effect-btn col-sm-6 col-md-3 col-xl-2 mb-2 mb-md-0" name="previous" value="{{ __('demand_detail.back_button') }}">{{ __('demand_detail.back_button') }}</button>
    @endif
@endif
