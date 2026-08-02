@extends('layouts.front.hawdaj_master')
@section('content')
    <link rel="stylesheet" href="{{ asset('front_assets/css/evnt-style.css') }}">
    <!-- ******************* evnt-details-page************************************ -->
    <!-- ******************* evnt-details-page************************************ -->
    <!-- ******************* evnt-details-page************************************ -->

    <section class="evnt-page-details m-4">
        <div class="details-evnt-page container m-4">
            {{-- <h2>{{ $event->title ?? '' }}</h2> --}}
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12">
                    <div id="carouselExampleIndicatorsPC" class="carousel inner-carsul slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicatorsPC" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 0"></button>
                            @if(count($event->galleries))
                                @foreach(range(1 , count($event->galleries)) as $range)
                                    <button type="button" data-bs-target="#carouselExampleIndicatorsPC"
                                            data-bs-slide-to="{{$range}}"
                                            aria-label="Slide {{$range}}"></button>
                                @endforeach
                            @endif
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset($event->image) }}" class="d-block" alt="...">
                            </div>
                            @foreach ($event->galleries as $key => $galary)
                                <div class="carousel-item ">
                                    <img src="{{ asset($galary->file) }}" class="d-block w-100" alt="...">
                                </div>
                            @endforeach
                        </div>
                        <div class="carousel__place-info d-flex align-items-center gap-lg share-box ">
                            <button data-bs-toggle="modal" data-bs-target="#share" class="btn p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                     fill="currentColor" class="bi bi-share-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z"/>
                                </svg>
                            </button>
                            @if($event->lat && $event->long)
                                <button id="map_btn" data-bs-toggle="modal" data-bs-target="#map" class="btn p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                         width="50" height="50" x="0" y="0" viewBox="0 0 64 64"
                                         style="enable-background:new 0 0 50 50" xml:space="preserve" class=""><g
                                            transform="matrix(0.6000000000000002,0,0,0.6000000000000002,12.799999999999983,12.799999999999983)">
                                            <g data-name="03-google map">
                                                <circle cx="45" cy="19" r="6" fill="#a83229" data-original="#a83229"
                                                        class=""></circle>
                                                <path fill="#4a9bf6" d="M41 63H7l17-17z" data-original="#4a9bf6"
                                                      class=""></path>
                                                <path fill="#d3d9f2"
                                                      d="M63 7v50a6 6 0 0 1-6 6h-4L30 40l7.61-7.61A102.883 102.883 0 0 0 45 41s14-14.27 14-22a13.921 13.921 0 0 0-1.57-6.43z"
                                                      data-original="#d3d9f2" class=""></path>
                                                <path fill="#ffdf64"
                                                      d="m63 7-5.57 5.57a13.908 13.908 0 0 0-6-6L57 1a6 6 0 0 1 6 6z"
                                                      data-original="#ffdf64" class=""></path>
                                                <path fill="#f7f7ff" d="M53 63H41L24 46l6-6z" data-original="#f7f7ff"
                                                      class=""></path>
                                                <path fill="#ffdf64"
                                                      d="M37.61 32.39 30 40l-6 6L7 63a6 6 0 0 1-6-6l31.89-31.89a50.652 50.652 0 0 0 4.72 7.28z"
                                                      data-original="#ffdf64" class=""></path>
                                                <path fill="#28be77"
                                                      d="m57 1-5.57 5.57A13.991 13.991 0 0 0 31 19a14.149 14.149 0 0 0 1.89 6.11L1 57V7a6 6 0 0 1 6-6z"
                                                      data-original="#28be77" class=""></path>
                                                <path fill="#d73d3f"
                                                      d="M57.43 12.57A13.921 13.921 0 0 1 59 19c0 7.73-14 22-14 22a102.883 102.883 0 0 1-7.39-8.61 50.652 50.652 0 0 1-4.72-7.28A14.149 14.149 0 0 1 31 19a14 14 0 0 1 26.43-6.43zM51 19a6 6 0 1 0-6 6 6 6 0 0 0 6-6z"
                                                      data-original="#d73d3f"></path>
                                                <path fill="#3b7cc4" d="m11 59-4 4h34l-4-4z" data-original="#3b7cc4"
                                                      class=""></path>
                                                <path fill="#aeb2d4" d="M57 59h-8l4 4h4a6 6 0 0 0 6-6v-4a6 6 0 0 1-6 6z"
                                                      data-original="#aeb2d4" class=""></path>
                                                <path fill="#b8bce0" d="M62.117 7.883A5.945 5.945 0 0 1 63 11V7z"
                                                      data-original="#b8bce0" class=""></path>
                                                <path fill="#ffb844"
                                                      d="M57 5a5.992 5.992 0 0 1 5.117 2.883L63 7a6 6 0 0 0-6-6l-4 4z"
                                                      data-original="#ffb844" class=""></path>
                                                <path fill="#cacfe8" d="m37 59 4 4h12l-4-4z" data-original="#cacfe8"
                                                      class=""></path>
                                                <path fill="#ffb844"
                                                      d="M7 59a5.992 5.992 0 0 1-5.117-2.883L1 57a6 6 0 0 0 6 6l4-4z"
                                                      data-original="#ffb844" class=""></path>
                                                <path fill="#1fa463"
                                                      d="M1 53v4l.883-.883A5.945 5.945 0 0 1 1 53zM7 5h46l4-4H7a6 6 0 0 0-6 6v4a6 6 0 0 1 6-6z"
                                                      data-original="#1fa463" class=""></path>
                                                <path fill="#f7f7ff"
                                                      d="M13 22a9 9 0 1 1 7.025-14.625l-3.125 2.5A5 5 0 1 0 17.583 15H13v-4h7a2 2 0 0 1 2 2 9.011 9.011 0 0 1-9 9z"
                                                      data-original="#f7f7ff" class=""></path>
                                                <path fill="#91323c"
                                                      d="M45 17a6 6 0 0 1 5.65 4 6 6 0 1 0-11.3 0A6 6 0 0 1 45 17z"
                                                      data-original="#91323c" class=""></path>
                                                <path fill="#1fa463"
                                                      d="M31.2 20.71A14.048 14.048 0 0 0 31 23a10.248 10.248 0 0 0 .668 3.332l1.222-1.222a17.958 17.958 0 0 1-1.69-4.4z"
                                                      data-original="#1fa463" class=""></path>
                                                <path fill="#aeb2d4"
                                                      d="M58.793 20.735C57.082 28.681 45 41 45 41a102.883 102.883 0 0 1-7.39-8.61l-1.68 1.68a72.01 72.01 0 0 0 1.68 2.32A102.883 102.883 0 0 0 45 45s14-14.27 14-22a13.843 13.843 0 0 0-.207-2.265z"
                                                      data-original="#aeb2d4" class=""></path>
                                                <path fill="#ffb844"
                                                      d="m32.89 25.11-1.222 1.222a21.63 21.63 0 0 0 1.222 2.778 43.491 43.491 0 0 0 3.04 4.96l1.68-1.68a50.652 50.652 0 0 1-4.72-7.28z"
                                                      data-original="#ffb844" class=""></path>
                                            </g>
                                        </g></svg>
                                </button>
                            @elseif($event->address)
                                <a href="{{$event->address}}" class="btn p-1" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                         width="50" height="50" x="0" y="0" viewBox="0 0 64 64"
                                         style="enable-background:new 0 0 50 50" xml:space="preserve" class=""><g
                                            transform="matrix(0.6000000000000002,0,0,0.6000000000000002,12.799999999999983,12.799999999999983)">
                                            <g data-name="03-google map">
                                                <circle cx="45" cy="19" r="6" fill="#a83229" data-original="#a83229"
                                                        class=""></circle>
                                                <path fill="#4a9bf6" d="M41 63H7l17-17z" data-original="#4a9bf6"
                                                      class=""></path>
                                                <path fill="#d3d9f2"
                                                      d="M63 7v50a6 6 0 0 1-6 6h-4L30 40l7.61-7.61A102.883 102.883 0 0 0 45 41s14-14.27 14-22a13.921 13.921 0 0 0-1.57-6.43z"
                                                      data-original="#d3d9f2" class=""></path>
                                                <path fill="#ffdf64"
                                                      d="m63 7-5.57 5.57a13.908 13.908 0 0 0-6-6L57 1a6 6 0 0 1 6 6z"
                                                      data-original="#ffdf64" class=""></path>
                                                <path fill="#f7f7ff" d="M53 63H41L24 46l6-6z" data-original="#f7f7ff"
                                                      class=""></path>
                                                <path fill="#ffdf64"
                                                      d="M37.61 32.39 30 40l-6 6L7 63a6 6 0 0 1-6-6l31.89-31.89a50.652 50.652 0 0 0 4.72 7.28z"
                                                      data-original="#ffdf64" class=""></path>
                                                <path fill="#28be77"
                                                      d="m57 1-5.57 5.57A13.991 13.991 0 0 0 31 19a14.149 14.149 0 0 0 1.89 6.11L1 57V7a6 6 0 0 1 6-6z"
                                                      data-original="#28be77" class=""></path>
                                                <path fill="#d73d3f"
                                                      d="M57.43 12.57A13.921 13.921 0 0 1 59 19c0 7.73-14 22-14 22a102.883 102.883 0 0 1-7.39-8.61 50.652 50.652 0 0 1-4.72-7.28A14.149 14.149 0 0 1 31 19a14 14 0 0 1 26.43-6.43zM51 19a6 6 0 1 0-6 6 6 6 0 0 0 6-6z"
                                                      data-original="#d73d3f"></path>
                                                <path fill="#3b7cc4" d="m11 59-4 4h34l-4-4z" data-original="#3b7cc4"
                                                      class=""></path>
                                                <path fill="#aeb2d4" d="M57 59h-8l4 4h4a6 6 0 0 0 6-6v-4a6 6 0 0 1-6 6z"
                                                      data-original="#aeb2d4" class=""></path>
                                                <path fill="#b8bce0" d="M62.117 7.883A5.945 5.945 0 0 1 63 11V7z"
                                                      data-original="#b8bce0" class=""></path>
                                                <path fill="#ffb844"
                                                      d="M57 5a5.992 5.992 0 0 1 5.117 2.883L63 7a6 6 0 0 0-6-6l-4 4z"
                                                      data-original="#ffb844" class=""></path>
                                                <path fill="#cacfe8" d="m37 59 4 4h12l-4-4z" data-original="#cacfe8"
                                                      class=""></path>
                                                <path fill="#ffb844"
                                                      d="M7 59a5.992 5.992 0 0 1-5.117-2.883L1 57a6 6 0 0 0 6 6l4-4z"
                                                      data-original="#ffb844" class=""></path>
                                                <path fill="#1fa463"
                                                      d="M1 53v4l.883-.883A5.945 5.945 0 0 1 1 53zM7 5h46l4-4H7a6 6 0 0 0-6 6v4a6 6 0 0 1 6-6z"
                                                      data-original="#1fa463" class=""></path>
                                                <path fill="#f7f7ff"
                                                      d="M13 22a9 9 0 1 1 7.025-14.625l-3.125 2.5A5 5 0 1 0 17.583 15H13v-4h7a2 2 0 0 1 2 2 9.011 9.011 0 0 1-9 9z"
                                                      data-original="#f7f7ff" class=""></path>
                                                <path fill="#91323c"
                                                      d="M45 17a6 6 0 0 1 5.65 4 6 6 0 1 0-11.3 0A6 6 0 0 1 45 17z"
                                                      data-original="#91323c" class=""></path>
                                                <path fill="#1fa463"
                                                      d="M31.2 20.71A14.048 14.048 0 0 0 31 23a10.248 10.248 0 0 0 .668 3.332l1.222-1.222a17.958 17.958 0 0 1-1.69-4.4z"
                                                      data-original="#1fa463" class=""></path>
                                                <path fill="#aeb2d4"
                                                      d="M58.793 20.735C57.082 28.681 45 41 45 41a102.883 102.883 0 0 1-7.39-8.61l-1.68 1.68a72.01 72.01 0 0 0 1.68 2.32A102.883 102.883 0 0 0 45 45s14-14.27 14-22a13.843 13.843 0 0 0-.207-2.265z"
                                                      data-original="#aeb2d4" class=""></path>
                                                <path fill="#ffb844"
                                                      d="m32.89 25.11-1.222 1.222a21.63 21.63 0 0 0 1.222 2.778 43.491 43.491 0 0 0 3.04 4.96l1.68-1.68a50.652 50.652 0 0 1-4.72-7.28z"
                                                      data-original="#ffb844" class=""></path>
                                            </g>
                                        </g></svg>
                                </a>
                            @endif

                            <button data-bs-toggle="modal" data-bs-target="#rating"
                                    class="btn p-1 d-flex align-items-center gap">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                             fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                    </span>
                                <span>({{ $event->rate }})</span>
                            </button>
                        </div>
                    </div>

                    <div class="tabs-details">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                        aria-selected="true">{{__("Reviews")}}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">{{__("Description")}}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">{{__("Ratings")}}
                                </button>
                            </li>
                            @if ($event->type == 'map')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-map-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-map" type="button" role="tab"
                                            aria-controls="pills-map" aria-selected="false">{{__("The map")}}
                                    </button>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h2 class="place-details__title mb-0">{{ $event->title }}</h2>

                                        <div class="d-flex align-items-center">
                                            <div class="views d-flex align-items-center">
                                                <span class="mx-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem"
                                                         height="1.2rem" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                        <path
                                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                    </svg>
                                                </span>
                                                <span class=" mx-1">{{ $event->views_num }}</span>
                                            </div>
                                            <span class="views mr-3">({{ $event->review }} {{__("Review")}})</span>
                                        </div>


                                    </div>
                                    <div class="d-flex align-items-center gap">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem"
                                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                 fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="11" r="3"/>
                                                <path
                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>
                                            </svg>
                                        </span>
                                        <span>{{ $event->city ? $event->city->name : '' }} ,
                                            {{ $event->region ? $event->region->name : '' }}</span>
                                    </div>
                                    <!-- <p class="mb-0 mt-3">{{ $event->address }}</p> -->
                                    @if ($event->address_type == 'map')
                                        <p class="mb-0 mt-3">{{ $event->address }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                 aria-labelledby="pills-profile-tab">

                                <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                                    <h3 class="place-details__sub-title">{{__("Description")}}</h3>
                                    <p class="mb-1">{!! $event->description !!}</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                 aria-labelledby="pills-contact-tab">
                                <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                                    <!-- reviews -->
                                    @php $r = isset($event->ratings) ? $event->ratings->take(10) : []; @endphp
                                    @if (count($r) > 0)
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <h3 class="place-details__sub-title mb-0">{{__("Ratings")}}</h3>

                                            <button data-bs-toggle="modal" data-bs-target="#rating"
                                                    class="btn btn-primary btn-sm">
                                                {{__("Add rating")}}
                                            </button>
                                        </div>
                                        <ul class="place-details__reviews py-2 rates"
                                            style="max-height: 400px;overflow-y: scroll;">
                                            @foreach ($r as $rate)
                                                <li class="d-flex flex-column flex-sm-row justify-content-between">
                                                    <div class="d-flex flex-column flex-sm-row gap-lg">
                                                        <div class="review-img">
                                                            <img src="{{ asset('front_assets/imgs/empty.png') }}"
                                                                 alt="empty">
                                                        </div>
                                                        <div>
                                                            <h4 class="review-author">{{ $rate->name }}</h4>
                                                            <p class="review-text">{{ $rate->rateText ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-sm-center pt-3">
                                                        <!-- rating -->
                                                        <div class="d-flex align-items-center gap mb-2">
                                                            <div class="review-rate d-flex">
                                                                @for ($x = 0; $x < $rate->rate; $x++)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem"
                                                                         height="1rem" fill="currentColor"
                                                                         viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                                    </svg>
                                                                @endfor
                                                                @for ($x = 0; $x < 5 - $rate->rate; $x++)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="1rem"
                                                                         height="1rem" fill="currentColor"
                                                                         viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                                                                    </svg>
                                                                @endfor
                                                            </div>
                                                            <span>({{ $rate->rate }})</span>
                                                        </div>
                                                        <p dir="ltr" class="review-date mb-0">
                                                            {{ date('Y:m:d, h:i A', strtotime($event->created_at ?? '')) }}
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if (count($r) <= 0)
                                        <ul class="place-details__reviews py-2 rates">
                                            <li id="empty" style="text-align: center">
                                                <div class="review-img text-center">
                                                    <img src="{{ asset('front_assets/imgs/empty.png') }}" alt="empty">
                                                </div>
                                                <div>
                                                    <p class="review-text me-3 my-2">{{ __('no_rev_yet') }}</p>
                                                    <h4 class="review-author btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rating">{{ __('be_first_to_add_rev') }}</h4>
                                                </div>
                                            </li>
                                        </ul>
                                    @endif

                                </div>
                            </div>
                            @if ($event->type == 'map')
                                <div class="tab-pane fade" id="pills-map" role="tabpanel"
                                     aria-labelledby="pills-map-tab">
                                    <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                                        <h2 class="place-details__title mb-0">{{__("The map")}}</h2>
                                        <div id="placeMap"></div>

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12">
                    <div class="details-side-evnt">
                        <div class="section-shadow section-radius d-flex gap p-3 p-sm-4 mb-4">
                            <div class="place-details__info">
                                <p>{{__("Created date")}}</p>
                                <span>{{ $event->created_at ? date('Y-m-d', strtotime($event->created_at)) : '-' }}</span>
                            </div>
                            <div class="place-details__info">
                                <p>{{__("Status")}}</p>
                                <span> {{ $event->active ? __("Published") : __("Unpublished") }}</span>
                            </div>
                        </div>
                        <div
                            class="place-details__addition-info section-shadow section-radius p-3 p-sm-4 mb-4 nice-scroll">
                            @if ($event->whatsapp)
                                <div class="place-details__meta on-ev-page">
                                    <em> <i class="fa-brands fa-whatsapp"></i></em>
                                    <a href="https://wa.me/{{$event->whatsapp}}">{{__("dashboard.whatsapp")}}</a>
                                </div>
                            @endif
                            @if ($event->facebook)
                                <div class="place-details__meta on-ev-page">
                                    <em> <i class="fa-brands fa-facebook-f"></i> </em>
                                    <a target="_blank" href="{{ $event->facebook }}">{{__("dashboard.facebook")}}</a>
                                </div>
                            @endif
                            @if ($event->instagram)
                                <div class="place-details__meta on-ev-page">
                                    <em> <i class="fa-brands fa-instagram"></i></em>
                                    <a target="_blank" href="{{ $event->instagram }}">{{__("dashboard.instagram")}}</a>
                                </div>
                            @endif
                            @if ($event->website)
                                <div class="place-details__meta on-ev-page">
                                    <em> <i class="fa-solid fa-blog"></i></em>
                                    <a target="_blank" href="{{ $event->website }}">{{__("dashboard.website")}}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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
