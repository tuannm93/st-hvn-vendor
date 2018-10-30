<div class="form-category mb-4" id="correspondsinfo">
    @include('demand.create.anchor_top')
    <label class="form-category__label"> @lang('demand_detail.corresponding_history_information')</label>
    <div class="form-category__body clearfix">
        <div class="form-table mb-4">
            <div class="row mx-0">
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.corresponding_person')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::select('demandCorrespond[responders]', $userDropDownList, $demand->demandCorrespond->responders ?? Auth::user()->id ?? '', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-lg-6 row m-0 p-0">
                    <label class="col-lg-6 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.corresponding_date_time')</label>
                    <div class="col-lg-6 py-2 form-table-cell">
                        {!! Form::text('demandCorrespond[correspond_datetime]', dateTimeNowFormat(), ['class' => 'form-control datetimepicker']) !!}
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <label class="col-lg-3 form__label--white-light font-weight-bold p-3 mb-0 form-table-cell">@lang('demand_detail.correspondence_contents')</label>
                <div class="col-lg-6 py-2 form-table-cell">
                    {!! Form::textarea('demandCorrespond[corresponding_contens]', isset($demand->corresponding_contens) ? $demand->corresponding_contens : '', ['class' => 'form-control', 'id' => 'demandCorrespondContent']) !!}
                    @if ($errors->has('corresponding_contens'))
                        <label class="invalid-feedback d-block">{{$errors->first('corresponding_contens')}}</label>
                    @elseif ($errors->has('demandCorrespond.corresponding_contens'))
                        <label class="invalid-feedback d-block">
                            {{$errors->first('demandCorrespond.corresponding_contens') == __('demand.validation_error.not_empty') ? __('demand.validation_error.corresponding_contens') : $errors->first('demandCorrespond.corresponding_contens')
                            }}
                        </label>
                    @endif
                </div>
                <div class="d-none d-lg-flex col-lg-3 form-table-cell"></div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-list table-bordered" >
                <thead>
                    <tr>
                        <th class="p-2 align-middle fix-w-100">No</th>
                        <th class="p-2 align-middle fix-w-100">@lang('demand_detail.person_in_charge')</th>
                        <th class="p-2 align-middle fix-button-w-120">@lang('demand_detail.corresponding_date_time')</th>
                        <th class="p-2 align-middle fix-w-250">@lang('demand_detail.correspondence_contents')</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($demand->demandCorresponds))
                    @forelse($demand->demandCorresponds as $key => $demandCorrespond)
                        <tr>
                            <td class="p-2 align-center w-5 text-center"><a class="popupHistory"
                                                                            style="cursor: pointer;text-decoration: underline; color: #f27b07"
                                                                            data-toggle="modal"
                                                                            data-url_data="{{ route('demand.history_input', $demandCorrespond->id) }}">
                                    {{ count($demand->demandCorresponds) - $key }}
                                </a>
                            </td>
                            <td class="p-2 align-middle w-10" id="user-{{ $demandCorrespond->id }}">{{ $demandCorrespond->user_name }}</td>
                            <td class="p-2 align-middle w-10 text-center" id="date-time-{{ $demandCorrespond->id }}">{{ $demandCorrespond->correspond_date_time_format }}</td>
                            <td id="content-{{ $demandCorrespond->id}}" class="p-2 align-middle"><p class="fix-world-break-demand mb-0">{!! nl2br($demandCorrespond->corresponding_contens) !!}</p></td>
                        </tr>

                    @empty
                        <tr>
                            <td class="p-2 align-middle" colspan="4">@lang('demand_detail.there_is_no_record')</td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
