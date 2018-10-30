
<div class="row">
    @foreach ($results as $key => $val)
        @if(!empty($val['corp_id']))
            @php $check = 'checked' @endphp
        @else
            @php $check = '' @endphp
        @endif
        <div class="col-6 col-lg-3">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" value="{{ $val['jis_cd'] }}" {{ $check }} name="jis_cd[]"
                            id="jis_cd{{ $key }}" class="custom-control-input">
                <label class="custom-control-label" for="jis_cd{{ $key }}">{{ $val['address2'] }}</label>
            </div>
            <input type="hidden" value="{{ $val['corp_id'] }}" name="{{ $val['jis_cd'] }}"
                       id="jis_cd{{ $key }}">
        </div>
    @endforeach
</div>

