<div class="fieldset-custom mt-4">
    <fieldset class="form-group">
        <input type="hidden" id="checkBack" value="{{ $isBack }}" >

        <legend class="">@lang("user_index.form_legend")</legend>
        {{ Form::open(['action' => 'User\UserController@search', 'method' => 'post', 'id' => 'UserSearch']) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">@lang("user_index.authority")</label>
                    <div class="col-sm-9">
                        {{Form::select('auth', $authList, isset($auth) && $auth != null ? $auth : "",["class" => "form-control"])}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">@lang("user_index.username")</label>
                    <div class="col-sm-9">
                        {{Form::text('user_name', isset($username) ? $username : "", ["class" => "form-control"])}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 ">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">@lang("user_index.corp_name")</label>
                    <div class="col-sm-9">
                        {{Form::text('corp_name', isset($corpName) ? $corpName : "", ["class" => "form-control"])}}
                    </div>
                </div>
            </div>
        </div>
        <div id="dataPage" data-url-search="{{route('user.search')}}"></div>
        <div class="d-flex flex-column flex-sm-row">
            <a href="{{action("User\UserController@create")}}" class="btn btn--gradient-green col-lg-2 col-sm-3 mb-1 mb-sm-0">@lang("user_index.create")</a>
            <button type="submit" id="buttonSearchForm" class="btn btn--gradient-orange col-lg-2 col-sm-3 mx-sm-2 mb-1 mb-sm-0" name="submit" value="search">@lang("user_index.search")</button>
            <button type="submit" id="btnExportCsv" class="btn btn--gradient-orange col-lg-2 col-sm-3" name="submit" value="export">@lang("user_index.export")</button>
        </div>
        {{Form::close()}}
    </fieldset>
</div>
