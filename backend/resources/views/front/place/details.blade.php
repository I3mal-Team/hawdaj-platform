@extends('layouts.front.hawdaj_master')

@section('style')
    <style>
        .carousel-control-next, .carousel-control-prev {
            transform: rotate(-180deg) !important;
            height: 65px !important;
        }

        .place-slider__container .place-slider-nav {
            width: 100% !important;
        }

        .slick-vertical .slick-slide {
            width: 33% !important;
        }

        .place-slider__container {
            align-items: flex-start !important;
        }

        .slick-list {
            height: 80.875px !important;
        }
    </style>
@endsection

@section('content')
    <main class="place-details mx-auto overflow-hidden py-4">
        <div class="container-fluid">
            @include('front.place.mobile_details')
            @include('front.place.pc_details')
        </div>
    </main>
    <!-- map modal -->
    <div class="modal fade " id="map" tabindex="-1" aria-labelledby="map" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Display on the map") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div id="placeMap"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let lat = null,
            long = null;

        $(document).ready(function () {

            // get users lat/long

            var getPosition = {
                enableHighAccuracy: false,
                timeout: 9000,
                maximumAge: 0
            };

            function success(gotPosition) {
                lat = gotPosition.coords.latitude;
                long = gotPosition.coords.longitude;
                console.log(`${lat}`, `${long}`);

            };

            function error(err) {
                console.warn(`ERROR(${err.code}): ${err.message}`);
            };

            navigator.geolocation.getCurrentPosition(success, error, getPosition);


            $('#map_btn').on('click', function () {
                $('#map').modal('show')
            })


            $('.close').on('click', function () {
                $('#map').modal('hide')
            })

        });
        // suggested places slider
        if ($('.suggested-places__slider').length) {
            new Swiper('.suggested-places__slider', {
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
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 1,
                    }
                }
            });
        }

        function initMap() {
            var place = @json($place_for_map);

            var location = {
                lat: place.lat,
                lng: place.long
            };
            var map = new google.maps.Map(document.getElementById('placeMap'), {
                mapId: '4b1dce4a1905ca17',
                center: location,
                zoom: 8,
                mapTypeControl: false,
                streetViewControl: false,
            });

            let marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: {
                    url: "{{ asset('front_assets/imgs/marker-open.svg') }}",
                    scaledSize: new google.maps.Size(35, 35)
                },
            });

            if (place && place.lat && place.long) {
                google.maps.event.addListener(marker, 'click', function (event) {
                    if (lat != '' && lat != " " && lat != null) {
                        var ulat = lat;
                        var ulong = long;
                    } else {
                        var ulat = place.lat;
                        var ulong = place.long;
                    }
                    let url = "https://www.google.com/maps/dir/" + ulat + "," + ulong + "/" + place.title + "/@" + place.lat +
                        "," + place.long + ",6z"
                    // https://www.google.com/maps/dir/31.0305379,30.4748227/Hilton/@31.1404997,29.9482694,10z
                    window.open(url, '_blank');
                });
            }
        }

        window.initMap = initMap();

        // new StarRating("form");


    </script>
@endsection
