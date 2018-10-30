@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12"><h3>加盟店情報</h3><hr></div>
            <div class="col-md-12">

            </div>
        </div>
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))

                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
        <div class="row">
            <form enctype="multipart/form-data" accept-charset="UTF-8" method="post">
                <h1>契約状況</h1>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>申請種別</td>
                        <td>FAX</td>
                    </tr>
                    <tr>
                        <td>Web規約</td>
                        <td>
                            <input type="radio" name="CorpAgreement.agreement_flag" value="0"> 未契約
                            <input type="radio" name="CorpAgreement.agreement_flag" value="1"> 契約済
                        </td>
                    </tr>
                    <tr>
                        <td>区分</td>
                        <td>個人<input type="hidden" name="data[CorpAgreement][corp_kind]" value="" id="CorpAgreementCorpKind"></td>
                    </tr>
                    <tr>
                        <td>承認</td>
                        <td><input type="checkbox" value="">承認する</td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="2" class="text-center"><input type="submit" name="update-corp-agreement" class="btn btn-lg btn-success" value="登 録"></td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection
