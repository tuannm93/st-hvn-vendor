<div class="form-category mb-4">
    <label class="form-category__label d-none d-md-block">{!! trans('commission_detail.corresponding_his_info') !!}</label>
    <div class="form-category__body clearfix">
        <div class="ml-lg-4">
            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.corresponding_person') !!}</span>
                </div>
                <div class="col-12 col-sm-9 col-lg-3">
                    @if ($auth == 'affiliation')
                        {{Form::input('text', 'data[CommissionCorrespond][responders]', '', ['id' => 'responders', 'class' => 'form-control'])}}
                    @else
                        {{ Form::select(
                            'data[CommissionCorrespond][rits_responders]',
                            ['' => trans('common.none')] + $user_list,
                            isset($results['CommissionCorrespond__rits_responders']) ? $results['CommissionCorrespond__rits_responders'] : $user->id,
                            [
                                'id' => 'rits_responders',
                                'class' => 'form-control'
                            ]
                        ) }}
                    @endif
                    @if (Session::has('rits_responders_error'))
                    <p class="form-control-feedback text-danger my-2 has-danger">{{ Session::get('rits_responders_error') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.corresponding_content') !!}</span>
                </div>
                <div class="col-12 col-sm-9">
                    {{ Form::textarea('data[CommissionCorrespond][corresponding_contens]', '', ['class' => 'form-control', 'id' => 'corresponding_contens', 'rows' => 5]) }}
                    @if (Session::has('corresponding_contens_error'))
                    <p class="form-control-feedback text-danger my-2 has-danger">{{ Session::get('corresponding_contens_error') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.corresponding_datetime') !!}</span>
                </div>
                <div class="col-12 col-sm-9 col-lg-3">
                    {{Form::input('text', 'data[CommissionCorrespond][correspond_datetime]', date('Y/m/d H:i'), ['id' => 'correspond_datetime', 'class' => 'form-control datetimepicker'])}}
                </div>
            </div>
        </div>
    </div>
</div>
