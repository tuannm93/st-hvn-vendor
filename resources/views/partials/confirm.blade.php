<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">{{ __('support.confirm') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">{{ $text }}</div>
            <div class="modal-footer">
                <button type="submit" id="submitDelete" form="{{ $formIdSubmit }}" class="btn btn-secondary">{{ __('support.confirm') }}</button>
                <button type="button" class="btn" data-dismiss="modal">{{ __('support.cancel') }}</button>
            </div>
        </div>
    </div>
</div>