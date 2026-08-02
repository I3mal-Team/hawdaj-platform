@extends('layouts.front.hawdaj_master')
@section('content')
<main class="place-details mx-auto overflow-hidden py-4">
    <div class="container-fluid">
        {{-- <h2 class="page-title mb-4">{{ $store->title }}</h2> --}}
        <div class="row">
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="row mx-0">
                    {{-- <div class="col-sm-2 d-none d-sm-flex px-0 place-slider__container">
                        <button class="carousel-control-prev" type="button" data-bs-target="#placeSlider" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <div class="place-slider-nav overflow-hidden">
                        <div class="place-slider-nav__slide">
                                <img src="{{ asset($store->image) }}" class="d-block" alt="...">
                            </div>
                            @foreach ($store->galleries as $galary)
                            <div class="place-slider-nav__slide">
                                <img src="{{ asset($galary->file) }}" class="d-block" alt="...">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-next" type="button" data-bs-target="#placeSlider" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div> --}}
                        {{-- <div class="col-sm-10 px-0"> --}}

                        <div id="placeSlider" class="carousel slide inner-carsul zd-carusel" data-ride="carousel">
                            <div class="owl-carousel owl-theme zad-details">
                                <div class="item">
                                    <div class="carousel-item active">
                                        <img src="{{ asset($store->image) }}" class="d-block w-100" alt="...">
                                    </div>
                                </div>
                                @foreach ($store->galleries as $key => $galary)
                                    <div class="item">
                                        <div class="carousel-item active">
                                            <img src="{{ asset($galary->file) }}" class="d-block w-100" alt="...">
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            {{-- <div id="carouselExampleControls" class="carousel slide " data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($store->galleries as $key => $galary)
                                        <div class="carousel-item active">
                                            <img src="{{ asset($galary->file) }}" class="d-block w-100" alt="...">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div> --}}


                            {{-- <div class="carousel-inner">
                            <div class="place-slider-nav__slide">
                                <img src="{{ asset($store->image) }}" class="d-block" alt="...">
                            </div>
                                @foreach ($store->galleries as $key => $galary)
                                <div class="carousel-item ">
                                    <img src="{{ asset($galary->file) }}" class="d-block w-100" alt="...">
                                </div>
                                @endforeach
                            </div> --}}
                            <div class="carousel__place-info d-flex align-items-center gap-lg share-box">
                                <button data-bs-toggle="modal" data-bs-target="#share" class="btn p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                        fill="currentColor" class="bi bi-share-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z" />
                                    </svg>
                                </button>

                                @if ($store->address_type == 'link')
                                    <a href="{{ $store->address }}" class="btn p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="11" r="3" />
                                            <path
                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                        </svg>
                                    </a>
                                @elseif($store->address_type == 'map')
                                    <button data-bs-toggle="modal" data-bs-target="#map" class="btn p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="12" cy="11" r="3" />
                                            <path
                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                        </svg>
                                    </button>
                                @endif

                                <button data-bs-toggle="modal" data-bs-target="#rating"
                                    class="btn p-1 d-flex align-items-center gap">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                        </svg>
                                    </span>
                                    <span>({{ $store->rate }})</span>
                                </button>
                            </div>
                        </div>
                        <!--  -->
                        <div class="d-sm-none px-0 place-slider__container">
                            <button class="carousel-control-prev" type="button" data-bs-target="#placeSlider"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </button>
                            <div class="place-slider-nav overflow-hidden">
                                <div class="carousel-item active">
                                    <img src="{{ asset($store->image) }}" class="d-block  w-100" alt="...">
                                </div>
                                @foreach ($store->galleries as $galary)
                                    <div class="place-slider-nav__slide">
                                        <img src="{{ asset($galary->file) }}" class="d-block" alt="...">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-next" type="button" data-bs-target="#placeSlider"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h2 class="place-details__title mb-0">{{ $store->title }}</h2>

                                <div class="d-flex align-items-center">
                                    <div class="views d-flex align-items-center">
                                        <span class="mx-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                                fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                <path
                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                            </svg>
                                        </span>
                                        <span class=" mx-1">{{ $store->views_num }}</span>
                                    </div>
                                    <span class="views mr-3">({{ $store->review }} {{ __("Review") }})</span>
                                </div>


                            </div>
                            <div class="d-flex align-items-center gap">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="12" cy="11" r="3" />
                                        <path
                                            d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                    </svg>
                                </span>
                                <span>{{ $store->city ? $store->city->name : '' }} ,
                                    {{ $store->region ? $store->region->name : '' }}</span>
                            </div>
                            <!-- <p class="mb-0 mt-3">{{ $store->address }}</p> -->
                            @if ($store->address_type == 'map')
                                <p class="mb-0 mt-3">{{ $store->address }}</p>
                            @endif
                        </div>
                        <!-- description -->
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <h3 class="place-details__sub-title">{{__("Description")}}</h3>
                            <p class="mb-1">{!! $store->description ? substr(strip_tags($store->description), 0, 200) : '' !!}</p>
                        </div>

                        <!-- reviews -->
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <!-- reviews -->
                            @php $r = $store->ratings->take(10); @endphp
                            @if (count($r) > 0)
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h3 class="place-details__sub-title mb-0">{{ __("Ratings") }}</h3>

                                    <button data-bs-toggle="modal" data-bs-target="#rating" class="btn btn-primary btn-sm">
                                        {{ __("Add rating") }}
                                    </button>
                                </div>
                                <ul class="place-details__reviews py-2 rates"
                                    style="max-height: 400px;overflow-y: scroll;">
                                    @foreach ($r as $rate)
                                        <li class="d-flex flex-column flex-sm-row justify-content-between">
                                            <div class="d-flex flex-column flex-sm-row gap-lg">
                                                <div class="review-img">
                                                    <img src="{{ asset('front_assets/imgs/empty.png') }}" alt="empty">
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
                                                                height="1rem" fill="currentColor" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                            </svg>
                                                        @endfor
                                                        @for ($x = 0; $x < 5 - $rate->rate; $x++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1rem"
                                                                height="1rem" fill="currentColor" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span>({{ $rate->rate }})</span>
                                                </div>
                                                <p dir="ltr" class="review-date mb-0">
                                                    {{ date('Y:m:d, h:i A', strtotime($store->created_at ?? '')) }}
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
                                            <h4 class="review-author btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#rating">{{ __('be_first_to_add_rev') }}</h4>
                                        </div>
                                    </li>
                                </ul>
                            @endif

                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 order-1 order-lg-2">
                    <div class="section-shadow section-radius d-flex gap p-3 p-sm-4 mb-4">
                        <div class="place-details__info">
                            <p>{{ __('Created date') }}</p>
                            <span>{{ $store ? date('Y:m:d', strtotime($store->created_at ?? '')) : '' }}</span>
                        </div>
                        <div class="place-details__info">
                            <p>{{ __('Status') }}</p>
                            <span>{{ $store && $store->active ? __('Published') : __('Unpublished') }}</span>
                        </div>
                    </div>
                    <div class="place-details__addition-info section-shadow section-radius p-3 p-sm-4 mb-4 nice-scroll">
                        @if ($store->seasons)
                            <div class="place-details__meta">
                                <span>{{__("Occasions")}}</span>
                                <span>{{ implode(',', $store->seasons) }} </span>
                            </div>
                        @endif
                        @if ($store->whatsapp)
                            <div class="place-details__meta">
                                <span>{{__("Whatsapp") }}  </span>
                                <span>{{ $store->whatsapp }}</span>
                            </div>
                        @endif
                        @if ($store->facebook_link)
                            <div class="place-details__meta">
                                <span>{{__("FACEBOOK") }}  </span>
                                <a href="{{ $store->facebook_link }}">{{__("FACEBOOK") }}</a>
                            </div>
                        @endif
                        @if ($store->Instagram_link)
                            <div class="place-details__meta">
                                <span>{{__("INSTGRAM") }}</span>
                                <a href="{{ $store->Instagram_link }}">{{__("INSTGRAM") }}</a>
                            </div>
                        @endif
                        @if ($store->website_link)
                            <div class="place-details__meta">
                                <span>{{__("Website")}}</span>
                                <a href="{{ $store->website_link }}">{{__("Website")}}</a>
                            </div>
                        @endif
                    </div>
                    @if ($best_stores)
                        <div class="suggested-places section-shadow section-radius p-3 p-sm-4 mb-4">
                            <h5 class="mb-4 font-weight-bold">{{__("Selected stores") }} </h5>
                            <div dir="rtl" class="swiper suggested-places__slider">
                                <div class="swiper-wrapper">
                                    @if (count($best_stores) > 0)
                                        @foreach ($best_stores as $p)
                                            <div class="swiper-slide">
                                                <a href="/store-details/{{ $p->slug }}"
                                                    class="palce-card card h-100">
                                                    <!-- card img -->
                                                    <img src="{{ asset($p->image) }}" class="card-img-top"
                                                        alt="store">

                                                    <!-- card content -->
                                                    <div class="card-body pb-4">
                                                        <h5 class="card-title"> {{ $p->title }}</h5>
                                                        <p class="card-text">{{ $p->city ? $p->city->name : '' }},
                                                            {{ $p->region ? $p->region->name : '' }}
                                                        </p>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rate d-flex align-items-center">
                                                                <span class="mx-1">
                                                                    <svg width="14" height="13"
                                                                        viewBox="0 0 14 13" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                                            fill="#FFCA00" />
                                                                    </svg>
                                                                </span>
                                                                <span class="mx-1">{{ $p->rate }}</span>
                                                            </div>
                                                            <span class="views mr-3">({{ $p->review }} {{ __("Review") }})</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <h6>{{__("no_chosen_stores") }} </h6>
                                    @endif
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    @endif
                    @if ($best_Places)
                        <div class="suggested-places section-shadow section-radius p-3 p-sm-4 mb-4">
                            <h5 class="mb-4 font-weight-bold">{{__("Selected places") }} </h5>
                            <div dir="rtl" class="swiper suggested-places__slider">
                                <div class="swiper-wrapper">
                                    @if (count($best_Places) > 0)
                                        @foreach ($best_Places as $p)
                                            <div class="swiper-slide">
                                                <a href="/place-details/{{ $p->slug }}"
                                                    class="palce-card card h-100">
                                                    <!-- card img -->
                                                    <img src="{{ asset($p->image) }}" class="card-img-top"
                                                        alt="place">

                                                    <!-- card content -->
                                                    <div class="card-body pb-4">
                                                        <h5 class="card-title"> {{ $p->title }}</h5>
                                                        <p class="card-text">{{ $p->city ? $p->city->name : '' }},
                                                            {{ $p->region ? $p->region->name : '' }}
                                                        </p>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rate d-flex align-items-center">
                                                                <span class="mx-1">
                                                                    <svg width="14" height="13"
                                                                        viewBox="0 0 14 13" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                                            fill="#FFCA00" />
                                                                    </svg>
                                                                </span>
                                                                <span class="mx-1">{{ $p->rate }}</span>
                                                            </div>
                                                            <span class="views mr-3">({{ $p->review }} {{ __("Review") }})</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <h6>{{__("no_chosen_places") }} </h6>
                                    @endif
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
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

    <!-- share modal -->
    <div class="modal fade" id="share" tabindex="-1" aria-labelledby="share" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Share the place") }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <div class="share-btn">
                        <a data-id="fb" title="Facebook" class="facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                            </svg>
                        </a>
                        <a data-id="tw" title="Twitter" class="twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                            </svg>
                        </a>
                        <a data-id="in" title="LinkedIn" class="linkedin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
                            </svg>
                        </a>
                        <a data-id="tg" title="Telegram" class="telegram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- rate modal -->
    <div class="modal fade" id="rating" tabindex="-1" aria-labelledby="rating" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content rating" method="GET" action="/" id="rateForm">
                <div class="modal-header">
                    <h5 class="modal-title">تقييم</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-3">
                    <div class="form-group mb-0 d-flex justify-content-center">
                        <div class="rating__stars">
                            <input id="rating-1" class="rating__input rating__input-1" type="radio" name="rating"
                                value="1">
                            <input id="rating-2" class="rating__input rating__input-2" type="radio" name="rating"
                                value="2">
                            <input id="rating-3" class="rating__input rating__input-3" type="radio" name="rating"
                                value="3">
                            <input id="rating-4" class="rating__input rating__input-4" type="radio" name="rating"
                                value="4">
                            <input id="rating-5" class="rating__input rating__input-5" type="radio" name="rating"
                                value="5">
                            <label class="rating__label" for="rating-1">
                                <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                    aria-hidden="true">
                                    <g transform="translate(16,16)">
                                        <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)" />
                                    </g>
                                    <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <g transform="translate(16,16) rotate(180)">
                                            <polygon class="rating__star-stroke"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="none" />
                                            <polygon class="rating__star-fill"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="#000" />
                                        </g>
                                        <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                            <polyline class="rating__star-line" transform="rotate(0)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(72)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(144)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(216)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(288)"
                                                points="0 4,0 16" />
                                        </g>
                                    </g>
                                </svg>
                                <span class="rating__sr">1 star—Terrible</span>
                            </label>
                            <label class="rating__label" for="rating-2">
                                <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                    aria-hidden="true">
                                    <g transform="translate(16,16)">
                                        <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)" />
                                    </g>
                                    <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <g transform="translate(16,16) rotate(180)">
                                            <polygon class="rating__star-stroke"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="none" />
                                            <polygon class="rating__star-fill"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="#000" />
                                        </g>
                                        <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                            <polyline class="rating__star-line" transform="rotate(0)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(72)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(144)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(216)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(288)"
                                                points="0 4,0 16" />
                                        </g>
                                    </g>
                                </svg>
                                <span class="rating__sr">2 stars—Bad</span>
                            </label>
                            <label class="rating__label" for="rating-3">
                                <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                    aria-hidden="true">
                                    <g transform="translate(16,16)">
                                        <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)" />
                                    </g>
                                    <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <g transform="translate(16,16) rotate(180)">
                                            <polygon class="rating__star-stroke"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="none" />
                                            <polygon class="rating__star-fill"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="#000" />
                                        </g>
                                        <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                            <polyline class="rating__star-line" transform="rotate(0)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(72)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(144)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(216)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(288)"
                                                points="0 4,0 16" />
                                        </g>
                                    </g>
                                </svg>
                                <span class="rating__sr">3 stars—OK</span>
                            </label>
                            <label class="rating__label" for="rating-4">
                                <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                    aria-hidden="true">
                                    <g transform="translate(16,16)">
                                        <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)" />
                                    </g>
                                    <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <g transform="translate(16,16) rotate(180)">
                                            <polygon class="rating__star-stroke"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="none" />
                                            <polygon class="rating__star-fill"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="#000" />
                                        </g>
                                        <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                            <polyline class="rating__star-line" transform="rotate(0)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(72)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(144)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(216)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(288)"
                                                points="0 4,0 16" />
                                        </g>
                                    </g>
                                </svg>
                                <span class="rating__sr">4 stars—Good</span>
                            </label>
                            <label class="rating__label" for="rating-5">
                                <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                    aria-hidden="true">
                                    <g transform="translate(16,16)">
                                        <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)" />
                                    </g>
                                    <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <g transform="translate(16,16) rotate(180)">
                                            <polygon class="rating__star-stroke"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="none" />
                                            <polygon class="rating__star-fill"
                                                points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                fill="#000" />
                                        </g>
                                        <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                            <polyline class="rating__star-line" transform="rotate(0)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(72)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(144)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(216)"
                                                points="0 4,0 16" />
                                            <polyline class="rating__star-line" transform="rotate(288)"
                                                points="0 4,0 16" />
                                        </g>
                                    </g>
                                </svg>
                                <span class="rating__sr">5 stars—Excellent</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">{{__("name")}}</label>
                        <input type="text" id="name" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">الأيميل</label>
                        <input type="email" id="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="rateText">{{ __("Rate text") }}</label>
                        <textarea class="form-control w-100" name="rate_text" id="rateText" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __("Close") }}</button>
                    <button type="button" onclick="rateFunction()" class="btn btn-primary btn-sm">
                        {{ __("Send rate") }}
                    </button>
                </div>
            </form>
        </div>0
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

        new StarRating("form");



        function rateFunction() {
            let rate = $('input[name="rating"]:checked').val()
            let name = $('#name').val()
            let email = $('#email').val()
            let rateText = $('#rateText').val()
            let parent_id = "{{ $store->id }}"

            $('#name_t').hide();
            $('#email_t').hide();
            $('#rateText_t').hide();
            $('#rate_t').hide();

            if (name == '' || name == null || name == ' ') {
                $('#name_t').show();
            }
            // if (email == '' || email == null || email == ' ') {
            //     $('#email_t').show();
            // }
            // if (rateText == '' || rateText == null || rateText == ' ') {
            //     $('#rateText_t').show();
            // }
            if (rate == '' || rate == null || rate == ' ') {
                $('#rate_t').show();
            }

            if (rate < 1 || name == '') { // || email == '' || rateText == ''
                //alert('please complete your data')
            } else {
                $.ajax({
                    url: "{{ route('front.ratePlaces') }}",
                    type: "POST",
                    data: {
                        rate: rate,
                        name: name,
                        email: email,
                        rateText: rateText,
                        parent_id: parent_id,
                        type: "zad_elgadels",
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
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
                                            <p class="review-text">${response.rateText ? response.rateText : '' }</p>
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
                                <span>(${ response.rate })</span>
                            </div>
                            <p dir="ltr" class="review-date mb-0">
                                ${ response.created_at }
                            </p>
                        </div>
                    </li>
                    `;
                        $('#empty').hide();
                        $('.rates').append(rate);
                        toastr.success('{{__("Rating added successfully")}}');

                        // location.reload()
                    },
                    error(data) {}
                });
            }
        }
    </script>
@endsection
