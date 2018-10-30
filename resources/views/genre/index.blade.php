@extends('layouts.app')
@php
    $id = isset($corp_data['id']) ? $corp_data['id'] : "";
    $saveGenreGroup = 1;
    $tableAgree = [];
    $tableIntro = [];
    $viewAgree = '';
    $viewIntro = '';
    $chkId = "";
    $titleList = Config::get('datacustom.genre_group_list');
    $autoCallList = getDropList('オートコール区分');
    foreach($allGenre as $key => $val):
        if ($val['registration_mediation'] > 0) {
            $chkId .= "-". $val['id'];
        }
        $genre_group = $val['genre_group'];
        if ($val['commission_type'] == 1 && $genre_group > 0) {

            if ($genre_group != $saveGenreGroup) {
                $tableAgree[$saveGenreGroup] = $viewAgree;
                $viewAgree = '';
                $saveGenreGroup = $genre_group;
            }
            $viewAgree = $viewAgree.View::make('genre.component.genre_table', ['key' => $key, 'val' => $val, 'autoCallList' => $autoCallList, 'acaAccount' => $acaAccount]);
        } else if ($val['commission_type'] == 2 && $genre_group > 0) {
            if ($genre_group != $saveGenreGroup) {
                $tableIntro[$saveGenreGroup] = $viewIntro;
                $viewIntro = '';
                $saveGenreGroup = $genre_group;
            }
            $viewIntro = $viewIntro.View::make('genre.component.genre_table', ['key' => $key, 'val' => $val, 'autoCallList' => $autoCallList, 'acaAccount' => $acaAccount]);
        }
    endforeach;
    if( $viewAgree != '' ){
        $tableAgree[$saveGenreGroup] = $viewAgree;
    }
    if( $viewIntro != '' ){
        $tableIntro[$saveGenreGroup] = $viewIntro;
    }
    if (strlen($chkId) > 0) {
        $chkId = substr($chkId, 1);
    }
@endphp
@section('content')
    <div class="genre-index">
        @if (Session::has('InputError'))
            <div class="alert alert-danger">{{ Session::get('InputError') }}</div>
        @endif
        @if (Session::has('Update'))
            <div class="alert alert-success">{{ Session::get('Update') }}</div>
        @endif
        <div class="form-category pt-4">
            <label class="form-category__label pb-1">{{trans('genre.genre_title')}}</label>
            {{ Form::open(['id'=>'genre', 'enctype' => 'multipart/form-data', 'type'=>'post', 'route'=>['genre.regist', $id], 'accept-charset'=>"UTF-8" ]) }}
            {{ Form::input('hidden', 'chk_id', $chkId, ['id' => 'chk_id']) }}
            <label class="form-category__label pt-4 pb-1">{{ trans('genre.h_title') }}</label>
            @foreach ($tableAgree as $key => $val)
                <div class="form-category__item mr-0 ml-0">
                    <label class="border_left_orange col-12 col-sm-12 col-md-12"><a name="genre_key_{{ $key }}"></a>{{ $titleList[$key] }}</label>
                    <div class="mb-0 block-header row mr-0 ml-0">
                        <div class="item-header item-hearder-1 col-12 col-sm-6 col-md-4">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th class="text-left border-top-0 border-left-0 border-bottom-0 w-30">ID</th>
                                    <th class="text-left border-top-0 border-bottom-0 w-40">{{ trans('genre.name') }}</th>
                                    <th class="text-left border-top-0 border-right-0 border-bottom-0 w-30">{{ trans('genre.subscription_registration') }}<br>{{ trans('genre.flag') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="item-header item-hearder-2 col-12 col-sm-6 col-md-4">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th class="text-left border-top-0 border-left-0 border-bottom-0 w-30">ID</th>
                                    <th class="text-left border-top-0 border-bottom-0 w-40">{{ trans('genre.name') }}</th>
                                    <th class="text-left border-top-0 border-right-0 border-bottom-0 w-30">{{ trans('genre.subscription_registration') }}<br>{{ trans('genre.flag') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="item-header item-hearder-3 col-12 col-sm-6 col-md-4">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th class="text-left border-top-0 border-left-0 border-bottom-0 w-30">ID</th>
                                    <th class="text-left border-top-0 border-bottom-0 w-40">{{ trans('genre.name') }}</th>
                                    <th class="text-left border-top-0 border-right-0 border-bottom-0 w-30">{{ trans('genre.subscription_registration') }}<br>{{ trans('genre.flag') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row mr-0 ml-0">
                        @php echo $val  @endphp
                    </div>
                </div>
            @endforeach
            <div class="regist_block pt-4 pb-4 text-center">
                {{ Form::input('submit', 'regist', trans('genre.submit'), ['disabled'=>$acaAccount, 'class' => 'btn btn--gradient-green col-6 col-sm-4 col-md-3']) }}
            </div>
            <label class="form-category__label pt-4 pb-1">{{ trans('genre.h2_title') }}</label>
            @foreach ($tableIntro as $key => $val)
                <div class="form-category__item mr-0 ml-0">
                    <label class="border_left_orange col-12 col-sm-12 col-md-12"><a name="genre_key_{{ $key }}"></a>{{ $titleList[$key] }}</label>
                    <div class="mb-0 block-header row mr-0 ml-0">
                        <div class="item-header item-hearder-1 col-12 col-sm-6 col-md-4">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th class="text-left border-top-0 border-left-0 border-bottom-0 w-30">ID</th>
                                    <th class="text-left border-top-0 border-bottom-0 w-40">{{ trans('genre.name') }}</th>
                                    <th class="text-left border-top-0 border-right-0 border-bottom-0 w-30">{{ trans('genre.subscription_registration') }}<br>{{ trans('genre.flag') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="item-header item-hearder-2 col-12 col-sm-6 col-md-4">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th class="text-left border-top-0 border-left-0 border-bottom-0 w-30">ID</th>
                                    <th class="text-left border-top-0 border-bottom-0 w-40">{{ trans('genre.name') }}</th>
                                    <th class="text-left border-top-0 border-right-0 border-bottom-0 w-30">{{ trans('genre.subscription_registration') }}<br>{{ trans('genre.flag') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="item-header item-hearder-3 col-12 col-sm-6 col-md-4">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th class="text-left border-top-0 border-left-0 border-bottom-0 w-30">ID</th>
                                    <th class="text-left border-top-0 border-bottom-0 w-40">{{ trans('genre.name') }}</th>
                                    <th class="text-left border-top-0 border-right-0 border-bottom-0 w-30">{{ trans('genre.subscription_registration') }}<br>{{ trans('genre.flag') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row mr-0 ml-0">
                        @php echo $val  @endphp
                    </div>
                </div>
            @endforeach
            <div class="regist_block pt-4 pb-4 text-center">
                {{ Form::input('submit', 'regist', trans('genre.submit'), ['disabled'=>$acaAccount, 'class' => 'btn btn--gradient-green col-6 col-sm-4 col-md-3']) }}
            </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/lib/jquery.validate.min.js') }}"></script>
    <script src="{{ mix('js/lib/localization/jquery.validate.messages_ja.js') }}"></script>
    <script src="{{ mix('js/utilities/form.validate.js') }}"></script>
    <script>
        FormUtil.validate('#genre');
    </script>
@endsection
