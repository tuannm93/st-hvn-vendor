@extends('layouts.app')

@section('content')
    @if(session()->has('error'))
        <div class="box__mess box--error">
            {!! session('error') !!}
        </div>
    @endif
    @if(session()->has('success'))
        <div class="box__mess box--success">
            {!! session('success') !!}
        </div>
    @endif
    <div class="container selection">
        <h3 class="title-selection mt-2">@lang('selection.selection_label')</h3>
        <div class="row">
            <div class="col">
                <form action="{{ route('selection.post') }}" method="post" accept-charset="utf-8" class="">
                    {{ csrf_field() }}
                    @foreach($genres->chunk(3) as $groupKey => $groupGenres)
                        <div class="row mt-2">
                            @foreach($groupGenres as $key => $genre)
                                <div class="box col-md-4 form-group">
                                    <a @if(isset($genre->select_type) && (in_array($genre->select_type, [2,3,4]))) class="highlight-link text--underline" href="{{ route('selection.prefecture', $genre->genre_id) }}" @endif>{{ $genre->genre_name }}</a>
                                    <br>
                                    <select class="form-control" name="data[{{ $groupKey }}{{ $key }}][select_type]"
                                        @if(!$hasRoleUser) disabled="disabled" @endif>
                                        @if($selectionType)
                                            @foreach($selectionType as $k => $v)
                                                <option @if($k == $genre->select_type) selected @endif value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" value="{{ $genre->id }}" name="data[{{ $groupKey }}{{ $key }}][id]">
                                    <input type="hidden" value="{{ $genre->genre_id }}" name="data[{{ $groupKey }}{{ $key }}][genre_id]">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="d-flex flex-column flex-sm-row justify-content-md-center">
                        <a href="{{ route('admin.index') }}" class="btn btn--gradient-gray mt-2 mr-md-1 fix-width-120" role="button">@lang('common.return_button')</a>
                        <button @if(!$hasRoleUser) disabled="disabled" @endif type="submit" class="btn btn--gradient-green mt-2 ml-md-1 fix-width-120" type="button">@lang('common.save_button')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
