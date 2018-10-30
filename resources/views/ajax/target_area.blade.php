<div class="form-group row">
    <?php
    foreach ($list as $key => $val):
        $check = false;
        if(!empty($val['m_target_area_id'])) {
            $check = true;
        }
        echo '<div class="col-6 col-lg-3 form-check">';
        echo '<div class="custom-control custom-checkbox">';
        echo Form::input('checkbox', 'data[jis_cd][]', $val['jis_cd'], ['id'=>'jis_cd'.$key, 'class'=>'check_group custom-control-input', 'checked'=>$check]);
        echo Form::label('jis_cd'.$key, $val['address2'], ['class'=>'custom-control-label']);
        echo Form::input('hidden', 'data['.$val['jis_cd'].']', '', ['id'=>$val['jis_cd']]);
        echo '</div>';
        echo '</div>';
    endforeach;
    ?>
</div>
