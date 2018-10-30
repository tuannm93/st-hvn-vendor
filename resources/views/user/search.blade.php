@extends('layouts.app')

@section('content')
    <div class="container">
        @component("user.components._form_search", [
            "authList" => $authList,
            "export" => $results->total() > 0,
            "auth" => $auth,
            "username" => $username,
            "corpName" => $corpName,])
        @endcomponent
        @component("user.components._table", ["authList" => $authList, "checkSysAdmin" => $checkSysAdmin, "results" => $results])
        @endcomponent
    </div>
@endsection
