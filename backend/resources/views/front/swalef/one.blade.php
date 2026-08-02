@extends('layouts.front.hawdaj_master')
@section('content')
    <section class="filter-grid-section filter-grid-section--height swalef-page">
        <div class="container">
            {{-- <h2 class="page-title mb-4">{{ $swalef->title ?? '' }}</h2> --}}
            <div class="section-boxes container mt-4">
                <div class="inner-page">

                    <div class="card-img">
                        <img src="{{ asset($swalef->image ?? 'front_assets/imgs/our-services.jpg') }}">
                        {{-- <div class="date-type">
                            <div class="arte-type d-flex justify-content-center">
                                <span class="rate-project">
                                    <em class="rating-">
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        (5)
                                    </em>
                                </span>
                            </div>
                        </div> --}}

                        <div class="d-flex">
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
                                {{-- <span class=" mx-1">{{ $swalef->views_num }}</span> --}}
                            </div>
                            <span class="views mr-3">({{ $swalef->review }} {{ __("Review") }})</span>
                        </div>

                        <div class="share-social-media">
                            <div class="carousel__place-info d-flex align-items-center gap-lg share-box">
                                <button data-bs-toggle="modal" data-bs-target="#share" class="btn p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                         fill="currentColor" class="bi bi-share-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z"/>
                                    </svg>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#rating"
                                        class="btn p-1 d-flex align-items-center gap">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.2rem" height="1.2rem"
                                             fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                    </span>
                                    <span>({{ $swalef->rate }})</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="width:30px;"></div>
                    <div class="section-details">
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="date-type"> <span
                                        class=" mx-1"><strong>{{ __('Created date') }}</strong> <em>
                                            {{ $swalef->created_at ? date('d-m-Y', strtotime($swalef->created_at)) : '' }}</em></span>
                                </div>
                                <div class="date-type"> <span class="views mr-3"> <strong>{{ __('Status') }}</strong>
                                        <em>{{ $swalef->active ? __('Published') : __('Unpublished') }}</em></span>
                                </div>


                            </div>
                        </div>


                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">

                            <div class="d-flex align-items-center gap">
                                <span> {{__("Description")}}</span>
                            </div>

                            <p class="mb-0 mt-3"> {{ $swalef->description ?? '' }}</p>


                        </div>

                        <!-- description -->
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <h3 class="place-details__sub-title">{{__("Content")}}</h3>
                            @if ($swalef->type == 'file')
                                <div class="mb-3 download_link_area_in_course_content">
                                    @php
                                        $ext = pathinfo(asset($swalef->content), PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array($ext, ['pdf', 'application/pdf']))
                                        <iframe height="700px" width="100%"
                                                src="{{ asset('storage/' . $swalef->content) }}#toolbar=0"
                                                class="mb-3"></iframe>
                                    @elseif (in_array($ext, ['png', 'jpg', 'jpeg']))
                                        <img
                                            src="{{ asset('storage/' . $swalef->content ?? 'front_assets/imgs/our-services.jpg') }}">
                                    @else
                                        <div
                                            class="d-flex justify-content-center w-100 download_inner_continaer align-items-center">
                                            <a class="download_file_in_course_content"
                                               href="{{ asset('storage/' . $swalef->content) }}" download>
                                                <div class="download_icon"><i class="fas fa-download"></i></div>
                                                <div class="download_text">{{__("Download file")}}</div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p> {{ $swalef->content ?? '' }}</p>
                            @endif

                        </div>

                        <!-- reviews -->
                        <div class="section-shadow section-radius p-3 p-sm-4 mb-4">
                            <!-- reviews -->
                            @php $r = $swalef->ratings->take(10); @endphp
                            @if (count($r) > 0)
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
                                                                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                            </svg>
                                                        @endfor
                                                        @for ($x = 0; $x < 5 - $rate->rate; $x++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="1rem"
                                                                 height="1rem" fill="currentColor" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span>({{ $rate->rate }})</span>
                                                </div>
                                                <p dir="ltr" class="review-date mb-0">
                                                    {{ date('Y:m:d, h:i A', strtotime($swalef->created_at ?? '')) }}
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

                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-4">
            <h2 class="page-title mb-2 mt-4" style="margin-top: 20px"> {{__("Swalef most read")}} </h2>
            <div class="section-boxes container">
                <div class="section-boxes-wrap">
                    <div class="section-title">
                        <div class="section-projects-overflow">
                            <div class="card-row">
                                @foreach ($swalefs as $swalef)
                                    <div class="card-seation m-4">
                                        <a href="{{ url(app()->getLocale() . '/swalef/' . $swalef->id) }}">
                                            <div class="swiper-zoom-container">
                                                <!-- <span class="wishlist"><i class="fa-regular fa-heart"></i></span> -->
                                                <img
                                                    src="{{ asset($swalef->image ?? 'front_assets/imgs/our-services.jpg') }}">
                                                <span class="date-card"> <i
                                                        class="fa-solid fa-calendar-days p-2"></i>{{ $swalef->created_at ? date('d-m-Y', strtotime($swalef->created_at)) : '' }}</span>
                                            </div>
                                            <div class="title-area">
                                                <div class="project-type"><span>{{ $swalef->title ?? '' }}</span></div>
                                                <div class="project-title">
                                                    <span>{{ $swalef->description ? Str::substr($swalef->description, 0, 40) : '' }}</span>
                                                </div>
                                                <div class="arte-type d-flex justify-content-between">
                                                    <span
                                                        class="type-project"><span>{{ $swalef->active ? __('Published') : __('Unpublished') }}</span></span>
                                                    {{-- <span class="rate-project">
                                                        <em class="rating-">
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                            <i class="fa-regular fa-star"></i>
                                                        </em>
                                                    </span> --}}
                                                </div>
                                            </div>
                                            <div class="see-more-n">
                                                <span class="see-m-nd">
                                                    {{__("Explore more")}}
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('scripts')
    @parent
    @if ($swalef->type == 'file')
        <script>
            // If absolute URL from the remote server is provided, configure the CORS
            // header on that server.
            var url = '{{ $swalef->content }}';

            // Loaded via <script> tag, create shortcut to access PDF.js exports.
            var pdfjsLib = window['pdfjs-dist/build/pdf'];

            // The workerSrc property shall be specified.
            pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

            // Asynchronous download of PDF
            var loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function (pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function (page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = document.getElementById('the-canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                        console.log('Page rendered');
                    });
                });
            }, function (reason) {
                // PDF loading error
                console.error(reason);
            });
        </script>
    @endif
@endsection
