<div class="modal modal-global modal-form-border fade" id="update-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    @lang('agreement_admin.select_license')
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <h4 class="modal-title text-center">
                    @lang('agreement_admin.category_name')：
                    <span id = "update-popup-category-name"></span>
                </h4>
                <div class="mb-2">
                    <div class="row form-group m-0">
                        <div class="col-sm-6 d-flex align-items-center modal-label">
                            <label class="m-0">
                                @lang('agreement_admin.license_check_condition')
                            </label>
                        </div>
                        <div class="col-sm-2 d-flex align-items-center pl-sm-0">
                            <select id="license-check-condition-select" name="license-check-condition-select"
                                    class="custom-select filter-control form-control">
                                @foreach(\App\Models\MCategory::LICENSE_CONDITION_TYPE as $type)
                                    <option value="{{$type}}">{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <table id="license-list" class="table responsive table-striped table-bordered"
                       cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center">
                            @lang('agreement_admin.license_name')
                            <input type="text" class="form-control filter-control">
                        </th>
                        <th class="text-center">
                            @lang('agreement_admin.certificate')
                            <input type="text" class="form-control filter-control">
                        </th>
                    </tr>
                    </thead>
                </table>

                <div class="modal-footer justify-content-center p-4">
                    <button type="button"
                            class="btn btn--gradient-green w-45 text-white"
                            id="update-category-button">
                        @lang('agreement_admin.btn_update')
                    </button>
                    <button type="button"
                            class="btn btn--gradient-default border w-45 text-secondary"
                            data-dismiss="modal">
                        @lang('agreement_admin.btn_cancel')
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
