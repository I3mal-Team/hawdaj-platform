@foreach ($places as $place)
    <div
        class="col-12 col-sm-6 col-md-4 col-lg-12 col-xl-6 mb-4 d-flex justify-content-center">
        <a href="{{ url('place-details/' . $place->slug) }}"
           class="palce-card card h-100" style="width:300px">
            <!-- tooltip -->
            <div class="palce-card__tooltip ">
                <img
                    src="{{ asset($place->image ?? 'front_assets/imgs/zad1.jpg') }}"
                    class="palce-card__tooltip--img " alt="place">
                <div class="palce-card__tooltip--text">
                    <h5 class="map-card__title"> {{ $place->title }}</h5>
                    <p class="map-card__text">
                        {{ $place->city ? $place->city->name : '' }},
                        {{ $place->region ? $place->region->name : '' }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rate d-flex align-items-center"><span
                                class="mx-1"><svg width="14" height="13"
                                                  viewBox="0 0 14 13"
                                                  fill="none"
                                                  xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                                                fill="#FFCA00"/>
                                                                        </svg></span><span
                                class="mx-1">{{ $place->rate }}</span></div>
                        <span class="views mr-3">({{ $place->review }}
                            {{ __("Review") }})</span>
                    </div>
                    <p class="mb-1">{!! $place->description ?? '' !!}</p>
                    <div class="active-see-more">
                        <div class="more-data">
                            <span>{{__("Explore more")}}</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- card img -->
            <img
                src="{{ asset($place->image ?? 'front_assets/imgs/zad1.jpg') }}"
                class="card-img-top" alt="place">

            <!-- card content -->
            <div class="card-body pb-4">
                <h5 class="card-title">{{ $place->title }} </h5>
                <p class="card-text">{{ $place->city ? $place->city->name : '' }}
                    ,
                    {{ $place->region ? $place->region->name : '' }}
                </p>
                <div class="d-flex align-items-center">
                    <div class="rate d-flex align-items-center">
                                                                <span class="mx-1">
                                                                    <svg width="14" height="13"
                                                                         viewBox="0 0 14 13" fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M7.90806 0.968665C7.55064 0.193793 6.44936 0.193793 6.09194 0.968665L4.97736 3.38508C4.83169 3.70089 4.5324 3.91833 4.18704 3.95928L1.54446 4.2726C0.697071 4.37307 0.356754 5.42046 0.983254 5.99983L2.93698 7.80658C3.19232 8.0427 3.30663 8.39454 3.23885 8.73565L2.72024 11.3457C2.55393 12.1827 3.44489 12.83 4.1895 12.4132L6.51156 11.1134C6.81503 10.9435 7.18497 10.9435 7.48844 11.1134L9.8105 12.4132C10.5551 12.83 11.4461 12.1827 11.2798 11.3457L10.7611 8.73565C10.6934 8.39454 10.8077 8.0427 11.063 7.80658L13.0167 5.99983C13.6432 5.42046 13.3029 4.37307 12.4555 4.2726L9.81296 3.95928C9.4676 3.91833 9.16831 3.70089 9.02264 3.38508L7.90806 0.968665Z"
                                                                            fill="#FFCA00"/>
                                                                    </svg>
                                                                </span>
                        <span class="mx-1">{{ $place->rate }}</span>
                    </div>
                    <span
                        class="views mr-3">({{ $place->review }} {{ __("Review") }})</span>
                </div>
            </div>

        </a>
    </div>
@endforeach
