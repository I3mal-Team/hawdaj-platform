<div class="row mobile_details">
    <div class="col-lg-8 order-2 order-lg-1">
        <div class="row mx-0">
            <div class="col-sm-10 px-0">
                <!-- reviews -->
                <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                    <!-- reviews -->
                    @php $r = $place->ratings->take(10); @endphp
                    @if(count($r) > 0)
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h3 class="place-details__sub-title mb-0">{{ __("Ratings") }}</h3>

                            <button data-bs-toggle="modal" data-bs-target="#rating"
                                    class="btn btn-primary btn-sm">
                                {{ __("Add rating") }}
                            </button>
                        </div>
                        <ul class="place-details__reviews py-2 rates"
                            style="max-height: 400px;overflow-y: scroll;">
                            @foreach ($r as $rate)
                                <li class="d-flex  justify-content-between">
                                    <div class="d-flex  gap-lg">
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
                                            {{ date('Y:m:d, h:i A', strtotime($place->created_at)) }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if(count($r) <= 0)
                        <ul class="place-details__reviews py-2 rates">
                            <li id="empty" data-bs-toggle="modal" data-bs-target="#rating"
                                style="margin-right: 240px;overflow-y:hidden;overflow-x:hidden"
                                class="row justify-content-center">
                                <div class="review-img col-12" style="margin-right: 100px;">
                                    <img src="{{ asset('front_assets/imgs/empty.png') }}" alt="empty">
                                </div>
                                <div class=" col-12 d-flex">
                                    <div>
                                        <p class="review-text mr-3">{{ __('no_rev_yet') }}</p>
                                        <h4 class="review-author btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#rating"> {{ __('be_first_to_add_rev') }} </h4>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 order-1 order-lg-2">

        <div id="carouselExampleIndicators" class="carousel inner-carsul slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                        class="active" aria-current="true" aria-label="Slide 0"></button>
                @if(count($place->galleries))
                    @foreach(range(1 , count($place->galleries)) as $range)
                        <button type="button" data-bs-target="#carouselExampleIndicatorsPC"
                                data-bs-slide-to="{{$range}}"
                                aria-label="Slide {{$range}}"></button>
                    @endforeach
                @endif
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset($place->image) }}" class="d-block" alt="...">
                </div>
                @foreach ($place->galleries as $key => $galary)
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
                @if($place->lat && $place->long)
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
                @elseif($place->address)
                    <a href="{{$place->address}}" class="btn p-1" target="_blank">
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
                    <span>({{ $place->rate }})</span>
                </button>
            </div>
        </div>
        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="place-details__title mb-0">{{ $place->title }}</h2>

                <div class="d-flex align-items-center">
                    <div class="views d-flex align-items-center">
                                        <span class="mx-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                                 fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path
                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                        </span>
                        <span class=" mx-1">{{ $place->views_num }}</span>
                    </div>
                    <span class="views mr-3">({{ $place->review }} {{ __("Review") }})</span>
                </div>


            </div>
            <div class="d-flex align-items-center gap">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem"
                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="11" r="3"/>
                                        <path
                                            d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/>
                                    </svg>
                                </span>
                <span>{{ $place->city ? $place->city->name : '' }} ,
                                    {{ $place->region ? $place->region->name : '' }}</span>
            </div>
            @if($place->address_type == 'map')
                <p class="mb-0 mt-3">{{ $place->address }}</p>
            @endif
        </div>
        <!-- description -->
        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
            <h3 class="place-details__sub-title">{{__("Description")}}</h3>
            <p>{!! $place->description ?? '' !!}</p>
        </div>
        <div class="section-shadow section-radius d-flex gap p-3 p-sm-4 mb-4">
            <div class="place-details__info">
                <p>{{ __('Created date') }}</p>
                <span>{{ $place ? date('Y:m:d', strtotime($place->created_at)) : '' }}</span>
            </div>
            <div class="place-details__info">
                <p>{{ __('Status') }}</p>
                <span>{{ $place && $place->active ? __('Published') : __('Unpublished') }}</span>
            </div>
        </div>

        <div class="place-details__addition-info section-shadow section-radius p-3 p-sm-4 mb-4 nice-scroll">
            @if ($place->temperature)
                <div class="place-details__meta">
                    <span>{{__("Temperature")}}</h3></span>
                    <span>{{ $place->temperature }}</span>
                </div>
            @endif
            @if ($place->price)
                <div class="place-details__meta">
                    <span>{{__("Price")}}</span>
                    <span>{{ $place->price->name }} </span>
                </div>
            @endif

            @if(isset($place->seasons_tra))
                <div class="place-details__meta">
                    <span>{{__("Occasions")}}</span>
                    @if(count($place->seasons_tra) >0)
                        <span>
                            @foreach($place->seasons_tra as $season)
                                {{ $season }},
                            @endforeach
                        </span>
                    @endif
                </div>
            @endif

            @if ($place->price)
                <div class="place-details__meta">
                    <span> {{ __('dashboard.visited')}}</span>
                    <span>{{ $place->visited ? __("Yes") : __("No") }} </span>
                </div>
            @endif
            @if ($place->whatsapp)
                <div class="place-details__meta">
                    <span>{{__("Whatsapp") }}  </span>
                    <span>{{ $place->whatsapp }}</span>
                </div>
            @endif
            @if ($place->facebook_link)
                <div class="place-details__meta">
                    <span>{{__("FACEBOOK") }}  </span>
                    <a href="{{ $place->facebook_link }}">{{__("FACEBOOK") }}</a>
                </div>
            @endif
            @if ($place->instagram_link)
                <div class="place-details__meta">
                    <span>{{__("INSTGRAM") }}</span>
                    <a href="{{ $place->instagram_link }}">{{__("INSTGRAM") }}</a>
                </div>
            @endif
            @if ($place->website_link)
                <div class="place-details__meta">
                    <span>{{__("Website")}}</span>
                    <a href="{{ $place->website_link }}">{{__("Website")}}</a>
                </div>
            @endif
            @if ($place->ticket_link)
                <div class="place-details__meta">
                    <span>{{__("Ticket link")}}</span>
                    <a href="{{ $place->ticket_link }}">{{__("Ticket link")}}</a>
                </div>
            @endif
        </div>
        @if ($best_Places)
            <div class="suggested-places section-shadow section-radius p-3 p-sm-4 mb-4">
                <h5 class="mb-4 font-weight-bold">{{__("Selected places") }} </h5>
                <div dir="rtl" class="swiper suggested-places__slider">
                    <div class="swiper-wrapper">
                        @if (count($best_Places) > 0)
                            @foreach ($best_Places as $p)
                                <div class="swiper-slide">
                                    <a href="/place-details/{{ $p->slug }}" class="palce-card card h-100">
                                        <!-- card img -->
                                        <img src="{{ asset($p->image) }}" class="card-img-top" alt="place">

                                        <!-- card content -->
                                        <div class="card-body pb-4">
                                            <h5 class="card-title"> {{ $p->title }}</h5>
                                            <p class="card-text">{{ $p->city ? $p->city->name : '' }},
                                                {{ $p->region ? $p->region->name : '' }}
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <div class="rate d-flex align-items-center">
                                                <span class="mx-1">
                                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                            fill="#FFCA00"/>
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
        @if ($best_stores)
            <div class="suggested-places section-shadow section-radius p-3 p-sm-4 mb-4">
                <h5 class="mb-4 font-weight-bold">{{__("Selected stores") }} </h5>
                <div dir="rtl" class="swiper suggested-places__slider">
                    <div class="swiper-wrapper">
                        @if (count($best_stores) > 0)
                            @foreach ($best_stores as $p)
                                <div class="swiper-slide">
                                    <a href="/store-details/{{ $p->slug }}" class="palce-card card h-100">
                                        <!-- card img -->
                                        <img src="{{ asset($p->image) }}" class="card-img-top" alt="store">

                                        <!-- card content -->
                                        <div class="card-body pb-4">
                                            <h5 class="card-title"> {{ $p->title }}</h5>
                                            <p class="card-text">{{ $p->city ? $p->city->name : '' }},
                                                {{ $p->region ? $p->region->name : '' }}
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <div class="rate d-flex align-items-center">
                                                <span class="mx-1">
                                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                            fill="#FFCA00"/>
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
    </div>
</div>
