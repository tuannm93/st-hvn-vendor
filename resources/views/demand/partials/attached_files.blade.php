@if(isset($demand->id))
    <div class="col-12 my-4">
    <h6 class="form-note align-items-center d-flex font-weight-bold mt-0 mb-3">
        {{__('demand.attachment')}}
    </h6>

    @if(isset($demand->demandAttachedFiles) && count($demand->demandAttachedFiles) > 0)
        <div class="table-responsive">
            <table class="table table-list table-bordered">
                <thead>
                <tr>
                    <th align="center">{{__('demand.file')}}</th>
                    <th align="center">{{__('demand.date_update')}}</th>
                    <th align="center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($demand->demandAttachedFiles as $file)
                    <tr>
                        <td align="center">
                            <a class="text--orange" href="{{ route('demand.file.download', ['id' => $file->id]) }}">{{ $file->name }}</a>
                        </td>
                        <td>{{ $file->create_date }}</td>
                        <td>
                            <button data-attached_id="{{ $file->id }}" class="btn btn-default btn-delete-attached-file">
                                {{__('demand.delete_file')}}
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>{{__('demand.message_not_have_item_upload')}}</p>
    @endif


    <div class="file-list mb-4">
        @for($i = 0 ; $i < 5; $i++)
            <div class="row mb-2">
                <div class="col-6 col-lg-4 trigger-section-file">
                    {!! Form::file('demand_attached_file[' . $i . ']', [ 'class' => 'demand_input_file']) !!}
                    <button class="btn btn--gradient-default remove-effect-btn chosen-attach-file">{{__('demand.choose_file')}}</button>
                    <span class="text-muted reset-file-name">{{__('demand.no_file_chosen')}}</span>
                </div>
                <div class="col-6">
                    <button class="btn btn--gradient-default reset-default-attached-file remove-effect-btn">{{__('demand.clear')}}</button>
                </div>
            </div>
        @endfor
    </div>

    <p class="text--info"><strong>{{__('demand.message_choice_file_upload')}}</strong></p>
    <p class="text-muted">
        {{__('demand.message_file_type_upload')}}
        <br>
        {{__('demand.message_file_size_upload')}}
    </p>
    <hr>
    {!! Form::button(__('demand.upload'), [
        'class' => 'btn btn--gradient-orange d-block mx-auto mb-2 btn-lg',
        'id' => 'submit-form-attached-file']) !!}
    <p class="text--info text-center font-weight-bold">{{__('demand.message_file_upload_guild_line')}}</p>
</div>
@endif
