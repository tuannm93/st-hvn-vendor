@extends('layouts.app')
@section('content')
    <section class="content container affiliation">
        <div class="row header-field my-4">
            <div class="col-sm-6 col-md-4">
                <strong>{{trans('add_agreement.business_name')}}:</strong>
                @if(isset($mCorp->id))
                <a target="_blank" class="text--orange"
                   href="{{route('affiliation.detail.edit', ['id'=>$mCorp->id])}}">
                    @if(isset($mCorp->official_corp_name))
                        <u>{{ $mCorp->official_corp_name }}</u>
                    @endif
                </a>
                @endif
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
        @if($checkCorpId)
            <div class="alert alert-danger">
                {{ trans('add_agreement.check_id') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="form-category">
                    <label class="form-category__label mb-0">{{trans('add_agreement.contract_status')}}</label>
                    <div class="form-category__body">
                        {!! Form::open(['url' => route('affiliation.postAddAgreement', (isset($mCorp->id) ? ['corp_id' => $mCorp->id] : '')), 'class' => 'form--border']) !!}
                        {!! Form::hidden('corp_id', (isset($mCorp->id) ? $mCorp->id : ''), ['id' => 'corpAgreement']) !!}
                        <div class="form-group row">
                            <label for="corpAgreementKind" class="col-sm-2 col-form-label form__label--light-white p-3">
                                <strong>{{trans('add_agreement.application_type')}}</strong>
                            </label>
                            <div class="col-sm-10 py-3">
                                {!! Form::text('', trans('add_agreement.fax'), ['class' => 'form-control-plaintext p-0', 'readonly' => 'readonly', 'tabindex' => -1]) !!}
                                {!! Form::hidden('kind', trans('add_agreement.fax'), ['id' => 'corpAgreementKind']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 form__label--light-white p-3">
                                <strong>{{trans('add_agreement.web_terms')}}</strong>
                            </div>
                            <div class="col-sm-10 py-3">
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('agreement_flag',0,true, ['class' => 'custom-control-input', 'id' => 'agreement_flag1']) }}
                                    <label class="custom-control-label"
                                           for="agreement_flag1">{{trans('add_agreement.not_signed')}}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    {{ Form::radio('agreement_flag',1,false, ['class' => 'custom-control-input', 'id' => 'agreement_flag2']) }}
                                    <label class="custom-control-label"
                                           for="agreement_flag2">{{trans('add_agreement.contracted')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="CorpAgreementCorpKind"
                                   class="col-sm-2 col-form-label form__label--light-white p-3">
                                <strong>{{trans('add_agreement.classification')}}</strong>
                            </label>
                            <div class="col-sm-10 py-3">
                                {!! Form::text('', isset($checkCorpKind) ? $checkCorpKind : '', ['class' => 'form-control-plaintext p-0', 'readonly' => 'readonly', 'tabindex' => -1]) !!}
                                {!! Form::hidden('corp_kind', isset($mCorp->corp_kind) ? $mCorp->corp_kind : '', ['id' => 'CorpAgreementCorpKind']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 form__label--light-white p-3">
                                <strong>{{trans('add_agreement.approval')}}</strong>
                            </div>
                            <div class="col-sm-10 py-3">
                                <div class="custom-control custom-checkbox">
                                    {{ Form::checkbox('acceptation',1,false, ['class' => 'custom-control-input', 'id' => 'acceptation', 'disabled' => ($checkDisableFlg ? true : false)]) }}
                                    <label class="custom-control-label" for="acceptation">
                                        {{trans('add_agreement.approve')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center mt-4 mb-5">
                            {!! Form::submit(trans('add_agreement.register'), ['class' => 'btn btn-lg btn-success btn--gradient-green w-25',($checkCorpId) ? 'disabled' : '']) !!}
                        </div>
                        {!!  Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
