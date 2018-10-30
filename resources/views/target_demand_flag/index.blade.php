@extends('layouts.app')

@section('content')
    @if(session()->has('error'))
        <div class="box__mess box--error">
            {!! session('error') !!}
        </div>
    @endif
    @if(session()->has('success'))
        <div class="box__mess box--success">
            {!! session('success') !!}
        </div>
    @endif
     <div class="target_demand_flag">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <h3 class="border_double_gray">{{ __('target_demand_flag.target_demand_flag_label') }}</h3>
                    <form id="postTargetFlag" action="{{ route('post.target.demand.flag') }}" method="post" class="row">
                        {{ csrf_field() }}
                        @foreach($allData as $groupData)
                            <div class="col-md-6">
                                <table class="table-list list_table mt-1">
                                    <thead>
                                    <tr>
                                        <th class="genre_table_th_tr text-center">{{ __('target_demand_flag.id') }}</th>
                                        <th class="genre_table_th_tr text-center">{{ __('target_demand_flag.name') }}</th>
                                        <th class="genre_table_th_tr text-center">
                                            {{ __('target_demand_flag.proposal_flag') }}
                                            <br>
                                            {{ __('target_demand_flag.excluded_check') }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($groupData as $key => $value)
                                        <tr>
                                            <td class="genre_table_tr_td text-center">{{ $value->id }}</td>
                                            <td class="genre_table_tr_td text-left">{{ $value->genre_name }}</td>
                                            <td class="genre_table_tr_td text-center"><input type="checkbox" name="exclusion_flg[]" value="{{ $value->id }}" @if($value->exclusion_flg == 1) checked @endif> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </form>
                    <div class="d-flex justify-content-center mt-2">
                        <button type="submit" form="postTargetFlag" class="btn btn--gradient-green fix-w-200">{{ __('common.register') }}</button>
                    </div>
                </div>
            </div>
        </div>
     </div>

@endsection
