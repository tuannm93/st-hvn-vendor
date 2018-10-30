<div class="demand-detail-print">
    @foreach($results as $result)
        @php
            if(isset($result->mCorp)) {
                $officialCorpName = $result->mCorp->official_corp_name;
            } else {
                $officialCorpName = '';
            }
        @endphp
        <div class="form-group row d-flex">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">・{{ $officialCorpName }}</label>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('commission.print.exportWord', ['commissionId' => $result->id])}}" class="btn btn--gradient-orange font-weight-bold ml-2 fix-button-w-120">印刷</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    @endforeach
</div>
