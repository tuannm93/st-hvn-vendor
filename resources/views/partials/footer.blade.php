@php
$class = 'default';
$isCustom = false;
switch (Route::current()->getName()) {
    case 'bill.moneyCorrespond':
    case 'auction.support':
        $class = 'custom-1';
        $isCustom = true;
        break;
    case 'auction.proposal':
        $class = 'custom-2';
        $isCustom = true;
        break;
    default:
        $class = 'default';
        $isCustom = false;
        break;
}
@endphp
<footer class="clearfix {{ $class }}" id="footer_default">
    <div class="container">
        @if(!$isCustom)
        <a class="d-block float-md-left" href="{{ route('guideline.index') }}" target="_blank"><b>{{ __('common.guideline')}}</b></a>
        <p class="float-md-right m-0">Copyright © 2014 Sharingtechnology All Rights Reserved.</p>
        @endif
    </div>
</footer>
