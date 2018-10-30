<td class="p-1 bg-2">{{$prefecture[$index]}}</td>
<td class="p-1 text-center">
    @if(isset($noAttackList[$index]))
        <a href="{{action("Report\ReportDevelopmentController@getDevelopmentSearchByParams", ["status" => 1, "address" => $index])}}" class="highlight-link">{{$noAttackList[$index]}}</a>
    @else
        0
    @endif
</td>
<td class="p-1 text-center">
    @if(isset($advanceList[$index]))
        <a href="{{action("Report\ReportDevelopmentController@getDevelopmentSearchByParams", ["status" => 2, "address" => $index])}}" class="highlight-link">{{$advanceList[$index]}}</a>
    @else
        0
    @endif
</td>
