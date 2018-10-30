<!-- Search details -->
@if(isset($results))
    @if ($results->count() > 0)
        <div class="table-result-search demand-list">
            <span class="count-total">{{ __('common.total') }} {{ $results->total() }} {{ __('common.item') }}</span>
            <div class="table-responsive">
                <table class="table custom-border" id="searchData">
                    <thead>
                    <tr class="bg-primary-lighter">
                        <th class="text-center fix-w-70">@lang('report_corp_cate_group_app_answer.corp_category_application_answer_id')</th>
                        <th class="text-center">@lang('report_corp_cate_group_app_answer.default_value_label')</th>
                        <th class="text-center">@lang('report_corp_cate_group_app_answer.m_corps_id')</th>
                        <th class="text-center">@lang('report_corp_cate_group_app_answer.created_user_id')</th>
                        <th class="text-center">@lang('report_corp_cate_group_app_answer.corp_category_application_answer_created')</th>
                        <th class="text-center">@lang('report_corp_cate_group_app_answer.application_count')</th>
                        <th class="text-center">@lang('report_corp_cate_group_app_answer.application_count_check')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($results))
                        @foreach($results as $key => $data)
                            @php
                                if($data->unapproved_count > 0) {
                                    $bgColor = '#ffffc0';
                                    $status = -1;
                                } elseif($data->approval_count == $data->application_count) {
                                    $bgColor = '#81F79F';
                                    $status = 1;
                                } elseif($data->reject_count == $data->application_count) {
                                    $bgColor = '#F5A9A9';
                                    $status = 2;
                                } else {
                                    $bgColor = '#fff';
                                    $status = 0;
                                }
                            @endphp

                            <tr>
                                <td align="right">{{ $data->cid }}</td>
                                <td align="center">{{ __('report_corp_cate_group_app_answer.default_value') }}</td>
                                <td align="center">
                                    <a class="highlight-link" href="{{ URL::route('affiliation.detail.edit', $data->m_corps_id) }}" target="_blank">{{ $data->official_corp_name }}</a>
                                <td align="center" style="word-wrap:break-word">{{ $data->created_user_id }}</td>
                                <td align="center">{{ $data->created }}</td>
                                <td align="center">
                                    <a class="highlight-link" href="{{ URL::route('report.get.corp.category.application.answer', $data->cid) }}"
                                       target="_blank">{{ $data->application_count }}</a>
                                </td>
                                <td align="center" bgcolor="{{ $bgColor }}">
                                    {{ $propriety[$status] }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                @if(!empty($results))
                    {{ $results->links('common.pagination') }}
                @endif
            </div>
        </div>
    @else
        <div class="text-center">{{ __('report_corp_cate_group_app_answer.no_answer') }}</div>
    @endif
@endif
