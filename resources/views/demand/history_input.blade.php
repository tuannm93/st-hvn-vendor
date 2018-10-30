<div id="contents">
    <p class="font-weight-bold mb-0 fs-14"> {{trans('demandcorresponds.corresponding_history_information_input') }} </p>
    <form id="history-input-form" action="{{action('Demand\DemandController@historyInput')}}" method="post">
        <input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
        <input type="hidden" value="{{ $demand->id }}" id="id" name="id">
        <input type="hidden" value="{{ Session::get('modified') ?? $demand->modified }}" name="modified">
        <div>
            <div class="row border-top ml-2 mr-2">
                <div class="col-md-1 p-3 my-auto">
                    {{trans('demandcorresponds.corresponding_person') }}
                </div>
                <div class="col-md-1 my-auto p-3">
                    <span class="badge badge-warning">{{ __('common.have_to') }}</span>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4 p-3">
                    <select name="responders" id="responders" class="form-control custom-required" type="text">                       >
                        <option value="">--{{trans('demandcorresponds.none') }}--</option>
                        @foreach($userList as $key => $item)
                        <option
                                @php if ($demand->responders == $key) echo 'selected'; @endphp value="{{$key}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row border-top ml-2 mr-2">
                <div class="col-md-1 p-3 my-auto">
                    {{trans('demandcorresponds.correspondence_contents') }}
                </div>
                <div class="col-md-1 my-auto p-3">
                    <span class="badge badge-warning">{{ __('common.have_to') }}</span>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-8 p-3">
                    <textarea name="corresponding_contens" class="corresponding_contens form-control custom-required"
                        rows="8" id="CommissionCorrespondCorrespondingContens" maxlength="1000">{{$demand->corresponding_contens}}</textarea>
                    @if ($errors->has('corresponding_contens'))
                        <p class="form-control-feedback text-danger my-2 has-danger">
                            {{ $errors->first('corresponding_contens') }}
                        </p>
                    @endif
                </div>
            </div>

             <div class="row border-top ml-2 mr-2">
                <div class="col-md-1 p-3 my-auto">
                    {{trans('demandcorresponds.date_and_time') }}
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-1"></div>
                <div class="col-md-4 p-3">
                    <input name="correspond_datetime" id="correspond_datetime"
                                size="40" maxlength="40" class="datetimepicker form-control border-clor" type="text"
                                value="{{$demand->correspond_datetime ?  date_format(date_create($demand->correspond_datetime),'Y/m/d H:i') : ''}}"></td>
                    @if ($errors->has('correspond_datetime'))
                        <p class="form-control-feedback text-danger my-2 {{$errors->has('correspond_datetime') ? 'has-danger' : ''}}">{{__($errors->first('correspond_datetime'))}}</p>
                    @endif
                </div>
            </div>
        </div>
        <p class="text-center" height="0">
            <button class="btn btn--gradient-default fix-button-w-120" data-dismiss="modal"
                    >{{trans('demandcorresponds.cancel') }}</button>
            <input name="edit" id="edit" class="btn btn--gradient-green fix-button-w-120" type="submit" value="{{trans('demandcorresponds.edit') }}">
        </p>
        <br>
</form>
</div>

<script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
<script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
<script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
<script src="{{ mix('js/utilities/form.validate.js') }}"></script>
<script src="{{ mix('js/pages/demand_correspond.js') }}"></script>
<script>
    Datetime.initForDateTimepicker();
    FormUtil.validate('#history-input-form');
    Demandcorrespons.init();
</script>
<script>
    var urlPopupHistory ='{{action('Demand\DemandController@historyInput')}}';
</script>




