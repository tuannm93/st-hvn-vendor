<div class="custom-scroll-x">
    <table id="{{$idTable}}" class="table custom-border">
        <thead>
            <tr class="text-center bg-yellow-light">
                <th class="p-1 align-middle fix-w-150">{{__('affiliation_resign.col_genre_name')}}</th>
                <th class="p-1 align-middle fix-w-150">{{__('affiliation_resign.col_category_name')}}</th>
                <th class="p-1 align-middle fix-w-100">
                    {{__('affiliation_resign.col_fee')}}
                    <span class="text-danger">{{__('affiliation_resign.required_mark')}}</span>
                </th>
                <th class="p-1 align-middle fix-w-100">
                    {{__('affiliation_resign.col_unit')}}
                    <span class="text-danger">{{__('affiliation_resign.required_mark')}}</span>
                </th>
                <th class="p-1 align-middle fix-w-100">{{__('affiliation_resign.col_order_from')}}</th>
                <th class="p-1 align-middle fix-w-150">{{__('affiliation_resign.col_remark')}}</th>
                <th class="p-1 align-middle fix-w-150">{{__('affiliation_resign.col_reason')}}
                    <span class="text-danger">{{__('affiliation_resign.required_mark')}}</span>
                </th>
                <th class="p-1 align-middle">{{__('affiliation_resign.col_application_check')}}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $key => $obj) 
            <tr>
                <td class="p-1 align-middle fix-w-150">
                    <div class="dataForm">
                        <input title="" type="hidden" name="id" value="{{!empty($obj['id']) ? $obj['id'] : ''}}">
                        <input title="" type="hidden" name="corp_id"
                            value="{{!empty($obj['corp_id']) ? $obj['corp_id'] : ''}}">
                        <input title="" type="hidden" name="genre_id"
                            value="{{!empty($obj['genre_id']) ? $obj['genre_id'] : ''}}">
                        <input title="" type="hidden" name="category_id"
                            value="{{!empty($obj['category_id']) ? $obj['category_id'] : ''}}">
                        <input title="" type="hidden" name="modified"
                            value="{{!empty($obj['modified']) ? $obj['modified'] : ''}}">
                    </div>
                    {{$obj['genre_name']}}
                </td>
                <td class="p-1 align-middle fix-w-150">{{$obj['category_name']}}</td>
                <td class="p-1 align-middle fix-w-100">
                    @if($isConclusionBase)
                        {{Form::input('text', 'order_fee', !empty($obj['order_fee']) ? $obj['order_fee']:'', ['id' => 'order_fee_' . $key, 'class' => 'p-1 form-control text-right', 'maxlength' => '10', 'data-rule-numberAllSize' => 'true'])}}
                    @else
                        {{Form::input('text', 'order_fee', !empty($obj['introduce_fee']) ? $obj['introduce_fee']:'', ['id' => 'order_fee_' . $key, 'class' => 'p-1 form-control text-right', 'maxlength' => '10', 'data-rule-numberAllSize' => 'true'])}}
                    @endif
                </td>
                <td class="p-1 align-middle fix-w-100">
                    @if($obj['corp_commission_type'] != 2)
                        {{Form::select('order_fee_unit', $listFeeUnit, !empty($obj['order_fee_unit']) ? $obj['order_fee_unit']: '', ['class' => 'p-1 form-control fix-height-select'])}}
                    @else
                        {{Form::select('order_fee_unit', $listFeeUnit, 0, ['class' => 'p-1 form-control fix-height-select' , 'disabled' => 'disabled'])}}
                    @endif
                </td>
                <td class="p-1 align-middle fix-w-100">
                    {{Form::select('corp_commission_type', $listCorpCommisionType, $obj['corp_commission_type'], ['class' => 'corp_commission_type p-1 form-control fix-height-select'])}}
                </td>
                <td class="p-1 align-middle fix-w-100">
                    {{Form::textarea('note', !empty($obj['note']) ? $obj['note'] : '', ['rows'=> 2, 'class' => 'p-1 form-control', 'maxlength' => '1000'])}}
                </td>
                <td class="p-1 align-middle fix-w-100">{{Form::textarea('application_reason', '', ['rows'=> 2, 'class' => 'p-1 form-control', 'maxlength' => '1000'])}}</td>
                <td class="p-1 align-middle fix-w-50 text-center">
                    <input type="checkbox" class="cbApp" name="application_check" value="1">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>