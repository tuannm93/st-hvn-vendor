{{-- Affiliation info view --}}
<div class="form-category mb-4">
    <label class="form-category__label">{{ trans('affiliation_detail.federation_store_information_label') }}</label>
    <div class="form-category__body clearfix">
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.legal_person') }}・{{ trans('affiliation_detail.personal') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="corp_kind_1" name="data[m_corps][corp_kind]" class="custom-control-input" @if(old('data.m_corps.corp_kind') == "Corp") checked @endif value="Corp">
                    <label class="custom-control-label" for="corp_kind_1">{{ trans('affiliation_detail.legal_person') }}</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="corp_kind_2" name="data[m_corps][corp_kind]" class="custom-control-input" @if(old('data.m_corps.corp_kind') == "Person") checked @endif value="Person">
                    <label class="custom-control-label" for="corp_kind_2">{{ trans('affiliation_detail.personal') }}</label>
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.corp_kind'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.capital_stock') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control" type="text" id="capital_stock" name="data[affiliation_infos][capital_stock]" size="50" maxlength="50" value="{{ old('data.affiliation_infos.capital_stock') }}">
                    <label class="form-group__sub-label" for="capital_stock">
                        円
                    </label>
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.capital_stock'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.employees') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control" type="text" id="employees" name="data[affiliation_infos][employees]" maxlength="50" size="50" value="{{ old('data.affiliation_infos.employees') }}">
                    @include('element.error_line', ['attribute' => 'data.affiliation_infos.employees'])
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.play') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="listed_kind_check" name="data[affiliation_infos][listed_kind]" @if(old('data.m_corps.listed_kind_uncheck') === "listed") checked @endif class="custom-control-input" value="listed">
                    <label class="custom-control-label" for="listed_kind_check">{{ trans('affiliation_detail.play') }}</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="listed_kind_uncheck" name="data[affiliation_infos][listed_kind]" @if(old('data.m_corps.listed_kind_uncheck') === "unlisted") checked @endif class="custom-control-input" value="unlisted">
                    <label class="custom-control-label" for="listed_kind_uncheck">{{ trans('affiliation_detail.non_play') }}</label>
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.listed_kind'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.default_tax') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="default_tax" name="data[affiliation_infos][default_tax]" @if(old('data.affiliation_infos.default_tax') == 1) checked @endif value="1">
                    <label class="custom-control-label" for="default_tax"></label>
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.default_tax'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.max_commission') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control" type="text" id="max_commission" name="data[affiliation_infos][max_commission]" size="50" maxlength="50" value="{{ old('data.affiliation_infos.max_commission') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.max_commission'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.collection_method') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[affiliation_infos][collection_method]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($collectionMethod))
                        @foreach($collectionMethod as $key => $value)
                            <option @if(old('data.affiliation_infos.collection_method') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.collection_method'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.collection_method_others') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control" type="text" id="collection_method_others" name="data[affiliation_infos][collection_method_others]" size="100" maxlength="100" value="{{ old('data.affiliation_infos.collection_method_others') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.collection_method_others'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.credit_limit_outstanding') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <label class="form-control-plaintext">{{ trans('affiliation_detail.credit_limit_outstanding_show') }}</label>
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.credit_limit') }}<span class="text-danger">*</span>
            </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    @if(!in_array($userRole, $roleAdmin1))
                        <input class="form-control" type="hidden" id="credit_limit" name="data[affiliation_infos][credit_limit]" data-rule-number="true" value="0" data-rule-required="true">
                        <label>0 円</label>
                    @else
                        <input class="form-control" type="text" id="credit_limit" name="data[affiliation_infos][credit_limit]" data-rule-number="true" data-rule-required="true" size="100" maxlength="100" value="{{ old('data.affiliation_infos.credit_limit') }}">
                        <label class="form-group__sub-label" for="credit_limit">
                            円
                        </label>
                    @endif
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.credit_limit'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.add_month_credit') }}<span class="text-danger">*</span>
            </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    @if(in_array($userRole, $hiddenAttributeRoleGeneral))
                        <input class="form-control" type="hidden" id="add_month_credit" name="data[affiliation_infos][add_month_credit]" data-rule-number="true" data-rule-required="true" size="100" maxlength="100" value="0">
                    @else
                        <input class="form-control" type="text" id="add_month_credit" name="data[affiliation_infos][add_month_credit]" data-rule-number="true" data-rule-required="true" size="100" maxlength="100" value="{{ old('data.affiliation_infos.add_month_credit') }}">
                        <label class="form-group__sub-label" for="add_month_credit">
                            円
                        </label>
                    @endif
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.add_month_credit'])
            </div>
            <div class="col-12 col-sm-9 col-lg-4">
                <div class="custom-control custom-checkbox mt-2">
                    @if(in_array($userRole, $hiddenAttributeRoleGeneral))
                        <input class="form-control" type="hidden" id="allow_credit_mail_send" name="data[affiliation_infos][allow_credit_mail_send]" size="100" maxlength="100" value="0">
                    @else
                        <input type="checkbox" class="custom-control-input" id="allow_credit_mail_send" name="data[affiliation_infos][allow_credit_mail_send]" @if(old('data.affiliation_infos.allow_credit_mail_send') == 1) checked @endif value="1">
                        <label class="custom-control-label" for="allow_credit_mail_send">{{ trans('affiliation_detail.allow_credit_mail_send') }}</label>
                    @endif
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.allow_credit_mail_send'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.virtual_account') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    @if(in_array($userRole, $hiddenAttributeRoleGeneral))
                        <input class="form-control" type="hidden" id="virtual_account" name="data[affiliation_infos][virtual_account]" data-rule-number="true" size="7" maxlength="7" value="">
                    @else
                        <input class="form-control" type="text" id="virtual_account" name="data[affiliation_infos][virtual_account]" data-rule-number="true" size="7" maxlength="7" value="{{ old('data.affiliation_infos.virtual_account') }}">
                    @endif
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.virtual_account'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.liability_insurance') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[affiliation_infos][liability_insurance]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($liabilityInsurance))
                        @foreach($liabilityInsurance as $key => $value)
                            <option @if(old('data.affiliation_infos.liability_insurance') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.liability_insurance'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_follow_date') }}1</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control datepicker" type="text" id="reg_follow_date1" name="data[affiliation_infos][reg_follow_date1]" size="10" maxlength="10" value="{{ old('data.affiliation_infos.reg_follow_date1') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.reg_follow_date1'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_follow_date') }}2</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control datepicker" type="text" id="reg_follow_date2" name="data[affiliation_infos][reg_follow_date2]" size="10" maxlength="10" value="{{ old('data.affiliation_infos.reg_follow_date2') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.reg_follow_date2'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_follow_date') }}3</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control datepicker" type="text" id="reg_follow_date3" name="data[affiliation_infos][reg_follow_date3]" size="10" maxlength="10" value="{{ old('data.affiliation_infos.reg_follow_date3') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.reg_follow_date3'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.waste_collect_oath') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[affiliation_infos][waste_collect_oath]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($wasteCollectOath))
                        @foreach($wasteCollectOath as $key => $value)
                            <option @if(old('data.affiliation_infos.waste_collect_oath') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.waste_collect_oath'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.transfer_name') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control" type="text" id="transfer_name" name="data[affiliation_infos][transfer_name]" size="100" maxlength="100" value="{{ old('data.affiliation_infos.transfer_name') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.transfer_name'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold"></label>
            <div class="col-12 col-sm-9">
                <div class="form-inline">
                    <label class="mr-2 col-auto">{{ trans('affiliation_detail.commission_category_filter') }}: </label>
                    <input class="form-control col-10 col-md-5" id="commission-category-filter" type="search" aria-label="Search">
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.agency_stop_category') }}</label>
            <div class="col-12 col-sm-9">
                <div class="form-inline table-responsive">
                    <table cellpadding="0">
                        <tr>
                            <td>
                                <select name="data[m_corps][s1]" id="s1" size="10" multiple="multiple" style="width: 250px">
                                    @if(count($stopCategoryList) > 0)
                                        @foreach ($stopCategoryList as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td align="center">
                                <input type="button" class="function-button" style="width:45px" name="right" id="right" value="≫" /><br /><br />
                                <input type="button" class="function-button" style="width:45px" name="left" id="left" value="≪" />
                            </td>
                            <td>
                                <select name="data[stop_category][]" id="s2" size="10" multiple="multiple" style="width: 250px">
                                    @if(count($stopCategory) > 0)
                                        @foreach ($stopCategory as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.claim_count') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[affiliation_infos][claim_count]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($claimCount))
                        @foreach($claimCount as $key => $value)
                            <option @if(old('data.affiliation_infos.claim_count') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.claim_count'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.claim_history') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <textarea class="form-control" rows="5" maxlength="1000" name="data[affiliation_infos][claim_history]">{{ old('data.affiliation_infos.claim_history') }}</textarea>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.claim_history'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.prog_send_method') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[m_corps][prog_send_method]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($progSendMethod))
                        @foreach($progSendMethod as $key => $value)
                            <option @if(old('data.m_corps.prog_send_method') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.prog_send_method'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.prog_send_fax') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control" type="text" id="prog_send_fax" name="data[m_corps][prog_send_fax]" size="500" maxlength="500" value="{{ old('data.m_corps.prog_send_fax') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.prog_send_fax'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.prog_send_mail_address') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2 ">
                    <input class="form-control multiple-email-validation" type="text" id="prog_send_mail_address" name="data[m_corps][prog_send_mail_address]" size="500" maxlength="500" value="{{ old('data.m_corps.prog_send_mail_address') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.prog_send_mail_address'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.prog_irregular') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <textarea class="form-control" rows="5" maxlength="1000" name="data[m_corps][prog_irregular]">{{ old('data.m_corps.prog_irregular') }}</textarea>
            </div>
            @include('element.error_line', ['attribute' => 'data.m_corps.prog_irregular'])
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.special_agreement_check') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="special_agreement_check" name="data[m_corps][special_agreement_check]" @if(old('data.m_corps.special_agreement_check') == 1) checked @endif value="1">
                    <label class="custom-control-label" for="special_agreement_check"></label>
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.special_agreement_check'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.bill_send_method') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[m_corps][bill_send_method]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($billSendMethod))
                        @foreach($billSendMethod as $key => $value)
                            <option @if(old('data.m_corps.bill_send_method') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.bill_send_method'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.bill_send_address') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control" type="text" id="bill_send_address" size="500" maxlength="500" name="data[m_corps][bill_send_address]" value="{{ old('data.m_corps.bill_send_address') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.bill_send_address'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.bill_irregular') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <textarea class="form-control" rows="5" maxlength="1000" name="data[m_corps][bill_irregular]">{{ old('data.m_corps.bill_irregular') }}</textarea>
            </div>
            @include('element.error_line', ['attribute' => 'data.m_corps.bill_irregular'])
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.special_agreement') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control" type="text" id="special_agreement" size="1000" maxlength="1000" name="data[m_corps][special_agreement]" value="{{ old('data.m_corps.special_agreement') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.special_agreement'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.development_response') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                @foreach($developmentResponse as $key => $value)
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="development_response_{{ $key }}" @if(in_array($key, $developmentResponseChecked)) checked @endif name="data[m_corp_subs][development_response][]" value="{{ $key }}">
                        <label class="custom-control-label" for="development_response_{{ $key }}">{{ $value }}</label>
                    </div>
                @endforeach
                @include('element.error_line', ['attribute' => 'data.m_corp_subs.development_response'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.order_fail_date') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control datepicker" type="text" size="10" maxlength="10" id="order_fail_date" name="data[m_corps][order_fail_date]" value="{{ old('data.m_corps.order_fail_date') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.order_fail_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.geocode_lat') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control" type="text" id="geocode_lat" size="12" maxlength="12" name="data[m_corps][geocode_lat]" value="{{ old('data.m_corps.geocode_lat') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.geocode_lat'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.geocode_long') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-group mb-2">
                    <input class="form-control" type="text" id="geocode_long" size="13" maxlength="13" name="data[m_corps][geocode_long]" value="{{ old('data.m_corps.geocode_long') }}">
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.geocode_long'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.remarks') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <textarea class="form-control" rows="5" maxlength="5000" name="data[m_corps][note]">{{ old('data.m_corps.note') }}</textarea>
                @include('element.error_line', ['attribute' => 'data.m_corps.note'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.advertising_status') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[m_corps][advertising_status]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($advertisingStatus))
                        @foreach($advertisingStatus as $key => $value)
                            <option @if(old('data.m_corps.advertising_status') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.advertising_status'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.advertising_send_date') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <input class="form-control datepicker" type="text" id="advertising_send_date" name="data[m_corps][advertising_send_date]" value="{{ old('data.m_corps.advertising_send_date') }}">
                @include('element.error_line', ['attribute' => 'data.m_corps.advertising_send_date'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.payment_site') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <select class="custom-select my-1" name="data[m_corps][payment_site]">
                    <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                    @if(!empty($paymentSite))
                        @foreach($paymentSite as $key => $value)
                            <option @if(old('data.m_corps.payment_site') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @include('element.error_line', ['attribute' => 'data.m_corps.payment_site'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.commission_count') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.weekly_commission_count') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.orders_count') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.orders_rate') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.construction_unit_price') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.commission_unit_price') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold"></label>
            <div class="col-12 col-sm-9">
                <table border="1" class="table table-list table-bordered">
                    <thead>
                    <tr>
                        <th>{{ trans('affiliation_detail.affiliation_stats_list_genre_name') }}</th>
                        <th>{{ trans('affiliation_detail.affiliation_stats_list_commission_count_category') }}<br>{{ trans('affiliation_detail.affiliation_stats_list_grand_total') }}</th>
                        <th>{{ trans('affiliation_detail.affiliation_stats_list_orders_count_category') }}<br>{{ trans('affiliation_detail.affiliation_stats_list_grand_total') }}</th>
                        <th>{{ trans('affiliation_detail.affiliation_stats_list_commission_unit_price_category') }}<br>{{ trans('affiliation_detail.affiliation_stats_list_past_year') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_info') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <input type="text" class="form-control" size="40" maxlength="40" name="data[affiliation_infos][reg_info]" value="{{ old('data.affiliation_infos.reg_info') }}">
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.reg_info'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reg_pdf_path') }}</label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="form-row align-items-center">
                    <div class="col-auto my-1">
                        <input type="file" name="data[affiliation_infos][reg_pdf_path]" value="{{ old('data.affiliation_infos.reg_pdf_path') }}">
                    </div>
                </div>
                @if(session('pdf_mess'))
                    <p><span class="text-danger">{{ session('pdf_mess') }}</span></p>
                @endif
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.reg_pdf_path'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.reference') }}</label>
            <div class="col-12 col-sm-9 col-lg-5"></div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.attention') }} </label>
            <div class="col-12 col-sm-9">
                <textarea class="form-control" rows="5" maxlength="1000" name="data[affiliation_infos][attention]">{{ old('data.affiliation_infos.attention') }}</textarea>
                @include('element.error_line', ['attribute' => 'data.affiliation_infos.attention'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.accept_check') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                <div class="custom-control custom-checkbox">
                    <input disabled type="checkbox" class="custom-control-input" id="accept_check" name="data[m_corps][accept_check]" @if(old('data.m_corps.accept_check') == 1) checked @endif value="1">
                    <label class="custom-control-label" for="accept_check"></label>
                </div>
                @include('element.error_line', ['attribute' => 'data.m_corps.accept_check'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.last_antisocial_check') }} </label>
            <div class="col-12 col-sm-9 col-lg-5">
                @if(in_array($userRole, $hiddenAttributeRoleGeneral))
                    <input class="form-control" type="hidden" id="last_antisocial_check" name="data[m_corps][last_antisocial_check]" size="100" maxlength="100" value="None">
                    <input type="text" readonly class="form-control-plaintext" value="未実施">
                @else
                <select class="custom-select my-1" name="data[m_corps][last_antisocial_check]">
                    @if(!empty($antisocialResultList))
                        @foreach($antisocialResultList as $key => $value)
                            <option @if(old('data.m_corps.last_antisocial_check') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
                @endif
                @include('element.error_line', ['attribute' => 'data.m_corps.last_antisocial_check'])
            </div>
        </div>
        <div class="form-group row mb-2">
            <label class="col-sm-3 col-form-label font-weight-bold">{{ trans('affiliation_detail.check_history') }} </label>
            <div class="col-12 col-sm-auto">
                <table border="1" class="table table-list table-bordered">
                    <thead>
                    <tr>
                        <th>{{ trans('affiliation_detail.check_history_date') }}</th>
                        <th>{{ trans('affiliation_detail.check_history_created_user') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="col-12 col-sm-auto">
                <table border="1" class="table table-list table-bordered">
                    <thead>
                    <tr>
                        <th>{{ trans('affiliation_detail.reputation_check_date') }}</th>
                        <th>{{ trans('affiliation_detail.reputation_check_created_user') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="row justify-content-center">
            <button type="button" data-url="{{ URL::route('affiliation.index') }}" data-session="{{ route('affiliation.set.session') }}" class="btn btn--gradient-default form-btn return-link">{{ trans('affiliation_detail.return_link') }}</button>
            <input type="submit" id="register_button" class="btn btn--gradient-green form-btn d-inline-block" value="{{ trans('affiliation_detail.registration_button') }}">
        </div>
    </div>
</div>
