<table class="table table-bordered">
    <thead>
    <tr class="genre_table_th_tr">
        <th>@lang('agreement_system.genre')</th>
        <th>@lang('agreement_system.category')</th>
        <th class="text-nowrap">@lang('agreement_system.compatible')</th>
        <th class="text-nowrap">
            @lang('agreement_system.expertise')
            <span class="text--orange">@lang('agreement_system.required')</span>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($genre[$categoryListNumber] as $indexCategory => $category)
        <tr class="genre_table_tr_td"
            @if(array_key_exists('mCorpCategoryTempId', $category)) style="background-color: #ffff9c" @endif>
            <td class="genre_table_tr_td"
                valign="middle">{{$category['genreName']}}</td>
            <td class="genre_table_tr_td"
                valign="middle">{{$category['categoryName']}}</td>
            <td class="genre_table_tr_td" align="center" valign="middle">
                <input type="checkbox"
                       @if(array_key_exists('mCorpCategoryTempId', $category))  {{'checked'}} @endif  name="categoryId_{{$category['categoryId']}}"
                       id="categoryId_{{$category['categoryId']}}"
                       value="{{$category['categoryId']}}"/>
            </td>
            <td class="genre_table_tr_td" align="center" valign="middle">
                <select name="selectList_{{$category['categoryId']}}"
                        id="selectList_{{$category['categoryId']}}">
                    @foreach($selectList as $indexSelectList => $selectValue)
                        <option value="{{$indexSelectList}}" @if($indexSelectList === $category['selectList']) {{'selected'}} @endif>{{$selectValue}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
