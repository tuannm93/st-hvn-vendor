@extends('layouts.app')

@section('content')
    <div class="report-development-search">
        @if (Session::has('error'))
            <p class="box__mess box--error mb-0">
                {{ Session::get('error') }}
            </p>
        @endif
        @if ($errors->any())
            <div class="box__mess box--error mb-0">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @if (Session::has('_old_input'))
            {{Session::forget('_old_input')}}
        @endif
        <label class="form-category__label mt-2">{{trans("report_development.title")}}</label>
        {!! Form::open(['url' => route('report.development.search'), 'id' => 'form-report-search', 'novalidate', 'class' => 'fieldset-custom']) !!}
            <fieldset class="form-group">
                <legend class="col-form-label">{{trans("report_development.form.title")}}</legend>
                <div class="box--bg-gray box--border-gray p-2">
                    <div class="row mx-0 mb-2">
                        <div class="col-sm-2 col-xl-1 px-0">
                            <label class="col-form-label">{{trans("report_development.drop.genre")}}</label>
                        </div>
                        <div class="col-sm-6 col-lg-4 px-0">
                            {{Form::select('genre_id', $genres, $genreId, ['placeholder' => trans("report_development.drop.genre.place"), 'class' => 'form-control p-1'])}}
                        </div>
                    </div>
                    <div class="row mx-0 mb-2">
                        <div class="col-sm-2 col-xl-1 px-0">
                            <label class="col-form-label">{{trans("report_development.drop.prefecture")}}</label>
                        </div>
                        <div class="col-sm-3 col-xl-1 px-0">
                            {{Form::select('address', $prefecture, $address, ['placeholder' => trans("report_development.drop.prefecture.place"), 'class' => 'form-control p-1'])}}
                        </div>
                    </div>
                    {{Form::submit(trans("report_development.button.search"), ['class' => 'btn btn--gradient-orange col-sm-2 col-lg-1'])}}
                </div>
            </fieldset>
        {!! Form::close() !!}
    </div>
@endsection