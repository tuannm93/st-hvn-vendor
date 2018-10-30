@extends('layouts.app')
@section('style')

@endsection
@section('content')
    <div class="progress-management-delete pt-3">
        @section('header-progress')
            @lang('progress_management.import_commission_info.progress_table')
        @endsection
        <table class="table-progress w-100 pt">
            <tbody>
            <tr class="text-center">
                <th class="align-middle p-1">@lang('progress_management.import_commission_info.file_no')</th>
                <th class="align-middle p-1">@lang('progress_management.import_commission_info.file_name')</th>
                <th class="align-middle p-1">@lang('progress_management.import_commission_info.import_date_time')</th>
            </tr>
            <tr>
                <td class="align-middle p-1 text-center">{{ $progImportFile->id }}<input type="hidden" name="prog_import_file_id"
                                                    value="{{ $progImportFile->id }}" id="ProgImportFileId"></td>
                <td class="align-middle p-1">{{ $progImportFile->original_file_name }}</td>
                <td class="align-middle p-1 text-center">{{ $progImportFile->import_date }}</td>
            </tr>
            </tbody>
        </table>
        <div>
            <div class="title-progress d-flex justify-content-md-between flex-column flex-md-row">
                <h2>@lang('progress_management.import_commission_info.add_progress')</h2>
                <p>@lang('progress_management.import_commission_info.valid_id')</p>
            </div>
            @foreach (['success', 'error'] as $msg)
                @if(Session::has('box--' . $msg))
                    <p class="box__mess box--{{ $msg }} mb-0 mt-3">{{ Session::get('box--' . $msg) }}</p>
                @endif
            @endforeach
            @if ($errors->any())
                <div class="box__mess box--error">
                    @foreach ($errors->all() as $error)
                        <p>{!!  $error !!}</p>
                    @endforeach
                </div>
            @endif
            {!! Form::open(['url' => route('post.import.commission.infos', $progImportFile->id), 'id' => 'commission_info_form']) !!}
            <table class="table-progress w-100">
                <tr>
                    <th>@lang('progress_management.import_commission_info.assignment_id')</th>
                    <td>
                        @php
                            $inValid = '';
                            if ($errors->has('commission_info_id')){$inValid = 'is-invalid';}
                        @endphp
                        {{ Form::textarea('commission_info_id', session('oldValue')['commission_info_id'],['class' => 'form-control '.$inValid, 'rows' => 10]) }}
                    </td>
                </tr>
                <tr>
                    <th>@lang('progress_management.import_commission_info.case_lock')</th>
                    <td>
                        <div class="form-check form-check-inline p-2">
                            {{ Form::radio('commission_info_lock', 1, session('oldValue')['commission_info_lock'] == 1 ? true : false , ['class' => 'form-check-input', 'id' => 'agreement_flag1', 'checked' => true]) }}
                            <label class="form-check-label" for="agreement_flag1">@lang('progress_management.import_commission_info.dont_lock')</label>
                        </div>
                        <div class="form-check form-check-inline p-3">
                            {{ Form::radio('commission_info_lock', 2, session('oldValue')['commission_info_lock'] == 2 ? true : false , ['class' => 'form-check-input', 'id' => 'agreement_flag2']) }}
                            <label class="form-check-label" for="agreement_flag2">@lang('progress_management.import_commission_info.lock')</label>
                        </div>
                    </td>
                </tr>
            </table>
            <div id="page-data"
                data-confirm-import="{{ __('progress_management.confirm_import') }}" >
            </div>
            {{ Form::button(trans('progress_management.import_commission_info.submit'),['class' => 'btn btn--gradient-green mt-2', 'id' => 'btn-import']) }}
            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/import.commission.infos.js') }}"></script>
    <script>
        ImportCommissionInfo.init();
    </script>
@endsection
