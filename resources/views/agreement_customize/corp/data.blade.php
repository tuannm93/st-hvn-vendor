@foreach($arrayProvision as $index => $provision)
    <div>
        <span><strong>{{$provision['provisions']}}</strong></span>
        <span class="fa fa-edit text-primary edit-customize-provision cursor"
           data-id="{{$provision['id']}}"
           data-content="{{$provision['provisions']}}"
           data-sort-no="{{$provision['sort_no']}}"
           data-customize-flag = "{{array_key_exists('customize_flag', $provision) && $provision['customize_flag']}}">
            @lang('agreement_admin.hyper_link_edit')
        </span>
        @if(array_key_exists('customize_flag', $provision) && $provision['customize_flag'])
            <span class="fa fa-edit text-danger">
                            @lang('agreement_admin.hyper_link_edited')
                        </span>
        @endif
        @if (array_key_exists('agreement_provision_item', $provision) && checkNotNullAndEmpty($provision['agreement_provision_item']))
            <ul class="list-unstyled border">
                @foreach($provision['agreement_provision_item'] as $key => $item)
                    <li class="p-3 mb-2">
                        {{$item['item']}}
                        <p class="m-0">
                            <span class="fa fa-edit text-primary edit-customize-provision-item cursor"
                               data-item-id="{{$item['id'] > 0 ? $item['id'] : $item['customize_key']}}"
                               data-provision-id="{{$provision['id']}}"
                               data-provision="{{$provision['provisions']}}"
                               data-content="{{$item['item']}}"
                               data-sort-no="{{$item['sort_no']}}"
                               data-customize-flag = "{{array_key_exists('customize_flag', $item) && $item['customize_flag']}}">
                                @lang('agreement_admin.hyper_link_edit')
                            </span>
                            @if(array_key_exists('customize_flag', $item) && $item['customize_flag'])
                                <span class="fa fa-edit text-danger">
                                                @lang('agreement_admin.hyper_link_edited')
                                            </span>
                            @endif
                        </p>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-muted pl-3">@lang('agreement_admin.item_not_exist')</div>
        @endif
    </div>
@endforeach