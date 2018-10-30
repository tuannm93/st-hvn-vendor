@extends('layouts.app')

@section('content')
    <div class="agreement-system">
        <div class="text-center mb-4">
            <h3>
                {!! __('agreement_system.note_step0') !!}
            </h3>
        </div>
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="box__mess box--error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <h4 class="border_left_orange"><strong>@lang('agreement_system.history_agreement')</strong></h4>
        <form method="post" action="{{route('agreementSystem.postStep0Proceed')}}">
            {{ csrf_field() }}
            <table class="table table-bordered table-list">
                <thead>
                <tr>
                    <th class="text-center w-10">@lang('agreement_system.No')</th>
                    <th class="text-center w-70">@lang('agreement_system.acceptation_date')</th>
                    <th class="text-center">@lang('agreement_system.status')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($corpAgreementList as $i => $corpAgreement)
                    <tr>
                        <td class="text-center">{{$i + 1}}</td>
                        <td class="text-center">
                            @if($corpAgreement->status === \App\Models\CorpAgreement::APPLICATION)
                                {{ date_time_format_jp($corpAgreement->create_date)}}
                            @endif
                        </td>
                        <td>{{$corpAgreement->status}}
                            / {{\App\Models\CorpAgreement::AGREEMENT_STATUS[$corpAgreement->status]}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <hr>
            <div align="center">
                <button class="btn btn--gradient-default btn-lg" type="button"
                        onclick="onBack()">@lang('agreement_system.btn.back')</button>
                <button class="btn btn--gradient-green btn-lg"
                        type="submit">@lang('agreement_system.btn.proceed')</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function onBack() {
            var urlAuction = '{{route('auction.index')}}';
            window.location.href = urlAuction;
        }
    </script>
@endsection

