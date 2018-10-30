{{-- Affiliation correspond view --}}
<div class="form-category mb-4">
    <label class="form-category__label">{{ trans('affiliation_detail.corresponding_history_information') }}</label>
    <div class="form-category__body clearfix">
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.responders') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                @php
                    $selected = \Auth::user()->id ? \Auth::user()->id : 1;
                @endphp
                <select class="custom-select my-1 mr-sm-2" name="data[affiliation_correspond][responders]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($userList))
                        @foreach($userList as $key => $value)
                            <option @if($key == $selected) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.affiliation_correspond.responders'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.corresponding_contents') }}<span class="text-danger">*</span></label>
            <div class="col-12 col-sm-9">
                <textarea rows="5" maxlength="1000" class="form-control" data-rule-required="true" name="data[affiliation_correspond][corresponding_contens]"></textarea>
                @include('element.error_line', ['attribute' => 'data.affiliation_correspond.corresponding_contens'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.correspond_datetime') }}</label>
            <div class="col-12 col-sm-9 col-lg-6">
                <input type="text" class="form-control datetimepicker" name="data[affiliation_correspond][correspond_datetime]" size="1000" maxlength="1000" value="{{ date('Y/m/d H:i') }}">
                @include('element.error_line', ['attribute' => 'data.affiliation_correspond.correspond_datetime'])
            </div>
        </div>
        <table class="table table-list table-bordered mt-4">
            <thead>
            <tr>
                <th align="center" style="width:10%;">{{ trans('affiliation_detail.correspond_no') }}</th>
                <th align="center">{{ trans('affiliation_detail.responders') }}</th>
                <th align="center">{{ trans('affiliation_detail.correspond_datetime') }}</th>
            </tr>

            <tr class="bg-primary-lighter">
                <th align="center" colspan="3">{{ trans('affiliation_detail.corresponding_contents') }}</th>
            </tr>
            </thead>
            <tbody>
            @if($historyData->count() > 0)
                @foreach($historyData as $key => $value)
                    <tr>
                        <td align="center"><a href="javascript:void(0);" class="affiliation_history_input" data-get-url="{{ route('affiliation.get.history.input', $value->id) }}" data-post-url="{{ route('affiliation.post.history.input', $value->id) }}">{{ $historyData->count() - $key }}</a></td>
                        <td align="left">{!! (!empty($userList)) ? $userList[$value->responders] : '' !!}</td>
                        <td align="center">{{ dateTimeFormat($value->correspond_datetime) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">{!!  nl2br($value->corresponding_contens) !!}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>

    </div>
</div>
