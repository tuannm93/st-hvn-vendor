<div class="form-category mb-4">
    <label class="form-category__label d-none d-sm-block">{!! trans('commission_detail.jpr_info') !!}</label>
    <div class="form-category__body clearfix">
        <div class="ml-lg-4">
            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.jbr_estimate_status') !!}</span>
                </div>
                <div class="col-sm-9 col-lg-3">
                    @if(Auth::user()['auth'] == 'affiliation')
                        {{Form::input('hidden', 'data[DemandInfo][jbr_estimate_status]', $results['DemandInfo__jbr_estimate_status'], ['id' => 'jbr_estimate_status', 'class' => 'form-control'])}}
                    @else
                        {{ Form::select(
                            'data[DemandInfo][jbr_estimate_status]',
                            ['' => trans('common.none')] + $drop_list['jbr_estimate_status'],
                            $results['DemandInfo__jbr_estimate_status'],
                            [
                                'id' => 'jbr_estimate_status',
                                'class' => 'form-control'
                            ]
                        ) }}
                    @endif
                </div>
            </div>
            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.jbr_receipt_status') !!}</span>
                </div>
                <div class="col-sm-9 col-lg-3">
                    @if(Auth::user()['auth'] == 'affiliation')
                        {{Form::input('hidden', 'data[DemandInfo][jbr_receipt_status]', $results['DemandInfo__jbr_receipt_status'], ['id' => 'jbr_receipt_status', 'class' => 'form-control'])}}
                    @else
                        {{ Form::select(
                            'data[DemandInfo][jbr_receipt_status]',
                            ['' => trans('common.none')] + $drop_list['jbr_receipt_status'],
                            $results['DemandInfo__jbr_receipt_status'],
                            [
                                'id' => 'jbr_receipt_status',
                                'class' => 'form-control'
                            ]
                        ) }}
                    @endif
                </div>
            </div>

            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.jbr_receipt_price') !!}</span>
                </div>
                <div class="col-sm-9 col-lg-3 d-flex pr-0">
                    <div class="form-group mb-2 w-100">
                        {{Form::input('text', 'data[DemandInfo][jbr_receipt_price]', $results['DemandInfo__jbr_receipt_price'], ['id' => 'jbr_receipt_price', 'class' => 'form-control', 'data-rule-number'=>"true"])}}
                        @if ($errors->has('jbr_receipt_price'))
                            <label class="invalid-feedback d-block">{{$errors->first('jbr_receipt_price')}}</label>
                        @endif
                    </div>
                    <label class="ml-1 mt-2" for="jbr_receipt_price">{!! trans('common.yen') !!}</label>
                </div>
            </div>

            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.jbr_estimate') !!}</span>
                </div>
                <div class="col-sm-9 col-lg-5">
                    <div class="form-row align-items-center">
                        <div class="col-auto my-1">
                            {{ Form::file('data[DemandInfo][jbr_estimate]', ['id' => 'jbr_estimate', 'class' => 'comission-input-file']) }}
                            <label class="btn btn--gradient-default remove-effect-btn chosen-attach-file" for="jbr_estimate">{{ trans('common.choose_file') }}</label>
                            <span class="text-muted reset-file-name">{{ trans('common.no_file_chosen') }}</span>
                        </div>
                        @if (isset($estimate_file_url) && $estimate_file_url['path'])
                        <div class="col-auto my-1">
                            <a href="{{ route('download.index', ['target' => base64_encode($estimate_file_url['path']), 'filename' => base64_encode($results['DemandInfo__upload_estimate_file_name'])]) }}" class="link-primary text--underline" target="_blank">{{$results['DemandInfo__upload_estimate_file_name']}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row mb-2">
                <div class="col-sm-3 col-lg-2 col-form-label pr-0">
                    <span class="font-weight-bold pl-2 pl-md-0 border-left-orange-mobi">{!! trans('commission_detail.jbr_receipt') !!}</span>
                </div>
                <div class="col-sm-9 col-lg-5">
                    <div class="form-row align-items-center">
                        <div class="col-auto my-1">
                            {{ Form::file('data[DemandInfo][jbr_receipt]', ['id' => 'jbr_receipt', 'class' => 'comission-input-file']) }}
                            <label class="btn btn--gradient-default remove-effect-btn chosen-attach-file" for="jbr_receipt">{{ trans('common.choose_file') }}</label>
                            <span class="text-muted reset-file-name">{{ trans('common.no_file_chosen') }}</span>
                        </div>
                        @if (isset($receipt_file_url) && $receipt_file_url['path'])
                        <div class="col-auto my-1">
                            <a href="{{ route('download.index', ['target' => base64_encode($receipt_file_url['path']), 'filename' => base64_encode($results['DemandInfo__upload_receipt_file_name'])]) }}" class="link-primary text--underline" target="_blank">{{$results['DemandInfo__upload_receipt_file_name']}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
