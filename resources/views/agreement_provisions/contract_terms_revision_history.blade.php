@extends('layouts.app')
@section('style')
    <link href="{{ mix('css/lib/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ mix('css/lib/buttons.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row agreement-customize contract-terms-revision-history">
        <div class="col-md-12 col-md-offset-1">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading mb-3">
                        @lang('agreement_admin.contract_terms_revision_history')
                    </div>
                    <div class="table-responsive">
                        <table id="datalist" class="table responsive table-striped table-bordered"
                               cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        @lang('agreement_admin.history_id')
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.revision_date_time')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                    <th class="text-center">
                                        @lang('agreement_admin.update_user')
                                        <input type="text" class="form-control filter-control">
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-global fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            @lang('agreement_admin.reorganize_information_reference')
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group px-3 mb-sm-0">
                            <div class="row">
                                <div class="col-sm-3 d-flex align-items-center modal-label">
                                    <label for="detail-id">
                                        <strong>@lang('agreement_admin.history_id')</strong>
                                    </label>
                                </div>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div id="detail-id" name="detail-id"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group px-3 mb-sm-0">
                            <div class="row border-top-0">
                                <div class="col-sm-3 d-flex align-items-center modal-label">
                                    <label for="detail-content">
                                        <strong>@lang('agreement_admin.contract_description_content')</strong>
                                    </label>
                                </div>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div id="detail-content" class="my-2" name="detail-content"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group px-3 mb-sm-0">
                            <div class="row border-top-0">
                                <div class="col-sm-3 d-flex align-items-center modal-label">
                                    <label for="detail-created">
                                        <strong>@lang('agreement_admin.revision_date_time')</strong>
                                    </label>
                                </div>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div id="detail-created" name="detail-created"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group px-3 mb-0">
                            <div class="row border-top-0">
                                <div class="col-sm-3 d-flex align-items-center modal-label">
                                    <label for="detail-user-name">
                                        <strong>@lang('agreement_admin.update_user')</strong>
                                    </label>
                                </div>
                                <div class="col-sm-9 d-flex align-items-center">
                                    <div id="detail-user-name" name="detail-user-name"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        const GET_DATA_URL = '{{route('contract.terms.revision.history.data')}}';

        const KEY_WORD_SEARCH_FROM_THE_WHOLE = '{{trans('agreement_admin_dashboard.keywordSearchFromTheWhole')}}';
        const ZERO_RECORDS = '{{trans('agreement_admin_dashboard.zeroRecords')}}';
        const PROCESSING = '{{trans('agreement_admin_dashboard.processing')}}';
        const EXPRESS = '{{trans('agreement_admin_dashboard.express')}}';
        const PAGE = '{{trans('agreement_admin_dashboard.page')}}';

        const DISPLAY_BY_ITEM = '{{trans('agreement_admin.display_by_item')}}'
    </script>

    <script src="{{ mix('js/lib/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ mix('js/lib/jszip.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.buttons.min.js') }}"></script>
    <script src="{{ mix('js/lib/dataTables.select.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('js/lib/buttons.html5.min.js') }}"></script>
    <script src="{{ mix('js/pages/agreement.admin.contract_terms_revision_history.js') }}"></script>

@endsection