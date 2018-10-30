<div id="contents">
    <div id="main" class="commission_history_input">
        @php
            $isError = $errors->any()
        @endphp
        @if (Session::has('success'))
            <p class="box__mess box--success">
                {{ __('commission_corresponds.message_successfully') }}
            </p>
        @endif
        @if (Session::has('modified'))
            <p class="box__mess box--error">
                {{ __('commission_corresponds.message_modified_not_check') }}
            </p>
        @endif
        @if ($isError)
            <p class="box__mess box--error">
                {{ __('commission_corresponds.message_failure') }}
            </p>
        @endif
        <h3>{{trans('commission_corresponds.history_information_input') }}</h3>
        <br>
        <form action="{{route('commission.update')}}" method="post" id="commission-history-input">
            <input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
            <input type="hidden" value="{{ $commission->id }}" id="id" name="id">
            <input type="hidden" value="{{ Session::get('modified') ?? $commission->modified }}" name="modified">

            <div id="hankyo_info">
                <div class="form-group row">
                    <label for="@if(isset($commission->rits_responders)) rits_responders @else responders @endif" class="col-sm-3 d-flex col-form-label">
                        {{trans('commission_corresponds.corresponding_person') }}
                        <span class="badge badge-warning ml-3">{{ __('common.have_to') }}</span>
                    </label>
                    <div class="col-sm-9">
                        @if(isset($commission->rits_responders))
                            <select class="form-control" name="rits_responders" id="rits_responders" data-rule-required="true"
                                    type="text">
                                <option value="">--{{trans('commission_corresponds.none')}}--</option>
                                @foreach($userList as $key => $item)
                                    <option
                                        @php if ($commission->rits_responders == $key) echo 'selected'; @endphp value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('rits_responders'))
                                <p class="form-control-feedback text-danger my-2 {{$errors->has('rits_responders') ? 'has-danger' : ''}}">{{__($errors->first('rits_responders'))}}</p>
                            @endif
                        @else
                            <input class="form-control" name="responders" id="responders" size="40" data-rule-required="true" data-rule-maxlength="20"
                                   maxlength="40" type="text"
                                   value="{{$isError ? old('responders') : $commission->responders}}">
                            @if ($errors->has('responders'))
                                <p class="form-control-feedback text-danger my-2 {{$errors->has('responders') ? 'has-danger' : ''}}">{{__($errors->first('responders'))}}</p>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="corresponding_contens" class=" col-sm-3 d-flex align-items-baseline col-form-label">
                        {{trans('commission_corresponds.correspondence_contents')}}
                        <span class="badge badge-warning">{{ __('common.have_to') }}</span>
                    </label>
                    <div class="col-sm-9">
                        <textarea class="form-control corresponding_contens" name="corresponding_contens" id="corresponding_contens"
                                  data-rule-required="true" data-rule-maxlength="1000"
                                  rows="6"
                                  id="CommissionCorrespondCorrespondingContens">{{$isError ? old('corresponding_contens') : $commission->corresponding_contens}}</textarea>
                        @if ($errors->has('corresponding_contens'))
                            <p class="form-control-feedback text-danger my-2 {{$errors->has('corresponding_contens') ? 'has-danger' : ''}}">{{__($errors->first('corresponding_contens'))}}</p>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="correspond_datetime" class="col-sm-3 col-form-label ">
                        {{trans('commission_corresponds.date_and_time')}}
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control datetimepicker" name="correspond_datetime" id="correspond_datetime_popup"
                               size="40" maxlength="40" type="text"
                               value="{{$commission->correspond_datetime ?  date_format(date_create($commission->correspond_datetime),'Y/m/d H:i') : ''}}">
                        @if ($errors->has('correspond_datetime'))
                            <p class="form-control-feedback text-danger my-2 {{$errors->has('correspond_datetime') ? 'has-danger' : ''}}">{{__($errors->first('correspond_datetime'))}}</p>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-9">
                    <div class="d-flex">
                        <button name="cancel" id="cancel" class=" btn btn--gradient-gray function-button mb-2"
                                type="button">
                            {{trans('commission_corresponds.cancel')}}
                        </button>
                        <button name="edit" id="edit" class=" btn btn--gradient-green function-button ml-1 mb-2" type="submit">
                            {{trans('commission_corresponds.edit')}}
                        </button>
                    </div>
                </div>
            </div>
            
            <br>
        </form>
    </div>
</div>
