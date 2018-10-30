<div class="">
    {{ Session::get('message') }}
</div>

<div class="container">

    {!! Form::open(['route' => 'aution_settings.store']) !!}

    @form_maker_table("aution_settings")

    {!! Form::submit('Save') !!}

    {!! Form::close() !!}

</div>