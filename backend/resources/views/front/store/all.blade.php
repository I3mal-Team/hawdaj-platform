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
                            <label for="">{{ __('Store name') }}</label>
                            <input type="text" class="form-control" value="{{ request('search') }}"
                                   placeholder="{{ __('Store name') }}" name="search">
                        </div>
                        <div class="secltion-forms">
                            <label for="">{{ __('Category') }}</label>
                            <select multiple class="form-control select2-input" name="category_id[]"
                                    id="category_id"
                                    onchange="get_sub_categories()">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id , request('category_id') ?? [])  ? 'selected' : '' }}>
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
                        {{--                            <div class="secltion-forms">--}}
                        {{--                                <label for=""> {{ __('Location type') }}</label>--}}
                        {{--                                <select class="form-control select2-input" name="address_type" id="address_type">--}}
                        {{--                                    <option selected value="0">{{ __('Location type') }}</option>--}}
                        {{--                                    <option value="link" {{ request('address_type') == 'link' ? 'selected' : '' }}>--}}
                        {{--                                        {{__("Link")}}--}}
                        {{--                                    </option>--}}
                        {{--                                    <option value="map" {{ request('address_type') == 'map' ? 'selected' : '' }}>--}}
                        {{--                                        {{__("Map")}}--}}
                        {{--                                    </option>--}}
                        {{--                                </select>--}}
                        {{--                            </div>--}}
                        <button type="submit" id="search" class="btn btn-primary">
                            {{ __('Search') }}
                        </button>
                    </form>
                </div>
            </section> <!-- filter -->
            @if ($stores && count($stores) > 0)
                <!-- filter grid section -->
                <section class="filter-grid-section filter-grid-section--height place-page-hu mt-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-xl-6 mb-4 mb-lg-0 stiky-map overflow-hidden">
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
                                    <!-- fullscreen map page navigator -->
                                    {{--                                    <button class="btn show-fullscreen-map-navigator" id="showFullscreenMapPage">--}}
                                    {{--                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.3rem" height="1.3rem"--}}
                                    {{--                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"--}}
                                    {{--                                             stroke-linecap="round" stroke-linejoin="round">--}}
                                    {{--                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>--}}
                                    {{--                                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2"/>--}}
                                    {{--                                            <path d="M4 16v2a2 2 0 0 0 2 2h2"/>--}}
                                    {{--                                            <path d="M16 4h2a2 2 0 0 1 2 2v2"/>--}}
                                    {{--                                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2"/>--}}
                                    {{--                                        </svg>--}}
                                    {{--                                    </button>--}}

                                    <div id="placeMap" class="filter-grid-section__local-map"></div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-xl-6">

                                <div class="filter-grid-section__container filter-grid-section--height">
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
                                        @include('front.store.cards' , compact('stores'))
                                    </div>
                                    <input type="hidden" id="page" value="1"/>
                                    <input type="hidden" id="max_page" value="{{$stores->lastPage()}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> <!-- filter grid section -->
            @else
                @include('errors.notFount')
            @endif
        </div>
    </main>

@endsection

@section('scripts')
    <script src="{{asset('front_assets/libs/select2/select2.min.js')}}"></script>

    <script>
        var chart;

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

            chart = Highcharts.mapChart('placeMap', {
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

            var places = @json($stores->items());
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

            var map = new google.maps.Map(document.getElementById('placeMap'), {
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

        KSAMAP();
        // initMap();

        get_sub_categories();

        function get_sub_categories() {
            let parent_id = $('#category_id').val();
            let sub_category_id = "{{ implode(',',request('sub_category_id')??[]) }}"
            sub_category_id = sub_category_id.split(",");
            console.log(parent_id, sub_category_id)
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
