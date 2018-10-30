<div class="row agreement-system">
    <div class="col-12">
        @foreach($arrayProvision as $index => $provision)
            <div class="mb-3">
                <strong>{{$provision['provisions']}}</strong>
                @if (array_key_exists('agreement_provision_item', $provision))
                    @foreach($provision['agreement_provision_item'] as $key => $item)
                        <div>
                            <span class="pl-4">{{$item['item']}}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>
