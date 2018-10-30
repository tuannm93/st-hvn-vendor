<div class="row mb-1">
    <div class="col-md-6">
        <label class="col-form-label">{{ trans('antisocial_follow.total_number').$results->total().trans('antisocial_follow.matter') }}</label>
    </div>
    <div class="col-md-6">
        @if($isUpdateAuthority)
            <div class="text-sm-right">
                {{Form::input('button', 'checkAll', trans('antisocial_follow.select_all_member_stores'), array('data-mode'=>'0', 'id'=>'checkAll' ,'class'=>'btn btn--gradient-orange col-sm-5 mb-1 mb-sm-0'))}}
                {{Form::input('submit', 'update', trans('antisocial_follow.update_check'), array('disabled'=>true, 'id'=>'update' ,'class'=>'btn btn--gradient-green col-sm-5'))}}
            </div>
        @endif
    </div>
</div>
<table class="table custom-border add-pseudo-scroll-bar">
    <thead>
    <tr class="text-center bg-yellow-light">
        <th class="p-1 align-middle fix-w-100">{{ trans('antisocial_follow.merchant_id') }}</th>
        <th class="p-1 align-middle fix-w-100">{{ trans('antisocial_follow.company_name') }}</th>
        <th class="p-1 align-middle fix-w-100">{{ trans('antisocial_follow.last_time') }}</th>
        <th class="p-1 align-middle fix-w-50">{{ trans('antisocial_follow.scheduled_month') }}</th>
        <th class="p-1 align-middle fix-w-50">{{ trans('antisocial_follow.procedure_dial') }}</th>
        <?php if ($isUpdateAuthority) : ?>
        <th class="p-1 align-middle fix-w-50">{{ trans('antisocial_follow.confirmation') }}</th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody class="rsltData text-center">
    @if (isset($results) && count($results) > 0)
        @foreach($results as $key => $result)
            <tr class="hover">
                <td class="p-1 align-middle text-wrap fix-w-100">
                    <a target="_blank" href="{{ url('/affiliation/detail/'.$result->mcorp_id) }}" class="highlight-link">{{ $result->mcorp_id }}</a>
                </td>
                <td class="p-1 align-middle text-wrap text-left fix-w-100">{{ $result->official_corp_name }}</td>
                <td class="p-1 align-middle text-wrap fix-w-100">{{ $result->max }}</td>
                <td class="p-1 align-middle text-wrap fix-w-50">{{ $result->concat }}</td>
                <td class="p-1 align-middle text-wrap fix-w-100">@php echo (ctype_digit($result->commission_dial)) ? '<a href="'.checkDevice().$result->commission_dial.'" class="highlight-link">'.$result->commission_dial.'</a>' : ''; @endphp</td>
                @if($isUpdateAuthority)
                    <td class="p-1 align-middle fix-w-50">
                        {{ Form::checkbox('check[]', $result->mcorp_id, null,['id' => 'r_checkbox_'.$key]) }}
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
{{ $results->links('pagination.nextprevajax') }}
