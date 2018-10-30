{{-- Form select order --}}
<form id="selectOrderForm" class="fieldset-custom">
    <fieldset>
        <legend>{{ __('report_corp_commission.sort_condition_label') }}</legend>
        <div class="bg-update-box border-update-box p-2">
            <div class="form-row">
                <div class="form-group col-lg-2">
                    <select name="order1" id="order1" class="form-control order">
                        <option value=""></option>
                        @foreach($sortOptions as $key => $value)
                            <option value="{{ $key }}" @if(isset($session['order1']) && $session['order1'] == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="asc" @if(isset($session['direction1']) && $session['direction1'] == 'asc') checked @endif name="direction1" id="direction1Asc">
                        <label class="custom-control-label" for="direction1Asc">{{ __('report_corp_commission.ascending') }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="desc" @if(isset($session['direction1']) && $session['direction1'] == 'desc') checked @endif name="direction1" id="direction1Desc">
                        <label class="custom-control-label" for="direction1Desc">{{ __('report_corp_commission.descending') }}</label>
                    </div>
                </div>

                <div class="form-group col-lg-2">
                    <select name="order2" id="order2" class="form-control order">
                        <option value=""></option>
                        @foreach($sortOptions as $key => $value)
                            <option value="{{ $key }}" @if(isset($session['order2']) && $session['order2'] == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="asc" @if(isset($session['direction2']) && $session['direction2'] == 'asc') checked @endif name="direction2" id="direction2Asc">
                        <label class="custom-control-label" for="direction2Asc">{{ __('report_corp_commission.ascending') }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="desc" @if(isset($session['direction2']) && $session['direction2'] == 'desc') checked @endif name="direction2" id="direction2Desc">
                        <label class="custom-control-label" for="direction2Desc">{{ __('report_corp_commission.descending') }}</label>
                    </div>
                </div>

                <div class="form-group col-lg-2">
                    <select name="order3" id="order3" class="form-control order">
                        <option value=""></option>
                        @foreach($sortOptions as $key => $value)
                            <option value="{{ $key }}" @if(isset($session['order3']) && $session['order3'] == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="asc" @if(isset($session['direction3']) && $session['direction3'] == 'asc') checked @endif name="direction3" id="direction3Asc">
                        <label class="custom-control-label" for="direction3Asc">{{ __('report_corp_commission.ascending') }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="desc" @if(isset($session['direction3']) && $session['direction3'] == 'desc') checked @endif name="direction3" id="direction3Desc">
                        <label class="custom-control-label" for="direction3Desc">{{ __('report_corp_commission.descending') }}</label>
                    </div>
                </div>

                <div class="form-group col-lg-2">
                    <select name="order4" id="order4" class="form-control order">
                        <option value=""></option>
                        @foreach($sortOptions as $key => $value)
                            <option value="{{ $key }}" @if(isset($session['order4']) && $session['order4'] == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="asc" @if(isset($session['direction4']) && $session['direction4'] == 'asc') checked @endif name="direction4" id="direction4Asc">
                        <label class="custom-control-label" for="direction4Asc">{{ __('report_corp_commission.ascending') }}</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="direction custom-control-input" value="desc" @if(isset($session['direction4']) && $session['direction4'] == 'desc') checked @endif name="direction4" id="direction4Desc">
                        <label class="custom-control-label" for="direction4Desc">{{ __('report_corp_commission.descending') }}</label>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <input type="hidden" value="{{ $defaultOrder }}" id="defaultOrder">
                <div class="col-lg-3 d-flex flex-column flex-md-row align-self-md-start justify-content-md-end">
                    <button id="orderSearch" type="button" name="search" class="btn btn--gradient-orange border mt-1 mt-md-0">{{ __('report_corp_commission.sort_by') }}</button>
                    <button id="resetOrder" type="button" name="reset" class="btn btn--gradient-gray border mt-1 mt-md-0 ml-md-1">{{ __('report_corp_commission.reset_initial_value') }}</button>
                </div>
            </div>
        </div>
    </fieldset>
</form>
