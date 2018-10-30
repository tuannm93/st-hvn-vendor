<tr>
    <td width="150">{{__('demand.hearing_item')}}</td>
    <td width="600">&nbsp;</td>
</tr>
@foreach($data as $arr)
    <tr>
        <td>
            {{$arr['inquiry_name']}}
            {{Form::input('hidden', 'category_id', $arr['category_id'], ['class' => ''])}}
            {{Form::input('hidden', 'inquiry_name', $arr['inquiry_name'], ['class' => ''])}}
            {{Form::input('hidden', 'inquiry_id', $arr['id'], ['class' => ''])}}
        </td>
        <td>
            {{Form::select('answer_name',['' => __('demand.empty')] + $arr['answer'], '', ['class' => ''])}}
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            {{Form::textarea('answer_note', '', ['class' => ''])}}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr/>
        </td>
    </tr>
@endforeach;
