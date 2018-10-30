@foreach($agreementProvisions as $provision)
    <div>
        <span><strong>{{ $provision->provisions }}</strong></span>
        <a class="fa fa-edit text-primary open-edit-provision-dialog text--no-decoration"
           href="#"
           data-id="{{$provision->id}}"
           data-provision="{{ $provision->provisions }}"
           data-sort-no="{{ $provision->sort_no }}"
           data-delete-url="{{ route('agreement.provisions.delete-provision', $provision->id) }}">
            @lang('agreement_admin.hyper_link_edit')
        </a>
        @php $cnt = 0; @endphp
        <ol class="border">
            @foreach($provision->agreementProvisionItem as $item)
                <li class="py-3 pr-3">
                    <p>{!! nl2br(e($item->item)) !!}</p>
                    <a class="fa fa-edit text-primary open-edit-item-dialog text--no-decoration"
                       href="#"
                       data-id="{{$item->id}}"
                       data-item="{{ $item->item }}"
                       data-provision="{{ $provision->provisions }}"
                       data-sort-no="{{ $item->sort_no }}"
                       data-delete-url="{{ route('agreement.provisions.delete-item', $item->id) }}">
                        @lang('agreement_admin.hyper_link_edit')
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
@endforeach
