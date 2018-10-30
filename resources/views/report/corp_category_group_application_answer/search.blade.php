<div id="search" class="form-horizontal fieldset-custom mb-4 px-3">
    <div id="contents" class="demand-list">
        <div id="main">
            <form id="downloadCsv" action="{{ URL::route('export.csv.corp.category.group.application.answer') }}" method="post" multiple="multipart/form-data" accept-charset="UTF-8">
                {{ csrf_field() }}
                <input name="corp_id_hid" id="corp_id_hid" type="hidden" value="">
                <input name="corp_name_hid" id="corp_name_hid" type="hidden" value="">
                <input name="group_id_hid" id="group_id_hid" type="hidden" value="">
                <input name="application_date_from_hid"  id="application_date_from_hid"  type="hidden" value="">
                <input name="application_date_to_hid"  id="application_date_to_hid"  type="hidden" value="">
            </form>
            <form id="searchForm" action="{{ URL::route('report.get.corp.category.group.application.answer') }}" method="get">
                <fieldset>
                    <legend>{{ __('report_corp_cate_group_app_answer.search_condition') }}</legend>
                    <div class="form-container bg-update-box border-update-box p-2">
                        <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-inline mb-2">
                                        <label class="col-12 col-sm-3 col-md-3 col-lg-4" for="corp_id">{{ __('report_corp_cate_group_app_answer.corp_id') }}</label>
                                        <div class="col-12 col-sm-9 col-md-9 col-lg-8 p-0">
                                            <input class="form-control w-100" name="corp_id" id="corp_id" type="text" data-rule-number="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-inline mb-2">
                                        <label class="col-12 col-sm-3 col-md-3 col-lg-4" for="corp_name">{{ __('report_corp_cate_group_app_answer.corp_name') }}</label>
                                        <div class="col-12 col-sm-9 col-md-9 col-lg-8 p-0">
                                            <input class="form-control w-100" name="corp_name" id="corp_name" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-inline mb-2">
                                        <label class="col-12 col-sm-3 col-md-3 col-lg-4" for="group_id">{{ __('report_corp_cate_group_app_answer.group_id') }}</label>
                                        <div class="col-12 col-sm-9 col-md-9 col-lg-8 p-0">
                                            <input class="form-control w-100" name="group_id" id="group_id" type="text" data-rule-number="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-inline mb-2">
                                        <label class="col-12 col-sm-3 col-md-3 col-lg-4" for="from_contact_desired_time">{{ __('report_corp_cate_group_app_answer.application_date') }}</label>
                                        <div class="d-flex col-12 col-sm-9 col-md-9 col-lg-8 p-0">
                                            <div class="d-inline-flex flex-column">
                                                <input name="application_date_from" class="form-control datepicker w-100" type="text" id="application_date_from" value="">
                                            </div>
                                            <label class="px-2">～</label>
                                            <div class="d-inline-flex flex-column">
                                                <input name="application_date_to" class="form-control datepicker w-100" type="text" id="application_date_to" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex">
                                        <button type="button" id="searchButton" class="btn btn--gradient-orange remove-effect-btn col-lg-2 col-sm-3 mb-1 mb-sm-0 mr-2">{{ __('report_corp_cate_group_app_answer.searchForm') }}</button>
                                        <button type="submit" id="downloadCsv" form="downloadCsv" class="btn btn--gradient-orange  remove-effect-btn col-lg-2 col-sm-3 mb-1 mb-sm-0">{{ __('report_corp_cate_group_app_answer.downloadCsv') }}</button>
                                    </div>
                                </div>
                        <div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
