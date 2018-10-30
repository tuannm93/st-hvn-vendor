{{-- Affiliation correspond view --}}
<div class="form-category mb-4">
    <label class="form-category__label">{{ trans('affiliation_detail.corresponding_history_information') }}</label>
    <div class="form-category__body clearfix">
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.responders') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1 mr-sm-2" name="data[affiliation_correspond][responders]">
                    @if(!empty($userList))
                        @foreach($userList as $key => $value)
                            <option @if($oldResponders == $key) selected @elseif($key == Auth::getUser()->id) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.affiliation_correspond.responders'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.corresponding_contents') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9">
                @php $content = isset($correspondingContents) ? $correspondingContents : '初回登録'; @endphp
                <textarea rows="5" maxlength="1000" class="form-control" name="data[affiliation_correspond][corresponding_contens]" data-rule-required="true">{{ $content }}</textarea>
                @include('element.error_line', ['attribute' => 'data.affiliation_correspond.corresponding_contens'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.correspond_datetime') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <input type="text" class="form-control datetimepicker" name="data[affiliation_correspond][correspond_datetime]" size="1000" maxlength="1000" value="{{ date('Y/m/d H:i') }}">
                @include('element.error_line', ['attribute' => 'data.affiliation_correspond.correspond_datetime'])
            </div>
        </div>
        <table class="table table-list table-bordered mt-4">
            <thead>
            <tr class="bg-primary-lighter">
                <th align="center" style="width:10%;">{{ trans('affiliation_detail.correspond_no') }}</th>
                <th align="center">{{ trans('affiliation_detail.responders') }}</th>
                <th align="center">{{ trans('affiliation_detail.correspond_datetime') }}</th>
            </tr>

            <tr class="bg-primary-lighter">
                <th align="center" colspan="3">{{ trans('affiliation_detail.corresponding_contents') }}</th>
            </tr>
            </thead>
        </table>

    </div>
</div>
