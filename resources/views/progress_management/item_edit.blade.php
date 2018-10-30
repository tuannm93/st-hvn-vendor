@extends('layouts.app', ['show_head' => false, 'header_progress' => trans('progress_management.item_update.page_title')])
@section('content')
<div class="progress_management-edit">
    <div class="container">
        @if(Session::has('flash_message'))
            <p class="alert alert-{{ (Session::get('flash_message')['type'] == 'success') ? 'success' : 'danger'}}">{{ Session::get('flash_message')['text'] }} <a href="#"  class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </p>
        @endif
        
            {!! Form::model($item, ['route' => ['progress.update_item_edit', $item->id],'id' => 'item_edit']) !!}
                <div class="total">
                    <div class="form-group row">
                        <div class="col-md-2 d-flex align-items-center">
                                <h5> @lang('progress_management.item_update.lbl_up_text') </h5> 
                        </div>
                        <div class="col-md-10">
                            {{Form::textarea('up_text',null,['cols' => "30", 'rows' => "10",'class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 d-flex align-items-center">
                                <h5> @lang('progress_management.item_update.lbl_down_text')</h5>
                        </div>
                        <div class="col-md-10">
                            {{Form::textarea('down_text',null,['cols' => "30", 'rows' => "10",'class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 d-flex align-items-center">
                                <h5> @lang('progress_management.item_update.lbl_return_limit')</h5>
                        </div>
                        <div class="col-md-10 d-flex">
                                {{Form::text('return_limit',null,['cols' => "30",'data-rule-number'=>'true' , 'rows' => "10",'id' => 'demand_id','class' => 'form-control','maxlength' => 2,'style' => 'width:42px; 
                                '])}}
                                &nbsp;
                                <div class="d-flex align-items-center">
                                    <p>
                                            @lang('progress_management.item_update.return_limit_unit')
                                    </p>
                                
                                </div>
                               
                        </div>
                        <label id="demand_id-error" class="invalid-feedback offset-md-2 col-md-10" for="demand_id"></label>
                    </div>

                    <div class="row">
                        <div class="offset-sm-4 col-sm-4 text-center">
                            {{Form::submit(trans('progress_management.item_update.submit'),array('class' => 'btn btn--gradient-green font-weight-bold col-12'))}}
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
    </div>
</div> 
@endsection
@section('script')
<script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
<script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
<script src="{{ mix('js/lib/additional-methods.min.js') }}"></script>
<script src="{{ mix('js/utilities/form.validate.js') }}"></script>

<script>
FormUtil.validate('#item_edit');
</script>
@endsection
