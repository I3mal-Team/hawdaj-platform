@extends('layouts.front.hawdaj_master')
@section('content')
    <div class="map-page">
        <!-- navbar -->
        <div class="navbar-wrapper">
            <div class="container">
                @include('layouts.front.partials.nav')
            </div>
        </div> <!-- navbar -->
        <!-- map page content -->
        <main>

            <div class="overflow-hidden position-relative">
                <!-- map options -->
                <div class="map-options">
                    <button id="positionBtn" class="btn map-options__control">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.8rem" height="1.8rem" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="12" cy="12" r="3"/>
                            <circle cx="12" cy="12" r="8"/>
                            <line x1="12" y1="2" x2="12" y2="4"/>
                            <line x1="12" y1="20" x2="12" y2="22"/>
                            <line x1="20" y1="12" x2="22" y2="12"/>
                            <line x1="2" y1="12" x2="4" y2="12"/>
                        </svg>
                    </button>

                    <!-- map type control -->
                    <div class="map-options__control menu-container">
                        <button id="googleMapTypeBtn" class="btn">
                            <svg class="show-icon" xmlns="http://www.w3.org/2000/svg" width="1.8rem" height="1.8rem"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="18" y1="6" x2="18" y2="6.01"/>
                                <path d="M18 13l-3.5 -5a4 4 0 1 1 7 0l-3.5 5"/>
                                <polyline points="10.5 4.75 9 4 3 7 3 20 9 17 15 20 21 17 21 15"/>
                                <line x1="9" y1="4" x2="9" y2="17"/>
                                <line x1="15" y1="15" x2="15" y2="20"/>
                            </svg>
                            <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem"
                                 fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                        <div class="map-options__control--menu">
                            <button class="btn active" data-map-type="roadmap">
                                <img width="32" src="{{ asset('front_assets/imgs/map-type-2.png') }}" alt="">
                            </button>
                            <button class="btn" data-map-type="terrain">
                                <img width="32" src="{{ asset('front_assets/imgs/map-type-1.png') }}" alt="">
                            </button>
                            <button class="btn" data-map-type="hybrid">
                                <img width="32" src="{{ asset('front_assets/imgs/map-type-3.png') }}" alt="">
                            </button>
                        </div>
                    </div>

                    <!-- most popular control -->
                    <button id="mostPopularBtn" class="btn map-options__control">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor"
                             viewBox="0 0 16 16">
                            <path
                                d="M7.84 4.1a.178.178 0 0 1 .32 0l.634 1.285a.178.178 0 0 0 .134.098l1.42.206c.145.021.204.2.098.303L9.42 6.993a.178.178 0 0 0-.051.158l.242 1.414a.178.178 0 0 1-.258.187l-1.27-.668a.178.178 0 0 0-.165 0l-1.27.668a.178.178 0 0 1-.257-.187l.242-1.414a.178.178 0 0 0-.05-.158l-1.03-1.001a.178.178 0 0 1 .098-.303l1.42-.206a.178.178 0 0 0 .134-.098L7.84 4.1z"/>
                            <path
                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z"/>
                        </svg>
                    </button>
                </div> <!-- map options -->

                <!-- serach box -->
                <div id="searchBox" class="search-box">
                    <div class="search-box__input">
                        <button id="searchBoxBackBtn" class="btn p-1 btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem" fill="currentColor"
                                 viewBox="0 0 16 16">
                                <path
                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                        <button id="searchBoxSearchBtn" class="btn p-1 btn-search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7"/>
                                <line x1="21" y1="21" x2="15" y2="15"/>
                            </svg>
                        </button>
                        <input oninput="search(this.value)" id="searchBoxInput" type="text" class="form-control"
                               placeholder="بحث">
                        <button id="searchBoxResetBtn" class="btn p-1 d-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor"
                                 viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                      d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                <path
                                    d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="search-box__content nice-scroll">
                        <!-- no results found -->
                        <div id="searchBoxNoResult" class="search-box__content--no-results">
                            <div class="d-flex justify-content-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="4rem" height="4rem" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="10" cy="10" r="7"/>
                                    <line x1="21" y1="21" x2="15" y2="15"/>
                                </svg>
                            </div>
                            <p class="mb-2">{{__("No results")}} </p>
                            <p>{{__("Type the name of the place to start the search")}}</p>
                        </div>
                        <!-- display place details -->
                        <div id="searchBoxPlaceDetails" class="search-box__content--place-details d-none">
                            <!-- palce details goes here -->
                        </div>
                        <!-- display search results -->
                        <div id="searchBoxResultsContainer" class="search-box__content--search-results d-none">


                        </div>
                    </div>
                </div> <!-- /serach box -->

                <!-- Map canvas -->
                <div id="mapPageCanvas" class="map-page__canvas"></div>
                <!-- /Map canvas -->
            </div>

            <div class="most-popular-places__container show">
                <div class="most-popular-places__slider">
                    @foreach ($map_most_pupular_places as $mplace)
                        <div>
                            <div class="map-card" data-place-id="{{ $mplace->id }}"
                                 data-map-lat="{{ $mplace->lat ?? '21.4505278' }}"
                                 data-map-lng="{{ $mplace->long ?? '38.9302735' }}">
                                <div class="card-ribbon">
                                    <span>{{ isset($mplace->type) && $mplace->type =='place' ? 'أشهر مكان' : 'أشهر متجر'}}</span>
                                </div>
                                <img class="map-card__img"
                                     src=" {{ asset($mplace->image ?? 'front_assets/imgs/zad1.jpg')}}">
                                <div class="map-card__footer">
                                    <h5 class="map-card__title">{{ $mplace->title ?? '' }}</h5>
                                    <p class="map-card__text"> {{ $mplace->city ? $mplace->city->name : '' }},
                                        {{ $mplace->region ? $mplace->region->name : '' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="rate d-flex align-items-center"><span class="mx-1"><svg
                                                        width="14" height="13" viewBox="0 0 14 13" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                    fill="#FFCA00"/>
                                            </svg></span><span class="mx-1">{{ $mplace->rate }}</span></div>
                                            <span class="views mr-3">({{ $mplace->review }} {{ __("Review") }})</span>
                                        </div>
                                        <span class="btn map-card__btn">
                                    <a href="/{{$mplace->type}}-details/{{$mplace->slug}}" class="btn map-card__btn"><svg
                                            width="12" height="12" viewBox="0 0 5 9" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.8088 8.80184C4.93123 8.67495 5 8.50288 5 8.32347C5 8.14405 4.93123 7.97198 4.8088 7.8451L1.57628 4.49585L4.8088 1.14661C4.92776 1.019 4.99358 0.848082 4.99209 0.670675C4.9906 0.493269 4.92192 0.323565 4.80085 0.198114C4.67977 0.0726652 4.51598 0.00150681 4.34476 -3.52859e-05C4.17353 -0.00157642 4.00857 0.0666227 3.88541 0.189874L0.191199 4.01749C0.0687744 4.14437 0 4.31644 0 4.49585C0 4.67527 0.0687744 4.84734 0.191199 4.97422L3.88541 8.80184C4.00787 8.92868 4.17394 8.99994 4.34711 8.99994C4.52027 8.99994 4.68634 8.92868 4.8088 8.80184Z"
                                                fill="white"/>
                                        </svg>
                                    </a>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main> <!-- /map page content -->
    </div>
@endsection

@section('scripts')
    <script src="{{asset('front_assets/libs/select2/select2.min.js')}}"></script>
    {{-- <script src="{{ asset('front_assets/js/map.fullscreen.min.js') }}"></script> --}}
    <script src="{{ asset('front_assets/js/mapFullscreen.js') }}"></script>
    <script>
        // handle place card click
        $('.map-card').on('click', function () {
            let lat = $(this).attr('data-map-lat');
            let lng = $(this).attr('data-map-lng');

            if (lat && lng) {
                let coordinates = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };
                let placeID = $(this).attr('data-place-id');
                displayPlaceOnMap(map, coordinates, placeID);
            }
        });

        // toggle GoogleMap type options
        $('#googleMapTypeBtn').on('click', function () {
            $(this).toggleClass('active');
            $('#googleMapTypeBtn+.map-options__control--menu').toggleClass('show');
        });

        // change google map type
        $('.map-options__control--menu .btn').each((idx, ele) => {
            $(ele).on('click', function () {
                map.setMapTypeId($(this).attr('data-map-type'));

                $('.map-options__control--menu .btn').removeClass('active');
                $(this).addClass('active');
            });
        });

        // search box
        $('#searchBoxInput').on('focus', () => {
            if ($('#searchBoxPlaceDetails').children.length) {
                $('#searchBoxPlaceDetails').html('');
                $('#searchBoxPlaceDetails').addClass('d-none');
            }

            showSearchBox();
        });

        // search on typing
        $('#searchBoxInput').on('keyup', function (event) {
            search(event.target.value);
        });

        $('#searchBoxSearchBtn').on('click', () => {
            $('#searchBoxInput').focus();
        });

        $('#searchBoxBackBtn').on('click', () => {
            hideSearchBox();
        });

        // reset search
        $('#searchBoxResetBtn').on('click', function () {
            resetSearchBox();
        });

        // most populat toggler
        $('#mostPopularBtn').on('click', () => {
            $('.most-popular-places__container').toggleClass('show');
        });
        var places = @json($places);
        intMapFullscreen(JSON.stringify(places));

        // setTimeout(() => {
        //     $.ajax({
        //         url: "{{ route('front.getFullMapData') }}",
        //         type: "GET",
        //         data: {
        //             size: 1000,
        //         },
        //         success: function(response) {
        //             intMapFullscreen(response);
        //         }
        //     });
        // }, 1000);

        $('.most-popular-places__slider').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            rtl: true,
            infinite: true,
            arrows: true,
            responsive: [{
                breakpoint: 1499,
                settings: {
                    slidesToShow: 4,
                }
            },
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        centerMode: true,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 350,
                    settings: {
                        slidesToShow: 1,
                        centerMode: false,
                    }
                },
            ]
        });
    </script>
@endsection
