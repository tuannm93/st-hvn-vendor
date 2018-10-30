<h2>@lang('agreement_system.note_1_confirm_step')</h2>
<h2>@lang('agreement_system.note_2_confirm_step')</h2>
<div class="row agreement-system">
    <div class="col-12">
        @foreach($arrayProvision as $index => $provision)
            <div class="mb-3">
                @if($provision['sort_no'] == 6 || $provision['sort_no'] == 9 ||
                           $provision['sort_no'] == 10 || $provision['sort_no'] == 18 || $provision['sort_no'] == 21)
                    <strong>{{$provision['provisions']}}</strong>
                    @if (array_key_exists('agreement_provision_item', $provision))
                        @foreach($provision['agreement_provision_item'] as $key => $item)

                            <div>
                                <span class="pl-4">{{$item['item']}}</span>
                            </div>

                        @endforeach
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
