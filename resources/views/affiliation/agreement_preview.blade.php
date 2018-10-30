<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    @php
        $logined = Auth::user();
        $class = 'body-wrapper-default';
        if (Route::current()->getName() == 'bill.moneyCorrespond') {
            $class = 'body-wrapper-custom-1';
        } else if (Route::current()->getName() == 'auction.proposal') {
            $class = 'body-wrapper-custom-2';
        }
    @endphp
    <body>
        <div class="progress-block">
            <div class="progress"></div>
        </div>
        <div id="top">
            <div class="container">
                <div class="affiliation-agreement-preview">
                    <div class="row header-field mt-3">
                        <div class="col-sm-6 col-lg-auto">
                            <strong>@lang('agreement_preview.corp_name')：{{ $mCorp['official_corp_name'] }}</strong>
                        </div>
                        <div class="col-sm-6 col-lg-auto">
                            <strong>@lang('agreement_preview.corp_id')：{{ $mCorp['id'] }}</strong>
                        </div>
                    </div>
                    <div class="form-category mt-4">
                        <label class="form-category__label">@lang('agreement_preview.content')</label>
                        <div>
                            @if(!empty($corpAgreement['customize_agreement']))
                                {!! nl2br($corpAgreement['customize_agreement']) !!}
                            @else
                                {!! nl2br($corpAgreement['original_agreement']) !!}
                            @endif
                        </div>
                    </div>
                    <div class="form-category mt-4">
                        <label class="form-category__label">@lang('agreement_preview.info')</label>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.individual')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{ $checkCorpKind }}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.listing')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{ $checkListedKind }}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.tax')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if($mCorp['default_tax'] == true)
                                @lang('agreement_preview.delinquent')
                            @else
                                @lang('agreement_preview.no_delinquency')
                            @endif
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.capital')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if(!empty($mCorp['capital_stock']))
                                {{ $mCorp['capital_stock'] }} @lang('agreement_preview.circle')
                            @endif
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.employee_number')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if(!empty($mCorp['employees']))
                                {{ $mCorp['employees'] }} @lang('agreement_preview.man')
                            @endif
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.concern')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @lang('agreement_preview.representative')：{{ $mCorp['responsibility'] }}
                            @lang('agreement_preview.charge')：{{ $mCorp['corp_person'] }}
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.location')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            <div>〒{{ $mCorp['postcode'] }}</div>
                            {{ $address1 }}
                            {{ $mCorp['address2'] }}
                            {{ $mCorp['address3'] }}
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.head')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            <div>〒{{ $mCorp['representative_postcode'] }}</div>
                            {{ $representative }}
                            {{ $mCorp['representative_address2'] }}
                            {{ $mCorp['representative_address3'] }}
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.phone')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{ $mCorp['tel1'] }}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.pc_mail')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{ $mCorp['mailaddress_pc'] }}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.mobile_mail')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if(!empty($mCorp['mobile_mail_none']))
                                @lang('agreement_preview.dont_have')
                            @else
                                {{ $mobileTelType }}
                                {{ $mCorp['mailaddress_mobile'] }}
                            @endif
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.agency')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{ $mCorp['commission_dial'] }}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.method')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{$coordination}}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.fax_number')</div>
                        <div class="col-sm-8 p-2 p-sm-3">{{ $mCorp['fax'] }}</div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.24h')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if(!empty($mCorp['support24hour']))
                                @lang('agreement_preview.compatible')
                            @else
                                @lang('agreement_preview.other')
                            @endif
                            @if(!empty($mCorp['available_time_from']) || !empty($mCorp['available_time_to']))
                                {{ $mCorp['available_time_from'] . trans('common.wavy_seal') . $mCorp['available_time_to']}}
                            @endif
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.available_time')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if(!empty($mCorp['contactable_support24hour']))
                                @lang('agreement_preview.compatible')
                            @else
                                @lang('agreement_preview.other')
                            @endif
                            @if(!empty($mCorp['contactable_time_from']) || !empty($mCorp['contactable_time_to']))
                                {{ $mCorp['contactable_time_from'] . trans('common.wavy_seal') . $mCorp['contactable_time_to']}}
                            @endif
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.holiday')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @foreach($mCorpSubs as $value)
                                {{ '['. $value['item_name'] . '] '}}
                            @endforeach
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.refund_account')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            {{ $mCorp['refund_bank_name'] }}
                            {{ $mCorp['refund_branch_name'] }}<br>
                            {{ $mCorp['refund_account_type'] }}
                            {{ $mCorp['refund_account'] }}
                        </div>
                    </div>
                    <div class="row border-bottom mb-0 mx-0">
                        <div class="col-sm-2 p-2 p-sm-3 font-weight-bold bg-label">@lang('agreement_preview.support')</div>
                        <div class="col-sm-8 p-2 p-sm-3">
                            @if(!empty($mCorp['support_language_en']))
                                @lang('agreement_preview.english')
                            @endif
                            @if(!empty($mCorp['support_language_zh']))
                                    @lang('agreement_preview.china')
                            @endif
                            @if(!empty($mCorp['support_language_employees']))
                                    @lang('agreement_preview.available_employees') {{ $mCorp['support_language_employees'] }}
                            @endif
                        </div>
                    </div>
                    <div class="border-left-orange my-3">
                        <strong class="ml-2">@lang('agreement_preview.area_list')</strong>
                    </div>
                    <div class="corp_table bg-item-yellow-light border-gray">
                        <div class="row">
                        @if(!empty($corpAreas))
                            @foreach($corpAreas as $corpArea)
                                <div class="col-md-6 col-xl-4 my-2">
                                    <div class="form-inline pref_sub">
                                        @if($corpArea['rank'] == 0)
                                            <div class="w-25 text-center font-weight-bold py-2 pref_sub1" id="taiou_ {{$corpArea['id']}}">{!! __('agreement_preview.impossible') !!}</div>
                                        @elseif($corpArea['rank'] == 1)
                                            <div class="w-25 text-center font-weight-bold py-2 pref_sub1_part" id="taiou_ {{$corpArea['id']}}">{!! __('agreement_preview.patrial') !!}</div>
                                        @elseif($corpArea['rank'] == 2)
                                            <div class="w-25 text-center font-weight-bold py-2 pref_sub1_non" id="taiou_{{$corpArea['id']}}">{!! __('agreement_preview.region') !!}</div>
                                        @endif
                                        <div class="w-45 font-weight-bold pl-3 fs-16">{{ $corpArea['name'] }}</div>
                                        <div class="w-30 font-weight-bold pref_sub3">
                                            <a class="p-2 hover-link corpAreaBtn" data-url="{{ route('affiliation.agreement.searchCorpTargetArea', ['corpId' => $mCorp['id'], 'address1' => $corpArea['name']]) }}" id=" {{ $corpArea['name'] }}" tabindex="0">@lang('agreement_preview.area')≫</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        </div>
                    </div>
                    <div class="border-left-orange my-3">
                        <strong class="ml-2">@lang('agreement_preview.genre_list')</strong>
                    </div>
                    <div class="border-left-orange my-3">
                        <strong class="ml-2">@lang('agreement_preview.conclusion')</strong>
                    </div>
                    <div class="custom-scroll-x">
                        <table class="table custom-border">
                            <thead>
                                <tr class="text-center bg-yellow-light">
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.genre')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.category')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.expertise')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.fee')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.unit')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.remarks')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $key => $category)
                                    @if($category['corp_commission_type'] != 2)
                                        <tr id="row{{ $key }}">
                                            <td class="p-1 align-middle fix-w-50">{{$category['genre_name']}}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['category_name'] }}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['select_list'] }}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['order_fee'] }}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ getDivText('fee_div', $category['order_fee_unit']) }}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['note'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-left-orange my-3">
                        <strong class="ml-2">@lang('agreement_preview.base')</strong>
                    </div>
                    <div class="custom-scroll-x">
                        <table class="table custom-border">
                            <thead>
                                <tr class="text-center bg-yellow-light">
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.genre')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.category')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.expertise')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.fee')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.unit')</th>
                                    <th class="p-1 align-middle fix-w-50">@lang('agreement_preview.remarks')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $key => $category)
                                    @if($category['corp_commission_type'] == 2)
                                        <tr id="row{{ $key }}">
                                            <td class="p-1 align-middle fix-w-50">{{$category['genre_name']}}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['category_name'] }}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['select_list'] }}</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['introduce_fee'] }}</td>
                                            <td class="p-1 align-middle fix-w-50">@lang('agreement_preview.circle')</td>
                                            <td class="p-1 align-middle fix-w-50">{{ $category['note'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-left-orange my-3">
                        <strong class="ml-2">@lang('agreement_preview.contact')</strong>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" disabled @if($corpAgreement['accept_check']) checked @else  @endif>
                        <label class="custom-control-label"></label>
                    </div>
                    <div class="text-center">
                        <button class='btn btn--gradient-gray mb-5' onclick="window.close();">@lang('agreement_preview.close')</button>
                    </div>
                </div>
                <div class="modal affiliation-agreement-preview-modal fade" id="agreementPreview" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close fs-11" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body px-5">
                                <div class="agreement-support-content pt-2">
                                </div>
                            </div>
                            <div class="text-center my-3">
                                <button class="btn btn--gradient-gray closeBtn" type="button">@lang('support.closeBtn')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ mix('js/lib/jquery-ui.min.js') }}"></script>
        <script src="{{ mix('js/lib/jquery.ui.datepicker-ja.min.js') }}"></script>
        <script src="{{ mix('js/pages/global.js') }}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
            $(document).on("keydown", disableF5);
        </script>
        <script src="{{ mix('js/utilities/st.common.js') }}"></script>
        <script src="{{ mix('js/pages/agreement.preview.js') }}"></script>
    </body>
</html>
