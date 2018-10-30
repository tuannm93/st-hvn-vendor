@foreach($results as $result)
    <tr>
        <td class="text-left">{{$result->category_name}}</td>
        <td class="text-left">{{$result->address}}</td>
    </tr>
@endforeach
