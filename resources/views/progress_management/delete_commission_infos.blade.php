@extends('layouts.app')
@section('title', $title)
@section('style')

@endsection
@section('content')
    <div class="progress-management-delete pt-3">
        @section('header-progress')
            {{ trans('progress_management.delete_commission_infos.title') }}
        @endsection
        <table class="table-progress w-100 pt">
            <tbody>
            <tr class="text-center">
                <th class="align-middle p-1">{{ trans('progress_management.delete_commission_infos.file_id') }}</th>
                <th class="align-middle p-1">{{ trans('progress_management.delete_commission_infos.file_name') }}</th>
                <th class="align-middle p-1">{{ trans('progress_management.delete_commission_infos.file_upload_date') }}</th>
            </tr>
            <tr>
                <td class="align-middle p-1 text-center">{{$file->id}}</td>
                <td class="align-middle p-1">{{$file->original_file_name}}</td>
                <td class="align-middle p-1 text-center">{{$file->import_date}}</td>
            </tr>
            </tbody>
        </table>
        <div>
            <div class="title-progress d-flex justify-content-md-between flex-column flex-md-row">
                <h2>{{ __('progress_management.delete_commission_infos.label_title') }}</h2>
                <p>{{ __('progress_management.delete_commission_infos.label_guide') }}</p>
            </div>
            @if(session()->has('error'))
                <div class="box__mess box--error">
                    {!! session('error') !!}
                </div>
            @endif
            @if(session()->has('success'))
                <div class="box__mess box--success">
                    {!! session('success') !!}
                </div>
            @endif
            <form method="post" id="delete_commission_infos" action="{{ URL::route('post.progress.management.delete.commission.infos', $file->id) }}">
                {{ csrf_field() }}
                <table class="table-progress w-100">
                    <tr>
                        <th>{{ __('progress_management.delete_commission_infos.label_commission_id') }}</th>
                        <td>
                            <textarea class="form-control" name="delete_ids" rows="10">{{ old('delete_ids') }}</textarea>
                        </td>
                    </tr>
                </table>
                <button class="btn btn--gradient-green mt-2" type="button" id="deleteButton">{{ __('progress_management.delete_commission_infos.button_submit') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script>
        var msg = "@lang('progress_management.delete_commission_infos.confirm_request')";
        var confirm = "@lang('support.confirm')";
        var cancel = "@lang('support.cancel')";
    </script>
    <script src="{{ mix('js/pages/delete_commission_infos.js') }}"></script>
@endsection
