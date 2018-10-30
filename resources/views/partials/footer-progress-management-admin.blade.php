@php
$class = 'default';
$isSpec = false;
if (Route::current()->getName() == 'bill.moneyCorrespond') {
    $class = 'custom-1';
} else if (Route::current()->getName() == 'auction.proposal') {
    $class = 'custom-2';
} else {
    $isSpec = true;
}
@endphp
<footer class="clearfix {{ $class }}">
    <div class="container-fluid">
        @if($isSpec)
        <a class="d-block float-md-left" href="javascript:void(0)"><b>利用規約</b></a>
        <p class="float-md-right m-0">Copyright © 2014 Sharingtechnology All Rights Reserved.</p>
        @endif
    </div>
</footer>