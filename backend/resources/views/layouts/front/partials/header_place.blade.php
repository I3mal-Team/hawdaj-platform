<!-- navbar -->
<div class="navbar-wrapper">
    {{--    <div class="navbar-wrapper__bg"></div>--}}
    <div class="container">
        @include('layouts.front.partials.nav')
        @if (!isset($map_most_pupular_places))
            @switch(request()->segment(2))
                @case('places')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                            </ol>
                        </nav>

                        <h1 class="page-title">{{ __('Places') }}</h1>

                    </div>
                    @break

                @case('place-details')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item {{ isset($place) ? '' : 'active' }}"><a href="/places">الاماكن</a>
                                </li>
                                @if (isset($place))
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $place->title }}
                                    </li>
                                @endif
                            </ol>
                        </nav>

                        @if (isset($place->title))
                            <h1 class="page-title">{{ $place->title }}</h1>
                        @endif

                    </div>
                    @break

                @case('store-details')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item {{ isset($store) ? '' : 'active' }}"><a
                                        href="/stores">{{__("Ezba")}}</a>
                                </li>
                                @if (isset($store))
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $store->title }}
                                    </li>
                                @endif
                            </ol>
                        </nav>
                        @if (isset($store->title))
                            <h1 class="page-title">{{ $store->title }}</h1>
                        @endif

                    </div>
                    @break

                @case('stores')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                            </ol>
                        </nav>
                        <h1 class="page-title"> {{ __('Ezba') }}</h1>
                    </div>
                    @break

                @case('zads')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                            </ol>
                        </nav>
                        <h1 class="page-title">{{ __('Zad') }}</h1>
                    </div>
                    @break

                @case('zad-details')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item {{ isset($store) ? '' : 'active' }}"><a
                                        href="/zads">{{ __('Zad') }}</a>
                                </li>
                                @if (isset($store))
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $store->title }}
                                    </li>
                                @endif
                            </ol>
                        </nav>

                        @if (isset($store->title))
                            <h1 class="page-title">{{ $store->title }}</h1>
                        @endif

                    </div>
                    @break

                @case('swalef')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item {{ isset($swalef) ? '' : 'active' }}"><a
                                        href="/swalefs">{{ __('Swalefs') }}</a>
                                </li>
                                @if (isset($swalef))
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $swalef->title }}
                                    </li>
                                @endif
                            </ol>
                        </nav>

                        @if (isset($swalef->title))
                            <h1 class="page-title">{{ $swalef->title }}</h1>
                        @endif

                    </div>
                    @break

                @case('swalefs')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                            </ol>
                        </nav>
                        <h1 class="page-title">{{ __('Swalefs') }}</h1>
                    </div>
                    @break

                @case('my_trips')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                            </ol>
                        </nav>
                        <h1 class="page-title">{{ __('My trips') }}</h1>
                    </div>
                    @break

                @case('view_trip')
                @case('action_selected_places')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item active"><a href="/my_trips">{{ __('My trips') }}</a>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="page-title"> {{ isset($trip) ? $trip->name : $daterange ?? $date }}</h1>
                    </div>
                    @break

                @case('event-details')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item {{ isset($event) ? '' : 'active' }}"><a
                                        href="/events">{{ __('Events') }}</a>
                                </li>
                                @if (isset($event))
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $event->title }}
                                    </li>
                                @endif
                            </ol>
                        </nav>

                        @if (isset($event->title))
                            <h1 class="page-title">{{ $event->title }}</h1>
                        @endif

                    </div>
                    @break

                @case('events')
                    <div class="breadcrumb__container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                            </ol>
                        </nav>
                        <h1 class="page-title"> {{ __('Events') }}</h1>
                    </div>
                    @break

                @default
            @endswitch
        @endif
    </div>
    @auth

        <div class="make-trip-icon" onclick="makeATripPopupShow()">
            <div class="trip-search-box">
                <span class="trip-search-txt">{{ __('Start your trip') }}</span>
                <div class="trip-search-btn">
                    <!-- <i class="fa-solid fa-plane-departure"></i> -->
                    <!-- <iframe src="https://embed.lottiefiles.com/animation/20678"></iframe> -->
                    <img src="{{ asset('front_assets/imgs/—Pngtree—camel vector illustration_8925219.png') }}"
                         alt="">
                </div>
            </div>
            <!-- <div class="the-trip-fixed-btn">
            <div class="trip-img-c">
                <img src="{{ asset('front_assets/imgs/Screenshosmall.png') }}" alt="">
            </div>
        </div> -->
        </div>
    @endauth

</div> <!-- navbar -->
