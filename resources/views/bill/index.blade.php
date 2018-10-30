@extends('layouts.app')

@section('content')
    <div class="container" id="admin-index">
        <div class="row">
            <div class="list-group col-md-3">
                <div class="group">
                    <h5 class="list-group-item list-group-item-info">{{__('bill.title_bill_index')}}</h5> 
                    <a class="list-group-item list-group-item-warning" href="/bill/mcorp_list">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>{{__('bill.router_mcorp_list')}}
                    </a>
                    <a class="list-group-item list-group-item-warning" href="/bill/bill_output">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>{{__('bill.router_bill_output')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
