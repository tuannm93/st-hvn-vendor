<label class="form-category__label mt-2">{{ __('notice_info_update.answer_from_franchise_store') }}</label>
<div>
    <button id="download-csv-answer" class="btn btn--gradient-orange">{{ __('notice_info_update.btn_export_csv') }}</button>
</div>
<div class="table-responsive mt-3">
    <table class="table custom-border">
        <thead class="text-center bg-yellow-light">
            <tr>
                <th class="p-1">{{ __('notice_info_update.corp_id_col') }}</th>
                <th class="p-1">{{ __('notice_info_update.corp_name') }}</th>
                <th class="p-1">{{ __('notice_info_update.answer_col') }}</th>
                <th class="p-1">{{ __('notice_info_update.answer_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listAnswers as $answer)
            <tr>
                <td class="text-center p-1">{{ $answer->m_corp_id }}</td>
                <td class="p-1">{{ $answer->corp_name }}</td>
                <td class="p-1">{{ $answer->answer_value }}</td>
                <td class="text-center p-1">{{ $answer->answer_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
