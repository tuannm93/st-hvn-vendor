@extends('layouts.app')
@section('content')
<div class="vacation-edit pt-2">
    @if (Session::has('success'))
    <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif @if (Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <div class="form-category pt-2">
        <label class="form-category__label mb-2">@lang("vacation_edit.title")</label>
        {!! Form::open(['url' => route('vacation_edit.update'), 'method' => 'post', 'class'=>'form-horizontal', 'id' => 'vacation-edit']) !!}
            <div class="form-category__body">
                <div class="p-2 row item-hearder mr-0 ml-0">
                    <label class="col-12 col-form-label font-weight-bold">{!! __("vacation_edit.description") !!}</label>
                </div>
                @for($i = 1; $i <=10; $i++)
                    <div class="form-group row item-inline">
                        <label class="col-3 col-sm-3 col-md-3 col-form-label">@lang("vacation_edit.date", ["count" => $i])</label>
                        <div class="col-9 col-sm-9 col-md-3">
                            {!! Form::text("mItem[$i][item_name]", isset($results[$i]) ? $results[$i]->item_name : '', ['class' => 'form-control w-100', 'data-rule-pattern' => '^((([13578]|0[13578]|1[02])\/([1-9]|0[1-9]|[12][0-9]|3[01]))|(([469]|0[469]|11)\/([1-9]|0[1-9]|[12][0-9]|30))|((2|02)\/([1-9]|0[1-9]|1[0-9]|2[0-9])))$', 'data-msg-pattern' => trans('vacation_edit.invalid-date')]) !!}
                        </div>
                    </div>
                @endfor
            </div>
            <div class="text-center mt-3">
                {!! Form::submit(trans('vacation_edit.submit'), ['class' => 'btn btn--gradient-green']) !!}
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{ mix('js/pages/vacation-edit.js') }}"></script>
@endsection
