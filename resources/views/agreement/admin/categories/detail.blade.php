<div class="modal modal-row-grid modal-global fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    @lang('agreement_admin.refer_to_category_information')
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="px-3 mb-sm-0">
                    <div class="row border ">
                        <div class="col-sm-3 d-flex align-items-center modal-label border-right py-2">
                            <label for="detail-category-id" class="m-0">
                                @lang('agreement_admin.category_id')
                            </label>
                        </div>
                        <div class="col-sm-9 d-flex align-items-center p-2">
                            <div id="detail-category-id"></div>
                        </div>
                    </div>
                </div>
                <div class="px-3 mb-sm-0">
                    <div class="row border border-top-0">
                        <div class="col-sm-3 d-flex align-items-center modal-label border-right py-2">
                            <label for="detail-genre-name" class="m-0">
                                @lang('agreement_admin.genre_name')
                            </label>
                        </div>
                        <div class="col-sm-9 d-flex align-items-center p-2">
                            <div id="detail-genre-name"></div>
                        </div>
                    </div>
                </div>
                <div class="px-3 mb-sm-0">
                    <div class="row border border-top-0">
                        <div class="col-sm-3 d-flex align-items-center modal-label border-right py-2">
                            <label for="detail-category-name" class="m-0">
                                @lang('agreement_admin.category_name')
                            </label>
                        </div>
                        <div class="col-sm-9 d-flex align-items-center p-2">
                            <div id="detail-category-name"></div>
                        </div>
                    </div>
                </div>
                <div class="px-3 mb-0">
                    <div class="row border border-top-0">
                        <div class="col-sm-3 d-flex align-items-center modal-label border-right py-2">
                            <label for="detail-license" class="m-0">
                                @lang('agreement_admin.license')
                            </label>
                        </div>
                        <div class="col-sm-9 d-flex align-items-center p-2">
                            <div id="detail-license"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
