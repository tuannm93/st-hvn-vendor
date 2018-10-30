<div class="">
    {{ Session::get('message') }}
</div>

<div class="container">

    {!! Form::model($aution_setting, ['route' => ['aution_settings.update', $aution_setting->id], 'method' => 'patch']) !!}

    @form_maker_object($aution_setting, FormMaker::getTableColumns('aution_settings'))

    {!! Form::submit('Update') !!}

    {!! Form::close() !!}
</div>
