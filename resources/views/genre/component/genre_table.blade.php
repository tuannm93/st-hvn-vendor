<div class="col-12 col-sm-6 col-md-4 block-item">
    <table class="table table-bordered table-genre mb-1">
        <tbody>
        {{ Form::input('hidden', 'data[MGenre][' . $key . '][id]', isset($val['id']) ? $val['id'] : "", ['id' => 'MGenre' . $key . 'Id']) }}
        {{ Form::input('hidden', 'data[MGenre][' . $key . '][modified]', isset($val['modified']) ? $val['modified'] : "", ['id' => 'modified' . $key]) }}
        <tr>
            <td class="text-left border-top-0 border-left-0 w-30 {{$val['registration_mediation'] != 0 ? 'bg-pink' : ''}}">{{ $val['id'] }}</td>
            <td class="text-left border-top-0 w-40 {{$val['registration_mediation'] != 0 ? 'bg-pink' : ''}}">{{ $val['genre_name'] }}</td>
            <td class="text-center border-top-0 border-right-0 w-30 {{$val['registration_mediation'] != 0 ? 'bg-pink' : ''}}">
                {{ Form::checkbox('data[MGenre][' . $key . '][registration_mediation]', 1, ($val['registration_mediation'] != 0) ? true : null,['id' => 'mgenre_registration_mediation' . $key, 'disabled'=>$acaAccount]) }}
            </td>
        </tr>
        <tr>
            <td class="text-left font-weight-bold border-left-0 w-30">{{ trans('genre.for_credit_calculation') }}<br />{{ trans('genre.unit_price') }}</td>
            <td colspan="2" class="text-center border-right-0 w-70">
                {{ Form::input('text', 'data[MGenre][' . $key . '][credit_unit_price]', $val['credit_unit_price'], ['id' => 'mgenre_credit_unit_price' . $key, 'class' => 'text-left form-control w-70 ml-auto mr-auto', 'disabled'=>$acaAccount, 'data-rule-number'=>'true', 'data-rule-required' => "true", 'data-error-container' => '#mgenre_credit_unit_price_feedback_' . $key ]) }}
                <div class="text-left w-70 ml-auto mr-auto unit-price-feedback" id="mgenre_credit_unit_price_feedback_{{$key}}"></div>
            </td>
        </tr>
        <tr>
            <td class="text-left border-bottom-0 font-weight-bold border-left-0 w-30">{{ trans('genre.auto_call_disabled') }}</td>
            <td class="text-center border-bottom-0 border-right-0 w-70" colspan="2">
                {{ Form::select('data[MGenre][' . $key . '][auto_call_flag]', $autoCallList, $val['auto_call_flag'], ['disabled'=>$acaAccount, 'class' => 'form-control w-auto ml-auto mr-auto']) }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
