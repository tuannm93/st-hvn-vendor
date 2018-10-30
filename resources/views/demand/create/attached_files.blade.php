<div class="col-12 my-4">
    <h6 class="form-note align-items-center d-flex font-weight-bold mt-0 mb-3">
        @lang('demand_detail.attachment')
    </h6>
    {{--<p class="form__status">アップロードされていません</p>--}}

    <div class="table-responsive">
        <table class="table table-list table-bordered">
            <thead>
                <tr>
                    <th align="center" >@lang('demand_detail.file')</th>
                    <th align="center" >@lang('demand_detail.upload_date_time')</th>
                    <th align="center"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($demand->demandAttachedFiles as $file)
                    <tr>
                        <td>
                            <a class="text--orange">{{ $file->name }}</a>
                        </td>
                        <td>{{ $file->create_date }}</td>
                        <td>
                            <button data-attached_id="{{ $file->id }}" data-url="{{ $file->path }}" class="btn btn-default btn-delete-attached-file">
                                {{__('demand.delete_file')}}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">@lang('demand_detail.not_upload')</td></tr>
                @endforelse
            </tbody>
        </table>

    </div>


    <div class="file-list mb-4">
        @for($i = 0 ; $i < 5; $i++)
        <div class="row mb-2">
            <div class="col-6 col-lg-4 trigger-section-file">
                 {!! Form::file('demand_attached_file[' . $i . ']', ['class' => 'demand_input_file']) !!}
                <button class="btn btn--gradient-default remove-effect-btn">{{__('demand.choose_file')}}</button>
                <span class="text-muted reset-file-name">{{__('demand.no_file_chosen')}}</span>
            </div>
            <div class="col-6">
                <button class="btn btn--gradient-default reset-default-attached-file remove-effect-btn">{{__('demand.clear')}}</button>
            </div>
        </div>
        @endfor
    </div>

    <p class="text--info"><strong>@lang('demand_detail.please_select_upload')</strong></p>
    <p class="text-muted">
        @lang('demand-detail.file_type_upload')
        <br>
        @lang('demand_detail.max_file_size')
    </p>
    <hr>
    {!! Form::button('アップロード', ['class' => 'btn btn--gradient-orange d-block mx-auto mb-2 btn-lg', 'id' => 'submit-form-attached-file']) !!}
    <p class="text--info text-center font-weight-bold">@lang('demand_detail.confirm_upload_done')</p>
</div>
