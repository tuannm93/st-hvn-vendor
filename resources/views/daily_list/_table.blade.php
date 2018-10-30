<div class="table-responsive">
    <table class="table table-list table-bordered">
        <thead>
        <tr>
            <th  class="text-center font-weight-bold w-20">@lang("daily_list.col1")</th>
            <th class="text-center font-weight-bold">@lang("daily_list.col2")</th>
        </tr>
        </thead>
        <tbody>
        @php
        $valueNo  = 1;
        @endphp
        @foreach($files as $key => $file)
            <tr>
                <td class="text-center">{{ $valueNo }}</td>
                <td>
                    <a href="{{ route('dailylist.downloadfile', ['filepath' => $file['path'],
                    'filename' => $file['filename']]) }}">{{$file["filename"]}}</a>
                </td>
                @php
                $valueNo++;
                @endphp
            </tr>
        @endforeach
        </tbody>
    </table>
</div>