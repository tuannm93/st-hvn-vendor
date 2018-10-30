@extends('layouts.app')

@section('content')
    <div class="progress-management-demand-detail content-ajax-tbl fs-14">
        @include('progress_management.component.ajax_form_demand_detail')
    </div>
@endsection

@section('script')
    <script>
        var required_mess = '@lang('demand_detail.required_mess')';
        var urlDemandDetail = '{{ route('get.progress_management.demand_detail', $id) }}';
        var urlRedirect = '{{ route('post.progress_management.demand_detail.redirect') }}';
        var urlSaveSession = '{{ route('post.progress_management.demand_detail.saveSession') }}';
        var urlUpdateConfirm = '{{ route('progress_management.show.update_confirm') }}';
        $(document).ready(function () {
            AffDemandDetail.init();
        });
    </script>

    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script type="text/javascript" src="{{ mix('js/pages/aff_demand_detail.js') }}"></script>
@endsection