@if ($paginator->hasPages())
    @if ($paginator->hasMorePages())
        <button type="submit" class="next btn btn--gradient-green remove-effect-btn col-sm-6 col-md-3 col-xl-2" name="next" value="{{ __('demand_detail.submit_boss') }}">{{ __('demand_detail.submit_boss') }}</button>
    @else
        <button type="submit" class="submitForm btn btn--gradient-green remove-effect-btn col-sm-6 col-md-3 col-xl-2" name="submitForm" value="{{ __('demand_detail.submit_boss') }}">{{ __('demand_detail.submit_boss') }}</button>
    @endif
@else
    <button type="submit" class="submitForm btn btn--gradient-green remove-effect-btn col-sm-6 col-md-3 col-xl-2" name="submitForm" value="{{ __('demand_detail.submit_boss') }}">{{ __('demand_detail.submit_boss') }}</button>
@endif
