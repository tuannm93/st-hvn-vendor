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
        <h3 class="title-selection mt-2">@lang('selection.selection_prefecture_label')</h3>
        <div class="row">
            <div class="col mt-1">
                <span>@lang('selection.selection_title')：{{ $genre->genre_name }}</span>
                <form action="{{ route('selection.prefecture.post', $genre->id) }}" id="prefecture-selection" method="post" accept-charset="utf-8">
                    {{ csrf_field() }}
                    <div class="col mt-2 p-0"><input type="checkbox" id="select_all">@lang('common.select_all')</div>
                    @php $i = 0; @endphp
                    @foreach(array_chunk($prefectures, 4) as $keyGroup => $group)
                        <div class="row">
                            @foreach($group as $key => $value)
                                @php
                                    $i++;
                                    $checked = "";
                                    $businessTripAmount = "";
                                    $selectionTypeValue = $defaultSelectionType;
                                    $auctionFee = "";
                                    foreach ($genreList as $row) {
                                        if (!empty($row->prefecture_cd) && $row->prefecture_cd == $i) {
                                            $checked = "checked";
                                            $businessTripAmount = $row->business_trip_amount;
                                            $selectionTypeValue = isset($row->selection_type) ? $row->selection_type : $defaultSelectionType;
                                            $auctionFee = $row->auction_fee;
                                        }
                                    }
                                @endphp
                                <div class="col-md-6 col-lg-3 mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checkbox-selection" type="checkbox" {{ $checked }} id="inline{{ $i }}" value="{{ $i }}" name="data[{{ $i }}][prefecture_cd]">
                                        <label class="form-check-label" for="inline{{ $i }}">{{ $value }}</label>
                                    </div>
                                    <div class="form-group row">
                                        <label for="selectionType{{ $i }}" class="col-md-5 col-form-label">@lang('selection.selection_type') :</label>
                                        <div class="col-md-6 pl-md-0">
                                            <select class="form-control" id="selectionType{{ $i }}" name="data[{{ $i }}][selection_type]">
                                                @foreach($selectionType as $k => $v)
                                                    <option @if($selectionTypeValue == $k) selected @endif value="{{ $k }}">{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="auctionFee{{ $i }}" class="col-md-5 col-form-label pr-0">@lang('selection.auction_fee') :</label>
                                        <div class="col-md-7 d-flex pl-md-0">
                                            <div class="w-100">
                                                <input type="text" class="form-control" id="auctionFee{{ $i }}" name="data[{{ $i }}][auction_fee]" value="{{ $auctionFee }}" data-rule-number="true">
                                            </div>
                                            <div class="ml-1 mt-2"> @lang('common.yen') </div>
                                            <input type="hidden" class="form-control" id="businessTripAmount{{ $i }}" name="data[{{ $i }}][business_trip_amount]" value="{{ $businessTripAmount }}">
                                            <input type="hidden" class="form-control" id="genreId{{ $i }}" name="data[{{ $i }}][genre_id]" value="{{ $genre->id }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="d-flex flex-column flex-sm-row justify-content-sm-center">
                        <a href="{{ route('selection.index') }}" class="btn btn--gradient-gray mb-1 fix-width-120" role="button">@lang('common.return_button')</a>
                        <button type="submit" class="btn btn--gradient-green mb-1 ml-sm-2 fix-width-120" type="button">@lang('common.save_button')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ mix('js/pages/selection_prefecture.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>

    <script>
        $(document).ready(function () {
            SelectionPrefecture.init();
        });
        FormUtil.validate('#prefecture-selection');
    </script>
@endsection
