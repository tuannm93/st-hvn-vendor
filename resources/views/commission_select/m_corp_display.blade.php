<!-- Modal -->
<div class="modal fade" id="mCorpList" tabindex="-1" role="dialog" aria-labelledby="mCorpList" aria-hidden="true"
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-category">
                    <label class="form-category__label mb-2">@lang('commissionselect.m_corp_display.title')</label>
                    <div class="form-category__body">
                        {{Form::open(['url' => route('commission_select.m_corp_search'), 'id' => 'corpSearch', 'novalidate'])}}
                        <div class="form-group row ml-0 mr-0 mb-2">
                            <div class="col-8 col-sm-9 col-md-4 pl-0">
                                {{Form::text('corp_name', ((isset($search_data) && isset($search_data['corp_name'])) ? $search_data['corp_name'] : null), ['id' => 'corp_name', 'class' => 'form-control'])}}
                            </div>
                            {{Form::submit(trans('commissionselect.m_corp_display.search_btn'), ['id' => 'btnSearch', 'class' => 'btn btn--gradient-orange col-4 col-sm-3 col-md-2'])}}
                        </div>
                        {{Form::close()}}

                        <div class="row pl-1">
                            <div class="col-12">
                                <label class="mb-1">@lang('commissionselect.m_corp_display.header')</label>
                                <div class="table-responsive">
                                    <table>
                                        <tbody class="list-data"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <ul class="pagination dataTables_paginate mb-0 pl-2 pt-3" id="mCorpPagination"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn--gradient-gray" data-dismiss="modal">@lang('commissionselect.m_corp_display.close_btn')</a>
            </div>
        </div>
    </div>
</div>

{{--<a data-toggle="modal" href="#mCorpList">Open Modal</a>--}}
{{--<input type="text" name="" id="official_corp_name">--}}
{{--<input type="text" name="" id="official_corp_id">--}}
{{--<button id="btn-click">asdasdasd</button>--}}
{{--@endsection--}}
 {{--Import doan Script comment phia duoi vao view can hien popup--}}
{{--@section('script')--}}
    {{--<script>--}}
        {{--var SEARCH_URL = '{{route('commission_select.m_corp_search')}}';--}}
        {{--var FORM = '#corpSearch';--}}
        {{--var MODAL = '#mCorpList';--}}
        {{--var INPUT_CORP_NAME = '#official_corp_name';--}}
        {{--var INPUT_CORP_ID = '#official_corp_id';--}}
        {{--var LIST_DATA_SELECTOR = '.list-data';--}}
        {{--var PAGINATE_SELECTOR = '#mCorpPagination';--}}
        {{--var NEXT_TEXT = '@lang('commissionselect.m_corp_display.next')';--}}
        {{--var PREV_TEXT = '@lang('commissionselect.m_corp_display.prev')';--}}
        {{--var BTN_TOGGLE_MODAL = '#btn-click';--}}
        {{--var BTN_SEARCH = '#btnSearch';--}}
        {{--var INPUT_SEARCH_CORP = '#mCorpList input[name=corp_name]';--}}
    {{--</script>--}}
    {{--<script src="{{ mix('js/pages/commission_select_m_corp.js') }}"></script>--}}
{{--@endsection--}}
