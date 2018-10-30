@extends('layouts.app')

@section('content')
    <section class="agreement-system step-5">
        @include('agreement.system.progress')

        <h3>@lang('agreement_system.require_upload_document')</h3>

        <form method="post" id="agreementSystemStep5" enctype="multipart/form-data"
              action="{{route('agreementSystem.postStep5')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="m-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if(Session::has('missing_document_file'))
                        <div class="alert alert-danger">
                            <p class="m-0">{{ Session::get('missing_document_file') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <input hidden="true" value="{{$corpAgreementId}}" name="corpAgreementId"/>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h4 class="card-title p-2 text-white">ファイルアップロード</h4>
                        <div class="card-body pt-0">

                            <div class="alert alert-info alert-dismissible mb-3 pr-4">
                                <i class="fa fa-info-sign"></i> @lang('agreement_system.note_upload_file')
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    &times;
                                </button>
                            </div>

                            <label class="border-left-orange pl-3">
                                @lang('agreement_system.type_label') ({{$companyKind}})：
                                @if($companyKind == \App\Models\MCorp::CORP)
                                    {{\App\Models\MCorp::CORP_KIND[\App\Models\MCorp::CORP]}}
                                @else
                                    {{\App\Models\MCorp::CORP_KIND[\App\Models\MCorp::PERSON]}}
                                @endif
                            </label>
                            <table class="table table-bordered table-list">
                                <thead>
                                    <tr>
                                        <th width="150">@lang('agreement_system.type_label')</th>
                                        <th width="150">@lang('agreement_system.require_document_name')</th>
                                        <th>@lang('agreement_system.upload_label')</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr id="row0" name="genre-row">
                                        @if($companyKind == \App\Models\MCorp::CORP)
                                            <td data-label="@lang('agreement_system.type_label')">{{\App\Models\MCorp::CORP_KIND[\App\Models\MCorp::CORP]}}</td>
                                            <td data-label="@lang('agreement_system.require_document_name')">@lang('agreement_system.image_register')</td>
                                        @else
                                            <td data-label="@lang('agreement_system.type_label')">{{\App\Models\MCorp::CORP_KIND[\App\Models\MCorp::PERSON]}}</td>
                                            <td data-label="@lang('agreement_system.require_document_name')">@lang('agreement_system.image_card_id')</td>
                                        @endif
                                        <td data-label="@lang('agreement_system.upload_label')">
                                            <div>
                                                {!! __('agreement_system.note_image') !!}
                                                <br/>
                                                <input type="file" id="fileUpload" name="fileUpload" class="mw-100"/>
                                                <p class="text-primary">@lang('agreement_system.position_upload_image')</p>
                                                {!! __('agreement_system.format_image') !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table>
                                <tbody>
                                <tr>
                                    <th width="150">@lang('agreement_system.No')</th>
                                    <th width="350">@lang('agreement_system.file_label')</th>
                                    <th>@lang('agreement_system.btn_delete')</th>
                                </tr>

                                @foreach($fileList as $index => $attachFile)
                                    <tr>
                                        <td>
                                            {{$index + 1}}
                                        </td>
                                        <td>
                                            <a href="{{ route('agreementSystem.get.attachfile', ['attachFileId' => $attachFile->id]) }}"
                                               target="_blank">
                                                @if($attachFile->isFileTypePdf())
                                                    @lang('agreement_system.show_pdf')
                                                @else
                                                    <img
                                                        src="{{ url($attachFile->imageUrl ) }}"
                                                        width="100px"
                                                        height="50px"/>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="fileDelete btn btn--gradient-default" data-file_id="{{$attachFile->id}}">
                                                @lang('agreement_system.btn_delete')
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="agreement-system text-center">
                <button class="btn btn--gradient-default btn-lg" type="button" id="back_button">
                    @lang('agreement_system.btn.return')
                </button>
                <button class="btn btn--gradient-green btn-lg" type="submit" id="submit_step5">
                    @lang('agreement_system.btn_continue_upload')
                </button>
            </div>
            <input type="hidden" name="agreementAttachedFileId" id="agreementAttachedFileId" value=""/>
        </form>
    </section>
@endsection
@section('script')
    <script>
        var agreementSystem = {
            urlBackStep5: '{{route('agreementSystem.getStep4')}}',
            urlUploadFile: '{{route('agreementSystem.step5.fileUpload')}}',
            urlDeleteFile: '{{route('agreementSystem.step5.fileDelete')}}',
            confirmDeleteFile: '@lang('agreement_system.confirm_delete_file')',
            alertContentUploadFile: '@lang('agreement_system.alert_content_upload_file')'
        };
        var YES = '{{trans('agreement_admin.btn_yes')}}';
        var NO = '{{trans('agreement_admin.btn_no')}}';
    </script>
    <script src="{{ mix('js/utilities/st.common.js') }}"></script>
    <script src="{{ mix('js/pages/step5_agreement_system.js')}}"></script>
    <script>
        jQuery(document).ready(function () {
            Step5AgreementSystem.init();
        });
    </script>
@endsection
