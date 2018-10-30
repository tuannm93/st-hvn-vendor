<div class="text-center mb-3">
    {{ Form::input('button', 'all_regist', trans('corp_target_area_select.select_all_areas'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.update_all_data'), 'class' => 'btn btn--gradient-orange col-12 col-sm-4 col-md-2 mb-2 mb-sm-0', 'id'=>'all_regist']) }}
    {{ Form::input('button', 'all_remove', trans('corp_target_area_select.delete_all_areas'), ['data-corp-id' => $corpId, 'data-url' => route('corp_target_area_select.delete_all_data'), 'class' => 'btn btn--gradient-orange col-12 col-sm-4 col-md-2 ml-sm-2', 'id'=>'all_remove']) }}
</div>
<div class="form-group row">
@php
foreach ($listTargetArea as $key => $val):
    $check = false;
    if (!empty($val['corp_id'])) {
        $check = true;
    }
    echo '<div class="col-6 col-lg-3 form-check">';
    echo '<div class="custom-control custom-checkbox">';
    echo Form::input('checkbox', 'data[jis_cd][]', $val['jis_cd'], ['id' => 'jis_cd' . $key, 'class' => 'check_group custom-control-input', 'checked' => $check]);
    echo Form::label('jis_cd' . $key, $val['address2'], ['class'=>'custom-control-label sss']);
    echo Form::input('hidden', 'data[' . $val['jis_cd'] . ']', '', ['id' => $val['jis_cd']]);
    echo '</div>';
    echo '</div>';
endforeach;
@endphp
</div>
