@extends('layouts.app')

@section('content')
<div class="notice-info-index">
    @if(!empty(session('success_message')))
        <div class="box__mess box--success my-1">
            {{ session('success_message') }}
        </div>
    @endif
    @include('notice_info.components.list_notice_infos', ['isGet' => true])
</div>
@endsection

@section('script')
    <script>
        var urlAjaxGetListNoticeInfo = '{{ route('notice_info.ajax.get.list') }}';
    </script>
    <script src="{{ mix('js/pages/notice_info.index.js') }}"></script>
@endsection
