<div>
    <label class="form-category__label mb-4 d-none d-sm-block">
        @lang('agreement_system.genre_info_title')
    </label>
    <div id="category_info">
        <form id="categoryDialogFormId" method="post" action="{{route('post.category_dialog')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-8">
                    <p class="comment_space">
                        <span class="text--orange">● <strong>@lang('agreement_system.genre_base_question')</strong></span>
                        @lang('agreement_system.genre_base_answer')<br/>
                        <span class="text--orange">● <strong>@lang('agreement_system.genre_intro_question')</strong></span>
                        @lang('agreement_system.genre_intro_answer')
                    </p>
                </div>
                <div class="col-lg-4">
                    <p class="comment_space_sm">
                        <strong> @lang('agreement_system.introduce_genre')</strong><br/>
                        @lang('agreement_system.level_A')<br/>
                        @lang('agreement_system.level_B')<br/>
                        @lang('agreement_system.level_C')
                    </p>
                </div>
                <div class="col-12">
                    <div class="p-3 border genre-group">
                        <p class="m-0">
                            @lang('agreement_system.common_genre_info')
                        </p>
                        <ul class="list-inline m-0">
                            @foreach ($genreNote as $index => $valueGenreNote)
                                <li class="list-inline-item">
                                    ・<a href="{{$valueGenreNote['genreNameHref']}}" class="genre-item">{{$valueGenreNote['genreName']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{--contracted_base_genre--}}
            <label class="form-category__label mb-4 mt-5 d-none d-sm-block">
                @lang('agreement_system.contracted_base_genre')
            </label>
            @foreach($genreGroupBase as $index => $genre)
                @if($genre['categoryCount'] > 0)
                    <label class="border_left_orange">{{$genre['genreGroup']}}</label>
                    <div class="row">
                        <div class="col-lg-4 pr-lg-0">
                            @component('agreement.system.component.category_dialog_table', [
                                    'genre' => $genre,
                                    'categoryListNumber' => 'categoryList1',
                                    'selectList' => $selectList
                                ])
                            @endcomponent
                        </div>
                        <div class="col-lg-4 px-lg-0">
                            @component('agreement.system.component.category_dialog_table', [
                                                               'genre' => $genre,
                                                               'categoryListNumber' => 'categoryList2',
                                                               'selectList' => $selectList
                                                           ])
                            @endcomponent
                        </div>
                        <div class="col-lg-4 pl-lg-0">
                            @component('agreement.system.component.category_dialog_table', [
                                                               'genre' => $genre,
                                                               'categoryListNumber' => 'categoryList3',
                                                               'selectList' => $selectList
                                                           ])
                            @endcomponent
                        </div>
                    </div>

                    <br>
                    <div class="row justify-content-center">
                        <button class="btn btn--gradient-green w-30 text-white selectCategory"
                                type="button">@lang('agreement_system.btn_register')</button>
                    </div>
                    <br>
                @endif
            @endforeach
            {{--introduction_base_genre--}}
            <label class="form-category__label mb-4 mt-5 d-none d-sm-block">
                @lang('agreement_system.introduction_base_genre')
            </label>
            @foreach($genreGroupIntro as $index => $genre)
                @if($genre['categoryCount'] > 0)
                    <label class="border_left_orange">{{$genre['genreGroup']}}</label>
                    <div class="row">
                        <div class="col-lg-4 pr-lg-0">
                            @component('agreement.system.component.category_dialog_table', [
                                                                'genre' => $genre,
                                                                'categoryListNumber' => 'categoryList1',
                                                                'selectList' => $selectList
                                                            ])
                            @endcomponent
                        </div>
                        <div class="col-lg-4 px-lg-0">
                            @component('agreement.system.component.category_dialog_table', [
                                                               'genre' => $genre,
                                                               'categoryListNumber' => 'categoryList2',
                                                               'selectList' => $selectList
                                                           ])
                            @endcomponent
                        </div>
                        <div class="col-lg-4 pl-lg-0">
                            @component('agreement.system.component.category_dialog_table', [
                                                               'genre' => $genre,
                                                               'categoryListNumber' => 'categoryList3',
                                                               'selectList' => $selectList
                                                           ])
                            @endcomponent
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-center">
                        <button class="btn btn--gradient-green w-30 text-white selectCategory"
                                type="button">
                            @lang('agreement_system.btn_register')
                        </button>
                    </div>
                    <br>
                @endif
            @endforeach
        </form>
    </div>
</div>
