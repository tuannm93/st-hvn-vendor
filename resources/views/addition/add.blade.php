<div class="panel panel-default">
    <div class="panel-heading">{{ trans('addition.form_add') }}</div>
    <div class="panel-body">
        <div class="col-md-8 col-md-offset-2">

            <form name="create_addition" method="post" id="create_addition" action="{{ route('addition.regist') }}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="form-group row">
                    <div class="col-md-5"><label for="demand_id" class="control-label">{{ trans('addition.number') }}</label></div>
                    <div class="col-md-7"><input type="text" class="form-control" name="demand_id" value="" /></div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5"><label for="customer_name" class="control-label">{{ trans('addition.customer_name') }}</label></div>
                    <div class="col-md-7"><input type="text" class="form-control" name="customer_name" value="" /></div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5"><label for="name" class="control-label">{{ trans('addition.genres') }}</label></div>
                    <div class="col-md-7">
                        <select name="genre_id" class="form-control" required>
                            <option value="">{{ trans('addition.none') }}</option>
                            @foreach($genre_list as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5"><label for="construction_price_tax_exclude" class="control-label">{{ trans('addition.construction_amount') }}</label></div>
                    <div class="col-md-6"><input type="number" required name="construction_price_tax_exclude" value="" class="form-control" /></div>
                    <div class="col-md-1 text-left yen">{{ trans('addition.yen') }}</div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5"><label for="complete_date" class="control-label">{{ trans('addition.construction_date') }}</label></div>
                    <div class="col-md-7"><input type="text" id="datepicker" name="complete_date" required value="" class="form-control" /></div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5"><label for="demand_type_update[]" class="control-label">{{ trans('addition.opp_attributes') }}</label></div>
                    <div class="col-md-7">
                        @foreach(Config::get('constant.demand_type_update') as $attribute => $value)
                            <div class="radio">
                                <label><input type="radio" name="demand_type_update" value="{{ $attribute }}">{{ $value }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5"><label for="note" class="control-label">{{ trans('addition.remarks') }}</label></div>
                    <div class="col-md-7">
                        <textarea class="form-control" name="note" cols="20" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group row text-center">
                    <input type="submit" id="btn_submit" value="{{ trans('addition.submit') }}" class="btn_green_s" />
                </div>
            </form>
        </div>
    </div>
</div>