@extends('layouts.app')

@section('content')
    <div class="progress-management-admin">
            <input type="hidden" id="consTax" value="{{ $tax }}" />
            <table class="table custom-table pt-2">
                <thead>
                    <tr class="text-center">
                        <th class="align-middle border-0 p-1">@lang('progress_management.file')No</th>
                        <th class="align-middle border-0 p-1">@lang('progress_management.kmei_ten')</th>
                        <th class="align-middle border-0 p-1">@lang('progress_management.file')@lang('progress_management.name')</th>
                        <th class="align-middle border-0 p-1">@lang('progress_management.inport_time')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-yellow-light border-bottom">
                        <td class="align-middle border-bottom border-top-bold p-1 text-center">
                            {!! ($progCorp->progImportFile) ? $progCorp->progImportFile->id : '' !!}
                        </td>
                        <td class="align-middle border-bottom border-top-bold p-1">
                            {!! ($progCorp->mCorp) ? $progCorp->mCorp->official_corp_name : '' !!}
                        </td>
                        <td class="align-middle border-bottom border-top-bold p-1">
                            {!! ($progCorp->progImportFile) ? $progCorp->progImportFile->original_file_name : '' !!}
                        </td>
                        <td class="align-middle border-bottom border-top-bold p-1 text-center">
                            {!! ($progCorp->progImportFile) ? $progCorp->progImportFile->import_date : '' !!}
                        </td>
                    </tr>
                </tbody>
                </table>
            <div class="p-2 rounded status-badge text-right text--white">
                  {!! $guideTitle !!}
            </div>
            <br />
            <div class="font-weight-bold rounded text--white bar-success px-3 py-2 mb-4"  id="barSuccess">

                @lang('progress_management.update_message')
            </div>
            @if(Session::has('message'))
            <div class="font-weight-bold rounded text--white bar-success px-3 py-2 mb-4" role="alert" id="success-bar-2">
              {!! Session::get('message') !!}
            </div>
            @endif
        @if ($errors->any())
            <div class="box__mess box--error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="mt-2">
            <strong>{{ $pageInfo }}</strong>
        </div>
        @include('progress_management.tr_progdemandinfo',
                [
                    'pDemandInfos' => $pDemandInfos,
                    'commissionStatus' => $pmCommissionStatus, 
                    'diffFllags' => $diffFllags,
                    'hidClass' => $hidClass,
                    'commissionOrderFailReasonUpdate' => $cmOrderFailReasonUpdate,
                    'progCorp' => $progCorp
                ]
            )
        @include('progress_management.t_addDemand',
                [
                    'addDemandData' => $addDemandData,
                    'limitAddDemand' => $limitAddDemand,
                    'commissionStatus' => $pmCommissionStatus,
                    'hidClass' => $hidClass,
                    'commissionOrderFailReasonUpdate' => $cmOrderFailReasonUpdate,
                    'demandTypeUpdate' => $demandTypeUpdate
                ]
            )
    </div>
@endsection
@section('script')
<script>
    var hiddenStatus = '{{ $hidClass }}';
</script>

<script type="text/javascript" src="{{ mix('js/pages/admin_demand_detail.js') }}"></script>
<script>
    ProgressManagementAdminDemandDetail.init();
</script>
@endsection