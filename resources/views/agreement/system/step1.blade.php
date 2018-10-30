@extends('layouts.app')

@section('content')
    @include('agreement.system.progress')

    <div class="row agreement-system">
        <div class="col-12">

            <h3>@lang('agreement_system.contract_contents')</h3>
            <div class="mb-5">
                @lang('agreement_system.agreement_number')
            </div>
            <form method="post" action="{{route('agreementSystem.postStep1Proceed')}}">
                {{ csrf_field() }}
                @foreach($arrayProvision as $index => $provision)
                    <div class="mb-3">
                        <strong>{{$provision['provisions']}}</strong>
                        @if (array_key_exists('agreement_provision_item', $provision))
                            @foreach($provision['agreement_provision_item'] as $key => $item)
                                <div>
                                    <span class="pl-4">{{$item['item']}}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
                <hr>
                <div align="center">
                    <button class="btn btn--gradient-default btn-lg" type="button"
                            onclick="onBack()">@lang('agreement_system.btn.cancel')</button>
                    <button class="btn btn--gradient-green btn-lg"
                            type="submit">@lang('agreement_system.btn_i_agree')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var urlBackStep0 = '{{route('agreementSystem.back.getStep0', ['corpAgreementId' => $corpAgreementId ])}}';

        function onBack() {
            window.location.href = urlBackStep0;
        }

    </script>
@endsection
