@extends('layouts.front.hawdaj_master')
@section('style')
    <style>
        #map {
            position: sticky !important;
            overflow: hidden !important;
            top: 30px !important;
            height: 650px !important;
        }
    </style>
@endsection
@section('content')

    <section class="zad section-padding row">
        <div class="my_trips_section inner_trips_section d-flex flex-direction-row mr-2 col-7">
            <div class="my_trips_right_section"></div>
            <div class="my_trips_left_section">
                <div class="container p-0">
                    <div class="mb-4 ">
                        <h2 class="section__title">{{__("Trip program")}}
                        </h2>
                    </div>

                    <div class="mb-4  all-btn-trips">
                        @if (isset($items))
                            <form action="{{ route('front.save_trip') }}" method="post" class="d-flex">
                                @csrf
                                <input type="hidden" name="items" value="{{ json_encode($items) }}">
                                <input type="hidden" name="days" value="{{ $days }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <input type="text" required placeholder="{{__("Trip title")}}" class="form-control p-3" name="name" value="{{old("name")}}">
                                <input type="hidden" name="item_per_day" value="{{ $funny_place_per_day }}">
                                <button type="submit" class="btn save-trip w-25 btn-warning">
                                    {{__("Save trip")}}
                                </button>
                            </form>
                        @else
                            <form action="{{ route('front.delete_trip' , $trip->id) }}" method="post"
                                  class="change-trp">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger col-12 mr-2 ml-2">
                                    {{__("Delete Trip")}}
                                </button>
                            </form>
                        @endif

                        @if (isset($trip) || !isset($trip->token) || is_null($trip->token))

                            <div class="popup-saved-trip">
                                <div class="card-save-trip">
                                    <div class="close-icon">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </div>

                                    <form action="{{ route('front.save_trip_to_email') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="items"
                                               value="{{ isset($trip) ? json_encode($trip->items) : json_encode($items) }}">
                                        <input type="hidden" name="days" value="{{ $days }}">
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <input type="hidden" name="name" value="{{ $daterange }}">
                                        <input type="hidden" name="item_per_day" value="{{ $funny_place_per_day }}">
                                        <div class="details-save-trip">
                                            <label for="{{__("name")}}">{{__("name")}}</label>
                                            <input type="text" name="user_name" placeholder="{{__("name")}}" required>
                                            <p id="u_user_name" style="display: none;color: red">
                                                {{ __("The data for this field is incorrect") }}
                                            </p>
                                        </div>
                                        <div class="details-save-trip">
                                            <label for="email">{{__("E-mail")}}</label>
                                            <input type="email" name="email" placeholder="{{__("E-mail")}}" required>
                                            <p id="u_email" style="display: none;color: red">
                                                {{ __("The data for this field is incorrect") }}
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class=" px-0">
                    @if ($places)
                        <div class="zad__grid-container">
                            @foreach ($places as $i => $d)
                                <h1 class="col-12 my_trips_day_header">
                                    <div>
                                        {{__("Day")}} {{ $i + 1 }}
                                    </div>
                                    <div class="my_trips_right_date">
                                        {{ date('d F ', strtotime($date . '+' . $i . ' days')) }}
                                    </div>
                                </h1>

                                <div class="d-flex flex-row flex-wrap mx-0">
                                    @foreach (collect($d) as $place)
                                        <div class="trip_day_place mb-4 d-flex card-on-trip">
                                            <a href="{{ url('place-details/' . $place['slug']) }}"
                                               class="palce-card my_trips_place_card card h-100" style="width:300px">
                                                <!-- tooltip -->
                                                <div class="palce-card__tooltip">
                                                    <img
                                                        src="{{ asset($place['image'] ?? 'front_assets/imgs/zad1.jpg') }}"
                                                        class="palce-card__tooltip--img" alt="place">
                                                    <div class="palce-card__tooltip--text">
                                                        <p class="mb-1">{!! $place['description'] ?? '' !!}</p>
                                                    </div>
                                                </div>

                                                <!-- card img -->
                                                <img src="{{ asset($place['image'] ?? 'front_assets/imgs/zad1.jpg') }}"
                                                     class="card-img-top" alt="place">

                                                <!-- card content -->
                                                <div class="card-body pb-4">
                                                    <h5 class="card-title my_trips_card_title">{{ $place['title'] }} </h5>
                                                    <p class="card-text my_trips_card_date">
                                                        {{ isset($place['city']) ? $place['city']['name'] . ',' : '' }}
                                                        {{ isset($place['region']) ? $place['region']['name'] : '' }}
                                                    </p>
                                                    <div class="d-flex align-items-center">
                                                        <div class="rate d-flex align-items-center">
                                                            <span class="mx-1 d-flex">
                                                                <svg width="15" height="15" viewBox="0 0 14 13"
                                                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                                        fill="#FFCA00"/>
                                                                </svg>
                                                            </span>
                                                            <span class="mx-1">{{ $place['rate'] }}</span>
                                                        </div>
                                                        <span
                                                            class="views mr-3">({{ $place['review'] }} {{ __("Review") }})</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center alert alert-danger">{{__("No Result Found")}}</div>
                    @endif
                </div>
            </div>
        </div>

        <div id="map" class="col-4 ml-1"></div>
    </section>

    <div class="last-thre-btn">

        @if (isset($items))
            @if (auth()->check())
                <form action="{{ route('front.save_trip') }}" method="post" class="">
                    @csrf
                    <input type="hidden" name="items" value="{{ json_encode($items) }}">
                    <input type="hidden" name="days" value="{{ $days }}">
                    <input type="hidden" name="days" value="{{ $days }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="name" value="{{ $daterange }}">
                    <input type="hidden" name="item_per_day" value="{{ $funny_place_per_day }}">
                    <div class="fixed-btn">
                        <button type="submit" class="saved-btn-trp fixed-saved-btn">
                            <span><i class="fa-solid fa-heart"></i></span>
                            <em>{{__("Save trip")}} </em>
                        </button>
                    </div>
                </form>
            @else
                <div class="fixed-btn">
                    <button type="button" onclick="makeALoginPopupShow()" class="saved-btn-trp fixed-saved-btn">
                        <span><i class="fa-solid fa-heart"></i></span>
                        <em>{{__("Save trip")}} </em>
                    </button>
                </div>
            @endif

            {{--            <form action="{{ route('front.action_selected_places') }}" method="post" class="change-trp">--}}
            {{--                @csrf--}}
            {{--                --}}{{-- <input type="hidden" name="trip_days" value="{{ $days }}"> --}}
            {{--                <input type="hidden" name="daterange" value="{{ $daterange }}">--}}
            {{--                <input type="hidden" name="type" value="{{ $type }}">--}}
            {{--                <input type="hidden" name="funny_place_per_day" value="{{ $funny }}">--}}
            {{--                <input type="hidden" name="region1" value="{{ $region1 }}">--}}
            {{--                <input type="hidden" name="lat1" value="{{ $lat1 }}">--}}
            {{--                <input type="hidden" name="long1" value="{{ $long1 }}">--}}
            {{--                <input type="hidden" name="lat2" value="{{ $lat2 }}">--}}
            {{--                <input type="hidden" name="long2" value="{{ $long2 }}">--}}
            {{--                <input type="hidden" name="region2" value="{{ $region2 }}">--}}
            {{--                <input type="hidden" name="categories" value="{{ $selected_categories }}">--}}
            {{--                <input type="hidden" name="season" value="{{ $season }}">--}}
            {{--                <div class="fixed-btn">--}}
            {{--                    <button type="submit" class="btn btn-warning col-12 ">--}}
            {{--                        <span><i class="fa-brands fa-stack-exchange"></i></span>--}}
            {{--                        <em>تغيير الرحله</em>--}}
            {{--                    </button>--}}
            {{--                </div>--}}
            {{--            </form>--}}
        @endif
    </div>

@endsection

@section('scripts')
    <script>
        let map, activeInfoWindow, markers = [],
            marker, paths = [];
        var points_first_last = [];

        // function initMap() {

        //     map = new google.maps.Map(document.getElementById("map"), {
        // center: {
        //     lat: 24.7136,
        //     lng: 46.6753
        // },
        //         zoom: 5
        //     });

        // initMarkers();
        // drawDirections();
        // }

        function initMap() {
            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            // var chicago = new google.maps.LatLng(41.850033, -87.6500523);
            var mapOptions = {
                zoom: 7,
                center: {
                    lat: 24.7136,
                    lng: 46.6753
                }
            }
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            directionsRenderer.setMap(map);

            initMarkers();
            // drawDirections();
            calcRoute(directionsService, directionsRenderer);
        }

        function calcRoute(directionsService, directionsRenderer) {
            var request = {
                origin: points_first_last[0], // new google.maps.LatLng(24.6305889, 46.702096),
                destination: points_first_last[1], // new google.maps.LatLng(19.7934998, 45.1215324),
                travelMode: 'DRIVING'
            };
            directionsService.route(request, function (result, status) {
                console.log(result, status);

                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                }
            });
        }


        function drawDirections() {
            for (let index = 0; index < paths.length; index++) {
                const element = paths[index];
                element.setOptions({
                    map: map
                });
            }
        }

        function initMarkers() {
            const initialMarkers = <?php echo json_encode($places); ?>;
            points_first_last[0] = new google.maps.LatLng(initialMarkers[0][0]['lat'], initialMarkers[0][0]['long']);
            points_first_last[1] = new google.maps.LatLng(
                initialMarkers[(initialMarkers.length - 1)][(initialMarkers[(
                    initialMarkers.length - 1)].length - 1)]['lat'],
                initialMarkers[(initialMarkers.length - 1)][(
                    initialMarkers[(
                        initialMarkers.length - 1)].length - 1)]['long']);

            for (let index = 0; index < initialMarkers.length; index++) {

                const markerData = initialMarkers[index];

                var points = [];

                for (let i = 0; i < markerData.length; i++) {
                    const markerData2 = markerData[i];

                    if (markerData2.lat && markerData2.long) {

                        var place_icon = markerData2.place_icon ? markerData2.place_icon : null

                        addMarker(markerData2, map, true, place_icon)

                        points[i] = new google.maps.LatLng(markerData2.lat, markerData2.long);
                    }
                }

                paths[index] = new google.maps.Polyline({
                    path: points,
                    strokeColor: generateRandomColor()
                });
            }
        }

        function generateRandomColor() {
            let maxVal = 0xFFFFFF; // 16777215
            let randomNumber = Math.random() * maxVal;
            randomNumber = Math.floor(randomNumber);
            randomNumber = randomNumber.toString(16);
            let randColor = randomNumber.padStart(6, 0);
            var c = `#${randColor.toUpperCase()}`
            return c;
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

        function getMarkerCard(place) {

            if (place && place.lat != '' && place.long != '') {
                return `<div class="map-card" data-place-id="${place.id}" data-map-lat="${place.lat}" data-map-lng="${place.long}">
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

        initMap()

        $(".save-trips").on('click', function () {
            $(".popup-saved-trip").addClass("active")
        })
        $(".close-icon").on('click', function () {
            $(".popup-saved-trip").removeClass("active")
        })

        $("#btn_via_mail").on("click", function (e) {
            e.preventDefault();
            var items = $('.card-save-trip input[name="items"]').val();
            var days = $('.card-save-trip input[name="days"]').val();
            var date = $('.card-save-trip input[name="date"]').val();
            var name = $('.card-save-trip input[name="name"]').val();
            var item_per_day = $('.card-save-trip input[name="item_per_day"]').val();
            var user_name = $('.card-save-trip input[name="user_name"]').val();
            var email = $('.card-save-trip input[name="email"]').val();

            $(this).html("{{__("Sending Now")}}");
            $('.card-save-trip #u_email').html("");
            $('.card-save-trip #u_user_name').html("");

            $.ajax({
                url: "{{ route('front.save_trip_to_email') }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    items,
                    days,
                    date,
                    name,
                    item_per_day,
                    user_name,
                    email,
                },
                type: 'POST',
                success: function () {
                    toastr.success('{{__("Sent successfully")}}')
                    $("#btn_via_mail").html("{{__("Transfer in progress")}}")

                    setTimeout(() => {
                        window.location.replace('{{route("front.index")}}');
                    }, 3000);
                },
                error(data) {
                    const keys = Object.keys(data.responseJSON);
                    keys.forEach(key => $('.card-save-trip #u_' + key).html(data.responseJSON[key]).show());
                    $("#btn_via_mail").html("{{__("Save your trip via email")}}")
                }
            });

        });


    </script>
    <script>
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();

            if (scroll >= 350) {
                $(".last-thre-btn").addClass("acive");
            } else {
                $(".last-thre-btn").removeClass("acive");
            }
        });
    </script>
@endsection
