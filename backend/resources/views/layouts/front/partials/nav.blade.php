<nav class="navbar navbar-expand-lg navbar-dark px-3 px-sm-0">
    <a class="navbar-brand" href="/">
        <img src="{{ asset('front_assets/imgs/new_logo.png') }}" alt="هودج">
    </a>
    <button id="menuBurger" class="btn menu__burger d-lg-none" type="button">
        <span class="menu__burger-box">
            <span class="menu__burger-inner"></span>
        </span>
    </button>
    <div id="menuOverlay" class="menu__burger-overlay"></div>
    <div id="navbarMenu" class="collapse navbar-collapse">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">{{ __('Home') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(app()->getLocale() . '/places') }}">{{ __('Places') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(app()->getLocale() . '/stores') }}">{{ __('Ezba') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(app()->getLocale() . '/zads') }}">{{ __('Zad') }}</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="#">الكرفانات</a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ url(app()->getLocale() . '/swalefs') }}">{{ __('Swalefs') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url(app()->getLocale() . '/events') }}">{{ __('Events') }}</a>
            </li>
            <li class="nav-item dr-vist-list">
                <a class="nav-link">{{ __('We invite you to visit') }}</a>
                <ul class="dr-nd-list-vist">
                    @foreach (\App\Models\Category::whereNull('parent_id')->get() as $place)
                        <li><a href="/places?category_id={{ $place->id }}" class="name-bro-dawn ">{{ $place->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            @auth
                <li class="nav-item">
                    <a style="cursor: pointer" target="_blank" class="nav-link" onclick="makeATripPopupShow()">
                        {{ __('Start your trip') }}
                    </a>
                </li>
            @endauth
            <li class="nav-item">
                <div class="dropdown mobile-lang-icon">
                    <button class="btn w-25 dropdown-toggle btn-sm rounded-pill" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa fa-earth"></i>
                        {{__(getLocaleName())}}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="/en">
                                <img src="/images/flags/us.svg" width="20" alt="United States flag"/> English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/ru">
                                <img src="/images/flags/ru.svg" width="15" alt="Russian"/> Русский
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/zh">
                                <img src="/images/flags/zh.svg" width="15" alt="Chinese"/> 简体中文
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/ar">
                                <img src="/images/flags/sa.svg" width="20" alt="Saudi Arabia flag"/> عربى
                            </a>
                        </li>
                    </ul>
                </div>

            </li>

        </ul>

        <ul class="socials-sm d-flex align-items-center d-lg-none">
            @if (auth()->check() &&
                    (in_array(1,
                        auth()->user()->roles->pluck('pivot.role_id')->toArray()) ||
                        in_array(2,
                            auth()->user()->roles->pluck('pivot.role_id')->toArray())))
                <li class="pl-2">
                    <a href="{{ url(app()->getLocale() . '/dashboard') }}" target="_blank" class=" btn btn-primary">
                        {{__("dashboard.dashboard")}}
                    </a>
                </li>
            @endif
            <li class="additional_bar_login_and_welcome">
                @if (!auth()->check())
                    <div class="mx-2 home_login_buttons_area">
                        <button class="home_login_button"
                                onclick="makeALoginPopupShow()">{{ __('dashboard.login') }}</button>
                    </div>
                @else
                    <div class="mx-2 pl-2 home_login_buttons_area">
                        <a class="home_login_button btn btn-primary" href="{{ route('front.my_trips') }}">
                            {{__("My trips")}}
                        </a>
                    </div>

                    <div class="home_welcome_text_area ml-2" dir="{{ app()->getLocale() != 'ar' ? 'ltr' : 'rtl' }}">
                        <span>{{ __('dashboard.welcome') }}</span>
                        <span>{{ auth()->user()->first_name }}</span>
                        <span>!</span>
                        <div class="pl-4">
                            <a href="{{ url(app()->getLocale() . '/logout') }}">
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </div>
                @endif
            </li>

            <div class="socaial-box d-flex justify-content-between align-items-center">
                <li>
                    <a href="#" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem" viewBox="0 0 22.999 23">
                            <g transform="translate(-873.501 -1199.5)">
                                <path
                                    d="M-3647.875-6713.5h-8.249a6.883,6.883,0,0,1-6.875-6.875v-8.251a6.883,6.883,0,0,1,6.875-6.875h8.249a6.883,6.883,0,0,1,6.875,6.875v8.251A6.882,6.882,0,0,1-3647.875-6713.5ZM-3656-6733a5.006,5.006,0,0,0-5,5v7a5.006,5.006,0,0,0,5,5h8a5.006,5.006,0,0,0,5-5v-7a5.006,5.006,0,0,0-5-5Z"
                                    transform="translate(4537 7935.5)" fill="#84548E" stroke="rgba(0,0,0,0)"
                                    stroke-miterlimit="10" stroke-width="1"/>
                                <path d="M5.5,0A5.5,5.5,0,1,0,11,5.5,5.5,5.5,0,0,0,5.5,0Z"
                                      transform="translate(879.5 1205.5)" fill="#84548E"/>
                                <path d="M3.438,6.875A3.438,3.438,0,1,1,6.875,3.438,3.442,3.442,0,0,1,3.438,6.875Z"
                                      transform="translate(881.563 1207.563)" fill="#84548E"/>
                                <circle cx="1.375" cy="1.375" r="1.375"
                                        transform="translate(889.583 1203.667)" fill="#84548E"/>
                            </g>
                        </svg>
                    </a>
                </li>
                <li class="">
                    <a href="#" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem" viewBox="0 0 22 18">
                            <path
                                d="M22,2.131a8.958,8.958,0,0,1-2.593.715A4.551,4.551,0,0,0,21.392.332a9,9,0,0,1-2.866,1.1,4.51,4.51,0,0,0-7.809,3.11,4.566,4.566,0,0,0,.117,1.036A12.784,12.784,0,0,1,1.531.831,4.569,4.569,0,0,0,2.928,6.9,4.459,4.459,0,0,1,.884,6.329c0,.019,0,.039,0,.058A4.539,4.539,0,0,0,4.5,10.842a4.5,4.5,0,0,1-2.038.079,4.522,4.522,0,0,0,4.216,3.156,9.017,9.017,0,0,1-5.606,1.945A9.028,9.028,0,0,1,0,15.958,12.7,12.7,0,0,0,6.918,18,12.8,12.8,0,0,0,19.761,5.07c0-.2,0-.393-.013-.588A9.188,9.188,0,0,0,22,2.131Z"
                                fill="#84548E"/>
                        </svg>
                    </a>
                </li>
                <li>
                    <a href="#" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem" viewBox="0 0 10.798 19.401">
                            <path
                                d="M10.392,0,7.8,0a4.482,4.482,0,0,0-4.79,4.775v2.2H.407a.4.4,0,0,0-.407.4v3.19a.4.4,0,0,0,.407.4h2.6v8.048a.4.4,0,0,0,.407.4h3.4a.4.4,0,0,0,.407-.4V10.957h3.045a.4.4,0,0,0,.407-.4V7.372a.391.391,0,0,0-.119-.28.413.413,0,0,0-.288-.116H7.224V5.11c0-.9.22-1.352,1.423-1.352h1.745a.4.4,0,0,0,.407-.4V.4A.4.4,0,0,0,10.392,0Z"
                                transform="translate(0)" fill="#84548E"/>
                        </svg>
                    </a>
                </li>
            </div>
        </ul>
    </div>

    <ul class="social-media d-none d-lg-flex align-items-center">
        <li class="additional_bar_login_and_welcome">
            @if (!auth()->check())
                <button class="btn btn-light btn-sm rounded-pill fw-bold px-3"
                        onclick="makeALoginPopupShow()">{{ __('dashboard.login') }}</button>
            @else
                <div class="home_welcome_text_area ml-2 " dir="{{ app()->getLocale() != 'ar' ? 'ltr' : 'rtl' }}">
                    <div class="user-list">
                        <div class="user-name-box">
                            <span><i class="fa-solid fa-user"></i></span>
                            <span>{{ __('dashboard.welcome') }}</span>
                            <span>{{ auth()->user()->first_name }}</span>
                            <span>!</span>
                        </div>
                        <div class="drop-dawn-list-user">
                            <div class=" ml-2 pl-2 home_login_buttons_area ac-user">
                                <a class="home_login_button btn btn-primary "
                                   href="{{ route('front.my_trips') }}">
                                    <span class="icon-label">
                                    <i class="fa-solid fa-plane-departure"></i>
                                    </span>
                                    {{ __('My trips') }}
                                </a>
                            </div>
                            @if (auth()->check() &&
                                    (in_array(1,
                                        auth()->user()->roles->pluck('pivot.role_id')->toArray()) ||
                                        in_array(2,
                                            auth()->user()->roles->pluck('pivot.role_id')->toArray())))
                                <div class=" ml-2 pl-2 home_login_buttons_area ac-user">
                                    <a class="home_login_button btn btn-primary "
                                       href="{{ url(app()->getLocale() . '/dashboard') }}"> <span class="icon-label"><i
                                                class="fa-solid fa-table-columns"></i></span> {{__("Control Panel")}}
                                    </a>
                                </div>
                            @endif
                            <div class="pl-4 ac-user">
                                <a href="{{ url(app()->getLocale() . '/logout') }}"
                                   class="home_login_button btn btn-primary log-outt">
                                <span class="icon-label">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                </span>
                                    {{__("Logout")}}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            <div class="dropdown mx-3">
                <button class="btn btn-light dropdown-toggle btn-sm rounded-pill px-2" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-earth"></i>
                    {{__(getLocaleName())}}
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="/en">
                            <img src="/images/flags/us.svg" width="15" alt="United States flag"/> English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/ru">
                            <img src="/images/flags/ru.svg" width="15" alt="Russian"/> Русский
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/zh">
                            <img src="/images/flags/zh.svg" width="15" alt="Chinese"/> 简体中文
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/ar">
                            <img src="/images/flags/sa.svg" width="15" alt="Saudi Arabia flag"/> عربى
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li>
            <a href="{{ $settings->where('group', 'app')->where('key', 'FACEBOOK')->first()->value ?? '#' }}"
               target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem" viewBox="0 0 22.999 23">
                    <g transform="translate(-873.501 -1199.5)">
                        <path
                            d="M-3647.875-6713.5h-8.249a6.883,6.883,0,0,1-6.875-6.875v-8.251a6.883,6.883,0,0,1,6.875-6.875h8.249a6.883,6.883,0,0,1,6.875,6.875v8.251A6.882,6.882,0,0,1-3647.875-6713.5ZM-3656-6733a5.006,5.006,0,0,0-5,5v7a5.006,5.006,0,0,0,5,5h8a5.006,5.006,0,0,0,5-5v-7a5.006,5.006,0,0,0-5-5Z"
                            transform="translate(4537 7935.5)" fill="#f9f6e5" stroke="rgba(0,0,0,0)"
                            stroke-miterlimit="10" stroke-width="1"/>
                        <path d="M5.5,0A5.5,5.5,0,1,0,11,5.5,5.5,5.5,0,0,0,5.5,0Z" transform="translate(879.5 1205.5)"
                              fill="#f9f6e5"/>
                        <path d="M3.438,6.875A3.438,3.438,0,1,1,6.875,3.438,3.442,3.442,0,0,1,3.438,6.875Z"
                              transform="translate(881.563 1207.563)" fill="#f9f6e5"/>
                        <circle cx="1.375" cy="1.375" r="1.375" transform="translate(889.583 1203.667)"
                                fill="#f9f6e5"/>
                    </g>
                </svg>
            </a>
        </li>
        <li class="mx-4">
            <a href="{{ $settings->where('group', 'app')->where('key', 'TWITTER')->first()->value ?? '#' }}"
               target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem" viewBox="0 0 22 18">
                    <path
                        d="M22,2.131a8.958,8.958,0,0,1-2.593.715A4.551,4.551,0,0,0,21.392.332a9,9,0,0,1-2.866,1.1,4.51,4.51,0,0,0-7.809,3.11,4.566,4.566,0,0,0,.117,1.036A12.784,12.784,0,0,1,1.531.831,4.569,4.569,0,0,0,2.928,6.9,4.459,4.459,0,0,1,.884,6.329c0,.019,0,.039,0,.058A4.539,4.539,0,0,0,4.5,10.842a4.5,4.5,0,0,1-2.038.079,4.522,4.522,0,0,0,4.216,3.156,9.017,9.017,0,0,1-5.606,1.945A9.028,9.028,0,0,1,0,15.958,12.7,12.7,0,0,0,6.918,18,12.8,12.8,0,0,0,19.761,5.07c0-.2,0-.393-.013-.588A9.188,9.188,0,0,0,22,2.131Z"
                        fill="#f9f6e5"/>
                </svg>
            </a>
        </li>
        <li>
            <a href="{{ $settings->where('group', 'app')->where('key', 'INSTGRAM')->first()->value ?? '#' }}"
               target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem" viewBox="0 0 10.798 19.401">
                    <path
                        d="M10.392,0,7.8,0a4.482,4.482,0,0,0-4.79,4.775v2.2H.407a.4.4,0,0,0-.407.4v3.19a.4.4,0,0,0,.407.4h2.6v8.048a.4.4,0,0,0,.407.4h3.4a.4.4,0,0,0,.407-.4V10.957h3.045a.4.4,0,0,0,.407-.4V7.372a.391.391,0,0,0-.119-.28.413.413,0,0,0-.288-.116H7.224V5.11c0-.9.22-1.352,1.423-1.352h1.745a.4.4,0,0,0,.407-.4V.4A.4.4,0,0,0,10.392,0Z"
                        transform="translate(0)" fill="#f9f6e5"/>
                </svg>
            </a>
        </li>
    </ul>
</nav>
