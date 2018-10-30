<!-- エラーメッセージ領域開始 -->
<div id="error">
    <div id="error-inner"><?php //echo htmlspecialchars($error_message, ENT_QUOTES) ?></div>
</div>
<!-- エラーメッセージ領域終了 -->
{!! Form::open(['route'=> 'search']) !!}
{!! Form::label('name', 'mode') !!}
{!! Form::label('name', 'operation') !!}

<div id="top">
    <br />
    <div id="contents">
        <div id="main">
            <h2>@lang('common.info_from_system')</h2>
        </div><!-- main end-->
    </div><!-- contents end -->
</div><!-- top end -->
{!! Form::close() !!}
