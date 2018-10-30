@extends('layouts.app')

@section('content')
    <section class="content container affiliation">
        <div class="row header-field my-4">
            <div class="col-sm-6 col-md-4">
                <strong>{{trans('add_agreement.business_name')}}:</strong>
                <a target="_blank" class="text--orange"
                   href=" @if(isset($mCorp->id)) {{route('agreement.detail',['id'=>$mCorp->id])}} @endif">
                    @if(isset($mCorp->official_corp_name))
                        <u>{{ $mCorp->official_corp_name }}</u>
                    @endif
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <strong>
                    {{trans('add_agreement.business_ID')}}:
                    @if(isset($mCorp->id))
                        {{$mCorp->id}}
                    @endif
                </strong>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <div class="form-category">
                    <label class="form-category__label mb-0">{{trans('add_agreement.contract_status')}}</label>
                    <div class="form-category__body">
                        <form class="form--border" method="post" action="{{route('agreement.postAddAgreement',$mCorp->id)}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="corp_id" value="{{$mCorp->id}}" id="corpAgreementId">

                            <div class="form-group row">
                                <label for="corpAgreementKind" class="col-sm-2 col-form-label form__label--light-white p-3">
                                    <strong>{{trans('add_agreement.application_type')}}</strong>
                                </label>
                                <div class="col-sm-10 py-3">
                                    <input type="text" readonly class="form-control-plaintext p-0" value="{{trans('add_agreement.fax')}}">
                                    <input type="hidden" name="kind" value="{{trans('add_agreement.fax')}}"
                                           id="corpAgreementKind">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2 form__label--light-white p-3">
                                    <strong>{{trans('add_agreement.web_terms')}}</strong>
                                </div>
                                <div class="col-sm-10 py-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="agreement_flag" id="agreement_flag1" value="0" checked>
                                        <label class="custom-control-label" for="agreement_flag1">{{trans('add_agreement.not_signed')}}</label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="agreement_flag" id="agreement_flag2" value="1">
                                        <label class="custom-control-label" for="agreement_flag2">{{trans('add_agreement.contracted')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="CorpAgreementCorpKind" class="col-sm-2 col-form-label form__label--light-white p-3">
                                    <strong>{{trans('add_agreement.classification')}}</strong>
                                </label>
                                <div class="col-sm-10 py-3">
                                    <input type="text" readonly class="form-control-plaintext p-0" value="{{$checkCorpKind}}">
                                    <input type="hidden" name="corp_kind" value="{{$mCorp->corp_kind}}"
                                           id="CorpAgreementCorpKind">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2 form__label--light-white p-3">
                                    <strong>{{trans('add_agreement.approval')}}</strong>
                                </div>
                                <div class="col-sm-10 py-3">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input"
                                               type="checkbox"
                                               name="acceptation"
                                               id="acceptation"
                                               value="0"
                                               @if($checkDisableFlg)
                                               disabled
                                                @endif>
                                        <input type="hidden" name="acceptation" value="0">
                                        <label class="custom-control-label" for="acceptation">
                                            {{trans('add_agreement.approve')}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center mt-4 mb-5">
                                <button type="submit" name="update-corp-agreement"
                                       class="btn btn-lg btn-success btn--gradient-green w-25">
                                    {{trans('add_agreement.register')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
