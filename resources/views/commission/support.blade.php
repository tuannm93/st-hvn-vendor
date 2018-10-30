@foreach ($supports[$prefix] as $val)
<tr>
    <td class="p-2">{{ $val['correspond_datetime'] }}</td>
    <td class="p-2">{{ isset($situation[$val['correspond_status']]) ? $situation[$val['correspond_status']] : '' }}</td>
    <td class="p-2">{{ $val['responders'] }}</td>
    <td class="p-2">{{ $val['created'] }}</td>
    <td class="p-2">{{ $val['corresponding_contens'] }}</td>
</tr>
@endforeach
