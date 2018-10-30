@if ($errors->has($attribute))
    {!! $errors->first($attribute, '<p><span class="text-danger">:message</span></p>') !!}
@endif