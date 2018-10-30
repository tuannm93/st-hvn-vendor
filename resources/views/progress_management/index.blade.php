@extends('layouts.app')

@section('content')
<div class="progress-management-index">
    <div class="text-sm-right mt-2">
        <a href="{{ route('progress.item_edit') }}" target="_blank" class="highlight-link text--underline">@lang('progress_management.index.item_edit_link')</a>
    </div>
    <div class="progress-management-badge my-3">
        <label class="font-weight-bold fs-15 mb-0 ml-2 py-2">@lang('progress_management.index.progress_management')</label>
    </div>
    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissible fade show">
          {!! Session::get('message') !!}
        </div>
    @endif
    @if($progFiles->isEmpty())
        <p class="text-center">@lang('progress_management.index.empty_data')</p>
    @else
        <div class="custom-scroll-x">
            <table class="table custom-border">
                <thead>
                    <tr class="text-center bg-yellow-light">
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.detail')</th>
                        <th class="p-1 fix-w-50 align-middle">No.</th>
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.filename')</th>
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.import_date')</th>
                        <th class="p-1 fix-w-50 align-middle">@lang('progress_management.index.release')</th>
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.output')</th>
                        @if(auth()->user()->isSystem())
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.delete')</th>
                        @endif
                        @if(auth()->user()->isPoster())
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.progress_addition')</th>
                        <th class="p-1 fix-w-100 align-middle">@lang('progress_management.index.delete_progress')</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($progFiles as $file)
                        <tr class="text-center">
                            <td class="p-1 fix-w-100 align-middle">
                                <a href="{{ route('progress.corpIndex', $file->id) }}" class="btn btn--gradient-orange  py-1 col-12" target="_blank">@lang('progress_management.index.detail')</a>
                            </td>
                            <td class="p-1 fix-w-50 align-middle">{{ $file->id }}</td>
                            <td class="p-1 fix-w-100 align-middle text-left">
                                <a href="{{ route('progress.corpIndex', $file->id) }}" class="font-weight-bold highlight-link" target="_blank">{{ $file->original_file_name }}</a>
                            </td>
                            <td class="p-1 fix-w-100 align-middle">{{ $file->import_date }}</td>
                            <td class="p-1 fix-w-50 align-middle">
                                @if(!empty($file->release_flag) && $file->release_flag == 1)
                                <i class="fa fa-circle-thin fa-lg" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td class="p-1 fix-w-100 align-middle">
                                <a href="{{ route('progress.corpIndex.progcorp.outcsv.file', $file->id) }}" class="btn btn--gradient-orange py-1 col-12" target="_blank">@lang('progress_management.index.output')</a>
                            </td>
                            @if(auth()->user()->isSystem())
                            <td class="p-1 fix-w-100 align-middle">
                                <form method="POST" action="{{ route('progress.file.delete', $file) }}">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn--gradient-gray  py-1 col-12  submitDeleteFile">@lang('progress_management.index.delete')</button>
                                </form>
                            </td>
                            @endif
                            @if(auth()->user()->isPoster())
                                <td class="p-1 fix-w-100 align-middle">
                                    <a target="_blank" href="{{ route('get.import.commission.infos', $file->id) }}" class="btn btn--gradient-green  py-1 col-12 ">@lang('progress_management.index.progress_addition')</a>
                                </td>
                                <td class="p-1 fix-w-100 align-middle">
                                    <a target="_blank" href="{{ route('get.progress.management.delete.commission.infos', $file->id) }}" class="btn btn--gradient-gray  py-1 col-12 ">@lang('progress_management.index.delete_progress')</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@section('script')
<script type="text/javascript">
    var confirmDelete = "{{ $confirmDelete }}";
</script>
<script type="text/javascript" src="{{ mix('js/utilities/st.common.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/pages/prog_index.js') }}"></script>
@endsection
