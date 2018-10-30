@extends('layouts.app')
@section('content')
<div class="auction-flowing">
    @component('auction_setting.components.tabs')
    @endcomponent
    <div class="form-category">
        <label class="form-category__label">{!! __('auction_settings.bid_rank_title') !!}</label>
        <div class="container-fluid">
                {!! Form::open(['url' => route('auction.setting.post.flowing'),'class' => 'form-horizontal fieldset-custom']) !!}
                <fieldset>
                    <legend>{!! __('auction_settings.search_con_title') !!}</legend>
                    <div class="form-search"> 
                        <div class="row">
                            <div class="form-group col-12 col-sm-12 col-md-6 form-inline">
                                <label for="genres_id" class="col-form-label col-12 col-sm-12 col-md-2">
                                    {!! __('auction_settings.type_title') !!}
                                </label>
                                {!! Form::select('genreIds[]', $genres,false,['class' => 'form-control select col-12 col-sm-12 col-md-8', 'id' => 'genres_id', 'multiple' => true, $disabled]) !!}
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-2 form-inline">
                                <label for="year" class="col-form-label col-12 col-sm-2 col-md-1">{!! __('auction_settings.year_title') !!}</label>
                                <div class="col-12 col-sm-10 col-md-10 form-date">
                                    {!! Form::select('year', $years, null, ['class'=>'form-control', 'id'=>'year', $disabled])!!}
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-2 form-inline">
                                <label for="month" class="col-form-label col-12 col-sm-2 col-md-1">{!! __('auction_settings.month_title') !!}</label>
                                <div class="col-12 col-sm-10 col-md-10 form-date">
                                    {!! Form::select('month', $months, null, ['class'=>'form-control', 'id'=>'month', $disabled])!!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-12 col-sm-12 col-md-4">
                                <div class="col-5 col-sm-5 col-md-5 pl-0">
                                    {{ Form::submit(__('auction_settings.btn_search_title'),['class' => 'btn btn--gradient-orange w-100', 'name' => 'search', 'id' => 'btn-search', $disabled]) }}
                                </div>
                                <div class="col-7 col-sm-7 col-md-7 pr-0">
                                    {{ Form::submit(__('auction_settings.csv_download'),['class' => 'btn btn--gradient-green w-100', 'name' => 'csv', 'id' => 'btn-csv', $disabled]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                {{ Form::close() }}
            <div class="table-result-search">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center w-16-7">{!! __('auction_settings.type_title') !!}</th>
                                <th class="text-center w-16-7">{!! __('auction_settings.state') !!}</th>
                                <th class="text-center w-16-7">{!! __('auction_settings.c_number') !!}</th>
                                <th class="text-center w-16-7">{!! __('auction_settings.c_rate') !!}</th>
                                <th class="text-center w-16-7">{!! __('auction_settings.number') !!}</th>
                                <th class="text-center w-16-7">{!! __('auction_settings.rate') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($rankData))
                                @foreach($rankData as $r)
                                <tr>
                                    <td class="w-16-7">{!! $r['genre_name'] !!}</td>
                                    <td class="w-16-7">{!! $r['prefecture_name'] !!}</td>
                                    <td class="text-right w-16-7">{!! $r['year_count'] !!}</td>
                                    <td class="text-right w-16-7">{!! $r['year_flowing_ratio'] !!}</td>
                                    <td class="text-right w-16-7">{!! $r['month_count'] !!}</td>
                                    <td class="text-right w-16-7">{!! $r['month_flowing_ratio'] !!}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ mix('js/lib/jquery.multiselect.js') }}"></script>
<script src="{{ mix('js/lib/jquery.multiselect.filter.js') }}"></script>
<script src="{{ mix('js/pages/auction.flowing.js') }}"></script>
@endsection