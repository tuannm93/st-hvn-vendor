<form method="POST" action="{{$routeAction}}" novalidate id="form-user-detail">
    {{csrf_field()}}
    <div class="form-group row">
        <label class="col-12 col-sm-12 col-md-2">@lang('user.user_id')<span class="text-danger">*</span></label>
        <div class="col-12 col-sm-12 col-md-4">
            <input data-rule-pattern="[a-zA-Z0-9\-]+" data-msg-pattern="{{ trans('user.user_id_regex') }}" data-rule-required="true" maxlength="20" class="form-control {{ $errors->first('user_id') ? 'is-invalid' : ''}}" type="text" name="user_id"
                   @if(!Session::has('check')) value="{{isset($data) ? $data['user_id'] : old('user_id')}}" @endif/>
            @if($errors->first('user_id'))
                <label class="invalid-feedback">{{$errors->first('user_id')}}</label>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-sm-12 col-md-2">@lang('user.user_name')<span class="text-danger">*</span></label>
        <div class="col-12 col-sm-12 col-md-4">
            <input data-rule-required="true" maxlength="40" class="form-control" type="text" name="user_name"
                   @if(!Session::has('check')) value="{{isset($data) ? $data['user_name'] : old('user_name')}}" @endif/>
            @if($errors->first('user_name'))
                <label class="invalid-feedback">{{$errors->first('user_name')}}</label>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-sm-12 col-md-2">@lang('user.password')<span class="text-danger">*</span></label>
        <div class="col-12 col-sm-12 col-md-4">
        <input data-rule-pattern="[a-zA-Z0-9_\<\>\!\$%&@\+\-\*\=]*" data-msg-pattern="{{ trans('user.password_regex') }}"  @if(!Session::has('check')) value="{{old('password')}}" @endif type="password" name="password" maxlength="40" id="password" data-rule-required="{{isset($data) ? 'false' : 'true'}}" class="form-control"/>
        @if($errors->first('password'))
            <label class="invalid-feedback">{{$errors->first('password')}}</label>
        @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-sm-12 col-md-2">@lang('user.re_password')<span class="text-danger">*</span></label>
        <div class="col-12 col-sm-12 col-md-4">
        <input data-rule-equalto="#password" maxlength="40" data-msg-equalto="{{ trans('user.same_password') }}" @if(!Session::has('check')) value="{{old('password_confirm')}}" @endif data-rule-required="{{isset($data) ? 'false' : 'true'}}" class="form-control" type="password" name="password_confirm"/>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-sm-12 col-md-2">@lang('user.business')<span class="text-danger">*</span></label>
        <div class="col-12 col-sm-6 col-md-2">
            <select name="auth" id="select_user_detail" data-rule-required="true" class="form-control">
                @if(!isset($data))
                    @foreach($authList as $key => $value)
                        <option id="auth_{{$key}}" value="{{$key}}" {{old('auth') == $key ? 'selected' : ''}}>{{$value}}</option>
                    @endforeach
                @else
                    @if(old('auth'))
                        @foreach($authList as $key => $value)
                            <option id="auth_{{$key}}" value="{{$key}}" {{old('auth') == $key ? 'selected' : ''}}>{{$value}}</option>
                        @endforeach
                    @else
                        @foreach($authList as $key => $value)
                            <option id="auth_{{$key}}" value="{{$key}}" {{$data['auth'] == $key ? 'selected' : ''}}>{{$value}}</option>
                        @endforeach
                    @endif
                @endif
            </select>
            @if($errors->first('auth'))
                <label class="invalid-feedback">{{$errors->first('auth')}}</label>
            @endif
        </div>
        <input type="hidden" name="affiliation_id" id="official_corp_id" />
    </div>
    <div class="form-group row pr-md-3" id="row_official_corp_name">
        <label class="col-12 col-sm-12 col-md-2">@lang('user.store')<span class="text-danger">*</span></label>
        <div class="col-8 col-sm-9 col-md-4 pr-1 pl-md-3">
            <input maxlength="40" class="form-control ignore" type="text" name="official_corp_name"
                   @if(!Session::has('check')) value="@if(old('official_corp_name')){{old('official_corp_name')}} @elseif(isset($dataMcorp['official_corp_name']) && old('official_corp_name')){{old('official_corp_name')}} @elseif(isset($dataMcorp['official_corp_name'])) {{$dataMcorp['official_corp_name']}} @endif" @endif
                   id="official_corp_name" />
            @if($errors->first('official_corp_name'))
                <label class="invalid-feedback">{{$errors->first('official_corp_name')}}</label>
            @endif
        </div>
        <div class="col-4 col-sm-3 col-md-2 pl-1">
            <input value="@lang('user.customer_name_search')" type="button" id="customer_name_search" class="btn btn--gradient-orange w-100"/>
        </div>
    </div>
    <div class="form-group row justify-content-center pt-2 pr-md-3">
        <div class="col-6 col-sm-4 col-md-2 pr-1">
            <input class="btn btn--gradient-gray w-100" type="button" value="@lang('user.cancel')" id="btn_user_search" />
        </div>
        <div class="col-6 col-sm-4 col-md-2 pl-1">
            <input type="submit" class="btn btn--gradient-green w-100" value="@lang('user.register')"/>
        </div>
    </div>
</form>
@section('script')
    <script>
        var SEARCH_URL = '{{route('commission_select.m_corp_search')}}';
        var FORM = '#corpSearch';
        var MODAL = '#mCorpList';
        var INPUT_CORP_NAME = '#official_corp_name';
        var INPUT_CORP_ID = '#official_corp_id';
        var LIST_DATA_SELECTOR = '.list-data';
        var PAGINATE_SELECTOR = '#mCorpPagination';
        var NEXT_TEXT = '@lang('commissionselect.m_corp_display.next')';
        var PREV_TEXT = '@lang('commissionselect.m_corp_display.prev')';
        var BTN_TOGGLE_MODAL = '#customer_name_search';
        var BTN_SEARCH = '#btnSearch';
        var INPUT_SEARCH_CORP = '#mCorpList input[name=corp_name]';
    </script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/commission_select_m_corp.js') }}"></script>
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script src="{{mix('js/pages/user_detail.js')}}"></script>
@endsection
