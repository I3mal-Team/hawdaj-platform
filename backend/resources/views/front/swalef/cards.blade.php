@foreach ($swalefs as $swalef)
    <div class="card-seation m-2">
        <a href="{{ url(app()->getLocale() . '/swalef/' . $swalef->slug) }}">
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
