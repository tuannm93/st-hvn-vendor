<div class="modal modal-global" tabindex="-1" role="dialog" id="delete_confirm_dialog">
    {!! Form::open(['route' => ['demand.delete', $demand->id], 'method' => 'POST', 'id' => 'form_delete_demand']) !!}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header p-1">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-5" id="display_modal_area">
                    <div class="text-center">削除します。宜しいですか？</div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn--gradient-default btn--w-normal ml-4" id="delete_confirm_close">
                        閉じる
                    </button>
                    <button type="submit" class="btn btn--gradient-green btn--w-normal ml-4" id="delete_confirm_approved">
                        削除
                    </button>
                    <p></p>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>