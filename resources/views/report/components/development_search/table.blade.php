<div>
    {!! Form::open(['url' => route('report.development.search'), 'id' => 'form-report-search', 'novalidate', 'class' => 'fieldset-custom']) !!}
    <fieldset class="form-group">
        <legend class="col-form-label">{{trans("report_development.form.title")}}</legend>
        <div class="box--bg-gray box--border-gray p-2">
            <div class="row mx-0 mb-2">
                <div class="col-sm-2 col-xl-1 px-0">
                    <label class="col-form-label">{{trans("report_development.drop.genre")}}</label>
                </div>
                <div class="col-sm-6 col-lg-4 px-0">
                    {{Form::select('genre_id', $genres, $genreId ? $genreId : null, ['placeholder' => trans("report_development.drop.genre.place"), 'class' => 'form-control'])}}
                </div>
            </div>
            <div class="row mx-0 mb-2">
                <div class="col-sm-2 col-xl-1 px-0">
                    <label class="col-form-label">{{trans("report_development.drop.prefecture")}}</label>
                </div>
                <div class="col-sm-3 col-xl-1 px-0">
                    {{Form::select('address', $prefecture, null, ['placeholder' => trans("report_development.drop.prefecture.place"), 'class' => 'form-control'])}}
                </div>
            </div>
            {{Form::submit(trans("report_development.button.search"), ['class' => 'btn btn--gradient-orange col-sm-2 col-lg-1'])}}
        </div>
    </fieldset>
    {!! Form::close() !!}
</div>
<div class="table-responsive">
    <table class="table custom-border fs-16">
        <thead>
            <tr class="text-center bg-yellow-light">
                <th class="fix-w-100 p-1">{{trans("report_development.col1")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col2")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col3")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col4")}}</th>

                <th class="fix-w-100 p-1">{{trans("report_development.col2")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col3")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col4")}}</th>

                <th class="fix-w-100 p-1">{{trans("report_development.col2")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col3")}}</th>
                <th class="fix-w-100 p-1">{{trans("report_development.col4")}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="p-1 bg-1">{{trans("report_development.region.row1")}}</td>
                @component("report/components/development_search/_row", [
                        "prefecture" => $prefecture,
                        "noAttackList" => $noAttackList,
                        "advanceList" => $advanceList,
                        "index" => 1])
                @endcomponent
                <td colspan="6"></td>
            </tr>
            <tr>
                <td rowspan="2" class="p-1 bg-1">{{trans("report_development.region.row2")}}</td>
                @for ($i = 2; $i <= 4; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 5; $i <= 7; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                <td rowspan="3" class="p-1 bg-1">{{trans("report_development.region.row3")}}</td>
                @for ($i = 8; $i <= 10; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 11; $i <= 13; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @component("report/components/development_search/_row", [
                        "prefecture" => $prefecture,
                        "noAttackList" => $noAttackList,
                        "advanceList" => $advanceList,
                        "index" => 14])
                @endcomponent
                <td colspan="6"></td>
            </tr>
            <tr>
                <td rowspan="2" class="p-1 bg-1">{{trans("report_development.region.row4")}}</td>
                @for ($i = 15; $i <= 17; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 18; $i <= 20; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                <td rowspan="2" class="p-1 bg-1">{{trans("report_development.region.row5")}}</td>
                @for ($i = 21; $i <= 23; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @component("report/components/development_search/_row", [
                        "prefecture" => $prefecture,
                        "noAttackList" => $noAttackList,
                        "advanceList" => $advanceList,
                        "index" => 24])
                @endcomponent
                <td colspan="6"></td>
            </tr>
            <tr>
                <td rowspan="2" class="p-1 bg-1">{{trans("report_development.region.row6")}}</td>
                @for ($i = 25; $i <= 27; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 28; $i <= 30; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                <td rowspan="2" class="p-1 bg-1">{{trans("report_development.region.row7")}}</td>
                @for ($i = 31; $i <= 33; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 34; $i <= 35; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
                <td colspan="3"></td>
            </tr>
            <tr>
                <td rowspan="2" class="p-1 bg-1">{{trans("report_development.region.row8")}}</td>
                @for ($i = 36; $i <= 38; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @component("report/components/development_search/_row", [
                        "prefecture" => $prefecture,
                        "noAttackList" => $noAttackList,
                        "advanceList" => $advanceList,
                        "index" => 39])
                @endcomponent
                <td colspan="6"></td>
            </tr>
            <tr>
                <td rowspan="3" class="p-1 bg-1">{{trans("report_development.region.row9")}}</td>
                @for ($i = 40; $i <= 42; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 43; $i <= 45; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
            </tr>
            <tr>
                @for ($i = 46; $i <= 47; $i++)
                    @component("report/components/development_search/_row", [
                            "prefecture" => $prefecture,
                            "noAttackList" => $noAttackList,
                            "advanceList" => $advanceList,
                            "index" => $i])
                    @endcomponent
                @endfor
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
</div>