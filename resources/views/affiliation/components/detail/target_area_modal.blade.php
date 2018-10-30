<!-- Modal -->
<div class="target_area">
    <div class="modal fade" id="targertAreaModal" tabindex="-1" role="dialog" aria-labelledby="targertAreaModal"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header mb-2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <table border="1" class="list_table">
                                <thead>
                                <tr>
                                    <th class="text-center w-15 font-weight-bold">{{ trans('affiliation.category_name') }}</th>
                                    <th class="text-center w-85 font-weight-bold">{{ trans('affiliation.applicable_area') }}</th>
                                </tr>
                                </thead>
                                <tbody class="modal-body">
                                </tbody>
                            </table>
                            <input type="button" class="btn btn--gradient-gray mt-2" id="close-area" value="{{ trans('affiliation.close') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
