<!DOCTYPE html>
{{-- <html lang="ar" dir="rtl"> --}}
<html dir="{{ app()->getLocale() != 'ar' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('front_assets/imgs/fav.png') }}" type="image" sizes="16x16">
    <title>{{ __('hawdaj_title') }}</title>
    <!-- SEO Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ asset('/') }}">
    <link rel="canonical" href="{{ url('/') }}"/>

    @if (isset($place) && isset($place->ceo))
        <meta name="description" content="{{ $place->ceo->description ?? '' }}">
        <meta name="keywords" content="{{ $place->ceo->keyWordsSentense() }}">
        <meta name="robots" content="{{ $place->ceo->keyWordsSentense() }}">
        <meta property="og:type" content="{{ $place->ceo->type ?? '' }}"/>
        <meta property="og:title" content="{{ $place->ceo->title ?? '' }}"/>
        <meta property="og:description" content="{{ $place->ceo->description ?? '' }}"/>
        <meta property="og:image" content="{{ asset($place->image) ?? '' }}"/>
        <meta property="og:url" content="{{ asset($place->ceo->link) ?? '' }}"/>
        <meta property="og:site_name" content="Hwdaj"/>
    @endif
    <!-- Styles -->

    @yield('style')
    @if (app()->getLocale() != 'ar')
        <link rel="stylesheet" href="{{ asset('css/vendor.css') }}"/>
    @else
        <link rel="stylesheet" href="{{ asset('css/vendor.rtl.css') }}"/>
    @endif
    <link rel="stylesheet" href="{{ asset('front_assets/libs/intlTelInput/css/intlTelInput.min.css') }}">
    <link rel="stylesheet" href="{{asset('front_assets/libs/select2/select2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('front_assets/libs/font-awesome/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('front_assets/libs/toastr/toastr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front_assets/libs/daterangepicker/daterangepicker.css') }}"/>

    @if (app()->getLocale() != 'ar')
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}"/>
    @else
        <link rel="stylesheet" href="{{ asset('/css/app.rtl.css') }}"/>
    @endif
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"/>
    <script>
        window.isRTL = @json(app()->getLocale() === 'ar')
    </script>
    <script>
        // initMap declaration
        // to hide 'initMap is not a function' error
        function initMap() {
        }
    </script>
</head>

<body class="overflow-hidden">
<div id="google_translate_element"></div>

<!-- loader -->
<div class="loader-container">
    <!-- <div class="loader">
        <div class="inner"></div>
    </div> -->
    <iframe src="https://lottie.host/?file=2ddb7a85-0ea8-464f-9ecd-6a55b8f442bd/zE42qap4OI.lottie"></iframe>
</div>

@php
    $popup = \App\Models\Event::where('active', 1)
    ->where('display_type', 'banner')
    ->where('date_from' , '<=' , now())
    ->where('date_to' , '>=' , now())
    ->latest()
    ->first();
@endphp
@if ($popup)
    @if (!session()->has('modal'))
        <div class="adds-layer">
            <div class="card-adds">
                <span class="sm-close"><i class="fa-solid fa-circle-xmark"></i></span>
                <a href="event-details/{{ $popup->slug }}">
                    <div class="add-img">
                        <img src="{{ asset($popup->image ?? 'front_assets/imgs/16414227701709.jpg') }}" alt="">
                    </div>
                    <div class="data-layer">
                        <div class="details-later">
                            <h3>{{$popup->title ?? ''}}</h3>
                            <p>
                                {!! $popup->description ? \Illuminate\Support\Str::limit($popup->description, 150, $end='...') : '' !!}
                            </p>
                            <button onclick='goto({{$popup->slug}})'>{{__("Go to content")}}</button>
                        </div>
                    </div>
                </a>

            </div>
        </div>
        <script>
            $(window).load(function () {
                $('#popup').modal('show');
            });
        </script>
        {{ session()->put('modal', 'shown') }}
    @endif
@endif

@if (!isset($map_most_pupular_places))
    @if (isset($place) || isset($contactus) || isset($places) || isset($store) || isset($stores))
        @include('layouts.front.partials.header_place')
    @else
        @include('layouts.front.partials.header')
    @endif
@endif

@yield('content')

@if (!isset($map_most_pupular_places))
    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 logo">
                    <div class="d-flex align-items-center mb-4">
                        <img width="150" src="{{ asset('front_assets/imgs/new_logo.png') }}" alt="هودج">
                        {{--                        <span--}}
                        {{--                            class="tajawal-bold heading">{{ $settings->where('group', 'main_services')->where('key', 'SECTION_TITLE')->first()->value ?? 'test' }}</span>--}}
                    </div>
                    <p class="mb-4">
                        {{ $settings->where('group', 'main_services')->where('key', 'SECTION_DESCRIPTION')->first()->value ?? 'test' }}
                    </p>
                    <div class="mb-4 mb-lg-0">
                        <h6 class="heading mb-2 tajawal-bold">
                            <span></span>
                            {{__("Follow us on")}}
                        </h6>
                        <ul class="social-media d-flex align-items-center">
                            <li>
                                <a href="{{ $settings->where('group', 'app')->where('key', 'INSTGRAM')->first()->value ?? '#' }}"
                                   target="_blank">
                                    <span>{{__("INSTGRAM")}}</span>
                                    <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem"
                                                 viewBox="0 0 22.999 23">
                                                <g transform="translate(-873.501 -1199.5)">
                                                    <path
                                                        d="M-3647.875-6713.5h-8.249a6.883,6.883,0,0,1-6.875-6.875v-8.251a6.883,6.883,0,0,1,6.875-6.875h8.249a6.883,6.883,0,0,1,6.875,6.875v8.251A6.882,6.882,0,0,1-3647.875-6713.5ZM-3656-6733a5.006,5.006,0,0,0-5,5v7a5.006,5.006,0,0,0,5,5h8a5.006,5.006,0,0,0,5-5v-7a5.006,5.006,0,0,0-5-5Z"
                                                        transform="translate(4537 7935.5)" fill="#f9f6e5"
                                                        stroke="rgba(0,0,0,0)" stroke-miterlimit="10"
                                                        stroke-width="1"/>
                                                    <path d="M5.5,0A5.5,5.5,0,1,0,11,5.5,5.5,5.5,0,0,0,5.5,0Z"
                                                          transform="translate(879.5 1205.5)" fill="#f9f6e5"/>
                                                    <path
                                                        d="M3.438,6.875A3.438,3.438,0,1,1,6.875,3.438,3.442,3.442,0,0,1,3.438,6.875Z"
                                                        transform="translate(881.563 1207.563)" fill="#f9f6e5"/>
                                                    <circle cx="1.375" cy="1.375" r="1.375"
                                                            transform="translate(889.583 1203.667)" fill="#f9f6e5"/>
                                                </g>
                                            </svg>
                                        </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $settings->where('group', 'app')->where('key', 'TWITTER')->first()->value ?? '#' }}"
                                   target="_blank">
                                    <span>{{__("TWITTER")}}</span>
                                    <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem"
                                                 viewBox="0 0 22 18">
                                                <path
                                                    d="M22,2.131a8.958,8.958,0,0,1-2.593.715A4.551,4.551,0,0,0,21.392.332a9,9,0,0,1-2.866,1.1,4.51,4.51,0,0,0-7.809,3.11,4.566,4.566,0,0,0,.117,1.036A12.784,12.784,0,0,1,1.531.831,4.569,4.569,0,0,0,2.928,6.9,4.459,4.459,0,0,1,.884,6.329c0,.019,0,.039,0,.058A4.539,4.539,0,0,0,4.5,10.842a4.5,4.5,0,0,1-2.038.079,4.522,4.522,0,0,0,4.216,3.156,9.017,9.017,0,0,1-5.606,1.945A9.028,9.028,0,0,1,0,15.958,12.7,12.7,0,0,0,6.918,18,12.8,12.8,0,0,0,19.761,5.07c0-.2,0-.393-.013-.588A9.188,9.188,0,0,0,22,2.131Z"
                                                    fill="#f9f6e5"/>
                                            </svg>
                                        </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $settings->where('group', 'app')->where('key', 'FACEBOOK')->first()->value ?? '#' }}"
                                   target="_blank">
                                    <span>{{__("FACEBOOK")}}</span>
                                    <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="0.8rem"
                                                 viewBox="0 0 10.798 19.401">
                                                <path
                                                    d="M10.392,0,7.8,0a4.482,4.482,0,0,0-4.79,4.775v2.2H.407a.4.4,0,0,0-.407.4v3.19a.4.4,0,0,0,.407.4h2.6v8.048a.4.4,0,0,0,.407.4h3.4a.4.4,0,0,0,.407-.4V10.957h3.045a.4.4,0,0,0,.407-.4V7.372a.391.391,0,0,0-.119-.28.413.413,0,0,0-.288-.116H7.224V5.11c0-.9.22-1.352,1.423-1.352h1.745a.4.4,0,0,0,.407-.4V.4A.4.4,0,0,0,10.392,0Z"
                                                    transform="translate(0)" fill="#f9f6e5"/>
                                            </svg>
                                        </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 site-links">
                    <div class="row w-100">
                        {{-- <div class="col-6 col-sm-4 col-md-3 mb-4 mb-md-0">
                            <div>
                                <h6 class="heading tajawal-bold">
                                    <span></span>
                                    تأجير كرفان
                                </h6>
                                <ul>
                                    <li>
                                        <a href="/caravan.html">
                                            الأقرب لك
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            المتاح حاليا
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            فئات الكرفان
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            أسعار التأجير
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            كرفان مميز
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                        <div class="col-6 col-sm-4 col-md-3 mb-4 mb-md-0">
                            <div>
                                <h6 class="heading tajawal-bold">
                                    <span></span>
                                    {{ __('Stores') }}
                                </h6>
                                <ul>
                                    @foreach (\App\Models\CategoryOfStore::whereNull('parent_id')->take(5)->get() as $s_cat)
                                        <li>
                                            <a href="{{route('front.stores' , ["category_id" => [$s_cat->id]])}}">{{ $s_cat->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                        <div class="col-6 col-sm-4 col-md-3">
                            <div>
                                <h6 class="heading tajawal-bold">
                                    <span></span>
                                    {{ __('Featured places') }}
                                </h6>
                                <ul>
                                    @foreach (\App\Models\Category::whereNull('parent_id')->take(5)->get() as $s_cat)
                                        <li>
                                            <a href="/places?category_id={{ $s_cat->id }}">{{ $s_cat->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3">
                            <div>
                                <h6 class="heading tajawal-bold">
                                    <span></span>
                                    {{ __('Website Map') }}
                                </h6>
                                <ul>
                                    <li>
                                        <a href="{{route('front.contactus')}}">
                                            {{__('Contact Us')}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        {{-- <div class="col-6 col-md-3">
                            <div>
                                <h6 class="heading tajawal-bold">
                                    <span></span>
                                    طلب خاص
                                </h6>
                                <ul>
                                    <li>
                                        <a href="#">
                                            تقديم المواصفات
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            احتساب التكلفة
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="year">
            <div class="container">
                <p class="mb-0 d-flex justify-content-start">
                    &copy; 2022
                </p>
            </div>
        </div> --}}
    </footer>
@endif

<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('front_assets/libs/maps/highmaps.js') }}"></script>
<script src="{{ asset('front_assets/libs/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script>
    $(document).ready(function () {
        $(".adds-layer").addClass("active")
        $(".sm-close").click(function () {
            $(".adds-layer").removeClass("active")
        })

    });
</script>
<script>
    Highcharts.maps["ksa"] = {
        "title": "Saudi Arabia",
        "version": "2.0.1",
        "type": "FeatureCollection",
        "copyright": "Copyright (c) 2022 Highsoft AS, Based on data from Natural Earth",
        "copyrightShort": "Natural Earth",
        "copyrightUrl": "http://www.naturalearthdata.com",
        "crs": {
            "type": "name",
            "properties": {
                "name": "urn:ogc:def:crs:EPSG:32638"
            }
        },
        "hc-transform": {
            "default": {
                "crs": "+proj=utm +zone=38 +datum=WGS84 +units=m +no_defs",
                "scale": 0.00032887600928,
                "jsonres": 15.5,
                "jsonmarginX": -999,
                "jsonmarginY": 9851.0,
                "xoffset": -525691.526649,
                "yoffset": 3568869.22221
            }
        },
        "features": [{
            "type": "Feature",
            "id": "SA.4293",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.50,
                "hc-middle-y": 0.53,
                "hc-key": "sa-4293",
                "hc-a2": "NU",
                "labelrank": "20",
                "hasc": "-99",
                "alt-name": null,
                "woe-id": "-99",
                "subregion": null,
                "fips": null,
                "postal-code": null,
                "name": null,
                "country": "Saudi Arabia",
                "type-en": null,
                "region": null,
                "longitude": "41.6833",
                "woe-name": null,
                "latitude": "16.9326",
                "woe-label": null,
                "type": null
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [2430, 1199],
                        [2410, 1212],
                        [2427, 1235],
                        [2448, 1220],
                        [2430, 1199]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.TB",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.45,
                "hc-middle-y": 0.25,
                "hc-key": "sa-tb",
                "hc-a2": "TB",
                "labelrank": "6",
                "hasc": "SA.TB",
                "alt-name": "Tabouk",
                "woe-id": "2346962",
                "subregion": null,
                "fips": "SA19",
                "postal-code": "TB",
                "name": "تبوك",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 10,
                "longitude": "36.8014",
                "woe-name": "Tabuk",
                "latitude": "27.9146",
                "woe-label": "Tabuk, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "MultiPolygon",
                "coordinates": [
                    [
                        [
                            [-42, 6201],
                            [-132, 6246],
                            [-117, 6267],
                            [-61, 6213],
                            [-16, 6205],
                            [72, 6123],
                            [66, 6083],
                            [15, 6165],
                            [-42, 6201]
                        ]
                    ],
                    [
                        [
                            [1224, 7616],
                            [1252, 7593],
                            [1412, 7547],
                            [1457, 7523],
                            [1610, 7387],
                            [1667, 7138],
                            [1787, 7035],
                            [1781, 7000],
                            [1699, 6927],
                            [1598, 6742],
                            [1550, 6785],
                            [1338, 6761],
                            [1242, 6775],
                            [1183, 6825],
                            [1091, 6873],
                            [1052, 6914],
                            [1050, 6872],
                            [930, 6896],
                            [852, 6851],
                            [789, 6985],
                            [657, 7036],
                            [602, 7178],
                            [561, 7211],
                            [463, 7166],
                            [393, 7175],
                            [289, 7217],
                            [155, 7164],
                            [95, 7154],
                            [72, 7122],
                            [140, 7116],
                            [172, 7080],
                            [160, 6922],
                            [191, 6824],
                            [291, 6772],
                            [287, 6724],
                            [237, 6628],
                            [282, 6479],
                            [332, 6496],
                            [421, 6438],
                            [457, 6359],
                            [543, 6260],
                            [575, 6134],
                            [631, 6122],
                            [648, 6089],
                            [623, 5961],
                            [619, 5774],
                            [588, 5733],
                            [479, 5727],
                            [426, 5680],
                            [318, 5668],
                            [289, 5602],
                            [178, 5786],
                            [234, 5794],
                            [249, 5846],
                            [231, 5978],
                            [171, 6055],
                            [95, 6244],
                            [38, 6311],
                            [-10, 6312],
                            [-38, 6372],
                            [-15, 6466],
                            [-95, 6505],
                            [-164, 6654],
                            [-231, 6824],
                            [-294, 6902],
                            [-321, 6991],
                            [-415, 7105],
                            [-421, 7161],
                            [-538, 7321],
                            [-565, 7425],
                            [-670, 7617],
                            [-714, 7652],
                            [-685, 7676],
                            [-765, 7720],
                            [-830, 7702],
                            [-859, 7724],
                            [-970, 7725],
                            [-991, 7688],
                            [-999, 7747],
                            [-965, 7764],
                            [-948, 7826],
                            [-870, 7963],
                            [-876, 8042],
                            [-833, 8164],
                            [-756, 8161],
                            [-645, 8135],
                            [-565, 8167],
                            [-461, 8179],
                            [-322, 8179],
                            [-226, 8148],
                            [-178, 8108],
                            [-61, 7931],
                            [-14, 7915],
                            [164, 7950],
                            [232, 7941],
                            [348, 7899],
                            [460, 7896],
                            [514, 7989],
                            [603, 8048],
                            [629, 8012],
                            [619, 7838],
                            [650, 7758],
                            [704, 7714],
                            [789, 7684],
                            [906, 7667],
                            [954, 7681],
                            [1023, 7660],
                            [1053, 7609],
                            [1177, 7641],
                            [1224, 7616]
                        ]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.JZ",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.66,
                "hc-middle-y": 0.44,
                "hc-key": "sa-jz",
                "hc-a2": "JZ",
                "labelrank": "7",
                "hasc": "SA.JZ",
                "alt-name": "Jazan|Qizan",
                "woe-id": "2346956",
                "subregion": null,
                "fips": "SA17",
                "postal-code": "JZ",
                "name": "جازان",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 7,
                "longitude": "42.726",
                "woe-name": "Jizan",
                "latitude": "17.3028",
                "woe-label": "Jizan, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "MultiPolygon",
                "coordinates": [
                    [
                        [
                            [2544, 1113],
                            [2605, 1105],
                            [2626, 1146],
                            [2700, 1084],
                            [2682, 1017],
                            [2638, 1046],
                            [2645, 1078],
                            [2585, 1062],
                            [2479, 1148],
                            [2468, 1196],
                            [2506, 1174],
                            [2544, 1113]
                        ]
                    ],
                    [
                        [
                            [2571, 1220],
                            [2566, 1152],
                            [2547, 1142],
                            [2510, 1192],
                            [2571, 1220]
                        ]
                    ],
                    [
                        [
                            [2489, 1996],
                            [2571, 1994],
                            [2571, 1854],
                            [2651, 1842],
                            [2699, 1772],
                            [2808, 1760],
                            [2947, 1620],
                            [2981, 1643],
                            [3007, 1736],
                            [3036, 1779],
                            [3110, 1806],
                            [3129, 1735],
                            [3226, 1620],
                            [3268, 1605],
                            [3332, 1516],
                            [3235, 1428],
                            [3280, 1386],
                            [3230, 1363],
                            [3203, 1203],
                            [3256, 1116],
                            [3231, 1053],
                            [3188, 1060],
                            [3161, 983],
                            [3090, 952],
                            [3071, 908],
                            [3026, 891],
                            [3026, 948],
                            [2992, 1002],
                            [2999, 1061],
                            [2945, 1143],
                            [2894, 1179],
                            [2897, 1250],
                            [2851, 1270],
                            [2821, 1341],
                            [2787, 1347],
                            [2768, 1506],
                            [2538, 1716],
                            [2457, 1768],
                            [2394, 1889],
                            [2418, 1931],
                            [2489, 1996]
                        ]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.NJ",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.54,
                "hc-middle-y": 0.50,
                "hc-key": "sa-nj",
                "hc-a2": "NJ",
                "labelrank": "6",
                "hasc": "SA.NJ",
                "alt-name": null,
                "woe-id": "2346960",
                "subregion": null,
                "fips": "SA16",
                "postal-code": "NJ",
                "name": "نجران",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 6,
                "longitude": "45.6917",
                "woe-name": "Najran",
                "latitude": "18.2931",
                "woe-label": "Najran, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [5547, 1300],
                        [5419, 1223],
                        [5299, 1221],
                        [5176, 1388],
                        [5156, 1399],
                        [4946, 1368],
                        [4463, 1416],
                        [4319, 1481],
                        [4038, 1483],
                        [3992, 1468],
                        [3907, 1476],
                        [3745, 1439],
                        [3553, 1437],
                        [3492, 1490],
                        [3508, 1564],
                        [3496, 1628],
                        [3515, 1805],
                        [3495, 1902],
                        [3526, 1970],
                        [3674, 2112],
                        [3725, 2195],
                        [3871, 2265],
                        [3905, 2326],
                        [3886, 2463],
                        [3916, 2523],
                        [3965, 2555],
                        [3971, 2657],
                        [4120, 2564],
                        [4237, 2522],
                        [4376, 2513],
                        [5629, 2658],
                        [5698, 2679],
                        [5547, 1301],
                        [5547, 1300]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.RI",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.54,
                "hc-middle-y": 0.53,
                "hc-key": "sa-ri",
                "hc-a2": "RI",
                "labelrank": "6",
                "hasc": "SA.RI",
                "alt-name": "Riyad|Riad|Riyadh",
                "woe-id": "2346951",
                "subregion": null,
                "fips": "SA10",
                "postal-code": "RI",
                "name": "الرياض",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 1,
                "longitude": "45.1404",
                "woe-name": "Ar Riyad",
                "latitude": "23.3432",
                "woe-label": "Ar Riyad, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [5698, 2679],
                        [5629, 2658],
                        [4376, 2513],
                        [4237, 2522],
                        [4120, 2564],
                        [3971, 2657],
                        [3753, 2858],
                        [3663, 2994],
                        [3681, 3122],
                        [3731, 3237],
                        [3600, 3415],
                        [3523, 3485],
                        [3387, 3692],
                        [3378, 3739],
                        [3404, 3845],
                        [3399, 3939],
                        [3444, 4050],
                        [3420, 4239],
                        [3436, 4345],
                        [3344, 4378],
                        [3259, 4361],
                        [3189, 4387],
                        [3175, 4436],
                        [3189, 4528],
                        [3140, 4576],
                        [2921, 4592],
                        [2846, 4637],
                        [2782, 4799],
                        [2725, 5013],
                        [2660, 5088],
                        [2666, 5177],
                        [2679, 5434],
                        [2731, 5550],
                        [2776, 5622],
                        [2833, 5644],
                        [2946, 5596],
                        [3039, 5585],
                        [3139, 5595],
                        [3203, 5581],
                        [3268, 5634],
                        [3245, 5709],
                        [3306, 5804],
                        [3380, 5866],
                        [3552, 5905],
                        [3650, 5973],
                        [3712, 6064],
                        [3784, 6086],
                        [3893, 6071],
                        [4082, 6080],
                        [4090, 6180],
                        [4060, 6256],
                        [4064, 6318],
                        [3912, 6547],
                        [3921, 6684],
                        [3988, 6722],
                        [4067, 6818],
                        [4139, 6856],
                        [4136, 6938],
                        [4042, 7008],
                        [4040, 7087],
                        [4099, 7165],
                        [4128, 7176],
                        [4180, 7141],
                        [4225, 7183],
                        [4309, 7103],
                        [4353, 7013],
                        [4497, 6931],
                        [4569, 6919],
                        [4670, 6820],
                        [4724, 6796],
                        [4808, 6794],
                        [4885, 6765],
                        [5011, 6676],
                        [5169, 6669],
                        [5289, 6551],
                        [5453, 6488],
                        [5485, 6428],
                        [5511, 5671],
                        [5536, 5615],
                        [5722, 5488],
                        [5855, 5359],
                        [5936, 5208],
                        [5945, 5014],
                        [5822, 3793],
                        [5698, 2679]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.MD",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.57,
                "hc-middle-y": 0.59,
                "hc-key": "sa-md",
                "hc-a2": "MD",
                "labelrank": "6",
                "hasc": "SA.MD",
                "alt-name": "Madinah|Al Madinah al Munawwarah|Monwarah|Medina|MÃ©dine",
                "woe-id": "2346958",
                "subregion": null,
                "fips": "SA05",
                "postal-code": "MD",
                "name": "المدينة المنورة",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 3,
                "longitude": "39.4378",
                "woe-name": "Al Madinah",
                "latitude": "24.9279",
                "woe-label": "Al Madinah, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [2731, 5550],
                        [2679, 5434],
                        [2666, 5177],
                        [2565, 5187],
                        [2536, 5165],
                        [2548, 5056],
                        [2496, 4984],
                        [2334, 4910],
                        [2291, 4851],
                        [2368, 4800],
                        [2351, 4745],
                        [2289, 4724],
                        [2184, 4632],
                        [2031, 4518],
                        [1956, 4424],
                        [1823, 4445],
                        [1718, 4378],
                        [1658, 4385],
                        [1637, 4518],
                        [1558, 4560],
                        [1585, 4659],
                        [1572, 4753],
                        [1501, 4754],
                        [1348, 4717],
                        [1322, 4776],
                        [1203, 4779],
                        [1049, 4945],
                        [915, 4934],
                        [896, 5020],
                        [864, 5007],
                        [824, 5145],
                        [781, 5196],
                        [690, 5261],
                        [637, 5317],
                        [528, 5368],
                        [434, 5453],
                        [408, 5430],
                        [353, 5444],
                        [308, 5503],
                        [324, 5544],
                        [289, 5602],
                        [318, 5668],
                        [426, 5680],
                        [479, 5727],
                        [588, 5733],
                        [619, 5774],
                        [623, 5961],
                        [648, 6089],
                        [631, 6122],
                        [575, 6134],
                        [543, 6260],
                        [457, 6359],
                        [421, 6438],
                        [332, 6496],
                        [282, 6479],
                        [237, 6628],
                        [287, 6724],
                        [291, 6772],
                        [191, 6824],
                        [160, 6922],
                        [172, 7080],
                        [140, 7116],
                        [72, 7122],
                        [95, 7154],
                        [155, 7164],
                        [289, 7217],
                        [393, 7175],
                        [463, 7166],
                        [561, 7211],
                        [602, 7178],
                        [657, 7036],
                        [789, 6985],
                        [852, 6851],
                        [930, 6896],
                        [1050, 6872],
                        [1052, 6914],
                        [1091, 6873],
                        [1183, 6825],
                        [1242, 6775],
                        [1338, 6761],
                        [1550, 6785],
                        [1598, 6742],
                        [1608, 6679],
                        [1578, 6576],
                        [1646, 6564],
                        [1663, 6530],
                        [1627, 6342],
                        [1648, 6191],
                        [1637, 6040],
                        [1659, 5992],
                        [1721, 5977],
                        [1850, 6018],
                        [1987, 6026],
                        [2057, 6093],
                        [2115, 6120],
                        [2204, 6121],
                        [2390, 6081],
                        [2492, 6049],
                        [2515, 6004],
                        [2501, 5858],
                        [2539, 5800],
                        [2621, 5771],
                        [2662, 5626],
                        [2731, 5550]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.HA",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.41,
                "hc-middle-y": 0.48,
                "hc-key": "sa-ha",
                "hc-a2": "HA",
                "labelrank": "6",
                "hasc": "SA.HA",
                "alt-name": "Hail",
                "woe-id": "2346957",
                "subregion": null,
                "fips": "SA13",
                "postal-code": "HA",
                "name": "حائل",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 9,
                "longitude": "41.7076",
                "woe-name": "Ha'il",
                "latitude": "27.2652",
                "woe-label": "Hail, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [4128, 7176],
                        [4099, 7165],
                        [4040, 7087],
                        [4042, 7008],
                        [3981, 6935],
                        [3944, 6922],
                        [3824, 6950],
                        [3724, 7041],
                        [3671, 7070],
                        [3623, 7057],
                        [3430, 6961],
                        [3349, 6891],
                        [3291, 6809],
                        [3174, 6761],
                        [2977, 6618],
                        [2871, 6479],
                        [2811, 6474],
                        [2773, 6414],
                        [2777, 6310],
                        [2677, 6320],
                        [2646, 6251],
                        [2669, 6181],
                        [2563, 6154],
                        [2390, 6081],
                        [2204, 6121],
                        [2115, 6120],
                        [2057, 6093],
                        [1987, 6026],
                        [1850, 6018],
                        [1721, 5977],
                        [1659, 5992],
                        [1637, 6040],
                        [1648, 6191],
                        [1627, 6342],
                        [1663, 6530],
                        [1646, 6564],
                        [1578, 6576],
                        [1608, 6679],
                        [1598, 6742],
                        [1699, 6927],
                        [1781, 7000],
                        [1787, 7035],
                        [1667, 7138],
                        [1610, 7387],
                        [1457, 7523],
                        [1412, 7547],
                        [1252, 7593],
                        [1224, 7616],
                        [1306, 7688],
                        [1500, 7769],
                        [1588, 7790],
                        [1778, 7887],
                        [1970, 7957],
                        [2012, 7949],
                        [2108, 7914],
                        [2375, 7966],
                        [2448, 7965],
                        [2567, 7922],
                        [2738, 7935],
                        [2907, 7891],
                        [2952, 7864],
                        [3042, 7767],
                        [3101, 7755],
                        [3217, 7793],
                        [3246, 7699],
                        [3274, 7674],
                        [3382, 7667],
                        [3478, 7631],
                        [3588, 7685],
                        [3621, 7659],
                        [3704, 7506],
                        [3746, 7394],
                        [3819, 7335],
                        [3974, 7310],
                        [4025, 7283],
                        [4128, 7176]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.QS",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.50,
                "hc-middle-y": 0.50,
                "hc-key": "sa-qs",
                "hc-a2": "QS",
                "labelrank": "4",
                "hasc": "SA.QS",
                "alt-name": "Al Gassim|Gasim|Qaseem|Al Qasseem",
                "woe-id": "2346952",
                "subregion": null,
                "fips": "SA08",
                "postal-code": "QS",
                "name": "منطقة القصيم",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 12,
                "longitude": "43.2716",
                "woe-name": "Al Quassim",
                "latitude": "25.9478",
                "woe-label": "Al Qasim, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [2390, 6081],
                        [2563, 6154],
                        [2669, 6181],
                        [2646, 6251],
                        [2677, 6320],
                        [2777, 6310],
                        [2773, 6414],
                        [2811, 6474],
                        [2871, 6479],
                        [2977, 6618],
                        [3174, 6761],
                        [3291, 6809],
                        [3349, 6891],
                        [3430, 6961],
                        [3623, 7057],
                        [3671, 7070],
                        [3724, 7041],
                        [3824, 6950],
                        [3944, 6922],
                        [3981, 6935],
                        [4042, 7008],
                        [4136, 6938],
                        [4139, 6856],
                        [4067, 6818],
                        [3988, 6722],
                        [3921, 6684],
                        [3912, 6547],
                        [4064, 6318],
                        [4060, 6256],
                        [4090, 6180],
                        [4082, 6080],
                        [3893, 6071],
                        [3784, 6086],
                        [3712, 6064],
                        [3650, 5973],
                        [3552, 5905],
                        [3380, 5866],
                        [3306, 5804],
                        [3245, 5709],
                        [3268, 5634],
                        [3203, 5581],
                        [3139, 5595],
                        [3039, 5585],
                        [2946, 5596],
                        [2833, 5644],
                        [2776, 5622],
                        [2731, 5550],
                        [2662, 5626],
                        [2621, 5771],
                        [2539, 5800],
                        [2501, 5858],
                        [2515, 6004],
                        [2492, 6049],
                        [2390, 6081]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.HS",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.62,
                "hc-middle-y": 0.65,
                "hc-key": "sa-hs",
                "hc-a2": "HS",
                "labelrank": "4",
                "hasc": "SA.HS",
                "alt-name": "Northern Frontier",
                "woe-id": "2346961",
                "subregion": null,
                "fips": "SA15",
                "postal-code": "HS",
                "name": "منطقة الحدود الشمالية",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 8,
                "longitude": "43.3124",
                "woe-name": "Al Hudud ash Shamaliyah",
                "latitude": "29.227",
                "woe-label": "Al Hudud ash Shamaliyah, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [4225, 7183],
                        [4180, 7141],
                        [4128, 7176],
                        [4025, 7283],
                        [3974, 7310],
                        [3819, 7335],
                        [3746, 7394],
                        [3704, 7506],
                        [3621, 7659],
                        [3588, 7685],
                        [3478, 7631],
                        [3382, 7667],
                        [3274, 7674],
                        [3246, 7699],
                        [3217, 7793],
                        [3101, 7755],
                        [3042, 7767],
                        [2952, 7864],
                        [2907, 7891],
                        [2738, 7935],
                        [2567, 7922],
                        [2448, 7965],
                        [2375, 7966],
                        [2108, 7914],
                        [2012, 7949],
                        [2102, 8050],
                        [2121, 8238],
                        [2158, 8296],
                        [2213, 8335],
                        [2633, 8579],
                        [2698, 8632],
                        [2697, 8676],
                        [2602, 8759],
                        [2559, 8849],
                        [2500, 8903],
                        [2376, 8918],
                        [2252, 9014],
                        [1874, 9043],
                        [1800, 9069],
                        [1727, 9124],
                        [1400, 9191],
                        [1335, 9196],
                        [1183, 9182],
                        [964, 9173],
                        [880, 9216],
                        [846, 9291],
                        [767, 9384],
                        [767, 9464],
                        [840, 9676],
                        [1320, 9784],
                        [1418, 9851],
                        [1835, 9758],
                        [1998, 9719],
                        [2050, 9691],
                        [2807, 9205],
                        [3182, 8866],
                        [4077, 8126],
                        [4090, 8121],
                        [4903, 8051],
                        [4938, 8059],
                        [4697, 7960],
                        [4628, 7921],
                        [4511, 7787],
                        [4336, 7456],
                        [4227, 7318],
                        [4199, 7232],
                        [4225, 7183]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.JF",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.58,
                "hc-middle-y": 0.62,
                "hc-key": "sa-jf",
                "hc-a2": "JF",
                "labelrank": "4",
                "hasc": "SA.JF",
                "alt-name": "JawfAl Joaf|Al-Jouf|Jowf",
                "woe-id": "2346950",
                "subregion": null,
                "fips": "SA03",
                "postal-code": "JF",
                "name": "الجوف",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 4,
                "longitude": "38.3651",
                "woe-name": "Al Jawf",
                "latitude": "29.5589",
                "woe-label": "Al Jawf, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [2012, 7949],
                        [1970, 7957],
                        [1778, 7887],
                        [1588, 7790],
                        [1500, 7769],
                        [1306, 7688],
                        [1224, 7616],
                        [1177, 7641],
                        [1053, 7609],
                        [1023, 7660],
                        [954, 7681],
                        [906, 7667],
                        [789, 7684],
                        [704, 7714],
                        [650, 7758],
                        [619, 7838],
                        [629, 8012],
                        [603, 8048],
                        [514, 7989],
                        [460, 7896],
                        [348, 7899],
                        [232, 7941],
                        [164, 7950],
                        [-14, 7915],
                        [-61, 7931],
                        [-178, 8108],
                        [-226, 8148],
                        [-322, 8179],
                        [-461, 8179],
                        [-565, 8167],
                        [-645, 8135],
                        [-756, 8161],
                        [-833, 8164],
                        [-821, 8255],
                        [-757, 8426],
                        [-232, 8290],
                        [-205, 8294],
                        [10, 8446],
                        [137, 8629],
                        [164, 8647],
                        [522, 8695],
                        [615, 8871],
                        [634, 8889],
                        [792, 8966],
                        [565, 9269],
                        [331, 9562],
                        [840, 9676],
                        [767, 9464],
                        [767, 9384],
                        [846, 9291],
                        [880, 9216],
                        [964, 9173],
                        [1183, 9182],
                        [1335, 9196],
                        [1400, 9191],
                        [1727, 9124],
                        [1800, 9069],
                        [1874, 9043],
                        [2252, 9014],
                        [2376, 8918],
                        [2500, 8903],
                        [2559, 8849],
                        [2602, 8759],
                        [2697, 8676],
                        [2698, 8632],
                        [2633, 8579],
                        [2213, 8335],
                        [2158, 8296],
                        [2121, 8238],
                        [2102, 8050],
                        [2012, 7949]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.SH",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.51,
                "hc-middle-y": 0.57,
                "hc-key": "sa-sh",
                "hc-a2": "SH",
                "labelrank": "4",
                "hasc": "SA.SH",
                "alt-name": "Eastern Province",
                "woe-id": "2346954",
                "subregion": null,
                "fips": "SA06",
                "postal-code": "SH",
                "name": "المنطقة الشرقية",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 13,
                "longitude": "50.1714",
                "woe-name": "Ash Sharqiyah",
                "latitude": "22.9875",
                "woe-label": "Ash Sharqiyah, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [5547, 1300],
                        [5547, 1301],
                        [5698, 2679],
                        [5822, 3793],
                        [5945, 5014],
                        [5936, 5208],
                        [5855, 5359],
                        [5722, 5488],
                        [5536, 5615],
                        [5511, 5671],
                        [5485, 6428],
                        [5453, 6488],
                        [5289, 6551],
                        [5169, 6669],
                        [5011, 6676],
                        [4885, 6765],
                        [4808, 6794],
                        [4724, 6796],
                        [4670, 6820],
                        [4569, 6919],
                        [4497, 6931],
                        [4353, 7013],
                        [4309, 7103],
                        [4225, 7183],
                        [4199, 7232],
                        [4227, 7318],
                        [4336, 7456],
                        [4511, 7787],
                        [4628, 7921],
                        [4697, 7960],
                        [4938, 8059],
                        [4990, 8070],
                        [5438, 8021],
                        [5469, 7970],
                        [5524, 7805],
                        [5560, 7763],
                        [5942, 7776],
                        [5995, 7703],
                        [5997, 7641],
                        [6040, 7596],
                        [6036, 7545],
                        [6177, 7385],
                        [6140, 7372],
                        [6130, 7314],
                        [6179, 7258],
                        [6226, 7264],
                        [6283, 7224],
                        [6365, 7226],
                        [6413, 7036],
                        [6440, 7005],
                        [6517, 6998],
                        [6609, 6903],
                        [6734, 6851],
                        [6840, 6755],
                        [6758, 6767],
                        [6787, 6647],
                        [6871, 6599],
                        [6882, 6467],
                        [6858, 6464],
                        [6854, 6390],
                        [6812, 6466],
                        [6767, 6445],
                        [6834, 6362],
                        [6846, 6294],
                        [6916, 6190],
                        [6862, 6218],
                        [6972, 6080],
                        [7032, 6063],
                        [7068, 5977],
                        [7082, 5879],
                        [7181, 5748],
                        [7189, 5692],
                        [7319, 5576],
                        [7380, 5575],
                        [7468, 5615],
                        [7488, 5555],
                        [7534, 5609],
                        [7561, 5580],
                        [7493, 5492],
                        [7483, 5433],
                        [7551, 5448],
                        [7634, 5414],
                        [7651, 5313],
                        [8177, 4700],
                        [8201, 4688],
                        [9543, 4592],
                        [9583, 4642],
                        [9843, 4270],
                        [9851, 4245],
                        [9571, 3089],
                        [7981, 2440],
                        [6451, 2174],
                        [6402, 2155],
                        [5935, 1902],
                        [5631, 1510],
                        [5547, 1300]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.BA",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.47,
                "hc-middle-y": 0.45,
                "hc-key": "sa-ba",
                "hc-a2": "BA",
                "labelrank": "4",
                "hasc": "SA.BA",
                "alt-name": "Baha",
                "woe-id": "2346949",
                "subregion": null,
                "fips": "SA02",
                "postal-code": "BA",
                "name": "الباحة",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 5,
                "longitude": "41.4165",
                "woe-name": "Al Bahah",
                "latitude": "20.1605",
                "woe-label": "Al Baha, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [2653, 3299],
                        [2626, 3212],
                        [2649, 3156],
                        [2623, 3102],
                        [2548, 3048],
                        [2522, 2950],
                        [2466, 2834],
                        [2362, 2820],
                        [2336, 2784],
                        [2274, 2613],
                        [2242, 2596],
                        [2179, 2614],
                        [2073, 2720],
                        [2064, 2850],
                        [2072, 2908],
                        [2051, 2958],
                        [1992, 2986],
                        [2052, 3068],
                        [2160, 3094],
                        [2235, 3219],
                        [2226, 3314],
                        [2238, 3361],
                        [2282, 3384],
                        [2330, 3352],
                        [2384, 3274],
                        [2515, 3293],
                        [2588, 3328],
                        [2653, 3299]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.AS",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.49,
                "hc-middle-y": 0.50,
                "hc-key": "sa-as",
                "hc-a2": "AS",
                "labelrank": "4",
                "hasc": "SA.AS",
                "alt-name": "Asir|Aseer|Assyear",
                "woe-id": "2346955",
                "subregion": null,
                "fips": "SA11",
                "postal-code": "AS",
                "name": "منطقة عسير",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 11,
                "longitude": "42.9503",
                "woe-name": "`Asir",
                "latitude": "19.3484",
                "woe-label": "Asir, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [2466, 2834],
                        [2522, 2950],
                        [2548, 3048],
                        [2623, 3102],
                        [2649, 3156],
                        [2626, 3212],
                        [2653, 3299],
                        [2715, 3170],
                        [2756, 3139],
                        [2836, 3173],
                        [2973, 3293],
                        [3143, 3396],
                        [3286, 3425],
                        [3383, 3416],
                        [3523, 3485],
                        [3600, 3415],
                        [3731, 3237],
                        [3681, 3122],
                        [3663, 2994],
                        [3753, 2858],
                        [3971, 2657],
                        [3965, 2555],
                        [3916, 2523],
                        [3886, 2463],
                        [3905, 2326],
                        [3871, 2265],
                        [3725, 2195],
                        [3674, 2112],
                        [3526, 1970],
                        [3495, 1902],
                        [3515, 1805],
                        [3496, 1628],
                        [3508, 1564],
                        [3492, 1490],
                        [3383, 1538],
                        [3332, 1516],
                        [3268, 1605],
                        [3226, 1620],
                        [3129, 1735],
                        [3110, 1806],
                        [3036, 1779],
                        [3007, 1736],
                        [2981, 1643],
                        [2947, 1620],
                        [2808, 1760],
                        [2699, 1772],
                        [2651, 1842],
                        [2571, 1854],
                        [2571, 1994],
                        [2489, 1996],
                        [2509, 2115],
                        [2474, 2227],
                        [2438, 2258],
                        [2333, 2265],
                        [2287, 2343],
                        [2294, 2474],
                        [2401, 2510],
                        [2532, 2521],
                        [2564, 2599],
                        [2536, 2705],
                        [2551, 2813],
                        [2523, 2842],
                        [2466, 2834]
                    ]
                ]
            }
        }, {
            "type": "Feature",
            "id": "SA.MK",
            "properties": {
                "hc-group": "admin1",
                "hc-middle-x": 0.48,
                "hc-middle-y": 0.41,
                "hc-key": "sa-mk",
                "hc-a2": "MK",
                "labelrank": "6",
                "hasc": "SA.MK",
                "alt-name": "La Meca|La Mecca|La Mecque|Makka|Makkah al-Mukarramah|Mecca|Meca|Mecka|Mekka",
                "woe-id": "2346959",
                "subregion": null,
                "fips": "SA14",
                "postal-code": "MK",
                "name": "مكة المكرمة",
                "country": "Saudi Arabia",
                "type-en": "Region",
                "region": 2,
                "longitude": "40.2542",
                "woe-name": "Makkah",
                "latitude": "21.4348",
                "woe-label": "Makka, SA, Saudi Arabia",
                "type": "Emirate|Mintaqah"
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [3523, 3485],
                        [3383, 3416],
                        [3286, 3425],
                        [3143, 3396],
                        [2973, 3293],
                        [2836, 3173],
                        [2756, 3139],
                        [2715, 3170],
                        [2653, 3299],
                        [2588, 3328],
                        [2515, 3293],
                        [2384, 3274],
                        [2330, 3352],
                        [2282, 3384],
                        [2238, 3361],
                        [2226, 3314],
                        [2235, 3219],
                        [2160, 3094],
                        [2052, 3068],
                        [1992, 2986],
                        [2051, 2958],
                        [2072, 2908],
                        [2064, 2850],
                        [2073, 2720],
                        [2179, 2614],
                        [2242, 2596],
                        [2274, 2613],
                        [2336, 2784],
                        [2362, 2820],
                        [2466, 2834],
                        [2523, 2842],
                        [2551, 2813],
                        [2536, 2705],
                        [2564, 2599],
                        [2532, 2521],
                        [2401, 2510],
                        [2294, 2474],
                        [2287, 2343],
                        [2333, 2265],
                        [2438, 2258],
                        [2474, 2227],
                        [2509, 2115],
                        [2489, 1996],
                        [2418, 1931],
                        [2394, 1889],
                        [2328, 1979],
                        [2312, 2084],
                        [2191, 2219],
                        [2214, 2295],
                        [2173, 2317],
                        [2155, 2366],
                        [2178, 2428],
                        [2121, 2462],
                        [2105, 2541],
                        [2063, 2587],
                        [2059, 2678],
                        [1975, 2732],
                        [1979, 2798],
                        [1946, 2842],
                        [1894, 2845],
                        [1845, 2909],
                        [1840, 2948],
                        [1706, 3038],
                        [1602, 3133],
                        [1524, 3122],
                        [1439, 3174],
                        [1380, 3239],
                        [1303, 3380],
                        [1311, 3402],
                        [1203, 3507],
                        [1141, 3661],
                        [1096, 3732],
                        [1146, 3781],
                        [1142, 3840],
                        [1108, 3880],
                        [1104, 3954],
                        [1040, 4079],
                        [1033, 4138],
                        [1071, 4123],
                        [1095, 4255],
                        [1152, 4340],
                        [1114, 4328],
                        [1124, 4430],
                        [1042, 4612],
                        [1070, 4616],
                        [1035, 4671],
                        [993, 4683],
                        [992, 4746],
                        [946, 4829],
                        [915, 4934],
                        [1049, 4945],
                        [1203, 4779],
                        [1322, 4776],
                        [1348, 4717],
                        [1501, 4754],
                        [1572, 4753],
                        [1585, 4659],
                        [1558, 4560],
                        [1637, 4518],
                        [1658, 4385],
                        [1718, 4378],
                        [1823, 4445],
                        [1956, 4424],
                        [2031, 4518],
                        [2184, 4632],
                        [2289, 4724],
                        [2351, 4745],
                        [2368, 4800],
                        [2291, 4851],
                        [2334, 4910],
                        [2496, 4984],
                        [2548, 5056],
                        [2536, 5165],
                        [2565, 5187],
                        [2666, 5177],
                        [2660, 5088],
                        [2725, 5013],
                        [2782, 4799],
                        [2846, 4637],
                        [2921, 4592],
                        [3140, 4576],
                        [3189, 4528],
                        [3175, 4436],
                        [3189, 4387],
                        [3259, 4361],
                        [3344, 4378],
                        [3436, 4345],
                        [3420, 4239],
                        [3444, 4050],
                        [3399, 3939],
                        [3404, 3845],
                        [3378, 3739],
                        [3387, 3692],
                        [3523, 3485]
                    ]
                ]
            }
        }]
    };

    var KSAStates = Highcharts.maps["ksa"].features
</script>
@include('front.trip.make_trip')
@include('front.trip.login_popup')

<!-- make a trip popup -->

@include('front.modals.rate')
@include('front.modals.share')

<!-- highcharts -->
<!-- select2 plugin -->
<!-- google map -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc&callback=initMap"></script>
<!-- vendor js bundle -->
{{-- <script src="{{ asset('front_assets/js/vendor/vendor.bundle.js') }}"></script> --}}
<!-- swiper -->
{{-- <script src="{{ asset('front_assets/libs/swiper/swiper-bundle.min.js') }}"></script> --}}

<!-- intlTelInput-jquery -->
<script src="{{ asset('front_assets/libs/intlTelInput/intlTelInput-jquery.min.js') }}"></script>

<!-- share buttons -->
<script src="{{ asset('front_assets/libs/share-buttons/share-buttons.js') }}"></script>
<script src="{{ asset('front_assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('front_assets/libs/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('front_assets/libs/OwlCarousel2/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front_assets/js/new-main.js') }}"></script>
<script type="text/javascript" src="{{ asset('front_assets/libs/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front_assets/libs/daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('front_assets/libs/marquee/jquery.marquee.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#open-filters').on('click', function () {
            $('#filterForm').collapse('toggle');
        })

    });

    $('input[name="daterange"]').daterangepicker({
        "startDate": moment(),
        "endDate": moment().subtract(5, 'days'),
        locale: {
            format: 'YYYY/MM/DD'
        },
        minDate: new Date(),
        maxDate: "31/12/2050",
    });

    $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.endDate.format('YYYY/MM/DD') + ' - ' + picker.startDate.format('YYYY/MM/DD'));
    });

    $(document).ready(function () {
        new Swiper(".our-services-slider", {
            cssMode: true,
            // loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            mousewheel: true,
            keyboard: true,
        });

        // telInput plugin
        $("#phone").intlTelInput({
            initialCountry: 'SA'
        });
    });


    function rateFunction() {
        let rate = $('input[name="rating"]:checked').val()
        let name = $('#name').val()
        let email = $('#email').val()
        let rateText = $('#rateText').val()
        let parent_id = "{{ request()->segment(3) }}"
        let type = "{{ request()->segment(2) }}"

        if (type == 'place-details') {
            type = 'places'
        } else if (type == 'store-details') {
            type = 'stores'
        } else if (type == 'event-details') {
            type = 'events'
        } else if (type == 'zad-details') {
            type = 'zad_elgadels'
        } else if (type == 'swalef') {
            type = 'swalefs'
        }
        $('.rating #u_rate').html("");
        $('.rating #u_name').html("");
        $('.rating #u_email').html("");
        $('.rating #u_rateText').html("");

        $.ajax({
            url: "{{ route('front.ratePlaces') }}",
            type: "POST",
            data: {
                rate: rate,
                name: name,
                email: email,
                rateText: rateText,
                parent_id: parent_id,
                type: type,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#rateForm').trigger("reset");
                $('#rating').modal('toggle')
                var rate = `
                    <li class="d-flex flex-column flex-sm-row justify-content-between">
                                    <div class="d-flex flex-column flex-sm-row gap-lg">
                                        <div class="review-img">
                                            <img src="{{ asset('front_assets/imgs/empty.png') }}" alt="empty">
                                        </div>
                                        <div>
                                            <h4 class="review-author">${response.name}</h4>
                                            <p class="review-text">${response.rateText ? response.rateText : ''}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-sm-center pt-3">
                                        <!-- rating -->
                                        <div class="d-flex align-items-center gap mb-2">
                                            <div class="review-rate d-flex">`;
                for (var x = 0; x < response.rate; x++) {
                    rate += `<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                </svg>`;
                }
                for (var x = 0; x < 5 - response.rate; x++) {
                    rate += `<svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                </svg>`;
                }

                rate += `</div>
                                <span>(${response.rate})</span>
                            </div>
                            <p dir="ltr" class="review-date mb-0">
                                ${response.created_at}
                            </p>
                        </div>
                    </li>
                    `;
                setTimeout(function () {
                    $('#rating').modal('hide');
                    $('.modal-backdrop').hide()
                }, 1000);

                $('#empty').hide();
                $('.rates').prepend(rate);
                toastr.success('{{__("Rating added successfully")}}');

                // location.reload()
            },
            error(data) {
                const keys = Object.keys(data.responseJSON);
                keys.forEach(key => $('.rating #u_' + key).html(data.responseJSON[key]).show());
            }
        });

    }
</script>
<script src="{{ asset('front_assets/js/main.bundle.js') }}"></script>
<script>
    $(document).ready(function () {


        $("#home_login_and_register_form .register_page #register").on("click", function (e) {
            e.preventDefault();
            var first_name = $('.register_page input[name="first_name"]').val();
            var last_name = $('.register_page input[name="last_name"]').val();
            var register_email = $('.register_page input[name="register_email"]').val();
            var register_password = $('.register_page input[name="register_password"]').val();

            $('button#register').html("{{__("Register in progress")}}");
            $('.register_page #u_first_name').html("");
            $('.register_page #u_last_name').html("");
            $('.register_page #u_register_email').html("");
            $('.register_page #u_register_password').html("");

            $.ajax({
                url: "{{ route('front.register') }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    first_name,
                    last_name,
                    register_email,
                    register_password,
                },
                type: 'POST',
                success: function () {
                    window.location.replace("{{route("front.index")}}");
                },
                error(data) {
                    const keys = Object.keys(data.responseJSON);
                    keys.forEach(key => $('.register_page #u_' + key).html(data.responseJSON[key]).show());
                    $('button#register').html("{{ __('dashboard.register') }}")
                }
            });

        });


        $("#home_login_and_register_form .login_page #login").on("click", function (e) {
            e.preventDefault();
            login();
        });


        function login() {
            var email = $('.login_page input[name="email"]').val();
            var password = $('.login_page input[name="password"]').val();

            $('button#login').html("{{__("Login in progress")}}");
            $('.login_page #u_email').html("");
            $('.login_page #u_password').html("");


            $.ajax({
                url: "{{ route('front.login') }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    email,
                    password
                },
                type: 'POST',
                success: function () {
                    window.location.replace("{{route("front.index")}}");
                },
                error(data) {
                    const keys = Object.keys(data.responseJSON);
                    keys.forEach(key => $('.login_page #u_' + key).html(data.responseJSON[key]).show());
                    $('button#login').html("{{ __('dashboard.login') }}")
                }
            });
        }

        @if (session()->get('show_trip') !== 0)
        $('.popup_that_shows_on_startup #make_a_trip_popup').addClass('hide')
        $('.popup_that_shows_on_startup #popup_background').addClass('hide')
        @else
        makeATripPopupShow()
        @endif

        $('.filter-grid-section__container').bind('scroll', function () { //watches scroll of the window
            if ($(this).scrollTop() + $(this).innerHeight() + 2 >= $(this)[0].scrollHeight) {
                pageCountUpdate();
            }
        });

        //This function runs when user scrolls. It will call the new posts if the max_page isn't met and will fade in/fade out the end of page message
        function pageCountUpdate() {
            var page = parseInt($('#page').val());
            var max_page = parseInt($('#max_page').val());

            if (page < max_page) {
                page++;
                $('#page').val(page);
                getData(page);
                $('#end_of_page').hide();
            } else {
                $('#end_of_page').fadeIn();
            }
        }

        function getData(page) {

            const data = $("#filterForm").serialize() + "&page=" + page;

            $.ajax({
                type: "get",
                headers: {
                    "Accept": "Application/json"
                },
                url: "{{\Illuminate\Support\Facades\URL::current()}}", // whatever your URL is
                data: data,
                success: function (html) { // success! YAY!! Add HTML to content container
                    $('#all-data').append(html);
                }
            });

        } //end of getPosts function


    })


    // *******************************
    // *******************************
    /* popup back and next buttons */
    var popup_input_values = true

    function makeATripNextTab(tabNum, button_direction) {
        var val = -(tabNum * 100),
            childArray = [],
            newChildArray = [],
            childCounter = 1;
        popup_input_values = true
        if (button_direction == 'next') {
            $(`.popup_that_shows_on_startup #make_a_trip_popup .tabs > .tab:nth-of-type(${tabNum}) .left-side`)
                .children('input[required], select[required]').each(function () {
                if ($(this).val()) {
                } else {
                    popup_input_values = false;
                    // childArray.push(childCounter)
                    childArray.push(this)
                }
                // alert('gere')
                // childCounter = childCounter + 1;
            })
            if (popup_input_values == false) {
                // alert("{{ __('dashboard.fill_required_fields') }}")
                // alert(childArray)

                for (i = 0; i < childArray.length; i++) {
                    if (!$(childArray[i]).prev('label').children('.please_fill_this_field').length) {
                        $(childArray[i]).prev('label').append(
                            " <b class='please_fill_this_field'> {{ __('dashboard.please_fill_this_field') }} </b> "
                        )
                    }
                }

                // childCounter = 1
                // $(`.popup_that_shows_on_startup #make_a_trip_popup .tabs > .tab:nth-of-type(${tabNum}) .left-side`).children('input[required], select[required]').each(function(){
                //     if(jQuery.inArray(childCounter, childArray)){
                //         alert(childCounter)
                //     }
                //     childCounter = childCounter + 1
                // })
                // $( "p" ).before( "<b>Hello</b>" );
            }
        }
        if (popup_input_values == true) {
            $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transition", "all 0.3s")
            $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("opacity", "0")
            $('.popup_that_shows_on_startup #make_a_trip_popup .loader_infinity').removeClass('hide')
            setTimeout(() => {
                $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transform",
                    `translateX(${val}%)`)
            }, 300);
            setTimeout(() => {
                $('.popup_that_shows_on_startup #make_a_trip_popup .loader_infinity').addClass('hide')
                $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("opacity", "1")
            }, 600);
            // the following part is to control length of pages in popup
            if (tabNum == 4) {
                $('.popup_that_shows_on_startup #make_a_trip_popup .tab.registerPage').addClass('hide')
            } else if (tabNum == 5) {
                $('.popup_that_shows_on_startup #make_a_trip_popup .tab.registerPage').removeClass('hide')
            }
        }
    }

    // *******************************
    // *******************************
    /* show hide popup */
    function makeATripPopupShow() {
        setTimeout(() => {
            $('.popup_that_shows_on_startup').removeClass('hide')
        }, 200);
        $('.popup_that_shows_on_startup').click(function (e) {
            var $target = $(e.target);
            if ($target.hasClass("closing_x")) {
                $('.popup_that_shows_on_startup').addClass('hide')
            }
            // alert(e.target.className)
            if (!$target.closest('#make_a_trip_popup').length) {
                if (!$target.hasClass("popup_that_shows_on_startup")) {
                } else {
                    // $('#make_a_trip_popup .select2 .select2-selection--multiple').click(function(){
                    // })
                    $('.popup_that_shows_on_startup').addClass('hide')
                }
            }
            // alert('hi')
        })
    }

    function makeALoginPopupShow() {
        setTimeout(() => {
            $('.popup_login').removeClass('hide')
        }, 200);
        $('.popup_login').click(function (e) {
            var $target = $(e.target);
            if (!$target.closest('#make_a_trip_popup').children().length) {
                $('.popup_login').addClass('hide')
            }
            // alert('hi')
        })
    }


    $('.popup_that_shows_on_startup #make_a_trip_popup button#as_a_guest').click(function () {
        // alert('clicked')
        $('.popup_that_shows_on_startup #make_a_trip_popup input[required]').prop('required', false);
    })


    $(document).on('change', '#region_id', function () {
        // get cities
        const region_id = $(this).val()

        $.ajax({
            type: "GET",
            url: "{{ route('cities') }}",
            data: {
                region_id: region_id
            },
            success: function (data) {
                $('#city_id').empty();
                $('#city_id').append(data);
            }
        });
    })

</script>
<script>
    /* show hide register and login popup */
    function openHomeRegisterAndLoginPopup() {
        setTimeout(() => {
            $('.home_login_and_register_form_container').removeClass('hide')
        }, 300);
        $('.home_login_and_register_form_container').click(function (e) {
            var $target = $(e.target);
            if (!$target.closest('#home_login_and_register_form').length) {
                $('.home_login_and_register_form_container').addClass('hide')
            }
            // alert('hi')
        })
    }

    // toggle register and login pages
    function toggleLoginAndRegisterPages() {
        $('#home_login_and_register_form > form').toggleClass('hide')
    }
</script>
<script src="{{ asset('front_assets/libs/amcharts/index.js') }}"></script>
<script src="{{ asset('front_assets/libs/amcharts/map.js') }}"></script>
<script src="{{ asset('front_assets/libs/amcharts/worldLow.js') }}"></script>
<script src="{{ asset('front_assets/libs/amcharts/Animated.js') }}"></script>
<script>

    if (document.getElementById('chartdiv')) {

        am5.ready(function () {
            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("chartdiv");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create the map chart
            // https://www.amcharts.com/docs/v5/charts/map-chart/
            var chart = root.container.children.push(am5map.MapChart.new(root, {
                panX: "rotateX",
                panY: "rotateY",
                projection: am5map.geoOrthographic()
            }));


            // Create series for background fill
            // https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/#Background_polygon
            var backgroundSeries = chart.series.push(
                am5map.MapPolygonSeries.new(root, {})
            );
            backgroundSeries.mapPolygons.template.setAll({
                fill: root.interfaceColors.get("alternativeBackground"),
                fillOpacity: 0.1,
                strokeOpacity: 0
            });
            backgroundSeries.data.push({
                geometry: am5map.getGeoRectangle(90, 180, -90, -180)
            });

            // Create main polygon series for countries
            // https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
            var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
                geoJSON: am5geodata_worldLow
            }));
            polygonSeries.mapPolygons.template.setAll({
                fill: root.interfaceColors.get("alternativeBackground"),
                fillOpacity: 0.15,
                strokeWidth: 0.5,
                stroke: root.interfaceColors.get("background")
            });

            // Create polygon series for projected circles
            var circleSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {}));
            circleSeries.mapPolygons.template.setAll({
                templateField: "polygonTemplate",
                tooltipText: "{name}:{value}"
            });

            // Define data
            var colors = am5.ColorSet.new(root, {});

            var data = "{{ isset($most_data) ? $most_data : null }}"
            data = JSON.parse(data.replace(/&quot;/g, '"'));

            var valueLow = Infinity;
            var valueHigh = -Infinity;

            for (var i = 0; i < data.length; i++) {
                var value = data[i].value;
                if (value < valueLow) {
                    valueLow = value;
                }
                if (value > valueHigh) {
                    valueHigh = value;
                }
            }

            // radius in degrees
            var minRadius = 0.5;
            var maxRadius = 5;

            // Create circles when data for countries is fully loaded.
            polygonSeries.events.on("datavalidated", function () {
                circleSeries.data.clear();
                const length = Object.keys(data).length;
                for (var i = 0; i < length; i++) {
                    var dataContext = data[i];
                    var countryDataItem = polygonSeries.getDataItemById(dataContext.id);
                    var countryPolygon = countryDataItem.get("mapPolygon");
                    var value = dataContext.value;
                    var radius = minRadius + maxRadius * (value - valueLow) / (valueHigh - valueLow);
                    if (countryPolygon) {
                        var geometry = am5map.getGeoCircle(countryPolygon.visualCentroid(), radius);
                        circleSeries.data.push({
                            name: dataContext.name,
                            value: dataContext.value,
                            polygonTemplate: dataContext.polygonTemplate,
                            geometry: geometry
                        });
                    }
                }
            })
            // Make stuff animate on load
            chart.appear(1000, 100);
        });
    }

    $('.marquee').marquee({
        direction: "{{ app()->getLocale() == 'ar' ? 'right' : 'left'}}",
        duplicated: true,
        duration: 15000,
        pauseOnHover: true
    });

    function goTo(slug) {
        window.location.href = '/event-details/' + slug;
    }
</script>
@yield('scripts')

@if (session('message'))
    <script>
        toastr.success("{{ session('message') }}");
    </script>
@endif

@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif

@if ($errors->count() > 0)
    @foreach ($errors->all() as $error)
        <script>
            toastr.error("{{ $error }}");
        </script>
    @endforeach
@endif
</body>

</html>
