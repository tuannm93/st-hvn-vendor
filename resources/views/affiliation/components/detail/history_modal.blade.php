<!-- Modal -->
<div class="historyModal">
        <div class="modal fade" id="historyModal"  class="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                                <h3 class="font-weight-bold">{{ __('affiliation_detail.affiliation_history_label') }}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                           
                        </div>
                        <div class="container">
                            <div class="modal-body">
                                <div id="contents">
                                    <div id="main">
                                        
                                        <form id="formAffiliationHistory" method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-md-3" for="popup-responders">{{ __('affiliation_detail.responders') }}</label>
                                                    <select class="col-md-9 form-control" name="data_history[responders]" id="popup-responders" data-rule-required="true">
                                                        <option value="">{{ trans('affiliation_detail.none_value') }}</option>
                                                        @if(!empty($userList))
                                                            @foreach($userList as $key => $value)
                                                                <option value="{{ $key }}">{{ $value }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-md-3" for="popup-content">{{ __('affiliation_detail.corresponding_contents') }}</label>
                                                    <textarea class="col-md-9 form-control" rows="4" name="data_history[corresponding_contens]" id="popup-content" data-rule-required="true" data-rule-maxlength="1000"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="col-md-3" for="popup-datetime">{{ __('affiliation_detail.correspond_datetime') }}</label>
                                                    <input type="text" class="col-md-9 form-control datetimepicker" name="data_history[correspond_datetime]" id="popup-datetime">
                                                </div>
                                            </div>
                                            <input type="hidden" name="affiliation_id" value="{{ $mCorp->id }}">
                                            <button type="button" class="btn btn--gradient-gray fix-w-100" id="cancel-history">{{ __('common.cancel') }}</button>
                                            <button type="submit" class="btn btn--gradient-green fix-w-100" id="submit-history">{{ __('common.edit') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>    

