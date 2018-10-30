@extends('layouts.app')

@section('content')
<div class="report-jbr-ongoing">
    @include('report.components.ongoing')
</div>
@endsection

@section('script')
    <script>
        var urlGetJbrOngoing = '{{ route('report.jbr_ongoing') }}';
    </script>
    <script src="{{ mix('js/pages/jbr_ongoing.js') }}"></script>
@endsection