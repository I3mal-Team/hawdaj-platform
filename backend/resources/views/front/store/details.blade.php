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
            {{-- <h2 class="page-title mb-4">{{ $store->title }}</h2> --}}
            @include('front.store.mobile_details')
            @include('front.store.pc_details')
        </div>
    </main>
    <!-- map modal -->
    <div class="modal fade map-modal" id="map" tabindex="-1" aria-labelledby="map" aria-hidden="true">
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


            $('#map_btn').on('click', function () {
                $('#map').modal('show')
            })


            $('.close').on('click', function () {
                $('#map').modal('hide')
            })

        }

        function initMap() {
            var data = '<?php echo json_encode($store); ?>';
            var place = JSON.parse(data);

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

            new google.maps.Marker({
                position: location,
                map: map,
                icon: {
                    url: "{{ asset('') }}" + (true ? 'front_assets/imgs/marker-open.svg' :
                        'front_assets/imgs/marker.svg'),
                    scaledSize: new google.maps.Size(35, 35)
                },
            });
        }

        window.initMap = initMap();

        // new StarRating("form");


    </script>
@endsection
