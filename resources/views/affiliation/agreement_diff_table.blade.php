@if ($diffProv)
<div style="font-size: 22px; font-weight: 700; margin-bottom: 20px">
    ― @lang('agreement_terms.file_name_term')
</div>
<br>
<div style="font-size: 16px;">
    @foreach($diffProv as $item)
    ・{{ $item }}
    <br>
    @endforeach
</div>
@endif
