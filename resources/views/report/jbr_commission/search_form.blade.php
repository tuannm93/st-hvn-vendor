
{!! Form::open(['route' => 'report.jbr_commission', 'method' => 'get', 'class' => 'fieldset-custom']) !!}
    <fieldset class="form-group">
        <legend class="col-form-label fs-13">{{ __('report_corp_commission.sort_condition_label') }}</legend>
        <div class="search-box row">
            @foreach($sortDefault as $key => $sort)
                <div class="col-sm-6 col-xl-20 mb-1">
                    {!! Form::select('order_by[' . $key . ']', $sortOptions,
                        Request::get('order_by') ? '' : (Request::get('order_by[' . $key . ']') ?? $sort['name']),
                    ['class' => 'form-control']) !!}
                    <div class="custom-control custom-radio custom-control-inline">
                        {!! Form::radio('sort_by[' . $key . ']', 'asc', !in_array($key, [3]) && (!Request::get('sort_by['. $key .']') || Request::get('sort_by['. $key .']') == 'asc'),
                        ['class' => 'custom-control-input radio-asc', 'id'=> 'sort_by['.$key.']["asc"]', 'style' => 'z-index: 2']) !!}
                        <label class="custom-control-label" for='sort_by[{{ $key }}]["asc"]'>{{ __('report_corp_commission.ascending') }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        {!! Form::radio('sort_by[' . $key . ']', 'desc', Request::get('sort_by['. $key .']') == 'desc',
                        ['class' => 'custom-control-input radio-desc', 'id'=> 'sort_by['.$key.']["desc"]', 'style' => 'z-index: 2']) !!}
                        <label class="custom-control-label" for='sort_by[{{ $key }}]["desc"]'>{{ __('report_corp_commission.descending') }}</label>
                    </div>
                </div>
            @endforeach

            <div class="col-12 col-xl-20">

                <button class="btn btn--gradient-orange">{{ __('report_corp_commission.sort_by') }}</button>
                <button type="reset" class="btn btn--gradient-gray">{{ __('report_corp_commission.reset_initial_value') }}</button>
            </div>
        </div>
    </fieldset>
{!! Form::close() !!}
