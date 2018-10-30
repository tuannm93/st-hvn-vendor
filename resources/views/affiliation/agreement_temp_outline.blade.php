<table style="border-collapse: collapse; border: none;">
    <tbody>
        <tr>
            <td style="font-size: 16px; width: 110px;">@lang('agreement_terms.corp_id')</td>
            <td style="width: 500px;">：  {{ $corpId }}</td>
        </tr>
        <tr>
            <td style="font-size: 16px; width: 110px;">@lang('agreement_terms.corp_name')</td>
            <td style="width: 500px;">：  {{ $corpName }}</td>
        </tr>
        <tr>
            <td style="font-size: 16px; width: 110px;">@lang('agreement_terms.agreement_id')</td>
            <td style="width: 500px;">：  {{ $agreementId }}</td>
        </tr>
        <tr>
            <td style="font-size: 16px; width: 110px;">@lang('agreement_terms.agreement_date')</td>
            <td style="width: 500px;">：  {{ $agreementDate }}</td>
        </tr>
    </tbody>
</table>
<br>
<div style="width: 1578px; display: flex">
    <div style="width: 49%; float: left">
        <div style="font-size: 18px; font-weight: 700; text-align: center; margin-bottom: 15px;">@lang('agreement_terms.org_term')</div>
        <div style="font-size: 12px;">{!! $orgTerms !!}</div>
    </div>
    <div style="width: 2%; float: left">&nbsp;</div>
    <div style="width: 49%; float: right">
        <div style="font-size: 18px; font-weight: 700; text-align: center; margin-bottom: 15px;">@lang('agreement_terms.cst_term')</div>
        <div style="font-size: 12px;">{!! $cstTerms !!}</div>
    </div>
</div>
