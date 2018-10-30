<!-- The Modal -->
<div class="modal fade" id="list_event_dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header p-1">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h3 class="font-weight-bold text-danger text-center py-3">@lang('auction.please_select_a_matter')</h3>
                <div id="list_demands"></div>
            </div>
            <!-- Modal footer -->
            <div class="text-center py-3">
                <button type="button" class="btn btn--gradient-green font-weight-bold px-5" onclick="$('#list_event_dialog').modal('hide');goCommissionDetail();">@lang('auction.express')</button>
            </div>
        </div>
    </div>
</div>
