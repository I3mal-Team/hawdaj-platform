@extends('layouts.front.hawdaj_master')

@section('content')
    <main>
        <div class="pt-3">
            <!-- filter -->
            <section class="filter-section">
                <div class="container-fluid text-start">
                    <div class="secltion-forms">
                        <button class="btn btn-primary" type="button" id="open-filters">
                            <i class="fa fa-glasses"></i>
                        </button>
                    </div>
                    <form id="filterForm" class="filter-section__filters-form collapse">
                        <div class="secltion-forms">
                            <label for="">{{ __('Place name') }}</label>
                            <input type="text" class="form-control" value="{{ request('search') }}"
                                   placeholder="{{ __('Place name') }}" name="search">
                        </div>
                        <div class="secltion-forms">
                            <label for="">{{ __('Region') }}</label>
                            <select multiple class="form-control select2-input" name="region_id[]" id="region_id"
                                    onchange="get_cities()">
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}"
                                        {{ in_array($region->id , request('region_id') ?? [])  ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="secltion-forms">
                            <label for="">{{ __('City') }}</label>
                            <select multiple class="form-control select2-input" name="city_id[]" id="city_id">
                            </select>
                        </div>
                        <div class="secltion-forms">
                            <label for="">{{ __('Category') }}</label>
                            <select multiple class="form-control select2-input" name="category_id[]"
                                    id="category_id"
                                    onchange="get_sub_categories()">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id , is_array(request('category_id')) ?request('category_id'): [request('category_id')])  ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="secltion-forms">
                            <label for="">{{ __('Sub category') }}</label>
                            <select multiple class="form-control select2-input" name="sub_category_id[]"
                                    id="sub_category_id">
                            </select>
                        </div>
                        <button type="submit" id="search" class="btn btn-primary">
                            {{ __('Search') }}
                        </button>
                    </form>
                </div>
            </section> <!-- filter -->

            @if ($places && count($places) > 0)
                <!-- filter grid section -->
                <section class="filter-grid-section filter-grid-section--height place-page-hu mt-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-xl-6 mb-4 mb-lg-0 stiky-map">
                                <div class="filter-grid-section__map-container filter-grid-section--height">
                                    <!-- map toggler -->
                                    <div class="map-toggler">
                                        <button id="googleMapType" class="btn" onclick="initMap()">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="icon icon-tabler icon-tabler-map-2" width="1.3rem"
                                                 height="1.3rem"
                                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 fill="none"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="18" y1="6" x2="18" y2="6.01"/>
                                                <path d="M18 13l-3.5 -5a4 4 0 1 1 7 0l-3.5 5"/>
                                                <polyline points="10.5 4.75 9 4 3 7 3 20 9 17 15 20 21 17 21 15"/>
                                                <line x1="9" y1="4" x2="9" y2="17"/>
                                                <line x1="15" y1="15" x2="15" y2="20"/>
                                            </svg>
                                            <span class="google-maps-tooltip">{{ __("Google map") }}</span>
                                        </button>
                                        <button id="customMapType" class="btn" onclick="KSAMAP()">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="icon icon-tabler icon-tabler-map"
                                                 width="1.3rem" height="1.3rem" viewBox="0 0 24 24" stroke-width="1.5"
                                                 stroke="currentColor" fill="none" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <polyline points="3 7 9 4 15 7 21 4 21 17 15 20 9 17 3 20 3 7"/>
                                                <line x1="9" y1="4" x2="9" y2="17"/>
                                                <line x1="15" y1="7" x2="15" y2="20"/>
                                            </svg>
                                            <span class="customMapType-tooltip">{{ __("Figurative drawing") }} </span>
                                        </button>
                                        <a class="btn" href="{{ route('front.getFullMap') }}">

                                            <img src="{{ asset('front_assets/imgs/gps.svg') }}" style="width: 15px">
                                            <span class="show-maps-tooltip">{{ __("Display on the map") }} </span>
                                        </a>
                                    </div>

                                    <div id="filterGridMap" class="filter-grid-section__map"></div>
                                    <div id="placeMap3" class="filter-grid-section__local-map"></div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xl-6">
                                <div class="filter-grid-section__container filter-grid-section--height all-cards">
                                    <!-- place card  details -->
                                    <div id="mapCardsContainer"></div>
                                    <button class="full-map-card__close btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem"
                                             fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                        </svg>
                                    </button>
                                    <div class="row mx-0" id="all-data">
                                        @include('front.place.cards' , compact('places'))
                                    </div>
                                    <input type="hidden" id="page" value="1"/>
                                    <input type="hidden" id="max_page" value="{{$places->lastPage()}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> <!-- filter grid section -->
            @else
                @include('errors.notFount')
                <div class="add-thelace add-place-div">
                    <button class="add-place-btn mb-4"> {{ __("Add place") }}</button>
                    <div class="add-place-popup">
                        <span class="fk-layer"></span>
                        <div class="card-addplace">
                    <span class="close-card">
                        <i class="fa-solid fa-rectangle-xmark"></i>
                    </span>
                            <div class="card-details">
                                <strong>{{ __("Add place") }}</strong>
                                <form action="{{ route('front.add_suggest') }}" method="post">
                                    @csrf

                                    <div class="details-inputs">
                                        <label for="title">{{__("Place name")}}</label>
                                        <input type="text" id="title" name="title">
                                        <p id="u_title"
                                           style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                                    </div>
                                    <div class="details-inputs">
                                        <label for="description">{{__("Place description")}}</label>
                                        <textarea name="description" id="description" cols="10" rows="3"></textarea>
                                        <p id="u_description" style="display: none;color: red">
                                            {{ __("The data for this field is incorrect") }}</p>
                                    </div>

                                    @if (!auth()->check())
                                        <div class="details-inputs">
                                            <label for="name">{{__("Username")}}</label>
                                            <input type="text" id="name" name="name">
                                            <p id="u_name" style="display: none;color: red">
                                                {{ __("The data for this field is incorrect") }}
                                            </p>
                                        </div>
                                        <div class="details-inputs">
                                            <label for="email">{{__("E-mail")}}</label>
                                            <input type="email" id="email" name="email">
                                            <p id="u_email" style="display: none;color: red">
                                                {{ __("The data for this field is incorrect") }}
                                            </p>
                                        </div>
                                    @endif
                                    {{-- <div class="details-inputs">
                                        <label for="">النوع </label>
                                        <select name="" id="">
                                            <option value=""></option>
                                            <option value="">العنوان</option>
                                            <option value="">الخريطة</option>
                                        </select>
                                    </div> --}}
                                    <div class="add-btn-place">
                                        <button type="button">{{ __("Add place") }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@section('style')
    <style>
        .add-place-popup {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #a1a1a1ad;
            top: 0;
            z-index: 99;
            display: none;
        }

        .add-place-popup.active {
            display: block;
        }

        span.fk-layer {
            position: fixed;
            width: 100%;
            height: 100%;
            background: #a1a1a1ad;
            top: 0;
            left: 0;
        }

        .card-addplace {
            width: 50%;
            margin: 0 auto;
            position: relative;
            background: white;
            margin-top: 20px;
            box-shadow: 0px 8px 16px #606060;
            border-radius: 6px;
            padding: 30px;
        }

        span.close-card {
            position: relative;
            font-size: 25px;
            cursor: pointer;
            text-align: start;
            display: block;
        }

        .card-details {
            position: relative;
            margin: 10px 0;
        }

        .card-details strong {
            text-align: center;
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .details-inputs label {
            display: block;
            font-size: 16px;
            font-weight: 700;
            text-align: start;
        }

        .details-inputs input,
        .details-inputs textarea,
        .details-inputs select {
            width: 100%;
            border-radius: 6px;
            border: 1px solid #b2b2b2;
            padding: 7px;
        }

        .add-btn-place, .add-place-div {
            text-align: center;
            margin: 40px 0 0 0;
        }

        .add-place-div button {
            border: unset;
            background: #84548E;
            padding: 5px 20px;
            border-radius: 9px;
            color: #fff;
            font-weight: 600;
        }

        /* *********************************************** */
        @media only screen and (max-width: 800px) {
            .card-addplace {
                width: 80%;
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="{{asset('front_assets/libs/select2/select2.min.js')}}"></script>

    <script>

        $(".add-place-btn").click(function () {
            $(".add-place-popup").addClass("active")
        })
        $(".fk-layer").click(function () {
            $(".add-place-popup").removeClass("active")
        })
        $(".close-card").click(function () {
            $(".add-place-popup").removeClass("active")
        })


        $(".add-btn-place button").on("click", function (e) {
            e.preventDefault();
            var title = $('.card-details input[name="title"]').val();
            var description = $('.card-details #description').val();
            var name = $('.card-details input[name="name"]').val();
            var email = $('.card-details input[name="email"]').val();

            $(this).html("{{__("in progress")}}");
            $('#u_email').html("");
            $('#u_title').html("");
            $('#u_description').html("");
            $('#u_name').html("");


            $.ajax({
                url: "{{ route('front.add_suggest') }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    title,
                    description,
                    name,
                    email,
                },
                type: 'POST',
                success: function () {
                    toastr.success('{{__("Added successfully")}}')
                    $(".add-btn-place button").html("{{__("Transfer in progress")}}")
                    setTimeout(() => {
                        window.location.replace("{{route("front.Places")}}");
                    }, 2000);
                },
                error(data) {
                    const keys = Object.keys(data.responseJSON);
                    keys.forEach(key => $('.card-details #u_' + key).html(data.responseJSON[key]).show());
                    $(".add-btn-place button").html("{{ __('Add place') }}")
                }
            });

        });


        function KSAMAP() {
            $("#customMapType").addClass('active')
            $("#googleMapType").removeClass('active')

            const data = [
                ['sa-4293', 0],
                ['sa-tb', 1],
                ['sa-jz', 2],
                ['sa-nj', 3],
                ['sa-ri', 4],
                ['sa-md', 5],
                ['sa-ha', 6],
                ['sa-qs', 7],
                ['sa-hs', 8],
                ['sa-jf', 9],
                ['sa-sh', 10],
                ['sa-ba', 11],
                ['sa-as', 12],
                ['sa-mk', 250]
            ];

            Highcharts.mapChart('placeMap3', {
                chart: {
                    map: 'ksa',
                    backgroundColor: '#e4e4e4',
                    borderColor: '#c8c8c8',
                    borderRadius: 9,
                    events: {
                        load: function () {
                        }
                    },
                },
                tooltip: false,
                title: {
                    text: ''
                },
                mapNavigation: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        point: {
                            events: {
                                click: function () {
                                    displayStateOnGoogleMap(this);
                                },
                                mouseOver: function () {
                                    this.setState('hover')
                                },
                                mouseOut: function () {
                                    this.setState('')
                                }
                            }
                        },
                    }
                },
                series: [{
                    data: data,
                    name: '',
                    states: {
                        hover: {
                            color: '#84548E'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}',
                    }
                }],
                colorAxis: {
                    min: 1,
                    type: '',
                    minColor: '#b0b0b0',
                    maxColor: '#84548E',
                    stops: [
                        [0, '#f3f3f3']
                    ]
                }
            });
        }

        function displayStateOnGoogleMap(context) {
            let stateID = context['hc-key'].toUpperCase().replace('-', '.');
            let lat;
            let lng;
            let x;
            let y;

            KSAStates.forEach(state => {
                if (state.id === stateID) {
                    console.log(state.properties);
                    lat = parseFloat(state.properties.latitude),
                        lng = parseFloat(state.properties.longitude),
                        x = parseFloat(state.properties['hc-middle-x']),
                        y = parseFloat(state.properties['hc-middle-y'])
                    return;
                }
            });

            if (lat && lng && x && y) {
                window.location.href = `/places?lat=${lat}&lng=${lng}&x=${x}&y=${y}`;
            }
        }
        function initMap() {
            $("#googleMapType").addClass('active')
            $("#customMapType").removeClass('active')

            var places = @json($places_data_for_map);
            var allPlaces = places;

            if (allPlaces.length > 0 && allPlaces[0].lat && allPlaces[0].long) {
                var location = {
                    lat: allPlaces[0].lat,
                    lng: allPlaces[0].long
                }
            } else {
                var location = {
                    lat: 24.7136,
                    lng: 46.6753
                }
            }

            var map = new google.maps.Map(document.getElementById('placeMap3'), {
                mapId: '4b1dce4a1905ca17',
                center: location,
                zoom: 8,
                mapTypeControl: false,
                streetViewControl: false,
            });

            if (allPlaces.length > 0) {

                for (let index = 0; index < allPlaces.length; index++) {
                    const element = allPlaces[index];
                    var place_icon = element.place_icon ? element.place_icon : null
                    addMarker(element, map, true, place_icon)
                }
            }
        }

        // KSAMAP();
        initMap();

        get_sub_categories();

        function get_sub_categories() {
            let parent_id = $('#category_id').val();
            let sub_category_id = "{{ implode(',',request('sub_category_id')??[]) }}"
            sub_category_id = sub_category_id.split(",");

            if (parent_id != '') {
                $.ajax({
                    url: "{{ route('front.getSubCategory') }}",
                    type: "POST",
                    data: {
                        parent_id: parent_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.length > 0) {
                            let result = '';
                            response.forEach(element => {
                                result +=
                                    `
                                                            <option value="${element.id}" ${(sub_category_id.includes(element.id.toString())) ? 'selected' : ''}>${element.name}</option> `
                            });
                            $("#sub_category_id").html('')
                            $("#sub_category_id").append(result)
                        } else {
                            $("#sub_category_id").html('')
                            $("#sub_category_id").append('')
                        }
                    },
                    error(data) {
                    }
                });
            }
        }

        get_cities();

        function get_cities() {
            let parent_id = $('#region_id').val();
            let city_id = "{{ implode(',',request('city_id')??[]) }}"
            city_id = city_id.split(",");
            if (parent_id != '') {
                $.ajax({
                    url: "{{ route('front.getCities') }}",
                    type: "POST",
                    data: {
                        parent_id: parent_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.length > 0) {
                            let result = '';
                            response.forEach(element => {
                                result +=
                                    `
                                                            <option value="${element.id}" ${(city_id.includes(element.id.toString())) ? 'selected' : ''}>${element.name} - ${element.region.name}</option> `
                            });
                            $("#city_id").html('')
                            $("#city_id").append(result)
                        } else {
                            let result = '<option selected value="0"> {{__("Region")}}</option>';
                            $("#city_id").html('')
                            $("#city_id").append(result)
                        }
                    },
                    error(data) {
                    }
                });
            }
        }

        function getMarkerCard(place) {

            if (place && place.lat != '' && place.long != '') {
                return `<div class="map-card" data-place-id="${place.id}"
                                                data-map-lat="${place.lat}" data-map-lng="${place.long}">
                                                <img class="map-card__img" src="{{ asset('') }}${(place.image ? place.image : 'front_assets/imgs/zad1.jpg')}">
                                                <div class="map-card__footer">
                                                    <h5 class="map-card__title">${place.title}</h5>
                                                    <p class="map-card__text">${(place.city ? place?.city?.name : '..')}, ${(place.region ? place?.region?.name : '..')}</p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rate d-flex align-items-center">
                                                            <span class="mx-1"><svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z" fill="#FFCA00"/></svg></span><span class="mx-1">${place.rate}</span></div>
                                                        <span class="views mr-3">(${place.review} {{ __("Review") }})</span>
                                                    </div>
                                                    <a href="/${place.type}-details/${place.slug}" class="btn map-card__btn"><svg width="12" height="12" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.8088 8.80184C4.93123 8.67495 5 8.50288 5 8.32347C5 8.14405 4.93123 7.97198 4.8088 7.8451L1.57628 4.49585L4.8088 1.14661C4.92776 1.019 4.99358 0.848082 4.99209 0.670675C4.9906 0.493269 4.92192 0.323565 4.80085 0.198114C4.67977 0.0726652 4.51598 0.00150681 4.34476 -3.52859e-05C4.17353 -0.00157642 4.00857 0.0666227 3.88541 0.189874L0.191199 4.01749C0.0687744 4.14437 0 4.31644 0 4.49585C0 4.67527 0.0687744 4.84734 0.191199 4.97422L3.88541 8.80184C4.00787 8.92868 4.17394 8.99994 4.34711 8.99994C4.52027 8.99994 4.68634 8.92868 4.8088 8.80184Z" fill="white"/></svg></a>
                                                    </div>
                                                </div>
                                                </div>`;
            }
            return null
        }

        function addMarker(element, map, isActive = false, icon = null) {

            var location = {
                lat: element.lat,
                lng: element.long
            }

            var marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: {
                    url: isActive ? (icon ? icon : '/front_assets/imgs/marker-open.svg') :
                        '/front_assets/imgs/marker.svg',
                    scaledSize: new google.maps.Size(35, 35)
                },
            });

            var infowindow = new google.maps.InfoWindow({
                content: getMarkerCard(element)
            });

            google.maps.event.addListener(marker, 'click', function (e) {
                var tempMap = infowindow.getMap();
                if (tempMap !== null && typeof tempMap !== "undefined") {
                    infowindow.close();
                } else {
                    infowindow.open(map, marker);
                }
            });
        }

    </script>
@endsection
