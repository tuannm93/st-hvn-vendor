@extends('layouts.app')

@section('content')
    <div class="container">
        @component("user.components._form_search", ["authList" => $authList,
            "export"   => false,
            "auth"     => $auth,
            "username" => $username,
            "corpName" => $corpName,
            "isBack"   => $isBack
            ])
        @endcomponent
    </div>
    <div id="viewResult"></div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/user.search.js') }}"></script>
@endsection
