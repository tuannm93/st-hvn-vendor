<header>
    @php
        $logined = Auth::user();
        $menu = [];
        if (!empty($logined)) {
            $affiliationId = $logined->affiliation_id;
            $menu = getMenuByRole($logined->auth);
        }

        $isPageCustom = false;
        switch (Route::current()->getName()) {
            case 'bill.moneyCorrespond':
            case 'auction.proposal':
            case 'auction.support':
                $isPageCustom = true;
                break;
            default:
                $isPageCustom = false;
                break;
        }
		$url = '/';
    @endphp

    <nav class="navbar navbar-expand-lg navbar-light bg-white-light">
        @if(!$isPageCustom)
        <div class="container px-3 pb-2">
			@auth
				@php
					$url = (auth()->user()->auth == 'affiliation') ? route('auction.index') : '/';
				@endphp
			@endauth
            {{--Logo brand--}}
            <a class="navbar-brand" href="{{ $url }}">
                {{--{{ config('app.name', 'Laravel') }}--}}
                <span class="sr-only">Sharing Tech</span>
                <img src="{{asset('assets/img/mover.png')}}" alt="Logo" class="img-fluid">
            </a>

            @auth
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-navbar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <ul class="navbar-text d-none d-lg-block m-0">
                    <li>
                        ユーザID：{{ $logined->user_id }}
                    </li>
                    <li>
                        ユーザ名：{{ $logined->user_name }}
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            ログアウト
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            @endauth
        </div>

        <div class="collapse navbar-collapse d-lg-block w-100 mt-lg-2" id="app-navbar-collapse">
            <div class="container">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav justify-content-lg-start w-100">
                    <!-- Authentication Links -->
                    @auth
                        @foreach ($menu as $key => $mnu)
                        <li>
                            @php
                                if (!empty($mnu['route'])) {
                                    if($mnu['route'] == 'affiliation.category') {
                                        $route = route($mnu['route'], ['id' => $affiliationId]);
                                    } elseif ($mnu['route'] == 'affiliation.agreement.index') {
                                        $route = route($mnu['route'], ['corpId' => $affiliationId]);
                                    } elseif ($mnu['route'] == 'commission.index') {
                                        $route = route($mnu['route'], ['affiliationId' => 'none']);
                                    } else {
                                        $route = route($mnu['route']);
                                    }
                                } else {
                                    $route = '';
                                }

                            @endphp
                            <a href="{{$route}}">{{trans($mnu['name'])}}</a>
                            @if($mnu['route'] == 'notice_info.index' && isset($numberUnreadNoticeInfo) && $numberUnreadNoticeInfo >0)
                                <img src="{{asset('/img/new_icon.jpg')}}" class="new-icon">
                            @endif
                        </li>
                        @endforeach
                        <li class="dropdown-divider d-lg-none"></li>
                        <li class="d-lg-none">
                            <p class="m-0 text-muted">
                                <em>ユーザID：{{ $logined->user_id }}</em> <br>
                                <em>ユーザ名：{{ $logined->user_name }}</em>
                            </p>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                            document.getElementById('logout-form-mobile').submit();">
                                ログアウト
                            </a>

                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
        @endif
    </nav>
</header>
